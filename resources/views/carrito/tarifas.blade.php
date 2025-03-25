
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tarifas de Envío</title>
</head>
<body>
    <h1>Tarifas de Envío</h1>

    @if(session('tarifas'))
        <table>
            <thead>
                <tr>
                    <th>Comuna</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Peso</th>
                    <th>Tarifa</th>
                </tr>
            </thead>
            <tbody>
                @foreach(session('tarifas') as $rate)
                    <tr>
                        <td>{{ $rate['comuna'] }}</td>
                        <td>{{ $rate['producto'] }}</td>
                        <td>{{ $rate['cantidad'] }}</td>
                        <td>{{ $rate['peso'] }} kg</td>
                        <td>{{ $rate['tarifa'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No se han encontrado tarifas disponibles.</p>
    @endif

    <br>
    <a href="{{ route('carrito.index') }}">Volver al carrito</a>
</body>
</html>
