<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracionEmpresa;
use Illuminate\Http\Request;
use App\Models\Documento;
use App\Models\Cliente;
use App\Models\Sucursal;
use App\Models\Empresa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Models\Timbre;
// SERVICIO
use App\Services\FacturamaService;
use App\Jobs\DescargarXmlCfdi;



class FacturacionController extends Controller
{
    //
    public function create(Request $request)
    {
        // CONECTAR A LA BASE DE DATOS DEL TENANT
        $empresa = Empresa::findOrFail(1);
        Config::set('database.connections.tenant', [
            'driver' => 'mysql',
            'host' => $empresa->db_host,
            'port' => $empresa->db_port,
            'database' => $empresa->db_database,
            'username' => $empresa->db_username,
            'password' => $empresa->db_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
        ]);


        DB::purge('tenant');
        DB::reconnect('tenant');

        $documento = Documento::where('serie', $request->input('serie'))
            ->where('folio', $request->input('folio'))
            ->where('codigo_unico', $request->input('codigo_unico'))
            ->where('timbrado_online',0)
            ->where('estatus', 1)->first();

        return view('facturas.online', compact('documento'));
    }
    public function store(Request $request,FacturamaService $facturama)
    {
        $request->validate([
            'razon_social' => 'required|string|max:255',
            'rfc' => 'required|string|max:13',
            'regimen_fiscal' => 'required|string|min:2|max:255',
            'usos_cfdi' => 'required|string|min:2|max:255',
            'email' => 'required|email|max:255',
            'cp' => 'required|string|max:6',
            'documento_id' => 'required',
        ]);
        // CONECTAR A LA BASE DE DATOS DEL TENANT
        $empresa = Empresa::findOrFail(1);
        Config::set('database.connections.tenant', [
            'driver' => 'mysql',
            'host' => $empresa->db_host,
            'port' => $empresa->db_port,
            'database' => $empresa->db_database,
            'username' => $empresa->db_username,
            'password' => $empresa->db_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
        ]);
        DB::purge('tenant');
        DB::reconnect('tenant');

        //BUSCAR EL DOCUMENTO EN LA BASE DE DATOS DEL TENANT
        $documento = Documento::with(['cliente', 'detalles.producto'])->findOrFail($request->documento_id);
        $empresa = ConfiguracionEmpresa::first();
        // GENERA EL JSON PARA ENVIAR
        $payload = $this->buildPayload($documento, $empresa,$request);

        //BUSCAR EL CLIENTE
        $cliente=Cliente::where('rfc',$request->rfc)->first();
        //SINO EXISTE EL CLIENTE CREARLO
        if(!$cliente){
            $cliente = Cliente::create([
            'tipo' => 1,
            'codigo' => 'PENDIENTE',
            'nombre' => $request->razon_social,
            'rfc' => $request->rfc,
            'email1' => $request->email,
            'regimen_fiscal' => $request->regimen_fiscal
        ]);
        }
        //ASIGNAR ID
        $id_cliente = $cliente->id;

        try {
            //REALIZA EL TIMBRADO
            $response = $facturama->crearCfdi($payload);
            //Obtener la respuesta de la factura

            $uuid = $response['Complement']['TaxStamp']['Uuid'] ?? null;
            $facturamaId = $response['Id'] ?? null;

            dispatch(new DescargarXmlCfdi(
                $facturamaId,
                $uuid
            ));

            // ACTUALIZAR ESTADO DE LA REMISON CONVERTIDA EN LINEA
            $documento->update([
                'estatus' => '4',
                'timbrado_online'=>1,
            ]);

            //CONTEO DE  TIMBRES
            $timbre = Timbre::first();
            $timbre->update([
                'utilizados' => $timbre->utilizados + 1
            ]);

            DB::beginTransaction();
            $sucursal=Sucursal::where('id',$documento->sucursal_id)->first();
            $serie=$sucursal->serie_factura;
            $ultimoFolio = Documento::where('serie', $serie)
                ->lockForUpdate()
                ->max('folio');

            $siguienteFolio = $ultimoFolio ? $ultimoFolio + 1 : 1;

            //CREAR EL DOCUMENTO EN FACTURACION
            $documento_convertido = Documento::create([
                'sucursal_id'         => $documento->sucursal_id,
                'documento_modelo_id' => 2,//FACTURA
                'serie'               => $serie,
                'folio'               => $siguienteFolio,
                'fecha'               => now(),
                'cliente_id'          => $id_cliente,
                'almacen_id'          => $documento->almacen_id,
                'user_id'             => $documento->user_id,
                'subtotal'            => $documento->subtotal,
                'impuestos'           => $documento->impuestos,
                'total'               => $documento->total,
                'estatus'             => 4,
                'metodo_pago'         => $documento->metodo_pago,
                'forma_pago'          => $documento->forma_pago,
                'uso_cfdi'            => $request->usos_cfdi,
                'observaciones'       => $documento->observaciones,
                'saldo_pendiente'       => 0,
                'descuentos'       =>  $documento->descuentos,
                'agente_id'           =>$documento->agente_id,
                'timbrado_online' => 1,
            ]);
            //CREAR LOS DETALLES DEL DOCUMENTO
            foreach ($documento->detalles as $detalle) {
                $documento_convertido->detalles()->create([
                    'producto_id'   => $detalle->producto_id,
                    'cantidad'      => $detalle->cantidad,
                    'costo_unitario' => $detalle->costo_unitario,
                    'importe'       => $detalle->importe,
                    'descuento'       => $detalle->descuento,
                ]);
            }
            //ASIGNAR LOS DATOS DE FACTURACION
            $documento_convertido->update([
                'facturama_id' => $facturamaId,
                'uuid' => $uuid,
                'cadena_original' => $response['OriginalString'],
                'timbrado_online'=>1,
            ]);
            DB::commit();

            return redirect()
                ->back()
                ->with('success', '📧 La factura fue timbrada correctamente');

                } catch (\Throwable $e) {
            flash()
                ->option('position', 'top-right')
                ->option('timeout', 5000)
                ->option('direction', 'top')
                ->error($e->getMessage());
            return back();
        }

        }

