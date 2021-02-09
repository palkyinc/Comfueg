<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title>Actualización de Deuda Técnica</title>
</head>
<body>
    <h3>Actualización de Deuda Técnica: {{ $incidente->id }}</h3>

        @foreach ($incidente->incidente_has_mensaje as $mensaje)
            <ul>
                <li>Actualizado por: <strong><i>{{$mensaje->relUser->name}}</i></strong>. Fecha: <strong><i>{{$mensaje->created_at}}</i></strong></li>
                <li>Mensaje: <strong><i>{{$mensaje->mensaje}}</i></strong></li>
            </ul>
        @endforeach

    <h3>Informacíon Principal.</h3>

    <ul>
        <li>Inicio: <strong><i>{{ $incidente->inicio }}</i></strong></li>
        <li>Equipo Afectado: <strong><i>{{ $incidente->relPanel->relEquipo->nombre }}</i></strong></li>
        <li>IP Equipo Afectado: <strong><i>{{ $incidente->relPanel->relEquipo->ip }}</i></strong></li>
        <li>Sitio Afectado: <strong><i>{{ $incidente->relPanel->relSite->nombre }}</i></strong></li>
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