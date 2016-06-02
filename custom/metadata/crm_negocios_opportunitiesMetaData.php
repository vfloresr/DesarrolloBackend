<?php
// created: 2015-11-11 18:22:41
$dictionary["crm_negocios_opportunities"] = array (
  'true_relationship_type' => 'one-to-many',
  'relationships' => 
  array (
    'crm_negocios_opportunities' => 
    array (
      'lhs_module' => 'crm_negocios',
      'lhs_table' => 'crm_negocios',
      'lhs_key' => 'id',
      'rhs_module' => 'Opportunities',
      'rhs_table' => 'opportunities',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'crm_negocios_opportunities_c',
      'join_key_lhs' => 'crm_negocios_opportunitiescrm_negocios_ida',
      'join_key_rhs' => 'crm_negocios_opportunitiesopportunities_idb',
    ),
  ),
  'table' => 'crm_negocios_opportunities_c',
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
      'name' => 'crm_negocios_opportunitiescrm_negocios_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'crm_negocios_opportunitiesopportunities_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'crm_negocios_opportunitiesspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'crm_negocios_opportunities_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'crm_negocios_opportunitiescrm_negocios_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'crm_negocios_opportunities_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'crm_negocios_opportunitiesopportunities_idb',
      ),
    ),
  ),
);