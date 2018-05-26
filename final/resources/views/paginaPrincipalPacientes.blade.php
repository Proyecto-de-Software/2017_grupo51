@extends('layout')

@section('stylesheets')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/sitioInhabilitado.css') }}" >
@endsection

@section('javascript')
    <script src="{{ asset('js/moduloUsuario.js') }}"></script>
    <script src="{{ asset('js/validacionPermisos.js') }}"></script>
    <script src="{{ asset('js/bloqueoBotonAtras.js') }}"></script>
    <script src="{{ asset('js/moduloPacientes.js') }}"></script>
@endsection

@section('navlist')
    <div>
        <ul class="nav navbar-nav navbar-right navbar-header">
            <li class="dropdown"><a class="dropdown-toggle btn" data-toggle="dropdown" href="#">Menú <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="{{ url('/pacientes/create') }}" >Nuevo paciente</a></li>
                    <li><a href="{{ url('/pacientes/demographics') }}">Graficos de datos demograficos</a></li>
                </ul>
            </li>
            <li><a href="{{ url('/home') }}" class="btn"><span class="glyphicon glyphicon-arrow-left"></span> Volver al inicio</a></li>
            <li>
                <form class="navbar-form" action="{{ url('/pacientes/filter') }}" method="POST" onsubmit="return validarBusqueda();">
                    <div class="form-group">
                        <select class="form-control" name="busquedaPaciente" id="seleccion" onchange="visibilidadTipoDocumento(this.value);">
                            <option value="0">Opciones de busqueda...</option>
                            <option value="buscarNombrePaciente">Nombre de paciente</option>
                            <option value="buscarApellidoPaciente">Apellido del paciente</option>
                            <option value="buscarDocumentoPaciente">Documento</option>
                        </select>
                        <select class="form-control hide" name="selectDoc" id="selectDoc">
                            <option value="0">Selecciona un tipo de documento</option>
                            <option value="DNI">DNI</option>
                            <option value="LE">LE</option>
                            <option value="Pasaporte">Pasaporte</option>
                            <option value="Carnet extranjero">Carnet extranjero</option>
                            <option value="RUC">RUC</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="buscaPaciente" id="buscador" required>
                    </div>
                    <button type="submit" class="btn btn-default">Buscar paciente</button>
                </form>
        </ul>
    </div>
@endsection

@section('content')
    @if(count($pacientes)>0)
    <div class="container">
        <div class="row">
            <div class="text-center">
                <h1><strong>Pacientes</strong></h1>
            </div>
            @include('flash::message')
        </div>
    </div>
        <div class="container table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Nombre y Apellido</th>
                        <th scope="col">Fecha de nacimiento</th>
                        <th scope="col">Tipo de documento</th>
                        <th scope="col">Numero de documento</th>
                        <th scope="col" colspan="4" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pacientes as $paciente)
                        <tr>
                            <td>{{ $paciente['nombre'] }} {{ $paciente['apellido'] }}</td>
                            <td>{{ $paciente['fecha_nacimiento'] }}</td>
                            <td>{{ $paciente['tipo_documento'] }}</td>
                            <td>{{ $paciente['numero_documento'] }}</td>
                            <td><a href="{{ url('pacientes/'.$paciente['id']) }}" class="btn btn-primary">Ver datos completos</a></td>
                            <td><a href="{{ url('pacientes/'.$paciente['id'].'/destroy') }}"  class="btn btn-primary" onclick="return confirmacion('¿Esta seguro que desea eliminar el paciente?');">Eliminar paciente</a></td>
                            <td><a href="{{ url('controles/'.$paciente['id']) }}" class="btn btn-primary" >Ver historia clinica</a></td>
                            <td><a href="{{ url('controles/'.$paciente['id'].'/create') }}" class="btn btn-primary" >Nuevo control</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>    
            {{ $pacientes->links() }}
        </div>
    @else
        <div class="container">
        <div class="row">
            <div class="text-center">
                <h1><strong>No hay pacientes.</strong></h1>
            </div>
        </div>
    </div>
    @endif
@endsection

@section('atributosFooter')
    @if (count($pacientes) < 10)
        class="posicionFooter"
    @elseif ($config[0]['elementos_pagina'] < 10)
        class="posicionFooter"
    @endif
@endsection