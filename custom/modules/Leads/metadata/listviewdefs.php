<?php
$listViewDefs ['Leads'] = 
array (
  'NAME' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_NAME',
    'link' => true,
    'orderBy' => 'name',
    'default' => true,
    'related_fields' => 
    array (
      0 => 'first_name',
      1 => 'last_name',
      2 => 'salutation',
    ),
  ),
  'PHONE_FAX' => 
  array (
    'width' => '10%',
    'label' => 'LBL_FAX_PHONE',
    'default' => true,
  ),
  'STATUS' => 
  array (
    'width' => '7%',
    'label' => 'LBL_LIST_STATUS',
    'default' => true,
  ),
  'PHONE_HOME' => 
  array (
    'width' => '10%',
    'label' => 'LBL_HOME_PHONE',
    'default' => true,
  ),
  'CAMPAIGN_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CAMPAIGN',
    'id' => 'CAMPAIGN_ID',
    'width' => '10%',
    'default' => true,
  ),
  'LEAD_SOURCE' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LEAD_SOURCE',
    'default' => true,
  ),
  'EMAIL1' => 
  array (
    'width' => '16%',
    'label' => 'LBL_LIST_EMAIL_ADDRESS',
    'sortable' => false,
    'customCode' => '{$EMAIL1_LINK}{$EMAIL1}</a>',
    'default' => true,
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '5%',
    'label' => 'LBL_LIST_ASSIGNED_USER',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
  'RECIBIR_NOTIFICACIONES_C' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_RECIBIR_NOTIFICACIONES',
    'width' => '10%',
  ),
  'POLITICAS_PRIVACIDAD_C' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_POLITICAS_PRIVACIDAD',
    'width' => '10%',
  ),
  'DATE_ENTERED' => 
  array (
    'width' => '10%',
    'label' => 'LBL_DATE_ENTERED',
    'default' => true,
  ),
);
?>