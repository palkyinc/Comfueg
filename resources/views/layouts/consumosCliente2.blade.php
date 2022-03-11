    <script src="/js/resources/consumosCliente.js"></script>
    <script>renderChartInsta( {{$contrato->id}}, '{{$contrato->relCliente->getNomYApe()}}')</script>
    <script>renderChartDaily({{$contrato->id}}, '{{$contrato->relCliente->getNomYApe()}}')</script>
    <script>renderChartWeekly({{$contrato->id}})</script>
    <script>renderChartMounthly({{$contrato->id}}, {{$conteo->id}})</script>
