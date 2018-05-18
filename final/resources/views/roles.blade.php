@extends('layout')

@section('navlist')
    <div>
        <ul class="nav navbar-nav navbar-right navbar-header">
            <li><a href="{{ url('usuarios/'.$usuario['id']) }}" class="btn"><span class="glyphicon glyphicon-arrow-left"></span> Volver al detalle del usuario</a></li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="text-center">
                <h1><strong>Asignar/Desaginar roles</strong></h1>
            </div>
        </div>
    </div>
        <div class="container table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        @foreach($roles as $rol)
                            <th scope="col">{{ $rol['nombre'] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach ($roles as $rol)
                            @if($rol['poseeRol']) 
                                <td><a href="{{ url("usuarios/".$usuario['id']."/unassignRol/".$rol['id']) }}" class="btn btn-danger">Desasignar rol: {{ $rol['nombre'] }}</a></td> 
                            @else
                                <td><a href="{{ url("usuarios/".$usuario['id']."/assignRol/".$rol['id']) }}" class="btn btn-success">Asignar rol: {{ $rol['nombre'] }}</a></td>
                            @endif
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
@endsection

@section('atributosFooter')
    class="posicionFooter"
@endsection