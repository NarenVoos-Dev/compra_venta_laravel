@extends('layouts.app')

@section('title', 'Compras')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Listado de Compras</h4>

                    <div class="page-title-right">
                        <ol class="m-0 breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Compras</a></li>
                            <li class="breadcrumb-item active">Listado de compras</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('compras.create') }}" class="mb-3 btn btn-primary">
                            <i class="ri-add-line"></i> Nueva Compra
                        </a>

                        @if(session('success'))
                        <div class="alert alert-success" id="success-alert">{{ session('success') }}</div>
                        @endif
                        <table id="comprasTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Proveedor</th>
                                    <th>Usuario</th>
                                    <th>Tipo de Documento</th>
                                    <th>Total</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($compras as $compra)
                                <tr>
                                    <td>{{ $compra->id }}</td>
                                    <td>{{ $compra->supplier->name }}</td>
                                    <td>{{ $compra->user->name }}</td>
                                    <td>{{ $compra->tipodocumento->name }}</td>
                                    <td>${{ number_format($compra->total_cost, 2) }}</td>
                                    <td>{{ $compra->purchase_date }}</td>
                                    <td>{{ $compra->status }}</td>
                                    <td>
                                        <button class="btn btn-info btn-sm ver-detalles" data-id="{{ $compra->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Ver detalles">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                        <a href="{{ url('storage/compras/compra_' . $compra->id . '.pdf') }}" target="_blank" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="PDF">
                                            <i class="ri-file-pdf-line"></i>
                                        </a>
                                        <!--<a href="{{ route('compras.edit', $compra->id) }}"
                                            class="btn btn-warning btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar ">
                                            <i class="ri-edit-2-line"></i>
                                        </a>-->
                                        <button class="btn btn-danger btn-sm"
                                            onclick="eliminarCompra({{ $compra->id }})" data-bs-toggle="tooltip" data-bs-placement="top" title="Eliminar">
                                            <i class="ri-delete-bin-6-line"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Modal para ver detalles de la compra -->
<div id="modalCompra" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de Compra</h5>
            </div>
            <div class="modal-body">
                <p><strong>Proveedor:</strong> <span id="modalSupplier"></span></p>
                <p><strong>Fecha:</strong> <span id="modalFecha"></span></p>
                <p><strong>Total:</strong> <span id="modalTotal"></span></p>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Costo Unitario</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="modalDetalles"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script>
$(document).ready(function() {
    // Tabla de compras
    $('#comprasTable').DataTable({
        "order": [[ 0, "desc" ]], // Ordena por la primera columna en orden descendente
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros por página",
            "zeroRecords": "No se encontraron resultados",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "No hay registros disponibles",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            "search": "Buscar:",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        }
    });

    // Modal para ver detalles de la compra
    $(".ver-detalles").click(function() {
        let compraId = $(this).data("id");

        $.ajax({
            url: "/compras/" + compraId, // Ruta al controlador
            type: "GET",
            dataType: "json",
            success: function(data) {

                $("#modalCompra .modal-title").text("Detalles de Compra #" + data.id);
                $("#modalSupplier").text(data.supplier.name);
                $("#modalTotal").text("$" + data.total_cost);
                $("#modalFecha").text(data.purchase_date);

                let detallesHtml = "";
                data.detalles.forEach(detalle => {
                    console.log(detalle.producto.name);
                    detallesHtml += `
                        <tr>
                            <td>${detalle.producto.name}</td>
                            <td>${detalle.quantity}</td>
                            <td>$${detalle.unit_cost}</td>
                            <td>$${detalle.subtotal}</td>
                        </tr>`;
                });

                $("#modalDetalles").html(detallesHtml);
                $("#modalCompra").modal("show");
            }
        });
    });

    //titulo del boton
    document.addEventListener("DOMContentLoaded", function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
    
    //mostrar alerta de tiempo
    setTimeout(function() {
        let alert = document.getElementById('success-alert');
        if (alert) {
            alert.style.transition = "opacity 0.5s ease-out";
            alert.style.opacity = "0";
            setTimeout(() => alert.remove(), 500);
        }
    }, 3000);

});
 //Eliminar compra
function eliminarCompra(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/compras/' + id,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    location.reload();
                }
            });
        }
    });
}
</script>

@endpush