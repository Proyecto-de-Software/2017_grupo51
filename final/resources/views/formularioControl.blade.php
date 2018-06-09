@extends('layout')

@section('javascript')
    <script src="{{ asset('js/validarFormControlPac.js') }}"></script>
@endsection

@section('navlist')
    <div>
        <ul class="nav navbar-nav navbar-right navbar-header">
            @if($accion == 'Editar')
                <li><a href="{{ url('controles/'.$control['id'].'/show') }}" class="btn"><span class="glyphicon glyphicon-arrow-left"></span> Volver al detalle del control</a></li>
            @else
                <li><a href="{{ url('controles/'.$paciente) }}" class="btn"><span class="glyphicon glyphicon-arrow-left"></span> Volver a historia clínica</a></li>
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
                <form method= "POST" action="@if($accion == 'Crear'){{ url('/controles') }}@else{{ url('/controles/'.$control['id']) }}@endif">
                        @if($accion == 'Editar')
                            {{ method_field('PUT') }}
                            <input type="hidden" name="id" value="{{ $control['id'] }}">
                        @else
                            <input type="hidden" name="id_paciente" value="{{ $paciente }}">
                        @endif
                        {{ csrf_field() }}
			<h1 class="text-center"><label>{{ $accion }} control</label></h1><br>
                        @if ($accion == 'Crear')
                            <div class="form-group">
				<label>Fecha:</label>
                                <p>Guarda automaticamente la fecha actual</p>
                            </div>
                        @endif
			<div class="form-group">
				<label>Peso(*):</label>
				<input type="number" step="any" class="form-control" name="peso" id="PesoPac"  required placeholder="Ingrese el peso" value="@if (! empty($control)){{ $control['peso'] }}@elseif (! empty(old('peso'))){{ old('peso') }}@endif">
			</div>
			<div class="form-group">
				<label>Tiene todas las vacunas (*):</label><br>
                                <label><input type="radio"  name="vacunas_completas" value="1" @if(! empty($control) and $control['vacunas_completas'] ) checked @endif required>Si</label>
                                <label><input type="radio"  name="vacunas_completas" value="0" @if(! empty($control) and !$control['vacunas_completas'] ) checked @endif required>No</label><br>
			</div>
			<div class="form-group">
				<label>Observaciones de Vacuna (*):</label>
				<input type="text" class="form-control" name="vacunas_observaciones" id="VacObs" placeholder="Observaciones" value="@if (! empty($control)){{ $control['vacunas_observaciones'] }}@elseif (! empty(old('vacunas_observaciones'))) {{ old('vacunas_observaciones') }}@endif" required>
			</div>
			<div class="form-group">
				<label>Maduracion acorde (*):</label><br>
				<label><input type="radio" name="maduracion_acorde" value="1" @if(! empty($control) and $control['maduracion_acorde'] ) checked @endif required>Si</label>
                                <label><input type="radio" name="maduracion_acorde" value="0" @if(! empty($control) and !$control['maduracion_acorde'] ) checked @endif required>No</label><br>
			</div>
			<div class="form-group">
				<label>Observaciones de Maduración (*):</label>
				<input type="text" class="form-control" name="maduracion_observaciones" id="MaduracionObs" placeholder="Observaciones" value="@if (! empty($control)){{ $control['maduracion_observaciones'] }}@elseif (! empty(old('maduracion_observaciones'))) {{ old('maduracion_observaciones') }}@endif" required>
			</div>
			<div class="form-group">
				<label>Examen físico normal (*):</label><br>
				<label><input type="radio" name="examen_fisico_normal" value="1" @if(! empty($control) and $control['examen_fisico_normal'] ) checked @endif required>Si</label>
                                <label><input type="radio" name="examen_fisico_normal" value="0" @if(! empty($control) and !$control['examen_fisico_normal'] ) checked @endif required>No</label><br>
			</div>
			<div class="form-group">
				<label>Observaciones del examen fisico (*):</label>
				<input type="text" class="form-control" name="examen_fisico_observaciones" id="ExamenFisicoObs" placeholder="Observaciones" value="@if (! empty($control)){{ $control['examen_fisico_observaciones'] }}@elseif (! empty(old('examen_fisico_observaciones'))) {{ old('examen_fisico_observaciones') }}@endif" required>
			</div>
			<div class="form-group">
				<label>PC: Percentilo cefálico</label>
				<input type="number" step="any" class="form-control" name="percentilo_cefalico" id="PC" placeholder="Ingrese valor" value="@if (! empty($control)){{ $control['percentilo_cefalico'] }}@elseif (! empty(old('percentilo_cefalico'))) {{ old('percentilo_cefalico') }}@endif">
			</div>
			<div class="form-group">
				<label>PPC: Percentilo perímetro cefálico</label>
				<input type="number" step="any" class="form-control" name="percentilo_perimetro_cefalico" id="PPC" placeholder="Ingrese valor" value="@if (! empty($control)){{ $control['percentilo_perimetro_cefalico'] }}@elseif (! empty(old('percentilo_perimetro_cefalico'))) {{ old('percentilo_perimetro_cefalico') }}@endif">
			</div>
			<div class="form-group">
				<label>Talla:</label>
				<input type="number" step="any" class="form-control" name="talla" id="TallaPac" placeholder="Ingrese la Talla" value="@if (! empty($control)){{ $control['talla'] }}@elseif (! empty(old('talla'))) {{ old('talla') }}@endif">
			</div>
			<div class="form-group">
				<label>Alimentacion:</label>
				<input type="text" class="form-control" name="alimentacion" id="AlimPac" placeholder="Descripcion de Alimnetacion" value="@if (! empty($control)){{ $control['alimentacion'] }}@elseif (! empty(old('alimentacion'))) {{ old('alimentacion') }}@endif">
			</div>
			<div class="form-group">
				<label>Observaciones Generales:</label>
				<input type="text" class="form-control" name="observaciones_generales" id="AlimObs" placeholder="Observaciones" value="@if (! empty($control)){{ $control['observaciones_generales'] }}@elseif (! empty(old('observaciones_generales'))) {{ old('observaciones_generales') }}@endif">
			</div>
			<button type="submit" class="btn btn-primary" onclick="return validarFormControlPac();">@if (! empty($control))Actualizar información @else Registrar nuevo control @endif</button>
		
	</form>
                </div>
            </div>
        </div>
@endsection