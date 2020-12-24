<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title>Nueva Incidencia GLobal</title>
</head>
<body>
    <h3>Datos del Incidente: {{ $incidente->crearNombre() }}.</h3>
    <ul>
        <li>Inicio: <strong><i>{{ $incidente->inicio }}</i></strong></li>
        <li>Finalizado: <strong><i>{{ 'Aun ocurriendo.' }}</i></strong></li>
        <li>Equipo Afectado: <strong><i>{{ $incidente->relPanel->relEquipo->nombre }}</i></strong></li>
        <li>IP Equipo Afectado: <strong><i>{{ $incidente->relPanel->relEquipo->ip }}</i></strong></li>
        <li>Sitio Equipo Afectado: <strong><i>{{ $incidente->relPanel->relSite->nombre }}</i></strong></li>
        <li>Sitios Afectados: <strong><i>{{ $incidente->sitios_afectados }}</i></strong></li>
        <li>Paneles Afectados Indirectamente: <strong><i>{{ $incidente->afectados_indi }}</i></strong></li>
        <li>Barrios Afectados: <strong><i>{{ $incidente->barrios_afectados }}</i></strong></li>
        <li>Causa/Diagnostico: <strong><i>{{ $incidente->causa }}</i></strong></li>
        <li>Mensaje para los Clientes: <strong><i>{{ $incidente->mensaje_clientes }}</i></strong></li>
    </ul>
    <p>Información Extra:</p>
    <ul>
        <li>Creado por: <strong><i>{{ $incidente->relUser->name }}</i></strong></li>
        <li>Hora de Creado: <strong><i>{{ $incidente->created_at }}</i></strong></li>
        <li>
            <a href="http://{{$incidente->obtenerDominio()}}/modificarSiteHasIncidente/{{$incidente->id}}">
                Ver Incidencia
            </a>
        </li>
    </ul>
</body>
</html>