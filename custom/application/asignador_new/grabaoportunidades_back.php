<?php  
 ini_set('error_reporting', E_ALL);
 ini_set('display_errors', True);
 require_once('modules/EmailTemplates/EmailTemplate.php');
 global $current_user;
 global $db, $sugar_config;
$timeDate = new TimeDate();

// $query_2="SELECT o.id, IFNULL(s.destino,'Sin Destino') AS detino ,o.date_entered, o.lead_source AS canal,o.sales_stage AS etapa, s.fecha_viaje,s.agente,priority_c
			// FROM  opportunities o
			// JOIN opportunities_cstm d ON o.id = d.id_c
			// JOIN opportunities_crm_solicitudes_1_c os ON os.opportunities_crm_solicitudes_1opportunities_ida= d.id_c 
			// JOIN crm_solicitudes s ON s.id = os.opportunities_crm_solicitudes_1crm_solicitudes_idb
			// WHERE o.opportunity_type = 'solicitud_web' 
			// AND  o.sales_stage = 'recepcionado'
			// ORDER BY s.destino,d.priority_c;";	
      // $list = $db->query($query_2);
	 // $row = $db->fetchByAssoc($list);

//se listan lo usuario que que correspondan a cada select de la vista            
 // $usuarios = new User();
// $list_contact =   $usuarios->get_full_list("users.full_name", "users_cstm.sucursal_c = 'CONTACT' and users.status='Active'  ",true);
// $list_workflow =  $usuarios->get_full_list("users.full_name", "users.status='Active'  ",true);
// $list_externos =  $usuarios->get_full_list("users.last_name", " (users_cstm.sucursal_c = 'EXTERNOS' OR users_cstm.sucursal_c = 'PROTOTIPO') and users.status='Active'  ",true);
// $list_favoritos = $usuarios->get_full_list("users.last_name", "users_cstm.favorito_c = 1 and users.status='Active' ",true);



$asig	 =	explode("|",$_POST['asignado_a']);
$id_user =$asig[0];
$nombre =$asig[1];
$cantidad = count($_POST['solicitudes_asig']);

 

//*****************************************************************************************//
 
	   //creamos fecha hora de asignacion
		$user_time_format = $current_user->getPreference('timef');
		$current_user->setPreference('timef', 'H:i:s');
		$fecha = date($current_user->getPreference('datef').' '.$user_time_format, strtotime(date('Y-m-d H:i:s')));
		//$fecha_new = date($current_user->getPreference('datef').' '.$user_time_format, strtotime(date('Y-m-d H:i:s',strtotime('+15 minutes'))));
		$acceso_wf = new User();
		$acceso_wf->retrieve($id_user );
       if ($_POST['tipo'] == 'user_workflow' && $acceso_wf->disponible_sw_c == 1 && $acceso_wf->sucursal_c == 'WORKFLOW'){ 
					
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
				  $cant = $row['cantidad'];
			
				if($cant > 0){
				   echo '<script>alert("El Ejecutivo '.$nombre.' tiene Asignada Solicitudes..!!Por Favor Espere 30 min");</script>';		
				}else{	
						$asig		=	explode("|",$_POST['asignado_a']);
						$id_user	= $asig[0];
						$full_name	= $asig[1];				
							
						
						$tarea 		= new Task();
						$tarea->name='AsignaSolicitudes - '.$fecha;
						$tarea->assigned_user_id=$id_user;
						$tarea->created_by=$current_user->id;
						$tarea->modified_user_id=$current_user->id;
						$tarea->status='Not Started';
						$tarea->description='['.$fecha.'] Asigna Sollicitudes a Ejecutiva '.$full_name;
						$tarea->priority='Low';
						$tarea->date_start=$timeDate->getNow(true)->asDb();
						$tarea->date_due=date('Y-m-d H:i:s',strtotime('+210 minutes'));
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
								// now create email
								@$mail->Send();
								 
								  // $GLOBALS['log']->fatal($user->email1);
								 // $GLOBALS['log']->fatal($emailSubject);
								 // $GLOBALS['log']->fatal($emailBody);
							echo '<script>alert("El Ejecutivo '.$nombre.' se le Asigno la Cantidad de '.$cantidad.' Solicitudes..");</script>';
					
			           }
		}else{
						$usuario= new User();
						$usuario->retrieve($id_user);
					  foreach($_POST['solicitudes_asig'] as $solicitudes) {
						$oportunidad = new Opportunity();
						$oportunidad->retrieve($solicitudes);
						$oportunidad->assigned_user_id = $id_user;
						if ($usuario->sucursal_c == 'EXTERNOS'){
							$oportunidad->sales_stage='CerradoExterno';
						}else if ($usuario->sucursal_c == 'CONTACT'){
							$oportunidad->sales_stage='asignado';	
						}
						$user_time_format = $current_user->getPreference('timef');
						$current_user->setPreference('timef', 'H:i:s');
						$fecha = date($current_user->getPreference('datef').' '.$user_time_format, strtotime(date('Y-m-d H:i:s')));
						$oportunidad->date_assigned_c=$fecha;
						$oportunidad->save();
						$solicitud = new crm_solicitudes();
						$solicitud->retrieve($oportunidad->crm_solicitud_id_c);
						
					   if($usuario->sucursal_c =='CONTACT'){
							$texto = "
							  <h2>Administrator, le ha asignado una Oportunidad.</h2>

								Nombre Oportunidad: ".$oportunidad->name." <br>
								Fecha de Viaje:".$solicitud->fecha_viaje." <br>
								Estapa de la venta:".$oportunidad->sales_stage." <br>
								Cantidad Adultos:".$solicitud->adultos." <br>
								Cantidad de niños:".$solicitud->ninos." <br>
								Descripción:".$solicitud->description." <br>
								<br>
								<br>
								Puede examinar esta Oportunidad en:
								<".$sugar_config['site_url']."/index.php?module=Opportunities&action=DetailView&record=".$oportunidad->id.">";

								$emailObj = new Email();
								$defaults = $emailObj->getSystemDefaultEmail();
								$mail = new SugarPHPMailer();
								$mail->setMailerForSystem();
								$mail->From = 'noticias@cocha.com';//$usuario->email1
								$mail->FromName = 'Turismo Cocha';//$usuario->full_name
								$mail->ClearAllRecipients();
								$mail->ClearReplyTos();
								$mail->Subject=from_html('Oportunidad de SugarCRM - '.$oportunidad->name);
								$mail->Body=$texto;
								$mail->AltBody=from_html($texto);
								$mail->prepForOutbound();
								$mail->AddAddress($usuario->email1);
								$GLOBALS['log']->fatal('CONTACT');
							$mail->Send();
							}else if($usuario->sucursal_c =='PROTOTIPO'){
								$texto = "
								  <h2>Estimada(o) ".$usuario->full_name."</h2>
									<br>El administrator, le ha asignado oportunidades.
								";
								$emailObj = new Email();
								$defaults = $emailObj->getSystemDefaultEmail();
								$mail = new SugarPHPMailer();
								$mail->setMailerForSystem();
								$mail->From = 'noticias@cocha.com';//$usuario->email1
								$mail->FromName = 'Turismo Cocha';//$usuario->full_name
								$mail->ClearAllRecipients();
								$mail->ClearReplyTos();
								$mail->Subject=from_html('Oportunidad de SugarCRM - '.$oportunidad->name);
								$mail->Body=$texto;
								$mail->AltBody=from_html($texto);
								$mail->prepForOutbound();
								$mail->AddAddress($usuario->email1);
								$mail->Send();
							
						} 
				    }
					echo '<script>alert("La Ejecutiva '.$nombre.' se le Asigno la Cantidad de '.$cantidad.' Solicitudes..");</script>';
			}
    

		
