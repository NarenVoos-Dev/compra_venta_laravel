<?php

namespace App\Http\Controllers;
use App\Models\Customer;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    //Cargar la vista de index
    public function index()
    {
        $customers = Customer::all();
        return view('customers.index', compact('customers'));
    }
    //Cargar la vista de crear cliente
    public function create()
    {
        return view('customers.create');
    }

    //Crear cliente nuevo
    public function store(Request $request)
    {
        // Validar los datos
        $request->validate([
            'document_identification' => 'required|string|max:50|unique:customers,document_identification',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|in:active,inactive'
        ]);
    
        // Crear un nuevo cliente
        $customer = new Customer();
        $customer->document_identification = $request->document_identification;
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->status = $request->status;
    
        // Guardar la imagen si se sube
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('customers', 'public');
            $customer->photo = $photoPath;
        }
    
        // Guardar en la base de datos y verificar si falla
        if ($customer->save()) {
            return redirect()->route('customers.index')->with('success', 'Cliente creado exitosamente.');
        } else {
            return back()->with('error', 'Error al guardar el cliente.');
        }
    }
    
    
    



    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        $customer = Customer::findOrFail($id);
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        // Buscar el cliente antes de la validación
        $customer = Customer::findOrFail($id);
    
        $request->validate([
            'document_identification' => 'required|string|max:50|unique:customers,document_identification,' . $customer->id,
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email,' . $customer->id,
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|in:active,inactive'
        ]);
    
        // Obtener los datos del formulario
        $data = $request->except('photo');
    
        // Verificar si se subió una nueva imagen
        if ($request->hasFile('photo')) {
            // Depuración: Verificar si Laravel detecta la imagen
            if (!$request->file('photo')->isValid()) {
                return back()->with('error', 'Error al subir la imagen.');
            }
    
            // Si el cliente ya tiene una imagen, eliminar la anterior
            if ($customer->photo) {
                \Storage::disk('public')->delete($customer->photo);
            }
    
            // Guardar la nueva imagen y almacenar la ruta
            $photoPath = $request->file('photo')->store('customers', 'public');
            $data['photo'] = $photoPath;
        }
    
        // Actualizar cliente
        $customer->update($data);
    
        return redirect()->route('customers.index')->with('success', 'Cliente actualizado correctamente.');
    }
      


    // Eliminar cliente
    public function destroy($id)
    {
        // Buscar el cliente individualmente
        $customer = Customer::where('id', $id)->firstOrFail();
    
        // Eliminar la foto del cliente si existe
        if ($customer->photo) {
            \Storage::disk('public')->delete($customer->photo);
        }
    
        // Eliminar el cliente
        $customer->delete();
    
        return redirect()->route('customers.index')->with('success', 'Cliente eliminado correctamente.');
    }
}
