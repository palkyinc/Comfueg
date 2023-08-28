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
            top: -160px;
            right: 0px;
            height: 100px;
            background-color: #ddd;
            text-align: center;
            }
            header h1{
            margin: 10px 0;
            }
            header h2{
            margin: 0 0 10px 0;
            }
            footer {
            position: fixed;
            left: 0px;
            bottom: -50px;
            right: 0px;
            height: 40px;
            border-bottom: 2px solid #ddd;
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
            <h1>Membrete de la empresa</h1>
            <h2>Comunicaciones Fueguinas SRL</h2>
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
                <p class="page">
                    Página
                </p>
                </td>
            </tr>
            </table>
        </footer>
        <div id="content">
            <div class="table-responsive">
                <table class="table table-xl">
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
                    <tbody>
                        <tr>
                        <td>1</td>
                        <td>1</td>
                        <td>1</td>
                        <td>1</td>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                        </tr>
                        <tr>
                        <td>2</td>
                        <td>2</td>
                        <td>2</td>
                        <td>2</td>
                        <td>Jacob</td>
                        <td>tdornton</td>
                        <td>@fat</td>
                        </tr>
                        <tr>
                        <td>3</td>
                        <td>3</td>
                        <td>3</td>
                        <td>3</td>
                        <td colspan="2">Larry the Bird</td>
                        <td>@twitter</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <p style="page-break-before: always;">
            Podemos romper la página en cualquier momento...</p>
            </p><p>
            Praesent pharetra enim sit amet...
            </p>
        </div>   
    </body>
</html>