<?php  
 ini_set('error_reporting', E_ALL);
 ini_set('display_errors', True);
 global $current_user;
 global $db, $sugar_config;
 require_once('modules/EmailTemplates/EmailTemplate.php');
 include('custom/application/asignador/ControladorGrabaOportunidades.php');

//---------------------- CONSTANTES ------------------------
	//$cant_oportunidades = 50;
	$cant_oportunidades = 999999999999999999999999;
	$FechaAsignacion = TimeDate::getInstance()->getNow(true)->asDb();
	$minutos = TimeDate::getInstance()->getNow(true)->modify("+30 minutes")->asDb();

	// con aprobacion :Not Started / sin aprobacion: Completed
	$EstadoTarea = 'Completed';
	////////////////////////////////////////////////
	// con aprobacion : reservado / sin aprobacion: asignado
	$EstadoOportortunidadWorkflow = 'asignado';
	//////////////////////////////////////////////////////
	
	$EstadoOportortunidadDirecto = 'asignado';

 // --------------- RECEPCION DE VARIABLES---------------------
	$ejecutivo	  =	(isset( $_POST['asignado_a'] )) ?  explode("|",$_POST['asignado_a']):  '' ; // monbre del ejecujtivo|id del ejecutivo
	$ejecutivoId  = (isset($ejecutivo[1] )) ? $ejecutivo[1] :  '' ;
	$ejecutivoNombre   = (isset($ejecutivo[0] )) ? $ejecutivo[0] :  '' ;
	if(isset($_POST['id_oportunidad']))$_POST['solicitudes_asig'][] = $_POST['id_oportunidad'];
	$oportunidades =  $_POST['solicitudes_asig'];
	//tipos de asignacion (workflow,directo)
    $tipo     = (isset($_POST['tipo'] )) ? $_POST['tipo'] :  '' ;
	
	// ------------------- CONTROLADOR---------------------
	
	switch ($tipo) {
    case 'workflow':
		try{
			$resultado = AsignacionWorkflow($oportunidades,$ejecutivoId,$ejecutivoNombre,$cant_oportunidades,$FechaAsignacion,$minutos,$EstadoTarea,$EstadoOportortunidadWorkflow);
			echo $resultado;
		}catch(Exception $e){
			echo 'Error en asignar en el proceso workflow favor comunicarse con el administrador '.$e->getMessage().' '.$e->getLine();
		}
        break;
    case 'directo':
		try{
		   $resultado = AsignacionDirecto($oportunidades,$ejecutivoId,$ejecutivoNombre,$FechaAsignacion,$EstadoOportortunidadDirecto);
		   echo $resultado;
		}catch(Exception $e){
			echo 'Error en asignar en el proceso directo favor comunicarse con el administrador '.$e->getMessage().' '.$e->getLine();
		}
        break;
	}
	?>