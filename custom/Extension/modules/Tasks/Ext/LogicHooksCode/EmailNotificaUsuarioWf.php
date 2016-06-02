<?php

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class EmailNotificaUsuarioWf{
	
	function EmailNotificaUsuario(&$bean, $event, $arguments){
			global $db, $sugar_config,$current_user;

		if ($bean->status == 'Completed' && $bean->cierre_workflow_c == 'aceptado' && $bean->tipo_tarea_c == 'WORKFLOW' && $bean->date_modified < $bean->date_due && $bean->date_modified >= $bean->date_start ){
					$user= new User();
					$user->retrieve($bean->assigned_user_id);
				
					$query_2=" 	SELECT count( distinct rto.tasks_opportunities_1opportunities_idb) as can_oportunidad
						FROM tasks_opportunities_1_c rto
						JOIN  leads_opportunities_1_c ls ON rto.tasks_opportunities_1opportunities_idb = ls.leads_opportunities_1opportunities_idb
						WHERE tasks_opportunities_1tasks_ida = '".$bean->id."'";	
					$respt_2 = $db->Query($query_2);
					$cantidaOportunidad=$db->fetchByAssoc($respt_2);
					
					// plnatilla de email
					$template = new EmailTemplate();
					$template->retrieve_by_string_fields(array('name' => 'SW_5_Correo_Consultor_Con_Aviso' ));	
					// Parse Subject If we used variable in subject
					$template->subject= str_replace('$contact_user_full_name',$user->full_name, $template->subject);
					// Parse Body HTML
					$template->body_html= str_replace('$contact_user_full_name',$user->full_name, $template->body_html);
					$template->body_html= str_replace('$cantidad',$cantidaOportunidad['can_oportunidad'], $template->body_html);

					// enviamos el email
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
								$email->name = $template->subject;
								$email->type='out';
								$email->status='sent';
								$email->intent='pick';
								$email->from_addr = $defaults['email'];
								$email->to_addrs= $user->full_name."<".$user->email1.">";
								$email->description_html=$template->body_html;
								$email->assigned_user_id='1';
								$email->assigned_user_name='admin';
								$email->from_name=$current_user->full_name;
								$email->parent_name=$user->full_name;
								$email->date_sent=TimeDate::getInstance()->getNow(true)->modify("+0 days")->asDb();
								$email->parent_type='Task';
								$email->parent_id=$bean->id;
								$email->from_addr_name=$current_user->full_name;
								$email->save();
					}		
		}
		
	}

}
?>