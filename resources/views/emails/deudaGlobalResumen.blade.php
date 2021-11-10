<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title>Incidencias GLobales sin Resolver</title>
    <style>
		body {
			font-family: Verdana, Arial, sans-serif;
			font-size: 12px;
		}
		table {
			border: 1px solid #333;
			width: 800px;
			border-collapse: collapse;
		}
		th {
			background-color: #333;
			color: #fff;
		}
		td, th {
			border: 1px solid #aaa;
		}
	</style>
</head>
<body>
    <h3>Información importante de resolución:</h3>

    <table>
        <caption>Deudas técnicas pendientes de resolución</caption>
        <thead>
            <tr>
                <th>Título</th>
                <th>Inicio</th>
                <th>Sitio</th>
                <th>Causa</th>
                <th>Creada</th>
                <th>Tiempo s/resolver</th>
                <th>Programada</th>
                <th>Prioridad</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($deudas as $incidente)
                <tr>
                    <td>{{ $incidente->mensaje_clientes }}.</td>
                    <td>{{ $incidente->inicio }}</td>
                    <td>{{ $incidente->relPanel->relSite->nombre }}</td>
                    <td>{{ $incidente->causa }}</td>
                    <td>{{ $incidente->relUser->name }}</td>
                    <td>{{ $incidente->tiempoCaida() }}</td>
                    <td>{{ $incidente->fecha_tentativa }}</td>
                    <td>
                        @switch($incidente->prioridad)
                            @case(1)
                                Alta
                                @break
                            @case(2)
                                Media
                                @break
                            @case(3)
                                Baja
                                @break
                            @default
                                Sin Datos
                        @endswitch
                    </td>
                </tr>
        </tbody>
        @endforeach
    </table>
    
</body>
</html>