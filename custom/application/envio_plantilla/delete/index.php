<!DOCTYPE html>
<html lang="en">
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
				<form class="form-horizontal" role="form" method="post" action="http://backend.cochadigital.com/index.php?entryPoint=index" name="formulario" onsubmit="limpiar_pagina();">
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

 <?php
  global $db,$sugar_config;
   global $current_user;
  if(isset($_POST["submit"])) {
	  $opc = $_POST["opciones"];
	  
	   if(($opc == 'Si')) {
		   
        $sql="SELECT 
       con.assigned_user_id
			FROM crm_produccion.contacts con 
      WHERE date_format(con.birthdate,'%d-%m') = date_format(curdate(),'%d-%m') 
      	UNION 
		SELECT 
        opp.assigned_user_id
        FROM crm_produccion.opportunities opp 
        WHERE  date(opp.date_entered) = curdate()
        AND opp.sales_stage = 'creado' 
        AND opp.opportunity_type in ('crosselling','recompra','fugados') 
		UNION  
		 SELECT 
         tas.assigned_user_id
         FROM crm_produccion.tasks tas 
         JOIN crm_produccion.tasks_cstm ta_c ON ta_c.id_c = tas.id                                        
         WHERE  date(tas.date_entered) = curdate()                         
         AND ta_c.tipo_c in ('Retornos','Proximos') AND  tas.status = 'Not Started'";	  

		  $resul_db = $db->Query($sql);
		  $row = mysqli_num_rows($resul_db); 
		   echo "Cantidad de Email Enviados:".$row."<br>\n";
		 if($row > 0){
         while ($row = $db->fetchByAssoc($resul_db)) { 
		  
		   $id_user = $row['assigned_user_id'];
		  
		    $sql_email = "SELECT DISTINCT
			  u.id as id,
			  ead.email_address as email,
			  concat(u.first_name,' ',u.last_name) as usuario
					FROM users u 
			   JOIN email_addr_bean_rel ea on ea.bean_id=u.id
			   JOIN emails_email_addr_rel er on er.email_address_id = ea.email_address_id
			   JOIN emails e on e.id=er.email_id
			   JOIN emails y on y.id=er.email_id
						JOIN email_addresses ead ON ead.id = ea.email_address_id
						WHERE u.id ='".$id_user."'
						AND ea.bean_module='Users'
						AND e.deleted=0 AND ea.deleted=0 AND er.deleted=0  AND u.deleted=0 AND y.deleted=0 AND ead.deleted=0";
			  
			  $res = $db->Query($sql_email);
			  if($row = $db->fetchByAssoc($res)){
				   $correo  = $row['email'];
				}
		
          
           echo "Usuario:".$id_user."--"."Correo:".$correo."<br>\n";  
		}
		
	 }

   }
 }
 
?>
 
 
 <DIV id="plantilla" style="display:none;">
