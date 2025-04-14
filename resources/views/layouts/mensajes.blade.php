@if ( session('mensaje') )
            <ul class="list-group m-1 p-1">
                @foreach (session('mensaje') as $key => $items)
                    @if ($key === 'success')
                            @foreach ($items as $item)
                                <li class="list-group-item list-group-item-success">{{ $item }}</li>
                            @endforeach
                            @php
                                $mensaje2 = true;
                            @endphp
                    @endif
                    @if ($key === 'error')
                            @foreach ($items as $item)
                                <li class="list-group-item list-group-item-danger"> {{ $item }} </li>
                            @endforeach
                            @php
                                $mensaje2 = true;
                            @endphp
                    @endif
                    @if ($key === 'warning')
                            @foreach ($items as $item)
                                <li class="list-group-item list-group-item-warning"> {{ $item }} </li>
                            @endforeach
                            @php
                                $mensaje2 = true;
                            @endphp
                    @endif
                    @if ($key === 'info')
                            @foreach ($items as $item)
                                <li class="list-group-item list-group-item-info"> {{ $item }} </li>
                            @endforeach
                            @php
                                $mensaje2 = true;
                            @endphp
                    @endif
                @endforeach
            </ul>
        @endif
        @if ( session('mensaje') && !isset($mensaje2))
                @foreach (session('mensaje') as $item)
                    {{ $item }} <br>
                @endforeach
            </div>
        @endif