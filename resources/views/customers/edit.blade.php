@extends('layouts.app')

@section('title', 'Editar Cliente')

@section('content')
<div class="page-content">
    <div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Editar de Cliente</h4>

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
                        <form action="{{ route('customers.update', $customer) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="document_identification" class="form-label">Documento</label>
                                <input type="text" name="document_identification" class="form-control" value="{{ $customer->document_identification }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">Nombre</label>
                                <input type="text" name="name" class="form-control" value="{{ $customer->name }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" name="email" class="form-control" value="{{ $customer->email }}">
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Teléfono</label>
                                <input type="text" name="phone" class="form-control" value="{{ $customer->phone }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Dirección</label>
                                <textarea name="address" class="form-control">{{ $customer->address }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="photo" class="form-label">Foto</label>
                                <input type="file" name="photo" class="form-control" id="photo">
                                @if($customer->photo)
                                    <img src="{{ asset('storage/' . $customer->photo) }}" alt="Foto del cliente" class="mt-2 rounded" width="100">
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Estado</label>
                                <select name="status" class="form-select" required>
                                    <option value="active" {{ $customer->status == 'active' ? 'selected' : '' }}>Activo</option>
                                    <option value="inactive" {{ $customer->status == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-success">Actualizar</button>
                            <a href="{{ route('customers.index') }}" class="btn btn-secondary">Cancelar</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
