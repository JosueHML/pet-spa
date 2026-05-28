@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-lg">
        <div class="card-header bg-success text-white">
            <i class="fas fa-plus"></i> Nueva Compra a Proveedor
        </div>
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.compras.store') }}" id="formCompra">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>Proveedor *</label>
                            <select name="id_proveedor" class="form-control" required>
                                <option value="">Seleccione</option>
                                @foreach($proveedores as $proveedor)
                                    <option value="{{ $proveedor->id_proveedor }}">{{ $proveedor->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>N° Factura (Opcional)</label>
                            <input type="text" name="numero_factura" class="form-control" placeholder="Opcional">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>Fecha de Compra *</label>
                            <input type="date" name="fecha_compra" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>Observaciones</label>
                            <textarea name="observaciones" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>

                <hr>
                <h5>📦 Productos / Insumos</h5>
                <div id="itemsContainer">
                    <div class="item-row row mb-2">
                        <div class="col-md-3">
                            <select name="items[0][tipo]" class="form-control tipo-item" required>
                                <option value="">Seleccione Tipo</option>
                                <option value="PRODUCTO">Producto</option>
                                <option value="INSUMO">Insumo</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="items[0][id_item]" class="form-control item-select" required disabled>
                                <option value="">Primero seleccione tipo</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="items[0][cantidad]" class="form-control" placeholder="Cantidad" required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" step="0.01" name="items[0][precio]" class="form-control" placeholder="Precio" required>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger btn-sm" onclick="if(confirm('¿Eliminar?')) this.closest('.item-row').remove()">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <button type="button" class="btn btn-secondary btn-sm mt-2" onclick="agregarItem()">
                    <i class="fas fa-plus"></i> Agregar otro producto
                </button>

                <hr>
                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Registrar Compra
                    </button>
                    <a href="{{ route('admin.compras.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let itemCount = 1;

    function agregarItem() {
        const container = document.getElementById('itemsContainer');
        const newRow = document.createElement('div');
        newRow.className = 'item-row row mb-2';
        newRow.innerHTML = `
            <div class="col-md-3">
                <select name="items[${itemCount}][tipo]" class="form-control tipo-item" required>
                    <option value="">Seleccione Tipo</option>
                    <option value="PRODUCTO">Producto</option>
                    <option value="INSUMO">Insumo</option>
                </select>
            </div>
            <div class="col-md-4">
                <select name="items[${itemCount}][id_item]" class="form-control item-select" required disabled>
                    <option value="">Primero seleccione tipo</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" name="items[${itemCount}][cantidad]" class="form-control" placeholder="Cantidad" required>
            </div>
            <div class="col-md-2">
                <input type="number" step="0.01" name="items[${itemCount}][precio]" class="form-control" placeholder="Precio" required>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.item-row').remove()">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(newRow);
        itemCount++;
    }

    // Cargar productos o insumos según el tipo seleccionado
    $(document).on('change', '.tipo-item', function() {
        const row = $(this).closest('.item-row');
        const tipo = $(this).val();
        const selectItem = row.find('.item-select');

        if (!tipo) {
            selectItem.html('<option value="">Seleccione tipo primero</option>');
            selectItem.prop('disabled', true);
            return;
        }

        selectItem.html('<option value="">Cargando...</option>');
        selectItem.prop('disabled', false);

        const url = (tipo === 'PRODUCTO') ? '/admin/productos/list' : '/admin/insumos/list';
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                let options = `<option value="">Seleccione ${tipo === 'PRODUCTO' ? 'producto' : 'insumo'}</option>`;
                data.forEach(item => {
                    const id = (tipo === 'PRODUCTO') ? item.id_producto : item.id_insumo;
                    options += `<option value="${id}">${item.nombre} (Stock: ${item.stock_actual})</option>`;
                });
                selectItem.html(options);
            })
            .catch(error => {
                console.error('Error:', error);
                selectItem.html('<option value="">Error al cargar datos</option>');
            });
    });
</script>
@endsection