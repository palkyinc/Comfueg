@extends('layouts.plantilla')

    @section('contenido')
            <h1>Vista Prinicipal de Charts</h1>

            <h2>En contrucción</h2>
            
            <!-- Chart's container -->
    <div id="chart" style="height: 300px;"></div>
    @section('javascript')
        <!-- Charting library -->
       <script src="https://unpkg.com/echarts/dist/echarts.min.js"></script>
        <!-- Chartisan -->
        <script src="https://unpkg.com/@chartisan/echarts/dist/chartisan_echarts.js"></script>
        
        <!-- Your application script -->
        <script>
        const chart = new Chartisan({
            el: '#chart',
            url: "@chart('my_chart')",
            hooks: new ChartisanHooks()
             .colors(['#4299E1','#FE0045','#C07EF1','#67C560','#ECC94B'])
                .datasets('line')
                .axis(true)
        });
        </script>
    @endsection

    @endsection
