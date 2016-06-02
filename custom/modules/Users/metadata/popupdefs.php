<?php
$popupMeta = array (
    'moduleMain' => 'User',
    'varName' => 'USER',
    'orderBy' => 'user_name',
    'whereClauses' => array (
  'user_name' => 'users.user_name',
  'full_name' => 'users.full_name',
  'sucursal_c' => 'users_cstm.sucursal_c',
  'sucursal_cocha_c' => 'users_cstm.sucursal_cocha_c',
),
    'searchInputs' => array (
  2 => 'user_name',
  4 => 'full_name',
  5 => 'sucursal_c',
  6 => 'sucursal_cocha_c',
),
    'searchdefs' => array (
  'full_name' => 
  array (
    'type' => 'name',
    'studio' => 
    array (
      'formula' => false,
    ),
    'label' => 'LBL_NAME',
    'width' => '10%',
    'name' => 'full_name',
  ),
  'user_name' => 
  array (
    'name' => 'user_name',
    'width' => '10%',
  ),
  'sucursal_c' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_SUCURSAL',
    'width' => '10%',
    'name' => 'sucursal_c',
  ),
  'sucursal_cocha_c' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_SUCURSAL_COCHA',
    'width' => '10%',
    'name' => 'sucursal_cocha_c',
  ),
),
    'listviewdefs' => array (
  'NAME' => 
  array (
    'width' => '30%',
    'label' => 'LBL_LIST_NAME',
    'link' => true,
    'related_fields' => 
    array (
      0 => 'last_name',
      1 => 'first_name',
    ),
    'orderBy' => 'last_name',
    'default' => true,
    'name' => 'name',
  ),
  'PHONE_WORK' => 
  array (
    'width' => '25%',
    'label' => 'LBL_LIST_PHONE',
    'link' => true,
    'default' => true,
    'name' => 'phone_work',
  ),
  'FAVORITO_C' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_FAVORITO',
    'width' => '10%',
    'name' => 'favorito_c',
  ),
  'SUCURSAL_C' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_SUCURSAL',
    'width' => '10%',
    'name' => 'sucursal_c',
  ),
  'DEPARTMENT' => 
  array (
    'width' => '15%',
    'label' => 'LBL_DEPARTMENT',
    'link' => true,
    'default' => true,
    'name' => 'department',
  ),
  'DISPONIBLE_SW_C' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_DISPONIBLE_SW',
    'width' => '10%',
    'name' => 'disponible_sw_c',
  ),
),
);
