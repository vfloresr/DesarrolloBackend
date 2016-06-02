<?php
// created: 2015-10-16 18:40:50
$dictionary["Opportunity"]["fields"]["prospects_opportunities_1"] = array (
  'name' => 'prospects_opportunities_1',
  'type' => 'link',
  'relationship' => 'prospects_opportunities_1',
  'source' => 'non-db',
  'module' => 'Prospects',
  'bean_name' => 'Prospect',
  'vname' => 'LBL_PROSPECTS_OPPORTUNITIES_1_FROM_PROSPECTS_TITLE',
  'id_name' => 'prospects_opportunities_1prospects_ida',
);
$dictionary["Opportunity"]["fields"]["prospects_opportunities_1_name"] = array (
  'name' => 'prospects_opportunities_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_PROSPECTS_OPPORTUNITIES_1_FROM_PROSPECTS_TITLE',
  'save' => true,
  'id_name' => 'prospects_opportunities_1prospects_ida',
  'link' => 'prospects_opportunities_1',
  'table' => 'prospects',
  'module' => 'Prospects',
  'rname' => 'account_name',
);
$dictionary["Opportunity"]["fields"]["prospects_opportunities_1prospects_ida"] = array (
  'name' => 'prospects_opportunities_1prospects_ida',
  'type' => 'link',
  'relationship' => 'prospects_opportunities_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_PROSPECTS_OPPORTUNITIES_1_FROM_OPPORTUNITIES_TITLE',
);
