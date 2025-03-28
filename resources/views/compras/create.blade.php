@extends('layouts.app')

@section('title', 'Crear Compra')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Nueva compra</h4>

                    <div class="page-title-right">
                        <ol class="m-0 breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Compras</a></li>
                            <li class="breadcrumb-item active">Crear compra</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form id="compraForm" action="{{ route('compras.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="supplier_id" class="form-label">Proveedor</label>
                                <select class="form-control select2" name="supplier_id" id="supplier_id" required>
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
                            <button type="button" class="btn btn-primary" onclick="agregarProducto()">Agregar
                                Producto</button>

                            <div class="mt-3">
                                <label for="total_cost" class="form-label">Costo Total</label>
                                <input type="text" class="form-control" name="total_cost" id="total_cost" readonly>
                            </div>

                            <button type="button" class="mt-3 btn btn-success" id="guardarCompraButton">Guardar Compra</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')

<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Seleccione un proveedor",
            allowClear: true
        });
    });

    let productos = @json($products);
    let detalles = [];

    function agregarProducto() {
        let productoSelect = `<select class="form-control select2 product-select" name="productos[]" onchange="actualizarPrecio(this)">
            <option value="">Seleccione un producto</option>`;

        productos.forEach(producto => {
            productoSelect += `<option value="${producto.id}" data-price="${producto.price}">${producto.name}</option>`;
        });

        productoSelect += `</select>`;

        let fila = `<tr>
            <td>${productoSelect}</td>
            <td><input type="number" class="form-control quantity-input" name="cantidades[]" oninput="actualizarSubtotal(this)" min="1" value="1"></td>
            <td><input type="number" class="form-control unit-cost-input" name="precios[]" step="0.01" min="0" oninput="actualizarSubtotal(this)"></td>
            <td><input type="text" class="form-control subtotal" name="subtotales[]" readonly></td>
            <td><button type="button" class="btn btn-danger" onclick="eliminarFila(this)">X</button></td>
        </tr>`;

        document.querySelector('#detalleCompra tbody').insertAdjacentHTML('beforeend', fila);
        $('.select2').select2({
            width: '100%'
        }).on("select2:open", function() {
            $(".select2-selection").css("height", "38px");
        });
    }

    function actualizarPrecio(select) {
        let fila = select.closest('tr');
        let producto = select.selectedOptions[0];
        let precio = producto.dataset.price || 0;

        fila.querySelector('.unit-cost-input').value = precio; // Se carga el precio del producto seleccionado
        actualizarSubtotal(select); // Se recalcula el subtotal
    }

    function actualizarSubtotal(elemento) {
        let fila = elemento.closest('tr');
        let cantidad = parseFloat(fila.querySelector('.quantity-input').value) || 0;
        let precio = parseFloat(fila.querySelector('.unit-cost-input').value) || 0;
        let subtotal = cantidad * precio;

        fila.querySelector('.subtotal').value = subtotal.toFixed(2);
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
        document.querySelector('#total_cost').value = total.toFixed(2);
    }

    $('.supplier_id').select2({
        placeholder: "Seleccione una opción",
        allowClear: true
    });

    //  FUNCIÓN PARA VALIDAR Y GUARDAR LA COMPRA
    document.getElementById('guardarCompraButton').addEventListener('click', function () {
        const detalleTable = document.querySelector('#detalleCompra tbody');
        const compraForm = document.getElementById('compraForm');

        if (detalleTable.querySelectorAll('tr').length === 0) {
            Swal.fire({
                title: 'Error',
                text: 'Debe agregar al menos un producto al detalle.',
                icon: 'error',
                confirmButtonText: 'Entendido'
            });
            return;
        }

        Swal.fire({
            title: '¿Está seguro?',
            text: 'Está a punto de guardar esta compra.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const detailsInput = document.createElement('input');
                detailsInput.type = 'hidden';
                detailsInput.name = 'details';
                detailsInput.value = JSON.stringify(
                    Array.from(detalleTable.querySelectorAll('tr')).map(row => ({
                        product_id: row.querySelector('.product-select').value,
                        quantity: row.querySelector('.quantity-input').value,
                        unit_cost: row.querySelector('.unit-cost-input').value,
                    }))
                );
                compraForm.appendChild(detailsInput);
                compraForm.submit();
            }
        });
    });
</script>

@endpush