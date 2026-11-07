<?php

namespace App\Http\Controllers;
use App\Http\Requests\RecuperarPasswordRequest;
use App\Mail\RecuperarMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

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

        if(User::estaActivo($credenciales['email']))
        {
            if(Auth::attempt($credenciales)) {
                $request->session()->regenerate();
                return redirect()->intended(route('home'))->with('success', '¡Bienvenido de nuevo!');
            }
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

    public function cambiarPassword()
    {
        return view('login.olvide-password');
    }

    public function enviarCorreoPassword(RecuperarPasswordRequest $request)
    {
        $request->validated();

        // Buscar al usuario
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // 1. Generamos un token aleatorio seguro
            $token = Str::random(64);

            // 2. Guardamos/Actualizamos el token en TU tabla (encriptado por seguridad)
            DB::table('contrasenha_tokens_resetear')->updateOrInsert(
                ['email' => $request->email],
                [
                    'token' => Hash::make($token),
                    'creado_hace' => Carbon::now() // Usando tu columna personalizada
                ]
            );

            // 3. Construimos la URL
            $url = route('password.reset', [
                'token' => $token,
                'email' => $request->email
            ]);

            // 4. Enviamos el correo
            Mail::to($request->email)->send(new RecuperarMail($url));
        }

        return back()->with('success', 'Si el correo está registrado, te hemos enviado un enlace de recuperación.');
    }

    public function vistaRestablecer(Request $request, $token)
    {
        return view('login.cambiar-contraseña', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function actualizarPassword(Request $request)
    {
        // 1. Validar los datos del formulario (la vista que hicimos antes)
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed', // Laravel buscará 'password_confirmation'
        ], [
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.'
        ]);

        // 2. Buscar el registro en tu tabla de tokens
        $resetRecord = DB::table('contrasenha_tokens_resetear')
            ->where('email', $request->email)
            ->first();

        // 3. Validar: Que exista, que el token coincida y que no tenga más de 60 minutos
        if (!$resetRecord ||
            !Hash::check($request->token, $resetRecord->token) ||
            Carbon::parse($resetRecord->creado_hace)->addMinutes(60)->isPast()) {

            return back()->withErrors(['email' => 'El enlace de recuperación es inválido o ha expirado.']);
        }

        // 4. Si todo está bien, buscar el usuario y actualizar SU columna 'contrasenha'
        $user = User::where('email', $request->email)->first();

        $user->update([
            'contrasenha' => Hash::make($request->password) // Usamos tu columna
        ]);

        // 5. Borrar el token ya usado por seguridad
        DB::table('contrasenha_tokens_resetear')->where('email', $request->email)->delete();

        // 6. Redirigir al login
        return redirect()->route('login')->with('success', 'Tu contraseña ha sido restablecida exitosamente. Ya puedes iniciar sesión.');
    }
}
