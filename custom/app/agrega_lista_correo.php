<?php


global $current_user;
global $db, $sugar_config;




$id_contacto = $_REQUEST['id_registro'];//
$estado = $_REQUEST['estado'];//telefono

if($estado ==1)
{
	$query = "insert into prospect_lists_prospects (id,prospect_list_id,related_id,related_type,date_modified,deleted)
values('$id_contacto','c396117f-eee0-e6b1-35f1-54f467ad3317','$id_contacto','Contacts',now(),0)
					  ";
				$res = $db->Query($query);
				echo "Contacto Agregado a la lista";
}else
{
		$query = "update prospect_lists_prospects set deleted=1
					WHERE  related_id='".$id_contacto."' and deleted=0  ";
				$res = $db->Query($query);
				echo "Contacto quitado de la lista";
}
	
	
