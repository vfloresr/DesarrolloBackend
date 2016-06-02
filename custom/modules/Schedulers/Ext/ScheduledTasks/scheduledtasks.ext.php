<?php 
 //WARNING: The contents of this file are auto-generated



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



/**
 *
 * @package Advanced OpenPortal
 * @copyright SalesAgility Ltd http://www.salesagility.com
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU AFFERO GENERAL PUBLIC LICENSE
 * along with this program; if not, see http://www.gnu.org/licenses
 * or write to the Free Software Foundation,Inc., 51 Franklin Street,
 * Fifth Floor, Boston, MA 02110-1301  USA
 *
 * @author Salesagility Ltd <support@salesagility.com>
 */

$job_strings[] = 'pollMonitoredInboxesAOP';

function pollMonitoredInboxesAOP() {
    require_once 'custom/modules/InboundEmail/AOPInboundEmail.php';
    $_bck_up = array('team_id' => $GLOBALS['current_user']->team_id, 'team_set_id' => $GLOBALS['current_user']->team_set_id);
    $GLOBALS['log']->info('----->Scheduler fired job of type pollMonitoredInboxesAOP()');
    global $dictionary;
    global $app_strings;
    global $sugar_config;

    require_once('modules/Configurator/Configurator.php');
    require_once('modules/Emails/EmailUI.php');

    $ie = new AOPInboundEmail();
    $emailUI = new EmailUI();
    $r = $ie->db->query('SELECT id, name FROM inbound_email WHERE is_personal = 0 AND deleted=0 AND status=\'Active\' AND mailbox_type != \'bounce\'');
    $GLOBALS['log']->debug('Just got Result from get all Inbounds of Inbound Emails');

    while($a = $ie->db->fetchByAssoc($r)) {
        $GLOBALS['log']->debug('In while loop of Inbound Emails');
        $ieX = new AOPInboundEmail();
        $ieX->retrieve($a['id']);
        $GLOBALS['current_user']->team_id = $ieX->team_id;
        $GLOBALS['current_user']->team_set_id = $ieX->team_set_id;
        $mailboxes = $ieX->mailboxarray;
        foreach($mailboxes as $mbox) {
            $ieX->mailbox = $mbox;
            $newMsgs = array();
            $msgNoToUIDL = array();
            $connectToMailServer = false;
            if ($ieX->isPop3Protocol()) {
                $msgNoToUIDL = $ieX->getPop3NewMessagesToDownloadForCron();
                // get all the keys which are msgnos;
                $newMsgs = array_keys($msgNoToUIDL);
            }
            if($ieX->connectMailserver() == 'true') {
                $connectToMailServer = true;
            } // if

            $GLOBALS['log']->debug('Trying to connect to mailserver for [ '.$a['name'].' ]');
            if($connectToMailServer) {
                $GLOBALS['log']->debug('Connected to mailserver');
                if (!$ieX->isPop3Protocol()) {
                    $newMsgs = $ieX->getNewMessageIds();
                }
                if(is_array($newMsgs)) {
                    $current = 1;
                    $total = count($newMsgs);
                    require_once("include/SugarFolders/SugarFolders.php");
                    $sugarFolder = new SugarFolder();
                    $groupFolderId = $ieX->groupfolder_id;
                    $isGroupFolderExists = false;
                    $users = array();
                    if ($groupFolderId != null && $groupFolderId != "") {
                        $sugarFolder->retrieve($groupFolderId);
                        $isGroupFolderExists = true;
                    } // if
                    $messagesToDelete = array();
                    if ($ieX->isMailBoxTypeCreateCase()) {
                        require_once 'modules/AOP_Case_Updates/AOPAssignManager.php';
                        $assignManager = new AOPAssignManager($ieX);
                    }
                    foreach($newMsgs as $k => $msgNo) {
                        $uid = $msgNo;
                        if ($ieX->isPop3Protocol()) {
                            $uid = $msgNoToUIDL[$msgNo];
                        } else {
                            $uid = imap_uid($ieX->conn, $msgNo);
                        } // else
                        if ($isGroupFolderExists) {
                            if ($ieX->importOneEmail($msgNo, $uid)) {
                                // add to folder
                                $sugarFolder->addBean($ieX->email);
                                if ($ieX->isPop3Protocol()) {
                                    $messagesToDelete[] = $msgNo;
                                } else {
                                    $messagesToDelete[] = $uid;
                                }
                                if ($ieX->isMailBoxTypeCreateCase()) {
                                    $userId = $assignManager->getNextAssignedUser();
                                    $GLOBALS['log']->debug('userId [ '.$userId.' ]');
                                    $ieX->handleCreateCase($ieX->email, $userId);
                                } // if
                            } // if
                        } else {
                            if($ieX->isAutoImport()) {
                                $ieX->importOneEmail($msgNo, $uid);
                            } else {
                                /*If the group folder doesn't exist then download only those messages
                                 which has caseid in message*/

                                $ieX->getMessagesInEmailCache($msgNo, $uid);
                                $email = new Email();
                                $header = imap_headerinfo($ieX->conn, $msgNo);
                                $email->name = $ieX->handleMimeHeaderDecode($header->subject);
                                $email->from_addr = $ieX->convertImapToSugarEmailAddress($header->from);
                                $email->reply_to_email  = $ieX->convertImapToSugarEmailAddress($header->reply_to);
                                if(!empty($email->reply_to_email)) {
                                    $contactAddr = $email->reply_to_email;
                                } else {
                                    $contactAddr = $email->from_addr;
                                }
                                $mailBoxType = $ieX->mailbox_type;
                                $ieX->handleAutoresponse($email, $contactAddr);
                            } // else
                        } // else
                        $GLOBALS['log']->debug('***** On message [ '.$current.' of '.$total.' ] *****');
                        $current++;
                    } // foreach
                    // update Inbound Account with last robin

                } // if
                if ($isGroupFolderExists)	 {
                    $leaveMessagesOnMailServer = $ieX->get_stored_options("leaveMessagesOnMailServer", 0);
                    if (!$leaveMessagesOnMailServer) {
                        if ($ieX->isPop3Protocol()) {
                            $ieX->deleteMessageOnMailServerForPop3(implode(",", $messagesToDelete));
                        } else {
                            $ieX->deleteMessageOnMailServer(implode($app_strings['LBL_EMAIL_DELIMITER'], $messagesToDelete));
                        }
                    }
                }
            } else {
                $GLOBALS['log']->fatal("SCHEDULERS: could not get an IMAP connection resource for ID [ {$a['id']} ]. Skipping mailbox [ {$a['name']} ].");
                // cn: bug 9171 - continue while
            } // else
        } // foreach
        imap_expunge($ieX->conn);
        imap_close($ieX->conn, CL_EXPUNGE);
    } // while
    $GLOBALS['current_user']->team_id = $_bck_up['team_id'];
    $GLOBALS['current_user']->team_set_id = $_bck_up['team_set_id'];
    return true;
}



