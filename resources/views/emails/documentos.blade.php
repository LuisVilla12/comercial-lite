<p>{{ match ($documento->documento_modelo_id) {
    1 => 'Cotización',
    2 => 'Factura',
    3 => 'Remisión',
} }}
    {{ $documento->serie . ' #' . $documento->id }}</p>

<p>Estimado: {{ $documento->cliente->nombre }},</p>

<p>Le proporcionamos su {{ match ($documento->documento_modelo_id) {
    1 => 'Cotización',
    2 => 'Factura',
    3 => 'Remisión',
} }}.</p>

<ul>
    <li>Total: <strong>${{ number_format($documento->total, 2) }}</strong></li>
    <li>Fecha: {{ $documento->created_at->format('d/m/Y') }}</li>
</ul>

<p>Gracias por su preferencia.</p>
