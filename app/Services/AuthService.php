<?php

namespace app\Services;

use Illuminate\Support\Facades\Http;
class AuthService
{
    private $authUrl = "https://postulaciones.amplifica.io/auth";
    private $regionalConfigUrl = "https://postulaciones.amplifica.io/regionalConfig";
    private $rateUrl = "https://postulaciones.amplifica.io/getRate";


    public function authenticate($username, $password)
    {
        $response = Http::post($this->authUrl,[
            'username' => $username,
            'password' => $password,
        ]);

        if($response->successful()){
            return $response->json()['token'];
        }

        throw new \Exception('Autentificacion fallida');
    }

    public function getRegionalConfig($token)
    {
        $response = Http::withHeaders([
            'Authorization' => "Bearer $token"
        ])->get($this->regionalConfigUrl);
    
        
        if ($response->successful()) {
            return $response->json();
        } else {            
            throw new \Exception('Error al obtener la configuración regional. ');
        }
    }

    public function getRate($token, $comuna, $products)
    {
        $response = Http::withHeaders([
            'Authorization' => "Bearer $token"
        ])->post($this->rateUrl, [
            'comuna' => $comuna,
            'products' => $products
        ]);

        if ($response->successful()) {
            return $response->json();
        } else {            
            throw new \Exception('Error al obtener la configuración regional. ');
        }
    }
    
}