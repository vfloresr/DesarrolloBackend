<!DOCTYPE html>
<?php global $db,$sugar_config;
		   global $current_user;
		   
echo '<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Bootstrap contact form with PHP example by BootstrapBay.com.">
    <meta name="author" content="BootstrapBay.com">
    <title>Envio de Plantillas Nuevas CRM</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
	
	<style>
	     .span12{
			border:solid 1px #E6E6E6;
            margin-top:30px;
			border-radius: 25px;
		}
	</style>
  </head>
  <body>
  	 <div class="container span12">
  		<div class="row">
  			<div class="col-md-6 col-md-offset-3">
  				<h1 class="page-header text-center" style="font-size:18px;">CRM: Conoce tus nuevas oportunidades del día</h1>
				<form class="form-horizontal" role="form" method="post" action="'.$sugar_config['site_url'].'/index.php?entryPoint=index" name="formulario" onsubmit="limpiar_pagina();">
					<div class="form-group">
						 <div class="col-sm-10 col-sm-offset-2">	
							<label for="name">¿Desea Enviar la Siguiente Plantilla ?</label>
						</div>
					</div>
					 <div class="form-group">
						<div class="col-sm-10 col-sm-offset-4">
							<label><input type="radio"  id="opc1"     name="opciones" value="Si"  />Si</label> 
                            <label><input type="radio"  id="opc2"     name="opciones" value="No" checked />No</label> 
						</div>
					</div>
					<div class="form-group col-sm-offset-4">
						<div class="col-sm-10 col-sm-offset-4">
							<input id="submit" name="submit" type="submit" value="Enviar" class="btn btn-primary">
						</div>
					</div>
				</form> 
			</div>
		</div>
	</div>  
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
  </body>