<?php
 global $current_user;

 if($current_user->user_name == 'admin'){
 
  if(isset($_POST["submit"])) {
	 $opc = $_POST["opciones"];
     //echo "opc:".$opc."<br>";
	 
   if(($opc == 'Si')) {
   unset($_POST["opciones"]);
   global $db,$sugar_config;
   global $current_user;
      include('custom/application/envio_plantilla/funciones.php');
      $templatefile = 'custom/application/envio_plantilla/Template_nuevas_oportunidades_CRM.html';
   $page = file_get_contents($templatefile);

   $title = 'Nuevas Oportunidades CRM';

     $sql_consultores = "SELECT 
            con.assigned_user_id
			FROM crm_produccion.contacts con 
            WHERE date_format(con.birthdate,'%d-%m') = 	date_format(curdate(),'%d-%m') 
           
		 UNION 
		SELECT 
        opp.assigned_user_id
        FROM crm_produccion.opportunities opp 
        WHERE  date(opp.date_entered) = curdate()
        AND opp.sales_stage = 'creado' 
        AND opp.opportunity_type in ('crosselling','recompra','fugados') 
		UNION  
		SELECT 
         tas.assigned_user_id
         FROM crm_produccion.tasks tas 
         JOIN crm_produccion.tasks_cstm ta_c ON ta_c.id_c = tas.id                                        
         WHERE  date(tas.date_entered) = curdate()                         
         AND ta_c.tipo_c in ('Retornos','Proximos') AND  tas.status = 'Not Started' LIMIT 20   
         ";
    $resul_db = $db->Query($sql_consultores);
    $row = mysqli_num_rows($resul_db); 
  if($row > 0){
   while ($row = $db->fetchByAssoc($resul_db)) {
       $id_user = $row['assigned_user_id'];
  	   $page = file_get_contents($templatefile);
	  
	  $nombre_ejecutivo   = nombre_ejecutivo($id_user);
	  $cumpleanieros      = mostrar_cumpleanio($id_user);
	  $img_oportunidades  = mostrar_imgagen_oportunidades($id_user);
	  $cross_selling      = oportunidades_venta_cross($id_user);
	  $recompra           = oportunidades_venta_recompra($id_user);
	  $fugados            = oportunidades_venta_fugados($id_user);
	  $img_tareas         = mostrar_imagen_tareas($id_user);
	  $prox_viajes        = tareas_prox_viajes($id_user);
	  $retornos           = tareas_retorno($id_user);
	  $boton_acceder      = mostrar_boton_acceder($id_user);
	  
	  $page = str_replace('{title}',     $title, $page);
	  $page = str_replace('{nombre_ejecutivo}',  $nombre_ejecutivo, $page);
	  $page = str_replace('{cumpleanios}',  $cumpleanieros, $page);
	  $page = str_replace('{img_oportunidades}', $img_oportunidades, $page);
	  $page = str_replace('{cross}',    $cross_selling, $page);
	  $page = str_replace('{recompra}',   $recompra, $page);
	  $page = str_replace('{fugados}',   $fugados, $page);
	  $page = str_replace('{img_tareas}',      $img_tareas, $page);
	  $page = str_replace('{prox_viajes}',  $prox_viajes, $page);
	  $page = str_replace('{retornos}',     $retornos, $page);
	  $page = str_replace('{boton_acceder}',    $boton_acceder, $page);
  
      echo $page;

       $sql = "SELECT DISTINCT
			  u.id as id,
			  ead.email_address as email,
			  concat(u.first_name,' ',u.last_name) as usuario
        FROM crm_produccion.users u 
			   JOIN crm_produccion.email_addr_bean_rel ea on ea.bean_id=u.id
			   JOIN crm_produccion.emails_email_addr_rel er on er.email_address_id = ea.email_address_id
			   JOIN crm_produccion.emails e on e.id=er.email_id
			   JOIN crm_produccion.emails y on y.id=er.email_id
			   JOIN crm_produccion.email_addresses ead ON ead.id = ea.email_address_id
			   WHERE u.id ='".$id_user."'
			   AND ea.bean_module='Users'
			   AND e.deleted=0 AND ea.deleted=0 AND er.deleted=0  AND u.deleted=0 AND y.deleted=0 AND ead.deleted=0";
  
  $res = $db->Query($sql);
  if($row = $db->fetchByAssoc($res)){
	   $correo  = 'aytriago@cocha.com';
	}
	
	$admin_correo = 'crm@cocha.com';
    require_once('modules/EmailTemplates/EmailTemplate.php');
    $emailTemp = new EmailTemplate();
    $emailTemp->disable_row_level_security = true;
    $emailTemp->retrieve($emailTemplateId);
    print_r($emailTemp);
    
    $html="Addtional text";
    require_once("include/SugarPHPMailer.php");
    $mail = new SugarPHPMailer();
    $mail->ClearAllRecipients();
    $mail->ClearReplyTos();
    $mail->From = $admin_correo;
    $mail->FromName = "CRM Cocha";
    $mail->Subject = from_html("CRM: Conoce tus nuevas oportunidades del día de hoy");
    $mail->AddAddress($correo, "Ejecutivos de Cocha");
    //$mail->AddAddress("jbravo@cocha.com", "Jose Bravo");
    //$mail->AddAddress("victor.flores@cocha.com", "Victor Flores");
    $mail->Body_html = from_html($emailTemp->body_html);
    $mail->Body = wordwrap($emailTemp->body_html, 900);
    $mail->Body=$page;

    $mail->IsHTML(true); //Omit or comment out this line if plain text
    $mail->prepForOutbound();
    $mail->setMailerForSystem();
    //Send the message, log if error occurs
   /*if (!$mail->Send()) {
    $GLOBALS["log"]->fatal("ERROR: Message Send Failed GOYO");
    }*/
   
   }
  
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