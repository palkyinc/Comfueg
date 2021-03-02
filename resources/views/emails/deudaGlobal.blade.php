<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title>Nueva Incidencia GLobal</title>
</head>
<body>
    <h3>Deuda Técnica: {{ $incidente->mensaje_clientes }}.</h3>
    <ul>
        <li>Inicio: <strong><i>{{ $incidente->inicio }}</i></strong></li>
        <li>Sitio Equipo Afectado: <strong><i>{{ $incidente->relPanel->relSite->nombre }}</i></strong></li>
        <li>Deuda Técnica: <strong><i>{{ $incidente->causa }}</i></strong></li>
    </ul>
    <p>Información Extra:</p>
    <ul>
        <li>Creado por: <strong><i>{{ $incidente->relUser->name }}</i></strong></li>
        <li>Hora de Creado: <strong><i>{{ $incidente->created_at }}</i></strong></li>
        <li>
            <a href="http://{{$incidente->obtenerDominio()}}/inicio">
                Ver Incidencia
            </a>
        </li>
    </ul>
</body>
</html>