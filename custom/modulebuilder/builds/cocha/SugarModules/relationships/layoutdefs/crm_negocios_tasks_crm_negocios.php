<?php
 // created: 2015-11-11 18:22:41
$layout_defs["crm_negocios"]["subpanel_setup"]['crm_negocios_tasks'] = array (
  'order' => 100,
  'module' => 'Tasks',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_CRM_NEGOCIOS_TASKS_FROM_TASKS_TITLE',
  'get_subpanel_data' => 'crm_negocios_tasks',
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
