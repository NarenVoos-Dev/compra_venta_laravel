<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Compra #{{ $compra->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 10px; text-align: left; }
    </style>
</head>
<body>
    <h1>Detalle de la Compra</h1>
    <p><strong>Proveedor:</strong> {{ $compra->supplier->name }}</p>
    <p><strong>Fecha de compra:</strong> {{ $compra->purchase_date }}</p>
    <p><strong>Total:</strong> ${{ number_format($compra->total_cost, 2) }}</p>

    <h2>Productos</h2>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($compra->detalles as $detalle)
                <tr>
                    <td>{{ $detalle->producto->name }}</td>
                    <td>{{ $detalle->quantity }}</td>
                    <td>${{ number_format($detalle->unit_cost, 2) }}</td>
                    <td>${{ number_format($detalle->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
