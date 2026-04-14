<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password; // Facade para enviar los correos
use Illuminate\Validation\Rules\Password as PasswordRule; // Alias para evitar conflicto
use Illuminate\Support\Str; // Necesario para generar el token de sesión en el reset

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect($this->redirectByRole(Auth::user()));
        }
        
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Tu cuenta está desactivada. Contacta al administrador.']);
            }

            $user->update(['last_login_at' => now()]);
            $request->session()->regenerate();

            return redirect($this->redirectByRole($user))
                ->with('success', '¡Bienvenido de vuelta, ' . $user->name . '! 👋');
        }

        return back()
            ->withErrors(['email' => 'Las credenciales no son correctas. Verifica tu email y contraseña.'])
            ->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect($this->redirectByRole(Auth::user()));
        }
        
        return view('auth.register');
    }

    /**
     * Registra un nuevo estudiante y muestra página de éxito
     * SIN iniciar sesión automáticamente
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', PasswordRule::defaults()], 
        ]);

        // Creamos el usuario
        User::create([
            'name' => trim($request->name),
            'email' => strtolower(trim($request->email)),
            'password' => Hash::make($request->password),
            'role' => 'student',
            'is_active' => true,
        ]);

        // NO iniciamos sesión — redirigimos a página de éxito
        return view('auth.register-success', [
            'name' => $request->name,
            'email' => $request->email,
        ]);
    }

    /**
     * 1. Enviar el enlace de recuperación de contraseña
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', '¡Te hemos enviado el enlace de recuperación a tu correo!');
        }

        return back()->withErrors(['email' => 'No pudimos encontrar un usuario con esa dirección de correo.']);
    }

    /**
     * 2. Muestra el formulario para escribir la nueva contraseña
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
     * 3. Procesa la nueva contraseña y la actualiza en la base de datos
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', '¡Tu contraseña ha sido restablecida con éxito!');
        }

        return back()->withErrors(['email' => [__($status)]]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')
            ->with('success', 'Has cerrado sesión correctamente.');
    }

    protected function redirectByRole(User $user): string
    {
        return match($user->role) {
            'admin' => route('admin.dashboard'),
            'instructor' => route('instructor.dashboard'),
            default => route('student.dashboard'),
        };
    }
}