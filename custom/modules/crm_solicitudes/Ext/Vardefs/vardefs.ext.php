<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2015-11-11 18:33:57
$dictionary["crm_solicitudes"]["fields"]["opportunities_crm_solicitudes_1"] = array (
  'name' => 'opportunities_crm_solicitudes_1',
  'type' => 'link',
  'relationship' => 'opportunities_crm_solicitudes_1',
  'source' => 'non-db',
  'module' => 'Opportunities',
  'bean_name' => 'Opportunity',
  'vname' => 'LBL_OPPORTUNITIES_CRM_SOLICITUDES_1_FROM_OPPORTUNITIES_TITLE',
  'id_name' => 'opportunities_crm_solicitudes_1opportunities_ida',
);
$dictionary["crm_solicitudes"]["fields"]["opportunities_crm_solicitudes_1_name"] = array (
  'name' => 'opportunities_crm_solicitudes_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_OPPORTUNITIES_CRM_SOLICITUDES_1_FROM_OPPORTUNITIES_TITLE',
  'save' => true,
  'id_name' => 'opportunities_crm_solicitudes_1opportunities_ida',
  'link' => 'opportunities_crm_solicitudes_1',
  'table' => 'opportunities',
  'module' => 'Opportunities',
  'rname' => 'name',
);
$dictionary["crm_solicitudes"]["fields"]["opportunities_crm_solicitudes_1opportunities_ida"] = array (
  'name' => 'opportunities_crm_solicitudes_1opportunities_ida',
  'type' => 'link',
  'relationship' => 'opportunities_crm_solicitudes_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_OPPORTUNITIES_CRM_SOLICITUDES_1_FROM_CRM_SOLICITUDES_TITLE',
);


 // created: 2016-05-17 00:19:30
$dictionary['crm_solicitudes']['fields']['email_c']['inline_edit']='1';
$dictionary['crm_solicitudes']['fields']['email_c']['labelValue']='email';

 

 // created: 2016-05-17 00:20:18
$dictionary['crm_solicitudes']['fields']['rut_c']['inline_edit']='1';
$dictionary['crm_solicitudes']['fields']['rut_c']['labelValue']='rut';

 
?>