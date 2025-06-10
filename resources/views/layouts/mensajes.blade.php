@if ( session('mensaje') )
    <ul class="list-group m-1 p-1">
        @foreach (session('mensaje') as $key => $items)
            @if ($key === 'success')
                    @foreach ($items as $item)
                        <li class="list-group-item list-group-item-success">{{ $item }}</li>
                    @endforeach
            @endif
            @if ($key === 'error')
                    @foreach ($items as $item)
                        <li class="list-group-item list-group-item-danger"> {{ $item }} </li>
                    @endforeach
            @endif
            @if ($key === 'warning')
                    @foreach ($items as $item)
                        <li class="list-group-item list-group-item-warning"> {{ $item }} </li>
                    @endforeach
            @endif
            @if ($key === 'info')
                    @foreach ($items as $item)
                        <li class="list-group-item list-group-item-info"> {{ $item }} </li>
                    @endforeach
            @endif
            @if (!is_array($items))
                <ul class="list-group m-1 p-1">
                    @if ($wordTest = str_split($items, 5)[0] === 'EXITO') 
                        <li class="list-group-item list-group-item-success">{{ $items }}</li>
                    @else
                        @if ($wordTest === 'ERROR')
                            <li class="list-group-item list-group-item-info"> {{ $items }} </li>
                        @else
                            <li class="list-group-item list-group-item-danger"> {{ $items }} </li>
                        @endif
                    @endif
                </ul>
            @endif
        @endforeach
    </ul>
@endif