<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Carta Responsiva de Equipo de Cómputo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.2;
            margin: 0.8cm;
            font-size: 11pt;
        }
        .logo {
            height: 60px; /* Altura fija para controlar espacio */
            width: auto;
            margin: 0 auto 5px auto; /* Margen reducido */
            display: block;
        }
        h1 {
            text-align: center;
            font-size: 14pt;
            margin-bottom: 10px;
            text-decoration: underline;
        }
        .info-field {
            margin-bottom: 8px;
        }
        .equipo-list {
            list-style-type: none;
            padding-left: 0;
            margin: 10px 0;
        }
        .equipo-list li {
            margin-bottom: 5px;
        }
        .signature-section {
            margin-top: 25px;
        }
        .signature-line {
            border-top: 1px solid black;
            width: 250px;
            margin-top: 40px;
            text-align: center;
            padding-top: 3px;
        }
        .footer {
            font-size: 9pt;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header" style="text-align: center;">
        @if(isset($logoBase64))
            <img src="data:image/jpeg;base64,{{ $logoBase64 }}" alt="Logo Grupo Pryse" class="logo">
        @endif       
    </div>

    <h1>CARTA RESPONSIVA DE EQUIPO DE CÓMPUTO</h1>
    
    <div class="info-field"><strong>Nombre:</strong> {{ $equipo->nombre_persona }}</div>
    <div class="info-field"><strong>Puesto:</strong> {{ $equipo->puesto }}</div>
    
    <p>Por medio de la presente, hago constar que he recibido en perfectas condiciones el siguiente equipo 
    de cómputo, propiedad de <strong>Grupo Pryse</strong> para el desempeño de mis funciones laborales:</p>
    
    <h3>Datos del equipo:</h3>
    <ul class="equipo-list">
        <li><strong>Tipo de equipo:</strong> {{ $equipo->tipo_pc }}</li>
        <li><strong>Marca:</strong> {{ $equipo->marca_equipo }}</li>
        <li><strong>Procesador:</strong> {{ $equipo->procesador }}</li>
        <li><strong>Memoria RAM:</strong> {{ $equipo->capacidad_ram }} {{ $equipo->tipo_ram ? '('.$equipo->tipo_ram.')' : '' }}</li>
        <li><strong>Disco Duro/SSD:</strong> {{ $equipo->tipo_disco }} {{ $equipo->capacidad_disco }}</li>
        <li><strong>Accesorios entregados:</strong> 
            {{ $equipo->otro_periferico ?? 
                ($equipo->tipo_pc === 'Laptop' ? 'N/A' : 'Monitor, teclado y mouse') 
            }}
        </li>
    </ul>
    
    <p>Me comprometo a hacer un uso adecuado y responsable de este equipo, dándole únicamente el uso
    para el que ha sido destinado dentro de las actividades laborales de la empresa/institución. Asimismo,
    reconozco que, en caso de daño, extravío o mal uso del equipo por negligencia, seré responsable de su
    reparación o reposición según las políticas internas de  <strong>Grupo Pryse</strong>.</p>
    
    <p>En caso de separación laboral o por requerimiento de la empresa, me comprometo a devolver el
    equipo en las mismas condiciones en las que me fue entregado, salvo el desgaste natural por uso.</p>

    <div style="height: 100px;"></div> <!-- Espacio controlado entre texto y firmas -->

    <div class="signature-section" style="text-align: center;">
        <div class="signature-line" style="margin: 0 auto;"><strong>Firma</strong></div>
        
        <div style="height: 50Px;"></div> <!-- Espacio controlado entre texto y firmas -->

        <div style="margin-top: 50px;">
            <div class="signature-line" style="margin: 0 auto;"><strong>Nombre y Firma del responsable TI</strong></div>
        </div>
    </div>
    
    <div class="footer">
        <p>Documento generado el: {{ $fecha }}</p>
    </div>
</body>
</html>