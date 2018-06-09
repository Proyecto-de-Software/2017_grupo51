@extends('layout')

@section('javascript')
    <script src="{{ asset('js/moduloUsuario.js') }}"></script>
    <script src="{{ asset('js/moduloPacientes.js') }}"></script>
@endsection

@section('navlist')
    <div>
        <ul class="nav navbar-nav navbar-right navbar-header">
           <li><a href="{{ url('/pacientes/index') }}" class="btn"><span class="glyphicon glyphicon-arrow-left"></span> Volver a pacientes</a></li> 
        </ul>
    </div>
@endsection

@section('content')
                <div class="container">
                    <div class="row">
                        <div class="text-center">
                            <h1><strong>Detalle del paciente.</strong></h1>
                        </div>
                    </div>
                </div>
                <div class="container table-responsive">
                    @include('flash::message')
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td><strong>Nombre y apellido:</strong></td>
                                <td>{{ $paciente['nombre'] }} {{ $paciente['apellido'] }}</td>
                            </tr>
                            <tr>
                                <td><strong>Fecha de nacimiento:</strong></td>
                                <td>{{ $paciente['fecha_nacimiento'] }}</td>
                            </tr>
                            <tr>
                                <td><strong>Género:</strong></td>
                                <td>{{ $paciente['genero'] }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tipo de documento:</strong></td>
                                <td>{{ $paciente['tipo_documento'] }}</td>
                            </tr>
                            <tr>
                                <td><strong>Número de documento:</strong></td>
                                <td>{{ $paciente['numero_documento'] }}</td>
                            </tr>
                            <tr>
                                <td><strong>Domicilio:</strong></td>
                                <td>{{ $paciente['domicilio'] }}</td>
                            </tr>
                            <tr>
                                <td><strong>Teléfono:</strong></td>
                                <td>@if ($paciente['tel_cel'] == null )No posee @else {{ $paciente['tel_cel'] }} @endif</td>
                            </tr>
                            <tr>
                                <td><strong>Obra social:</strong></td>
                                <td>@if ($paciente['obra_social'] == null)No posee @else {{ $paciente['obra_social'] }} @endif</td>
                            </tr>
                            <tr>
                                <td><strong>Heladera:</strong></td>
                                <td>@if ($paciente['heladera']) Si @else No @endif</td>
                            </tr>
                            <tr>
                                <td><strong>Electricidad:</strong></td>
                                <td>@if ($paciente['electricidad']) Si @else No @endif</td>
                            </tr>
                            <tr>
                                <td><strong>Tipo de vivienda:</strong></td>
                                <td>{{ $paciente['tipo_vivienda'] }}</td>
                            </tr>
                            <tr>
                                <td><strong>Mascota:</strong></td>
                                <td>@if ($paciente['mascota']) Si @else No @endif</td>
                            </tr>
                            <tr>
                                <td><strong>Tipo de calefacción:</strong></td>
                                <td>{{ $paciente['tipo_calefaccion'] }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tipo de agua:</strong></td>
                                <td>{{ $paciente['tipo_agua'] }}</td>
                            </tr>
                            <tr>
                                <td><strong>Grafico percentilo cefalico</strong></td>
                                <td><a href="{{ url('pacientes/graficoCurva/percentilo_perimetro_cefalico').'/'.$paciente['id'] }}" class="btn btn-primary" >Ver grafico</a></td>
                            </tr>
                            <tr>
                                <td><strong>Grafico talla</strong></td>
                                <td><a href="{{ url('pacientes/graficoCurva/talla').'/'.$paciente['id'] }}" class="btn btn-primary" >Ver grafico</a></td>
                            </tr>
                            <tr>
                                <td><strong>Grafico peso</strong></td>
                                <td><a href="{{ url('pacientes/graficoCurva/peso').'/'.$paciente['id'] }}" class="btn btn-primary" onclick="permisoConfiguracion('permisoHistoriaClinica')">Ver grafico</a></td>
                            </tr>
                        </tbody>
                    </table>    
                    <a href="{{ url('pacientes/'.$paciente['id'].'/edit') }}" class="btn btn-primary"> Modificar paciente</a>
                </div>
@endsection