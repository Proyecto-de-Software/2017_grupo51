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
           <li><a href="{{ url('/usuarios/index') }}" class="btn"><span class="glyphicon glyphicon-arrow-left"></span> Volver a usuarios</a></li> 
        </ul>
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="text-center">
                <h1><strong>Detalle del usuario.</strong></h1>
            </div>
        </div>
    </div>
    <div class="container table-responsive">
            @include('flash::message')
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td><strong>Nombre de usuario:</strong></td>
                        <td>{{ $usuario['username'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nombre y apellido:</strong></td>
                        <td>{{ $usuario['first_name'] }} {{ $usuario['last_name'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td>{{ $usuario['email'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Estado:</strong></td>
                        <td>@if ($usuario['active'] == 1) Activo @else Bloqueado @endif</td>
                    </tr>
                    <tr>
                        <td><strong>Creado:</strong></td>
                        <td>{{ $usuario['created_at'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Actualizado:</strong></td>
                        <td>{{ $usuario['updated_at'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Rol(es):</strong></td>
                        <td>@foreach ($roles as $rol) {{ $rol['nombre'] }}/ @endforeach</td>
                    </tr>
                </tbody>
            </table> 
            <a href="{{ url('/usuarios/'.$usuario['id']).'/edit' }}" class="btn btn-primary"> Modificar usuario </a>
            <a href="{{ url('/usuarios/'.$usuario['id']).'/assignOrUnassignRol' }}" class="btn btn-primary"> Asignar/Desasignar roles </a>
        </div>
@endsection

@section('atributosFooter')
    class="posicionFooter"
@endsection