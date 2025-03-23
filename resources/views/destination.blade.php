<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Seleccionar Destino</title>
</head>
<body>
    <h1>Selecciona tu destino</h1>

    @if(session('error'))
        <p style="color:red;">{{ session('error') }}</p>
    @endif

    <form action="{{ route('carrito.init') }}" method="POST">
        @csrf
        <label for="region">Región:</label>
        <select id="region" name="region">
            <option value="">Selecciona una región</option>
            @foreach($regions as $region)
                <option value="{{ $region['code'] }}">{{ $region['region'] }}</option>
            @endforeach
        </select>

        <label for="comuna">Comuna:</label>
        <select id="comuna" name="comuna" disabled>
            <option value="">Selecciona una comuna</option>
        </select>

        <button type="submit">Continuar</button>
    </form>

    <script>
        document.getElementById('region').addEventListener('change', function () {
            let selectedRegion = this.value;
            let comunasDropdown = document.getElementById('comuna');

            
            comunasDropdown.innerHTML = '<option value="">Selecciona una comuna</option>';

            if (selectedRegion) {
                let regiones = @json($regions);
                let comunas = regiones.find(r => r.code == selectedRegion)?.comunas || [];

                comunas.forEach(comuna => {
                    let option = document.createElement('option');
                    option.value = comuna;
                    option.textContent = comuna;
                    comunasDropdown.appendChild(option);
                });

                comunasDropdown.disabled = false;
            } else {
                comunasDropdown.disabled = true;
            }
        });
    </script>

    <br>
    <a href="{{ route('logout') }}">Cerrar sesión</a>
</body>
</html>
