<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2015-10-20 11:13:27
$dictionary["Task"]["fields"]["tasks_opportunities_1"] = array (
  'name' => 'tasks_opportunities_1',
  'type' => 'link',
  'relationship' => 'tasks_opportunities_1',
  'source' => 'non-db',
  'module' => 'Opportunities',
  'bean_name' => 'Opportunity',
  'side' => 'right',
  'vname' => 'LBL_TASKS_OPPORTUNITIES_1_FROM_OPPORTUNITIES_TITLE',
);


 // created: 2015-12-03 19:18:51
$dictionary['Task']['fields']['tipo_c']['inline_edit']='';
$dictionary['Task']['fields']['tipo_c']['labelValue']='Tipo';

 

 // created: 2015-11-10 20:44:39
$dictionary['Task']['fields']['voucher_c']['inline_edit']='1';
$dictionary['Task']['fields']['voucher_c']['labelValue']='Voucher';

 

 // created: 2015-11-10 20:44:39
$dictionary['Task']['fields']['documentacion_c']['inline_edit']='1';
$dictionary['Task']['fields']['documentacion_c']['labelValue']='Documentacion';

 

// created: 2015-11-11 18:22:41
$dictionary["Task"]["fields"]["crm_negocios_tasks"] = array (
  'name' => 'crm_negocios_tasks',
  'type' => 'link',
  'relationship' => 'crm_negocios_tasks',
  'source' => 'non-db',
  'module' => 'crm_negocios',
  'bean_name' => 'crm_negocios',
  'vname' => 'LBL_CRM_NEGOCIOS_TASKS_FROM_CRM_NEGOCIOS_TITLE',
  'id_name' => 'crm_negocios_taskscrm_negocios_ida',
);
$dictionary["Task"]["fields"]["crm_negocios_tasks_name"] = array (
  'name' => 'crm_negocios_tasks_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_CRM_NEGOCIOS_TASKS_FROM_CRM_NEGOCIOS_TITLE',
  'save' => true,
  'id_name' => 'crm_negocios_taskscrm_negocios_ida',
  'link' => 'crm_negocios_tasks',
  'table' => 'crm_negocios',
  'module' => 'crm_negocios',
  'rname' => 'name',
);
$dictionary["Task"]["fields"]["crm_negocios_taskscrm_negocios_ida"] = array (
  'name' => 'crm_negocios_taskscrm_negocios_ida',
  'type' => 'link',
  'relationship' => 'crm_negocios_tasks',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_CRM_NEGOCIOS_TASKS_FROM_TASKS_TITLE',
);


 // created: 2015-11-30 13:59:36
$dictionary['Task']['fields']['cierre_workflow_c']['inline_edit']='1';
$dictionary['Task']['fields']['cierre_workflow_c']['labelValue']='Cierre Workflow';

 

 // created: 2015-11-30 13:05:04
$dictionary['Task']['fields']['tipo_tarea_c']['inline_edit']='1';
$dictionary['Task']['fields']['tipo_tarea_c']['labelValue']='Tipo Tarea';

 


$dictionary['Task']['fields']['SecurityGroups'] = array (
  	'name' => 'SecurityGroups',
    'type' => 'link',
	'relationship' => 'securitygroups_tasks',
	'module'=>'SecurityGroups',
	'bean_name'=>'SecurityGroup',
    'source'=>'non-db',
	'vname'=>'LBL_SECURITYGROUPS',
);





?>