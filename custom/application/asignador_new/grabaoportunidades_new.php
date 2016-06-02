<?php  
header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Credentials: true");
	header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
	header('Access-Control-Max-Age: 1000');
	header('Access-Control-Allow-Headers: Origin, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers');
 ini_set('error_reporting', E_ALL);
 ini_set('display_errors', True);
 require_once('modules/EmailTemplates/EmailTemplate.php');
 global $current_user;
 global $db, $sugar_config;
$timeDate = new TimeDate();

	$asig	  =	(isset( $_POST['asignado_a'] )) ?  explode("|",$_POST['asignado_a']):  '' ; 
	$id_user  = (isset($asig[1] )) ? $asig[1] :  '' ;
	$nombre   = (isset($asig[0] )) ? $asig[0] :  '' ;
	if(isset($_POST['id_oportunidad'])){
		$_POST['solicitudes_asig'][] =  $_POST['id_oportunidad'] ;
	}
	$cantidad = (isset($_POST['solicitudes_asig'])) ? count($_POST['solicitudes_asig']):  '' ;
    $tipo     = (isset($_POST['tipo'] )) ? $_POST['tipo'] :  '' ;
    $asig_directo    =  (isset( $_POST['asig_directo'] )) ? $_POST['asig_directo'] :  '' ; 

