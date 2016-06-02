<?php 
 //WARNING: The contents of this file are auto-generated


 // created: 2015-11-10 20:44:39
$dictionary['Opportunity']['fields']['jjwg_maps_geocode_status_c']['inline_edit']=1;

 

 // created: 2016-05-10 12:46:40
$dictionary['Opportunity']['fields']['sales_stage']['len']=100;
$dictionary['Opportunity']['fields']['sales_stage']['inline_edit']=true;
$dictionary['Opportunity']['fields']['sales_stage']['massupdate']='1';
$dictionary['Opportunity']['fields']['sales_stage']['options']='crm_etapa_ventas';
$dictionary['Opportunity']['fields']['sales_stage']['comments']='Indication of progression towards closure';
$dictionary['Opportunity']['fields']['sales_stage']['merge_filter']='disabled';

 

 // created: 2015-11-27 11:59:29
$dictionary['Opportunity']['fields']['resumen_json_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['resumen_json_c']['labelValue']='Resumen JSON';

 

 // created: 2015-11-27 11:23:23
$dictionary['Opportunity']['fields']['lead_source']['len']=100;
$dictionary['Opportunity']['fields']['lead_source']['audited']=true;
$dictionary['Opportunity']['fields']['lead_source']['inline_edit']=true;
$dictionary['Opportunity']['fields']['lead_source']['comments']='Source of the opportunity';
$dictionary['Opportunity']['fields']['lead_source']['merge_filter']='disabled';

 

// created: 2015-10-16 18:40:50
$dictionary["Opportunity"]["fields"]["prospects_opportunities_1"] = array (
  'name' => 'prospects_opportunities_1',
  'type' => 'link',
  'relationship' => 'prospects_opportunities_1',
  'source' => 'non-db',
  'module' => 'Prospects',
  'bean_name' => 'Prospect',
  'vname' => 'LBL_PROSPECTS_OPPORTUNITIES_1_FROM_PROSPECTS_TITLE',
  'id_name' => 'prospects_opportunities_1prospects_ida',
);
$dictionary["Opportunity"]["fields"]["prospects_opportunities_1_name"] = array (
  'name' => 'prospects_opportunities_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_PROSPECTS_OPPORTUNITIES_1_FROM_PROSPECTS_TITLE',
  'save' => true,
  'id_name' => 'prospects_opportunities_1prospects_ida',
  'link' => 'prospects_opportunities_1',
  'table' => 'prospects',
  'module' => 'Prospects',
  'rname' => 'account_name',
);
$dictionary["Opportunity"]["fields"]["prospects_opportunities_1prospects_ida"] = array (
  'name' => 'prospects_opportunities_1prospects_ida',
  'type' => 'link',
  'relationship' => 'prospects_opportunities_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_PROSPECTS_OPPORTUNITIES_1_FROM_OPPORTUNITIES_TITLE',
);


// created: 2015-11-11 18:22:41
$dictionary["Opportunity"]["fields"]["crm_negocios_opportunities"] = array (
  'name' => 'crm_negocios_opportunities',
  'type' => 'link',
  'relationship' => 'crm_negocios_opportunities',
  'source' => 'non-db',
  'module' => 'crm_negocios',
  'bean_name' => 'crm_negocios',
  'vname' => 'LBL_CRM_NEGOCIOS_OPPORTUNITIES_FROM_CRM_NEGOCIOS_TITLE',
  'id_name' => 'crm_negocios_opportunitiescrm_negocios_ida',
);
$dictionary["Opportunity"]["fields"]["crm_negocios_opportunities_name"] = array (
  'name' => 'crm_negocios_opportunities_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_CRM_NEGOCIOS_OPPORTUNITIES_FROM_CRM_NEGOCIOS_TITLE',
  'save' => true,
  'id_name' => 'crm_negocios_opportunitiescrm_negocios_ida',
  'link' => 'crm_negocios_opportunities',
  'table' => 'crm_negocios',
  'module' => 'crm_negocios',
  'rname' => 'name',
);
$dictionary["Opportunity"]["fields"]["crm_negocios_opportunitiescrm_negocios_ida"] = array (
  'name' => 'crm_negocios_opportunitiescrm_negocios_ida',
  'type' => 'link',
  'relationship' => 'crm_negocios_opportunities',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_CRM_NEGOCIOS_OPPORTUNITIES_FROM_OPPORTUNITIES_TITLE',
);


 // created: 2015-11-10 20:44:39
$dictionary['Opportunity']['fields']['numero_negocio_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['numero_negocio_c']['labelValue']='Numero negocio';

 

 // created: 2016-05-17 00:18:01
$dictionary['Opportunity']['fields']['email_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['email_c']['labelValue']='email';

 


$dictionary['Opportunity']['fields']['SecurityGroups'] = array (
  	'name' => 'SecurityGroups',
    'type' => 'link',
	'relationship' => 'securitygroups_opportunities',
	'module'=>'SecurityGroups',
	'bean_name'=>'SecurityGroup',
    'source'=>'non-db',
	'vname'=>'LBL_SECURITYGROUPS',
);






 // created: 2016-05-28 10:38:03
$dictionary['Opportunity']['fields']['tipo_cierre_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['tipo_cierre_c']['labelValue']='tipo cierre';

 

$dictionary["Opportunity"]["fields"]["aos_quotes"] = array (
  'name' => 'aos_quotes',
    'type' => 'link',
    'relationship' => 'opportunity_aos_quotes',
    'module'=>'AOS_Quotes',
    'bean_name'=>'AOS_Quotes',
    'source'=>'non-db',
);

$dictionary["Opportunity"]["relationships"]["opportunity_aos_quotes"] = array (
	'lhs_module'=> 'Opportunities', 
	'lhs_table'=> 'opportunities', 
	'lhs_key' => 'id',
	'rhs_module'=> 'AOS_Quotes', 
	'rhs_table'=> 'aos_quotes', 
	'rhs_key' => 'opportunity_id',
	'relationship_type'=>'one-to-many',
);

$dictionary["Opportunity"]["fields"]["aos_contracts"] = array (
  'name' => 'aos_contracts',
    'type' => 'link',
    'relationship' => 'opportunity_aos_contracts',
    'module'=>'AOS_Contracts',
    'bean_name'=>'AOS_Contracts',
    'source'=>'non-db',
);

$dictionary["Opportunity"]["relationships"]["opportunity_aos_contracts"] = array (
	'lhs_module'=> 'Opportunities', 
	'lhs_table'=> 'opportunities', 
	'lhs_key' => 'id',
	'rhs_module'=> 'AOS_Contracts', 
	'rhs_table'=> 'aos_contracts', 
	'rhs_key' => 'opportunity_id',
	'relationship_type'=>'one-to-many',
);



 // created: 2015-11-10 20:44:39
$dictionary['Opportunity']['fields']['productos_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['productos_c']['labelValue']='Productos';

 

 // created: 2015-11-12 19:22:11
$dictionary['Opportunity']['fields']['tipo_hotel_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['tipo_hotel_c']['labelValue']='tipo hotel';

 

// created: 2015-10-20 11:13:27
$dictionary["Opportunity"]["fields"]["tasks_opportunities_1"] = array (
  'name' => 'tasks_opportunities_1',
  'type' => 'link',
  'relationship' => 'tasks_opportunities_1',
  'source' => 'non-db',
  'module' => 'Tasks',
  'bean_name' => 'Task',
  'vname' => 'LBL_TASKS_OPPORTUNITIES_1_FROM_TASKS_TITLE',
  'id_name' => 'tasks_opportunities_1tasks_ida',
);
$dictionary["Opportunity"]["fields"]["tasks_opportunities_1_name"] = array (
  'name' => 'tasks_opportunities_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_TASKS_OPPORTUNITIES_1_FROM_TASKS_TITLE',
  'save' => true,
  'id_name' => 'tasks_opportunities_1tasks_ida',
  'link' => 'tasks_opportunities_1',
  'table' => 'tasks',
  'module' => 'Tasks',
  'rname' => 'name',
);
$dictionary["Opportunity"]["fields"]["tasks_opportunities_1tasks_ida"] = array (
  'name' => 'tasks_opportunities_1tasks_ida',
  'type' => 'link',
  'relationship' => 'tasks_opportunities_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_TASKS_OPPORTUNITIES_1_FROM_OPPORTUNITIES_TITLE',
);


 // created: 2015-11-26 17:10:23
$dictionary['Opportunity']['fields']['opportunity_type']['len']=100;
$dictionary['Opportunity']['fields']['opportunity_type']['inline_edit']=true;
$dictionary['Opportunity']['fields']['opportunity_type']['comments']='Type of opportunity (ex: Existing, New)';
$dictionary['Opportunity']['fields']['opportunity_type']['merge_filter']='disabled';
$dictionary['Opportunity']['fields']['opportunity_type']['default']='solicitud_web';

 

 // created: 2015-11-10 20:44:39
$dictionary['Opportunity']['fields']['motivo_cierre_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['motivo_cierre_c']['labelValue']='motivo cierre';

 

 // created: 2015-11-27 12:00:04
$dictionary['Opportunity']['fields']['description']['audited']=true;
$dictionary['Opportunity']['fields']['description']['inline_edit']=true;
$dictionary['Opportunity']['fields']['description']['comments']='Full text of the note';
$dictionary['Opportunity']['fields']['description']['merge_filter']='disabled';

 

 // created: 2016-05-24 19:39:00
$dictionary['Opportunity']['fields']['log_asignacion_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['log_asignacion_c']['labelValue']='log asignacion';

 

 // created: 2015-11-19 14:48:23
$dictionary['Opportunity']['fields']['next_step']['audited']=true;
$dictionary['Opportunity']['fields']['next_step']['inline_edit']=true;
$dictionary['Opportunity']['fields']['next_step']['comments']='The next step in the sales process';
$dictionary['Opportunity']['fields']['next_step']['merge_filter']='disabled';

 

 // created: 2016-05-25 19:59:34
$dictionary['Opportunity']['fields']['resumen_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['resumen_c']['labelValue']='Resumen';

 

 // created: 2016-05-17 00:18:17
$dictionary['Opportunity']['fields']['rut_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['rut_c']['labelValue']='rut';

 

// created: 2015-10-19 17:54:45
$dictionary["Opportunity"]["fields"]["leads_opportunities_1"] = array (
  'name' => 'leads_opportunities_1',
  'type' => 'link',
  'relationship' => 'leads_opportunities_1',
  'source' => 'non-db',
  'module' => 'Leads',
  'bean_name' => 'Lead',
  'vname' => 'LBL_LEADS_OPPORTUNITIES_1_FROM_LEADS_TITLE',
  'id_name' => 'leads_opportunities_1leads_ida',
);
$dictionary["Opportunity"]["fields"]["leads_opportunities_1_name"] = array (
  'name' => 'leads_opportunities_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_LEADS_OPPORTUNITIES_1_FROM_LEADS_TITLE',
  'save' => true,
  'id_name' => 'leads_opportunities_1leads_ida',
  'link' => 'leads_opportunities_1',
  'table' => 'leads',
  'module' => 'Leads',
  'rname' => 'name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["Opportunity"]["fields"]["leads_opportunities_1leads_ida"] = array (
  'name' => 'leads_opportunities_1leads_ida',
  'type' => 'link',
  'relationship' => 'leads_opportunities_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_LEADS_OPPORTUNITIES_1_FROM_OPPORTUNITIES_TITLE',
);


 // created: 2015-11-12 11:08:08
$dictionary['Opportunity']['fields']['crm_solicitud_id_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['crm_solicitud_id_c']['labelValue']='crm solicitud id';

 

 // created: 2015-11-26 16:08:19
$dictionary['Opportunity']['fields']['date_assigned_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['date_assigned_c']['options']='date_range_search_dom';
$dictionary['Opportunity']['fields']['date_assigned_c']['labelValue']='Fecha de Asignación';
$dictionary['Opportunity']['fields']['date_assigned_c']['enable_range_search']='1';

 

 // created: 2015-11-10 20:44:39
$dictionary['Opportunity']['fields']['priority_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['priority_c']['labelValue']='priority';

 

 // created: 2015-11-12 19:24:28
$dictionary['Opportunity']['fields']['destino_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['destino_c']['labelValue']='destino';

 

 // created: 2015-11-10 20:44:39
$dictionary['Opportunity']['fields']['jjwg_maps_lat_c']['inline_edit']=1;

 

 // created: 2016-03-08 18:18:22
$dictionary['Opportunity']['fields']['id_agente_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['id_agente_c']['labelValue']='id agente';

 

 // created: 2015-11-10 20:44:39
$dictionary['Opportunity']['fields']['jjwg_maps_lng_c']['inline_edit']=1;

 

 // created: 2015-11-26 09:18:25
$dictionary['Opportunity']['fields']['agente_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['agente_c']['labelValue']='agente';

 

// created: 2015-11-11 18:33:57
$dictionary["Opportunity"]["fields"]["opportunities_crm_solicitudes_1"] = array (
  'name' => 'opportunities_crm_solicitudes_1',
  'type' => 'link',
  'relationship' => 'opportunities_crm_solicitudes_1',
  'source' => 'non-db',
  'module' => 'crm_solicitudes',
  'bean_name' => 'crm_solicitudes',
  'side' => 'right',
  'vname' => 'LBL_OPPORTUNITIES_CRM_SOLICITUDES_1_FROM_CRM_SOLICITUDES_TITLE',
);


 // created: 2015-11-10 20:44:39
$dictionary['Opportunity']['fields']['estado_wf_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['estado_wf_c']['labelValue']='ESTADO WF';

 

 // created: 2015-11-10 20:44:39
$dictionary['Opportunity']['fields']['jjwg_maps_address_c']['inline_edit']=1;

 

 // created: 2015-10-28 18:46:15
$dictionary['Opportunity']['fields']['name']['len']='255';
$dictionary['Opportunity']['fields']['name']['inline_edit']=true;
$dictionary['Opportunity']['fields']['name']['comments']='Name of the opportunity';
$dictionary['Opportunity']['fields']['name']['merge_filter']='disabled';
$dictionary['Opportunity']['fields']['name']['full_text_search']=array (
);

 
?>