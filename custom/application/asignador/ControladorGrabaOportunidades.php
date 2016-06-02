<?php

function EnviaEmailOportunidad($usuarioId,$nombrePlantilla,$asunto,$opotunidadTareaId,$opotunidadTarea){

	global $current_user;
	 global $db, $sugar_config;
	$user= new User();
	$user->retrieve($usuarioId);
		
	// para el caso de que no se envia el asunto.
	if ($asunto == '')$asunto = $user->full_name;
	
	//extraigo el nombre del cliente 
	$nombreOportunidad =  explode("/",$asunto);
	
	// invocamos el template
	$template = new EmailTemplate();
	$template->retrieve_by_string_fields(array('name' => $nombrePlantilla ));	
	//Parse Subject If we used variable in subject
	$template->subject= str_replace('$contact_user_full_name', $asunto, $template->subject);
	//Parse Body HTML
	$template->body_html= str_replace('$contact_user_full_name', $user->full_name, $template->body_html);
	$template->body_html= str_replace('$fullname', $nombreOportunidad[1], $template->body_html);

	//enviamos el email
	$emailObj = new Email();
	$defaults = $emailObj->getSystemDefaultEmail();
	$mail = new SugarPHPMailer();
	$mail->setMailerForSystem();
	$mail->From = $defaults['email'];
	$mail->FromName = $user->full_name;
	$mail->ClearAllRecipients();
	$mail->ClearReplyTos();
	$mail->Subject=$template->subject;
	$mail->Body=$template->body_html;
	$mail->AltBody=$template->body_html;
	$mail->prepForOutbound();
	$emailTo=array($user->email1);
	foreach ($emailTo as &$value) {
		$mail->AddAddress($value);
	}
	
	if(@$mail->Send()){
		$email = new Email();
		$email->name = $asunto;
		$email->type='out';
		$email->status='sent';
		$email->intent='pick';
		$email->from_addr = $defaults['email'];
		$email->to_addrs= $user->full_name."<".$user->email1.">";
		$email->description_html=$template->body_html;
		$email->message_id=$template->id;
		$email->assigned_user_id=$current_user->id;
		$email->assigned_user_name=$current_user->user_name;
		$email->from_name=$current_user->full_name;
		$email->parent_name=$user->full_name;
		$email->date_sent=TimeDate::getInstance()->getNow(true)->modify("+0 days")->asDb();
		$email->parent_type=$opotunidadTarea;
		$email->parent_id=$opotunidadTareaId;
		$email->from_addr_name=$current_user->full_name;
		$email->save();
		return true ;
	}	
	return false;
}

