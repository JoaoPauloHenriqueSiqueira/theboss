{{-- layout --}}
@extends('layouts.fullLayoutMaster')

{{-- page title --}}
@section('title','Login')

{{-- page style --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/login.css')}}">
@endsection

{{-- page content --}}
@section('content')
<div id="login-page" class="row">
    <div class="col s12 m6 l4 z-depth-4 card-panel border-radius-6 login-card bg-opacity-8">
        <form class="login-form" method="POST" action="{{ route('active_post') }}">
            @csrf
            <div class="row">
                <div class="input-field col s12">
                    <h5 class="ml-4 center">{{ __('Um código de ativação foi enviado para o email cadastrado') }}</h5>
                </div>
            </div>
            <div class="row margin">
                <div class="input-field col s12">
                    <input id="token" required type="text" class=" @error('token') is-invalid @enderror" name="token" required autocomplete="email" autofocus>
                    <label for="email" class="center-align">{{ __('Token') }}</label>
                    @error('token')
                    <small class="red-text ml-10" role="alert">
                        {{ $message }}
                    </small>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <button type="submit" class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12">Ativar
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <p class="margin right-align medium-small">
                        <a href="{{ route('password.request') }}">Não recebeu nenhum token?</a>
                    </p>
                </div>
            </div>
        </form>
    </div>
</div>


@endsection
<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
<script>
    $(document).ready(function() {
        maskFields();
    });
    function maskFields() {
        $('#token').mask('000000');
    }
</script>