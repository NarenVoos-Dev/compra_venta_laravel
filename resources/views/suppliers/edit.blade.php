@extends('layouts.app')

@section('title', 'Editar Proveedor')

@section('content')
<div class="page-content">
    <div class="container-fluid">
    <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Editar Proveedor</h4>

                    <div class="page-title-right">
                        <ol class="m-0 breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Mantenimiento</a></li>
                            <li class="breadcrumb-item active">Proveedor</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('suppliers.update', $supplier) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Documento</label>
                                <input type="text" name="document_identification" class="form-control" value="{{ $supplier->document_identification }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" name="name" class="form-control" value="{{ $supplier->name }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Correo Electrónico</label>
                                <input type="email" name="email" class="form-control" value="{{ $supplier->email }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Teléfono</label>
                                <input type="text" name="phone" class="form-control" value="{{ $supplier->phone }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Dirección</label>
                                <textarea name="address" class="form-control">{{ $supplier->address }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Estado</label>
                                <select name="status" class="form-select" required>
                                    <option value="active" {{ $supplier->status == 'active' ? 'selected' : '' }}>Activo</option>
                                    <option value="inactive" {{ $supplier->status == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Foto</label>
                                <input type="file" name="photo" class="form-control">
                                @if($supplier->photo)
                                    <img src="{{ asset('storage/' . $supplier->photo) }}" alt="Foto del proveedor" class="mt-2 rounded" width="100">
                                @endif
                            </div>

                            <button type="submit" class="btn btn-success">Actualizar</button>
                            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Cancelar</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
