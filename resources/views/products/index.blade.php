@extends('layouts.app')

@section('title', 'Mantenimiento de Productos')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Listado de Productos</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('products.create') }}" class="mb-3 btn btn-primary">Nuevo Producto</a>

                        @if(session('success'))
                            <div class="alert alert-success" id="success-alert">{{ session('success') }}</div>
                        @endif

                        <div class="table-responsive">
                            <table id="tabla-productos" class="table align-middle table-bordered dt-responsive nowrap table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Imagen</th>
                                        <th>Nombre</th>
                                        <th>Categoría</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td>{{ $product->id }}</td>
                                            <td>
                                                @if($product->images->first())
                                                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" width="30" height="30" class="rounded">
                                                @else
                                                    <img src="{{ asset('storage/products//image1.jpg') }}" width="30" height="30" class="rounded">
                                                @endif
                                            </td>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->category->name ?? 'Sin categoría' }}</td>
                                            <td>${{ number_format($product->price, 2) }}</td>
                                            <td>{{ $product->quantity }}</td>
                                            <td>
                                                <span class="badge {{ $product->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ ucfirst($product->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">
                                                    <i class="ri-edit-2-line"></i> 
                                                </a>
                                                <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $product->id }}">
                                                    <i class="ri-delete-bin-6-line"></i> 
                                                </button>
                                                <form id="delete-form-{{ $product->id }}" action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-none">
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
            $('#tabla-productos').DataTable({
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

            $(".delete-btn").on("click", function () {
                let productId = $(this).data("id");

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
                        $("#delete-form-" + productId).submit();
                    }
                });
            });
            // Mostrar alerta de tiempo
            setTimeout(function() {
                let alert = document.getElementById('success-alert');
                if (alert) {
                    alert.style.transition = "opacity 0.5s ease-out";
                    alert.style.opacity = "0";
                    setTimeout(() => alert.remove(), 500);
                }
            }, 3000);


        });
    </script>
@endpush
