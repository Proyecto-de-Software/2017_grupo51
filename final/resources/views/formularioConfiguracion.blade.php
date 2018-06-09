@extends('layout')

@section('javascript')
    <script src="{{ asset('js/moduloConfiguracion.js') }}"></script>
@endsection

@section('navlist')
    <div>
        <ul class="nav navbar-nav navbar-right navbar-header">
            <li><a href="{{ url('/home') }}" class="btn"><span class="glyphicon glyphicon-arrow-left"></span> Volver al inicio</a></li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="text-center">
                <h1><strong>Actualización de la configuracion del sitio.</strong></h1>
            </div>
        </div>
    </div>
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
                <form action="{{ url('/configuracion') }}" method= "POST" name="formConfiguracion" >
                     {{ method_field('PUT') }}
                    <input type="hidden" name="id" value="{{ $config[0]['attributes']['id'] }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label>Título de la página:</label>
                        <input type="text" class="form-control" name="titulo_pagina" id="tituloPagina" placeholder="Ingresa el título" value="{{ $config[0]['attributes']['titulo_pagina'] }}" required>
                    </div>
                    <div class="form-group">
                        <label>Mail de contacto:</label>
                        <input type="email" class="form-control" name="mail_contacto" id="mailContacto" placeholder="Ingresa mail" value="{{ $config[0]['attributes']['mail_contacto'] }}" required>
                    </div>
                    <div class="form-group">
                        <label>Cantidad de elementos por página de listado:</label>
                        <input type="number" class="form-control" name="elementos_pagina" id="elementosPagina" placeholder="Ingresa cantidad" value="{{ $config[0]['attributes']['elementos_pagina'] }}" required>
                    </div>
                    <div class="form-group">
                        <label>Estado del sitio:</label>
                        <label><input type="radio" value="1" name="pagina_activa" id="sitioActivo" required @if ($config[0]['attributes']['pagina_activa']) checked @endif>Activo</label>
      			<label><input type="radio" value="0" name="pagina_activa" id="sitioBloqueado" required @if (! $config[0]['attributes']['pagina_activa']) checked @endif>Bloqueado</label>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary center-block" onclick="return validarInformacion()">Actualizar información</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('atributosFooter')
    @if (! $errors->any())
        class="posicionFooter"
    @endif
@endsection