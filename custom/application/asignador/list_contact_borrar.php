<?php
  $query_2="SELECT o.id, IFNULL(s.destino,'Sin Destino') AS detino ,o.date_entered, o.lead_source AS canal,o.sales_stage AS etapa, s.fecha_viaje,s.agente,priority_c
			FROM  opportunities o
			JOIN opportunities_cstm d ON o.id = d.id_c
			-- JOIN leads_opportunities_1_c rl ON rl.leads_opportunities_1opportunities_idb = d.id_c
			-- JOIN leads l ON l.id = rl.leads_opportunities_1leads_ida
			JOIN opportunities_crm_solicitudes_1_c os ON os.opportunities_crm_solicitudes_1opportunities_ida= d.id_c 
			JOIN crm_solicitudes s ON s.id = os.opportunities_crm_solicitudes_1crm_solicitudes_idb
			WHERE o.opportunity_type = 'solicitud_web' 
			AND  o.sales_stage = 'recepcionado'
			ORDER BY s.destino,d.priority_c";	
$list = $db->Query($query_2);
//$negocio = new Opportunity();
//$list = $negocio->get_full_list("opportunities.date_created desc", "opportunities.assigned_user_id = '1' and opportunities.sales_stage='RecepcionSolicitud' ",true);

//se listan lo usuario que que correspondan a cada select de la vista            
$usuarios = new User();
$list_contact =   $usuarios->get_full_list("users.full_name", "users_cstm.sucursal_c = 'CONTACT' and users.status='Active'  ",true);
$list_workflow =  $usuarios->get_full_list("users.full_name", "users.status='Active'  ",true);
$list_externos =  $usuarios->get_full_list("users.last_name", " (users_cstm.sucursal_c = 'EXTERNOS' OR users_cstm.sucursal_c = 'PROTOTIPO') and users.status='Active'  ",true);
$list_favoritos = $usuarios->get_full_list("users.last_name", "users_cstm.favorito_c = 1 and users.status='Active' ",true);
  ?>