/*********************************************************************************
* This code was developed by:
* Audox Ingeniería Ltda.
* You can contact us at:
* Web: www.audox.cl
* Email: info@audox.cl
* Skype: audox.ingenieria
********************************************************************************/

$job_strings[] = 'proceso_4d';


function proceso_4d(){
	global $sugar_config, $db;
	// $timeDate = new TimeDate();
	// $timeDateNow = $timeDate->getNow(true)->asDb();
	// $days_offset = 15;
	//$min = 20;
	$min = 10080;
	//$GLOBALS['log']->fatal("inicio proceso_4");
	$query = " 	select * 
				from
				(select case WHEN date_format(now(), '%Y-%m-%d %H:%i:%s') >= date_format(DATE_ADD(do.date_assigned_c,INTERVAL ".$min." MINUTE), '%Y-%m-%d %H:%i:%s') and o.sales_stage not in ('CerradoGanado','CerradoPerdido','CerradoExterno','asignado') 
						THEN 'notificar' 
						ELSE 'no' 
						END
						AS reporte,
						o.id,
						o.assigned_user_id
				 from opportunities o
				 join opportunities_cstm do on o.id = do.id_c 
				 where  o.opportunity_type = 'solicitud_web'
				 and  do.estado_wf_c = 'cambio_estado') n 
				 where reporte = 'notificar'
				 order by n.assigned_user_id DESC";
			//$GLOBALS['log']->fatal("Query: ".$query);
			
			
	$res = $db->query($query, true, 'Error: ');
	$oportunidad = new Opportunity();
	$i=0;
	$total=0;
	$ultimo=0;
	while($rowy = $db->fetchByAssoc($res)){
				$total=$total+1;
	}
	//$GLOBALS['log']->fatal($query);
	$res_2 = $db->query($query, true, 'Error: ');
	while($row = $db->fetchByAssoc($res_2)){
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
			$template->retrieve_by_string_fields(array('name' => 'SW_9_ConsultorDebeCerrarOportunidad' ));	
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
				$email->parent_id='3';
				$email->from_addr_name=$user->full_name;
				$email->save();
								
			}		

				// plnatilla de email
			$template = new EmailTemplate();
			$template->retrieve_by_string_fields(array('name' => 'SW_10_Supervisor_CerrarOportunidad' ));	
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
				$email->parent_id='3';
				$email->from_addr_name=$super->full_name;
				$email->save();
			}			
			$ante=$row['assigned_user_id'];
			$i=0;
			$i=$i+1;
		}
		$oportunidad->retrieve($row['id']);
		$oportunidad->opportunity_type='solicitud_web';
		$oportunidad->save();
	}
	//$GLOBALS['log']->fatal("proceso_4 fin");
	return true;
}



 /**
 * 
 * 
 * @package 
 * @copyright SalesAgility Ltd http://www.salesagility.com
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU AFFERO GENERAL PUBLIC LICENSE
 * along with this program; if not, see http://www.gnu.org/licenses
 * or write to the Free Software Foundation,Inc., 51 Franklin Street,
 * Fifth Floor, Boston, MA 02110-1301  USA
 *
 * @author Salesagility Ltd <support@salesagility.com>
 */
