<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verifica tu cuenta</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px; }
        .warning { color: #856404; background-color: #fff3cd; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🐾 ¡Bienvenido a Pet Spa!</h1>
        <p>Hola <strong>{{ $user->name }}</strong>,</p>
        <p>Gracias por registrarte en Pet Spa. Para activar tu cuenta, haz clic en el siguiente enlace:</p>
        <p style="text-align: center;">
            <a href="{{ $verificationUrl }}" class="btn">Verificar mi cuenta</a>
        </p>
        <p>O copia y pega este enlace en tu navegador:</p>
        <p><code>{{ $verificationUrl }}</code></p>
        <div class="warning">
            <strong>⚠️ Importante:</strong> Este enlace expirará en <strong>15 minutos</strong>.
        </div>
        <p>Si no solicitaste esta verificación, ignora este mensaje.</p>
        <hr>
        <small>Pet Spa - Tu mascota lo merece</small>
    </div>
</body>
</html>