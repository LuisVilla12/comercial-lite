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
use App\Jobs\DescargarFacturaAPI;
use App\Jobs\EnviarDocumentoMail;
use App\Services\FacturaApiService;

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
            ->where('codigo_utilizado', 0)
            ->where('estatus', 4)->first();

        return view('facturas.online', compact('documento'));
    }
    public function store(Request $request, FacturaApiService $facturama)
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
        $payload = $this->buildPayload($request, $documento);

        //BUSCAR EL CLIENTE
        $cliente = Cliente::where('rfc', $request->rfc)->first();

        //SINO EXISTE EL CLIENTE CREARLO
        if (!$cliente) {
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

            $uuid = $response['uuid'] ?? null;
            // OBTENER ID de FACTURA
            $facturaID = $response['id'] ?? null;

            // COLA PARA DESCARGAR XML
            dispatch(new DescargarFacturaAPI($facturaID, $uuid));

            // ACTUALIZAR ESTADO DE LA REMISON CONVERTIDA EN LINEA
            $documento->update([
                'estatus' => '4',
                'codigo_utilizado' => 1,
            ]);

            //CONTEO DE  TIMBRES
            $timbre = Timbre::first();
            $timbre->update([
                'utilizados' => $timbre->utilizados + 1
            ]);

            DB::beginTransaction();
            $sucursal = Sucursal::where('id', $documento->sucursal_id)->first();
            $serie = $sucursal->serie_factura;
            $ultimoFolio = Documento::where('serie', $serie)
                ->lockForUpdate()
                ->max('folio');

            $siguienteFolio = $ultimoFolio ? $ultimoFolio + 1 : 1;

            //CREAR EL DOCUMENTO EN FACTURACION
            $documento_convertido = Documento::create([
                'sucursal_id'         => $documento->sucursal_id,
                'documento_modelo_id' => 2, //FACTURA
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
                'agente_id'           => $documento->agente_id,
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
                'facturama_id' => $facturaID,
                'uuid' => $uuid,
                'cadena_original' => $response['stamp']['complement_string'] ?? null,
                'timbrado_online' => 1,
            ]);
            //CREAR UN DOMICILIO
            $documento_convertido->domicilios()->create([
                'pais' => 'MEXICO',
                'estado' => '',
                'municipio' => '',
                'ciudad' => '',
                'colonia' => '',
                'calle' =>  '',
                'numero_exterior' => '',
                'cp' => $request->cp,
            ]);
            DB::commit();
            //EJECUTA LA COLA PARA ENVIAR EL CORREO
            EnviarDocumentoMail::dispatch(
                $empresa->id,
                $sucursal->id,
                $documento_convertido->id,
                $request->email
            );

            return redirect()
                ->route('facturas.online')
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

    public function buildPayload($request, $documento)
    {
        return [
            "payment_form" => $documento->forma_pago,
            "use" => $request->usos_cfdi,
            "customer" => [
                "legal_name" => $request->razon_social,
                "tax_id" =>  $request->rfc,
                "tax_system" => $request->regimen_fiscal,
                "email" => $request->email,
                "address" => [
                    "zip" => $request->cp
                ]
            ],
            "items" => $this->buildItems($documento)
        ];
    }
    private function buildItems($documento)
    {
        $items = [];
        foreach ($documento->detalles as $detalle) {
            $item = [
                "quantity" => (float) $detalle->cantidad,
                "product" => [
                    "description" => $detalle->producto->nombre_producto,
                    "price" => $detalle->costo_unitario,
                    "product_key" => $detalle->producto->clave_sat,
                    "unit_key" => $detalle->producto->unidad->clave,
                    "tax_included" => false,
                    "taxes" => [
                        [
                            "type" => "IVA",
                            "rate" => $detalle->producto->impuesto1 / 100,
                            "factor" => "Tasa"
                        ]
                    ]
                ]
            ];

            if ($detalle->descuento > 0) {
                $item["discount"] = (float) $detalle->descuento;
            }


            $items[] = $item;
        }

        return $items;
    }
}