$job_strings[] = 'aorRunScheduledReports';

function aorRunScheduledReports(){
    require_once 'include/SugarQueue/SugarJobQueue.php';
    $date = new DateTime();//Ensure we check all schedules at the same instant
    foreach(BeanFactory::getBean('AOR_Scheduled_Reports')->get_full_list() as $scheduledReport){

        if($scheduledReport->status == 'active' && $scheduledReport->shouldRun($date)){
            if(empty($scheduledReport->aor_report_id)){
                continue;
            }
            $job = new SchedulersJob();
            $job->name = "Scheduled report - {$scheduledReport->name} on {$date->format('c')}";
            $job->data = $scheduledReport->id;
            $job->target = "class::AORScheduledReportJob";
            $job->assigned_user_id = 1;
            $jq = new SugarJobQueue();
            $jq->submitJob($job);
        }
    }
}



class AORScheduledReportJob implements RunnableSchedulerJob
{
    public function setJob(SchedulersJob $job)
    {
        $this->job = $job;
    }
    public function run($data)
    {
        global $sugar_config, $timedate;

        $bean = BeanFactory::getBean('AOR_Scheduled_Reports',$data);
        $report = $bean->get_linked_beans('aor_report','AOR_Reports');
        if($report){
            $report = $report[0];
        }else{
            return false;
        }
        $html = "<h1>{$report->name}</h1>".$report->build_group_report();
        $html .= <<<EOF
        <style>
        h1{
            color: black;
        }
        .list
        {
            font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;font-size: 12px;
            background: #fff;margin: 45px;width: 480px;border-collapse: collapse;text-align: left;
        }
        .list th
        {
            font-size: 14px;
            font-weight: normal;
            color: black;
            padding: 10px 8px;
            border-bottom: 2px solid black};
        }
        .list td
        {
            padding: 9px 8px 0px 8px;
        }
        </style>
EOF;
        $emailObj = new Email();
        $defaults = $emailObj->getSystemDefaultEmail();
        $mail = new SugarPHPMailer();

        /*$result = $report->db->query($report->build_report_query());
        $reportData = array();
        while($row = $report->db->fetchByAssoc($result, false))
        {
            $reportData[] = $row;
        }
        $fields = $report->getReportFields();
        foreach($report->get_linked_beans('aor_charts','AOR_Charts') as $chart){
            $image = $chart->buildChartImage($reportData,$fields,false);
            $mail->AddStringEmbeddedImage($image,$chart->id,$chart->name.".png",'base64','image/png');
            $html .= "<img src='cid:{$chart->id}'>";
        }*/

        $mail->setMailerForSystem();
        $mail->IsHTML(true);
        $mail->From = $defaults['email'];
        $mail->FromName = $defaults['name'];
        $mail->Subject=from_html($bean->name);
        $mail->Body=$html;
        $mail->prepForOutbound();
        $success = true;
        $emails = $bean->get_email_recipients();
        foreach($emails as $email_address) {
            $mail->ClearAddresses();
            $mail->AddAddress($email_address);
            $success = $mail->Send() && $success;
        }
        $bean->last_run = $timedate->getNow()->asDb(false);
        $bean->save();
        return true;
    }
}

