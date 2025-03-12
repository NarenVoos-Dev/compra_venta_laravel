@extends('layouts.app')

@section('title','Editar categoria')

@section('page-title','Bienvenido a nuestro panel de control')

@section ('content')
<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Editar Categoría</h4>

                    <div class="page-title-right">
                        <ol class="m-0 breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Mantenimiento</a></li>
                            <li class="breadcrumb-item active">Categoría</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="container m-4">
                        <form action="{{ route('categorias.update', $categoria) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="name" class="form-label">Nombre</label>
                                <input type="text" name="name" id="name" class="form-control"
                                    value="{{ $categoria->name }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Descripción</label>
                                <textarea name="description" id="description"
                                    class="form-control">{{ $categoria->description }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-success">Actualizar</button>
                            <a href="{{ route('categorias.index') }}" class="btn btn-secondary">Cancelar</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- container-fluid -->
</div>
@endsection