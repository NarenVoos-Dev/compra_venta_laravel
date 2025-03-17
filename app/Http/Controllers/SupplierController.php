<?php

namespace App\Http\Controllers;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    //Vista de proveedores en el index
    public function index()
    {
        $suppliers = Supplier::all();
        return view('suppliers.index', compact('suppliers'));
    }

    //mostrar formulario para crear proveedor
    public function create()
    {
        return view('suppliers.create');
    }


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

        // Crear un nuevo proveedor y guardar en la base de datos
        $supplier = new Supplier();
        $supplier->document_identification = $request->document_identification;
        $supplier->name = $request->name;
        $supplier->email = $request->email;
        $supplier->phone = $request->phone;
        $supplier->address = $request->address;
        $supplier->status = $request->status;

        // Guardar la imagen si se sube
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('suppliers', 'public');
            $supplier->photo = $photoPath;
        }

        // Guardar en la base de datos y verificar si falla
        if ($supplier->save()) {
            return redirect()->route('suppliers.index')->with('success', 'Proveedor creado exitosamente.');
        } else {
            return back()->with('error', 'Error al guardar el proveedor.');
        }
    }

 
    public function show(string $id)
    {
        //
    }

   
    public function edit(string $id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, string $id)
    {
        // Buscar el proveedor antes de la validación
        $supplier = Supplier::findOrFail($id);

        $request->validate([
            'document_identification' => 'required|string|max:50|unique:customers,document_identification,' . $supplier->id,
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email,' . $supplier->id,
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

            // Si el proveedor ya tiene una imagen, eliminar la anterior
            if ($supplier->photo) {
                \Storage::disk('public')->delete($supplier->photo);
            }

            // Guardar la nueva imagen y almacenar la ruta
            $photoPath = $request->file('photo')->store('suppliers', 'public');
            $data['photo'] = $photoPath;
        }

        // Actualizar proveedor
        $supplier->update($data);

        return redirect()->route('suppliers.index')->with('success', 'Proveedor actualizado correctamente.');
    }


    public function destroy(string $id)
    {
        // Buscar el proveedor individualmente
        $supplier = Supplier::where('id', $id)->firstOrFail();

        // Eliminar la foto del proveedor si existe
        if ($supplier->photo) {
            \Storage::disk('public')->delete($supplier->photo);
        }

        // Eliminar el proveedor
        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', 'Proveedor eliminado correctamente.');
    }
}