/**
 * Advanced OpenWorkflow, Automating SugarCRM.
 * @package Advanced OpenWorkflow for SugarCRM
 * @copyright SalesAgility Ltd http://www.salesagility.com
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU AFFERO GENERAL PUBLIC LICENSE
 * along with this program; if not, see http://www.gnu.org/licenses
 * or write to the Free Software Foundation,Inc., 51 Franklin Street,
 * Fifth Floor, Boston, MA 02110-1301  USA
 *
 * @author SalesAgility <info@salesagility.com>
 */
 
$job_strings['aow']='processAOW_Workflow';

function processAOW_Workflow() {
    require_once('modules/AOW_WorkFlow/AOW_WorkFlow.php');
    $workflow = new AOW_WorkFlow();
    return $workflow->run_flows();
}



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




/*********************************************************************************
* This code was developed by:
* Audox Ingeniería Ltda.
* You can contact us at:
* Web: www.audox.cl
* Email: info@audox.cl
* Skype: audox.ingenieria
********************************************************************************/

$job_strings[] = 'proceso_10';


function proceso_10(){
	global $sugar_config, $db;
	$min = -150;
	// $timeDate = new TimeDate();
	// $timeDateNow = $timeDate->getNow(true)->asDb();
	// $days_offset = 15;
	//$GLOBALS['log']->fatal("inicio proceso_10...");
	$query = "	select 	t.id,
						t.description,
						t.assigned_user_id,
						t.date_due,
						t.date_start
				from tasks t
				join tasks_cstm td on t.id = td.id_c and td.tipo_tarea_c = 'WORKFLOW'
				where t.status = 'Not Started'
				and date_format(DATE_ADD(t.date_entered,INTERVAL ".$min." MINUTE), '%Y-%m-%d %H:%i:%s') <= now()
				and !deleted";
		//	$GLOBALS['log']->fatal("Query: ".$query);
	$res = $db->query($query, true, 'Error: ');
	$oportunidad = new Opportunity();
	$tarea = new Task();
	while($row = $db->fetchByAssoc($res)){
				
				$tarea->retrieve($row['id']);
				$tarea->status='Completed';
				$tarea->date_due=$row['date_due'];
				$tarea->date_start=$row['date_start'];
				$tarea->cierre_workflow_c= 'caducado';
				$tarea->description=$row['description'].' / ['.TimeDate::getInstance()->getNow(true)->asDb().'] ACTUALIZADO POR SCHEDULERS PROCESO 30 MINUTOS';
				$tarea->save();
				
				 $query_2="SELECT tasks_opportunities_1opportunities_idb as oportunidad
							FROM tasks_opportunities_1_c 
							where tasks_opportunities_1tasks_ida = '".$row['id']."'";	
					$respt_2 = $db->Query($query_2);
			while($value2 = $db->fetchByAssoc($respt_2)){
				$oportunidad->retrieve($value2['oportunidad']);
				$oportunidad->assigned_user_id = '1';
				$oportunidad->sales_stage = 'recepcionado';
				$oportunidad->priority_c= 'High';
				$oportunidad->save();
				//$GLOBALS['log']->fatal($value2['oportunidad']);
			}
								// $user= new User();
								// $user->retrieve($tarea->assigned_user_id);
								
								//plnatilla de email
								//$template_id = '5e308aba-1771-8c38-b3b4-5642028180be';
								
								// $template = new EmailTemplate();
								// $template->retrieve($template_id);
								// $template->retrieve_by_string_fields(array('name' => 'SW_2_TiempoLimiteParaAceptarSW' ));	
								// Parse Subject If we used variable in subject
								// $template->subject= str_replace('$contact_user_full_name', $user->full_name, $template->subject);
								// Parse Body HTML
								// $template->body_html= str_replace('$contact_user_full_name', $user->full_name, $template->body_html);
								
								// $emailsTo = array();
								// $emailSubject = $template->subject;
								// $emailBody = $template->body_html;
								// $emailsTo[] = $user->email1;
								// SendEmail($emailsTo, $emailSubject, $emailBody);
				
	}
	//$GLOBALS['log']->fatal("fin proceso_10");
	return true;
}




/*********************************************************************************
* This code was developed by:
* Audox Ingeniería Ltda.
* You can contact us at:
* Web: www.audox.cl
* Email: info@audox.cl
* Skype: audox.ingenieria
********************************************************************************/

$job_strings[] = 'proceso_1d';


