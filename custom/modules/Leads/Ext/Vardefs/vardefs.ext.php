<?php 
 //WARNING: The contents of this file are auto-generated


 // created: 2016-02-09 09:37:04
$dictionary['Lead']['fields']['ip_registro_c']['inline_edit']='1';
$dictionary['Lead']['fields']['ip_registro_c']['labelValue']='ip registro';

 

 // created: 2015-10-15 19:26:16
$dictionary['Lead']['fields']['jjwg_maps_geocode_status_c']['inline_edit']=1;

 

 // created: 2015-12-01 18:00:51
$dictionary['Lead']['fields']['last_name']['audited']=true;
$dictionary['Lead']['fields']['last_name']['inline_edit']=true;
$dictionary['Lead']['fields']['last_name']['comments']='Last name of the contact';
$dictionary['Lead']['fields']['last_name']['merge_filter']='disabled';

 


$dictionary['Lead']['fields']['e_invite_status_fields'] =
		array (
			'name' => 'e_invite_status_fields',
			'rname' => 'id',
			'relationship_fields'=>array('id' => 'event_invite_id', 'invite_status' => 'event_status_name'),
			'vname' => 'LBL_CONT_INVITE_STATUS',
			'type' => 'relate',
			'link' => 'fp_events_leads_1',
			'link_type' => 'relationship_info',
            'join_link_name' => 'fp_events_leads_1',
			'source' => 'non-db',
			'importable' => 'false',
            'duplicate_merge'=> 'disabled',
			'studio' => false,
		);


$dictionary['Lead']['fields']['event_status_name'] =
		array(
            'massupdate' => false,
            'name' => 'event_status_name',
            'type' => 'enum',
            'studio' => 'false',
            'source' => 'non-db',
            'vname' => 'LBL_LIST_INVITE_STATUS_EVENT',
            'options' => 'fp_event_invite_status_dom',
            'importable' => 'false',
        );
$dictionary['Lead']['fields']['event_invite_id'] =
    array(
        'name' => 'event_invite_id',
        'type' => 'varchar',
        'source' => 'non-db',
        'vname' => 'LBL_LIST_INVITE_STATUS',
        'studio' => array('listview' => false),
    );


$dictionary['Lead']['fields']['e_accept_status_fields'] =
        array (
            'name' => 'e_accept_status_fields',
            'rname' => 'id',
            'relationship_fields'=>array('id' => 'event_status_id', 'accept_status' => 'event_accept_status'),
            'vname' => 'LBL_CONT_ACCEPT_STATUS',
            'type' => 'relate',
            'link' => 'fp_events_leads_1',
            'link_type' => 'relationship_info',
            'join_link_name' => 'fp_events_leads_1',
            'source' => 'non-db',
            'importable' => 'false',
            'duplicate_merge'=> 'disabled',
            'studio' => false,
        );


$dictionary['Lead']['fields']['event_accept_status'] =
        array(
            'massupdate' => false,
            'name' => 'event_accept_status',
            'type' => 'enum',
            'studio' => 'false',
            'source' => 'non-db',
            'vname' => 'LBL_LIST_ACCEPT_STATUS_EVENT',
            'options' => 'fp_event_status_dom',
            'importable' => 'false',
        );
$dictionary['Lead']['fields']['event_status_id'] =
    array(
        'name' => 'event_status_id',
        'type' => 'varchar',
        'source' => 'non-db',
        'vname' => 'LBL_LIST_ACCEPT_STATUS',
        'studio' => array('listview' => false),
    );

 // created: 2015-12-01 18:02:18
$dictionary['Lead']['fields']['phone_home']['audited']=true;
$dictionary['Lead']['fields']['phone_home']['inline_edit']=true;
$dictionary['Lead']['fields']['phone_home']['comments']='Home phone number of the contact';
$dictionary['Lead']['fields']['phone_home']['merge_filter']='disabled';

 

 // created: 2016-02-09 09:39:34
$dictionary['Lead']['fields']['recibir_notificaciones_c']['inline_edit']='1';
$dictionary['Lead']['fields']['recibir_notificaciones_c']['labelValue']='Recibir Notificaciones';

 


$dictionary['Lead']['fields']['SecurityGroups'] = array (
  	'name' => 'SecurityGroups',
    'type' => 'link',
	'relationship' => 'securitygroups_leads',
	'module'=>'SecurityGroups',
	'bean_name'=>'SecurityGroup',
    'source'=>'non-db',
	'vname'=>'LBL_SECURITYGROUPS',
);






 // created: 2015-12-01 18:02:27
$dictionary['Lead']['fields']['email1']['audited']=true;
$dictionary['Lead']['fields']['email1']['inline_edit']=true;
$dictionary['Lead']['fields']['email1']['merge_filter']='disabled';

 

 // created: 2016-05-17 00:16:18
$dictionary['Lead']['fields']['email_c']['inline_edit']='1';
$dictionary['Lead']['fields']['email_c']['labelValue']='email';

 

// created: 2013-04-30 14:52:24
$dictionary["Lead"]["fields"]["fp_events_leads_1"] = array (
  'name' => 'fp_events_leads_1',
  'type' => 'link',
  'relationship' => 'fp_events_leads_1',
  'source' => 'non-db',
  'vname' => 'LBL_FP_EVENTS_LEADS_1_FROM_FP_EVENTS_TITLE',
);


 // created: 2015-12-01 18:00:43
$dictionary['Lead']['fields']['first_name']['audited']=true;
$dictionary['Lead']['fields']['first_name']['inline_edit']=true;
$dictionary['Lead']['fields']['first_name']['comments']='First name of the contact';
$dictionary['Lead']['fields']['first_name']['merge_filter']='disabled';

 

 // created: 2016-05-17 00:16:58
$dictionary['Lead']['fields']['rut_c']['inline_edit']='1';
$dictionary['Lead']['fields']['rut_c']['labelValue']='rut';

 

// created: 2015-10-19 17:54:45
$dictionary["Lead"]["fields"]["leads_opportunities_1"] = array (
  'name' => 'leads_opportunities_1',
  'type' => 'link',
  'relationship' => 'leads_opportunities_1',
  'source' => 'non-db',
  'module' => 'Opportunities',
  'bean_name' => 'Opportunity',
  'side' => 'right',
  'vname' => 'LBL_LEADS_OPPORTUNITIES_1_FROM_OPPORTUNITIES_TITLE',
);


 // created: 2016-02-09 09:38:16
$dictionary['Lead']['fields']['politicas_privacidad_c']['inline_edit']='1';
$dictionary['Lead']['fields']['politicas_privacidad_c']['labelValue']='Politicas Privacidad';

 

 // created: 2015-10-15 19:26:16
$dictionary['Lead']['fields']['jjwg_maps_lat_c']['inline_edit']=1;

 

 // created: 2015-12-01 18:02:03
$dictionary['Lead']['fields']['phone_fax']['inline_edit']=true;
$dictionary['Lead']['fields']['phone_fax']['comments']='Contact fax number';
$dictionary['Lead']['fields']['phone_fax']['merge_filter']='disabled';
$dictionary['Lead']['fields']['phone_fax']['audited']=true;

 

 // created: 2015-10-15 19:26:16
$dictionary['Lead']['fields']['jjwg_maps_lng_c']['inline_edit']=1;

 

 // created: 2015-10-15 19:26:16
$dictionary['Lead']['fields']['jjwg_maps_address_c']['inline_edit']=1;

 
?>