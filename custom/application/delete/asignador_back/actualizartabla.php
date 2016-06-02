<?php
 	
clearstatcache();
 //ini_set('error_reporting', E_ALL);
 //ini_set('display_errors', True);
 global $current_user;
 global $db, $sugar_config;

$query_2="SELECT DISTINCT 
		  o.id, 
		  o.name,
		 IFNULL(s.destino,'Sin_Destino') AS detino ,
		 date_sub(o.date_entered,INTERVAL 3 HOUR) as date_entered, 
		 s.canal AS canal,
		 va.usuario as usuario,
		 s.fecha_viaje,
		 d.agente_c as agente,
		 d.id_agente_c,
		(CASE WHEN d.priority_c = 'High' then 'Alta' else 'Baja' END) AS prioridad,
		s.destino
    FROM  opportunities o
    JOIN  opportunities_cstm d ON o.id = d.id_c
		JOIN  crm_solicitudes s ON s.id = d.crm_solicitud_id_c
    JOIN opportunities_crm_solicitudes_1_c x  on x.opportunities_crm_solicitudes_1opportunities_ida = o.id
    AND x.opportunities_crm_solicitudes_1crm_solicitudes_idb = s.id
     LEFT JOIN(SELECT 
               oa.parent_id,
               oa.before_value_string as user_anterior,
               concat(u.first_name,' ',u.last_name) as usuario
            FROM opportunities_audit oa
            JOIN users u ON oa.before_value_string = u.id
            WHERE  oa.after_value_string = 1 
               AND oa.field_name = 'assigned_user_id') AS va ON va.parent_id = o.id
    
		WHERE o.opportunity_type = 'solicitud_web' 
		AND  o.sales_stage = 'recepcionado'
		and o.deleted=0  and o.assigned_user_id = 1
		group by 1,2,3 ORDER BY o.date_entered desc";
		
  $list = $db->query($query_2);
  
  $solicitudes = array();
  
  while($opp = $db->fetchByAssoc($list)){ 
	$solicitudes[] = $opp;
  }
  
  $respuesta = array(
	 "solicitudes"  => $solicitudes,
	 "sugar_config" => $sugar_config
  );
  
  echo json_encode($respuesta); 