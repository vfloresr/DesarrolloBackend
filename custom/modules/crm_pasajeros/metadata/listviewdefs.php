<?php
$module_name = 'crm_pasajeros';
$listViewDefs [$module_name] = 
array (
  'CONTACTO' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_CONTACTO',
    'id' => 'CONTACT_ID_C',
    'link' => true,
    'width' => '10%',
    'default' => true,
  ),
  'TIPO_PAX' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_TIPO_PAX',
    'width' => '10%',
    'default' => true,
  ),
  'NAME' => 
  array (
    'width' => '10%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'DATE_ENTERED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'default' => true,
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
);
?>
