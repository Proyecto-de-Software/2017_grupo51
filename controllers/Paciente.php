<?php

class Paciente{
    private static $instance;
    
    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function forPac(){
        //Muestra el formulario de registro de pacientes para crear.
        if(UserController::getInstance()->sePuedeAccederASeccion('paciente_new')){
            $this->formularioPacientes([]);
        }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function formularioPacientes($arreglo){
        //Muestra formulario de pacientes
        $layout = IndexController::getInstance()->layout();
        $documentos_api = file_get_contents("https://api-referencias.proyecto2017.linti.unlp.edu.ar/tipo-documento");
        $obraSocial_api = file_get_contents("https://api-referencias.proyecto2017.linti.unlp.edu.ar/obra-social");
        $tipoVivienda_api = file_get_contents("https://api-referencias.proyecto2017.linti.unlp.edu.ar/tipo-vivienda");
        $tipoAgua_api = file_get_contents("https://api-referencias.proyecto2017.linti.unlp.edu.ar/tipo-agua");
        $tipoCalefaccion_api = file_get_contents("https://api-referencias.proyecto2017.linti.unlp.edu.ar/tipo-calefaccion");
        $layout['documentos'] = json_decode($documentos_api);
        $layout['obraSocial'] = json_decode($obraSocial_api);
        $layout['tipoVivienda'] = json_decode($tipoVivienda_api);
        $layout['tipoAgua'] = json_decode($tipoAgua_api);
        $layout['tipoCalefaccion'] = json_decode($tipoCalefaccion_api);
        if(isset($arreglo['paciente'])){
            $layout['paciente'] = $arreglo['paciente'];
            $layout['titulo'] = 'Actualizar información.';
        }else{
            $layout['titulo'] = 'Ingrese los datos del nuevo paciente.';
        }
        Home::getInstance()->show('formPacientes.html.twig',$layout);
    }
    
    public function modificarPaciente($idPaciente){
        //Carga el formulario de paciente con los datos del paciente pasado por parametro
        if(UserController::getInstance()->sePuedeAccederASeccion('paciente_update')){
            $parametros['paciente'] = PacienteModel::getInstance()->obtenerDatosCompletos($idPaciente);
            $this->formularioPacientes($parametros);
        }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function accesoPagPacientes(){
        //Accede a la seccion de pacientes.
        if(UserController::getInstance()->sePuedeAccederASeccion('paciente_seccion')){
            $layout = IndexController::getInstance()->layout();
            $layout['titulo'] = 'Bienvenido a la seccion de pacientes.';
            $layout['id_usuario'] = $_SESSION['id_usuario'];
            $layout['rol_nombre'] = $_SESSION['rolNombre'];
            Home::getInstance()->show('seccionPacientes.html.twig',$layout);
        }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function listadoCompletoPacientes(){
        //Lista todos los pacientes con algunos detalles
        if(UserController::getInstance()->sePuedeAccederASeccion('paciente_index')){
            $pacientes = PacienteModel::getInstance()->obtenerPacientes();
            if(count($pacientes) == 0){
                    $parametros['mensaje'] = 'No hay pacientes registrados.';
                    $this->accesoAPaginaPacientes($parametros);
                }else{
                    $parametros['listaPacientes'] = $pacientes;
                    $parametros['mensaje'] = 'Listado de pacientes.';
                    $this->accesoAPaginaPacientes($parametros);
                }
        }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function accesoAPaginaPacientes($arreglo){
        //Muestra la pagina con el listado de pacientes
        $layout = IndexController::getInstance()->layout();
        $layout['titulo'] = $arreglo['mensaje'];
        $layout['rol_nombre'] = $_SESSION['rolNombre'];
        if(isset($arreglo['listaPacientes'])){
            $layout['listaPacientes'] = $arreglo['listaPacientes'];
            $layout['elemPorPagina'] = ConfigurationModule::getInstance()->elementosPorPagina();
        }elseif(isset($arreglo['pacienteCompleto'])){
            $layout['pacienteCompleto'] = $arreglo['pacienteCompleto'];
        }
        Home::getInstance()->show('seccionPacientes.html.twig',$layout);
    }
    
    public function verDatosCompletosPaciente($idPaciente){
        //Muestra los datos completos de el paciente pasado por parametro
        if(UserController::getInstance()->sePuedeAccederASeccion('paciente_show')){
            $paciente = PacienteModel::getInstance()->obtenerDatosCompletos($idPaciente);
            $arreglo = array(
                'mensaje' => 'Datos del paciente.',
                'pacienteCompleto' => $paciente,
            );
            $this->accesoAPaginaPacientes($arreglo);
        }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function eliminarPaciente($idPaciente){
        //Elimina el paciente pasado por parametro
        if(UserController::getInstance()->sePuedeAccederASeccion('paciente_destroy')){
            PacienteModel::getInstance()->eliminarPaciente($idPaciente);
            $this->listadoCompletoPacientes();
        }else{
            $this->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function buscarPorNombre($nombrePaciente){
        //Busca pacientes por su nombre
        if(UserController::getInstance()->sePuedeAccederASeccion('paciente_index')){
            $pacientes = PacienteModel::getInstance()->buscarNombrePaciente($nombrePaciente);
            if(count($pacientes) > 0){
                $parametros['mensaje'] = 'Pacientes con nombre que contenga: '.$nombrePaciente;
                $parametros['listaPacientes'] = $pacientes;
            }else{
                $parametros['mensaje'] = 'No hay pacientes cuyo nombre contenga: '.$nombrePaciente;
            }
            $this->accesoAPaginaPacientes($parametros);
        }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function buscarPorApellido($apellidoPaciente){
        //Busca pacientes por su apellido
        if(UserController::getInstance()->sePuedeAccederASeccion('paciente_index')){
            $pacientes = PacienteModel::getInstance()->buscarApellidoPaciente($apellidoPaciente);
            if(count($pacientes) > 0){
                $parametros['mensaje'] = 'Pacientes con apellido que contengan: '.$apellidoPaciente;
                $parametros['listaPacientes'] = $pacientes;
            }else{
                $parametros['mensaje'] = 'No hay pacientes cuyo apellido contenga: '.$apellidoPaciente;
            }
            $this->accesoAPaginaPacientes($parametros);
        }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function buscarPorDocumento($numero,$doc){
        // Busca pacientes por tipo y numero de documento
        if(UserController::getInstance()->sePuedeAccederASeccion('paciente_index')){
            $pacientes = PacienteModel::getInstance()->buscarDocumentoPaciente($numero,$doc);
            if(count($pacientes) > 0){
                $parametros['mensaje'] = 'Pacientes con tipo de documento '.$doc. ' que contengan el número: '.$numero;
                $parametros['listaPacientes'] = $pacientes;
            }else{
                $parametros['mensaje'] = 'No hay pacientes con tipo de documento '.$doc. ' que contengan el número: '.$numero;
            }
            $this->accesoAPaginaPacientes($parametros);
        }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }

    function ya_existe_paciente($numero_doc){
        //Chequea si existe paciente con el numero de documento pasado por parametro
        $aux = PacienteModel::getInstance()->existePaciente($numero_doc);
        if (count($aux) == 0){
            return true;
        }else {
            return false;
        }
    }

    function crearTablaPaciente($creaOActualiza,$idpaciente){
        //Si creaOActualiza es false crea un nuevo paciente. Actualiza en caso contrario.
        if(UserController::getInstance()->sePuedeAccederASeccion('paciente_new')){
            if($this->validarDatos()){
                if($_POST["TelefonoP"] == 0){
                    $telefono = NULL;
                }else{
                    $telefono = $_POST["TelefonoP"];
                }
                if($_POST["ObraSocP"] == 'No posee'){
                    $obraSocial = NULL;
                }else{
                    $obraSocial = $_POST["ObraSocP"];
                }
                $arregloPac = array("0"=> $_POST["ApellidoP"], "1"=> $_POST["NombreP"],"2"=> $_POST["FecNacP"],"3"=> $_POST["GeneroP"] ,"4"=> $_POST["TipoDocP"], "5"=> $_POST["NroDocP"], "6"=> $_POST["DomicP"], "7"=> $telefono, "8"=> $obraSocial, "9"=> $_POST["heladera"], "10"=> $_POST["electricidad"], "11"=> $_POST["mascota"], "12"=> $_POST["viviendaP"], "13"=> $_POST["calefaP"], "14"=> $_POST["tipoAguaP"] );
                if($creaOActualiza){
                    PacienteModel::getInstance()->actualizarPaciente($arregloPac,$idpaciente);
                }else{
                    PacienteModel::getInstance()->insertarPaciente($arregloPac);
                }
                $idPaciente = PacienteModel::getInstance()->obtenerIdPacienteConDoc($_POST['TipoDocP'],$_POST["NroDocP"]);
                $this->verDatosCompletosPaciente($idPaciente);
            }
        }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function validarDatos(){
        //Valida los datos ingresados
        if(isset($_POST["ApellidoP"]) && isset($_POST["NombreP"]) && isset($_POST["ObraSocP"]) && isset($_POST["FecNacP"]) && isset($_POST["GeneroP"]) && isset($_POST["TipoDocP"]) && isset($_POST["NroDocP"]) && isset($_POST["DomicP"]) && isset($_POST["heladera"]) && isset($_POST["electricidad"]) && isset($_POST["mascota"]) && isset($_POST["viviendaP"]) && isset($_POST["calefaP"]) && isset($_POST["tipoAguaP"]) && isset($_POST["TelefonoP"])){
            if(is_string($_POST["ApellidoP"]) && is_string($_POST["NombreP"]) && is_string($_POST["ObraSocP"]) && is_string($_POST["GeneroP"]) && is_string($_POST["TipoDocP"]) && is_string($_POST["NroDocP"]) && is_string($_POST["DomicP"]) && is_string($_POST["heladera"]) && is_string($_POST["electricidad"]) && is_string($_POST["mascota"]) && is_string($_POST["viviendaP"]) && is_string($_POST["calefaP"]) && is_string($_POST["tipoAguaP"]) && is_string($_POST["TelefonoP"])){
                return true;
            }
        }
        return false;
    }
       
    public function verHistoriaClinica($idPaciente,$fechaNacimiento){
        //Muestra los controles de un paciente
        if(UserController::getInstance()->sePuedeAccederASeccion('control_index')){
            $controles = PacienteModel::getInstance()->obtenerControles($idPaciente);
            $parametros = IndexController::getInstance()->layout();
            if(count($controles) > 0){
                $parametros['titulo'] = 'Controles.';
                $parametros['controles'] = $controles;
                $parametros['elemPorPagina'] = ConfigurationModule::getInstance()->elementosPorPagina();
                $parametros['rol_nombre'] = $_SESSION['rolNombre'];
                $parametros['nacimiento'] = $fechaNacimiento;
                $parametros['idPaciente'] = $idPaciente;
            }else{
                $parametros['titulo'] = 'No posee controles.';
            }
            Home::getInstance()->show('historiaClinica.html.twig',$parametros);
        }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function verControlCompleto($idControl,$fechaNacimiento){
        //Muestra un control completo de un paciente
        if(UserController::getInstance()->sePuedeAccederASeccion('paciente_historia')){
            $parametros = IndexController::getInstance()->layout();
            $parametros['control'] = PacienteModel::getInstance()->obtenerControlCompleto($idControl);
            $edad = $this->calcular_edad($fechaNacimiento,$parametros['control'][0]['fecha']);
            if($edad->format('%Y') == 0){
                $parametros['edad'] = $this->calculoSemanas($fechaNacimiento, $parametros['control'][0]['fecha']);
            }else{
                $parametros['edad'] = $edad->format('%Y').' años';
            }
            Home::getInstance()->show('controlCompleto.html.twig',$parametros);
        }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function calculoSemanas($fechaNacimiento,$fechaControl){
        //Calculo de semanas entre 2 fechas
        $datetime1 = new DateTime($fechaNacimiento);
        $datetime2 = new DateTime($fechaControl);
        $interval = $datetime1->diff($datetime2);
        return floor(($interval->format('%a') / 7)) . ' semanas con '
     . ($interval->format('%a') % 7) . ' días';
    }
    
    public function calcular_edad($fechaNacimiento,$fechaControl){
        //Calculo de años entre 2 fechas
        $fecha_nac = new DateTime(date('Y/m/d',strtotime($fechaNacimiento))); // Creo un objeto DateTime de la fecha ingresada
        $fecha_hoy =  new DateTime(date('Y/m/d',strtotime($fechaControl))); // Creo un objeto DateTime de la fecha de hoy
        $edad = date_diff($fecha_hoy,$fecha_nac); // La funcion ayuda a calcular la diferencia, esto seria un objeto
        return $edad;
    }

    public function verGraficoPeso($idPaciente,$sexo,$fechaNacimiento){
        //Muestra el grafico de evolucion del peso de un paciente
        if(UserController::getInstance()->sePuedeAccederASeccion('paciente_historia')){
            $nuevafecha = date('Y-m-d', strtotime("$fechaNacimiento + 98 day"));
            $controles = PacienteModel::getInstance()->obtenerPeso($idPaciente,$fechaNacimiento,$nuevafecha);
            $cantControles = count($controles);
            if($cantControles > 0){
                $arregloGrafico = $this->obtenerDatosParaGraficar($fechaNacimiento, $controles, $cantControles,'peso');
                $datosGraficaPaciente = ['name' => 'Paciente', 'data' => $arregloGrafico];
            }else{
                $datosGraficaPaciente = ['name' => 'Sin registro del paciente', 'data' => []];
            }
            $layout = IndexController::getInstance()->layout();
            if($sexo == 'Masculino'){
                $layout['datosGrafico'] = $this->datosPesoMasculino($datosGraficaPaciente);
            }else{
                $layout['datosGrafico'] = $this->datosPesoFemenino($datosGraficaPaciente);
            }
            Home::getInstance()->show('grafico.html.twig',$layout);
        }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function obtenerDatosParaGraficar($fechaNacimiento,$controles,$cantControles,$datoArreglo){
        //Retorna un arreglo con los datos del paciente a graficar
        $pos = 0;
        $arregloGrafico = [];
        $fechaAnt = $fechaNacimiento;
        $fechaSig = date('Y-m-d', strtotime("$fechaNacimiento + 7 day"));
        for($i=0; $i<=13; $i++){
            if(($controles[$pos]['fecha'] >= $fechaAnt) && ($controles[$pos]['fecha'] <= $fechaSig) ){
                $arregloAux = [$i,  floatval($controles[$pos][$datoArreglo])];
                array_push($arregloGrafico, $arregloAux);
                $pos++;
                if($pos == $cantControles){
                    break;
                }
            }
            $fechaAnt = $fechaSig;
            $fechaSig = date('Y-m-d', strtotime("$fechaAnt + 7 day"));
        }
        return $arregloGrafico;
    }
    
    public function datosPesoFemenino($datosPaciente){
        //Datos del peso para mujeres
        $peso['titulo'] = 'Grafico de evolucion del peso - Femenino.';
        $peso['Ymin'] = 2;
        $peso['Ymax'] = 8;
        $peso['YminorTickInterval'] = 0.5;
        $peso['Xmin'] = 0;
        $peso['Xmax'] = 13;
        $peso['XminorTickInterval'] = 0.1;
        $peso['tituloY'] = 'Peso(kg)';
        $peso['tituloX'] = 'Edad(semanas)';
        $peso['series'] = array(['name' => 'SD2neg', 'data' => [ [0, 2.4], [1, 2.5], [2, 2.7], [3, 2.9], [4, 3.1], [5, 3.3], [6, 3.5], [7, 3.7], [8, 3.9], [9, 4.1], [10, 4.2], [11, 4.3], [12, 4.4], [13, 4.5] ]],['name' => 'SD1neg', 'data' => [[0, 2.8], [1, 2.9], [2, 3.1], [3, 3.3], [4, 3.5], [5, 3.8], [6, 4.0], [7, 4.2],[8, 4.4], [9, 4.5], [10, 4.7], [11, 4.8], [12, 5.0], [13, 5.1] ]],['name' => 'SD0', 'data' => [ [0, 3.2], [1, 3.3], [2, 3.6], [3, 3.8], [4, 4.1], [5, 4.3], [6, 4.6], [7, 4.8],[8, 5.0], [9, 5.2], [10, 5.4], [11, 5.5], [12, 5.7], [13, 5.8] ]],['name' => 'SD1', 'data' => [	[0, 3.7],  [1, 3.9], [2, 4.1], [3, 4.4], [4, 4.7], [5, 5.0], [6, 5.2], [7, 5.5], [8, 5.7], [9, 5.9], [10, 6.1], [11, 6.3], [12, 6.5], [13, 6.6] ]],['name' => 'SD2', 'data' => [	[0, 4.2],  [1, 4.4], [2, 4.7], [3, 5.0], [4, 5.4], [5, 5.7], [6, 6.0], [7, 6.2], [8, 6.5], [9, 6.7], [10, 6.9], [11, 7.1], [12, 7.3], [13, 7.5] ]]);
        array_push($peso['series'],$datosPaciente);
        return json_encode($peso);
    }
    
    public function datosPesoMasculino($datosPaciente){
        //Datos del peso para hombres
        $peso['titulo'] = 'Grafico de evolucion del peso - Masculino.';
        $peso['Ymin'] = 2;
        $peso['Ymax'] = 8;
        $peso['YminorTickInterval'] = 0.5;
        $peso['Xmin'] = 0;
        $peso['Xmax'] = 13;
        $peso['XminorTickInterval'] = 0.1;
        $peso['tituloY'] = 'Peso(kg)';
        $peso['tituloX'] = 'Edad(semanas)';
        $peso['series'] = array(['name' => 'P3', 'data' => [ [0, 2.5], [1, 2.6], [2, 2.8], [3, 3.1], [4, 3.4], [5, 3.6], [6, 3.8],[7, 4.1], [8, 4.3], [9, 4.4], [10, 4.6], [11, 4.8], [12, 4.9], [13, 5.1] ]],['name' => 'P15', 'data' => [ [0, 2.9], [1, 3.0], [2, 3.2], [3, 3.5], [4, 3.8], [5, 4.1], [6, 4.3],[7, 4.5], [8, 4.7], [9, 4.9], [10, 5.1], [11, 5.3], [12, 5.5], [13, 5.6] ]],['name' => 'P50', 'data' => [ [0, 3.3], [1, 3.5], [2, 3.8], [3, 4.1], [4, 4.4], [5, 4.7], [6, 4.9],[7, 5.2], [8, 5.4], [9, 5.6], [10, 5.8], [11, 6.0], [12, 6.2], [13, 6.4] ]],['name' => 'P85', 'data' => [ [0, 3.7], [1, 3.9], [2, 4.1], [3, 4.4], [4, 4.7], [5, 5.0], [6, 5.2],[7, 5.5], [8, 5.7], [9, 5.9], [10, 6.1], [11, 6.3], [12, 6.5], [13, 6.6] ]],['name' => 'P97', 'data' => [ [0, 4.3], [1, 4.5], [2, 4.9], [3, 5.2], [4, 5.6], [5, 5.9], [6, 6.3],[7, 6.5], [8, 6.8], [9, 7.1], [10, 7.3], [11, 7.5], [12, 7.7], [13, 7.9] ]]);
        array_push($peso['series'],$datosPaciente);
        return json_encode($peso);
    }
    
    public function verGraficoTalla($idPaciente,$sexo,$fechaNacimiento){
        //Muestra el grafico de evolucion de la talla de un paciente
        if(UserController::getInstance()->sePuedeAccederASeccion('paciente_historia')){
            $nuevafecha = date('Y-m-d', strtotime("$fechaNacimiento + 98 day"));
            $controles = PacienteModel::getInstance()->obtenerTalla($idPaciente,$fechaNacimiento,$nuevafecha);
            $cantControles = count($controles);
            if($cantControles > 0){
                $arregloGrafico = $this->obtenerDatosParaGraficar($fechaNacimiento, $controles, $cantControles,'talla');
                $datosGraficaPaciente = ['name' => 'Paciente', 'data' => $arregloGrafico];
            }else{
                $datosGraficaPaciente = ['name' => 'Sin registro del paciente', 'data' => []];
            }
            $layout = IndexController::getInstance()->layout();
            if($sexo == 'Masculino'){
                $layout['datosGrafico'] = $this->datosTallaMasculino($datosGraficaPaciente);
            }else{
                $layout['datosGrafico'] = $this->datosTallaFemenino($datosGraficaPaciente);
            }
            Home::getInstance()->show('grafico.html.twig',$layout);
        }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function datosTallaFemenino($datosPaciente){
        //Datos de la talla para mujeres
        $talla['titulo'] = 'Gráfico de la evolución de la talla mujeres hasta 2 años.';
        $talla['YminorTickInterval'] = 0.5;
        $talla['XminorTickInterval'] = 0.5;
        $talla['tituloY'] = 'Talla(cm)';
        $talla['tituloX'] = 'Edad (semanas)';
        $talla['series'] = array(['name' => 'P3', 'data' => [[0,45.6],[4,50],[8,53.2],[13,55.8]]],['name' => 'P15', 'data' => [[0,47.2],[4,51.7],[8,55],[13,57.6]]],['name' => 'P50', 'data' => [[0,49.1],[4,53.7],[8,57.1],[13,59.8]]],['name' => 'P85', 'data' => [[0,51.1],[4,55.7],[8,59.2],[13,62]],['name' => 'P97', 'data' => [[0,52.7],[4,57.4],[8,60.9],[13,63.8]]]]);
        array_push($talla['series'],$datosPaciente);
        return json_encode($talla);
    }
    
    public function datosTallaMasculino($datosPaciente){
        //Datos de la talla para hombres
        $talla['titulo'] = 'Gráfico de la evolución de la talla hombres hasta 2 años.';
        $talla['YminorTickInterval'] = 0.5;
        $talla['XminorTickInterval'] = 0.5;
        $talla['tituloY'] = 'Talla(cm)';
        $talla['tituloX'] = 'Edad (semanas)';
        $talla['series'] = array(['name' => 'P3', 'data' => [[0,46.3],[4,51.1],[8,54.7],[13,57.6]]],['name' => 'P15', 'data' => [[0,47.9],[4,52.7],[8,56.4],[13,59.3]]],['name' => 'P50', 'data' => [[0,49.9],[4,54.7],[8,58.4],[13,61.4]]],['name' => 'P85', 'data' => [[0,51.8],[4,56.7],[8,60.5],[13,63.5]],['name' => 'P97', 'data' => [[0,53.4],[4,58.4],[8,62.2],[13,65.3]]]]);
        array_push($talla['series'],$datosPaciente);
        return json_encode($talla);
    }
    
    public function verGraficoPercentiloCefalico($idPaciente,$sexo,$fechaNacimiento){
        //Muestra el grafico de evolucion del percentilo cefalico de un paciente
        if(UserController::getInstance()->sePuedeAccederASeccion('paciente_historia')){
            $nuevafecha = date('Y-m-d', strtotime("$fechaNacimiento + 98 day"));
            $controles = PacienteModel::getInstance()->obtenerPercentilos($idPaciente,$fechaNacimiento,$nuevafecha);
            $cantControles = count($controles);
            if($cantControles > 0){
                $arregloGrafico = $this->obtenerDatosParaGraficar($fechaNacimiento, $controles, $cantControles,'percentilo_perimetro_cefalico');
                $datosGraficaPaciente = ['name' => 'Paciente', 'data' => $arregloGrafico];
            }else{
                $datosGraficaPaciente = ['name' => 'Sin registro del paciente', 'data' => []];
            }
            $layout = IndexController::getInstance()->layout();
            if($sexo == 'Masculino'){
                $layout['datosGrafico'] = $this->datosPercentiloMasculino($datosGraficaPaciente);
            }else{
                $layout['datosGrafico'] = $this->datosPercentiloFemenino($datosGraficaPaciente);
            }
            Home::getInstance()->show('grafico.html.twig',$layout);
        }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function datosPercentiloMasculino($datosPaciente){
        //Datos del percentilo cefalico para hombres
        $percentilo['titulo'] = 'Gráfico de la evolución del PPC hombres hasta 13 semanas.';
        $percentilo['YminorTickInterval'] = 0.1;
        $percentilo['XminorTickInterval'] = 0.5;
        $percentilo['tituloY'] = 'Circunferencia cefálica (En cm.)';
        $percentilo['tituloX'] = 'Edad(semanas)';
        $percentilo['series'] = array(['name' => 'P3', 'data' => [ [0, 32.1], [1, 32.9], [2, 33.7], [3, 34.3], [4, 34.9], [5, 35.4], [6, 35.9], [7, 36.3], [8, 36.7], [9, 37], [10, 37.4], [11, 37.7], [12, 38], [13, 38.3] ]],['name' => 'P15', 'data' => [ [0, 33.1], [1, 33.9], [2, 34.7], [3, 35.3], [4, 35.9], [5, 36.4], [6, 36.8], [7, 37.3],[8, 37.7], [9, 38], [10, 38.4], [11, 38.7], [12, 39], [13, 39.3] ]],['name' => 'P50', 'data' => [ [0, 34.5], [1, 35.2], [2, 35.9], [3, 36.5], [4, 37.1], [5, 37.6], [6, 38.1], [7, 38.5], [8, 38.9], [9, 39.2], [10, 39.6], [11, 39.9], [12, 40.2], [13, 40.5] ]],['name' => 'P85', 'data' => [ [0, 35.8], [1, 36.4], [2, 37.1], [3, 37.7], [4, 38.3], [5, 38.8], [6, 39.3], [7, 39.7], [8, 40.1], [9, 40.5], [10, 40.8], [11, 41.1], [12, 41.4], [13, 41.7] ]],['name' => 'P97', 'data' => [ [0, 36.9], [1, 37.5], [2, 38.1], [3, 38.7], [4, 39.3], [5, 39.8], [6, 40.3], [7, 40.7],[8, 41.1], [9, 41.4], [10, 41.8], [11, 42.1], [12, 42.4], [13, 42.7] ]]);
        array_push($percentilo['series'],$datosPaciente);
        return json_encode($percentilo);
    }
    
    public function datosPercentiloFemenino($datosPaciente){
        //Datos del percentilo cefalico para mujeres
        $percentilo['titulo'] = 'Gráfico de la evolución del PPC mujeres hasta 13 semanas.';
        $percentilo['YminorTickInterval'] = 0.1;
        $percentilo['XminorTickInterval'] = 0.5;
        $percentilo['tituloY'] = 'Circunferencia cefálica (En cm.)';
        $percentilo['tituloX'] = 'Edad(semanas)';
        $percentilo['series'] = array(['name' => 'P3', 'data' => [ [0, 31.7], [1, 32.4], [2, 33.1], [3, 33.7], [4, 34.2], [5, 34.6], [6, 35], [7, 35.4], [8, 35.7], [9, 36.1], [10, 36.4], [11, 36.7], [12, 36.9], [13, 37.2] ]],['name' => 'P15', 'data' => [ [0, 32.7], [1, 33.3], [2, 34], [3, 34.6], [4, 35.2], [5, 35.6], [6, 36], [7, 36.4],[8, 36.8], [9, 37.1], [10, 37.4], [11, 37.7], [12, 38], [13, 38.2] ]],['name' => 'P50', 'data' => [ [0, 33.9], [1, 34.6], [2, 35.2], [3, 35.8], [4, 36.4], [5, 36.8], [6, 37.3], [7, 37.7],[8, 38], [9, 38.4], [10, 38.7], [11, 39], [12, 39.3], [13, 39.5] ]],['name' => 'P85', 'data' => [ [0, 35.1], [1, 35.8], [2, 36.4], [3, 37], [4, 37.6], [5, 38.1], [6, 38.5], [7, 38.9],[8, 39.3], [9, 39.6], [10, 39.9], [11, 40.2], [12, 40.5], [13, 40.8] ]],['name' => 'P97', 'data' => [ [0, 36.1], [1, 36.7], [2, 37.4], [3, 38], [4, 38.6], [5, 39.1], [6, 39.5], [7, 39.9],[8, 40.3], [9, 40.6], [10, 41], [11, 41.3], [12, 41.6], [13, 41.9] ]]);
        array_push($percentilo['series'],$datosPaciente);
        return json_encode($percentilo);
    }
    
    public function eliminarControl($idControl,$fechaNacimiento,$idPaciente){
        //Elimina un control de un paciente
        if(UserController::getInstance()->sePuedeAccederASeccion('control_delete')){
            PacienteModel::getInstance()->eliminarControl($idControl);
            $this->verHistoriaClinica($idPaciente, $fechaNacimiento);
        }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function listadoGraficosDemograficos(){
        //Lista los nombres de los datos demograficos y la posibilidad de graficarlos
        if(UserController::getInstance()->sePuedeAccederASeccion('paciente_index')){
            $layout = IndexController::getInstance()->layout();
            Home::getInstance()->show('listaGraficosDemograficos.html.twig',$layout);
        }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function verGraficoMascotas(){
        //Muestra el grafico de porcentajes de pacientes que poseen/no posee mascotas
        if(UserController::getInstance()->sePuedeAccederASeccion('paciente_index')){
            $datosMascotas = PacienteModel::getInstance()->datosMascotas();
            $this->mostrarGraficoTortasPoseeNoPosee($datosMascotas,'mascota');
        }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function verGraficoElectricidad(){
        //Muestra el grafico de porcentajes de pacientes que poseen/no posee electricidad
        if(UserController::getInstance()->sePuedeAccederASeccion('paciente_index')){
            $datosElectricidad = PacienteModel::getInstance()->datosElectricidad();
            $this->mostrarGraficoTortasPoseeNoPosee($datosElectricidad,'electricidad');
        }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function verGraficoHeladera(){
        //Muestra el grafico de porcentajes de pacientes que poseen/no posee heladera
        if(UserController::getInstance()->sePuedeAccederASeccion('paciente_index')){
            $datosHeladera = PacienteModel::getInstance()->datosHeladera();
            $this->mostrarGraficoTortasPoseeNoPosee($datosHeladera,'heladera');
        }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function mostrarGraficoTortasPoseeNoPosee($datos,$atributo){
        //Muestra un grafico de tortas posee/no posee con los datos recibidos
        $cantidadDatos = count($datos);
        $layout = IndexController::getInstance()->layout();
        $layout['datosGrafico'] = $this->armaGraficoPoseeNoPosee($datos,$cantidadDatos,$atributo);
        Home::getInstance()->show('graficoTortas.html.twig',$layout);
    }


    public function armaGraficoPoseeNoPosee($datos,$cantidad,$atributo){
        //Arma un grafico de tortas posee/no posee con los datos recibidos para la funcion que grafica
        $posee = 0;
        foreach ($datos as $dato) {
            if($dato[0]){
                $posee++;
            }
        }
        $porcentajePosee = floatval($this->porcentaje($posee, $cantidad, 2));
        $porcentajeNoPosee = (100-$porcentajePosee);
        $grafico['titulo'] = 'Pacientes con '.$atributo.'.';
        $grafico['series'] = array(['name' => 'Posee','y' => $porcentajePosee],['name' => 'No posee','y' => $porcentajeNoPosee]);
        return json_encode($grafico);
    }
    
    public function porcentaje($cantidad,$porciento,$decimales){
        //Retorna porcentaje de un numero en una cantidad dada
        return number_format($cantidad*100/$porciento ,$decimales);
    }
    
    public function verGraficoAgua(){
        //Muestra el grafico de acuerdo al tipo de agua de los pacientes
        if(UserController::getInstance()->sePuedeAccederASeccion('paciente_index')){
            $agua = json_decode(file_get_contents("https://api-referencias.proyecto2017.linti.unlp.edu.ar/tipo-agua"));
            $datosPaciente = PacienteModel::getInstance()->agua();
            $this->graficoMultiplesDatos($agua, $datosPaciente, 'Tipo agua');
        }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function verGraficoVivienda(){
        //Muestra el grafico de acuerdo al tipo de vivienda de los pacientes
        if(UserController::getInstance()->sePuedeAccederASeccion('paciente_index')){
            $viviendas = json_decode(file_get_contents("https://api-referencias.proyecto2017.linti.unlp.edu.ar/tipo-vivienda"));
            $datosPacientes = PacienteModel::getInstance()->viviendas();
            $this->graficoMultiplesDatos($viviendas, $datosPacientes, 'Viviendas');
        }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function verGraficoCalefaccion(){
        //Muestra el grafico de acuerdo al tipo de calefaccion de los pacientes
        if(UserController::getInstance()->sePuedeAccederASeccion('paciente_index')){
            $calefacciones = json_decode(file_get_contents("https://api-referencias.proyecto2017.linti.unlp.edu.ar/tipo-calefaccion"));
            $datosPacientes = PacienteModel::getInstance()->calefaccion();
            $this->graficoMultiplesDatos($calefacciones, $datosPacientes, 'Tipo calefacción');
        }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function graficoMultiplesDatos($arregloDatosApi,$arregloDatosPaciente,$titulo){
        //Calcula los datos necesarios para realizar un grafico de tortas con varios datos
        foreach ($arregloDatosApi as $dato) {
            $cantPacientesPoseenDato[$dato->nombre] = 0;
        }
        foreach ($arregloDatosPaciente as $a) {
            $cantPacientesPoseenDato[$a[0]]++;
        }
        $layout = IndexController::getInstance()->layout();
        $layout['datosGrafico'] = $this->armarGraficoMultiplesDatos($cantPacientesPoseenDato, count($arregloDatosPaciente),$titulo);
        Home::getInstance()->show('graficoTortas.html.twig',$layout);
    }
    
    public function armarGraficoMultiplesDatos($datos,$cantidad,$atributo){
        //Arma los datos a enviarles a la libreria que grafica, en baso a lo recibidos
        foreach ($datos as $key => $datos){
            $porcentaje[$key] = floatval($this->porcentaje($datos, $cantidad, 2));
        }
        $grafico['titulo'] = $atributo;
        $grafico['series'] = [];
        foreach ($porcentaje as $key => $porc) {
            $arreglo = ['name' => $key,'y' => $porc];
            array_push($grafico['series'], $arreglo);
        }
        return json_encode($grafico);
    }

    public function validarControlPac(){
        //valida datos ingresados en formulario de control de pacientes
        if(isset($_POST["PesoPac"]) && isset($_POST["FechaCons"]) && isset($_POST["VacObs"]) && isset($_POST["MaduracionObs"]) && isset($_POST["ExamenFisicoOBs"]) && isset($_POST["ExamenFis"]) && isset($_POST["MaduraAcorde"]) && isset($_POST["AllVacunas"])){
            if(is_string($_POST["PesoPac"]) && is_string($_POST["FechaCons"]) && is_string($_POST["VacObs"]) && is_string($_POST["MaduracionObs"]) && is_string($_POST["ExamenFisicoOBs"]) && is_string($_POST["ExamenFis"]) && is_string($_POST["MaduraAcorde"]) && is_string($_POST["AllVacunas"])){
                return true;
            }
        }
        return false;
    } 
    public function crearControlPac(){
        if($this->validarControlPac()){
            $arreglo = array("0"=> $_POST["FechaCons"], "1"=> $_POST["PesoPac"],"2"=> $_POST["AllVacunas"],"3"=> $_POST["VacObs"] ,"4"=> $_POST["MaduraAcorde"], "5"=> $_POST["MaduracionObs"], "6"=> $_POST["ExamenFis"], "7"=> $_POST["ExamenFisicoObs"], "8"=> $_POST["PC"], "9"=> $_POST["PPC"], "10"=> $_POST["TallaPac"], "11"=> $_POST["AlimPac"], "12"=> $_POST["AlimObs"], "13"=>$_SESSION["id_usuario"]);
            PacienteModel::getInstance()->insertarControlPac($arreglo);
        }else{
            IndexController::getInstance()->index();
        }
    }

    public function actualizarControlPac($idControl){
        if($this->validarControlPac()){
            $arreglo = array("0"=> $_POST["FechaCons"], "1"=> $_POST["PesoPac"],"2"=> $_POST["AllVacunas"],"3"=> $_POST["VacObs"] ,"4"=> $_POST["MaduraAcorde"], "5"=> $_POST["MaduracionObs"], "6"=> $_POST["ExamenFis"], "7"=> $_POST["ExamenFisicoObs"], "8"=> $_POST["PC"], "9"=> $_POST["PPC"], "10"=> $_POST["TallaPac"], "11"=> $_POST["AlimPac"], "12"=> $_POST["AlimObs"], "13"=>$_SESSION["id_usuario"]);
            PacienteModel::getInstance()->insertarControlPac($arreglo, $idControl);
        }else{
            IndexController::getInstance()->index();
        }
    }

    public function cargarFormControl(){
        $layout = IndexController::getInstance()->layout();
        $layout['titulo'] = 'Ingrese la información.';
        Home::getInstance()->show('formControlPacientes.html.twig',$layout);
    }
}