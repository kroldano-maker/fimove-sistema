<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    protected array $roles = [
        'administrador' => 'Administrador',
        'personal_administrativo' => 'Personal administrativo',
        'fisioterapeuta' => 'Fisioterapeuta',
    ];

    public function index()
    {
        $usuarios = User::orderBy('estado', 'desc')->orderBy('name')->get();

        return view('usuarios.index', [
            'usuarios' => $usuarios,
            'roles' => $this->roles,
        ]);
    }

    public function create()
    {
        return view('usuarios.create', ['roles' => $this->roles]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'rol' => ['required', Rule::in(array_keys($this->roles))],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        User::create([
            'name' => $validated['name'],
            'apellido' => $validated['apellido'],
            'email' => $validated['email'],
            'rol' => $validated['rol'],
            'password' => Hash::make($validated['password']),
            'estado' => 1,
        ]);

        return redirect()
            ->route('usuarios.index')
            ->with('status', 'Usuario creado correctamente.');
    }

    public function edit(User $usuario)
    {
        return view('usuarios.edit', [
            'usuario' => $usuario,
            'roles' => $this->roles,
        ]);
    }

    public function update(Request $request, User $usuario)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($usuario->id)],
            'rol' => ['required', Rule::in(array_keys($this->roles))],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        $usuario->name = $validated['name'];
        $usuario->apellido = $validated['apellido'];
        $usuario->email = $validated['email'];
        $usuario->rol = $validated['rol'];

        if (! empty($validated['password'])) {
            $usuario->password = Hash::make($validated['password']);
        }

        $usuario->save();

        return redirect()
            ->route('usuarios.index')
            ->with('status', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $usuario)
    {
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes desactivar tu propia cuenta.');
        }

        $usuario->update(['estado' => 0]);

        return redirect()
            ->route('usuarios.index')
            ->with('status', 'Usuario desactivado correctamente.');
    }

    public function activar(User $usuario)
    {
        $usuario->update(['estado' => 1]);

        return redirect()
            ->route('usuarios.index')
            ->with('status', 'Usuario activado correctamente.');
    }
}