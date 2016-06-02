<?php
$module_name = 'crm_solicitudes';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'CATEGORIA_NOMBRE' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_CATEGORIA_NOMBRE',
    'width' => '10%',
    'default' => true,
  ),
  'HOTEL' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_HOTEL',
    'width' => '10%',
    'default' => true,
  ),
  'HOTEL_HABITACION' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_HOTEL_HABITACION',
    'width' => '10%',
    'default' => true,
  ),
  'ADULTOS' => 
  array (
    'type' => 'int',
    'label' => 'LBL_ADULTOS',
    'width' => '10%',
    'default' => true,
  ),
  'NINOS' => 
  array (
    'type' => 'int',
    'label' => 'LBL_NINOS',
    'width' => '10%',
    'default' => true,
  ),
  'CANAL' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_CANAL',
    'width' => '10%',
    'default' => true,
  ),
  'PRECIO' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_PRECIO',
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
  'URL_PRODUCTO' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_URL_PRODUCTO',
    'width' => '10%',
    'default' => true,
  ),
  'PDF_PRODUCTO' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_PDF_PRODUCTO',
    'width' => '10%',
    'default' => true,
  ),
  'DATE_ENTERED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'default' => true,
  ),
  'DESTINO' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_DESTINO',
    'width' => '10%',
    'default' => false,
  ),
  'FECHA_VIAJE' => 
  array (
    'type' => 'date',
    'label' => 'LBL_FECHA_VIAJE',
    'width' => '10%',
    'default' => false,
  ),
  'FECHA_FLEXIBLE' => 
  array (
    'type' => 'bool',
    'default' => false,
    'label' => 'LBL_FECHA_FLEXIBLE',
    'width' => '10%',
  ),
  'HOTEL_TARIFAS' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_HOTEL_TARIFAS',
    'width' => '10%',
    'default' => false,
  ),
  'PDF_JOOMLA' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_PDF_JOOMLA',
    'width' => '10%',
    'default' => false,
  ),
  'ANALYTICS' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_ANALYTICS',
    'width' => '10%',
    'default' => false,
  ),
  'AGENTE' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_AGENTE',
    'width' => '10%',
    'default' => false,
  ),
  'URL' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_URL',
    'width' => '10%',
    'default' => false,
  ),
  'EDADES' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_EDADES',
    'width' => '10%',
    'default' => false,
  ),
  'PRODUCTO_ID' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_PRODUCTO_ID',
    'width' => '10%',
    'default' => false,
  ),
);
?>
