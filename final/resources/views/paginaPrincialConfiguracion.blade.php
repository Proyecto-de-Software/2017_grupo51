@extends('layout')

@section('javascript')
    <script src="{{ asset('js/moduloConfiguracion.js') }}"></script>
@endsection

@section('navlist')
    <div>
        <ul class="nav navbar-nav navbar-right navbar-header">
            <li><a href="{{ url('/home') }}" class="btn"><span class="glyphicon glyphicon-arrow-left"></span> Volver al inicio</a></li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="text-center">
                <h1><strong>Tabla de configuracion.</strong></h1>
            </div>
        </div>
    </div>
    <div class="container table-responsive">
        @include('flash::message')
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td><strong>Título de la pagina:</strong></td>
                    <td>{{ $config[0]['attributes']['titulo_pagina'] }}</td>
                </tr>
                <tr>
                    <td><strong>Mail de contacto:</strong></td>
                    <td>{{ $config[0]['attributes']['mail_contacto'] }}</td>
                </tr>
                <tr>
                    <td><strong>Cantidad de elementos por pagina de listado:</strong></td>
                    <td>{{ $config[0]['attributes']['elementos_pagina'] }}</td>
                </tr>
                <tr>
                    <td><strong>Estado del sitio:</strong></td>
                    <td>@if ($config[0]['attributes']['pagina_activa']) Activo @else Bloqueado @endif</td>
                </tr>
            </tbody>
        </table>  
        <div>
            <a class="btn btn-primary" href="{{ url('/configuracion/actualizar') }}">Modificar configuración</a>
        </div>
    </div>
@endsection

@section('atributosFooter')
    class="posicionFooter"
@endsection