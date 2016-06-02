<?php

/*********************************************************************************
* This code was developed by:
* Audox Ingeniería Ltda.
* You can contact us at:
* Web: www.audox.cl
* Email: info@audox.cl
* Skype: audox.ingenieria
********************************************************************************/

$job_strings[] = 'proceso_1h';


function proceso_1h(){
	global $sugar_config, $db;
	// $timeDate = new TimeDate();
	// $timeDateNow = $timeDate->getNow(true)->asDb();
	// $days_offset = 15;
	//$min = 2;
	$horas = 12;
	//$GLOBALS['log']->fatal("inicio proceso_1h...");
	//este proceso esta orientado a tres horas
	$query = "SELECT o.id, o.assigned_user_id
				FROM   opportunities o JOIN opportunities_cstm do ON do.id_c = o.id
				WHERE  o.sales_stage = 'asignado' AND o.opportunity_type = 'solicitud_web' 
				AND CONVERT_TZ(now(), 'America/Santiago', 'UTC') >= date_format(DATE_ADD(do.date_assigned_c, INTERVAL ".$horas." hour), '%Y-%m-%d %H:%i:%s')
				AND do.estado_wf_c = 'asignado' AND o.deleted = 0";
					
					// $query = "  	select  o.id, 
							// o.assigned_user_id
					// from opportunities o
					// join  opportunities_cstm do on do.id_c = o.id
					// where 
					// o.sales_stage = 'asignado'
					// and o.opportunity_type = 'solicitud_web'
					// and date_format(now(), '%Y-%m-%d %H:%i:%s') >= date_format(DATE_ADD(do.date_assigned_c,INTERVAL ".$min." MINUTE), '%Y-%m-%d %H:%i:%s') 
					// and do.estado_wf_c = 'notificacion_2h'
					// and o.deleted = 0 ";
			//$GLOBALS['log']->fatal("Query: ".$query);
			$res = $db->query($query, true, 'Error: ');
			$oportunidad = new Opportunity();
			$i=0;
			$total=0;
			$ultimo=0;
			while($rowy = $db->fetchByAssoc($res)){
				$total=$total+1;
			}
			//$GLOBALS['log']->fatal($total);
			$res_2 = $db->query($query, true, 'Error: ');
			while($row = $db->fetchByAssoc($res_2)){
					//$GLOBALS['log']->fatal("paso");
					$oportunidad->retrieve($row['id']);
					$oportunidad->assigned_user_id = '1';
					$oportunidad->date_assigned_c = null;
					$oportunidad->sales_stage = 'recepcionado';
					$oportunidad->estado_wf_c='quita_oportunidad';
					$oportunidad->priority_c= 'High';
					$oportunidad->save();
					if ($ultimo == 0){
						 $ante = $row['assigned_user_id'];
					}
					if ($ante == $row['assigned_user_id']){
						$i=$i+1;
					}
					$ultimo=$ultimo+1;
					if ($row['assigned_user_id'] != $ante || $total == $ultimo ){
							
							if ( $total == $ultimo){
								$ante=$row['assigned_user_id'];
							}
							$user= new User();
							$user->retrieve($ante);
							
							$super= new User();
							$super->retrieve($user->reports_to_id);
							
							// plnatilla de email
							$template = new EmailTemplate();
							$template->retrieve_by_string_fields(array('name' => 'SW_7_Consultor_QuitaOportunidad' ));	
							// Parse Subject If we used variable in subject
							$template->subject= str_replace('$contact_user_full_name', $user->full_name, $template->subject);
							// Parse Body HTML
							$template->body_html= str_replace('$contact_user_full_name', $user->full_name, $template->body_html);
							$template->body_html= str_replace('$cantidad_oportunidades', $i, $template->body_html);

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
									$email->parent_id='2';
									$email->from_addr_name=$user->full_name;
									$email->save();
								
								}		
								// plnatilla de email
						/*  $template = new EmailTemplate();
							$template->retrieve($template_id);
							$template->retrieve_by_string_fields(array('name' => 'SW_8_Supervisor_QuitaOportunidad' ));	
							// Parse Subject If we used variable in subject
							$template->subject= str_replace('$contact_user_full_name', $user->full_name, $template->subject);
							// Parse Body HTML
							$template->body_html= str_replace('$nombre_supervisor', $super->full_name, $template->body_html);
							$template->body_html= str_replace('$contact_user_full_name', $user->full_name, $template->body_html);
							$template->body_html= str_replace('$cantidad_oportunidades', $i, $template->body_html);

							//enviamos el email
							$emailObj = new Email();
							$defaults = $emailObj->getSystemDefaultEmail();
							$mail = new SugarPHPMailer();
							$mail->setMailerForSystem();
							$mail->From = $defaults['email'];
							$mail->FromName = $super->full_name;
							$mail->ClearAllRecipients();
							$mail->ClearReplyTos();
							$mail->Subject=$template->subject;
							$mail->Body=$template->body_html;
							$mail->AltBody=$template->body_html;
							$mail->prepForOutbound();
							$emailTo=array($super->email1);
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
									$email->to_addrs= $super->full_name."<".$super->email1.">";
									$email->description_html=$template->body_html;
									$email->assigned_user_id='1';
									$email->assigned_user_name='admin';
									$email->from_name=$super->full_name;
									$email->parent_name=$super->full_name;
									$email->date_sent=TimeDate::getInstance()->getNow(true)->modify("+0 days")->asDb();
									$email->parent_type='workflow';
									$email->parent_id='2';
									$email->from_addr_name=$super->full_name;
									$email->save();
								}*/
							$ante=$row['assigned_user_id'];
							$i=0;
							$i=$i+1;
					}
				//	$GLOBALS['log']->fatal($usuario->email1);
			}
					
			
			
	//$GLOBALS['log']->fatal("proceso_1h fin");
	return true;
}

?>