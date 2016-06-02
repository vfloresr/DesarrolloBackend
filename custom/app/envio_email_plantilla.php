<?php 

 //ini_set('error_reporting', E_ALL);
 //ini_set('display_errors', True);

  global $current_user;
  global $db, $sugar_config;
  $encuesta_c='';



    $id_registro = $_REQUEST['id_registro'] ;
   $tipo = $_REQUEST['tipo'] ;
    
 // $comentarios = $_REQUEST['comentarios'] ;
   $texto = $_REQUEST['texto_hide'] ;
   $link_pdf = $_REQUEST['id_product'] ;
   $pdf_joomla = $_REQUEST['pdfjoomla'] ;
   $id_producto_joomla = $_REQUEST['id_producto_joomla'] ;
   $asunto = $_REQUEST['asunto'] ;



   if($link_pdf=='') $link_pdf=$pdf_joomla;

//die();


//Get the uploaded file information
  $name_file =basename($_FILES['name_file']['name']);
//copy the temp. uploaded file to uploads folder
  $path_of_uploaded_file = "upload/".$name_file;
  $tmp_path = $_FILES["name_file"]["tmp_name"];
 
if(is_uploaded_file($tmp_path))
{
  if(!copy($tmp_path,$path_of_uploaded_file))
  {
    $errors .= '\n error while copying the uploaded file';
  }
}



  $oportunidad = new Opportunity();
  $oportunidad->retrieve($id_registro);

$nombre_producto = $oportunidad->aos_products_opportunities_1_name;

  
  $query2 = "SELECT ema.email_address,con.first_name,con.last_name,bean_module 
			FROM leads con
			inner join email_addr_bean_rel em_rel on em_rel.bean_id = con.id
			inner join email_addresses ema on ema.id= em_rel.email_address_id
			inner join leads_opportunities_1_c op_c on op_c.leads_opportunities_1leads_ida=con.id
			where em_rel.bean_module='Leads' 
			and con.deleted=0 
			and op_c.deleted=0
			and op_c.leads_opportunities_1opportunities_idb = '".$id_registro."'
			and ema.deleted=0 
			and em_rel.deleted=0";
			
        $res2 = $db->Query($query2);
        if($row2 = $db->fetchByAssoc($res2)){
          $correo= $row2['email_address'];
          $nombre_contacto= $row2['first_name']." ".$row2['last_name'];

        }
  
  $usuario = new User();
  $usuario->retrieve($oportunidad->assigned_user_id);
  
 $texto =   (str_replace('$user_fullname', ucwords($usuario->full_name), $texto));
  $texto =   (str_replace('$user_telefono', $usuario->phone_work, $texto));
  $texto =   (str_replace('$user_email', $usuario->email1, $texto));
  $texto =   (str_replace('$user_sucursal', $usuario->departament, $texto));
  $texto =   (str_replace('$user_cargo', $usuario->title, $texto));
  $texto =   (str_replace('$contact_name', ucwords($nombre_contacto), $texto));
$texto =   (str_replace('$product_name', $nombre_producto, $texto));
$texto =   (str_replace('<br><br><br>', "", $texto));
$texto =   (str_replace('<p> </p>', "", $texto));



  $texto =   (str_replace('$comentarios', $comentarios, $texto));

  $emailObj = new Email();
  $defaults = $emailObj->getSystemDefaultEmail();
  $mail = new SugarPHPMailer();
  $mail->setMailerForSystem();
  $mail->From = $usuario->email1;//$usuario->email1
  $mail->FromName = $usuario->full_name;//$usuario->full_name
  $mail->ClearAllRecipients();
  $mail->ClearReplyTos();
  $mail->Subject=from_html( $asunto);
  $mail->Body=$texto;
  $mail->AltBody=from_html($texto);
  $mail->prepForOutbound();
  #############################################
  #   Codigo Jaime 14-11-15
  #############################################
  $mail->AddAddress($correo);
  $mail->AddBCC($usuario->email1);
  #############################################
  #   fin codigo
  #############################################

 echo $file_name  = $name_file;
  $location   = "upload/".$name_file;
  $mime_type  = mime_content_type("upload/".$name_file);
  // Add attachment to email
  $mail->AddAttachment($location, $file_name, 'base64', $mime_type);

echo "<br>". $link_pdf;


if($link_pdf != ''){
$ch = curl_init(trim($link_pdf));
$fp = fopen('upload/programa.pdf', 'wb');
curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_exec($ch);
curl_close($ch);
fclose($fp);


  $file_name2  = "programa.pdf";
  $location2   = "upload/programa.pdf";
  $mime_type2  = mime_content_type("upload/programa.pdf");
  // Add attachment to email
  $mail->AddAttachment($location2, $file_name2, 'base64', $mime_type2);

}

  // now create email
  if (@$mail->Send()) {
    
    echo "Mail Enviado";
    unlink($location);
    unlink($location2);

     $email = new Email();
     $email->name = $asunto." *".$id_producto_joomla."*";
     $email->type='out';
     $email->status='sent';
     $email->intent='pick';
     $email->from_addr=$usuario->full_name."<".$usuario->email1.">";
     $email->to_addrs=$nombre_contacto."<".$correo.">";
      $email->description_html=$texto;
      $email->assigned_user_id=$usuario->id;
      $email->assigned_user_name=$usuario->user_name;
      $email->from_name=$usuario->full_name;
      $email->parent_name=$nombre_contacto;
      $email->date_sent=TimeDate::getInstance()->getNow(true)->modify("+0 days")->asDb();
      $email->parent_type='Opportunities';
      $email->parent_id=$id_registro;
   
      $email->from_addr_name=$usuario->full_name;

      $email->save();





  }else echo "error al enviar";

  SugarApplication::appendErrorMessage($emailtemplate->subject);





?>
<script>
      window.parent.close()
      //window.opener.location.reload();
</script>
