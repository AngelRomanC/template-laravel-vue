<!-- resources/views/emails/licitacion_aviso.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aviso de Licitación</title>
</head>
<body>
    <h1>Aviso de Licitación</h1>
    <p>Estimado usuario,</p>
    <p>La licitación "{{ $documento->nombre_documento }}" vencerá en {{ $documento->dias_restantes }} días.</p>
    <p>Por favor, tome las medidas necesarias.</p>
</body>
</html>
