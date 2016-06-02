<?php

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class EmailNotificaClienteWf{
	
	function EmailNotificaCliente(&$bean, $event, $arguments){
			global $db, $sugar_config,$current_user;
			
		if ($bean->status == 'Completed' && $bean->cierre_workflow_c == 'aceptado' && $bean->tipo_tarea_c == 'WORKFLOW' && $bean->date_modified < $bean->date_due && $bean->date_modified >= $bean->date_start){
				$query_2=" SELECT distinct rto.tasks_opportunities_1opportunities_idb as oportunidad, ls.leads_opportunities_1leads_ida as id_contacto 
							FROM tasks_opportunities_1_c rto
							JOIN  leads_opportunities_1_c ls ON rto.tasks_opportunities_1opportunities_idb = ls.leads_opportunities_1opportunities_idb AND ls.deleted = '0'
							WHERE rto.tasks_opportunities_1tasks_ida = '".$bean->id."'
							AND rto.deleted = '0'";	
				$respt_2 = $db->Query($query_2);

				//$GLOBALS['log']->fatal('entro '.$query_2);
			 while($value2 = $db->fetchByAssoc($respt_2)){
					$contacto =new Lead();
					$contacto->retrieve($value2['id_contacto']);
					
					$user= new User();
					$user->retrieve($bean->assigned_user_id);
					
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
						$email->parent_id=$value2['oportunidad'];
						$email->from_addr_name=$current_user->full_name;
						$email->save();
					}	
					
			}	
		}
	}

}
?>