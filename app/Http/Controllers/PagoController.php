<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracionEmpresa;
use App\Models\Pago;
use App\Models\Cliente;
use App\Models\Documento;
use App\Models\PagosDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


class PagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        //
        $documentos = Pago::query();
        $documentos = $documentos->paginate(10);
        return view('pagos.index', compact('documentos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pagos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'proveedor_id' => 'required|integer',
            'user_id' => 'required',
            'forma_pago' => 'required|string|max:255',
            'facturas' => 'required',
            //DATOS DE DOMICLIO
            'colonia' => 'required|string|max:100',
            'calle' => 'required|string|max:255',
            'numero_exterior' => 'nullable|string|max:50',
            'codigo_postal' => 'required|string|max:6',
        ]);

        try {
            DB::beginTransaction();
            //calcular monto total de pago
            $facturas = json_decode($request->facturas, true);
            $montoTotal = 0;
            foreach ($facturas as $factura => $value) {
                $montoTotal += $value['monto'];
            }
            $folio=Pago::max('folio');
            $pago = Pago::create([
                'cliente_id' => $request->proveedor_id,
                'user_id' => $request->user_id,
                'fecha' => $request->fecha,
                'folio' =>  $folio+1,
                'forma_pago' => $request->forma_pago,
                'monto' => $montoTotal,
                'estatus' => 1,
            ]);

            //ALMACENAR LOS DETALLES DEL PAGO
            foreach ($facturas as $item) {
                // dd($item);
                PagosDetalle::create([
                    'pago_id' => $pago->id,
                    'documento_id' => $item['id'],
                    'monto' => $item['monto'],
                ]);
            }
            //CREAR DOMICILIO RELACIONADO ASIGNAR DOMICILIO AL DOCUMENTO
            $pago->domicilios()->create([
                'pais' => 'MEXICO',
                'estado' => $request->estado,
                'municipio' => $request->municipio . '',
                'ciudad' => $request->ciudad ?? '',
                'colonia' => $request->colonia,
                'calle' => $request->calle,
                'numero_exterior' => $request->numero_exterior,
                'cp' => $request->codigo_postal
            ]);
            DB::commit();

            return redirect()->route('pagos.show', $pago)->with('success', 'Pago creado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Ocurrió un error al crear el pago: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($pago)
    {
        //Buscar pago
        $documento = Pago::findOrFail($pago);
        $documento->load([
            'cliente',
            'detalles.documento',
        ]);
        return view('pagos.show', compact('documento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($pago)
    {
        $documento = Pago::findOrFail($pago);
        if ($documento->estatus != 1) {
            return redirect()
                ->route('pagos.edit', $documento)
                ->with('error', 'El pago ya fue timbrado');
        }

        $documento->load([
            'cliente',
            'detalles.documento',
            'domicilios'
        ]);
        return view('pagos.edit', compact('documento'));
    }


    public function update(Request $request, $documento)
    {
        //
        $request->validate([
            'fecha' => 'required|date',
            'proveedor_id' => 'required|integer',
            'user_id' => 'required',
            'forma_pago' => 'required|string|max:255',
            'facturas' => 'required',
            //DATOS DE DOMICLIO
            'colonia' => 'required|string|max:100',
            'calle' => 'required|string|max:255',
            'numero_exterior' => 'nullable|string|max:50',
            'codigo_postal' => 'required|string|max:6',
        ]);
        try {
            DB::beginTransaction();
            $pago = Pago::findOrFail($documento);
            //calcular monto total de pago
            $facturas = json_decode($request->facturas, true);
            $montoTotal = 0;

            foreach ($facturas as $factura => $value) {
                $montoTotal += $value['monto'];
            }

            //ACTUALIZAR PAGO
            $pago->update([
                'cliente_id' => $request->proveedor_id,
                'user_id' => $request->user_id,
                'fecha' => $request->fecha,
                'forma_pago' => $request->forma_pago,
                'monto' => $montoTotal,
                'estatus' => 1,
            ]);

            /* ================= DETALLES ================= */

            $facturasData = json_decode($request->facturas, true);
            foreach ($facturasData as $item) {
                PagosDetalle::where('id', $item['id'])->update([
                    'monto' => $item['monto'],
                ]);
            }

            // $detallesEnFormulario[] = $detalle->id;
            DB::commit();

            return redirect()->route('pagos.show', ['documento' => $pago])->with('success', 'REP Actualizado correctamente');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($documento)
    {
        try {
            DB::beginTransaction();
            $documento = Pago::findOrFail($documento);
            // Eliminar detalles
            $documento->detalles()->delete();
            // Eliminar documento
            $documento->delete();
            DB::commit();

            return redirect()
                ->route('pagos.index')
                ->with('success', 'Documento eliminado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    public function pdf($documento){
        $documento = Pago::findOrFail($documento);
        $empresa=ConfiguracionEmpresa::first();
        $documento->load([
            'cliente',
            'detalles.documento'
        ]);

        $datosXML = '';
        $qr = '';
        $pdf = Pdf::loadView('pagos.pdf', ['documento'=>$documento, 'empresa'=>$empresa,'qr'=>$qr, 'datosXML'=>$datosXML])->setPaper('letter');

        return $pdf->stream("REP_{$documento->folio}.pdf");
    }

    public function timbrar($documento){
        //REP
        $pago = Pago::findOrFail($documento);
        $pago->load([
            'cliente',
            'detalles.documento'
        ]);

        // BUSCAR CLIENTE y restarle el moto del pago
        $cliente=Cliente::findOrFail($pago->cliente_id);
        $cliente->update(['saldo'=>$cliente->saldo-$pago->monto]);

        //ITERAR POR LAS FACTURAS
        foreach($pago->detalles as $detalle){
            //ENCONTRAR EL DOCUMENTO
            $documento=Documento::find( $detalle->documento_id );
            //RESTARLE AL SALDO PENDIENTE EL DOCUMENTO
            $documento->update(['saldo_pendiente'=>$documento->saldo_pendiente - $detalle->monto]);

        }
        // CAMBIAR ESTATUS
        $pago->update(['estatus'=>4]);
        return redirect()
                ->route('pagos.show', $pago->id)
                ->with('success', 'Aplicado los saldos correctamente');


        //TIMBRADO
    }

    public function clientesPorPagar(Request $request){
    $search = $request->get('search');

    $clientes = Cliente::where('tipo', 1)
        ->where('saldo', '>',0)
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                ->orWhere('rfc', 'like', "%{$search}%")
                ;
            });
        })
        ->orderBy('id', 'desc')
        ->paginate(10)
        ->withQueryString(); // ← mantiene el search en la paginación

    return view('pagos.pendiente', compact('clientes', 'search'));    }
}
