@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">Editar Compra</div>
        <div class="card-body">
            <form id="compraForm" action="{{ route('compras.update', $compra->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Proveedor</label>
                        <select class="form-control" name="supplier_id" required>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ $compra->supplier_id == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tipo de Documento</label>
                        <select class="form-control" name="tipodocumento_id" required>
                            @foreach($tipodocumentos as $documento)
                                <option value="{{ $documento->id }}" {{ $compra->tipodocumento_id == $documento->id ? 'selected' : '' }}>
                                    {{ $documento->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-3 mb-3">
                    <label class="form-label">Fecha de Compra</label>
                    <input type="date" class="form-control" name="purchase_date" value="{{ $compra->purchase_date }}" required>
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
                    <tbody>
                        @foreach($compra->detalles as $detalle)
                        <tr>
                            <td>
                                <select class="form-control producto" name="productos[]" onchange="actualizarSubtotal(this)">
                                    <option value="">Seleccione un producto</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->price }}" {{ $detalle->product_id == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" class="form-control cantidad" name="cantidades[]" value="{{ $detalle->quantity }}" oninput="actualizarSubtotal(this)" min="1"></td>
                            <td><input type="text" class="form-control precio" name="precios[]" value="{{ $detalle->unit_cost }}" readonly></td>
                            <td><input type="text" class="form-control subtotal" name="subtotales[]" value="{{ $detalle->subtotal }}" readonly></td>
                            <td><button type="button" class="btn btn-danger" onclick="eliminarFila(this)">X</button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <button type="button" class="btn btn-primary" onclick="agregarProducto()">Agregar Producto</button>

                <div class="mt-3">
                    <label class="form-label">Costo Total</label>
                    <input type="text" class="form-control" name="total_cost" id="total_cost" value="{{ $compra->total_cost }}" readonly>
                </div>

                <button type="submit" class="mt-3 btn btn-success">Actualizar Compra</button>
            </form>
        </div>
    </div>
</div>

<script>
    let productos = @json($products);
    
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
        fila.querySelector('.precio').value = precio;
        fila.querySelector('.subtotal').value = subtotal;
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
