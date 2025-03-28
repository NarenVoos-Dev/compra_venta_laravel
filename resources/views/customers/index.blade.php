@extends('layouts.app')

@section('title', 'Clientes')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Listado de Clientes</h4>

                    <div class="page-title-right">
                        <ol class="m-0 breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Mantenimiento</a></li>
                            <li class="breadcrumb-item active">Clientes</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('customers.create') }}" class="mb-3 btn btn-primary">
                        <i class="ri-add-line"></i> Nuevo Cliente
                        </a>

                        @if(session('success'))
                        <div class="alert alert-success" id="success-alert">{{ session('success') }}</div>
                        @endif
                        <table id="tabla-clientes"
                            class="table align-middle table-bordered dt-responsive nowrap table-striped" style="100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Teléfono</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customers as $customer)
                                <tr>
                                    <td>{{ $customer->id }}</td>
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->phone }}</td>
                                    <td class="text-center">
                                        <!-- Botón de Editar con Icono -->
                                        <a href="{{ route('customers.edit', $customer->id) }}"
                                            class="btn btn-warning btn-sm">
                                            <i class="ri-edit-2-line"></i>
                                        </a>

                                        <!-- Botón de Eliminar con Icono y SweetAlert2 -->
                                        <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $customer->id }}">
                                            <i class="ri-delete-bin-6-line"></i>
                                        </button>

                                        <form id="delete-form-{{ $customer->id }}"
                                            action="{{ route('customers.destroy', $customer->id) }}" method="POST"
                                            class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
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
@endsection

@push('scripts')

<script>
$(document).ready(function() {
    $('#tabla-clientes').DataTable({
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

    // SweetAlert2 para eliminación de clientes
    document.querySelectorAll(".delete-btn").forEach(button => {
        button.addEventListener("click", function() {
            let customerId = this.getAttribute("data-id");

            Swal.fire({
                title: "¿Estás seguro?",
                text: "Esta acción no se puede deshacer.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${customerId}`).submit();
                }
            });
        });
    });

        //mostrar alerta de tiempo
    setTimeout(function() {
        let alert = document.getElementById('success-alert');
        if (alert) {
            alert.style.transition = "opacity 0.5s ease-out";
            alert.style.opacity = "0";
            setTimeout(() => alert.remove(), 300);
        }
    }, 3000);
});
</script>
@endpush