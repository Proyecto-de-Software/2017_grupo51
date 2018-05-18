@extends('layout')

@section('stylesheets')
    <link rel="stylesheet" type="text/css" href="{{ asset("css/sitioInhabilitado.css") }}" >
@endsection

@section('javascript')
    <script src="{{ asset("js/moduloUsuario.js") }}"></script>
    <script src="{{ asset("js/validacionPermisos.js") }}"></script>
    <script src="{{ asset("js/bloqueoBotonAtras.js") }}"></script>
@endsection

@section('navlist')
    <div>
        <ul class="nav navbar-nav navbar-right navbar-header">
            <li class="dropdown"><a class="dropdown-toggle btn" data-toggle="dropdown" href="#">Menú <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="{{ url('usuarios/index') }}">Usuarios</a></li>
                    <li><a href="./?action=pacientes">Pacientes</a></li>
                    <li><a href="./?action=roles">Roles</a></li>
                    <li><a href="#" class="divider"></a></li>
                    <li><a href="{{ url('configuracion') }}">Configuración del sistema</a></li>
                </ul>
            </li>
            <li><a href="{{ url('logout') }}" class="btn" onclick="return confirmacion('¿Cerrar sesión?');"><span class="glyphicon glyphicon-log-in"></span> Cerrar sesión</a></li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="container">
            @include('flash::message')
            <section class="col-md-4">
                <h3 class="text-center">El hospital</h3>
                <img class="img-responsive" src="{{ asset("images/image1.jpg") }}" alt="hospital_gutierrez">
                <!-- Imagen hospital -->
                <blockquote>
                <p class="text-info tamañoLetra">Este centro de salud tiene un programa de residencias
                de primer nivel en el país. Se ofrecen oportunidades de práctica
                intensiva y supervisada en ámbitos profesionales y por la misma
                -por supuesto- se recibe un salario mensual, acorde a lo que la
                regulación médica profesional lo indica en cada momento.</p>
                </blockquote>
            </section>
            <section class="col-md-4">
                <h3 class="text-center">Guardia</h3>
                <img class="img-responsive" src="{{ asset("images/image2.jpg") }}" alt="hospital_gutierrez">
                <!-- Imagen guardia -->
                <blockquote>
                <p class="text-info tamañoLetra">Hospital Dr. Ricardo Gutierrez de La Plata dispone de un
                sofisticado servicio de guardias médicas las 24 horas para la
                atención de distintas urgencias. La administración de la institución
                hace viable una eficiente separación de los pacientes según el nivel
                de seriedad y tipo de patología.</p>
                </blockquote>
            </section>
            <section class="col-md-4">
                <h3 class="text-center">Especialidades</h3>
                <img class="img-responsive" src="{{ asset("images/image3.jpg") }}" alt="hospital_gutierrez">
                <!-- Imagen especialidades -->
                <blockquote>
                <p class="text-info tamañoLetra">Acorde a una respetable trayectoria en materia de medicina y
                salud, en Hospital Dr. Ricardo Gutierrez de La Plata podemos encontrar
                profesionales de las principales especialidades de salud.
                Del mismo modo, se brinda atencion programada y de urgencias, se realizan
                estudios médicos y se brinda soporte en muchas de las ramas comunes
                de la medicina moderna.</p>
                </blockquote>
            </section>
        </div>
@endsection
