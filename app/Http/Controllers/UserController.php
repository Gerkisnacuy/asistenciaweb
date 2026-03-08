<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    // Muestra la lista de usuarios
    public function index()
    {
        $usuarios = User::all();
        return view('usuarios.index', compact('usuarios'));
    }

    // Muestra el formulario de creación
    public function create()
    {
        return view('usuarios.create');
    }

    // Guarda el nuevo usuario en la base de datos
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:super_admin,admin,assistant'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }
    // Muestra el formulario para editar un usuario
    public function edit(User $usuario)
    {
        return view('usuarios.edit', compact('usuario'));
    }

    // Procesa la actualización de los datos
    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$usuario->id],
            'role' => ['required', 'string', 'in:super_admin,admin,assistant'],
        ]);

        $usuario->update($request->only('name', 'email', 'role'));

        // Si se escribió una nueva contraseña, la actualizamos
        if ($request->filled('password')) {
            $request->validate(['password' => ['confirmed', Rules\Password::defaults()]]);
            $usuario->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    // Elimina al usuario de la base de datos
    public function destroy(User $usuario)
    {
        // Evitar que el Super Admin se elimine a sí mismo
        if ($usuario->id === auth()->id()) {
            return redirect()->route('usuarios.index')->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $usuario->delete();
        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado con éxito.');
    }
}