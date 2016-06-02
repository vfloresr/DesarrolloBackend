<?php 

 // ini_set('error_reporting', E_ALL);
 // ini_set('display_errors', True);

  global $current_user;
  global $db, $sugar_config;

   $id_registro = trim($_REQUEST['id_registro']);//asunto
  $tipo = trim($_REQUEST['tipo']);//asunto
  //$estado = trim($_GET['estado']);//asunto
if($tipo=='postventa'){

    $tarea = new CRM_postventa();
    $relacion = 'Tasks';
    $tarea->retrieve($id_registro);
    $id_contacto = $tarea->contact_id;
    $contacto = new Contact();
    $contacto->retrieve($id_contacto);
    $nombre_modulo=$tarea->name;

    $email_contacto = $contacto->email1;
    $full_name = $contacto->full_name;


  }else

if($tipo=='tarea'){

    $tarea = new Task();
    $relacion = 'Tasks';
    $tarea->retrieve($id_registro);
    $id_contacto = $tarea->contact_id;
    $contacto = new Contact();
    $contacto->retrieve($id_contacto);
    $nombre_modulo=$tarea->name;

    $email_contacto = $contacto->email1;
    $full_name = $contacto->full_name;


  }else{
    $negocio = new CRM_negocios();
    $negocio->retrieve($id_registro);
    $relacion = 'CRM_negocios';
    $nombre_modulo=$negocio->name;
    $contact_obj = $negocio->get_linked_beans('crm_negocios_contacts','Contact'); 


    foreach ( $contact_obj as $contacto ) { 
          if($contacto->tipo_c == 'Comprador' or $contacto->tipo_c == '')
          { 
              $id_contacto= $contacto->id;
              $email_contacto= $contacto->email1;
              $full_name = $contacto->full_name;

          } 
    }


  }

  

  echo "$full_name <$email_contacto>&$id_contacto&$full_name&$nombre_modulo";
//echo "ddd";
?>

