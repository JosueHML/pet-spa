<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\GroomingController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\Admin\AgendaBloqueoController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\Admin\SolicitudFacturaController;
use App\Http\Controllers\Admin\CierreCajaController;
use App\Http\Controllers\Admin\PromocionController;
use Illuminate\Http\Request;  // 👈 ESTO ES IMPORTANTE


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// =============================================
// RUTAS DE AUTENTICACIÓN
// =============================================
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

// =============================================
// RECUPERACIÓN DE CONTRASEÑA
// =============================================
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

// =============================================
// LOGIN CON GOOGLE
// =============================================
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// =============================================
// CAMBIO DE CONTRASEÑA OBLIGATORIO
// =============================================
Route::get('/set-password/{token}', [ResetPasswordController::class, 'showSetPasswordForm'])->name('password.set');
Route::post('/set-password', [ResetPasswordController::class, 'setPassword'])->name('password.set.submit');

// =============================================
// GESTIÓN DE CLIENTES (ADMIN)
// =============================================
Route::get('/admin/clientes', [App\Http\Controllers\Admin\ClienteController::class, 'index'])->name('admin.clientes.index');
Route::get('/admin/clientes/{id}/edit', [App\Http\Controllers\Admin\ClienteController::class, 'edit'])->name('admin.clientes.edit');
Route::put('/admin/clientes/{id}', [App\Http\Controllers\Admin\ClienteController::class, 'update'])->name('admin.clientes.update');

