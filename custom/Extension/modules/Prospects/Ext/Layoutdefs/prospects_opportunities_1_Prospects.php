<?php
 // created: 2015-10-16 18:40:50
$layout_defs["Prospects"]["subpanel_setup"]['prospects_opportunities_1'] = array (
  'order' => 100,
  'module' => 'Opportunities',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_PROSPECTS_OPPORTUNITIES_1_FROM_OPPORTUNITIES_TITLE',
  'get_subpanel_data' => 'prospects_opportunities_1',
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
