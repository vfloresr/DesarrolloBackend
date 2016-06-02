<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2015-11-11 18:22:41
$dictionary["crm_negocio_detalle"]["fields"]["crm_negocios_crm_negocio_detalle"] = array (
  'name' => 'crm_negocios_crm_negocio_detalle',
  'type' => 'link',
  'relationship' => 'crm_negocios_crm_negocio_detalle',
  'source' => 'non-db',
  'module' => 'crm_negocios',
  'bean_name' => 'crm_negocios',
  'vname' => 'LBL_CRM_NEGOCIOS_CRM_NEGOCIO_DETALLE_FROM_CRM_NEGOCIOS_TITLE',
  'id_name' => 'crm_negocios_crm_negocio_detallecrm_negocios_ida',
);
$dictionary["crm_negocio_detalle"]["fields"]["crm_negocios_crm_negocio_detalle_name"] = array (
  'name' => 'crm_negocios_crm_negocio_detalle_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_CRM_NEGOCIOS_CRM_NEGOCIO_DETALLE_FROM_CRM_NEGOCIOS_TITLE',
  'save' => true,
  'id_name' => 'crm_negocios_crm_negocio_detallecrm_negocios_ida',
  'link' => 'crm_negocios_crm_negocio_detalle',
  'table' => 'crm_negocios',
  'module' => 'crm_negocios',
  'rname' => 'name',
);
$dictionary["crm_negocio_detalle"]["fields"]["crm_negocios_crm_negocio_detallecrm_negocios_ida"] = array (
  'name' => 'crm_negocios_crm_negocio_detallecrm_negocios_ida',
  'type' => 'link',
  'relationship' => 'crm_negocios_crm_negocio_detalle',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_CRM_NEGOCIOS_CRM_NEGOCIO_DETALLE_FROM_CRM_NEGOCIO_DETALLE_TITLE',
);

?>