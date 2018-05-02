@extends('layout')

@section('javascript')
    <script src="{{ asset('js/validarCrearUsr.js') }}"></script>
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
                    @endif
                    {{ csrf_field() }}
                    <h1 class="text-center"><label>{{ $accion }} usuario</label></h1><br>
            	    	<div class="form-group">
                            <label>Email (*):</label>
                            <input type="email" class="form-control" name="email" id="emailUs" placeholder="mail@hotmail.com" value="@if (! empty($usuario)){{ $usuario['email'] }}@endif" required>
    			</div>
    			<div class="form-group">
     	 		    <label>Nombre de usuario(*):</label>
                            <input type="text" class="form-control" maxlength="255" name="username" id="nombreUs" placeholder="Ingrese nombre de usuario" value="@if (! empty($usuario)){{ $usuario['username'] }}@endif" required>
    			</div>
    			<div class="form-group">
                            <label>Contraseña(*):</label>
                            <input type="password" class="form-control" maxlength="255" name="password" id="contraUs" placeholder="Ingrese contraseña" value="@if (! empty($usuario)){{ $usuario['password'] }}@endif" required>
    			</div>
    			<div class="form-group">
                            <label>Nombre(*):</label>
                            <input type="text" class="form-control" maxlength="255" name="first_name" id="nombreRealUs" placeholder="Ingrese nombre real del usuario" value="@if (! empty($usuario)){{ $usuario['first_name'] }}@endif" required>
    			</div>
    			<div class="form-group">
                            <label>Apellido(*):</label>
                            <input type="text" class="form-control" maxlength="30" name="last_name" id="apellidoUs" placeholder="Ingrese apellido del usuario" value="@if (! empty($usuario)){{ $usuario['last_name'] }}@endif" required>
    			</div>
    			<button type="submit" class="btn btn-primary center-block" onclick="return validarCrearUsuario();">@if (! empty($usuario))Actualizar información @else Registrar nuevo usuario @endif</button>
            	</form>
            </div>
	</div>
    </div>
@endsection