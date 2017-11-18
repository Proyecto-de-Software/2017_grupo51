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
        session_start();
        var_dump($_SESSION);
        if($this->validarControlPac()){
            //pasa todas las var del post a un array y mandaselo al modelo(para usr es con $_SESSION)
        }else{
            IndexController::getInstance()->index();
        }
   }
}