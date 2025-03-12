<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Mostrar la lista de categorías.
     */
    public function index()
    {
        $categorias = Category::all();
        return view('categoria.index', compact('categorias'));
    }

    /**
     * Mostrar el formulario para crear una nueva categoría.
     */
    public function create()
    {
        return view('categoria.create');
    }

    /**
     * Guardar una nueva categoría en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string'
        ]);

        Category::create($request->all());

        return redirect()->route('categorias.index')->with('success', 'Categoría creada exitosamente.');
    }

    /**
     * Mostrar el formulario de edición de una categoría.
     */
    public function edit($id)
    {
        $categoria = Category::findOrFail($id);
        return view('categoria.edit', compact('categoria'));
    }

    /**
     * Actualizar la categoría en la base de datos.
     */
    public function update(Request $request, $id)
    {
        $categoria = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $categoria->id,
            'description' => 'nullable|string'
        ]);

        $categoria->update($request->all());

        return redirect()->route('categorias.index')->with('success', 'Categoría actualizada correctamente.');
    }

    /**
     * Eliminar una categoría de la base de datos.
     */
    public function destroy($id)
    {
        $categoria = Category::findOrFail($id);
        $categoria->delete();
        return redirect()->route('categorias.index')->with('success', 'Categoría eliminada correctamente.');
    }
}
