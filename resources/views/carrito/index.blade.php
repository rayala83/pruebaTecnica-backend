<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Productos</title>
</head>
<body>
    <h1>Carrito de Productos</h1>

    <!-- Mensajes de éxito y error -->
    @if(session('success'))
    <div style="padding: 10px; background-color: #d4edda; color: #155724; margin-bottom: 15px;">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
        <div style="padding: 10px; background-color: #f8d7da; color: #721c24; margin-bottom: 15px;">
            {{ session('error') }}
        </div>
    @endif

    <!-- Mostrar botón "Ir al carrito" -->
    @if(session('show_ir_al_carrito'))
        <div style="margin-bottom: 20px;">
            <a href="{{ route('carrito.index') }}" style="padding: 10px 15px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">
                Ir al carrito
            </a>
        </div>
    @endif

    <!-- Mostrar región y comuna seleccionadas -->
    <p>Región seleccionada: {{ session('selected_region') }}</p>
    <p>Comuna seleccionada: {{ session('selected_comuna') }}</p>

    <h2>Selecciona productos para tu carrito</h2>

    <!-- Mostrar productos disponibles -->
    @foreach($productos as $producto)
        <form action="{{ route('carrito.agregar') }}" method="POST">
            @csrf
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Peso</th>
                        <th>Agregar</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $producto->name }}</td>
                        <td>
                            <input type="number"
                                   name="productos[{{ $producto->id }}][quantity]"
                                   value="1"
                                   min="1"
                                   max="{{ $producto->quantity }}"
                                   required>
                        </td>
                        <td>
                            <input type="number"
                                   name="productos[{{ $producto->id }}][weight]"
                                   value="{{ $producto->weight }}"
                                   step="0.01"
                                   readonly>
                        </td>
                        <td>
                            <button type="submit">Agregar</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    @endforeach

    <!-- Mostrar productos en el carrito -->
    <h2>Productos en tu carrito</h2>
    @if(session('carrito') && count(session('carrito')) > 0)
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Peso</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach(session('carrito') as $productoId => $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>{{ $item['weight'] }} kg</td>
                        <td>{{ $item['quantity'] * $item['weight'] }} kg</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Botón para enviar la solicitud de tarifas de envío -->
        <form action="{{ route('carrito.solicitar_tarifas') }}" method="POST">
            @csrf
            <input type="hidden" name="comuna" value="{{ session('selected_comuna') }}">
            <input type="hidden" name="products" value="{{ json_encode(session('carrito')) }}">
            <button type="submit" style="padding: 10px 15px; background-color: #007bff; color: white; border-radius: 5px;">
                Solicitar tarifas de envío
            </button>
        </form>
    @else
        <p>No hay productos en tu carrito.</p>
    @endif
    <br>
    <a href="{{ route('logout') }}">Cerrar sesión</a>
</body>
</html>
