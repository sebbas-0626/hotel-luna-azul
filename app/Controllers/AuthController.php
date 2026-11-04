<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Models\Usuario;

// Controlador de autenticación, se encarga de manejar el login, logout y obtener información del usuario autenticado
class AuthController extends Controller
{
    public function login($request)
    {
        $data = $request->all();
        $this->validate($data, ['email' => 'required|email', 'password' => 'required']);

        $user = Usuario::where('email', $data['email'])->first();
        if (!$user || !password_verify($data['password'], $user->password)) {
            Response::json(['error' => 'Credenciales inválidas'], 401);
        }
        $_SESSION['user'] = [
            'id' => $user->id,
            'nombre' => $user->nombre,
            'email' => $user->email,
            'rol' => $user->rol,
        ];
        Response::json(['user' => $_SESSION['user']]);
    }

    public function logout($request)
    {
        $_SESSION = [];
        session_destroy();
        Response::json(['message' => 'Sesión cerrada']);
    }

    public function me($request)
    {
        if (empty($_SESSION['user'])) {
            Response::json(['user' => null]);
        }
        Response::json(['user' => $_SESSION['user']]);
    }
}