function AsignacionWorkflow($oportunidades,$EjecutivoId,$EjecutivoNombre,$cant_oportunidades,$FechaAsignacion,$minutos,$EstadoTarea,$EstadoOportortunidadWorkflow){
	 global $current_user;
	 global $db, $sugar_config;
	// Este query es usado para buscar la cantidad de tareas
	$sql =	"SELECT  
				 t.id,
				 count(o.id) as cantidad
			FROM opportunities o
			JOIN tasks_opportunities_1_c rt ON rt.tasks_opportunities_1opportunities_idb = o.id and rt.deleted = 0 
			JOIN tasks t ON t.id = rt.tasks_opportunities_1tasks_ida AND t.status = 'Not Started'
			JOIN tasks_cstm td ON t.id = td.id_c AND td.tipo_tarea_c = 'WORKFLOW'
			WHERE o.assigned_user_id = '".$EjecutivoId."'
			GROUP BY t.id ";

	$resultado = $db->query($sql);
    $row = $db->fetchByAssoc($resultado);
	$tarea = $row['cantidad'];
	
	// Este query es usado para buscar la cantidad de oportunidades			  
	$sql_2 =	"SELECT count(o.id) AS cantidad
				 FROM   opportunities o
				 WHERE   o.assigned_user_id = '".$EjecutivoId."' AND o.sales_stage IN ('asignado', 'contactado', 'cotizacion', 'negociacion') AND 
						 o.opportunity_type = 'solicitud_web'";

	$resultado_2 = $db->query($sql_2);
	$row_2 = $db->fetchByAssoc($resultado_2);
	$oportunidad = $row_2['cantidad'];
	
	  
	if($tarea > 0){ //esta condicion se usa para validar de que el ejecutivo no tenga ya una asignacion
	   return '<script>alert("El Ejecutivo '.$EjecutivoNombre.' tiene Asignada Solicitudes..!!Por Favor Espere 30 min");</script>';		
	}elseif ($oportunidad > $cant_oportunidades ){ //esta condicion se usa para validar de que el ejecutivo no tenga mas de una cantidad de oportunidades asignadas.
	   return '<script>alert("El Ejecutivo '.$EjecutivoNombre.' tiene Asignada mas de '.$cant_oportunidades.' Solicitudes Sin Cerrar..!!Por Favor Debe Cerrar Algunas solicitudes pendientes...");</script>';
	}
	
	// se crea la tarea asinada al ejecutivo
	$tarea 		= new Task();
	$tarea->name='AsignaSolicitudes - '.TimeDate::getInstance()->getNow(true)->modify("-4 hours")->asDb();
	$tarea->assigned_user_id=$EjecutivoId;
	$tarea->created_by=$current_user->id;
	$tarea->modified_user_id=$current_user->id;
	$tarea->status=$EstadoTarea;
	$tarea->description='['.TimeDate::getInstance()->getNow(true)->modify("-4 hours")->asDb().'] Asigna Sollicitudes a Ejecutiva '.$EjecutivoNombre;
	$tarea->priority='Low';
	$tarea->date_start = $FechaAsignacion;
	$tarea->date_due = $minutos;
	$tarea->tipo_tarea_c = 'WORKFLOW';
	$tarea->save();
							
	// se asinada la oportunidad al ejecutivo	
	foreach($oportunidades as $solicitudes) {
		  $oportunidad = new Opportunity();
		  $oportunidad->retrieve($solicitudes);
		  $oportunidad->assigned_user_id = $EjecutivoId;
		  $oportunidad->sales_stage= $EstadoOportortunidadWorkflow;
		  $oportunidad->estado_wf_c ='asignado';
		  if ($EstadoTarea != 'Not Started'){ 
			$oportunidad->date_assigned_c = $FechaAsignacion;
		  }
		  $oportunidad->save();
		  $tarea->load_relationship('tasks_opportunities_1');
		  $tarea->tasks_opportunities_1->add($oportunidad->id);
		  $cantidad_oportunidad = $cantidad_oportunidad + 1;	
	}
	



	// se envia email al ejecutivo
	if ($EstadoTarea == 'Not Started'){			
		$respuestaEmail = EnviaEmailOportunidad($EjecutivoId,'SW_2_TiempoLimiteParaAceptarSW','',$tarea->id,'Task');
		if ($respuestaEmail){
			return '<script>alert("El Ejecutivo '.$EjecutivoNombre.' se le Asigno la Cantidad de '.$cantidad_oportunidad.' Solicitudes por Medio del Workflow..");</script>';				
		}else{
			return 'Error al enviar al email de notificacion, comuniquese con el administrador';
		}
	}else{

		$tarea2 = new Task();
		$tarea2->retrieve($tarea->id);
		$tarea2->cierre_workflow_c = 'aceptado';
		$tarea2->save();
		return '<script>alert("El Ejecutivo '.$EjecutivoNombre.' se le Asigno la Cantidad de '.$cantidad_oportunidad.' Solicitudes por Medio del Workflow..");</script>';	
	}
	
	return false;
}
     
