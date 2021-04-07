 @foreach ($contratos as $contrato)
    <div class="modal fade" id="staticBackdrop{{$contrato->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdrop{{$contrato->id}}Label">Consumos del Contrato</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </div>
                    <div class="modal-body">
                        <div id="chartDay{{$contrato->id}}" style="height: 300px;"></div>
                        <div id="chartWeek{{$contrato->id}}" style="height: 300px;"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
            </div>
        </div>
    </div>
@endforeach
@section('javascript')
<!-- Your application script -->
<script>
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
</script>
@foreach ($contratos as $contrato)
    <script>renderChartDaily({{$contrato->id}}, '{{$contrato->relCliente->getNomYApe()}}')</script>
    <script>renderChartWeekly({{$contrato->id}})</script>
@endforeach 
@endsection