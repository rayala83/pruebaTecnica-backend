<?php
namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService; // Inyectamos el servicio para interactuar con la API externa
    }

    // Mostrar el formulario de login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Procesar el login
    public function login(Request $request)
    {
        // Validar las credenciales
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            // Intentar autenticar al usuario usando las credenciales
            $token = $this->authService->authenticate($request->email, $request->password);

            // Almacenar el token en la sesión
            Session::put('auth_token', $token);

            // Redirigir al usuario a la página de selección de destino
            return redirect()->route('destination.showForm');
        } catch (\Exception $e) {
            // Si ocurre un error, mostrar el mensaje
            return redirect()->back()->withErrors(['error' => 'Credenciales inválidas o error en la autenticación.']);
        }
    }
}
