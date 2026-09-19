<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    public array $roles = ['admin', 'supervisor', 'operario', 'ventas'];

    public function index(Request $request)
    {
        $q = $request->input('q');
        $usuarios = User::query()
            ->when($q, fn ($query) => $query->where('name', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('modules.usuarios.index', compact('usuarios', 'q'));
    }

    public function create()
    {
        return view('modules.usuarios.form', ['usuario' => new User(), 'roles' => $this->roles]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:'.implode(',', $this->roles)],
            'position' => ['nullable', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);
        $data['password'] = Hash::make($data['password']);
        $data['is_active'] = $request->boolean('is_active');
        User::create($data);

        return redirect()->route('usuarios.index')->with('ok', 'Usuario creado correctamente.');
    }

    public function edit(User $usuario)
    {
        return view('modules.usuarios.form', ['usuario' => $usuario, 'roles' => $this->roles]);
    }

    public function update(Request $request, User $usuario)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($usuario->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', 'in:'.implode(',', $this->roles)],
            'position' => ['nullable', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $data['is_active'] = $request->boolean('is_active');
        $usuario->update($data);

        return redirect()->route('usuarios.index')->with('ok', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $usuario)
    {
        if ($usuario->id === auth()->id()) {
            return redirect()->route('usuarios.index')->with('error', 'No puedes eliminar tu propia cuenta.');
        }
        $usuario->delete();

        return redirect()->route('usuarios.index')->with('ok', 'Usuario eliminado.');
    }
}
