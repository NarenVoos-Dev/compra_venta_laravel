@extends('layouts.app')

@section('title', 'Mantenimiento de Proveedores')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Listado de Proveedores</h4>

                    <div class="page-title-right">
                        <ol class="m-0 breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Mantenimiento</a></li>
                            <li class="breadcrumb-item active">Proveedores</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <a href="{{ route('suppliers.create') }}" class="mb-3 btn btn-primary">Nuevo Proveedor</a>

                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <div class="table-responsive">
                                <table id="tabla-proveedores" class="table align-middle table-bordered dt-responsive nowrap table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Documento</th>
                                            <th>Nombre</th>
                                            <th>Email</th>
                                            <th>Teléfono</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($suppliers as $supplier)
                                            <tr>
                                                <td>{{ $supplier->id }}</td>
                                                <td>{{ $supplier->document_identification }}</td>
                                                <td>{{ $supplier->name }}</td>
                                                <td>{{ $supplier->email }}</td>
                                                <td>{{ $supplier->phone }}</td>
                                                <td>
                                                    <span class="badge {{ $supplier->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                                        {{ ucfirst($supplier->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-warning btn-sm">
                                                    <i class="ri-edit-2-line"></i>
                                                    </a>
                                                    <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $supplier->id }}">
                                                        <i class="ri-delete-bin-6-line"></i>
                                                    </button>
                                                    <form id="delete-form-{{ $supplier->id }}" action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="d-none">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <br/>
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
            $('#tabla-proveedores').DataTable({
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

            // SweetAlert2 para eliminación de proveedores
            $(".delete-btn").on("click", function () {
                let supplierId = $(this).data("id");

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
                        $("#delete-form-" + supplierId).submit();
                    }
                });
            });
        });
    </script>
@endpush