</html>
 <DIV id="plantilla">';


 if($current_user->user_name == 'admin'){
	
  if(isset($_POST["submit"])) {

	  $opc = $_POST["opciones"];
     echo "opc:".$opc."<br>";
	 
   if(($opc == 'Si')) {
		   unset($_POST["opciones"]);
			  include('custom/application/envio_plantilla/funciones.php');
			  $templatefile = 'custom/application/envio_plantilla/Template_nuevas_oportunidades_CRM.html';
		   $page = file_get_contents($templatefile);
			$fecha = date("Y")."-".date("m")."-".date("d");
		   $title = 'Nuevas Oportunidades CRM';

			 $sql_consultores = "
								  SELECT distinct usu.id, ema1.email_address as email
								  FROM   users usu
								  JOIN  users_cstm us1 ON us1.id_c = usu.id
								  JOIN mo_accesos b ON usu.id = b.assigned_user_id and b.deleted = 0
								  JOIN email_addr_bean_rel ema ON ema.bean_id = usu.id and ema.deleted = 0 and ema.bean_module = 'Users'
								  JOIN email_addresses ema1 ON ema1.id = ema.email_address_id and ema.deleted = 0
								  JOIN (SELECT con.assigned_user_id
										 FROM   contacts con
										 WHERE  date_format(con.birthdate, '%d-%m') = date_format('".$fecha."', '%d-%m')
										 UNION
										 SELECT opp.assigned_user_id
										 FROM   opportunities opp
										 WHERE  date(opp.date_entered) = '".$fecha."' AND opp.sales_stage = 'creado' AND opp.opportunity_type IN ('crosselling',
												'recompra',
												'fugados')
										 UNION
										SELECT tas.assigned_user_id
										 FROM   tasks tas 
										 JOIN tasks_cstm ta_c ON ta_c.id_c = tas.id
										 JOIN   crm_negocios_tasks_c nt ON nt.crm_negocios_taskstasks_idb = ta_c.id_c and nt.deleted = 0
										 JOIN  crm_negocios neg ON neg.id = nt.crm_negocios_taskscrm_negocios_ida      
										 WHERE  date(neg.fecha_destino) = '".$fecha."' AND ta_c.tipo_c IN ('Retornos') AND tas.status = 'Not Started'
										 UNION
										 SELECT tas.assigned_user_id
										 FROM   tasks tas 
										 JOIN tasks_cstm ta_c ON ta_c.id_c = tas.id
										 JOIN   crm_negocios_tasks_c nt ON nt.crm_negocios_taskstasks_idb = ta_c.id_c and nt.deleted = 0
										 JOIN  crm_negocios neg ON neg.id = nt.crm_negocios_taskscrm_negocios_ida      
										 WHERE (neg.fecha_salida >= '".$fecha."') AND (neg.fecha_salida <= ('".$fecha."' + interval 7 day)) 
										AND ta_c.tipo_c IN ('Proximos') AND tas.status = 'Not Started') noti
									 ON noti.assigned_user_id = usu.id
								  WHERE  usu.status = 'Active'
								   AND usu.deleted = 0 ";
											   
			$resul_db = $db->Query($sql_consultores);
			$row = mysqli_num_rows($resul_db); 
		  if($row > 0){
			
		   while ($row = $db->fetchByAssoc($resul_db)) {

				$page = file_get_contents($templatefile);
				
				  $page = str_replace('{title}', $title, $page);
				  $page = str_replace('{nombre_ejecutivo}',nombre_ejecutivo($row['id'],$fecha), $page);
				  $page = str_replace('{cumpleanios}',  mostrar_cumpleanio($row['id'],$fecha), $page);
				  $page = str_replace('{img_oportunidades}',  mostrar_imgagen_oportunidades($row['id'],$fecha), $page);
				  $page = str_replace('{cross}',    oportunidades_venta_cross($row['id'],$fecha), $page);
				  $page = str_replace('{recompra}',  oportunidades_venta_recompra($row['id'],$fecha), $page);
				  $page = str_replace('{fugados}',   oportunidades_venta_fugados($row['id'],$fecha), $page);
				  $page = str_replace('{img_tareas}',      mostrar_imagen_tareas($row['id'],$fecha), $page);
				  $page = str_replace('{prox_viajes}', tareas_prox_viajes($row['id'],$fecha), $page);
				  $page = str_replace('{retornos}',    tareas_retorno($row['id'],$fecha), $page);
				  $page = str_replace('{boton_acceder}',   mostrar_boton_acceder($row['id'],$fecha), $page);
		  
				// echo $page;

					$emailObj = new Email();
					$defaults = $emailObj->getSystemDefaultEmail();
					$mail = new SugarPHPMailer();
					$mail->setMailerForSystem();
					$mail->From = $defaults['email'];
					$mail->FromName = "CRM Cocha";
					$mail->ClearAllRecipients();
					$mail->ClearReplyTos();
					$mail->Subject = from_html("CRM: Conoce tus nuevas oportunidades del día de hoy");
					$mail->Body=$page;
					$mail->AltBody=$page;
					$mail->prepForOutbound();
					$mail->AddAddress($row['email'], "Ejecutivos de Cocha"); 
				
				   if ($mail->Send()) {
						$email = new Email();
						$email->name = from_html("CRM: Conoce tus nuevas oportunidades del día de hoy");
						$email->type='out';
						$email->status='sent';
						$email->intent='pick';
						$email->from_addr = $defaults['email'];
						$email->to_addrs="Ejecutivos de Cocha <".$row['email'].">";
						$email->description_html=$page;
						$email->assigned_user_id='1';
						$email->assigned_user_name='admin';
						$email->from_name="CRM Cocha";
						$email->parent_name="CRM Cocha";
						$email->date_sent=TimeDate::getInstance()->getNow(true)->modify("+0 days")->asDb();
						$email->parent_type='Users';
						$email->parent_id=$row['id'];
						$email->from_addr_name="CRM Cocha";
						$email->save();
					}else{
						$GLOBALS["log"]->fatal("ERROR ENVIO EMAIL: ");
						$GLOBALS["log"]->fatal($mail);
					}
				echo "Usuario:".$row['id']."--"."Correo:".$row['email']."<br>\n";
			 }//End While Principal 
		   }
			   echo '<script>alert("Los Emails fueron Enviado Exitosamente...");</script>';  
			  limpiar_pagina();
			  $opc = '';
    }else{
			unset($_POST["opciones"]);
			echo '<script>alert("Hasta Pronto...");</script>';
			limpiar_pagina();
	}
	
  }
 }else{
	echo '<script>alert("Disculpe Usted no Tiene Privilegios para mandar los Emails...");</script>'; 
	 
 }
?>
</DIV>

<script>
function limpiar_pagina(){
	 setTimeout('document.formulario.reset()',2000);
    return false
	 
}
</script>
