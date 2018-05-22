@extends('layout')

@section('stylesheets')
    <link rel="stylesheet" type="text/css" href="{{ asset("css/sitioInhabilitado.css") }}" >
@endsection

@section('javascript')
    <script src="{{ asset("js/moduloUsuario.js") }}"></script>
    <script src="{{ asset("js/validacionPermisos.js") }}"></script>
    <script src="{{ asset("js/bloqueoBotonAtras.js") }}"></script>
@endsection

@section('navlist')
    <div>
        <ul class="nav navbar-nav navbar-right navbar-header">
            <li class="dropdown"><a class="dropdown-toggle btn" data-toggle="dropdown" href="#">Menú <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="{{ url('usuarios/index') }}">Usuarios</a></li>
                    <li><a href="{{ url('pacientes') }}">Pacientes</a></li>
                    <li><a href="#" class="divider"></a></li>
                    <li><a href="{{ url('configuracion') }}">Configuración del sistema</a></li>
                </ul>
            </li>
            <li><a href="{{ url('logout') }}" class="btn" onclick="return confirmacion('¿Cerrar sesión?');"><span class="glyphicon glyphicon-log-in"></span> Cerrar sesión</a></li>
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