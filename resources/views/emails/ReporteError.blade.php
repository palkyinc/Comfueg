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
    <h3>Errors.log</h3>

    <table>
        <caption>Informe:</caption>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Clase</th>
                <th>Método</th>
                <th>Nvedad</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($errores as $error)
                <tr>
                    <td>{{ $error[0] }}</td>
                    <td>{{ $error[1] }}</td>
                    <td>{{ $error[2] }}</td>
                    <td>{{ $error[3] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
</body>
</html>