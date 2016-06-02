<?php

/*********************************************************************************
* This code was developed by:
* Audox Ingeniería Ltda.
* You can contact us at:
* Web: www.audox.cl
* Email: info@audox.cl
* Skype: audox.ingenieria
********************************************************************************/

$job_strings[] = 'prospectos';


function prospectos(){
	global $app_strings, $app_list_strings, $sugar_config, $timedate, $current_user,$db;

					
					
						 // $query2="  SELECT lea.first_name, lea.last_name, lea.do_not_call,
										   // lea.phone_home, lea.phone_fax, lea.primary_address_country, lea.alt_address_city,
										   // lea.alt_address_country, lea.birthdate, lea1.politicas_privacidad_c, lea1.recibir_notificaciones_c,
										   // lea1.ip_registro_c, ema1.email_address AS email1, ema1.id AS id_email
									// FROM   leads lea
										   // JOIN leads_cstm lea1 ON lea.id = lea1.id_c
										   // JOIN email_addr_bean_rel ema ON ema.bean_id = lea.id AND ema.bean_module = 'Leads' AND ema.deleted = 0
										   // JOIN email_addresses ema1 ON ema1.id = ema.email_address_id AND ema1.deleted = 0
									// WHERE  lea.deleted = 0";	
									
						 $query2=" SELECT lea.first_name, lea.last_name, lea.do_not_call,
										   lea.phone_home, lea.phone_fax, lea.primary_address_country, lea.alt_address_city,
										   lea.alt_address_country, lea.birthdate, lea1.politicas_privacidad_c, lea1.recibir_notificaciones_c,
										   lea1.ip_registro_c, ema1.email_address AS email1, ema1.id AS id_email
									FROM   leads lea
										   JOIN leads_cstm lea1 ON lea.id = lea1.id_c
										   JOIN email_addr_bean_rel ema ON ema.bean_id = lea.id AND ema.bean_module = 'Leads' AND ema.deleted = 0 and not exists (select em.id from email_addr_bean_rel em where em.email_address_id = ema.email_address_id and em.bean_module = 'Prospects')
										   JOIN email_addresses ema1 ON ema1.id = ema.email_address_id AND ema1.deleted = 0
									WHERE  lea.deleted = 0";	
						$respt2 = $db->Query($query2);
						
						$emailObj = new Email();
						$defaults = $emailObj->getSystemDefaultEmail();
						$mail = new SugarPHPMailer();
						$mail->setMailerForSystem();
						$mail->From = $defaults['email'];
						$mail->FromName = 'armando';
						$mail->ClearAllRecipients();
						$mail->ClearReplyTos();
						$mail->Subject='inicio de proceso v2';
						$mail->Body='psi';
						$mail->AltBody='psi';
						$mail->prepForOutbound();
						$emailTo=array('aytriago@cocha.com');
						foreach ($emailTo as &$value) {
							$mail->AddAddress($value);
						}
						@$mail->Send();
						
						while ($fila2 = $db->fetchByAssoc($respt2)) {
							$prospecto = new Prospect();
							foreach ($fila2 as $key => $value){
								if($fila2[$key] != '' && $key != 'bean_id' && $key != 'id' && $key != 'id_c'){
									$query3="  SELECT emap.bean_id AS id
												FROM   email_addr_bean_rel emap
												WHERE  emap.email_address_id = '".$fila2['id_email']."' 
												AND emap.deleted = 0 
												AND emap.bean_module = 'Prospects' 
												limit 1";	
									$respt3 = $db->Query($query3);
									if($rowa = $db->fetchByAssoc($respt3)){
										$prospecto->retrieve($rowa['id']);
									}
									$prospecto->$key = $value;
								}
							}
							$prospecto->save(); 
						}
						
						$emailObj = new Email();
						$defaults = $emailObj->getSystemDefaultEmail();
						$mail = new SugarPHPMailer();
						$mail->setMailerForSystem();
						$mail->From = $defaults['email'];
						$mail->FromName = 'armando';
						$mail->ClearAllRecipients();
						$mail->ClearReplyTos();
						$mail->Subject='fin de proceso v2';
						$mail->Body='psi';
						$mail->AltBody='psi';
						$mail->prepForOutbound();
						$emailTo=array('aytriago@cocha.com');
						foreach ($emailTo as &$value) {
							$mail->AddAddress($value);
						}
						@$mail->Send();
	return true;
}

?>