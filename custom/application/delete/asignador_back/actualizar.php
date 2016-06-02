<?php
clearstatcache();
// ini_set('error_reporting', E_ALL);
//ini_set('display_errors', True);
global $current_user;
global $db, $sugar_config;

switch ($_GET['opc'])
{	 
	case "1" :
		/*$query = "select u.id,concat(u.last_name,' ',u.first_name) as usuario,count(o.id) as cantidad
				  from users u
				  join users_cstm ud on u.id = ud.id_c and ud.sucursal_c = 'CONTACT' and ud.disponible_sw_c = 0
				  left join opportunities o on o.assigned_user_id = ud.id_c and o.deleted=0 and o.sales_stage in ('reservado','asignado', 'recepcionado')
				  where u.status='Active' 
				   group by u.first_name,u.last_name order by usuario";*/
		$query = "   SELECT   u.id
							   , concat(u.last_name, ' ', u.first_name) AS usuario
							   , SUM(CASE WHEN date(date_assigned_c) = date(now()) THEN 1 ELSE 0 END) AS cantidad
							   , SUM(CASE WHEN date(date_assigned_c) <= date(now()) AND o.sales_stage IN ('recepcionado', 'asignado') THEN 1 ELSE 0 END) AS nuevas
					  FROM     users u
							   JOIN users_cstm ud ON u.id = ud.id_c AND ud.sucursal_c = 'CONTACT' AND ud.disponible_sw_c = 0
							   JOIN opportunities o ON o.assigned_user_id = u.id
							   JOIN opportunities_cstm opc ON opc.id_c = o.id AND o.deleted = 0
					  WHERE    u.status = 'Active' AND u.deleted = 0 AND opportunity_type = 'solicitud_web' AND ud.disponible_sw_c = 0
					  GROUP BY u.first_name, u.last_name";
			
		$query_db = $db->Query($query);

		$respuestas = array();
		
		while ( $row = $db->fetchByAssoc($query_db) ) 
		{
			$respuestas[] = $row;
		}
		
		echo json_encode($respuestas);
    break;
	  
	case "2" :
		/*$query = "select u.id,concat(u.last_name,' ',u.first_name) as usuario,case WHEN ud.sucursal_c = 'EXTERNOS'
				  THEN 'Ext' 
				  ELSE 
				  'no'
				END
				 AS tipo ,
				 case WHEN ud.sucursal_c = 'EXTERNOS'
				  THEN 0 
				  ELSE count(o.id) 
				END as cantidad 
					from users u
				  join users_cstm ud on u.id = ud.id_c and ud.sucursal_c in ('EXTERNOS')
					left join opportunities o on o.assigned_user_id = ud.id_c and o.deleted=0 and o.sales_stage in ('recepcionado','reservado','asignado')
				  where u.status='Active' 
					group by u.first_name,u.last_name order by usuario";*/
					
		$query = " SELECT   u.id
							 , concat(u.last_name, ' ', u.first_name) AS usuario
							 , CASE WHEN ud.sucursal_c = 'EXTERNOS' THEN 'Ext' ELSE 'no' END AS tipo
							 , CASE WHEN ud.sucursal_c = 'EXTERNOS' THEN 0 ELSE count(o.id) END AS cantidad
							 , SUM(CASE WHEN date(date_assigned_c) = date(now()) THEN 1 ELSE 0 END) AS cantidad
							 , SUM(CASE WHEN date(date_assigned_c) <= date(now()) AND o.sales_stage IN ('recepcionado', 'asignado') THEN 1 ELSE 0 END) AS nuevas
					FROM     users u
							 JOIN users_cstm ud ON u.id = ud.id_c
							 LEFT JOIN opportunities o ON o.assigned_user_id = ud.id_c AND o.deleted = 0 AND opportunity_type = 'solicitud_web'
							 LEFT JOIN opportunities_cstm opc ON opc.id_c = o.id AND o.deleted = 0
					WHERE    u.status = 'Active' AND ud.sucursal_c IN ('EXTERNOS')
					GROUP BY u.first_name, u.last_name
					ORDER BY usuario";
		$query_db = $db->Query($query);

		$respuestas = array();
		
		while ( $row = $db->fetchByAssoc($query_db) ) 
		{
			$respuestas[] = $row;
		}
		
		echo json_encode($respuestas);
    break;
       
	case "3" :
		$query = "SELECT   u.id
						 , concat(u.last_name, ' ', u.first_name) AS usuario
						 , CASE WHEN ud.sucursal_c = 'EXTERNOS' THEN 'Ext' ELSE 'no' END AS tipo
						 , CASE WHEN ud.sucursal_c = 'EXTERNOS' THEN 0 ELSE count(o.id) END AS cantidad_ext
						 , SUM(CASE WHEN date(date_assigned_c) = date(now()) THEN 1 ELSE 0 END) AS cantidad
						 , SUM(CASE WHEN date(date_assigned_c) <= date(now()) AND o.sales_stage IN ('recepcionado', 'asignado') THEN 1 ELSE 0 END) AS nuevas
				FROM     users u
						 JOIN users_cstm ud ON u.id = ud.id_c AND ud.favorito_c = 1
						 LEFT JOIN opportunities o ON o.assigned_user_id = ud.id_c
						 LEFT JOIN opportunities_cstm opc ON opc.id_c = o.id AND o.deleted = 0
				WHERE    u.status = 'Active'
				GROUP BY u.first_name, u.last_name
				ORDER BY usuario";
		$query_db = $db->Query($query);

		$respuestas = array();
		
		while ( $row = $db->fetchByAssoc($query_db) ) 
		{
			$respuestas[] = $row;
		}
		
		echo json_encode($respuestas);
	 break;
  
     case "4" :
		$query = " SELECT u.id, concat(u.last_name,' ',u.first_name) usuario, ud.sucursal_c,
				  SUM(CASE WHEN  DATE(date_assigned_c) =  DATE(now())  THEN 1 ELSE 0 END) AS cantidad,
				  SUM(CASE WHEN  DATE(date_assigned_c) <= DATE(now())  AND o.sales_stage in ('recepcionado','asignado') THEN 1 ELSE 0 END) AS nuevas
				FROM users u
				JOIN  users_cstm ud       ON u.id = ud.id_c  and ud.disponible_sw_c = 1 
				LEFT JOIN opportunities o ON o.assigned_user_id = ud.id_c 
				LEFT JOIN opportunities_cstm opc ON opc.id_c =o.id and o.deleted=0 
				WHERE u.status='Active' 
				AND sucursal_c='WORKFLOW' 
				GROUP BY 1,2,3 ORDER BY 2";
		  
		$query_db = $db->Query($query);

		$respuestas = array();
		
		while ( $row = $db->fetchByAssoc($query_db) ) 
		{
			$respuestas[] = $row;
		}
		
		echo json_encode($respuestas);
	 break;
}   