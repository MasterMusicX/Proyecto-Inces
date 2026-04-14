<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

    public function create() { return view('admin.users.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role'     => 'required|in:admin,instructor,student',
            'phone'    => 'nullable|string|max:20',
            'bio'      => 'nullable|string|max:500',
        ]);

        $data['password'] = Hash::make($data['password']);
        User::create($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    public function edit(User $user)
    {   $user = \App\Models\User::findOrFail($user->id);
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
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado.');
    }

    public function destroy(User $user)
    {
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
