<?php
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
