<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Supplier;
use App\Models\TipoDocumento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CompraController extends Controller
{
   
    public function index()
    {
        $compras = Compra::with('supplier', 'user', 'tipodocumento')->get();
        return view('compras.index', compact('compras'));
    }


    public function create()
    {
        $suppliers = Supplier::all();
        $tipodocumentos = TipoDocumento::all();
        $products = Product::all();
        
        return view('compras.create', compact('suppliers', 'tipodocumentos', 'products'));
    }

 
    public function store(Request $request)
    {   
        //dd($request->all()); 
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'tipodocumento_id' => 'required|exists:tipodocumento,id',
            'purchase_date' => 'required|date',
            'productos' => 'required|array',
            'cantidades' => 'required|array',
            'precios' => 'required|array',
            'subtotales' => 'required|array',
            'total_cost' => 'required|numeric'
        ]);

        DB::transaction(function () use ($request) {
            // Obtener el usuario autenticado
            $user_id = Auth::id();

            // Crear la compra
        $compra = Compra::create([
            'supplier_id' => $request->supplier_id,
            'user_id' => $user_id,
            'tipodocumento_id' => $request->tipodocumento_id,
            'total_cost' => $request->total_cost,
            'purchase_date' => $request->purchase_date, // O ajusta el estado según sea necesario
        ]);

        // Insertar los detalles de la compra
        foreach ($request->productos as $index => $product_id) {
            DetalleCompra::create([
                'purchase_id' => $compra->id,
                'product_id' => $product_id,
                'quantity' => $request->cantidades[$index],
                'unit_cost' => $request->precios[$index],
                'subtotal' => $request->subtotales[$index],
            ]);

            // Actualizar inventario
            Inventory::create([
                'product_id' => $product_id,
                'type' => 'purchase',
                'quantity' => $request->cantidades[$index],
                'description' => 'Compra ID ' . $compra->id,
                'user_id' => $user_id,
            ]);
        }
           


             // Registrar la transacción
            Transaction::create([
                'type' => 'purchase',
                'amount' => $request->total_cost,
                'reference_id' => $compra->id,
                'description' => 'Compra realizada',
                'user_id' => $user_id,
            ]);
           
        });

        return redirect()->route('compras.index')->with('success', 'Compra registrada correctamente');
    }


    /*public function show($id)
    {
        $compra = Compra::with(['supplier', 'detalles.product', 'user', 'tipodocumento'])->findOrFail($id);
    
        return view('compras.partials.detalle', compact('compra'))->render();
    }*/

    public function show($id)
    {
        $compra = Compra::with(['supplier', 'detalles.producto', 'user', 'tipodocumento'])->find($id);
    
        if (!$compra) {
            return response()->json(['error' => 'Compra no encontrada'], 404);
        }
    
        return response()->json($compra); // TEMPORAL: Ver datos en la consola del navegador
    }


    public function edit($id)
    {
        $compra = Compra::with('detalles')->findOrFail($id);
        $suppliers = Supplier::all();
        $tipodocumentos = TipoDocumento::all();
        $products = Product::all();
    
        return view('compras.edit', compact('compra', 'suppliers', 'tipodocumentos', 'products'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'tipodocumento_id' => 'required|exists:tipodocumento,id',
            'purchase_date' => 'required|date',
            'productos' => 'required|array',
            'cantidades' => 'required|array',
            'precios' => 'required|array',
            'subtotales' => 'required|array',
            'total_cost' => 'required|numeric'
        ]);
    
        DB::transaction(function () use ($request, $id) {
            // Buscar la compra existente
            $compra = Compra::findOrFail($id);
    
            // Actualizar los datos de la compra
            $compra->update([
                'supplier_id' => $request->supplier_id,
                'tipodocumento_id' => $request->tipodocumento_id,
                'purchase_date' => $request->purchase_date,
                'total_cost' => $request->total_cost,
                'status' => $request->status ?? 'completed' // Valor por defecto
            ]);
    
            // Eliminar los detalles antiguos
            DetalleCompra::where('purchase_id', $compra->id)->delete();
    
            // Insertar los nuevos detalles
            foreach ($request->productos as $index => $product_id) {
                DetalleCompra::create([
                    'purchase_id' => $compra->id,
                    'product_id' => $product_id,
                    'quantity' => $request->cantidades[$index],
                    'unit_cost' => $request->precios[$index],
                    'subtotal' => $request->subtotales[$index],
                ]);
    
                // Actualizar el inventario
                Inventory::updateOrCreate(
                    [
                        'product_id' => $product_id,
                        'type' => 'purchase',
                        'description' => 'Compra ID ' . $compra->id
                    ],
                    [
                        'quantity' => $request->cantidades[$index],
                        'user_id' => auth()->id()
                    ]
                );
            }
    
            // Actualizar la transacción
            Transaction::updateOrCreate(
                ['reference_id' => $compra->id, 'type' => 'purchase'],
                [
                    'amount' => $request->total_cost,
                    'description' => 'Compra actualizada',
                    'user_id' => auth()->id()
                ]
            );
        });
    
        return redirect()->route('compras.index')->with('success', 'Compra actualizada correctamente');
    }
    

   
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            // Buscar la compra con sus detalles
            $compra = Compra::with('detalles')->findOrFail($id);
    
            // Revertir el inventario restando las cantidades compradas
            foreach ($compra->detalles as $detalle) {
                Inventory::create([
                    'product_id' => $detalle->product_id,
                    'type' => 'adjustment',
                    'quantity' => -$detalle->quantity, // Restar del inventario
                    'description' => 'Eliminación de compra ID ' . $compra->id,
                    'user_id' => auth()->id(),
                ]);
            }
    
            // Eliminar los detalles de compra
            DetalleCompra::where('purchase_id', $compra->id)->delete();
    
            // Eliminar la compra
            $compra->delete();
    
            // Eliminar la transacción asociada
            Transaction::where('reference_id', $id)->where('type', 'purchase')->delete();
        });
    
        return redirect()->route('compras.index')->with('success', 'Compra eliminada correctamente');
    }
    
}
