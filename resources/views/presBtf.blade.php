<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>PDF Test</title>
        <style>
            body{
            font-family: sans-serif;
            }
            @page {
            margin: 160px 50px;
            }
            header { position: fixed;
            left: 0px;
            top: -130px;
            right: 0px;
            height: 100px;
            background-color: #ffffff;
            text-align: center;
            }
            header h1{
            margin: 10px 0;
            }
            header h2{
            margin: 0 0 10px 0;
            }
            .tar {
                text-align: right
            }
            .tac {
                text-align: center
            }
            .ti-20{
                text-indent: 20%;
            }
            .ti-10{
                text-indent: 10%;
            }
            footer {
            position: fixed;
            left: 0px;
            bottom: -10px;
            right: 0px;
            height: 40px;
            border-bottom: 2px solid #4d4c4c;
            }
            footer .page:after {
            content: counter(page);
            }
            footer table {
            width: 100%;
            }
            footer p {
            text-align: right;
            }
            footer .izq {
            text-align: left;
            }
            table{ width: 100%;}
            th, td {margin: 0%; padding: 0%}
            th {
                border-top: 2px solid;
                border-bottom: 2px solid;
            }
        </style>
    </head>
    <body>
        <header>
            <img src="imagenes/logoCF.jpg" height="100px" alt="">
        </header>
        <footer>
            <table>
            <tr>
                <td>
                    <p class="izq">
                    Comunicaciones Fueguinas SRL
                    </p>
                </td>
                <td>
                    <img src="imagenes/FirmaMG.png" height="200px" alt="">
                </td>
                <td>
                    <p class="page">
                        Página
                    </p>
                </td>
            </tr>
        </table>
</footer>
<div id="content">
            <p class="tar">Río Grande, {{$fecha_presentacion_full}}</p>
            <p>Sres.:</p>
            <p><b>BANCO DE LA PROVINCIA DE</b></p>
            <P><b>TIERRA DEL FUEGO:</b></P>
            <P class="tar"><b><u>REF.:N° ENTE 198</u></b></P>
            <P>De mi mayor consideración:</P>
            <p class="ti-20">Me dirijo a Ustedes a los efectos de presentar el Listado de débitos automáticos correspondientes al mes de <b>{{$mes_anio}}</b></p>
            <p class="ti-10">Total de Clientes: {{$cant_debitos}}</p>
            <p class="ti-10">Importe: {{$tot_importe}}</p>
            <p class="ti-20">Sin otro particular saluda a Ustedes muy atentamente.</p>
            <p style="page-break-before: always;"></p>


            <div class="table-responsive">
                <table class="table table-xl">
                    
                    @for ($i = 0; $i < $cant_debitos; $i++)
                        @if ($i%35 === 0)
                            <thead>
                                <tr>
                                <th>Documento</th>
                                <th>Nro. de Cliente</th>
                                <th>Suc.</th>
                                <th>Tipo Cta.</th>
                                <th>Cuenta</th>
                                <th>Importe</th>
                                <th>fecha</th>
                                </tr>
                            </thead>  
                        @endif
                        <tbody>
                            <tr>
                                <td class="tac">{{$debitos[$i]->dni}}</td>
                                <td class="tac">{{$debitos[$i]->cliente_id}}</td>
                                <td class="tac">{{$debitos[$i]->sucursal}}</td>
                                <td class="tac">{{$debitos[$i]->tipo_cuenta}}</td>
                                <td class="tac">{{$debitos[$i]->cuenta}}</td>
                                <td class="tar">{{$debitos[$i]->getImporteFormateado()}}</td>
                                <td class="tac">{{$fecha_presentacion_short}}</td>
                            </tr>
                        </tbody>
                        @if (($i + 1 )%35 === 0 && $key !== 0)
                            </table>
                            <p style="page-break-before: always;"></p>                
                        @endif
                    @endfor
                </table>
            </div>
        </div>   
    </body>
</html>