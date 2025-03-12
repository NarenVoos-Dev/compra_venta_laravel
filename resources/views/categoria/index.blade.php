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
                    <div class="container mt-4">
                        @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <a href="{{ route('categorias.create') }}" class="mb-3 btn btn-primary">Nueva Categoría</a>

                        <table class="table table-bordered" id="tabla-categorias">
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
                                    <td>
                                        <a href="{{ route('categorias.edit', $categoria->id) }}" class="btn btn-warning btn-sm">Editar</a>

                                        <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $categoria->id }}">Eliminar</button>

                                        <form id="delete-form-{{ $categoria->id }}" action="{{ route('categorias.destroy', $categoria->id) }}" method="POST" class="d-none">
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
        });

        //Boton eliminar
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".delete-btn").forEach(button => {
                button.addEventListener("click", function () {
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