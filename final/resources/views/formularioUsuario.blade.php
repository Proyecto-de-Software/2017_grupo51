@extends('layout')

@section('javascript')
    <script src="{{ asset('js/validarCrearUsr.js') }}"></script>
@endsection

@section('navlist')
    <div>
        <ul class="nav navbar-nav navbar-right navbar-header">
            @if($accion == 'Editar')
                <li><a href="{{ url('usuarios/'.$usuario['id']) }}" class="btn"><span class="glyphicon glyphicon-arrow-left"></span> Volver al detalle del usuario</a></li>
            @else
                <li><a href="{{ url('usuarios/index') }}" class="btn"><span class="glyphicon glyphicon-arrow-left"></span> Volver a usuarios</a></li>
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
                <form method="POST" action="@if($accion == 'Crear'){{ url('/usuarios/') }}@else{{ url('/usuarios/'.$usuario['id']) }}@endif">
                    @if($accion == 'Editar')
                        {{ method_field('PUT') }}
                         <input type="hidden" name="id" value="{{ $usuario['id'] }}">
                    @endif
                    {{ csrf_field() }}
                    <h1 class="text-center"><label>{{ $accion }} usuario</label></h1><br>
            	    	<div class="form-group">
                            <label>Email (*):</label>
                            <input type="email" class="form-control" name="email" id="emailUs" placeholder="mail@hotmail.com" value="@if (! empty($usuario)){{ $usuario['email'] }} @elseif (! empty(old('email'))) {{ old('email') }}@endif" required>
    			</div>
    			<div class="form-group">
     	 		    <label>Nombre de usuario(*):</label>
                            <input type="text" class="form-control" maxlength="255" name="username" id="nombreUs" placeholder="Ingrese nombre de usuario" value="@if (! empty($usuario)){{ $usuario['username'] }}@elseif (! empty(old('username'))) {{ old('username') }}@endif" required>
    			</div>
    			<div class="form-group">
                            <label>Contraseña(*):</label>
                            <input type="password" class="form-control" maxlength="255" name="password" id="contraUs" placeholder="Ingrese contraseña" value="@if (! empty($usuario)){{ $usuario['password'] }}@elseif (! empty(old('password'))){{ old('password') }}@endif" required>
    			</div>
    			<div class="form-group">
                            <label>Nombre(*):</label>
                            <input type="text" class="form-control" maxlength="255" name="first_name" id="nombreRealUs" placeholder="Ingrese nombre real del usuario" value="@if (! empty($usuario)){{ $usuario['first_name'] }}@elseif (! empty(old('first_name'))) {{ old('first_name') }} @endif" required>
    			</div>
    			<div class="form-group">
                            <label>Apellido(*):</label>
                            <input type="text" class="form-control" maxlength="30" name="last_name" id="apellidoUs" placeholder="Ingrese apellido del usuario" value="@if (! empty($usuario)){{ $usuario['last_name'] }}@elseif (! empty(old('last_name'))) {{ old('last_name') }}@endif" required>
    			</div>
                        @if(! empty($roles))
                        <div>
                            <h4>Seleccion el/los roles para este usuario</h4>
                            @foreach($roles as $rol) 
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="check[]" value="{{ $rol['id'] }}">{{ $rol['nombre'] }}
                                </label>
                            @endforeach
                        </div>
                        @endif
    			<button type="submit" class="btn btn-primary center-block" onclick="return validarCrearUsuario();">@if (! empty($usuario))Actualizar información @else Registrar nuevo usuario @endif</button>
            	</form>
            </div>
	</div>
    </div>
@endsection