<div class="">
        <div id="chartInsta{{$contrato->id}}" style="height: 300px;"></div>
    </div>
    @section('javascript')
    <!-- Your application script -->
    <script>
        function renderChartInsta(id, nameApe)
        {
            const chart = new Chartisan({
                el: '#chartInsta' + id,
                url: "@chart('insta')",
                options:{headers:{'cliente': id, 'status-chart': 1}},
                loader: {
                            color: '#ff00ff',
                            size: [30, 30],
                            type: 'bar',
                            textColor: '#67C560',
                            text: 'Cargando Gráfico...',
                        },
                error: {
                            color: '#ff00ff',
                            size: [30, 30],
                            text: 'Esperando Datos de la Antena...',
                            textColor: '#67C560',
                            type: 'general',
                            debug: true,
                        },
                hooks: new ChartisanHooks()
                .colors(['#4299E1','#FE0045','#C07EF1','#67C560','#ECC94B'])
                .datasets(['line', 'line'])
                .title('Cliente: ' + nameApe + ' - Tráfico en actual')
                .legend({ position: 'left' })
            });
            setInterval(() => {
                chart.update({options:{headers:{'status-chart': 0}}});
            }, 5000);
        }
        
    </script>
        <script>
                renderChartInsta({{$contrato->id}}, '{{$contrato->relCliente->getNomYApe()}}');        
        </script>