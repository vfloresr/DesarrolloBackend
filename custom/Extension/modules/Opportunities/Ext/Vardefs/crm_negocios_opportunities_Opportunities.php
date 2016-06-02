<?php
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
