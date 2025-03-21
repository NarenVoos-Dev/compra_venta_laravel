@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">Registrar Compra</div>
        <div class="card-body">
            <form id="compraForm" action="{{ route('compras.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label for="supplier_id" class="form-label">Proveedor</label>
                    <select class="form-control" name="supplier_id" required>
                        <option value="">Seleccione un proveedor</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="tipodocumento_id" class="form-label">Tipo de Documento</label>
                        <select class="form-control" name="tipodocumento_id" required>
                            <option value="">Seleccione un tipo</option>
                            @foreach($tipodocumentos as $documento)
                                <option value="{{ $documento->id }}">{{ $documento->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="purchase_date" class="form-label">Fecha de Compra</label>
                        <input type="date" class="form-control" name="purchase_date" required>
                    </div>
                </div>
                
                <h5>Detalles de la compra</h5>
                <table class="table" id="detalleCompra">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Costo Unitario</th>
                            <th>Subtotal</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <button type="button" class="btn btn-primary" onclick="agregarProducto()">Agregar Producto</button>
                
                <div class="mt-3">
                    <label for="total_cost" class="form-label">Costo Total</label>
                    <input type="text" class="form-control" name="total_cost" id="total_cost" readonly>
                </div>
                
                <button type="submit" class="mt-3 btn btn-success">Guardar Compra</button>
            </form>
        </div>
    </div>
</div>

<script>
    let productos = @json($products);
    let detalles = [];
    
    function agregarProducto() {
    let productoSelect = `<select class="form-control producto" name="productos[]" onchange="actualizarSubtotal(this)">
        <option value="">Seleccione un producto</option>`;
    
    productos.forEach(producto => {
        productoSelect += `<option value="${producto.id}" data-price="${producto.price}">${producto.name}</option>`;
    });

    productoSelect += `</select>`;

    let fila = `<tr>
        <td>${productoSelect}</td>
        <td><input type="number" class="form-control cantidad" name="cantidades[]" oninput="actualizarSubtotal(this)" min="1" value="1"></td>
        <td><input type="text" class="form-control precio" name="precios[]" readonly></td>
        <td><input type="text" class="form-control subtotal" name="subtotales[]" readonly></td>
        <td><button type="button" class="btn btn-danger" onclick="eliminarFila(this)">X</button></td>
    </tr>`;

    document.querySelector('#detalleCompra tbody').insertAdjacentHTML('beforeend', fila);
}
    
    function actualizarSubtotal(elemento) {
        let fila = elemento.closest('tr');
        let producto = fila.querySelector('select').selectedOptions[0];
        let cantidad = fila.querySelector('.cantidad').value;
        let precio = producto.dataset.price || 0;
        let subtotal = cantidad * precio;

        fila.querySelector('.precio').value = precio;  // Aseguramos que solo el campo de precio se actualiza correctamente
        fila.querySelector('.subtotal').value = subtotal;  // Aseguramos que el subtotal se actualiza correctamente
        calcularTotal();
    }
    
    function eliminarFila(btn) {
        btn.closest('tr').remove();
        calcularTotal();
    }
    
    function calcularTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal').forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        document.querySelector('#total_cost').value = total;
    }
</script>
@endsection