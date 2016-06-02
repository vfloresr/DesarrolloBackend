<?php

/*********************************************************************************
* This code was developed by:
* Audox Ingeniería Ltda.
* You can contact us at:
* Web: www.audox.cl
* Email: info@audox.cl
* Skype: audox.ingenieria
********************************************************************************/

$job_strings[] = 'proceso_2h';


function proceso_2h(){
	global $sugar_config, $db;
	//$min = 5;
	$min = 120;
	// $timeDate = new TimeDate();
	// $timeDateNow = $timeDate->getNow(true)->asDb();
	// $days_offset = 15;
//	$GLOBALS['log']->fatal("inicio proceso_2h...");
	$query = "select  o.id, 
						o.assigned_user_id
			from opportunities o
			join  opportunities_cstm do on do.id_c = o.id  
			where o.sales_stage = 'asignado'
			and CONVERT_TZ(now(),'America/Santiago','UTC') >= date_format(DATE_ADD(do.date_assigned_c,INTERVAL ".$min." MINUTE), '%Y-%m-%d %H:%i:%s') 
			and do.estado_wf_c = 'asignado'  
			and o.opportunity_type = 'solicitud_web'
			and deleted = 0";
			//$GLOBALS['log']->fatal("Query: ".$query);
			$res = $db->query($query, true, 'Error: ');
			
			$usuario = new User();
			while($row = $db->fetchByAssoc($res)){
					// $oportunidad = new Opportunity();
					// $oportunidad->retrieve($row['id']);
					// $GLOBALS['log']->fatal($oportunidad->id." ".$oportunidad->name." ".$oportunidad->estado_wf_c." ".$row['id']);
					// $oportunidad->estado_wf_c='notificacion_2h';
					//$oportunidad->save();
					$update= "update opportunities_cstm set estado_wf_c = 'notificacion_2h' where id_c = '".$row['id']."'";
					$resul = $db->Query($update);
					$usuario->retrieve($row['assigned_user_id']);
				//	$GLOBALS['log']->fatal($row['estado']);
					if ($row['assigned_user_id'] != $ante){
						$ante=$row['assigned_user_id'];
						$user= new User();
						$user->retrieve($ante);

						// plnatilla de email
						$template = new EmailTemplate();
						$template->retrieve_by_string_fields(array('name' => 'SW_6_Consultor_PrimeraAdvertencia' ));	
						// Parse Subject If we used variable in subject
						$template->subject= str_replace('$contact_user_full_name', $user->full_name, $template->subject);
						// Parse Body HTML
						$template->body_html= str_replace('$contact_user_full_name', $user->full_name, $template->body_html);

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
									$email->name = $template->subject;
									$email->type='out';
									$email->status='sent';
									$email->intent='pick';
									$email->from_addr = $defaults['email'];
									$email->to_addrs= $user->full_name."<".$user->email1.">";
									$email->description_html=$template->body_html;
									$email->assigned_user_id='1';
									$email->assigned_user_name='admin';
									$email->from_name=$user->full_name;
									$email->parent_name=$user->full_name;
									$email->date_sent=TimeDate::getInstance()->getNow(true)->modify("+0 days")->asDb();
									$email->parent_type='workflow';
									$email->parent_id='1';
									$email->from_addr_name=$user->full_name;
									$email->save();
						}		
					}
			}
			
			
	//$GLOBALS['log']->fatal("proceso_2h fin ".$query);
	return true;
}

?>