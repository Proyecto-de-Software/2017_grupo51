@extends('layout')
@section('stylesheets')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/sitioInhabilitado.css') }}" >
@endsection
@section('navlist')
    <div>
        <ul class="nav navbar-nav navbar-right navbar-header">
            <li><a href="{{ url('login') }}" class="btn"><span class="glyphicon glyphicon-log-in"></span> Entrar</a></li>
        </ul>
    </div>
@endsection
@section('content')
    @include('flash::message')
    @include('info')
@endsection 
@section('atributosFooter')
    @if( !$config[0]['attributes']['pagina_activa'] )
        class="posicionFooter"
    @endif
@endsection