<?php 
 //WARNING: The contents of this file are auto-generated


 // created: 2013-04-30 14:52:24
$layout_defs["Leads"]["subpanel_setup"]['fp_events_leads_1'] = array (
  'order' => 100,
  'module' => 'FP_events',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_FP_EVENTS_LEADS_1_FROM_FP_EVENTS_TITLE',
  'get_subpanel_data' => 'fp_events_leads_1',
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


/**
 * sps_leadsLayoutdefs.php
 * @author SalesAgility <support@salesagility.com>
 * Date: 27/01/14
 */


$layout_defs["Leads"]["subpanel_setup"]["history"]['searchdefs'] =
array (
    'collection' =>
        array (
            'name' => 'collection',
            'label' => 'LBL_COLLECTION_TYPE',
            'type' => 'enum',
            'options' => $GLOBALS['app_list_strings']['collection_temp_list'],
            'default' => true,
            'width' => '10%',
        ),
    'name' =>
        array (
            'name' => 'name',
            'default' => true,
            'width' => '10%',
        ),
    'current_user_only' =>
        array (
            'name' => 'current_user_only',
            'label' => 'LBL_CURRENT_USER_FILTER',
            'type' => 'bool',
            'default' => true,
            'width' => '10%',
        ),
    'date_modified' =>
        array (
            'name' => 'date_modified',
            'default' => true,
            'width' => '10%',
        ),
);

$layout_defs["Leads"]["subpanel_setup"]["history"]['top_buttons'][] = array('widget_class' => 'SubPanelTopFilterButton');

 // created: 2015-10-19 17:54:45
$layout_defs["Leads"]["subpanel_setup"]['leads_opportunities_1'] = array (
  'order' => 100,
  'module' => 'Opportunities',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_LEADS_OPPORTUNITIES_1_FROM_OPPORTUNITIES_TITLE',
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



$layout_defs['Leads']['subpanel_setup']['securitygroups'] = array(
	'top_buttons' => array(array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'SecurityGroups', 'mode' => 'MultiSelect'),),
	'order' => 900,
	'sort_by' => 'name',
	'sort_order' => 'asc',
	'module' => 'SecurityGroups',
	'refresh_page'=>1,
	'subpanel_name' => 'default',
	'get_subpanel_data' => 'SecurityGroups',
	'add_subpanel_data' => 'securitygroup_id',
	'title_key' => 'LBL_SECURITYGROUPS_SUBPANEL_TITLE',
);





//auto-generated file DO NOT EDIT
$layout_defs['Leads']['subpanel_setup']['leads_opportunities_1']['override_subpanel_name'] = 'Lead_subpanel_leads_opportunities_1';

?>