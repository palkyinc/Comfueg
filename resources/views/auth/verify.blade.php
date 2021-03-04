@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verifique Su dirección de  Email') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Un nuevo link de verificación ha sido enviado a su dirección de email.') }}
                        </div>
                    @endif

                    {{ __('Antes de proceder, por favor verifique su email con un link.') }}
                    {{ __('Si no recibió el email') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click hereaquí para otro pedido') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
