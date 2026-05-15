@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning">Editar Cliente</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.clientes.update', $cliente->id_cliente) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label>Nombre</label>
                            <input type="text" class="form-control" value="{{ $cliente->user->name }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="text" class="form-control" value="{{ $cliente->user->email }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label>Teléfono</label>
                            <input type="text" name="telefono" class="form-control" value="{{ $cliente->telefono }}">
                            <small class="text-muted">Este dato se encriptará automáticamente en la base de datos.</small>
                        </div>

                        <div class="mb-3">
                            <label>Dirección</label>
                            <textarea name="direccion" class="form-control" rows="2">{{ $cliente->direccion }}</textarea>
                            <small class="text-muted">Este dato se encriptará automáticamente en la base de datos.</small>
                        </div>

                        <button type="submit" class="btn btn-warning">Actualizar</button>
                        <a href="{{ route('admin.clientes.index') }}" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection