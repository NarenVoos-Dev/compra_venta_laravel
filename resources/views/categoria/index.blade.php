@extends('layouts.app')

@section('title','Categorias')

@section('page-title','Bienvenido a nuestro panel de control')

@section ('content')
<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Listado de Categorias</h4>

                    <div class="page-title-right">
                        <ol class="m-0 breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Mantenimiento</a></li>
                            <li class="breadcrumb-item active">Categorias</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="container mt-4">
                            <a href="{{ route('categorias.create') }}" class="mb-3 btn btn-primary">
                                <i class="ri-add-line"></i> Nueva Categoría
                            </a>

                            @if(session('success'))
                            <div class="alert alert-success" id="success-alert">{{ session('success') }}</div>
                            @endif

                            <table class="table align-middle table-bordered dt-responsive nowrap table-striped" id="tabla-categorias" >
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categorias as $categoria)
                                    <tr>
                                        <td>{{ $categoria->id }}</td>
                                        <td>{{ $categoria->name }}</td>
                                        <td>{{ $categoria->description }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('categorias.edit', $categoria->id) }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="ri-edit-2-line"></i>
                                            </a>

                                            <button class="btn btn-danger btn-sm delete-btn"
                                                data-id="{{ $categoria->id }}">
                                                <i class="ri-delete-bin-6-line"></i>
                                            </button>

                                            <form id="delete-form-{{ $categoria->id }}"
                                                action="{{ route('categorias.destroy', $categoria->id) }}" method="POST"
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
        <!-- end page title -->
    </div>
    <!-- container-fluid -->
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    //data table

    $('#tabla-categorias').DataTable({
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

//Boton eliminar
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".delete-btn").forEach(button => {
        button.addEventListener("click", function() {
            let categoriaId = this.getAttribute("data-id");

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
                    document.getElementById(`delete-form-${categoriaId}`).submit();
                }
            });
        });
    });
});
</script>
@endpush