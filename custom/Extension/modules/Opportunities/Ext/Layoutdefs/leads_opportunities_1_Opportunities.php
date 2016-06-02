<?php
 // created: 2015-11-10 17:52:17
$layout_defs["Opportunities"]["subpanel_setup"]['leads_opportunities_1'] = array (
  'order' => 0,
  'module' => 'Leads',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_LEADS_OPPORTUNITIES_1_FROM_LEADS_TITLE',
  'get_subpanel_data' => 'leads_opportunities_1',
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
