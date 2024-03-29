    @section('javascript')
    <!-- Your application script -->
    <script>
        function renderChartPieIssues(datos, id)
        {
            const chart = new Chartisan({
                el: '#chartTortaIssues' + id,
                url: "@chart('tortaIssues')",
                options:{headers:{'datos': datos, 'status-chart': 1}},
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
                            text: 'Error cargando Datos...',
                            textColor: '#67C560',
                            type: 'general',
                            debug: true,
                        },
                hooks: new ChartisanHooks()
                .colors()
                .datasets(['pie'])
                .title()
                .legend({ bottom: 0 })
                .axis(false)
            });
        }
        
    </script>
        <script>
                renderChartPieIssues('{{$total_tickets['tipos']}}', 1);        
                renderChartPieIssues('{{$total_tickets_180['tipos']}}', 2);        
        </script>
    @endsection