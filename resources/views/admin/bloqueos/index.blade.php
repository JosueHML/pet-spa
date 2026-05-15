@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
            <span>📅 Bloqueos de Agenda</span>
            <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modalCrear">+ Nuevo Bloqueo</button>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if($bloqueos->isEmpty())
                <div class="text-center">No hay bloqueos registrados</div>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Alcance</th>
                            <th>Groomer</th>
                            <th>Motivo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bloqueos as $bloqueo)
                        <tr>
                            <td>{{ $bloqueo->fecha_bloqueo }}</td>
                            <td>
                                @if($bloqueo->tipo == 'FERIADO') 🎉 Feriado
                                @elseif($bloqueo->tipo == 'MANTENIMIENTO') 🔧 Mantenimiento
                                @else 🏠 Ausencia
                                @endif
                            </td>
                            <td>{{ $bloqueo->alcance == 'GLOBAL' ? '🌍 Global' : '👤 Individual' }}</td>
                            <td>{{ $bloqueo->groomer->user->name ?? 'Todos' }}</td>
                            <td>{{ $bloqueo->motivo ?? '-' }}</td>
                            <td>
                                <form action="{{ route('admin.bloqueos.destroy', $bloqueo->id_bloqueo) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar bloqueo?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $bloqueos->links() }}
            @endif
        </div>
    </div>
</div>

<!-- Modal para crear bloqueo -->
<div class="modal fade" id="modalCrear" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Nuevo Bloqueo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.bloqueos.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Fecha *</label>
                        <input type="date" name="fecha_bloqueo" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Tipo *</label>
                        <select name="tipo" class="form-control" required>
                            <option value="FERIADO">🎉 Feriado</option>
                            <option value="MANTENIMIENTO">🔧 Mantenimiento</option>
                            <option value="AUSENCIA">🏠 Ausencia</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Alcance *</label>
                        <select name="alcance" id="alcance" class="form-control" required>
                            <option value="GLOBAL">🌍 Global (todos los groomers)</option>
                            <option value="INDIVIDUAL">👤 Individual (solo un groomer)</option>
                        </select>
                    </div>
                    <div class="mb-3" id="groomerDiv" style="display:none;">
                        <label>Groomer *</label>
                        <select name="id_groomer" class="form-control">
                            <option value="">Seleccione</option>
                            @foreach($groomers as $groomer)
                                <option value="{{ $groomer->id_groomer }}">{{ $groomer->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Motivo</label>
                        <textarea name="motivo" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('alcance').addEventListener('change', function() {
        var groomerDiv = document.getElementById('groomerDiv');
        if (this.value === 'INDIVIDUAL') {
            groomerDiv.style.display = 'block';
        } else {
            groomerDiv.style.display = 'none';
        }
    });
</script>
@endsection