// =============================================
// RUTAS PROTEGIDAS
// =============================================
Route::middleware(['auth', 'check.inactivity', 'password.check'])->group(function () {

    // DASHBOARDS
    Route::get('/admin/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');    Route::get('/cajero/dashboard', function () { return view('dashboard.cajero'); })->name('cajero.dashboard');
    Route::get('/groomer/dashboard', function () { return view('dashboard.groomer'); })->name('groomer.dashboard');
    Route::get('/cliente/dashboard', function () { return view('dashboard.cliente'); })->name('cliente.dashboard');

    // CAMBIO DE CONTRASEÑA
    Route::get('/change-password', [ChangePasswordController::class, 'showChangeForm'])->name('password.change');
    Route::post('/change-password', [ChangePasswordController::class, 'change'])->name('password.change.submit');

    // ADMINISTRADOR - GESTIÓN DE PERSONAL
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::put('/admin/users/{id}/activate', [UserController::class, 'activate'])->name('admin.users.activate');
    Route::put('/admin/users/{id}/deactivate', [UserController::class, 'deactivate'])->name('admin.users.deactivate');

    // ADMINISTRADOR - REPORTES Y LOGS
    Route::get('/admin/reports', [ReportController::class, 'index'])->name('admin.reports');
    Route::get('/admin/logs', [LogController::class, 'index'])->name('admin.logs');

    // ADMINISTRADOR - BLOQUEOS
    Route::get('/admin/bloqueos', [AgendaBloqueoController::class, 'index'])->name('admin.bloqueos.index');
    Route::post('/admin/bloqueos', [AgendaBloqueoController::class, 'store'])->name('admin.bloqueos.store');
    Route::delete('/admin/bloqueos/{id}', [AgendaBloqueoController::class, 'destroy'])->name('admin.bloqueos.destroy');

    // =============================================
    // PRODUCTOS (VISUALIZACIÓN PARA TODOS)
    // =============================================
    Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
    Route::get('/productos/{id}', [ProductoController::class, 'show'])->name('productos.show');

    // =============================================
    // PRODUCTOS (CRUD SOLO ADMIN)
    // =============================================
    Route::middleware(['role:1'])->group(function () {
        Route::get('/productos/create', [ProductoController::class, 'create'])->name('productos.create');
        Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
        Route::get('/productos/{id}/edit', [ProductoController::class, 'edit'])->name('productos.edit');
        Route::put('/productos/{id}', [ProductoController::class, 'update'])->name('productos.update');
        Route::delete('/productos/{id}', [ProductoController::class, 'destroy'])->name('productos.destroy');
    });

    // SERVICIOS (CRUD SOLO ADMIN)
    Route::middleware(['role:1'])->group(function () {
        Route::resource('servicios', ServicioController::class);
    });

    // CAJERO Y ADMIN - FACTURAS Y CITAS
    Route::resource('facturas', FacturaController::class);
    Route::get('/facturas/create/{cita_id}', [FacturaController::class, 'create'])->name('facturas.create.cita');
    Route::resource('citas', CitaController::class);
    Route::get('/citas/{id}/cancel', [CitaController::class, 'cancel'])->name('citas.cancel');

    // CLIENTE - MASCOTAS
    Route::resource('mascotas', MascotaController::class);

    // VERIFICACIÓN DE EMAIL
    Route::get('/verify-email/{token}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('/resend-verification', [VerificationController::class, 'resend'])->name('verification.resend');

    // 2FA
    Route::get('/2fa/setup', [TwoFactorController::class, 'showSetup'])->name('2fa.setup');
    Route::post('/2fa/enable', [TwoFactorController::class, 'enable'])->name('2fa.enable');
    Route::get('/2fa/verify', [TwoFactorController::class, 'showVerify'])->name('2fa.verify');
    Route::post('/2fa/verify', [TwoFactorController::class, 'verify']);
    Route::post('/2fa/disable', [TwoFactorController::class, 'disable'])->name('2fa.disable');

    // =============================================
    // GROOMING (TODAS LAS RUTAS JUNTAS)
    // =============================================
    Route::prefix('grooming')->group(function () {
        Route::get('/{cita_id}', [GroomingController::class, 'show'])->name('grooming.show');
        Route::post('/upload-foto', [GroomingController::class, 'uploadFoto'])->name('grooming.upload_foto');
        Route::delete('/delete-foto/{id}/{tipo}', [GroomingController::class, 'deleteFoto'])->name('grooming.delete_foto');
        Route::post('/update-checklist/{id}', [GroomingController::class, 'updateChecklist'])->name('grooming.update.checklist');
        Route::post('/update-estado/{id}', [GroomingController::class, 'updateEstado'])->name('grooming.update.estado');
        Route::post('/update-insumos/{id}', [GroomingController::class, 'updateInsumos'])->name('grooming.update.insumos');
        Route::post('/recibir-insumos/{id}', [GroomingController::class, 'recibirInsumos'])->name('grooming.recibir.insumos');
        Route::post('/cerrar/{id}', [GroomingController::class, 'cerrarServicio'])->name('grooming.cerrar');
    });

    // =============================================
    // GROOMER - RUTAS ESPECÍFICAS
    // =============================================
    Route::prefix('groomer')->group(function () {
        Route::get('/fichas-activas', function() {
            $fichas = App\Models\FichaGrooming::with(['cita.mascota', 'cita.servicio'])
                ->where('estado_ficha', 'ABIERTA')
                ->get();
            return view('groomer.fichas_activas', compact('fichas'));
        })->name('grooming.fichas.activas');

        Route::get('/checklist', function() {
            $fichas = App\Models\FichaGrooming::with(['cita.mascota', 'cita.servicio'])
                ->whereNotNull('checklist_json')
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get();
            return view('groomer.checklist', compact('fichas'));
        })->name('grooming.checklist');

        Route::get('/fotos', function() {
            $fichas = App\Models\FichaGrooming::with(['cita.mascota'])
                ->whereNotNull('foto_antes')
                ->orWhereNotNull('foto_despues')
                ->orderBy('created_at', 'desc')
                ->get();
            return view('groomer.fotos', compact('fichas'));
        })->name('grooming.fotos');
    });

    // CARRITO DE COMPRAS
    Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
    Route::post('/carrito/add', [CarritoController::class, 'add'])->name('carrito.add');
    Route::put('/carrito/update/{id}', [CarritoController::class, 'update'])->name('carrito.update');
    Route::delete('/carrito/remove/{id}', [CarritoController::class, 'remove'])->name('carrito.remove');
    Route::delete('/carrito/vaciar', [CarritoController::class, 'vaciar'])->name('carrito.vaciar');
    Route::get('/carrito/whatsapp', [CarritoController::class, 'whatsapp'])->name('carrito.whatsapp');
    Route::post('/carrito/solicitar-factura', [CarritoController::class, 'solicitarFactura'])->name('carrito.solicitar-factura');

    // NOTIFICACIONES
    Route::get('/mis-notificaciones', [NotificacionController::class, 'index'])->name('notificaciones.index');
    Route::get('/notificacion/marcar-leida/{id}', [NotificacionController::class, 'marcarLeida'])->name('notificacion.marcar-leida');

    // SOLICITUDES DE FACTURA (ADMIN Y CAJERO)
    Route::get('/admin/solicitudes', [SolicitudFacturaController::class, 'index'])->name('admin.solicitudes.index');
    Route::get('/admin/solicitudes/{id}', [SolicitudFacturaController::class, 'show'])->name('admin.solicitudes.show');
    Route::post('/admin/solicitudes/{id}/aprobar', [SolicitudFacturaController::class, 'aprobar'])->name('admin.solicitudes.aprobar');
    Route::post('/admin/solicitudes/{id}/rechazar', [SolicitudFacturaController::class, 'rechazar'])->name('admin.solicitudes.rechazar');

    // PROMOCIONES
    Route::resource('admin/promociones', PromocionController::class)->names([
        'index' => 'admin.promociones.index',
        'create' => 'admin.promociones.create',
        'store' => 'admin.promociones.store',
        'edit' => 'admin.promociones.edit',
        'update' => 'admin.promociones.update',
        'destroy' => 'admin.promociones.destroy',
    ]);
    Route::get('/admin/promociones/{id}/toggle', [PromocionController::class, 'toggle'])->name('admin.promociones.toggle');

    // CIERRES DE CAJA (CRUD COMPLETO)
    Route::resource('admin/cierres', CierreCajaController::class)->names([
        'index' => 'admin.cierres.index',
        'create' => 'admin.cierres.create',
        'store' => 'admin.cierres.store',
        'show' => 'admin.cierres.show',
        'edit' => 'admin.cierres.edit',
        'update' => 'admin.cierres.update',
        'destroy' => 'admin.cierres.destroy',
    ]);

    // RUTAS DE PRUEBAS PARA ADMIN (SOLO UNA VEZ, NO DUPLICADAS)
    Route::middleware(['role:1'])->group(function () {
        Route::get('/admin/citas/crear-prueba', [App\Http\Controllers\Admin\PruebaController::class, 'crearCitaPrueba'])->name('admin.citas.crear-prueba');
        Route::get('/admin/recordatorios/enviar', [App\Http\Controllers\Admin\PruebaController::class, 'enviarRecordatorios'])->name('admin.recordatorios.enviar');
    });

    // Panel de pruebas para Admin
    Route::middleware(['auth', 'role:1'])->group(function () {
        Route::get('/admin/pruebas', [App\Http\Controllers\Admin\PruebaController::class, 'index'])->name('admin.pruebas');
        Route::post('/admin/pruebas/crear-cita', [App\Http\Controllers\Admin\PruebaController::class, 'crearCita'])->name('admin.pruebas.crear-cita');
        Route::post('/admin/pruebas/enviar-recordatorios', [App\Http\Controllers\Admin\PruebaController::class, 'enviarRecordatorios'])->name('admin.pruebas.enviar-recordatorios');
    });

    Route::get('/admin/productividad-groomer', [App\Http\Controllers\Admin\ReportController::class, 'productividadGroomer'])->name('admin.productividad.groomer');

    Route::get('/mascotas/{id}/recomendaciones', [MascotaController::class, 'recomendaciones'])->name('mascotas.recomendaciones');

    Route::get('/citas/horarios-ocupados/{groomer_id}/{fecha}', [CitaController::class, 'getHorariosOcupados'])->name('citas.horarios.ocupados');


});

// RUTA HOME
Route::get('/home', function () {
    return redirect()->route('cliente.dashboard');
})->middleware(['auth']);

if (file_exists(__DIR__.'/auth.php')) {
    require __DIR__.'/auth.php';
}

// Alertas de consumo - SOLO UNA VEZ
Route::get('/admin/alertas-consumo', function() {
    $alertas = DB::table('notificaciones')
        ->where('tipo', 'CONSUMO_ELEVADO')
        ->where('estado', 'PENDIENTE')
        ->orderBy('created_at', 'desc')
        ->get();
    return view('admin.alertas_consumo', compact('alertas'));
})->name('admin.alertas.consumo');

Route::post('/admin/alertas/actualizar/{id}', function($id, Request $request) {
    $accion = $request->input('accion');
    $estado = ($accion == 'aprobar') ? 'APROBADA' : 'RECHAZADA';
    
    DB::table('notificaciones')
        ->where('id_notificacion', $id)
        ->update([
            'estado' => $estado,
            'updated_at' => now()
        ]);
    
    return redirect()->back()->with('success', "Alerta {$estado} correctamente");
})->name('admin.alertas.actualizar');

Route::post('/admin/alertas/actualizar/{id}', function($id, Request $request) {
    $accion = $request->input('accion');
    $estado = ($accion == 'aprobar') ? 'APROBADA' : 'RECHAZADA';
    
    // Obtener la alerta antes de actualizar
    $alerta = DB::table('notificaciones')->where('id_notificacion', $id)->first();
    
    // Extraer el groomer del mensaje (ej: "⚠️ ALERTA: El groomer Ana Estilista registró...")
    preg_match('/El groomer ([^ ]+)/', $alerta->mensaje, $matches);
    $nombreGroomer = $matches[1] ?? 'Desconocido';
    
    // Buscar el ID del usuario groomer por su nombre
    $groomerUser = DB::table('users')->where('name', 'like', "%{$nombreGroomer}%")->first();
    
    // Crear notificación para el GROOMER
    $mensajeGroomer = ($accion == 'aprobar') 
        ? "✅ Tu solicitud de consumo elevado ha sido APROBADA por el administrador."
        : "❌ Tu solicitud de consumo elevado ha sido RECHAZADA por el administrador. Por favor, justifica el consumo.";
    
    if ($groomerUser) {
        DB::table('notificaciones')->insert([
            'id_usuario' => $groomerUser->id,
            'tipo' => 'RESPUESTA_CONSUMO',
            'mensaje' => $mensajeGroomer,
            'canal' => 'EMAIL',
            'destino' => $groomerUser->email,
            'estado' => 'ENVIADO',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
    
    // Actualizar la alerta original
    DB::table('notificaciones')
        ->where('id_notificacion', $id)
        ->update([
            'estado' => $estado,
            'updated_at' => now()
        ]);
    
    return redirect()->back()->with('success', "Alerta {$estado}. Se notificó al groomer.");
})->name('admin.alertas.actualizar');