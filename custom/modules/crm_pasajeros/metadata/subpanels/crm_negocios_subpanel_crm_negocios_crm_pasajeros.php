<?php
// created: 2016-01-14 17:38:03
$subpanel_layout['list_fields'] = array (
  'contacto' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'vname' => 'LBL_CONTACTO',
    'id' => 'CONTACT_ID_C',
    'link' => true,
    'width' => '10%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'Contacts',
    'target_record_key' => 'contact_id_c',
  ),
  'tipo_pax' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'vname' => 'LBL_TIPO_PAX',
    'width' => '10%',
    'default' => true,
  ),
  'name' => 
  array (
    'vname' => 'LBL_NAME',
    'widget_class' => 'SubPanelDetailViewLink',
    'width' => '45%',
    'default' => true,
  ),
  'date_modified' => 
  array (
    'vname' => 'LBL_DATE_MODIFIED',
    'width' => '45%',
    'default' => true,
  ),
  'edit_button' => 
  array (
    'vname' => 'LBL_EDIT_BUTTON',
    'widget_class' => 'SubPanelEditButton',
    'module' => 'crm_pasajeros',
    'width' => '4%',
    'default' => true,
  ),
  'remove_button' => 
  array (
    'vname' => 'LBL_REMOVE',
    'widget_class' => 'SubPanelRemoveButton',
    'module' => 'crm_pasajeros',
    'width' => '5%',
    'default' => true,
  ),
);