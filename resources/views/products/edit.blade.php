@extends('layouts.app')

@section('title', 'Editar Producto')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Descripción</label>
                                <textarea name="description" class="form-control">{{ $product->description }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Precio</label>
                                <input type="number" name="price" class="form-control" step="0.01" value="{{ $product->price }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Cantidad</label>
                                <input type="number" name="quantity" class="form-control" value="{{ $product->quantity }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Categoría</label>
                                <select name="category_id" class="form-select" required>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Imágenes Actuales</label>
                                <div class="flex-wrap gap-2 d-flex">
                                    @foreach ($product->images as $image)
                                        <div class="position-relative">
                                            <img src="{{ asset('storage/' . $image->image_path) }}" width="80" height="80" class="rounded">
                                            <button type="button" class="top-0 btn btn-danger btn-sm position-absolute end-0 delete-image" data-id="{{ $image->id }}">
                                                <i class="ri-close-line"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Subir Nuevas Imágenes</label>
                                <input type="file" name="images[]" class="form-control" multiple>
                                <small class="text-muted">Puedes subir varias imágenes nuevas. Las anteriores no se eliminarán automáticamente.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Estado</label>
                                <select name="status" class="form-select" required>
                                    <option value="available" {{ $product->status == 'available' ? 'selected' : '' }}>Disponible</option>
                                    <option value="sold" {{ $product->status == 'sold' ? 'selected' : '' }}>Vendido</option>
                                    <option value="archived" {{ $product->status == 'archived' ? 'selected' : '' }}>Archivado</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-success">Actualizar</button>
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancelar</a>
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
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".delete-image").forEach(button => {
            button.addEventListener("click", function () {
                console.log("Botón clickeado, ID de la imagen:", this.dataset.id); // <-- Depuración

                let imageId = this.dataset.id;
                Swal.fire({
                    title: "¿Eliminar imagen?",
                    text: "Esta acción no se puede deshacer.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Sí, eliminar",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log("Se confirmó la eliminación de la imagen ID:", imageId); // <-- Depuración

                        fetch(`/product-images/${imageId}`, {
                            method: "DELETE",
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                                "Content-Type": "application/json"
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log("Respuesta del servidor:", data); // <-- Depuración
                            if (data.success) {
                                location.reload();
                            } else {
                                Swal.fire("Error", "No se pudo eliminar la imagen", "error");
                            }
                        })
                        .catch(error => console.error("Error en la petición:", error)); // <-- Captura errores
                    }
                });
            });
        });
    });

</script>
@endpush
