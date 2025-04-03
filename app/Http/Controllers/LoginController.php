<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Muestra el formulario de login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Maneja el login
    public function login(Request $request)
    {
        // Validación de las credenciales
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Intentar autenticar al usuario
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Redireccionar al usuario a la pagina de inicio
            return redirect()->intended('/')->with('success', 'Has iniciado sesión!');
        }
    }

    // Maneja el logout
    public function logout(Request $request)
    {
        // Cierra la sesión del usuario
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Has salido de la sesión!');
    }
}