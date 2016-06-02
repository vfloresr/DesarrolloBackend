<?php
		
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class ReasignacionOportunidades{
	static $already_ran = false;
	
function ReasignaOportunidad(&$bean, $event, $arguments){
  global $current_user,$db, $sugar_config;

	if(self::$already_ran == true) return;  // ***************** PARA QUE SOLO SE EJCUTE UNA VEZ
		self::$already_ran = true; 
		
	//---------------------- CONSTANTES ------------------------
		$FechaAsignacion_logic = TimeDate::getInstance()->getNow(true)->asDb();
		$minutosLogic = TimeDate::getInstance()->getNow(true)->modify("+30 minutes")->asDb();
		
		// con aprobacion :Not Started / sin aprobacion: Completed
		$EstadoTareaLogic = 'Completed';
		////////////////////////////////////////////////
		// con aprobacion : reservado / sin aprobacion: asignado
		$EstadoOportortunidadWorkflowLogic = 'asignado';
		//////////////////////////////////////////////////////
		
		$EstadoOportortunidadDirectoLogic = 'asignado';



	//-----------------------------------------------------------	
	if ($bean->fetched_row['assigned_user_id'] != '1' && $bean->created_by != 'bf61a216-d480-6fab-f04f-573a8b31de91' && $bean->assigned_user_id != '1' && $bean->opportunity_type == 'solicitud_web' && $bean->fetched_row['assigned_user_id'] != $bean->assigned_user_id){	
		$user= new User();
		$user->retrieve($bean->assigned_user_id);
		
		//*********************************************+Aqui puedes usar un SWITCH CASE

			switch ($user->sucursal_c) {
				case 'WORKFLOW':
					try{
						
						//actulizo la realcion de cualquier tarea que exista entre la oportunidad y la tarea.
						$update= "UPDATE tasks_opportunities_1_c
									SET    deleted = 1
									WHERE  tasks_opportunities_1opportunities_idb = '".$bean->id."'";
						$resul = $db->Query($update);
						

						$bean->log_asignacion_c = $user->user_name.'_REASIGNADO_WFN'.';'.$bean->log_asignacion_c;
						$bean->sales_stage = $EstadoOportortunidadDirectoLogic;
						$bean->date_assigned_c=$FechaAsignacion_logic;
						$bean->estado_wf_c='reasignado';
						$this->EmailNotificaCliente($bean->id, $user->id);
						$respuestaEmail = $this->EnviaEmailOportunidad($bean->assigned_user_id,'email_asignado_cliente','Oportunidad de SugarCRM - '.$bean->name,$bean->id,'Opportunity');
					}catch(Exception $e){
						echo 'Error en asignar en el proceso directo favor comunicarse con el administrador '.$e->getMessage().' '.$e->getLine();
					}
					break;
				default:
					try{
						 
						 	//actulizo la realcion de cualquier tarea que exista entre la oportunidad y la tarea.
						$update= "UPDATE tasks_opportunities_1_c
									SET    deleted = 1
									WHERE  tasks_opportunities_1opportunities_idb = '".$bean->id."'";
						$resul = $db->Query($update);
						

						$bean->log_asignacion_c =  $user->user_name.'_REASIGNADO_EXT'.';'.$bean->log_asignacion_c;
						$bean->sales_stage = 'CerradoExterno';
						$bean->date_assigned_c=$FechaAsignacion_logic;
					}catch(Exception $e){
						echo 'Error en asignar en el proceso directo favor comunicarse con el administrador '.$e->getMessage().' '.$e->getLine();
					}
			}
	}

}
	
	
function EnviaEmailOportunidad($usuarioId,$nombrePlantilla,$asunto,$opotunidadTareaId,$opotunidadTarea){ 
 global $current_user,$db, $sugar_config;
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

function EmailNotificaCliente($opotunidadTareaId, $usuarioId){
			global $db, $sugar_config,$current_user;
				

				$query_2=" SELECT DISTINCT ls.leads_opportunities_1leads_ida AS id_contacto
							FROM   leads_opportunities_1_c ls
							WHERE  ls.leads_opportunities_1opportunities_idb = '".$opotunidadTareaId."'";	
				$respt_2 = $db->Query($query_2);

				
			 while($value2 = $db->fetchByAssoc($respt_2)){
					$contacto =new Lead();
					$contacto->retrieve($value2['id_contacto']);
					
					$user= new User();
					$user->retrieve($usuarioId);
					
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
						$email->parent_id=$opotunidadTareaId;
						$email->from_addr_name=$current_user->full_name;
						$email->save();
						return true ;
					}		
			}
			return false ;
}
}
?>