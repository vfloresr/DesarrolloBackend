<?php

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

?>