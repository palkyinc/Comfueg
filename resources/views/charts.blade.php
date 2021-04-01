@extends('layouts.plantilla')

    @section('contenido')
            <h1>Últimas 24hs.</h1>

            
            <!-- Chart's container -->
            <div id="chart24" style="height: 300px;"></div>
        @section('javascript')
            <!-- Charting library -->
            <script src="https://unpkg.com/echarts/dist/echarts.min.js"></script>
            <!-- Chartisan -->
            <script src="https://unpkg.com/@chartisan/echarts/dist/chartisan_echarts.js"></script>
            
            <!-- Your application script -->
            <script>
                const chart = new Chartisan({
                    el: '#chart24',
                    url: "@chart('twentyFour')",
                    options:{headers:{'cliente':19}},
                    hooks: new ChartisanHooks()
                    .colors(['#4299E1','#FE0045','#C07EF1','#67C560','#ECC94B'])
                    .datasets('line')
                    .axis(true)
                    .title('Cliente: DE TAL, Fulano - Ultimas 24hs')
                });
                console.log(chart);
            </script>
            <h2>En contrucción</h2>
        @endsection

    @endsection
