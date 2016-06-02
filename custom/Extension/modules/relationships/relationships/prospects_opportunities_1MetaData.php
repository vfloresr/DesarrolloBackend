<?php
// created: 2015-10-16 18:40:50
$dictionary["prospects_opportunities_1"] = array (
  'true_relationship_type' => 'one-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'prospects_opportunities_1' => 
    array (
      'lhs_module' => 'Prospects',
      'lhs_table' => 'prospects',
      'lhs_key' => 'id',
      'rhs_module' => 'Opportunities',
      'rhs_table' => 'opportunities',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'prospects_opportunities_1_c',
      'join_key_lhs' => 'prospects_opportunities_1prospects_ida',
      'join_key_rhs' => 'prospects_opportunities_1opportunities_idb',
    ),
  ),
  'table' => 'prospects_opportunities_1_c',
  'fields' => 
  array (
    0 => 
    array (
      'name' => 'id',
      'type' => 'varchar',
      'len' => 36,
    ),
    1 => 
    array (
      'name' => 'date_modified',
      'type' => 'datetime',
    ),
    2 => 
    array (
      'name' => 'deleted',
      'type' => 'bool',
      'len' => '1',
      'default' => '0',
      'required' => true,
    ),
    3 => 
    array (
      'name' => 'prospects_opportunities_1prospects_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'prospects_opportunities_1opportunities_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'prospects_opportunities_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'prospects_opportunities_1_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'prospects_opportunities_1prospects_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'prospects_opportunities_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'prospects_opportunities_1opportunities_idb',
      ),
    ),
  ),
);