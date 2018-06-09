@extends('layout')

@section('navlist')
    <div>
        <ul class="nav navbar-nav navbar-right navbar-header">
            <li><a href="{{ url('controles/'.$paciente['id']) }}" class="btn"><span class="glyphicon glyphicon-arrow-left"></span> Volver a la historia clinica</a></li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="text-center">
                <h1><strong>Detalle del control.</strong></h1>
    </div>
    <div class="container table-responsive">
                    @include('flash::message')
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td><strong>Nombre y apellido del paciente:</strong></td>
                                <td>{{ $paciente['nombre'] }} {{ $paciente['apellido'] }}</td>
                            </tr>
                            <tr>
                                <td><strong>Edad:</strong></td>
                                <td>{{ $edad }}</td>
                            </tr>
                            <tr>
                                <td><strong>Fecha del control:</strong></td>
                                <td>{{ $control['fecha'] }}</td>
                            </tr>
                            <tr>
                                <td><strong>Peso:</strong></td>
                                <td>{{ $control['peso'] }} kg</td>
                            </tr>
                            <tr>
                                <td><strong>Vacunas completas:</strong></td>
                                <td>@if ($control['vacunas_completas']) Si @else No @endif</td>
                            </tr>
                            <tr>
                                <td><strong>Vacunas - Observaciones:</strong></td>
                                <td>{{ $control['vacunas_observaciones'] }}</td>
                            </tr>
                            <tr>
                                <td><strong>Maduración acorde:</strong></td>
                                <td>@if ($control['maduracion_acorde']) Si @else No @endif</td>
                            </tr>
                            <tr>
                                <td><strong>Maduración - Observaciones:</strong></td>
                                <td>{{ $control['maduracion_observaciones'] }}</td>
                            </tr>
                            <tr>
                                <td><strong>Examen fisico normal:</strong></td>
                                <td>@if ($control['examen_fisico_normal']) Si @else No @endif</td>
                            </tr>
                            <tr>
                                <td><strong>Examen fisico - Observaciones:</strong></td>
                                <td>{{ $control['examen_fisico_observaciones'] }}</td>
                            </tr>
                            <tr>
                                <td><strong>Percentilo cefalico:</strong></td>
                                <td>@if ($control['percentilo_cefalico'] == null) Sin registro @else {{ $control['percentilo_cefalico'] }} cm @endif </td>
                            </tr>
                            <tr>
                                <td><strong>Percentilo perimetro cefalico:</strong></td>
                                <td>@if ($control['percentilo_perimetro_cefalico'] == null) Sin registro @else {{ $control['percentilo_perimetro_cefalico'] }} cm @endif </td>
                            </tr>
                            <tr>
                                <td><strong>Talla:</strong></td>
                                <td>@if ($control['talla'] == null) Sin registro @else {{ $control['talla'] }} cm @endif </td>
                            </tr>
                            <tr>
                                <td><strong>Alimentación:</strong></td>
                                <td>@if ($control['alimentacion'] == null) Sin registro @else {{ $control['alimentacion'] }} @endif </td>
                            </tr>
                            <tr>
                                <td><strong>Observaciones generales:</strong></td>
                                <td>@if ($control['observaciones_generales'] == null) Sin registro @else {{ $control['observaciones_generales']}} @endif </td>
                            </tr>
                            <tr>
                                <td><strong>Pediatra que realizo el control:</strong></td>
                                <td>{{ $usuario['first_name'] }} {{ $usuario['last_name'] }}</td>
                            </tr>
                        </tbody>
                    </table>    
                    <a href="{{ url('controles/'.$control['id'].'/edit') }}" class="btn btn-primary"> Modificar control</a>
                </div>
@endsection