<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Models\Producto;

class ApiController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            $token = $this->authService->authenticate($request->username, $request->password);
            return response()->json(['token' => $token]);
        }catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    public function logout()
    {
        
        session()->forget('carrito');
        auth()->logout();
        return redirect()->route('login');
    }

    public function regionalConfig(Request $request)
    {
        $authHeader = $request->header('Authorization');
    
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return response()->json(['error' => 'Token no proporcionado o formato incorrecto'], 400);
        }
    
        $token = substr($authHeader, 7);
    
        try {
            $config = $this->authService->getRegionalConfig($token);
            return response()->json($config);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function rate(Request $request)
    {
        
        $request->validate([
            'comuna' => 'required|string',
            'products' => 'required|array',
            'products.*.weight' => 'required|numeric',
            'products.*.quantity' => 'required|integer',
        ]);

        $authHeader = $request->header('Authorization');
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return response()->json(['error' => 'Token no proporcionado o formato incorrecto'], 400);
        }

        $token = substr($authHeader, 7); 

    
        try {
            $rates = $this->authService->getRate($token, $request->comuna, $request->products);
            return response()->json(['rates' => $rates]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function webLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            $token = $this->authService->authenticate($request->username, $request->password);

            
            session(['external_token' => $token]);

            return redirect()->route('destination');
        } catch (\Exception $e) {
            return back()->withErrors(['login' => $e->getMessage()]);
        }
    }

    public function showRegions(Request $request)
    {
        
        $token = session('external_token');
    
        if (!$token) {
            return redirect()->route('login.form')->with('error', 'Debes iniciar sesión para acceder a esta página.');
        }
    
        try {
            
            $regionsData = $this->authService->getRegionalConfig($token);
    
            return view('destination', ['regions' => $regionsData]);
    
        } catch (\Exception $e) {
            return redirect()->route('login.form')->with('error', 'Error al obtener las regiones. Inténtalo de nuevo.');
        }
    }

    public function iniciarCarrito(Request $request)
    {
        session(['selected_region' => $request->region]);
        session(['selected_comuna' => $request->comuna]);

        return redirect()->route('carrito.index');
    }

    public function showCarrito()
    {
        $productos = Producto::all();
        

        return view('carrito.index', compact('productos'));
    }

    public function agregarAlCarrito(Request $request)
    {
        
        $productosSeleccionados = $request->input('productos');
    
        
        $carrito = session('carrito', []);
    
        foreach ($productosSeleccionados as $productoId => $datos) {
            
            $producto = Producto::find($productoId);
            
            if ($producto) {
                
                $existeEnCarrito = false;
                foreach ($carrito as &$item) {
                    if ($item['producto_id'] == $producto->id) {
                        
                        $item['quantity'] += $datos['quantity'];
                        $existeEnCarrito = true;
                        break;
                    }
                }
    
                
                if (!$existeEnCarrito) {
                    $carrito[] = [
                        'producto_id' => $producto->id,
                        'name' => $producto->name,
                        'quantity' => $datos['quantity'],
                        'weight' => $datos['weight'],
                    ];
                }
            }
        }
    
        
        session()->put('carrito', $carrito);
    
        
        return redirect()->route('carrito.index');
    }

    public function solicitarTarifas(Request $request)
    {
        
        $token = session('external_token');
       

        $request->validate([
            'comuna' => 'required|string',
            'products' => 'required|array',
            'products.*.weight' => 'required|numeric',
            'products.*.quantity' => 'required|integer',
        ]);

        // $authHeader = $request->header('Authorization');
        // if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
        //     return response()->json(['error' => 'Token no proporcionado o formato incorrecto'], 400);
        // }

        //$token = substr($authHeader, 7); 
        dd($token);

        try {
            
            $rates = $this->authService->getRate($token, $request->comuna, $request->products);

            session(['tarifas' => $rates]);

            return redirect()->route('carrito.tarifas');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    public function mostrarTarifas()
    {
        $tarifas = session('tarifas');

        return view('carrito.tarifas', compact('tarifas'));
    }
}
    

