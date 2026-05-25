@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-calendar-plus"></i> Nueva Cita
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('citas.store') }}" id="formCita">
                        @csrf

                        <div class="mb-3">
                            <label>Mascota *</label>
                            <select name="id_mascota" id="id_mascota" class="form-control" required>
                                <option value="">Seleccione</option>
                                @foreach($mascotas as $mascota)
                                    <option value="{{ $mascota->id_mascota }}" 
                                            data-tamanio="{{ $mascota->tamanio }}"
                                            data-factor="{{ $mascota->factor_tamanio ?? 1 }}">
                                        {{ $mascota->nombre_mascota }} 
                                        ({{ ucfirst($mascota->tamanio ?? 'mediano') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Servicio *</label>
                            <select name="id_servicio" id="id_servicio" class="form-control" required>
                                <option value="">Seleccione</option>
                                @foreach($servicios as $servicio)
                                    <option value="{{ $servicio->id_servicio }}" 
                                            data-duracion="{{ $servicio->duracion_minutos }}"
                                            data-precio="{{ $servicio->precio }}">
                                        {{ $servicio->nombre_servicio }} - ${{ number_format($servicio->precio, 2) }} 
                                        ({{ $servicio->duracion_minutos }} min)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Groomer *</label>
                            <select name="id_groomer" id="id_groomer" class="form-control" required>
                                <option value="">Seleccione</option>
                                @foreach($groomers as $groomer)
                                    <option value="{{ $groomer->id_groomer }}">
                                        {{ $groomer->user->name ?? 'Groomer' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Fecha *</label>
                            <input type="date" name="fecha" id="fecha" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Hora *</label>
                            <div class="row" id="horariosContainer">
                                <div class="col-12 text-muted text-center py-3">
                                    Selecciona primero: Mascota, Servicio, Groomer y Fecha
                                </div>
                            </div>
                            <input type="hidden" name="fecha_inicio" id="fecha_inicio">
                        </div>

                        <div id="resumenContainer" class="alert alert-info d-none">
                            <strong>📋 Resumen de la cita:</strong>
                            <div id="resumenTexto"></div>
                        </div>

                        <button type="submit" class="btn btn-success" id="btnAgendar" disabled>
                            <i class="fas fa-save"></i> Agendar Cita
                        </button>
                        <a href="{{ route('citas.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    var horariosOcupados = [];
    
    // Cuando cambia el groomer o la fecha
    $('#id_groomer, #fecha').change(function() {
        cargarHorarios();
    });
    
    // Cuando cambia la mascota o servicio, actualizar resumen
    $('#id_mascota, #id_servicio').change(function() {
        actualizarResumen();
    });
    
    function cargarHorarios() {
        var groomer_id = $('#id_groomer').val();
        var fecha = $('#fecha').val();
        
        if (!groomer_id || !fecha) {
            $('#horariosContainer').html('<div class="col-12 text-muted text-center py-3">Selecciona groomer y fecha primero</div>');
            return;
        }
        
        // Mostrar loading
        $('#horariosContainer').html('<div class="col-12 text-center py-3"><i class="fas fa-spinner fa-spin"></i> Cargando horarios...</div>');
        
        // Obtener horarios ocupados vía AJAX
        $.ajax({
            url: '{{ route("citas.horarios.ocupados", ["groomer_id" => "__groomer__", "fecha" => "__fecha__"]) }}'
                .replace('__groomer__', groomer_id)
                .replace('__fecha__', fecha),
            method: 'GET',
            success: function(ocupados) {
                horariosOcupados = ocupados;
                generarBotonesHorarios();
            },
            error: function() {
                // Si falla AJAX, generar horarios sin ocupados
                horariosOcupados = [];
                generarBotonesHorarios();
            }
        });
    }
    
    function generarBotonesHorarios() {
        var html = '';
        var horarios = ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'];
        
        for (var i = 0; i < horarios.length; i++) {
            var hora = horarios[i];
            var ocupado = false;
            var motivo = '';
            
            // Verificar si el horario está ocupado
            for (var j = 0; j < horariosOcupados.length; j++) {
                var ocup = horariosOcupados[j];
                if (hora >= ocup.inicio && hora < ocup.fin) {
                    ocupado = true;
                    motivo = ocup.servicio;
                    break;
                }
            }
            
            var clase = ocupado ? 'btn-danger disabled' : 'btn-outline-success';
            var icono = ocupado ? '❌' : '✅';
            var textoOcupado = ocupado ? ' - ' + motivo : '';
            
            html += `<div class="col-md-3 mb-2">
                <button type="button" 
                    class="btn ${clase} w-100 btn-horario" 
                    data-hora="${hora}"
                    data-ocupado="${ocupado}"
                    ${ocupado ? 'disabled' : ''}
                    onclick="seleccionarHora('${hora}', ${ocupado})">
                    ${icono} ${hora}${textoOcupado}
                </button>
            </div>`;
        }
        
        $('#horariosContainer').html(html);
        $('#btnAgendar').prop('disabled', true);
    }
    
    function actualizarResumen() {
        var mascotaId = $('#id_mascota').val();
        var servicioId = $('#id_servicio').val();
        var mascotaTexto = $('#id_mascota option:selected').text();
        var servicioTexto = $('#id_servicio option:selected').text();
        var duracion = $('#id_servicio option:selected').data('duracion') || 0;
        
        if (mascotaId && servicioId) {
            var factor = $('#id_mascota option:selected').data('factor') || 1;
            var duracionAjustada = Math.round(duracion * factor);
            
            $('#resumenContainer').removeClass('d-none');
            $('#resumenTexto').html(`
                <strong>Mascota:</strong> ${mascotaTexto}<br>
                <strong>Servicio:</strong> ${servicioTexto}<br>
                <strong>Duración base:</strong> ${duracion} minutos<br>
                <strong>Factor por tamaño:</strong> ${factor}x<br>
                <strong>Duración estimada:</strong> ${duracionAjustada} minutos
            `);
        } else {
            $('#resumenContainer').addClass('d-none');
        }
    }
});

function seleccionarHora(hora, ocupado) {
    if (ocupado) {
        alert('❌ Este horario ya está ocupado. Por favor selecciona otro.');
        return;
    }
    
    var fecha = $('#fecha').val();
    var fechaHora = fecha + ' ' + hora + ':00';
    
    $('#fecha_inicio').val(fechaHora);
    
    // Remover clase activa de todos los botones
    $('.btn-horario').removeClass('btn-primary btn-success').addClass('btn-outline-success');
    $('.btn-horario:not(.disabled)').removeClass('btn-danger');
    
    // Marcar el seleccionado
    $('.btn-horario[data-hora="' + hora + '"]').removeClass('btn-outline-success').addClass('btn-primary');
    
    // Habilitar botón de agendar
    $('#btnAgendar').prop('disabled', false);
    
    // Mostrar confirmación
    alert('✅ Hora seleccionada: ' + fecha + ' ' + hora);
}
</script>

<style>
.btn-horario {
    transition: all 0.3s ease;
}
.btn-horario:hover:not(.disabled) {
    transform: translateY(-2px);
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.btn-horario.disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
</style>
@endsection