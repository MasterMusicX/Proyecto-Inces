<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http; // 🔥 Importación necesaria para ImgBB
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role'))   $query->where('role', $request->role);
        if ($request->filled('search')) {
            $query->where(fn($q) =>
                $q->where('name', 'ilike', '%' . $request->search . '%')
                  ->orWhere('email', 'ilike', '%' . $request->search . '%')
            );
        }

        $users = $query->latest()->paginate(15)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function create() 
    { 
        return view('admin.users.create'); 
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role'     => 'required|in:admin,instructor,student',
            'phone'    => 'nullable|string|max:20',
            'bio'      => 'nullable|string|max:500',
            'avatar'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // 🔥 Validación de foto añadida
        ]);

        $data['password'] = Hash::make($data['password']);

        // 🔥 LÓGICA DE IMGBB PARA CREAR USUARIO 🔥
        if ($request->hasFile('avatar')) {
            $imagePath = $request->file('avatar')->getRealPath();
            $imageBase64 = base64_encode(file_get_contents($imagePath));

            $response = Http::asForm()->post('https://api.imgbb.com/1/upload', [
                'key' => config('services.imgbb.key'), // Llamado correcto al config de services
                'image' => $imageBase64,
            ]);

            if ($response->successful()) {
                $data['avatar'] = $response->json('data.url');
            } else {
                // Chismoso de errores activado
                return back()->withInput()->with('error', 'Error ImgBB: ' . $response->body());
            }
        }

        User::create($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    public function edit(User $user)
    {   
        // Limpiamos la búsqueda redundante, $user ya viene cargado
        return view('admin.users.edit', compact('user'));
    }

   public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => ['required', 'email', Rule::unique('users')->ignore($user)],
            'role'      => 'required|in:admin,instructor,student',
            'phone'     => 'nullable|string|max:20',
            'bio'       => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'avatar'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Validación de la foto
        ]);

        // 🔥 LÓGICA DE IMGBB PARA ACTUALIZAR USUARIO 🔥
        if ($request->hasFile('avatar')) {
            $imagePath = $request->file('avatar')->getRealPath();
            $imageBase64 = base64_encode(file_get_contents($imagePath));

            $response = Http::asForm()->post('https://api.imgbb.com/1/upload', [
                'key' => config('services.imgbb.key'), // Llamado correcto al config de services
                'image' => $imageBase64,
            ]);

            if ($response->successful()) {
                // Guardamos el enlace directo de ImgBB
                $data['avatar'] = $response->json('data.url');
            } else {
                // Chismoso de errores activado
                return back()->withInput()->with('error', 'Error ImgBB: ' . $response->body());
            }
        }

        // 2. Lógica de la contraseña (solo si el usuario decidió cambiarla)
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $data['password'] = Hash::make($request->password);
        } else {
            // Si no escribió nada, quitamos 'password' para que no sobreescriba la clave actual
            unset($data['password']);
        }

        $user->update($data);
        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        // Como ahora todo está en ImgBB, eliminamos la lógica del disco duro local
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado.');
    }

    public function toggle(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        $state = $user->is_active ? 'activado' : 'desactivado';
        return back()->with('success', "Usuario {$state}.");
    }
}