function AsignacionDirecto($oportunidades,$EjecutivoId,$ejecutivoNombre,$FechaAsignacion,$EstadoOportortunidadDirecto){
					 global $current_user;
					global $db, $sugar_config;
					$user= new User();
					$user->retrieve($EjecutivoId);
					
					foreach($oportunidades as $solicitudes) {
						$oportunidad = new Opportunity();
						$oportunidad->retrieve($solicitudes);
						$oportunidad->date_assigned_c = $FechaAsignacion;
						$oportunidad->assigned_user_id = $user->id;
						$oportunidad->estado_wf_c ='reasignado';
						$oportunidad->sales_stage = ($user->sucursal_c == 'EXTERNOS') ? 'CerradoExterno' : $EstadoOportortunidadDirecto;
						$oportunidad->save();
						$cantidad_oportunidad = $cantidad_oportunidad + 1;
						if($user->sucursal_c != 'EXTERNOS'){
							//EmailNotificaCliente($oportunidad->id, $user->id);
							$respuestaEmail = EnviaEmailOportunidad($user->id,'email_asignado_cliente','Oportunidad de SugarCRM - '.$oportunidad->name,$oportunidad->id,'Opportunity');
							if (!$respuestaEmail){
								return 'Error al enviar al email de notificacion, comuniquese con el administrador';
							}
						}
					}
                       return '<script>alert("El Ejecutivo '.$user->full_name.' se le Asigno la Cantidad de '.$cantidad_oportunidad.' Solicitudes de Forma Directa..");</script>';		
}

function EmailNotificaCliente($oportunidades, $EjecutivoId){
			global $db, $sugar_config,$current_user;
				
		
				$query_2=" SELECT DISTINCT ls.leads_opportunities_1leads_ida AS id_contacto
							FROM   leads_opportunities_1_c ls
							WHERE  ls.leads_opportunities_1opportunities_idb = '".$oportunidades."'";
				$respt_2 = $db->Query($query_2);

				
			 while($value2 = $db->fetchByAssoc($respt_2)){
					$contacto =new Lead();
					$contacto->retrieve($value2['id_contacto']);
					
					$user= new User();
					$user->retrieve($EjecutivoId);
					
					// plnatilla de email
					$template = new EmailTemplate();
					$template->retrieve_by_string_fields(array('name' => 'SW_3_ClienteAsignado' ));	
					
					// Parse Body HTML
					$template->body_html= str_replace('$lead_name',$contacto->full_name, $template->body_html);
					$template->body_html= str_replace('$contact_user_full_name',$user->full_name, $template->body_html);
					$template->body_html= str_replace('$contact_user_phone_work',$user->phone_work, $template->body_html);
					$template->body_html= str_replace('$contact_user_email1',$user->email1, $template->body_html);

					// enviamos el email
					$emailObj = new Email();
					$defaults = $emailObj->getSystemDefaultEmail();
					$mail = new SugarPHPMailer();
					$mail->setMailerForSystem();
					$mail->From = $user->email1;
					$mail->FromName = $user->full_name;
					$mail->ClearAllRecipients();
					$mail->ClearReplyTos();
					$mail->Subject=$template->subject;
					$mail->Body=$template->body_html;
					$mail->AltBody=$template->body_html;
					$mail->prepForOutbound();
					$emailTo=array($contacto->email1);
					foreach ($emailTo as &$value) {
						$mail->AddAddress($value);
					}
					$mail->AddReplyTo($user->email1,$user->email1);
					// $mail->Send();
					if(@$mail->Send()){
						$email = new Email();
						$email->name = $template->subject;
						$email->type='out';
						$email->status='sent';
						$email->intent='pick';
						$email->from_addr = $defaults['email'];
						$email->to_addrs= $contacto->full_name."<".$contacto->email1.">";
						$email->description_html=$template->body_html;
						$email->assigned_user_id='1';
						$email->assigned_user_name='admin';
						$email->from_name=$current_user->full_name;
						$email->parent_name=$user->full_name;
						$email->date_sent=TimeDate::getInstance()->getNow(true)->modify("+0 days")->asDb();
						$email->parent_type='Opportunity';
						$email->parent_id=$oportunidades;
						$email->from_addr_name=$current_user->full_name;
						$email->save();
						return true ;
					}		
			}
			return false ;
}
?>