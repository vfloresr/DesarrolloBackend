<?php

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

?>