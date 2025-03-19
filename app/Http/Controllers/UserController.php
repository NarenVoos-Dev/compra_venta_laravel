<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;

use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index()
    {
        $users = User::with('role')->get();
        return view('usuarios.index', compact('users'));
    }


    public function create()
    {
        $roles = Role::all(); // Obtener todos los roles
        return view('usuarios.create', compact('roles'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'role_id' => 'required|exists:roles,id'
        ]);

        $data = $request->except('password', 'photo');
        $data['password'] = Hash::make($request->password); // Encriptar contraseña

        // Guardar la imagen si se sube
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('users', 'public');
            $data['photo'] = $photoPath;
        }

        User::create($data);

        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
    }


    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {   
        $roles = Role::all();
        $user = User::find($id);
        return view('usuarios.edit', compact('user', 'roles'));
    }

 
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'role_id' => 'required|exists:roles,id'
        ]);

        $data = $request->except('password', 'photo');

        // Si se proporciona una nueva contraseña, la encriptamos
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Manejo de la imagen si se actualiza
        if ($request->hasFile('photo')) {
            if ($user->photo) {
                \Storage::disk('public')->delete($user->photo);
            }
            $photoPath = $request->file('photo')->store('users', 'public');
            $data['photo'] = $photoPath;
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
    }



    public function destroy(User $user)
    {
        // Eliminar la foto del usuario si existe
        if ($user->photo) {
            \Storage::disk('public')->delete($user->photo);
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
