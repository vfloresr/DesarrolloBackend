<?php
$dashletData['OpportunitiesDashlet']['searchFields'] = array (
  'sales_stage' => 
  array (
    'default' => '',
  ),
  'assigned_user_id' => 
  array (
    'default' => '',
  ),
);
$dashletData['OpportunitiesDashlet']['columns'] = array (
  'name' => 
  array (
    'width' => '35%',
    'label' => 'LBL_OPPORTUNITY_NAME',
    'link' => true,
    'default' => true,
    'name' => 'name',
  ),
  'leads_opportunities_1_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_LEADS_OPPORTUNITIES_1_FROM_LEADS_TITLE',
    'id' => 'LEADS_OPPORTUNITIES_1LEADS_IDA',
    'width' => '10%',
    'default' => true,
  ),
  'date_entered' => 
  array (
    'width' => '15%',
    'label' => 'LBL_DATE_ENTERED',
    'name' => 'date_entered',
    'default' => true,
  ),
  'opportunity_type' => 
  array (
    'width' => '15%',
    'label' => 'LBL_TYPE',
    'name' => 'opportunity_type',
    'default' => true,
  ),
  'sales_stage' => 
  array (
    'width' => '15%',
    'label' => 'LBL_SALES_STAGE',
    'name' => 'sales_stage',
    'default' => true,
  ),
  'assigned_user_name' => 
  array (
    'width' => '8%',
    'label' => 'LBL_LIST_ASSIGNED_USER',
    'name' => 'assigned_user_name',
    'default' => false,
  ),
);
