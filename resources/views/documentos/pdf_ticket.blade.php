<style>
    @page {
        margin: 0;
    }
    body {
        font-family: monospace;
        font-size: 10px;
        margin: 0;
        padding: 5px;
    }

    .center {
        text-align: center;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    td {
        padding: 2px 0;
    }
</style>

<div class="center">
    <strong>MI EMPRESA</strong><br>
    RFC: XXX<br>
    -------------------------
</div>

<table>
@foreach($documento->detalles as $item)
    <tr>
        <td>{{ $item->producto->nombre_producto }}</td>
        <td align="right">{{ $item->cantidad }}</td>
        <td align="right">${{ number_format($item->importe, 2) }}</td>
    </tr>
@endforeach
</table>

<div class="center">
    -------------------------<br>
    TOTAL: ${{ number_format($documento->total, 2) }}
</div>
