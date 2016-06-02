<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2014-06-20 12:06:29
$dictionary["User"]["fields"]["project_users_1"] = array (
  'name' => 'project_users_1',
  'type' => 'link',
  'relationship' => 'project_users_1',
  'source' => 'non-db',
  'module' => 'Project',
  'bean_name' => 'Project',
  'vname' => 'LBL_PROJECT_USERS_1_FROM_PROJECT_TITLE',
);


 // created: 2015-11-14 14:14:49
$dictionary['User']['fields']['disponible_sw_c']['inline_edit']='1';
$dictionary['User']['fields']['disponible_sw_c']['labelValue']='Disponible SW';

 

 // created: 2016-01-15 12:59:21
$dictionary['User']['fields']['sucursal_cocha_c']['inline_edit']='1';
$dictionary['User']['fields']['sucursal_cocha_c']['labelValue']='Sucursal Cocha';

 

 // created: 2015-11-10 20:44:39
$dictionary['User']['fields']['direccion_sucursal_c']['inline_edit']='1';
$dictionary['User']['fields']['direccion_sucursal_c']['labelValue']='Direccion sucursal';

 

 // created: 2015-11-10 20:44:39
$dictionary['User']['fields']['favorito_c']['inline_edit']='1';
$dictionary['User']['fields']['favorito_c']['labelValue']='Favorito';

 

 // created: 2015-11-27 10:29:35
$dictionary['User']['fields']['sucursal_c']['inline_edit']='1';
$dictionary['User']['fields']['sucursal_c']['labelValue']='Sucursal';

 

 // created: 2015-11-10 20:44:39
$dictionary['User']['fields']['crosselling_ejecutiva_c']['inline_edit']='1';
$dictionary['User']['fields']['crosselling_ejecutiva_c']['labelValue']='Crosselling Ejecutiva';

 


$dictionary["User"]["fields"]["SecurityGroups"] = array (
    'name' => 'SecurityGroups',
    'type' => 'link',
    'relationship' => 'securitygroups_users',
    'source' => 'non-db',
    'module' => 'SecurityGroups',
    'bean_name' => 'SecurityGroup',
    'vname' => 'LBL_SECURITYGROUPS',
);  
        
$dictionary["User"]["fields"]['securitygroup_noninher_fields'] = array (
    'name' => 'securitygroup_noninher_fields',
    'rname' => 'id',
    'relationship_fields'=>array('id' => 'securitygroup_noninherit_id', 'noninheritable' => 'securitygroup_noninheritable', 'primary_group' => 'securitygroup_primary_group'),
    'vname' => 'LBL_USER_NAME',
    'type' => 'relate',
    'link' => 'SecurityGroups',         
    'link_type' => 'relationship_info',
    'source' => 'non-db',
    'Importable' => false,
    'duplicate_merge'=> 'disabled',

);
        
        
$dictionary["User"]["fields"]['securitygroup_noninherit_id'] = array(
    'name' => 'securitygroup_noninherit_id',
    'type' => 'varchar',
    'source' => 'non-db',
    'vname' => 'LBL_securitygroup_noninherit_id',
);

$dictionary["User"]["fields"]['securitygroup_noninheritable'] = array(
    'name' => 'securitygroup_noninheritable',
    'type' => 'bool',
    'source' => 'non-db',
    'vname' => 'LBL_SECURITYGROUP_NONINHERITABLE',
);

$dictionary["User"]["fields"]['securitygroup_primary_group'] = array(
    'name' => 'securitygroup_primary_group',
    'type' => 'bool',
    'source' => 'non-db',
    'vname' => 'LBL_PRIMARY_GROUP',
);




 // created: 2015-11-10 20:44:39
$dictionary['User']['fields']['crosselling_sucursal_c']['inline_edit']='1';
$dictionary['User']['fields']['crosselling_sucursal_c']['labelValue']='Crosselling Sucursal';

 

 // created: 2015-12-28 21:23:29
$dictionary['User']['fields']['ultimo_acceso_c']['inline_edit']='';
$dictionary['User']['fields']['ultimo_acceso_c']['options']='date_range_search_dom';
$dictionary['User']['fields']['ultimo_acceso_c']['labelValue']='ultimo acceso';
$dictionary['User']['fields']['ultimo_acceso_c']['enable_range_search']='1';

 
?>