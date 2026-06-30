<?php

namespace App\Http\Controllers;

use App\Models\Certificado;
use App\Models\ConfiguracionEmpresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class CertificadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('certificados.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
        $request->validate([
        'cer' => 'required|file',
        'key' => 'required|file',
        'password' => 'required'
    ]);

    $empresa=ConfiguracionEmpresa::first();
    $cer = $request->file('cer')
        ->store("certificates/{$empresa->rfc}");

    $key = $request->file('key')
        ->store("certificates/{$empresa->rfc}");

    Certificado::create(
        [
            'cer_path' => $cer,
            'key_path' => $key,
            'key_password' => Crypt::encryptString($request->password),
        ]
    );
    // Enviar a Facturama
    $response = Http::withBasicAuth(
            config('services.facturama.user'),
            config('services.facturama.password')
        )
        ->acceptJson()
        ->post(config('services.facturama.url') . '/csds', [
            'Rfc' => $empresa->rfc,
            'Certificate' => $certificate,
            'PrivateKey' => $privateKey,
            'PrivateKeyPassword' => $request->password,
        ]);

    if (!$response->successful()) {
        // Opcional: eliminar el registro y archivos si falló la carga
        $certificado->delete();
        Storage::delete([$cer, $key]);

        return back()->withErrors([
            'facturama' => $response->json()['Message'] ?? $response->body()
        ]);
    }
            return redirect()->route('configuracion-empresa.show')->with('success', 'Certificados configurados exitosamente.');

    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
        $certificado=Certificado::first();
                return view('certificados.show',['certificado'=>$certificado]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Certificado $certificado)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Certificado $certificado)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Certificado $certificado)
    {
        //
    }
}