    //CONSTRUIR JSON
        private function buildPayload($documento, $empresa,$request)
    {
        $receiver = [
            "Rfc" => $request->rfc,
            "Name" => $request->razon_social,
            "CfdiUse" =>$request->usos_cfdi,
            "FiscalRegime" => $request->regimen_fiscal,
            "TaxZipCode" => $request->cp,
        ];

        $payload = [
            "Currency" => "MXN",
            "ExpeditionPlace" => $empresa->cp,
            "CfdiType" => "I",
            "PaymentForm" => $documento->forma_pago,   // 01, 03, etc
            "PaymentMethod" => $documento->metodo_pago, // PUE / PPD
            "Date"  =>  now()->format('Y-m-d\TH:i:s'),
            "Folio" =>  $documento->folio,

            "Receiver" => $receiver,

            "Items" => $documento->detalles->map(function ($d) {
                return [
                    "ProductCode" => $d->producto->clave_sat,
                    "IdentificationNumber" => $d->producto->codigo_producto,
                    "Description" => $d->producto->nombre_producto,
                    "Unit" => $d->producto->unidad->descripcion,
                    "UnitCode" => $d->producto->unidad->clave,
                    "UnitPrice" => $d->costo_unitario,
                    "Quantity" => $d->cantidad,
                    "Subtotal" => $d->importe,
                    "TaxObject" => "02",
                    "Taxes" => [
                        [
                            "Name" => "IVA",
                            "Rate" => 0.16,
                            "Base" => $d->importe,
                            "Total" => round($d->importe * 0.16, 2),
                            "IsRetention" => false
                        ]
                    ],

                    "Total" => round($d->importe * 1.16, 2),
                ];
            })->toArray(),
        ];
        // SI ES PUBLICO EN GENERAL
        if ($documento->cliente->rfc === 'XAXX010101000') {
            $payload['GlobalInformation'] = [
                "Periodicity" => "04",
                "Months" => now()->format('m'),
                "Year" => now()->year,
            ];
        }
        return $payload;
    }
}
