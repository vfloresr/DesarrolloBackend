<?php
$module_name = 'mo_Accesos';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
  'SOLICITUD_BOTON' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_SOLICITUD_BOTON',
    'width' => '10%',
  ),
  'OPORTUNIDADES_WEB' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_OPORTUNIDADES_WEB',
    'width' => '10%',
  ),
  'OPORTUNIDADES_CROSS' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_OPORTUNIDADES_CROSS',
    'width' => '10%',
  ),
  'TAREA_PROXIMOS' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_TAREA_PROXIMOS',
    'width' => '10%',
  ),
  'OPORTUNIDADES_FUGADOS' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_OPORTUNIDADES_FUGADOS',
    'width' => '10%',
  ),
  'OPORTUNIDADES_RECOMPRA' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_OPORTUNIDADES_RECOMPRA',
    'width' => '10%',
  ),
  'TAREA_RETORNO' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_TAREA_RETORNO',
    'width' => '10%',
  ),
  'TAREA_CARTERA' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_TAREA_CARTERA',
    'width' => '10%',
  ),
  'TAREA_CUMPLEANIOS' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_TAREA_CUMPLEANIOS',
    'width' => '10%',
  ),
);
?>
