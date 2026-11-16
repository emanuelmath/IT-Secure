<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RecuperarPasswordRequest;
use App\Mail\RecuperarMail;
use App\Models\Bitacora;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('login.index');
    }

    public function login(LoginRequest $request)
    {
        $credenciales = [
            'email' => $request->email,
            'password' => $request->contrasenha,
        ];

        if (User::estaActivo($credenciales['email'])) {
            if (Auth::attempt($credenciales)) {
                $request->session()->regenerate();

                Bitacora::registrar(
                    'Autenticación',
                    Bitacora::eventos[0], // LOGIN_OK
                    'Inicio de sesión exitoso en la plataforma',
                    Auth::id(),
                    Auth::id(),
                    Auth::user()->email
                );

                return redirect()->intended(route('home'))->with('success', '¡Bienvenido de nuevo!');
            }
        }

        Bitacora::registrar(
            'Autenticación',
            Bitacora::eventos[1],
            "Intento fallido de acceso para: {$request->email}",
            null,
            null,
            $request->email
        );

        return redirect()->route('login')->with('error', 'Las credenciales son incorrectas.');
    }

    public function logout()
    {
        $user = Auth::user();

        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        if ($user) {
            Bitacora::registrar(
                'Autenticación',
                Bitacora::eventos[2],
                'Cierre de sesión del usuario',
                $user->id,
                $user->id,
                $user->email
            );
        }

        return redirect()->route('login')->with('success', '¡Hasta la próxima!');
    }

    public function cambiarPassword()
    {
        return view('login.olvide-password');
    }

    public function enviarCorreoPassword(RecuperarPasswordRequest $request)
    {
        $request->validated();

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $token = Str::random(64);

            DB::table('contrasenha_tokens_resetear')->updateOrInsert(
                ['email' => $request->email],
                [
                    'token' => Hash::make($token),
                    'creado_hace' => Carbon::now(),
                ]
            );

            $url = route('password.reset', [
                'token' => $token,
                'email' => $request->email,
            ]);

            Mail::to($request->email)->send(new RecuperarMail($url));
        }

        return back()->with('success', 'Si el correo está registrado, te hemos enviado un enlace de recuperación.');
    }

    public function vistaRestablecer(Request $request, $token)
    {
        return view('login.cambiar-contraseña', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function actualizarPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ], [
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ]);

        $resetRecord = DB::table('contrasenha_tokens_resetear')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord ||
            !Hash::check($request->token, $resetRecord->token) ||
            Carbon::parse($resetRecord->creado_hace)->addMinutes(60)->isPast()) {
            return back()->withErrors(['email' => 'El enlace de recuperación es inválido o ha expirado.']);
        }

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $user->update([
                'contrasenha' => Hash::make($request->password),
            ]);
        }

        DB::table('contrasenha_tokens_resetear')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Tu contraseña ha sido restablecida exitosamente. Ya puedes iniciar sesión.');
    }
}
