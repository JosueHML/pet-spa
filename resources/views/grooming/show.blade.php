@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-info text-white">
            📋 Ficha de Grooming
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <!-- Datos de la cita -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <p><strong>Mascota:</strong> {{ $cita->mascota->nombre_mascota }}</p>
                    <p><strong>Servicio:</strong> {{ $cita->servicio->nombre_servicio }}</p>
                    <p><strong>Groomer:</strong> {{ $cita->groomer->user->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Fecha:</strong> {{ $cita->fecha_inicio->format('d/m/Y H:i') }}</p>
                    <p><strong>Estado ficha:</strong> 
                        <span class="badge bg-{{ $ficha->estado_ficha == 'ABIERTA' ? 'warning' : 'success' }}">
                            {{ $ficha->estado_ficha }}
                        </span>
                    </p>
                </div>
            </div>

            <hr>

            <!-- Estado de ingreso -->
            <h5>📝 Estado de ingreso</h5>
            <form method="POST" action="{{ route('grooming.update.estado', $ficha->id_ficha) }}">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-check">
                            <input type="checkbox" name="nudos" class="form-check-input" {{ $ficha->nudos ? 'checked' : '' }}>
                            <label>Nudos</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check">
                            <input type="checkbox" name="pulgas" class="form-check-input" {{ $ficha->pulgas ? 'checked' : '' }}>
                            <label>Pulgas</label>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label>Heridas</label>
                    <textarea name="heridas" class="form-control" rows="2">{{ $ficha->heridas }}</textarea>
                </div>
                <div class="mb-3">
                    <label>Recomendaciones</label>
                    <textarea name="recomendaciones" class="form-control" rows="2">{{ $ficha->recomendaciones }}</textarea>
                </div>
                <button type="submit" class="btn btn-info">Guardar Estado</button>
            </form>

            <hr>

            <!-- Checklist de servicio -->
            <h5>✅ Checklist de servicio</h5>
            <form method="POST" action="{{ route('grooming.update.checklist', $ficha->id_ficha) }}">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="checklist_baño" id="check_baño" {{ isset($ficha->checklist_json['baño']) && $ficha->checklist_json['baño'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="check_baño">Baño</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="checklist_corte" id="check_corte" {{ isset($ficha->checklist_json['corte']) && $ficha->checklist_json['corte'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="check_corte">Corte</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="checklist_uñas" id="check_uñas" {{ isset($ficha->checklist_json['uñas']) && $ficha->checklist_json['uñas'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="check_uñas">Uñas</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="checklist_oídos" id="check_oídos" {{ isset($ficha->checklist_json['oídos']) && $ficha->checklist_json['oídos'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="check_oídos">Oídos</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="checklist_glándulas" id="check_glándulas" {{ isset($ficha->checklist_json['glándulas']) && $ficha->checklist_json['glándulas'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="check_glándulas">Glándulas</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="checklist_perfume" id="check_perfume" {{ isset($ficha->checklist_json['perfume']) && $ficha->checklist_json['perfume'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="check_perfume">Perfume</label>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Guardar Checklist</button>
            </form>

            <hr>

            <!-- Registro de Salida de Insumos (Recibir materiales) -->
            <h5>📥 Registrar Salida de Insumos</h5>
            <form method="POST" action="{{ route('grooming.recibir.insumos', $ficha->id_ficha) }}">
                @csrf
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Insumo</th>
                            <th>Stock disponible</th>
                            <th>Cantidad a recibir</th>
                            <th>Recibir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($insumosServicio as $index => $item)
                        <tr>
                            <td>{{ $item->insumo->nombre }}</td>
                            <td>{{ $item->insumo->stock_actual }} unidades</td>
                            <td>
                                <input type="number" name="insumos_recibir[{{ $index }}][cantidad]" 
                                    class="form-control" style="width:100px" 
                                    value="{{ $item->cantidad }}" min="0" max="{{ $item->insumo->stock_actual }}">
                            </td>
                            <td>
                                <input type="checkbox" name="insumos_recibir[{{ $index }}][recibir]" value="1">
                                <input type="hidden" name="insumos_recibir[{{ $index }}][id_insumo]" value="{{ $item->id_insumo }}">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <button type="submit" class="btn btn-primary">Registrar Materiales Recibidos</button>
            </form>

            <hr>
            <!-- Fotos antes/después -->
            <h5>📸 Fotos del servicio</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Antes</div>
                        <div class="card-body text-center">
                            @if($ficha->foto_antes)
                                <img src="{{ asset('storage/' . $ficha->foto_antes) }}" class="img-fluid" style="max-width: 100%;">
                                <form action="{{ route('grooming.delete_foto', ['id' => $ficha->id_ficha, 'tipo' => 'antes']) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm mt-2">Eliminar</button>
                                </form>
                            @else
                                <form action="{{ route('grooming.upload_foto') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="ficha_id" value="{{ $ficha->id_ficha }}">
                                    <input type="hidden" name="tipo" value="antes">
                                    <input type="file" name="foto" accept="image/*" required>
                                    <button type="submit" class="btn btn-primary btn-sm mt-2">Subir foto</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Después</div>
                        <div class="card-body text-center">
                            @if($ficha->foto_despues)
                                <img src="{{ asset('storage/' . $ficha->foto_despues) }}" class="img-fluid" style="max-width: 100%;">
                                <form action="{{ route('grooming.delete_foto', ['id' => $ficha->id_ficha, 'tipo' => 'despues']) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm mt-2">Eliminar</button>
                                </form>
                            @else
                                <form action="{{ route('grooming.upload_foto') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="ficha_id" value="{{ $ficha->id_ficha }}">
                                    <input type="hidden" name="tipo" value="despues">
                                    <input type="file" name="foto" accept="image/*" required>
                                    <button type="submit" class="btn btn-primary btn-sm mt-2">Subir foto</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Botón de cierre -->
            @if($ficha->estado_ficha == 'ABIERTA')
            <div class="text-center">
                <form action="{{ route('grooming.cerrar', $ficha->id_ficha) }}" method="POST" onsubmit="return confirm('¿Cerrar el servicio? Se descontarán los insumos del inventario.')">
                    @csrf
                    <button type="submit" class="btn btn-success btn-lg">✅ Cerrar Servicio</button>
                </form>
            </div>
            @endif

            <div class="mt-3">
                <a href="{{ route('citas.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </div>
    </div>
</div>
@endsection