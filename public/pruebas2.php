<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" href="/cssboot/easy-autocomplete.min.css">
	<link rel="stylesheet" href="/cssboot/easy-autocomplete.themes.min.css">
	<script src="/jsboot/jquery-3.5.1.slim.min.js"></script>
	<script src="/jsboot/popper.min.js"></script>
	<script src="/jsboot/bootstrap.min.js"></script>
	<script src="/jsboot/sweetalert.min.js"></script>
	<script src="/jsboot/jquery.easy-autocomplete.min.js"></script>
	<link rel="stylesheet" href="/css/estilos.css">
</head>
<body>

</body>
</html>

<input type="text" id="nombreSearch">

 <script>
        let datosArray=[], optionsFinal;
        fetch('/searchBarrios')
        .then(valor=>valor.json())
        .then(valor=>
            {
                valor.forEach(element => datosArray.push(element.nombre));
            });
        optionsFinal = {data : datosArray, list:{match:{enabled:true}}};
        console.log(optionsFinal);
        $("#nombreSearch").easyAutocomplete(optionsFinal);
    </script>