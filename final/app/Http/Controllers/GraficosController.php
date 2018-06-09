<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Configuracion;
use App\Models\Control;
use Illuminate\Http\Request;

class GraficosController extends Controller
{
    public function curveGraphic($tipo_grafico, $id_paciente){
        //Proporciona los datos necesarios para un tipo de grafico especifico con el paciente pasado por parametro
        $paciente = Paciente::find($id_paciente);
        $fechaNacimiento = $paciente->fecha_nacimiento;
        $sexo = $paciente->genero;
        $nuevafecha = date('Y-m-d', strtotime("$fechaNacimiento + 98 day"));
        $parametros = [
            'id' => $id_paciente,
            'fechas' => [$fechaNacimiento,$nuevafecha],
        ];
        $controles = $this->obtenerControles($tipo_grafico,$parametros);
        if(count($controles) > 0){
            $arregloGrafico = $this->obtenerDatosParaGraficar($fechaNacimiento, $controles, count($controles),$tipo_grafico);
            $datosGraficaPaciente = ['name' => 'Paciente', 'data' => $arregloGrafico];
        }else{
            $datosGraficaPaciente = ['name' => 'Sin registro del paciente', 'data' => []];
        }
        if($sexo == 'Masculino'){
            $datosGrafico = $this->obtenerDatosGeneralesMasculinos($tipo_grafico,$datosGraficaPaciente);
        }else{
            $datosGrafico = $this->obtenerDatosGeneralesFemeninos($tipo_grafico,$datosGraficaPaciente);
        }
        $config = Configuracion::all();
        return view('graficoDatosDemograficos')->with(['config' => $config, 'datosGrafico' => $datosGrafico]);
    }
    
    private function obtenerControles($tipo_grafico,$parametros){
        //Retorna los datos correspondientes de los controles del paciente
        switch ($tipo_grafico) {
            case 'peso':
                return Control::Peso($parametros)->orderBy('fecha','ASC')->get()->all();
            case 'talla':
                return Control::Talla($parametros)->orderBy('fecha','ASC')->get()->all();
            default :
                return Control::PercentiloCefalico($parametros)->orderBy('fecha','ASC')->get()->all();
        }
    }
    
    private function obtenerDatosGeneralesMasculinos($tipo_grafico,$parametros){
        //Retorna los datos generales de los hombres para la comparativa de lineas en el grafico.
        switch ($tipo_grafico) {
            case 'peso':
                return $this->datosPesoMasculino($parametros);
            case 'talla':
                return $this->datosTallaMasculino($parametros);
            default :
                return $this->datosPercentiloMasculino($parametros);
        }
    }
    
    private function obtenerDatosGeneralesFemeninos($tipo_grafico,$parametros){
        //Retorna los datos generales de las mujeres para la comparativa de lineas en el grafico.
        switch ($tipo_grafico) {
            case 'peso':
                return $this->datosPesoFemenino($parametros);
            case 'talla':
                return $this->datosTallaFemenino($parametros);
            default :
                return $this->datosPercentiloFemenino($parametros);
        }
    }
    
    private function datosPercentiloMasculino($datosPaciente){
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
    
    private function datosPercentiloFemenino($datosPaciente){
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
    
    private function datosTallaFemenino($datosPaciente){
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
    
    private function datosTallaMasculino($datosPaciente){
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
    
    private function obtenerDatosParaGraficar($fechaNacimiento,$controles,$cantControles,$datoArreglo){
        //Retorna un arreglo con los datos del paciente a graficar
        $pos = 0;
        $arregloGrafico = [];
        $fechaAnt = $fechaNacimiento;
        $fechaSig = date('Y-m-d', strtotime("$fechaNacimiento + 7 day"));
        for($i=0; $i<=13; $i++){
            if(($controles[$pos]->fecha >= $fechaAnt) && ($controles[$pos]->fecha <= $fechaSig) ){
                $arregloAux = [$i,  floatval($controles[$pos]->$datoArreglo)];
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
    
    public function showDemograficGraphicList(){
        $config = Configuracion::all();
        return view('listaGraficoDemograficosGeneral')->with(['config' => $config]);
    }
    
    public function showDemograficGraphic($tipo_grafico){
        switch ($tipo_grafico) {
            case 'mascotas':
                $datosPacientes = Paciente::Demograficos('mascota');
                return $this->mostrarGraficoTortasPoseeNoPosee($datosPacientes,'mascota');
            case 'electricidad':
                $datosPacientes = Paciente::Demograficos('electricidad');
                return $this->mostrarGraficoTortasPoseeNoPosee($datosPacientes,'electricidad');
            case 'heladera':
                $datosPacientes = Paciente::Demograficos('heladera');
                return $this->mostrarGraficoTortasPoseeNoPosee($datosPacientes,'heladera');
            case 'agua':
                $agua = json_decode(file_get_contents("https://api-referencias.proyecto2017.linti.unlp.edu.ar/tipo-agua"));
                $datosPacientes = Paciente::Demograficos('tipo_agua');
                return $this->graficoMultiplesDatos($agua,$datosPacientes,'Tipo agua','tipo_agua');
            case 'vivienda':
                $viviendas = json_decode(file_get_contents("https://api-referencias.proyecto2017.linti.unlp.edu.ar/tipo-vivienda"));
                $datosPacientes = Paciente::Demograficos('tipo_vivienda');
                return $this->graficoMultiplesDatos($viviendas,$datosPacientes,'Tipo vivienda','tipo_vivienda');
            default:
                $calefacciones = json_decode(file_get_contents("https://api-referencias.proyecto2017.linti.unlp.edu.ar/tipo-calefaccion"));
                $datosPacientes = Paciente::Demograficos('tipo_calefaccion');
                return $this->graficoMultiplesDatos($calefacciones,$datosPacientes,'Tipo calefaccion','tipo_calefaccion');
        }
    }
    
    public function mostrarGraficoTortasPoseeNoPosee($datos,$atributo){
        //Muestra un grafico de tortas posee/no posee con los datos recibidos
        $cantidadDatos = count($datos);
        $datosGrafico = $this->armaGraficoPoseeNoPosee($datos,$cantidadDatos,$atributo);
        $config = Configuracion::all();
        return view('graficoDatosDemograficosTortas')->with(['config' => $config, 'datosGrafico' => $datosGrafico]);
    }


    public function armaGraficoPoseeNoPosee($datos,$cantidad,$atributo){
        //Arma un grafico de tortas posee/no posee con los datos recibidos para la funcion que grafica
        $posee = 0;
        foreach ($datos as $dato) {
            if($dato->$atributo){
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
    
    public function graficoMultiplesDatos($arregloDatosApi,$arregloDatosPaciente,$titulo,$atributo){
        //Calcula los datos necesarios para realizar un grafico de tortas con varios datos
        foreach ($arregloDatosApi as $dato) {
            $cantPacientesPoseenDato[$dato->nombre] = 0;
        }
        foreach ($arregloDatosPaciente as $a) {
            $cantPacientesPoseenDato[$a->$atributo]++;
        }
        $datosGrafico = $this->armarGraficoMultiplesDatos($cantPacientesPoseenDato, count($arregloDatosPaciente),$titulo);
        $config = Configuracion::all();
        return view('graficoDatosDemograficosTortas')->with(['config' => $config, 'datosGrafico' => $datosGrafico]);
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
}