function proceso_1d(){
	global $sugar_config, $db;
	
					$query_2="  select  case WHEN count(*)*1 > 0 and round(count(*)*1) < 1
										THEN  1
										ELSE round(count(*)*1)
										END
										AS  porcentaje 
								from opportunities o
								join  opportunities_cstm do on do.id_c = o.id
								join leads_opportunities_1_c lea on lea.leads_opportunities_1opportunities_idb = o.id 
								where o.sales_stage in ('CerradoGanado','CerradoPerdido')
								and o.opportunity_type = 'solicitud_web'
								and do.estado_wf_c in ('cambio_estado','asignado')
								and o.deleted = 0 ";
				$res_2 = $db->Query($query_2);
				if($row_2 = $db->fetchByAssoc($res_2)){
					$porcentaje = $row_2['porcentaje'];
				}
			
			$query = "select lea.leads_opportunities_1leads_ida as id_lead, o.id as id_oportunidad  
					from opportunities o
				join  opportunities_cstm do on do.id_c = o.id
				join leads_opportunities_1_c lea on lea.leads_opportunities_1opportunities_idb = o.id 
				where o.sales_stage in ('CerradoGanado','CerradoPerdido')
				and o.opportunity_type = 'solicitud_web'
				and do.estado_wf_c in ('cambio_estado','asignado')
				and o.deleted = 0
				limit ".$porcentaje;
				
	$res = $db->query($query, true, 'Error: ');
	$oportunidad = new Opportunity();
	$contacto = new Lead();
	while($row = $db->fetchByAssoc($res)){
			$oportunidad->retrieve($row['id_oportunidad']);
			$oportunidad->estado_wf_c='envio_encuesta';
			$oportunidad->save();
			$contacto->retrieve($row['id_lead']);
			// plnatilla de email
			
			$template = new EmailTemplate();
			$template->retrieve_by_string_fields(array('name' => 'SW_12_Encuesta_Atencion' ));	
			// Parse Subject If we used variable in subject
			$template->subject= str_replace('$lead_name', $contacto->full_name, $template->subject);
			// Parse Body HTML
			$template->body_html= str_replace('$lead_name', $contacto->full_name, $template->body_html);
			$template->body_html= str_replace('$rut', $row['id_oportunidad'], $template->body_html);
		
			// enviamos el email
			$emailObj = new Email();
			$defaults = $emailObj->getSystemDefaultEmail();
			$mail = new SugarPHPMailer();
			$mail->setMailerForSystem();
			$mail->From = $defaults['email'];
			$mail->FromName = $contacto->full_name;
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
				$email->from_name=$contacto->full_name;
				$email->parent_name=$contacto->full_name;
				$email->date_sent=TimeDate::getInstance()->getNow(true)->modify("+0 days")->asDb();
				$email->parent_type='Opportunity';
				$email->parent_id=$row['id_oportunidad'];
				$email->from_addr_name=$contacto->full_name;
				$email->save();
			}
			//$GLOBALS['log']->fatal("fin proceso_1d...");
	}
		
	return true;
}



 /**
 * 
 * 
 * @package AdvancedOpenDiscovery
 * @copyright SalesAgility Ltd http://www.salesagility.com
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU AFFERO GENERAL PUBLIC LICENSE
 * along with this program; if not, see http://www.gnu.org/licenses
 * or write to the Free Software Foundation,Inc., 51 Franklin Street,
 * Fifth Floor, Boston, MA 02110-1301  USA
 *
 * @author Salesagility Ltd <support@salesagility.com>
 */

$job_strings[] = 'aodIndexUnindexed';
$job_strings[] = 'aodOptimiseIndex';


/**
 * Scheduled job function to index any unindexed beans.
 * @return bool
 */
function aodIndexUnindexed(){
    $total = 1;
    while($total > 0){
        $total = performLuceneIndexing();
    }
    return true;
}

function aodOptimiseIndex(){
    $index = BeanFactory::getBean("AOD_Index")->getIndex();
    $index->optimise();
    return true;
}


function performLuceneIndexing(){
    global $db, $sugar_config;
    if(empty($sugar_config['aod']['enable_aod'])){
        return;
    }
    $index = BeanFactory::getBean("AOD_Index")->getIndex();

    $beanList = $index->getIndexableModules();
    $total = 0;
    foreach($beanList as $beanModule => $beanName){
        $bean = BeanFactory::getBean($beanModule);
        if(!$bean || !method_exists($bean,"getTableName") || !$bean->getTableName()){
            continue;
        }
        $query = "SELECT b.id FROM ".$bean->getTableName()." b LEFT JOIN aod_indexevent ie ON (ie.record_id = b.id AND ie.record_module = '".$beanModule."') WHERE b.deleted = 0 AND (ie.id IS NULL OR ie.date_modified < b.date_modified)";
        $res = $db->limitQuery($query,0,500);
        $c = 0;
        while($row = $db->fetchByAssoc($res)){
            $c++;
            $total++;
            $index->index($beanModule, $row['id']);
        }
        if($c){
            $index->commit();
            $index->optimise();
        }

    }
    $index->optimise();
    return $total;
}