// echo "asig:".$id_user."<br>";
// print_r ($_POST['solicitudes_asig']);
// die();
//*****************************************************************************************/ 
 
	 //creamos fecha hora de asignacion
	    $user_time_format = $current_user->getPreference('timef');
		$current_user->setPreference('timef', 'H:i:s');
		$fecha = date($current_user->getPreference('datef').' '.$user_time_format, strtotime(date('Y-m-d H:i:s')));
		//$fecha_new = date($current_user->getPreference('datef').' '.$user_time_format, strtotime(date('Y-m-d H:i:s',strtotime('+15 minutes'))));
		$acceso_wf = new User();
		$acceso_wf->retrieve($id_user );
		
       if ($tipo  == 'user_workflow' && $acceso_wf->disponible_sw_c == 1 && $acceso_wf->sucursal_c == 'WORKFLOW'){ 
			$cant_opor = 50;
				$sql =	"SELECT  
					 t.id,
					 count(o.id) as cantidad
					FROM opportunities o
					JOIN tasks_opportunities_1_c rt ON rt.tasks_opportunities_1opportunities_idb = o.id and rt.deleted = 0 
					JOIN tasks t ON t.id = rt.tasks_opportunities_1tasks_ida AND t.status = 'Not Started'
					JOIN tasks_cstm td ON t.id = td.id_c AND td.tipo_tarea_c = 'WORKFLOW'
					WHERE o.assigned_user_id = '".$id_user."'
					group by t.id ";

				  $resultado = $db->query($sql);
				  $row = $db->fetchByAssoc($resultado);
				  $tarea = $row['cantidad'];
				  
				  $sql_2 =	"SELECT count(o.id) AS cantidad
							  FROM   opportunities o
							 WHERE   o.assigned_user_id = '".$id_user."' AND o.sales_stage IN ('asignado', 'contactado', 'cotizacion', 'negociacion') AND 
								     o.opportunity_type = 'solicitud_web'";

				  $resultado_2 = $db->query($sql_2);
				  $row_2 = $db->fetchByAssoc($resultado_2);
				  $oportunidad = $row_2['cantidad'];
			
				if($tarea > 0){
				   echo '<script>alert("El Ejecutivo '.$nombre.' tiene Asignada Solicitudes..!!Por Favor Espere 30 min");</script>';		
				}elseif ($oportunidad > $cant_opor ){
					echo '<script>alert("El Ejecutivo '.$nombre.' tiene Asignada mas de '.$cant_opor.' Solicitudes Sin Cerrar..!!Por Favor Debe Cerrar Algunas solicitudes pendientes...");</script>';
				}else{
											
						$tarea 		= new Task();
						$tarea->name='AsignaSolicitudes - '.$fecha;
						$tarea->assigned_user_id=$id_user;
						$tarea->created_by=$current_user->id;
						$tarea->modified_user_id=$current_user->id;
						$tarea->status='Not Started';
						$tarea->description='['.$fecha.'] Asigna Sollicitudes a Ejecutiva '.$nombre;
						$tarea->priority='Low';
						$tarea->date_start=$timeDate->getNow(true)->asDb();
						$tarea->date_due= TimeDate::getInstance()->getNow(true)->modify("+30 minutes")->asDb();;
						$tarea->tipo_tarea_c='WORKFLOW';
						$tarea->save();
						
						
						  foreach($_POST['solicitudes_asig'] as $solicitudes) {
						 
							  $oportunidad = new Opportunity();
							  $oportunidad->retrieve($solicitudes);
							  $oportunidad->assigned_user_id = $id_user;
							  $oportunidad->sales_stage='reservado';
							  $oportunidad->save();
							  $tarea->load_relationship('tasks_opportunities_1');
							  $tarea->tasks_opportunities_1->add($oportunidad->id);
						 
							}
								$user= new User();
								$user->retrieve($tarea->assigned_user_id);
								
								//plnatilla de email
								//$template_id = '5e308aba-1771-8c38-b3b4-5642028180be';
								
								$template = new EmailTemplate();
								//$template->retrieve($template_id);
								$template->retrieve_by_string_fields(array('name' => 'SW_2_TiempoLimiteParaAceptarSW' ));	
								//Parse Subject If we used variable in subject
								$template->subject= str_replace('$contact_user_full_name', $user->full_name, $template->subject);
								//Parse Body HTML
								$template->body_html= str_replace('$contact_user_full_name', $user->full_name, $template->body_html);
								
								//$GLOBALS['log']->fatal('inicio envio');
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
									$email->assigned_user_id=$current_user->id;
									$email->assigned_user_name=$current_user->user_name;
									$email->from_name=$current_user->full_name;
									$email->parent_name=$user->full_name;
									$email->date_sent=TimeDate::getInstance()->getNow(true)->modify("+0 days")->asDb();
									$email->parent_type='Task';
									$email->parent_id=$tarea->id;
									$email->from_addr_name=$current_user->full_name;
									$email->save();
								}	
								 
								  // $GLOBALS['log']->fatal($user->email1);
								 // $GLOBALS['log']->fatal($emailSubject);
								 // $GLOBALS['log']->fatal($emailBody);
							echo '<script>alert("El Ejecutivo '.$nombre.' se le Asigno la Cantidad de '.$cantidad.' Solicitudes..");</script>';
					
			           }
		}else if($tipo == 'user_externo' && $acceso_wf->sucursal_c != 'EXTERNOS'){
					$usuario= new User();
					$usuario->retrieve($id_user);
					foreach($_POST['solicitudes_asig'] as $solicitudes) {
						$oportunidad = new Opportunity();
						$oportunidad->retrieve($solicitudes);
						$user_time_format = $current_user->getPreference('timef');
						$current_user->setPreference('timef', 'H:i:s');
						$fecha = date($current_user->getPreference('datef').' '.$user_time_format, strtotime(date('Y-m-d H:i:s')));
						$oportunidad->date_assigned_c=$fecha;
						$oportunidad->assigned_user_id = $id_user;
						$oportunidad->sales_stage ='asignado';
						//$oportunidad->id_agente_c =$id_user;
						//$oportunidad->agente_c ='asignado';
						$oportunidad->save();
						$solicitud = new crm_solicitudes();
						$solicitud->retrieve($oportunidad->crm_solicitud_id_c);
									$template = new EmailTemplate();
									$template->retrieve_by_string_fields(array('name' => 'email_asignado_cliente' ));	
									
									$oportunidad_cliente	=	explode("/",$oportunidad->name);
									$cliente	= $oportunidad_cliente[1];		
									$template->body_html= str_replace('$contact_user_full_name', $usuario->full_name, $template->body_html);
									$template->body_html= str_replace('$fullname', $cliente, $template->body_html);
								
								$emailObj = new Email();
									$defaults = $emailObj->getSystemDefaultEmail();
									$mail = new SugarPHPMailer();
									$mail->setMailerForSystem();
									$mail->From = $defaults['email'];
									$mail->FromName = $usuario->full_name;
									$mail->ClearAllRecipients();
									$mail->ClearReplyTos();
									$mail->Subject=from_html('Oportunidad de SugarCRM - '.$oportunidad->name);
									$mail->Body=$template->body_html;
									$mail->AltBody=$template->body_html;
									$mail->prepForOutbound();
									$emailTo=array($usuario->email1);
									foreach ($emailTo as &$value) {
										$mail->AddAddress($value);
									}
									
									if(@$mail->Send()){
										$email = new Email();
										$email->name = from_html('Oportunidad de SugarCRM - '.$oportunidad->name);
										$email->type='out';
										$email->status='sent';
										$email->intent='pick';
										$email->from_addr = $defaults['email'];
										$email->to_addrs= $usuario->full_name."<".$usuario->email1.">";
										$email->description_html=$template->body_html;
										$email->assigned_user_id=$current_user->id;
										$email->assigned_user_name=$current_user->user_name;
										$email->from_name=$current_user->full_name;
										$email->parent_name=$usuario->full_name;
										$email->date_sent=TimeDate::getInstance()->getNow(true)->modify("+0 days")->asDb();
										$email->parent_type='Opportunity';
										$email->parent_id=$oportunidad->id;
										$email->from_addr_name=$current_user->full_name;
										$email->save();
									}
					}		
                       	echo '<script>alert("La Asignacion se realizo exitosamente....");</script>';							
										
							
		}else{		
						$usuario= new User();
						$usuario->retrieve($id_user);
					  foreach($_POST['solicitudes_asig'] as $solicitudes) {
						$oportunidad = new Opportunity();
						$oportunidad->retrieve($solicitudes);
						$oportunidad->assigned_user_id = $id_user;
						if ($usuario->sucursal_c == 'EXTERNOS'){
							$oportunidad->sales_stage='CerradoExterno';
						}else{
							$oportunidad->sales_stage='asignado';	
						}
						$user_time_format = $current_user->getPreference('timef');
						$current_user->setPreference('timef', 'H:i:s');
						$fecha = date($current_user->getPreference('datef').' '.$user_time_format, strtotime(date('Y-m-d H:i:s')));
						$oportunidad->date_assigned_c=$fecha;
						$oportunidad->save();
						$solicitud = new crm_solicitudes();
						$solicitud->retrieve($oportunidad->crm_solicitud_id_c);
				    }
					echo '<script>alert("La Ejecutiva '.$nombre.' se le Asigno la Cantidad de '.$cantidad.' Solicitudes..");</script>';
		} 