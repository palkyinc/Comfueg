<div class="">
        <div id="chartInsta{{$contrato->id}}" style="height: 300px;"></div>
        <div id="chartDay{{$contrato->id}}" style="height: 300px;"></div>
        <div id="chartWeek{{$contrato->id}}" style="height: 300px;"></div>
        <div id="chartMonthly{{$contrato->id}}" style="height: 300px;"></div>
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
                chart.update({options:{headers:{'cliente': id, 'status-chart': 0}}});
            }, 5000);
        }

        function renderChartDaily(id, nameApe)
        {
            const chart = new Chartisan({
                el: '#chartDay' + id,
                url: "@chart('twentyFour')",
                options:{headers:{'cliente': id}},
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
                            text: 'Uff! Hubo un error...',
                            textColor: '#67C560',
                            type: 'general',
                            debug: true,
                        },
                hooks: new ChartisanHooks()
                .colors(['#4299E1','#FE0045','#C07EF1','#67C560','#ECC94B'])
                .datasets(['line', 'bar'])
                .title('Cliente: ' + nameApe + ' - Ultimas 24hs')
                .legend({ position: 'left' })
            });
        }
        
        function renderChartMounthly(id, conteo_id)
        {
            const chart = new Chartisan({
                el: '#chartMonthly' + id,
                url: "@chart('mounthly')",
                options:{headers:{'conteo-id': conteo_id}},
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
                            text: 'Uff! Hubo un error...',
                            textColor: '#67C560',
                            type: 'general',
                            debug: true,
                        },
                hooks: new ChartisanHooks()
                .legend(true)
                .colors(['#67C560','#FE0045','#C07EF1','#ECC94B','#4299E1'])
                .datasets(['bar', 'line'])
                .title('Ultimo Año')
                .legend({ position: 'left' })
            });
        }

        function renderChartWeekly(id)
        {
            const chart = new Chartisan({
                el: '#chartWeek' + id,
                url: "@chart('weekly')",
                options:{   headers:{'cliente': id},
                            scales: {
                                    y:  {
                                        ticks:  {
                                                stepSize: 2
                                                }
                                        }
                                    }
                        },
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
                            text: 'Uff! Hubo un error...',
                            textColor: '#67C560',
                            type: 'general',
                            debug: true,
                        },
                hooks: new ChartisanHooks()
                .colors(['#4299E1','#FE0045','#C07EF1','#67C560','#ECC94B'])
                .datasets([{ type: 'line', fill: true }, 'bar'])
                .title({text: 'Última semana'})
                .legend(false)
                .options({spanGaps: 1000 * 60 * 60 * 24, scales:{x:{type: 'time', display: true}}})
            });
        }
        let zona = document.getElementById('zona');
        let btnActivar = zona.getElementsByClassName('boton-Alta');
        for (let i = 0; i < btnActivar.length; i++) {
            btnActivar[i].addEventListener('click', e => {
                if(!confirm("¿Seguro de Dar Alta?")) {
                    e.preventDefault()
                }
                
            })
        }
        let btnDesactivar = zona.getElementsByClassName('boton-Baja');
        for (let i = 0; i < btnDesactivar.length; i++) {
            btnDesactivar[i].addEventListener('click', e => {
                if(!confirm("¿Seguro de Dar de Baja?")) {
                    e.preventDefault()
                }
            })
        }
    </script>
        <script>
                let id = {{$contrato->id}}; 
                renderChartInsta( id, '{{$contrato->relCliente->getNomYApe()}}');        
        </script>
        <script>renderChartDaily({{$contrato->id}}, '{{$contrato->relCliente->getNomYApe()}}')</script>
        <script>renderChartWeekly({{$contrato->id}})</script>
        <script>renderChartMounthly({{$contrato->id}}, {{$conteo->id}})</script>