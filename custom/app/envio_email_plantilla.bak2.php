<?php 

 //ini_set('error_reporting', E_ALL);
 //ini_set('display_errors', True);

  global $current_user;
  global $db, $sugar_config;
  $encuesta_c='';



   $id_registro = $_REQUEST['id_registro'] ;
   $tipo = $_REQUEST['tipo'] ;
    
  $comentarios = $_REQUEST['comentarios'] ;
  $id_plantilla = $_REQUEST['id_plantilla_select'] ;
  if($id_plantilla=='' or $id_plantilla=='Seleccione')$id_plantilla="cocha.html";
  $texto = file_get_contents ('custom/app/templates/'.$id_plantilla) ;
  //print_r($text);
  $texto =   (str_replace("*comentarios*", $comentarios, $texto));



if($tipo =='negocio'){
  $negocio = new CRM_negocios();
  $negocio->retrieve($id_registro);
  $nombre_registro = $negocio->name;
  $nombre_asunto_email = 'Cross-Selling';
  $contact_obj = $negocio->get_linked_beans('crm_negocios_contacts','Contact'); 

  foreach ( $contact_obj as $contacto ) { 
    if($contacto->tipo_c == 'Comprador' or $contacto->tipo_c == ''){ 
        $nombre_contacto=$contacto->full_name;
        $email_contacto=$contacto->email1;

    }  

  }
  $usuario = new User();
  $usuario->retrieve($negocio->assigned_user_id);
 

  $id_plantilla='2490ce98-ff32-5293-bd2d-54ad4871c318';
}else if($tipo =='tarea'){
    $tarea = new Task();
    $tarea->retrieve($id_registro);
    $nombre_registro = $tarea->name;
    $nombre_asunto_email = $tarea->tipo_c;
    $encuesta_c=$tarea->encuesta_c;
    $id_contacto = $tarea->contact_id;
    $contacto = new Contact();
    $contacto->retrieve($id_contacto);
    $nombre_contacto= $contacto->full_name;
    $email_contacto= $contacto->email1;
    $usuario = new User();
    $usuario->retrieve($tarea->assigned_user_id);

    
    if($tarea->tipo_c == 'CUMPLEANOS') $id_plantilla='726aaf18-42d7-ab85-0f7d-549aca4d3257';
    if($tarea->tipo_c == 'RETORNO') $id_plantilla='183da926-7ee4-e9c9-15b6-54a9f476951f';
    if($tarea->tipo_c == 'ANTERIOR') $id_plantilla='cf5769f3-1f14-c544-b6d2-54a69cb83050';
    if($tarea->tipo_c == 'MAILING') $id_plantilla='da824a69-b840-c9f5-2e00-54b6e491fab3';


}

else if($tipo =='postventa'){
    $tarea = new CRM_postventa();
    $tarea->retrieve($id_registro);
    $nombre_registro = $tarea->name;
    $nombre_asunto_email = "Recordatorio Viaje";
   
    $id_contacto = $tarea->contact_id_c;
    
    $contacto = new Contact();
    $contacto->retrieve($id_contacto);
    $nombre_contacto= $contacto->full_name;
    $email_contacto= $contacto->email1;
    $usuario = new User();
    $usuario->retrieve($tarea->assigned_user_id);

     $id_plantilla='726aaf18-42d7-ab85-0f7d-549aca4d3257';

}

//echo $id_plantilla;

  $emailtemplate = new EmailTemplate();
  $emailtemplate->retrieve($id_plantilla);
  $emailtemplate->body_html=str_replace('$contact_name', $nombre_contacto, $emailtemplate->body_html);
  $emailtemplate->body_html=str_replace('$contact_user_full_name', $usuario->full_name, $emailtemplate->body_html);
  $emailtemplate->body_html=str_replace('$task_encuesta_c', $encuesta_c, $emailtemplate->body_html);




  
  $emailObj = new Email();
  $defaults = $emailObj->getSystemDefaultEmail();
  $mail = new SugarPHPMailer();
  $mail->setMailerForSystem();
  $mail->From = $usuario->email1;
  $mail->FromName = $usuario->full_name;
  $mail->ClearAllRecipients();
  $mail->ClearReplyTos();
  $mail->Subject=from_html( $emailtemplate->subject);
 //  $mail->Subject=from_html( "hola");
  $mail->Body=$texto;
  $mail->AltBody=from_html($texto);
  $mail->prepForOutbound();
  $mail->AddAddress($email_contacto);

  
  
  // now create email
  if (@$mail->Send()) {
    
    echo "Mail Enviado";
    if($tipo=='negocio'){
      
      $email = new Email();
      $email->name = "Email Automatico ".$nombre_asunto_email;
      $email->type='out';
      $email->status='sent';
      $email->intent='pick';
      $email->from_addr=$usuario->full_name."<".$usuario->email1.">";
      $email->to_addrs=$nombre_contacto."<".$email_contacto.">";
      $email->description_html=$texto;
      $email->assigned_user_id=$usuario->id;
      $email->assigned_user_name=$usuario->user_name;
      $email->from_name=$usuario->full_name;
      $email->parent_name=$nombre_registro;
      $email->date_sent=TimeDate::getInstance()->getNow(true)->modify("+0 days")->asDb();
      $email->parent_type='CRM_negocios';
      $email->parent_id=$id_registro;
   
      $email->from_addr_name=$usuario->full_name;

      $email->save();
    }else
    if($tipo=='tarea' ){
      $email = new Email();
      $email->name = "Email Automatico ".$nombre_asunto_email;
      $email->type='out';
      $email->status='sent';
      $email->intent='pick';
      $email->from_addr=$usuario->full_name."<".$usuario->email1.">";
      $email->to_addrs=$nombre_contacto."<".$email_contacto.">";
      $email->description_html=$texto;
      $email->assigned_user_id=$usuario->id;
      $email->assigned_user_name=$usuario->user_name;
      $email->from_name=$usuario->full_name;
      $email->parent_name=$nombre_registro;
      $email->date_sent=TimeDate::getInstance()->getNow(true)->modify("+0 days")->asDb();
      $email->parent_type='Tasks';
      $email->parent_id=$id_registro;
   
      $email->from_addr_name=$usuario->full_name;

      $email->save();



    }else 
    if($tipo=='postventa' ){

      $email = new Email();
      $email->name = "Email Automatico ".$nombre_asunto_email;
      $email->type='out';
      $email->status='sent';
      $email->intent='pick';
      $email->from_addr=$usuario->full_name."<".$usuario->email1.">";
      $email->to_addrs=$nombre_contacto."<".$email_contacto.">";
      $email->description_html=$texto;
      $email->assigned_user_id=$usuario->id;
      $email->assigned_user_name=$usuario->user_name;
      $email->from_name=$usuario->full_name;
      $email->parent_name=$nombre_registro;
      $email->date_sent=TimeDate::getInstance()->getNow(true)->modify("+0 days")->asDb();
      $email->parent_type='CRM_postventa';
      $email->parent_id=$id_registro;
   
      $email->from_addr_name=$usuario->full_name;

      $email->save();



    } 
  }else echo "error al enviar";

  SugarApplication::appendErrorMessage($emailtemplate->subject);





?>
<script>
window.close()
            window.opener.location.reload();
</script>
