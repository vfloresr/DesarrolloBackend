<?php

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class SincronizaPropectos{

 function ActualizaProspectos (&$bean, $event, $arguments) {
		 global $db, $sugar_config,$current_user;
        // before_save
        		// prospectos
				//busqueda del prospecto
		if ($bean->email1 != ''){		
					$sql2 =	"SELECT ema.bean_id AS prospecto
							FROM   email_addr_bean_rel ema
								   JOIN email_addresses ema1 ON ema.email_address_id = ema1.id AND ema1.email_address = '".$bean->email1."'
							WHERE  ema.bean_module = 'Prospects' AND ema.deleted = 0";
					
						  $resultado2 = $db->query($sql2);
						  $row2 = $db->fetchByAssoc($resultado2);
					
			$prospecto = new Prospect();
			
			$prospecto->retrieve($row2['prospecto']);
			
			if (isset($prospecto->id)){
				$prospecto->last_name=( $bean->last_name != '' ) ? $bean->last_name : $prospecto->last_name;
				$prospecto->first_name=( $bean->first_name != '' ) ? $bean->first_name : $prospecto->first_name;
				$prospecto->phone_fax=( $bean->phone_fax != '' ) ? $bean->phone_fax : $prospecto->phone_fax;
				$prospecto->phone_home= ( $bean->phone_home != '' ) ? $bean->phone_home : $prospecto->phone_home;
				$prospecto->email1= ( $bean->email1 != '' ) ? $bean->email1 : $prospecto->email1;
				$prospecto->ip_registro_c = ( $bean->ip_registro_c != '' ) ? $bean->ip_registro_c : $prospecto->ip_registro_c;
				$prospecto->recibir_notificaciones_c = ( $bean->recibir_notificaciones_c != '' ) ? $bean->recibir_notificaciones_c : $prospecto->recibir_notificaciones_c;
				$prospecto->politicas_privacidad_c = ( $bean->politicas_privacidad_c != '' ) ? $bean->politicas_privacidad_c : $prospecto->politicas_privacidad_c;
				$prospecto->save();
			}else{
				$prospecto->last_name=$bean->last_name;
				$prospecto->first_name=$bean->first_name;
				$prospecto->phone_fax=$bean->phone_fax;
				$prospecto->phone_home=$bean->phone_home;
				$prospecto->email1=$bean->email1;
				$prospecto->ip_registro_c =$bean->ip_registro_c;
				$prospecto->recibir_notificaciones_c = $bean->recibir_notificaciones_c;
				$prospecto->politicas_privacidad_c = $bean->politicas_privacidad_c;
				$prospecto->save();
			}
		}
}

}
?>