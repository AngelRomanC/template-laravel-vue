<!DOCTYPE html>
<html>
<head>
    <title>Aviso de Licitación Próxima a Vencer</title>
</head>
<body>
    <h2>Estimado usuario,</h2>
    <p>Le informamos que la licitación <strong>{{ $documento->nombre_documento }}</strong> está próxima a vencer.</p>
    <p>Fecha de vencimiento: <strong>{{ $documento->fecha_vigencia }}</strong></p>
    <p>Le quedan <strong>7 días</strong> para realizar las acciones necesarias.</p>
    <br>
    <p>Atentamente,</p>
    <p>El equipo de administración</p>
</body>
</html>
