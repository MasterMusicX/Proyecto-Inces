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
public function register(Request $request)
    {
        // 1. AQUÍ VAN LAS REGLAS (El filtro de seguridad completo)
        $request->validate([
            'name'      => 'required|string|min:2|max:255',
            'last_name' => 'required|string|min:2|max:255', // <-- Faltaba el apellido
            'cedula'    => 'required|string|min:6|max:10|unique:users', // <-- Faltaba la cédula
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => ['required', 'confirmed', PasswordRule::defaults()], 
            'gender'    => 'required|in:M,F',
            'avatar'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // <-- Faltaba la foto
        ]);

        // Procesamos la foto si el estudiante subió una
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        // 2. AQUÍ SE GUARDA EL DATO (Lo que va a PostgreSQL)
        User::create([
            'name'      => trim($request->name),
            'last_name' => trim($request->last_name),
            'cedula'    => trim($request->cedula),
            'email'     => strtolower(trim($request->email)),
            'password'  => Hash::make($request->password),
            'role'      => 'student',
            'is_active' => true,
            'gender'    => $request->gender,
            'avatar'    => $avatarPath, // <-- Guardamos la ruta de la foto si existe
        ]);

        // 3. AQUÍ SE RESPONDE (Lo que ve el usuario después de enviar el formulario)
        return back()->with('success', '¡Cuenta creada exitosamente, ' . trim($request->name) . '! Ya puedes iniciar sesión.');
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