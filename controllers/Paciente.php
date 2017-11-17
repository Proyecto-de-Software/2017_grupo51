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
        if(UserController::getInstance()->sePuedeAccederASeccion('control_index')){
            $controles = PacienteModel::getInstance()->obtenerControles($idPaciente);
            $parametros = IndexController::getInstance()->layout();
            if(count($controles) > 0){
                $parametros['titulo'] = 'Controles.';
                $parametros['controles'] = $controles;
                $parametros['elemPorPagina'] = ConfigurationModule::getInstance()->elementosPorPagina();
                $parametros['rol_nombre'] = $_SESSION['rolNombre'];
                $parametros['nacimiento'] = $fechaNacimiento;
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
        $datetime1 = new DateTime($fechaNacimiento);
        $datetime2 = new DateTime($fechaControl);
        $interval = $datetime1->diff($datetime2);
        return floor(($interval->format('%a') / 7)) . ' semanas con '
     . ($interval->format('%a') % 7) . ' días';
    }
    
    public function calcular_edad($fechaNacimiento,$fechaControl){
        $fecha_nac = new DateTime(date('Y/m/d',strtotime($fechaNacimiento))); // Creo un objeto DateTime de la fecha ingresada
        $fecha_hoy =  new DateTime(date('Y/m/d',strtotime($fechaControl))); // Creo un objeto DateTime de la fecha de hoy
        $edad = date_diff($fecha_hoy,$fecha_nac); // La funcion ayuda a calcular la diferencia, esto seria un objeto
        return $edad;
    }

    public function verGraficoPeso($idPaciente,$sexo,$fechaNacimiento){
        if(UserController::getInstance()->sePuedeAccederASeccion('paciente_historia')){
            echo 'Fecha inicial: '.$fechaNacimiento;
            $nuevafecha = date('Y-m-d', strtotime("$fechaNacimiento + 98 day"));
            echo ' Nueva fecha: '.$nuevafecha;
            $layout = IndexController::getInstance()->layout();
            if($sexo == 'Masculino'){
                $layout['datosGrafico'] = $this->datosPesoMasculino();
            }else{
                $layout['datosGrafico'] = $this->datosPesoFemenino();
            }
            Home::getInstance()->show('grafico.html.twig',$layout);
        }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function datosPesoFemenino(){
        $peso['titulo'] = 'Grafico de evolucion del peso - Femenino.';
        $peso['Ymin'] = 2;
        $peso['Ymax'] = 8;
        $peso['YminorTickInterval'] = 0.5;
        $peso['Xmin'] = 0;
        $peso['Xmax'] = 13;
        $peso['XminorTickInterval'] = 0.1;
        $peso['tituloY'] = 'Peso(kg)';
        $peso['tituloX'] = 'Edad(semanas)';
        $peso['series'] = array(['name' => 'L', 'data' => [[0,0.3809],[1,0.2671],[2,0.2304],[3,0.2024],[4,0.1789],[5,0.1582],[6,0.1395],[7,0.1224],[8,0.1065],[9,0.0918],[10,0.0779],[11,0.0648],[12,0.0525],[13,0.0407]]],['name' => 'M', 'data' => [[0,3.2322],[1,3.3388],[2,3.5693],[3,3.8352],[4,4.0987],[5,4.3476],[6,4.5793],[7,4.7950],[8,4.9959],[9,5.1842],[10,5.3618],[11,5.5295],[12,5.6883],[13,5.8393]]],['name' => 'S', 'data' => [[0,0.14171],[1,0.14600],[2,0.14339],[3,0.14060],[4,0.13805],[5,0.13583],[6,0.13392],[7,0.13228],[8,0.13087],[9,0.12966],[10,0.12861],[11,0.12770],[12,0.12691],[13,0.12622]]],['name' => 'SD3neg', 'data' => [[0,2.0],[1,2.1],[2,2.3],[3,2.5],[4,2.7],[5,2.9],[6,3.0],[7,3.2],[8,3.3],[9,3.5],[10,3.6],[11,3.8],[12,3.9],[13,4.0]]],['name' => 'SD2neg', 'data' => [ [0, 2.4], [1, 2.5], [2, 2.7], [3, 2.9], [4, 3.1], [5, 3.3], [6, 3.5], [7, 3.7], [8, 3.9], [9, 4.1], [10, 4.2], [11, 4.3], [12, 4.4], [13, 4.5] ]],['name' => 'SD1neg', 'data' => [[0, 2.8], [1, 2.9], [2, 3.1], [3, 3.3], [4, 3.5], [5, 3.8], [6, 4.0], [7, 4.2],[8, 4.4], [9, 4.5], [10, 4.7], [11, 4.8], [12, 5.0], [13, 5.1] ]],['name' => 'SD0', 'data' => [ [0, 3.2], [1, 3.3], [2, 3.6], [3, 3.8], [4, 4.1], [5, 4.3], [6, 4.6], [7, 4.8],[8, 5.0], [9, 5.2], [10, 5.4], [11, 5.5], [12, 5.7], [13, 5.8] ]],['name' => 'SD1', 'data' => [	[0, 3.7],  [1, 3.9], [2, 4.1], [3, 4.4], [4, 4.7], [5, 5.0], [6, 5.2], [7, 5.5], [8, 5.7], [9, 5.9], [10, 6.1], [11, 6.3], [12, 6.5], [13, 6.6] ]],['name' => 'SD2', 'data' => [	[0, 4.2],  [1, 4.4], [2, 4.7], [3, 5.0], [4, 5.4], [5, 5.7], [6, 6.0], [7, 6.2], [8, 6.5], [9, 6.7], [10, 6.9], [11, 7.1], [12, 7.3], [13, 7.5] ]],['name' => 'SD3', 'data' => [[0,4.8],[1,5.1],[2,5.4],[3,5.7],[4,6.1],[5,6.5],[6,6.8],[7,7.1],[8,7.3],[9,7.6],[10,7.8],[11,8.1],[12,8.3],[13,8.5]]]);
        return json_encode($peso);
    }
    
    public function datosPesoMasculino(){
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
        return json_encode($peso);
    }
    
    public function verGraficoTalla($idPaciente,$sexo){
        if(UserController::getInstance()->sePuedeAccederASeccion('paciente_historia')){
            $layout = IndexController::getInstance()->layout();
            if($sexo == 'Masculino'){
                $layout['datosGrafico'] = $this->datosTallaMasculino();
            }else{
                $layout['datosGrafico'] = $this->datosTallaFemenino();
            }
            Home::getInstance()->show('grafico.html.twig',$layout);
        }
    }
    
    public function datosTallaFemenino(){
        $talla['titulo'] = 'Gráfico de la evolución de la talla mujeres hasta 2 años.';
        $talla['YminorTickInterval'] = 0.5;
        $talla['XminorTickInterval'] = 0.5;
        $talla['tituloY'] = 'Peso(kg)';
        $talla['tituloX'] = 'Longitud (En cm.)';
        $talla['series'] = array(['name' => 'P3', 'data' => [[0,45],[1,45.5],[2,46],[3,46.5],[4,47],[5,47.5],[6,48],[7,48.5],[8,49],[9,49.5],[10,50],[11,50.5],[12,51],[13,51.5]]],['name' => 'P15', 'data' => [[0,46],[1,46.5],[2,47],[3,47.5],[4,48],[5,48.5],[6,49],[7,49.5],[8,50],[9,50.5],[10,51],[11,51.5],[12,52],[13,52.5]]]);
        return json_encode($talla);
    }
    
    public function datosTallaMasculino(){
        $talla['titulo'] = 'Gráfico de la evolución de la talla hombres hasta 2 años.';
        $talla['YminorTickInterval'] = 0.5;
        $talla['XminorTickInterval'] = 0.5;
        $talla['tituloY'] = 'Peso(kg)';
        $talla['tituloX'] = 'Longitud (En cm.)';
        $talla['series'] = array(['name' => 'P3', 'data' => [	[45, 2.1], [45.5, 2.1], [46, 2.2], [46.5, 2.3], [47, 2.4], [47.5, 2.4], [48, 2.5], [48.5, 2.6], [49, 2.7], [49.5, 2.7], [50, 2.8], [50.5, 2.9], [51, 3], [51.5, 3.1], [52, 3.2], [52.5, 3.3], [53, 3.4], [53.5, 3.5], [54, 3.6], [54.5, 3.8], [55, 3.9], [55.5, 4], [56, 4.1], [56.5, 4.3], [57, 4.4], [57.5, 4.5], [58, 4.6], [58.5, 4.8], [59, 4.9], [59.5, 5], [60, 5.1], [60.5, 5.3], [61, 5.4], [61.5, 5.5], [62, 5.6], [62.5, 5.7], [63, 5.8], [63.5, 5.9], [64, 6], [64.5, 6.1], [65, 6.3], [65.5, 6.4], [66, 6.5], [66.5, 6.6], [67, 6.7], [67.5, 6.8], [68, 6.9], [68.5, 7], [69, 7.1], [69.5, 7.1], [70, 7.2], [70.5, 7.3], [71, 7.4], [71.5, 7.5], [72, 7.6], [72.5, 7.7], [73, 7.8], [73.5, 7.9], [74, 8], [74.5, 8.1], [75, 8.2], [75.5, 8.2], [76, 8.3], [76.5, 8.4], [77, 8.5], [77.5, 8.6], [78, 8.7], [78.5, 8.7], [79, 8.8], [79.5, 8.9], [80, 9], [80.5, 9.1], [81, 9.1], [81.5, 9.2], [82, 9.3], [82.5, 9.4], [83, 9.5], [83.5, 9.6], [84, 9.7], [84.5, 9.8], [85, 9.9], [85.5, 10], [86, 10.1], [86.5, 10.2], [87, 10.3], [87.5, 10.4], [88, 10.6], [88.5, 10.7], [89, 10.8], [89.5, 10.9], [90, 11], [90.5, 11.1], [91, 11.2], [91.5, 11.3], [92, 11.4], [92.5, 11.5], [93, 11.6], [93.5, 11.7], [94, 11.8], [94.5, 11.9], [95, 12], [95.5, 12.1], [96, 12.2], [96.5, 12.3], [97, 12.4], [97.5, 12.5], [98, 12.6], [98.5, 12.7], [99, 12.8], [99.5, 12.9], [100, 13], [100.5, 13.2], [101, 13.3], [101.5, 13.4], [102, 13.5], [102.5, 13.6], [103, 13.8], [103.5, 13.9], [104, 14], [104.5, 14.1], [105, 14.2], [105.5, 14.4], [106, 14.5], [106.5, 14.6], [107, 14.8], [107.5, 14.9], [108, 15], [108.5, 15.2], [109, 15.3], [109.5, 15.4], [110, 15.6] ]],['name' => 'P15', 'data' => [ [45, 2.2], [45.5, 2.3], [46, 2.4], [46.5, 2.5], [47, 2.5], [47.5, 2.6], [48, 2.7], [48.5, 2.8], [49, 2.9], [49.5, 2.9], [50, 3], [50.5, 3.1], [51, 3.2], [51.5, 3.3], [52, 3.4], [52.5, 3.6], [53, 3.7], [53.5, 3.8], [54, 3.9], [54.5, 4], [55, 4.2], [55.5, 4.3], [56, 4.4], [56.5, 4.6], [57, 4.7], [57.5, 4.8], [58, 5], [58.5, 5.1], [59, 5.2], [59.5, 5.4], [60, 5.5], [60.5, 5.6], [61, 5.8], [61.5, 5.9], [62, 6], [62.5, 6.1], [63, 6.2], [63.5, 6.3], [64, 6.5], [64.5, 6.6], [65, 6.7], [65.5, 6.8], [66, 6.9], [66.5, 7], [67, 7.1], [67.5, 7.2], [68, 7.3], [68.5, 7.4], [69, 7.5], [69.5, 7.6], [70, 7.7], [70.5, 7.8], [71, 8], [71.5, 8.1], [72, 8.2], [72.5, 8.3], [73, 8.4], [73.5, 8.4], [74, 8.5], [74.5, 8.6], [75, 8.7], [75.5, 8.8], [76, 8.9], [76.5, 9], [77, 9.1], [77.5, 9.2], [78, 9.3], [78.5, 9.3], [79, 9.4], [79.5, 9.5], [80, 9.6], [80.5, 9.7], [81, 9.8], [81.5, 9.9], [82, 10], [82.5, 10.1], [83, 10.1], [83.5, 10.3], [84, 10.4], [84.5, 10.5], [85, 10.6], [85.5, 10.7], [86, 10.8], [86.5, 10.9], [87, 11], [87.5, 11.2], [88, 11.3], [88.5, 11.4], [89, 11.5], [89.5, 11.6], [90, 11.7], [90.5, 11.8], [91, 11.9], [91.5, 12], [92, 12.2], [92.5, 12.3], [93, 12.4], [93.5, 12.5], [94, 12.6], [94.5, 12.7], [95, 12.8], [95.5, 12.9], [96, 13], [96.5, 13.1], [97, 13.2], [97.5, 13.4], [98, 13.5], [98.5, 13.6], [99, 13.7], [99.5, 13.8], [100, 13.9], [100.5, 14.1], [101, 14.2], [101.5, 14.3], [102, 14.5], [102.5, 14.6], [103, 14.7], [103.5, 14.8], [104, 15], [104.5, 15.1], [105, 15.3], [105.5, 15.4], [106, 15.5], [106.5, 15.7], [107, 15.8], [107.5, 16], [108, 16.1], [108.5, 16.3], [109, 16.4], [109.5, 16.6], [110, 16.7] ]],['name' => 'P50', 'data' => [	[45, 2.4], [45.5, 2.5], [46, 2.6], [46.5, 2.7], [47, 2.8], [47.5, 2.9], [48, 2.9], [48.5, 3], [49, 3.1], [49.5, 3.2], [50, 3.3], [50.5, 3.4], [51, 3.5], [51.5, 3.6], [52, 3.8], [52.5, 3.9], [53, 4], [53.5, 4.1], [54, 4.3], [54.5, 4.4], [55, 4.5], [55.5, 4.7], [56, 4.8], [56.5, 5], [57, 5.1], [57.5, 5.3], [58, 5.4], [58.5, 5.6], [59, 5.7], [59.5, 5.9], [60, 6], [60.5, 6.1], [61, 6.3], [61.5, 6.4], [62, 6.5], [62.5, 6.7], [63, 6.8], [63.5, 6.9], [64, 7], [64.5, 7.1], [65, 7.3], [65.5, 7.4], [66, 7.5], [66.5, 7.6], [67, 7.7], [67.5, 7.9], [68, 8], [68.5, 8.1], [69, 8.2], [69.5, 8.3], [70, 8.4], [70.5, 8.5], [71, 8.6], [71.5, 8.8], [72, 8.9], [72.5, 9], [73, 9.1], [73.5, 9.2], [74, 9.3], [74.5, 9.4], [75, 9.5], [75.5, 9.6], [76, 9.7], [76.5, 9.8], [77, 9.9], [77.5, 10], [78, 10.1], [78.5, 10.2], [79, 10.3], [79.5, 10.4], [80, 10.4], [80.5, 10.5], [81, 10.6], [81.5, 10.7], [82, 10.8], [82.5, 10.9], [83, 11], [83.5, 11.2], [84, 11.3], [84.5, 11.4], [85, 11.5], [85.5, 11.6], [86, 11.7], [86.5, 11.9], [87, 12], [87.5, 12.1], [88, 12.2], [88.5, 12.4], [89, 12.5], [89.5, 12.6], [90, 12.7], [90.5, 12.8], [91, 13], [91.5, 13.1], [92, 13.2], [92.5, 13.3], [93, 13.4], [93.5, 13.5], [94, 13.7], [94.5, 13.8], [95, 13.9], [95.5, 14], [96, 14.1], [96.5, 14.3], [97, 14.4], [97.5, 14.5], [98, 14.6], [98.5, 14.8], [99, 14.9], [99.5, 15], [100, 15.2], [100.5, 15.3], [101, 15.4], [101.5, 15.6], [102, 15.7], [102.5, 15.9], [103, 16], [103.5, 16.2], [104, 16.3], [104.5, 16.5], [105, 16.6], [105.5, 16.8], [106, 16.9], [106.5, 17.1], [107, 17.3], [107.5, 17.4], [108, 17.6], [108.5, 17.8], [109, 17.9], [109.5, 18.1], [110, 18.3] ]],['name' => 'P85', 'data' => [	[45, 2.7], [45.5, 2.8], [46, 2.9], [46.5, 3], [47, 3.1], [47.5, 3.1], [48, 3.2], [48.5, 3.3], [49, 3.4], [49.5, 3.5], [50, 3.7], [50.5, 3.8], [51, 3.9], [51.5, 4], [52, 4.1], [52.5, 4.3], [53, 4.4], [53.5, 4.5], [54, 4.7], [54.5, 4.8], [55, 5], [55.5, 5.1], [56, 5.3], [56.5, 5.4], [57, 5.6], [57.5, 5.8], [58, 5.9], [58.5, 6.1], [59, 6.2], [59.5, 6.4], [60, 6.5], [60.5, 6.7], [61, 6.8], [61.5, 7], [62, 7.1], [62.5, 7.3], [63, 7.4], [63.5, 7.5], [64, 7.7], [64.5, 7.8], [65, 7.9], [65.5, 8.1], [66, 8.2], [66.5, 8.3], [67, 8.4], [67.5, 8.6], [68, 8.7], [68.5, 8.8], [69, 8.9], [69.5, 9.1], [70, 9.2], [70.5, 9.3], [71, 9.4], [71.5, 9.6], [72, 9.7], [72.5, 9.8], [73, 9.9], [73.5, 10], [74, 10.1], [74.5, 10.3], [75, 10.4], [75.5, 10.5], [76, 10.6], [76.5, 10.7], [77, 10.8], [77.5, 10.9], [78, 11], [78.5, 11.1], [79, 11.2], [79.5, 11.3], [80, 11.4], [80.5, 11.5], [81, 11.6], [81.5, 11.7], [82, 11.8], [82.5, 11.9], [83, 12], [83.5, 12.2], [84, 12.3], [84.5, 12.4], [85, 12.5], [85.5, 12.7], [86, 12.8], [86.5, 12.9], [87, 13.1], [87.5, 13.2], [88, 13.3], [88.5, 13.5], [89, 13.6], [89.5, 13.7], [90, 13.8], [90.5, 14], [91, 14.1], [91.5, 14.2], [92, 14.4], [92.5, 14.5], [93, 14.6], [93.5, 14.7], [94, 14.9], [94.5, 15], [95, 15.1], [95.5, 15.3],[96, 15.4], [96.5, 15.5], [97, 15.7], [97.5, 15.8], [98, 15.9], [98.5, 16.1], [99, 16.2], [99.5, 16.4], [100, 16.5], [100.5, 16.7], [101, 16.8], [101.5, 17], [102, 17.2], [102.5, 17.3], [103, 17.5], [103.5, 17.7],[104, 17.8], [104.5, 18], [105, 18.2], [105.5, 18.4], [106, 18.5], [106.5, 18.7], [107, 18.9], [107.5, 19.1], [108, 19.3], [108.5, 19.5], [109, 19.6], [109.5, 19.8], [110, 20] ]],['name' => 'P97', 'data' => [	[45, 2.9], [45.5, 3], [46, 3.1], [46.5, 3.2], [47, 3.3], [47.5, 3.4], [48, 3.5], [48.5, 3.6], [49, 3.7],[49.5, 3.8], [50, 4], [50.5, 4.1], [51, 4.2], [51.5, 4.3], [52, 4.5], [52.5, 4.6], [53, 4.7], [53.5, 4.9], [54, 5], [54.5, 5.2], [55, 5.4], [55.5, 5.5], [56, 5.7], [56.5, 5.9], [57, 6], [57.5, 6.2], [58, 6.4], [58.5, 6.5], [59, 6.7], [59.5, 6.9], [60, 7], [60.5, 7.2], [61, 7.4], [61.5, 7.5], [62, 7.7], [62.5, 7.8], [63, 8], [63.5, 8.1], [64, 8.2], [64.5, 8.4], [65, 8.5], [65.5, 8.7], [66, 8.8], [66.5, 8.9], [67, 9.1], [67.5, 9.2], [68, 9.3], [68.5, 9.5], [69, 9.6], [69.5, 9.7], [70, 9.9], [70.5, 10], [71, 10.1], [71.5, 10.3], [72, 10.4], [72.5, 10.5], [73, 10.7], [73.5, 10.8], [74, 10.9], [74.5, 11], [75, 11.2], [75.5, 11.3],[76, 11.4], [76.5, 11.5], [77, 11.6], [77.5, 11.7], [78, 11.8], [78.5, 12], [79, 12.1], [79.5, 12.2], [80, 12.3], [80.5, 12.4], [81, 12.5], [81.5, 12.6], [82, 12.7], [82.5, 12.8], [83, 13], [83.5, 13.1], [84, 13.2], [84.5, 13.3], [85, 13.5], [85.5, 13.6], [86, 13.7], [86.5, 13.9], [87, 14], [87.5, 14.2], [88, 14.3], [88.5, 14.4], [89, 14.6], [89.5, 14.7], [90, 14.9], [90.5, 15], [91, 15.1], [91.5, 15.3], [92, 15.4], [92.5, 15.5], [93, 15.7], [93.5, 15.8], [94, 16], [94.5, 16.1], [95, 16.2], [95.5, 16.4], [96, 16.5], [96.5, 16.7], [97, 16.8], [97.5, 17], [98, 17.1], [98.5, 17.3], [99, 17.4], [99.5, 17.6], [100, 17.8], [100.5, 17.9], [101, 18.1], [101.5, 18.3], [102, 18.5], [102.5, 18.6], [103, 18.8], [103.5, 19], [104, 19.2], [104.5, 19.4], [105, 19.6], [105.5, 19.8], [106, 20], [106.5, 20.2], [107, 20.4], [107.5, 20.6], [108, 20.8], [108.5, 21], [109, 21.2], [109.5, 21.4], [110, 21.6] ]]);
        return json_encode($talla);
    }
    
    public function verGraficoPercentiloCefalico($idPaciente,$sexo){
        if(UserController::getInstance()->sePuedeAccederASeccion('paciente_historia')){
            $layout = IndexController::getInstance()->layout();
            if($sexo == 'Masculino'){
                $layout['datosGrafico'] = $this->datosPercentiloMasculino();
            }else{
                $layout['datosGrafico'] = $this->datosPercentiloFemenino();
            }
            Home::getInstance()->show('grafico.html.twig',$layout);
        }
    }
    
    public function datosPercentiloMasculino(){
        $peso['titulo'] = 'Gráfico de la evolución del PPC hombres hasta 13 semanas.';
        $peso['YminorTickInterval'] = 0.1;
        $peso['XminorTickInterval'] = 0.5;
        $peso['tituloY'] = 'Circunferencia cefálica (En cm.)';
        $peso['tituloX'] = 'Edad(semanas)';
        $peso['series'] = array(['name' => 'P3', 'data' => [ [0, 32.1], [1, 32.9], [2, 33.7], [3, 34.3], [4, 34.9], [5, 35.4], [6, 35.9], [7, 36.3], [8, 36.7], [9, 37], [10, 37.4], [11, 37.7], [12, 38], [13, 38.3] ]],['name' => 'P15', 'data' => [ [0, 33.1], [1, 33.9], [2, 34.7], [3, 35.3], [4, 35.9], [5, 36.4], [6, 36.8], [7, 37.3],[8, 37.7], [9, 38], [10, 38.4], [11, 38.7], [12, 39], [13, 39.3] ]],['name' => 'P50', 'data' => [ [0, 34.5], [1, 35.2], [2, 35.9], [3, 36.5], [4, 37.1], [5, 37.6], [6, 38.1], [7, 38.5], [8, 38.9], [9, 39.2], [10, 39.6], [11, 39.9], [12, 40.2], [13, 40.5] ]],['name' => 'P85', 'data' => [ [0, 35.8], [1, 36.4], [2, 37.1], [3, 37.7], [4, 38.3], [5, 38.8], [6, 39.3], [7, 39.7], [8, 40.1], [9, 40.5], [10, 40.8], [11, 41.1], [12, 41.4], [13, 41.7] ]],['name' => 'P97', 'data' => [ [0, 36.9], [1, 37.5], [2, 38.1], [3, 38.7], [4, 39.3], [5, 39.8], [6, 40.3], [7, 40.7],[8, 41.1], [9, 41.4], [10, 41.8], [11, 42.1], [12, 42.4], [13, 42.7] ]]);
        return json_encode($peso);
    }
    
    public function datosPercentiloFemenino(){
        $peso['titulo'] = 'Gráfico de la evolución del PPC mujeres hasta 13 semanas.';
        $peso['YminorTickInterval'] = 0.1;
        $peso['XminorTickInterval'] = 0.5;
        $peso['tituloY'] = 'Circunferencia cefálica (En cm.)';
        $peso['tituloX'] = 'Edad(semanas)';
        $peso['series'] = array(['name' => 'P3', 'data' => [ [0, 31.7], [1, 32.4], [2, 33.1], [3, 33.7], [4, 34.2], [5, 34.6], [6, 35], [7, 35.4], [8, 35.7], [9, 36.1], [10, 36.4], [11, 36.7], [12, 36.9], [13, 37.2] ]],['name' => 'P15', 'data' => [ [0, 32.7], [1, 33.3], [2, 34], [3, 34.6], [4, 35.2], [5, 35.6], [6, 36], [7, 36.4],[8, 36.8], [9, 37.1], [10, 37.4], [11, 37.7], [12, 38], [13, 38.2] ]],['name' => 'P50', 'data' => [ [0, 33.9], [1, 34.6], [2, 35.2], [3, 35.8], [4, 36.4], [5, 36.8], [6, 37.3], [7, 37.7],[8, 38], [9, 38.4], [10, 38.7], [11, 39], [12, 39.3], [13, 39.5] ]],['name' => 'P85', 'data' => [ [0, 35.1], [1, 35.8], [2, 36.4], [3, 37], [4, 37.6], [5, 38.1], [6, 38.5], [7, 38.9],[8, 39.3], [9, 39.6], [10, 39.9], [11, 40.2], [12, 40.5], [13, 40.8] ]],['name' => 'P97', 'data' => [ [0, 36.1], [1, 36.7], [2, 37.4], [3, 38], [4, 38.6], [5, 39.1], [6, 39.5], [7, 39.9],[8, 40.3], [9, 40.6], [10, 41], [11, 41.3], [12, 41.6], [13, 41.9] ]]);
        return json_encode($peso);
    }
}