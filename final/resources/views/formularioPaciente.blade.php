@extends('layout')

@section('javascript')    
    <script src="{{ asset('js/validarFormPacientes.js') }}"></script>
@endsection

@section('navlist')
    <div>
        <ul class="nav navbar-nav navbar-right navbar-header">
            @if($accion == 'Editar')
                <li><a href="{{ url('pacientes/'.$paciente['id']) }}" class="btn"><span class="glyphicon glyphicon-arrow-left"></span> Volver al detalle del paciente</a></li>
            @else
                <li><a href="{{ url('pacientes/index') }}" class="btn"><span class="glyphicon glyphicon-arrow-left"></span> Volver a pacientes</a></li>
            @endif
        </ul>
    </div>
@endsection

@section('content')
    <div class="container">
            
            	<div class="row">
            	   	<div class="col-md-6 col-md-offset-3 well">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
            	   		<form method= "POST" name="form_pacientes" action="@if($accion == 'Crear'){{ url('/pacientes') }}@else{{ url('/pacientes/'.$paciente['id']) }}@endif" >
            	   			@if($accion == 'Editar')
                                            {{ method_field('PUT') }}
                                            <input type="hidden" name="id" value="{{ $paciente['id'] }}">
                                        @endif
                                        {{ csrf_field() }}
                                        
            		   			<h1 class="text-center"><label>{{ $accion }} paciente</label></h1><br>
            		   			<div class="form-group">
     	 							<label>Apellido(*):</label>
     								<input type="text" class="form-control" name="apellido" id="ApellidoP" placeholder="Apellido del paciente" value="@if (! empty($paciente)){{ $paciente['apellido'] }}@elseif (! empty(old('apellido'))) {{ old('apellido') }}@endif" required>
    							</div>
    							<div class="form-group">
      								<label>Nombre(*):</label>
     								<input type="text" class="form-control" name="nombre" id="NombreP" placeholder="Nombre del paciente" value="@if (! empty($paciente)){{ $paciente['nombre'] }}@elseif (! empty(old('nombre'))) {{ old('nombre') }}@endif" required>
    							</div>
    							<div class="form-group">
      								<label>Fecha de nacimiento(*):</label>
     								<input type="date" class="form-control" name="fecha_nacimiento" id="FecNacP" value="@if (! empty($paciente)){{ $paciente['fecha_nacimiento'] }}@elseif (! empty(old('fecha_nacimiento'))) {{ old('fecha_nacimiento') }}@endif" required>
    							</div>
    							<div class="form-group">
	      							<label>Género(*):</label>
     									<select class="form-control" name="genero" id="GeneroP">
        									<option @if(! empty($paciente) and $paciente['genero'] == 'Masculino') selected @endif >Masculino</option>
        									<option @if(! empty($paciente) and $paciente['genero'] == 'Femenino') selected @endif>Femenino</option>
        								</select>
    							</div>
    							<div class="form-group">
    	  							<label>Tipo de documento(*):</label>
	     							<select class="form-control" name="tipo_documento" id="TipoDocP">
                                                                    @foreach ($documentos as $documento)
                                                                    <option value='{{ $documento->nombre }}' @if(! empty($paciente) and $paciente['tipo_documento'] == $documento->nombre ) selected @endif >{{ $documento->nombre }}</option>
                                                                    @endforeach
     								</select>
    							</div>
    							<div class="form-group">
      								<label>Numero de Documento(*):</label>
                                                                <input type="number" class="form-control" name="numero_documento" id="NroDocP" placeholder="Nro Documento del paciente" value="@if (! empty($paciente)){{ $paciente['numero_documento'] }}@elseif (! empty(old('numero_documento'))) {{ old('numero_documento') }}@endif" required>
	    						</div>
    							<div class="form-group">
      								<label>Domicilio(*):</label>
     								<input type="text" class="form-control" name="domicilio" id="DomicP" placeholder="Domicilio del paciente" value="@if (! empty($paciente)){{ $paciente['domicilio'] }}@elseif (! empty(old('domicilio'))) {{ old('domicilio') }}@endif" required>
    							</div>
    							<div class="form-group">
      								<label>Tel/Celular:</label>
    	 							<input type="number" class="form-control" name="tel_cel" id="TelefonoP" placeholder="Telefono del paciente" value="@if (! empty($paciente) and $paciente['tel_cel'] != null ){{ $paciente['tel_cel'] }}@elseif (! empty(old('tel_cel'))) {{ old('tel_cel') }}@endif">
	    						</div>
    							<div class="form-group">
      								<label>Obra social:</label>
     								<select class="form-control" name="obra_social" id="ObraSocP">
                                                                    <option @if(! empty($paciente) and $paciente['obra_social'] == null ) selected @endif>No posee</option>
                                                                    @foreach ($obraSocial as $os)
     									<option @if(! empty($paciente) and $paciente['obra_social'] == $os->nombre ) selected @endif>{{ $os->nombre }}</option>
                                                                    @endforeach
     								</select>
    							</div>
    						
                                                
    							<h1 class="text-center"><label>Datos demograficos del paciente</label></h1><br>
    							<div class="radio">
    								<label>¿Tiene Heladera?(*)</label><br>
      								<label><input type="radio" name="heladera" value="1" @if(! empty($paciente) and $paciente['heladera'] ) checked @endif required>Si</label>
      								<label><input type="radio" name="heladera" value="0" @if(! empty($paciente) and !$paciente['heladera'] ) checked @endif required>No</label>
    							</div>
    							<div class="radio">
    								<label>¿Tiene electricidad? (*)</label><br>
      								<label><input type="radio" name="electricidad" value="1" @if(! empty($paciente) and $paciente['electricidad'] ) checked @endif required>Si</label>
      								<label><input type="radio" name="electricidad" value="0" @if(! empty($paciente) and !$paciente['electricidad'] ) checked @endif required>No</label><br>
    							</div>
    							<div class="radio">
                                                                <label>¿Tiene mascota(s)? (*)</label><br>
      								<label><input type="radio" name="mascota" value="1" @if(! empty($paciente) and $paciente['mascota'] ) checked @endif required>Si</label>
      								<label><input type="radio" name="mascota" value="0" @if(! empty($paciente) and !$paciente['mascota'] ) checked @endif required>No</label>
    							</div>
    							<div class="form-group">
	      							<label>Tipo de vivienda(*):</label>
     									<select class="form-control" name="tipo_vivienda" id="viviendaP">
                                                                            @foreach ($viviendas as $vivienda)
        									<option @if(! empty($paciente) and $paciente['tipo_vivienda'] == $vivienda->nombre ) selected @endif>{{ $vivienda->nombre }}</option>
                                                                            @endforeach
        								</select>
    							</div>
    							<div class="form-group">
	      							<label>Tipo de calefacción(*):</label>
     									<select class="form-control" name="tipo_calefaccion" id="calefaP">
                                                                            @foreach ($calefaccion as $cal)
        									<option @if(! empty($paciente) and $paciente['tipo_calefaccion'] == $cal->nombre ) selected @endif>{{ $cal->nombre }}</option>
                                                                            @endforeach
        								</select>
    							</div>
    							<div class="form-group">
	      							<label>Tipo de agua(*):</label>
     									<select class="form-control" name="tipo_agua" id="tipoAguaP">
                                                                            @foreach ($agua as $tagua)
        									<option @if(! empty($paciente) and $paciente['tipo_agua'] == $tagua->nombre ) selected @endif>{{ $tagua->nombre }}</option>
                                                                            @endforeach
        								</select>
    							</div>
    						
    						<button type="submit" class="btn btn-primary" onclick="return validarFormPac();">@if (! empty($paciente))Actualizar información @else Registrar nuevo paciente @endif</button>
            	   		</form>
                        </div>
		</div>
    </div>
@endsection