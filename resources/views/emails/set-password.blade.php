<!DOCTYPE html>
<html>
<head>
    <title>Establece tu contraseña</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px; }
        .warning { color: #856404; background-color: #fff3cd; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🐾 ¡Bienvenido a Pet Spa!</h1>
        <p>Hola <strong>{{ $user->name }}</strong>,</p>
        <p>Se ha creado una cuenta para ti en Pet Spa con el rol de <strong>{{ $rol }}</strong>.</p>
        <p>Haz clic en el siguiente enlace para establecer tu contraseña:</p>
        <p style="text-align: center;">
            <a href="{{ url('/set-password/' . $token) }}" class="btn">Establecer mi contraseña</a>
        </p>
        <div class="warning">
            <strong>⚠️ Importante:</strong> Este enlace expirará en <strong>24 horas</strong>.
        </div>
        <p>Si no solicitaste esta cuenta, ignora este mensaje.</p>
        <hr>
        <small>Pet Spa - Tu mascota lo merece</small>
    </div>
</body>
</html>