/*********************************************************************************
* This code was developed by:
* este proceso es usado para desactivar le proceso de workflow en un tiempo determinado.
* You can contact us at: armando ytriago
* Web: www.audox.cl
* Email: info@audox.cl
* Skype: audox.ingenieria
********************************************************************************/

$job_strings[] = 'proceso_desactiva_workflow';


function proceso_desactiva_workflow(){
		global $sugar_config, $db;
	$min = 120;
	//$min = 1;
	//$GLOBALS['log']->fatal("inicio out...");
	//este proceso esta orientado a tres horas
	$query = "
				SELECT
				  us.id_c
				FROM
				  users_cstm us
				WHERE
				  date_format(now(),'%Y-%m-%d %H:%i:%s') >= date_format(DATE_ADD(us.ultimo_acceso_c,INTERVAL ".$min." MINUTE),'%Y-%m-%d %H:%i:%s') 
				  AND us.disponible_sw_c = 1";
			//$GLOBALS['log']->fatal("Query: ".$query);
			$res = $db->query($query, true, 'Error: ');
			while($row = $db->fetchByAssoc($res)){
				$update= "update users_cstm u set u.disponible_sw_c = 0 where u.id_c = '".$row['id_c']."'";
				$resul = $db->Query($update);
			}
	//$GLOBALS['log']->fatal("out fin");
	return true;
}




/*********************************************************************************
* This code was developed by:
* Audox Ingeniería Ltda.
* You can contact us at:
* Web: www.audox.cl
* Email: info@audox.cl
* Skype: audox.ingenieria
********************************************************************************/

$job_strings[] = 'proceso_notificacion_cumpleanio';


function proceso_notificacion_cumpleanio(){
	global $db,$sugar_config,$current_user;
	
					$query=" SELECT e.id
							 FROM
							   emails e
							 JOIN emails_text ema ON e.id = ema.email_id
							 WHERE
							  e.parent_type = 'contacts' AND
							  e.status = 'AGENDADO' AND 
							  SUBSTRING(e.name,1,11) = 'CUMPLEANIOS' and
							  DATE_FORMAT(e.date_sent, '%Y-%m-%d') =  DATE_FORMAT(NOW(),'%Y-%m-%d')";
	$res = $db->query($query, true, 'Error: ');
	while($row = $db->fetchByAssoc($res)){
			// recupero datos del email programado
			$Email= new Email();
			$Email->retrieve($row['id']);
			// recupero datos del contacto.
			$contacto = new Contact();
			$contacto->retrieve($Email->parent_id);
			// recupero datos del usuario asignado
			$user= new User();
			$user->retrieve($Email->assigned_user_id);
			
			// enviamos el email
			$emailObj = new Email();
			$defaults = $emailObj->getSystemDefaultEmail();
			$mail = new SugarPHPMailer();
			$mail->setMailerForSystem();
			$mail->From = $user->full_name.'<'.$user->email1.'>';
			$mail->FromName = $user->full_name;
			$mail->ClearAllRecipients();
			$mail->ClearReplyTos();
			$mail->Subject=substr($Email->name,12);
			$mail->Body=$Email->description_html;
			$mail->AltBody=$Email->description_html;
			$mail->prepForOutbound();
			$emailTo=array($contacto->email1);
			foreach ($emailTo as $value) {
				$mail->AddAddress($value);
			}
			$mail->AddReplyTo($user->email1,$user->full_name);
			$mail->AddBCC($user->email1);
			//$con= $con + 1;
			if($mail->Send()){
				//$GLOBALS['log']->fatal("actualizo email ".$con);
				$Email_save = new Email();
				$Email_save->retrieve($row['id']);
				$Email_save->status = 'sent';
				$Email_save->from_addr = $user->email1;
				$Email_save->to_addrs = $contacto->email1;
				$Email_save->cc_addrs = $user->email1;
				$Email_save->save();
			}
			//$GLOBALS['log']->fatal("fin proceso_1d...");
	}
		
	return true;
}


?>