@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-danger text-white d-flex justify-content-between">
            <span>Gestión de Personal</span>
            <a href="{{ route('admin.users.create') }}" class="btn btn-light btn-sm">+ Nuevo Personal</a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->id_rol == 1) <span class="badge bg-danger">ADMINISTRADOR</span>
                            @elseif($user->id_rol == 2) <span class="badge bg-warning">CAJERO</span>
                            @elseif($user->id_rol == 3) <span class="badge bg-info">GROOMER</span>
                            @else <span class="badge bg-success">CLIENTE</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $user->account_status == 'ACTIVO' ? 'success' : 'danger' }}">
                                {{ $user->account_status }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm">Editar</a>
                            
                            @if($user->id_rol != 1)
                                @if($user->account_status == 'ACTIVO')
                                    <form action="{{ route('admin.users.deactivate', $user->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('¿Desactivar este usuario?')">Desactivar</button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.users.activate', $user->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('¿Activar este usuario?')">Activar</button>
                                    </form>
                                @endif
                                
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este usuario?')">Eliminar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection