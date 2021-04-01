 @foreach ($contratos as $contrato)
    <div class="modal fade" id="staticBackdrop{{$contrato->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdrop{{$contrato->id}}Label">Consumos del Contrato</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </div>
                        <div class="modal-body">
                            
                            <div id="chart{{$contrato->id}}" style="height: 300px;"></div>
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
    function renderChart(id, nameApe)
    {
        const chart = new Chartisan({
            el: '#chart' + id,
            url: "@chart('twentyFour')",
            options:{headers:{'cliente': id}},
            hooks: new ChartisanHooks()
            .colors(['#4299E1','#FE0045','#C07EF1','#67C560','#ECC94B'])
            .datasets('line')
            .axis(true)
            .title('Cliente: ' + nameApe + ' - Ultimas 24hs')
        });
    }
</script>
@foreach ($contratos as $contrato)
    <script>renderChart({{$contrato->id}}, '{{$contrato->relCliente->getNomYApe()}}')</script>
@endforeach 
@endsection