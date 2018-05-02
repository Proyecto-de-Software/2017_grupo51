<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="{{ asset("css/bootstrap.min.css") }}" >
        <link rel="stylesheet" type="text/css" href="{{ asset("css/estilos.css") }}" >
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<script src="{{ asset("js/jquery.js")}}"></script>
        <script src="{{ asset("js/jquery-3.2.1.js") }}"></script>
	<script src="{{ asset("js/bootstrap.min.js") }}"></script>
        @yield('stylesheet')
        @yield('javascript')
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
	<title>Inicio</title>
    </head>
    <body @yield('atributosBody')>
         <header>
            <nav class="navbar back-color">
                <div class="container-fluid">
                    <div class="navbar-header">
                       <a href="#" class="navbar-brand" style="color:white;">{{ $config[0]['attributes']['titulo_pagina'] }}</a>
                    </div>
                    @yield('navlist')
                </div>
            </nav>
        </header>
        
        @yield('content')
        <footer @yield('atributosFooter')>
            <div class="container">
                <div class="row centro-vertical">
                    <div class="col-md-6 col-sm-6 col-xs-12 text-center">
                        <p>Proyecto de Software 2017<br>Consultorio del Niño Sano - Hospital Dr. Ricardo Gutierrez</p>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 text-center">
                        <h3 class="title">Información de contacto</h3>
                        <p><i class="glyphicon glyphicon-globe"></i>{{ $config[0]['attributes']['mail_contacto'] }}</p>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>