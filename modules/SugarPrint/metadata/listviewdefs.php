<?php
$module_name = 'SugarPrint';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'REPORT_MODULE' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_REPORT_MODULE',
    'width' => '10%',
    'default' => true,
  ),
  'REPORT_TYPE' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_REPORT_TYPE',
    'width' => '10%',
    'default' => true,
  ),
  'PRIVATEREPORT' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_PRIVATEREPORT',
    'width' => '10%',
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
  'DATE_ENTERED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'default' => true,
  ),
  'DATE_MODIFIED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_MODIFIED',
    'width' => '10%',
    'default' => true,
  ),
);
?>
