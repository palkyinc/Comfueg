<div class="modal fade" id="staticBackdropPhoto{{$incidente->id ?? ''}}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Fotos</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <!-- Carrousel -->
            <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-ride="carousel">
            <ol class="carousel-indicators">
                    @php
                            $number = 0;
                    @endphp
                        @foreach ($archivos as $archivo)
                            @if (null != $archivo && $archivo->tipo == 'PHOTO')
                                @if ($number == 0)
                                    <li data-target="#carouselExampleIndicators" data-slide-to="{{$number}}" class="active"></li>
                                @else
                                    <li data-target="#carouselExampleIndicators" data-slide-to="{{$number}}"></li>
                                @endif
                                @php
                                    $number++;
                                @endphp
                            @endif
                        @endforeach
            </ol>
            <div class="carousel-inner">
                @php
                    $active = true;
                @endphp
                    @foreach ($archivos as $archivo)
                    @if (null != $archivo && $archivo->tipo == 'PHOTO')
                        @if ($active === true)
                            <div class="carousel-item active">
                            <img src="/imgUsuarios/photos/{{$archivo->file_name}}" class="d-block w-100" alt="{{$archivo->file_name}}">
                                <div class="carousel-caption d-none d-md-block">
                                    <p>{{$archivo->file_name}}</p>
                                </div>
                            </div>
                            @php
                                $active = false;
                            @endphp
                        @else
                            <div class="carousel-item ">
                            <img src="/imgUsuarios/photos/{{$archivo->file_name}}" class="d-block w-100" alt="{{$archivo->file_name}}">
                                <div class="carousel-caption d-none d-md-block">
                                    <p>{{$archivo->file_name}}</p>
                                </div>
                            </div>
                        @endif
                    @endif
                @endforeach
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
            </div>


        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
</div>