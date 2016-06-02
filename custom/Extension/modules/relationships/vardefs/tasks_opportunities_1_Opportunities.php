<?php
// created: 2015-10-20 11:13:27
$dictionary["Opportunity"]["fields"]["tasks_opportunities_1"] = array (
  'name' => 'tasks_opportunities_1',
  'type' => 'link',
  'relationship' => 'tasks_opportunities_1',
  'source' => 'non-db',
  'module' => 'Tasks',
  'bean_name' => 'Task',
  'vname' => 'LBL_TASKS_OPPORTUNITIES_1_FROM_TASKS_TITLE',
  'id_name' => 'tasks_opportunities_1tasks_ida',
);
$dictionary["Opportunity"]["fields"]["tasks_opportunities_1_name"] = array (
  'name' => 'tasks_opportunities_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_TASKS_OPPORTUNITIES_1_FROM_TASKS_TITLE',
  'save' => true,
  'id_name' => 'tasks_opportunities_1tasks_ida',
  'link' => 'tasks_opportunities_1',
  'table' => 'tasks',
  'module' => 'Tasks',
  'rname' => 'name',
);
$dictionary["Opportunity"]["fields"]["tasks_opportunities_1tasks_ida"] = array (
  'name' => 'tasks_opportunities_1tasks_ida',
  'type' => 'link',
  'relationship' => 'tasks_opportunities_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_TASKS_OPPORTUNITIES_1_FROM_OPPORTUNITIES_TITLE',
);
