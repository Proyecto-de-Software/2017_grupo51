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
            <li class="dropdown"><a class="dropdown-toggle btn" data-toggle="dropdown" href="#">Menú <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="{{ url('/usuarios/create') }}" >Crear usuario</a></li>
                    <li><a href="{{ url('/usuarios/'.Auth::user()->id) }}" >Ver mi usuario</a></li>
                </ul>
            </li>
            <li><a href="{{ url('/home') }}" class="btn"><span class="glyphicon glyphicon-arrow-left"></span> Volver al inicio</a></li>
            <li>
                <form class="navbar-form" action="./?action=buscarUsuarios" method="POST" onsubmit="return obtenerDatos();">
                    <div class="form-group">
                        <select class="form-control" name="busquedaUsuario" id="seleccion" onchange="visibilidadInput(this.value);">
                            <option value="0">Opciones de busqueda...</option>
                            <option value="permisoBuscarNombreUsuario">Nombre de usuario</option>
                            <option value="permisoBuscarActivos">Activos</option>
                            <option value="permisoBuscarBloqueados">Bloqueados</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control hide" name="nombreUsuario" id="buscador" placeholder="Ingresa nombre de usuario">
                    </div>
                    <button type="submit" class="btn btn-default">Buscar usuarios</button>
                </form>
            </li>
        </ul>
    </div>
@endsection

@section('content')
    @if(count($usuarios)>0)
    <div class="container">
        <div class="row">
            <div class="text-center">
                <h1><strong>Usuarios</strong></h1>
            </div>
        </div>
    </div>
        <div class="container table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Nombre de usuario</th>
                        <th scope="col">Nombre y Apellido</th>
                        <th scope="col" colspan="3" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario['username'] }}</td>
                            <td>{{ $usuario['first_name'] }} {{ $usuario['last_name'] }}</td>
                            <td><a href="{{ url('usuarios/'.$usuario['id']) }}" class="btn btn-primary">Ver usuario completo</a></td>
                            <td><a href="" onclick="return confirmacion('¿Esta seguro que desea eliminar el usuario?');" class="btn btn-danger">Borrar</a></td>
                            <td><a href="" onclick="return confirmacion('¿Esta seguro que desea @if ($usuario['active'] == 1) bloquear @else desbloquear @endif el usuario?');" class="btn btn-primary">@if ($usuario['active'] == 1) Bloquear @else Desbloquear @endif</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>    
            {{ $usuarios->links() }}
        </div>
    @else
        <div class="container">
        <div class="row">
            <div class="text-center">
                <h1><strong>No hay usuarios registrados</strong></h1>
            </div>
        </div>
    </div>
    @endif
@endsection

@section('atributosFooter')
    @if (count($usuarios) < 10)
        class="posicionFooter"
    @elseif ($config[0]['elementos_pagina'] < 10)
        class="posicionFooter"
    @endif
@endsection