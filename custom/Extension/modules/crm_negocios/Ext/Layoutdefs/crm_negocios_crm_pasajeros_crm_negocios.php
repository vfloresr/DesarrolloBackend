<?php
 // created: 2015-11-11 18:22:41
$layout_defs["crm_negocios"]["subpanel_setup"]['crm_negocios_crm_pasajeros'] = array (
  'order' => 100,
  'module' => 'crm_pasajeros',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_CRM_NEGOCIOS_CRM_PASAJEROS_FROM_CRM_PASAJEROS_TITLE',
  'get_subpanel_data' => 'crm_negocios_crm_pasajeros',
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopButtonQuickCreate',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),
  ),
);
