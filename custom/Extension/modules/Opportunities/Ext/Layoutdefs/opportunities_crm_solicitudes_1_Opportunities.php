<?php
 // created: 2015-11-11 18:33:57
$layout_defs["Opportunities"]["subpanel_setup"]['opportunities_crm_solicitudes_1'] = array (
  'order' => 10,
  'module' => 'crm_solicitudes',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_OPPORTUNITIES_CRM_SOLICITUDES_1_FROM_CRM_SOLICITUDES_TITLE',
  'get_subpanel_data' => 'opportunities_crm_solicitudes_1',
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
