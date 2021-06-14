<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title>Cambio de estado de Proveedor</title>
</head>
<body>
    <h3>Cambio de Estado: {{ $proveedor->nombre }}.</h3>
    <ul>
        <li>Estado Actual: <strong>{!! $proveedor->en_linea ? "<FONT COLOR='GREEN'>EN LINEA</FONT>" : '<FONT COLOR="RED">FUERA DE LINEA</FONT>' !!}</strong></li>
        <li>Registrado a las : <strong><i>{{ $proveedor->updated_at }}</i></strong></li>
        <li>Gateway: <strong><i>{{ $proveedor->relGateway->relEquipo->nombre }}</i></strong></li>
        <li>Sitio: <strong><i>{{ $proveedor->relGateway->relSite->nombre }}</i></strong></li>
    </ul>
    <p>Información Extra:</p>
    <ul>
        <li>
            <a href="http://{{$proveedor->obtenerDominio()}}/adminProveedores">
                Ver Proveedores
            </a>
        </li>
    </ul>
</body>
</html>