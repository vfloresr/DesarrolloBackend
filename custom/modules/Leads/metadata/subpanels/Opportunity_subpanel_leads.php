<?php
// created: 2015-11-11 21:36:29
$subpanel_layout['list_fields'] = array (
  'name' => 
  array (
    'vname' => 'LBL_LIST_NAME',
    'widget_class' => 'SubPanelDetailViewLink',
    'sort_order' => 'asc',
    'sort_by' => 'last_name',
    'module' => 'Leads',
    'width' => '20%',
    'default' => true,
  ),
  'lead_source' => 
  array (
    'vname' => 'LBL_LIST_LEAD_SOURCE',
    'width' => '13%',
    'default' => true,
  ),
  'phone_fax' => 
  array (
    'type' => 'phone',
    'vname' => 'LBL_FAX_PHONE',
    'width' => '10%',
    'default' => true,
  ),
  'phone_home' => 
  array (
    'type' => 'phone',
    'vname' => 'LBL_HOME_PHONE',
    'width' => '10%',
    'default' => true,
  ),
  'email1' => 
  array (
    'vname' => 'LBL_LIST_EMAIL_ADDRESS',
    'width' => '10%',
    'widget_class' => 'SubPanelEmailLink',
    'sortable' => false,
    'default' => true,
  ),
  'edit_button' => 
  array (
    'vname' => 'LBL_EDIT_BUTTON',
    'widget_class' => 'SubPanelEditButton',
    'module' => 'Leads',
    'width' => '4%',
    'default' => true,
  ),
  'remove_button' => 
  array (
    'vname' => 'LBL_REMOVE',
    'widget_class' => 'SubPanelRemoveButton',
    'module' => 'Leads',
    'width' => '4%',
    'default' => true,
  ),
  'first_name' => 
  array (
    'usage' => 'query_only',
  ),
  'last_name' => 
  array (
    'usage' => 'query_only',
  ),
  'salutation' => 
  array (
    'name' => 'salutation',
    'usage' => 'query_only',
  ),
);