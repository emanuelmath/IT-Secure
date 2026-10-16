<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;

class LoginController extends Controller
{
    public function index()
    {
        if(Auth::check()) {
            return redirect()->route('home');
        }
        return view('login.index');
    }

    public function login(LoginRequest $request)
    {
        $credenciales = [
            'email' => $request->email,
            'password' => $request->contrasenha
        ];

        if(Auth::attempt($credenciales)) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'))->with('success', '¡Bienvenido de nuevo!');
        }

        return redirect()->route('login')->with('error', 'Las credenciales son incorrectas.');
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login')->with('success', '¡Hasta la próxima!');
    }
}
