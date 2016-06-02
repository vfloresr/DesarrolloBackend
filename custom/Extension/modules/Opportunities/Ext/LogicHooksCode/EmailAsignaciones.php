<?php

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class EmailAsignaciones{

	function CustomOpportunitiesEmail($emailTo, $emailSubject, $emailBody,$id_oportunidad){
		global $db, $sugar_config,$current_user;
		$timeDate = new TimeDate();
		
		$emailObj = new Email();
		$defaults = $emailObj->getSystemDefaultEmail();
		$mail = new SugarPHPMailer();
		$mail->setMailerForSystem();
		$mail->From = $defaults['email'];
		$mail->FromName = $defaults['name'];
		$mail->ClearAllRecipients();
		$mail->ClearReplyTos();
		$mail->Subject=from_html($emailSubject);
		$mail->Body=$emailBody;
		$mail->AltBody=from_html($emailBody);
		$mail->prepForOutbound();
		foreach ($emailTo as &$value) {
			$mail->AddAddress($value);
		}
		// now create email
		if(@$mail->Send()){
			$email = new Email();
			$email->name = from_html($emailSubject);
			$email->type='out';
			$email->status='sent';
			$email->intent='pick';
			$email->from_addr = $defaults['email'];

			$email->to_addrs= $defaults['name'];
			$email->description_html=$emailBody;

			$email->assigned_user_id=$current_user->id;
			$email->assigned_user_name=$current_user->user_name;
			$email->from_name=$current_user->full_name;
			$email->parent_name=$defaults['name'];
			$email->date_sent=TimeDate::getInstance()->getNow(true)->modify("+0 days")->asDb();
			$email->parent_type='Opportunity';
			$email->parent_id=$id_oportunidad;
			$email->from_addr_name=$current_user->full_name;
			$email->save();
		}
		
	}
	
	function EnvioEmailExternos(&$bean, $event, $arguments){
		global $db, $sugar_config,$current_user;
		$timeDate = new TimeDate();


		//cerrado ganado 
		if ($bean->sales_stage == 'CerradoGanado' && $bean->opportunity_type == 'solicitud_web'){
			if ($bean->estado_wf_c == 'cambio_estado' || $bean->estado_wf_c == 'asignado'){
					//$GLOBALS['log']->fatal("inicio ganado");
							$user= new User();
							$user->retrieve($bean->assigned_user_id);
						
							//plnatilla de email
							$template = new EmailTemplate();
							$template->retrieve_by_string_fields(array('name' => 'SW_11_Consultor_OportunidadGanada' ));	
							//Parse Subject If we used variable in subject
							$template->subject= str_replace('$contact_user_full_name', $user->full_name, $template->subject);
							//Parse Body HTML
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
								$email->name = from_html($template->subject);
								$email->type='out';
								$email->status='sent';
								$email->intent='pick';
								$email->from_addr = $defaults['email'];

								$email->to_addrs= $user->full_name."<".$user->email1.">";
								$email->description_html=$template->body_html;

								$email->assigned_user_id=$current_user->id;
								$email->assigned_user_name=$current_user->user_name;
								$email->from_name=$current_user->full_name;
								$email->parent_name=$usuario->full_name;
								$email->date_sent=TimeDate::getInstance()->getNow(true)->modify("+0 days")->asDb();
								$email->parent_type='Opportunity';
								$email->parent_id=$bean->id;
								$email->from_addr_name=$current_user->full_name;
								$email->save();
							}
		}
		}
		if ($bean->sales_stage=='CerradoExterno'){

			$user2 = new User();
			$user2->retrieve($bean->assigned_user_id);
			$solicitud_oportunidad = new crm_solicitudes();
			$solicitud_oportunidad->retrieve($bean->crm_solicitud_id_c);
			if(	$user2->sucursal_c =='EXTERNOS'){
				$email_template_id='7721f3ef-22d4-6520-bdde-564744f7bb58';
				$emailtemplate = new EmailTemplate();
				$emailtemplate->retrieve($email_template_id);
				$emailtemplate->body_html=str_replace('$opportunity_assigned_user_name', $user2->full_name, $emailtemplate->body_html);
				$emailtemplate->body_html=str_replace('$opportunity_name', $bean->name, $emailtemplate->body_html);

				if($solicitud_oportunidad->fecha_flexible == 1) $fecha_flexible_c='Si';else $fecha_flexible_c='No';
				$emailtemplate->body_html=str_replace('$opportunity_fecha_flexible_c', $fecha_flexible_c, $emailtemplate->body_html);
				$emailtemplate->body_html=str_replace('$opportunity_habitaciones_c', $solicitud_oportunidad->hotel_habitacion, $emailtemplate->body_html);
				$emailtemplate->body_html=str_replace('$opportunity_edad_1_c', $solicitud_oportunidad->edades, $emailtemplate->body_html);
								

				$emailtemplate->body_html=str_replace('$opportunity_fecha_viaje_c', date("d/m/Y", strtotime($solicitud_oportunidad->fecha_viaje)), $emailtemplate->body_html);
				$emailtemplate->body_html=str_replace('$opportunity_description', $solicitud_oportunidad->description, $emailtemplate->body_html);
				$emailtemplate->body_html=str_replace('$opportunity_pasajeros_adultos_c', $solicitud_oportunidad->adultos, $emailtemplate->body_html);
				$emailtemplate->body_html=str_replace('$opportunity_pasajeros_ninos_c', $solicitud_oportunidad->ninos, $emailtemplate->body_html);
				$emailtemplate->body_html=str_replace('$crm_solicitudes_edades', $solicitud_oportunidad->edades, $emailtemplate->body_html);
				$query=" select leads_opportunities_1leads_ida as lead 
							from leads_opportunities_1_c 
							where leads_opportunities_1opportunities_idb = '".$bean->id."'";	
				 $respt = $db->Query($query); 
				 if($row_1 = $db->fetchByAssoc($respt)){
					$potencial=$row_1['lead'];  
				}
				
				$cliente = new Lead();
				$cliente->retrieve($potencial);
				
				$emailtemplate->body_html=str_replace('$opportunity_account_name', $cliente->full_name, $emailtemplate->body_html);
				$emailtemplate->body_html=str_replace('$account_phone_office', $cliente->phone_home, $emailtemplate->body_html);
				$emailtemplate->body_html=str_replace('$account_email1', $cliente->email1, $emailtemplate->body_html);
				$emailtemplate->body_html=str_replace('$account_rut_c', $cliente->phone_fax, $emailtemplate->body_html);

				//#######################email contacto
				
				$emailtemplate->body_html=str_replace('$account_email1', $cliente->email1, $emailtemplate->body_html);
			
				//##################################################
	
				$query = "
							select s.id,s.pdf_joomla,s.url,s.name,s.tour_operador
							from opportunities o
							join opportunities_crm_solicitudes_1_c ro on ro.opportunities_crm_solicitudes_1opportunities_ida = o.id
							join crm_solicitudes s on ro.opportunities_crm_solicitudes_1crm_solicitudes_idb = s.id
							where o.id = '".$bean->id."' ";
				$res = $db->Query($query);
				$emailtemplate->body_html =$emailtemplate->body_html."
				<table style='text-align:left;width='100%'' >
				<tr>
					<th width='30%' text-align='left' colspan='4' style='background:#DCDCDC; text-align: left;'>
						<h3><span style='font-family: arial,helvetica,sans-serif;'>
						<strong><span style='font-size: small;'>Productos Cotizados</span></strong>
						</h3>
					</th>
					
				</tr>
				<tr>
					<th width='30%' style='text-align:left;'>Nombre Producto</th>
					<th width='10%' style='text-align:left;'>Operador</th>
					<th width='30%'  style='text-align:left;'>Producto en la Web</th>
					<th width='20%'  style='text-align:left;'>Link al Programa</th>
				</tr>
				";
				while($row = $db->fetchByAssoc($res)){
					if($row['url']=='') $texto_link='-'; else $texto_link="Ir a la web";
					if($row['pdf_joomla']=='' || empty($row['pdf_joomla'])) $texto_pdf='-'; else $texto_pdf="Link al producto";
					$simb2 = array("http:/","http://","http:","http//","http/");
					if($row['url']!=''){
						if(substr($row['url'],0,6) != 'http://' ){
							$link = 'http://'.str_replace($simb2,"",$row['url']);
						}else{
							$link = $row['url'];
						}
					} 
					 
					$emailtemplate->body_html =$emailtemplate->body_html."
					<tr>
						<td>".$row['name']."</td>
						<td>".$row['tour_operador']."</td>
						<td><a href='".$link."' target='_blank'>$texto_link</a></td>
						<td><a href='".$row['pdf_joomla']."' target='_blank'>$texto_pdf</a></td>
					</tr>";
			

				}         
				$emailtemplate->body_html =$emailtemplate->body_html."
				</table>
				<br>
				<p></p>

				<p ><h3>Saludos,<br> Equipo Cocha Digital</h3></p>
				
				";


				//##################################################
				$emailtemplate->subject = "Cocha Digital: ".$bean->name;
				$user = new User();
				$user->retrieve($bean->assigned_user_id);
				$this->CustomOpportunitiesEmail(array($user->email1), $emailtemplate->subject, $emailtemplate->body_html,$bean->id);
				SugarApplication::appendErrorMessage($emailtemplate->subject);
			}
		}
		
		if (empty($bean->fetched_row['id']) && $bean->campaign_id=='e473065b-9d04-b091-54f3-545b02c12710'){
			$task = new Task();
			$task->name="Ofrecer Hotel";
			$task->parent_type = "Opportunities";
			$task->parent_id = $bean->id;
			$task->assigned_user_id = $bean->assigned_user_id;
			$task->save();
			
			$task = new Task();
			$task->name="Ofrecer Asistencia de Viaje";
			$task->parent_type = "Opportunities";
			$task->parent_id = $bean->id;
			$task->assigned_user_id = $bean->assigned_user_id;
			$task->save();
			
			$task = new Task();
			$task->name="Ofrecer Arriendo de Vehículo";
			$task->parent_type = "Opportunities";
			$task->parent_id = $bean->id;
			$task->assigned_user_id = $bean->assigned_user_id;
			$task->save();
		}
		
	}
	
	

}
?>