//*************************************Actualiza los List Box****************************************// 
/** 
 $HTML = "";
 
  if(isset($_POST['div_combo'])) {
	
	  if (($_POST['div_combo'])=='div_workflow'){	
		
		 $HTML .= "<div class='row' >
					 <div class='col-md-6 form-inline tipo_ejecutivo'>
			         <label>Ejecutivos Workflow:</label>
					 <select class='form-control' name='user_workflow' id='user_workflow'>
		              <option value='0'>«« SELECCIONE »»</option>";
              

			if ($list_workflow == NULL){
				 
				$HTML.="<select class='form-control'>
						<option value='0'></option> 
					</select>";
				 
				 
			 }else{
 
			asort($list_workflow); 
			 foreach ($list_workflow as $u) { 
			  //muestro las cantidades de oportunidades por usuario.
          $cantidad=0;
		  
              $query2 = " SELECT count(*) as cantidad 
							FROM opportunities o
							inner join opportunities_cstm oc  ON o.id = oc.id_c
							where o.assigned_user_id='".$u->id."' 
							and o.deleted=0 
							 and o.sales_stage in ('reservado','asignado')";
				
				
			$res2 = $db->Query($query2);
			if($row2 = $db->fetchByAssoc($res2)){
					$cantidad= $row2['cantidad'];
			  }
              
          
				$HTML .= "<option value=".$u->id.'|'.$u->full_name.">".$u->full_name ."- (".$cantidad.")</option>";
               
			}
			 
			  
			     $HTML .= "</select>";
			     $HTML .= "</div>";
				 $HTML .= "<div class='col-md-3 form-group boton_asignar'>";
				 $HTML .= "<button type='button' onclick='asignar()' id='boton_asignar' class='btn btn-blue'>Asignar</button>";
		         $HTML .= "</div>";
		 
		 }
	   }else{
		  
		  if (($_POST['div_combo'])=='div_sucursales'){
		  
		   	 $HTML .= "<div class='row' >
			 <div class='col-md-6 form-inline tipo_ejecutivo'>
	         <label>Ejecutivos Sucursales</label>
			 <select class='form-control' name='user_externo' id='user_externo'>
              <option value='0'>«« SELECCIONE »»</option>";
              

			if ($list_externos == NULL){
				 
				$HTML.="<select class='form-control'>
						<option value='0'></option> 
					</select>";
				 
				 
			 }else{
 
			asort($list_externos); 
			 foreach ($list_externos as $u) { 
			  //muestro las cantidades de oportunidades por usuario.
          $cantidad=0;
		  
              $query2 = " SELECT count(*) as cantidad 
							FROM opportunities o
							inner join opportunities_cstm oc  ON o.id = oc.id_c
							where o.assigned_user_id='".$u->id."' 
							and o.deleted=0 
							 and o.sales_stage in ('reservado','asignado')";
				
				
			$res2 = $db->Query($query2);
			if($row2 = $db->fetchByAssoc($res2)){
					$cantidad= $row2['cantidad'];
			  }
              
          
				$HTML .= "<option value=".$u->id.'|'.$u->full_name.">".$u->full_name ."- (".$cantidad.")</option>";
             }
			  
			     $HTML .= "</select>";
			     $HTML .= "</div>";
				 $HTML .= "<div class='col-md-3 form-group boton_asignar'>";
				 $HTML .= "<button type='button' onclick='asignar()' id='boton_asignar' class='btn btn-blue'>Asignar</button>";
		         $HTML .= "</div>";
		  
		   }
	    }
      
	 }
      echo $HTML;
       
   }
*/