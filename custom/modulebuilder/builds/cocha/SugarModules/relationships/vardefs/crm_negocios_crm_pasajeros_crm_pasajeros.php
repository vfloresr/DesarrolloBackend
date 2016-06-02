<?php
// created: 2015-11-11 18:22:41
$dictionary["crm_pasajeros"]["fields"]["crm_negocios_crm_pasajeros"] = array (
  'name' => 'crm_negocios_crm_pasajeros',
  'type' => 'link',
  'relationship' => 'crm_negocios_crm_pasajeros',
  'source' => 'non-db',
  'module' => 'crm_negocios',
  'bean_name' => 'crm_negocios',
  'vname' => 'LBL_CRM_NEGOCIOS_CRM_PASAJEROS_FROM_CRM_NEGOCIOS_TITLE',
  'id_name' => 'crm_negocios_crm_pasajeroscrm_negocios_ida',
);
$dictionary["crm_pasajeros"]["fields"]["crm_negocios_crm_pasajeros_name"] = array (
  'name' => 'crm_negocios_crm_pasajeros_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_CRM_NEGOCIOS_CRM_PASAJEROS_FROM_CRM_NEGOCIOS_TITLE',
  'save' => true,
  'id_name' => 'crm_negocios_crm_pasajeroscrm_negocios_ida',
  'link' => 'crm_negocios_crm_pasajeros',
  'table' => 'crm_negocios',
  'module' => 'crm_negocios',
  'rname' => 'name',
);
$dictionary["crm_pasajeros"]["fields"]["crm_negocios_crm_pasajeroscrm_negocios_ida"] = array (
  'name' => 'crm_negocios_crm_pasajeroscrm_negocios_ida',
  'type' => 'link',
  'relationship' => 'crm_negocios_crm_pasajeros',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_CRM_NEGOCIOS_CRM_PASAJEROS_FROM_CRM_PASAJEROS_TITLE',
);
