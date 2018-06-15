@extends('layout')

@section('stylesheets')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/sitioInhabilitado.css') }}" >
@endsection

@section('javascript')
    <script src="{{ asset('js/moduloUsuario.js') }}"></script>
    <script src="{{ asset('js/validacionPermisos.js') }}"></script>
    <script src="{{ asset('js/bloqueoBotonAtras.js') }}"></script>
@endsection

@section('navlist')
    <div>
        <ul class="nav navbar-nav navbar-right navbar-header">
            <li><a href="{{ url('pacientes/index') }}" class="btn"><span class="glyphicon glyphicon-arrow-left"></span> Volver al listado de pacientes</a></li>
        </ul>
    </div>
@endsection

@section('content')
    @if(count($controles)>0)
    <div class="container">
        <div class="row">
            <div class="text-center">
                <h1><strong>Controles</strong></h1>
            </div>
            @include('flash::message')
        </div>
    </div>
        <div class="container table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Fecha del control</th>
                        <th scope="col" colspan="2" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($controles as $control)
                        <tr>
                            <td>{{ $control['fecha'] }}</td>
                            <td><a href="{{ url('controles/'.$control['id']).'/show' }}" class="btn btn-primary">Ver control completo</a></td>
                            <td><a href="{{ url('controles/'.$control['id']).'/destroy/'.$paciente }}" onclick="return confirmacion('¿Esta seguro que desea eliminar el control?');" class="btn btn-primary">Eliminar control</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>    
            {{ $controles->links() }}
        </div>
    @else
        <div class="container">
        <div class="row">
            <div class="text-center">
                <h1><strong>No hay controles.</strong></h1>
            </div>
        </div>
    </div>
    @endif
@endsection

@section('atributosFooter')
    @if (count($controles) < 10)
        class="posicionFooter"
    @elseif ($config[0]['elementos_pagina'] < 10)
        class="posicionFooter"
    @endif
@endsection