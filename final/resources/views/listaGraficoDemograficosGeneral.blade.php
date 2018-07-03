@extends('layout')

@section('navlist')
    <div>
        <ul class="nav navbar-nav navbar-right navbar-header">
            <li><a href="{{ url('pacientes/index') }}" class="btn"><span class="glyphicon glyphicon-arrow-left"></span> Volver a pacientes</a></li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="text-center">
                <h1><strong>Listado de datos demograficos.</strong></h1>
            </div>
        </div>
        <div class="container table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th colspan="2">Dato demografico</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Mascotas</td>
                        <td><a href="{{ url('/pacientes/demographics/allpatients/mascotas') }}" class="btn btn-primary">Ver grafico</a></td>
                    </tr>
                    <tr>
                        <td>Electricidad</td>
                        <td><a href="{{ url('/pacientes/demographics/allpatients/electricidad') }}" class="btn btn-primary">Ver grafico</a></td>
                    </tr>
                    <tr>
                        <td>Heladera</td>
                        <td><a href="{{ url('/pacientes/demographics/allpatients/heladera') }}" class="btn btn-primary">Ver grafico</a></td>
                    </tr>
                    <tr>
                        <td>Tipo de agua</td>
                        <td><a href="{{ url('/pacientes/demographics/allpatients/agua') }}" class="btn btn-primary">Ver grafico</a></td>
                    </tr>
                    <tr>
                        <td>Tipo de vivienda</td>
                        <td><a href="{{ url('/pacientes/demographics/allpatients/vivienda') }}" class="btn btn-primary">Ver grafico</a></td>
                    </tr>
                    <tr>
                        <td>Tipo de calefacción</td>
                        <td><a href="{{ url('/pacientes/demographics/allpatients/calefaccion') }}" class="btn btn-primary">Ver grafico</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('atributosFooter')
    class="posicionFooter"
@endsection