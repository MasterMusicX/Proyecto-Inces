<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user()->load(['enrolledCourses', 'aiQueries']);
        $stats = [
            'enrolled'   => $user->enrollments()->count(),
            'completed'  => $user->enrollments()->where('status', 'completed')->count(),
            'ai_queries' => $user->aiQueries()->count(),
        ];
        return view('student.profile', compact('user', 'stats'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'name'   => 'required|string|max:255',
            'phone'  => 'nullable|string|max:20',
            'bio'    => 'nullable|string|max:500',
            'avatar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) Storage::disk('public')->delete($user->avatar);
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);
        return back()->with('success', 'Perfil actualizado exitosamente.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual no es correcta.']);
        }

        $user->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Contraseña cambiada exitosamente.');
    }
}
