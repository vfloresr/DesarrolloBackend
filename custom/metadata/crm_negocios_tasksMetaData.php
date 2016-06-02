<?php
// created: 2015-11-11 18:22:41
$dictionary["crm_negocios_tasks"] = array (
  'true_relationship_type' => 'one-to-many',
  'relationships' => 
  array (
    'crm_negocios_tasks' => 
    array (
      'lhs_module' => 'crm_negocios',
      'lhs_table' => 'crm_negocios',
      'lhs_key' => 'id',
      'rhs_module' => 'Tasks',
      'rhs_table' => 'tasks',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'crm_negocios_tasks_c',
      'join_key_lhs' => 'crm_negocios_taskscrm_negocios_ida',
      'join_key_rhs' => 'crm_negocios_taskstasks_idb',
    ),
  ),
  'table' => 'crm_negocios_tasks_c',
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
      'name' => 'crm_negocios_taskscrm_negocios_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'crm_negocios_taskstasks_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'crm_negocios_tasksspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'crm_negocios_tasks_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'crm_negocios_taskscrm_negocios_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'crm_negocios_tasks_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'crm_negocios_taskstasks_idb',
      ),
    ),
  ),
);