<?php

/*********************************************************************************
* This code was developed by:
* este proceso es usado para desactivar le proceso de workflow en un tiempo determinado.
* You can contact us at: armando ytriago
* Web: www.audox.cl
* Email: info@audox.cl
* Skype: audox.ingenieria
********************************************************************************/

$job_strings[] = 'proceso_desactiva_workflow';


function proceso_desactiva_workflow(){
		global $sugar_config, $db;
	$min = 120;
	//$min = 1;
	//$GLOBALS['log']->fatal("inicio out...");
	//este proceso esta orientado a tres horas
	$query = "
				SELECT
				  us.id_c
				FROM
				  users_cstm us
				WHERE
				  date_format(now(),'%Y-%m-%d %H:%i:%s') >= date_format(DATE_ADD(us.ultimo_acceso_c,INTERVAL ".$min." MINUTE),'%Y-%m-%d %H:%i:%s') 
				  AND us.disponible_sw_c = 1";
			//$GLOBALS['log']->fatal("Query: ".$query);
			$res = $db->query($query, true, 'Error: ');
			while($row = $db->fetchByAssoc($res)){
				$update= "update users_cstm u set u.disponible_sw_c = 0 where u.id_c = '".$row['id_c']."'";
				$resul = $db->Query($update);
			}
	//$GLOBALS['log']->fatal("out fin");
	return true;
}

?>