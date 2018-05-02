//funcion para validar el Formulario de creacion de pacientes
function validarFormPac (){

	
	var peso = document.getElementById("PesoPac").value;
	var vacunas = document.getElementById("AllVacunas").value;
	var obsVacunas = document.getElementById("VacObs").value;
	var maduracion = document.getElementById("MaduraAcorde").value;
	var obsMaduracion = document.getElementById("MaduracionObs").value;
	var examenFisico = document.getElementById("ExamenFis").value;
	var obsExaman = document.getElementById("ExamenFisicoOBs").value;
	var PC = document.getElementById("PC").value;
	var PPC = document.getElementById("PPC").value;
	var talla = document.getElementById("TallaPac").value;
	var alimentacion = document.getElementById("AlimPac").value;
	var obsGrales = document.getElementById("AlimObs").value;

	if ( (peso == "") || (vacunas == "") || (obsVacunas == "") || (maduracion == "") || (obsMaduracion == "") || (obsExaman == "") || (examenFisico == "")) {
		alert ("Los campos con (*) son obligatorios");
		return false;
	}else if ( inNaN(peso) || isNaN(talla) || isNan(PC) || isNaN(PPC) ){
		alert ("Error !! Se esperaba un Numero");
		return false;
	}else{
            return true;
        }

}