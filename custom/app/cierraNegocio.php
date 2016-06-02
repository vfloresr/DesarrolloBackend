<?php 

 //ini_set('error_reporting', E_ALL);
 //ini_set('display_errors', True);

  global $current_user;
  global $db, $sugar_config;




  $id_registro = $_REQUEST['id_registro'] ;
  $estado = $_REQUEST['estado'] ;
  $negocio = new CRM_negocios();
  $negocio->retrieve($id_registro);
  $negocio->estado_c=$estado;
  $negocio->save();
  echo "Negocio Cerrado";
 
?>

