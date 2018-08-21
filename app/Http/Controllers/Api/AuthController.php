<?php
namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;

class AuthController extends ApiController
{

    public function loginAction(Request $request)
    {
        $payload = $request->all();
        print_r($payload); echo __FILE__; echo __LINE__; exit;
        
    }
}
