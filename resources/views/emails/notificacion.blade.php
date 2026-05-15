<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pet Spa - Notificación</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f8f9fa; padding: 30px; border-radius: 0 0 10px 10px; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #999; }
        .btn { display: inline-block; padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🐾 Pet Spa</h1>
        </div>
        <div class="content">
            <h2>¡Hola!</h2>
            <p>{!! nl2br(e($mensaje)) !!}</p>
            @if($mascotaNombre)
                <p><strong>Mascota:</strong> {{ $mascotaNombre }}</p>
            @endif
            <p>Gracias por confiar en nosotros.</p>
        </div>
        <div class="footer">
            <p>Pet Spa - El mejor cuidado para tu mascota</p>
            <p>© {{ date('Y') }} Pet Spa. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>