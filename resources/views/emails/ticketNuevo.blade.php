<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title>Nuevo Ticket</title>
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
		.titulo {
			background-color: #333;
			color: #fff;
		}
        .centrado{
            text-align: center;
        }
        .justificado{
            text-align: justify;
        }
		td, th {
            border: 1px solid #aaa;
		}
	</style>
</head>
<body>
    <h3>Nuevo Pedido de asistencia Técnica:</h3>

    <table>
        <caption>Resumen completo de ticket N° {{$issue->id}}</caption>
        <thead>
            <tr class="titulo">
                <th>Cliente</th>
                <th>Dirección</th>
                <th>Asignado</th>
            </tr>
        </thead>
        <tbody>
                <tr>
                    <td>{{$issue->relCliente->getNomYApe()}}<br>
                        {{$issue->relCliente->relCodAreaCel->codigoDeArea}} - 15 - {{$issue->relCliente->celular}}</td>
                    <td>
                        {{  $issue->relContrato->relDireccion->relCalle->nombre . ' ' . 
                            $issue->relContrato->relDireccion->numero . ', ' . 
                            $issue->relContrato->relDireccion->relBarrio->nombre}}
                        @if ($issue->relContrato->relDireccion->coordenadas != '')
                            <a href="https://www.google.com/maps/place/{{$issue->relContrato->relDireccion->coordenadas}}" target="_blank"
                                class="margenAbajo btn btn-link" title="Ver en Google maps">
                                (ver mapa)
                            </a>
                        @endif
                    </td>
                    <td>{{$issue->relAsignado->name}}</td>
                </tr>
                <tr class="titulo">
                    <td class="centrado">Titulo</td>
                    <td class="centrado">Creado por:</td>
                    <td class="centrado">Contrato</td>
                </tr>
                <tr>
                    <td class="centrado">{{$issue->relTitle->title}}</td>
                    <td>Creado {{$issue->created_at}} por {{$issue->relCreator->name}}</td>
                    <td class="centrado">{{$issue->relContrato->relPlan->nombre}}</td>
                </tr>
                <tr class="titulo">
                    <td class="centrado" colspan="3">Descripción del Problema</td>
                </tr>
                <tr>
                    <td colspan="3" class="justificado">{{$issue->descripcion}}</td>
                </tr>
        </tbody>
    </table>
    <h4>
        <a href="http://{{$issue->obtenerDominio()}}/modificarIssue/{{$issue->id}}">
                    Ver Incidencia
                </a>
    </h4>
</body>
</html>