    @if ( !$config[0]['attributes']['pagina_activa'] )
        <div class="container">
            <div class="row">
                <div class="text-center">
                    <p><h1><strong>Sitio no disponible por el momento.</strong></h1></p>
                </div>
            </div>    
        </div>
    @else
        <div class="container">
            <section class="col-md-4">
                <h3 class="text-center">El hospital</h3>
                <img class="img-responsive" src="./images/image1.jpg" alt="hospital_gutierrez">
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
                <img class="img-responsive" src="./images/image2.jpg" alt="hospital_gutierrez">
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
                <img class="img-responsive" src="./images/image3.jpg" alt="hospital_gutierrez">
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
    @endif 