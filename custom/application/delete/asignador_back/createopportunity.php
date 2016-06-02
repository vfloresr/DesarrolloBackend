<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
?>
<?php
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Credentials: true");
	header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
	header('Access-Control-Max-Age: 1000');
	header('Access-Control-Allow-Headers: Origin, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers');


if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.

 * SuiteCRM is an extension to SugarCRM Community Edition developed by Salesagility Ltd.
 * Copyright (C) 2011 - 2014 Salesagility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for  technical reasons, the Appropriate Legal Notices must
 * display the words  "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 ********************************************************************************/

require_once('include/formbase.php');
require_once('modules/Leads/LeadFormBase.php');


global $app_strings, $app_list_strings, $sugar_config, $timedate, $current_user,$db;
$timeDate = new TimeDate();

$mod_strings = return_module_language($sugar_config['default_language'], 'Leads');

$app_list_strings['record_type_module'] = array('Contact'=>'Contacts', 'Account'=>'Accounts', 'Opportunity'=>'Opportunities', 'Case'=>'Cases', 'Note'=>'Notes', 'Call'=>'Calls', 'Email'=>'Emails', 'Meeting'=>'Meetings', 'Task'=>'Tasks', 'Lead'=>'Leads','Bug'=>'Bugs',

);

/**
 * To make your changes upgrade safe create a file called leadCapture_override.php and place the changes there
 */
$users = array(
	'PUT A RANDOM KEY FROM THE WEBSITE HERE' => array('name'=>'PUT THE USER_NAME HERE', 'pass'=>'PUT THE USER_HASH FOR THE RESPECTIVE USER HERE'),
);
	
if (isset( $_POST['id_joomla']) && $_POST['id_joomla'] != ''){
			$json = file_get_contents('http://cms.cocha.com/mobile-api/programas?id='.$_POST['id_joomla']);
			$data = json_decode($json, TRUE);
			$categoria_nombre_web = trim($data[0]['category']);
			$imagen = trim($data[0]['imgUrl']);
			$descripcion = $data[0]['description'];
			//$incluye = $data[0]['includeDescriptions'];
			$dias = trim($data[0]['days']);
			$pdf = trim($data[0]['verMas']);
			//$precio = $data[0]['pricing'];
			//$aerolinea = $data[0]['airline'];
			$operador = $data[0]['tourOperador'];
			$aeropuerto = $data[0]['destinationAirportName'];
			$destino_web = $data[0]['destination'];
			$tour_operador= str_replace('"]', '',str_replace('["', '',str_replace('","', '',$data[0]['tourOperador'])));
}
	
$rut      =(isset( $_POST['rut'] )) ? $_POST['rut'] :  '' ;
$digito    =(isset( $_POST['digito'] )) ? $_POST['digito'] :  '' ;
$cel_pais =(isset( $_POST['cel_pais'] )) ? $_POST['cel_pais'] :  '' ;
$cel_code =(isset( $_POST['cel_code'] )) ? $_POST['cel_code'] :  '' ;
$phone    =(isset( $_POST['phone'] )) ? $_POST['phone'] :  '' ;
$rut_c               = 	trim($rut).'-'.trim($digito);
$phone_home          = 	$cel_pais.$cel_code.$phone;

$numero_producto_c   =(isset( $_POST['numero_producto_c'] )&& $_POST['numero_producto_c'] != '' ) ? $_POST['numero_producto_c'] :  '' ;
$select_producto   =(isset( $_POST['select_producto'] )&& $_POST['select_producto'] != '' ) ? $_POST['select_producto'] :  '' ;
$url_wufo            = (isset( $_POST['url_wufo'] )&& $_POST['url_wufo'] != '' ) ? $_POST['url_wufo'] :  '' ;
$url_producto        = (isset( $_POST['url_producto'] )&& $_POST['url_producto'] != '' ) ? $_POST['url_producto'] :  '' ;
$pdf_producto        = (isset( $_POST['pdf_producto'] )&& $_POST['pdf_producto'] != '' ) ? $_POST['pdf_producto'] :  '' ;
$lead_source         =  (isset( $_POST['canal'] )&& $_POST['canal'] != '' ) ? $_POST['canal'] :  '' ;
$id_joomla           = (isset( $_POST['id_joomla'] )&& $_POST['id_joomla'] != '' ) ? $_POST['id_joomla'] :  '' ;
$numero_producto     =(isset( $_POST['id_joomla'] )&& $_POST['id_joomla'] != '' ) ? $_POST['id_joomla'] :  '' ;
$precio_joomla       = (isset( $_POST['monto_pack'] )&& $_POST['monto_pack'] != '' ) ? $_POST['monto_pack'] :  '' ;
$url_joomla          = (isset( $_POST['url_pack'] )&& $_POST['url_pack'] != '' ) ? $_POST['url_pack'] :  '' ;
$nombre_joomla       = (isset( $_POST['nombre_pack'] )&& $_POST['nombre_pack'] != '' ) ? $_POST['nombre_pack'] :  '' ;
$tour_operador       = (isset( $_POST['touroperador'] )&& $_POST['touroperador'] != '' ) ? $_POST['touroperador'] :  $operador ;
$pdf_joomla          = (isset( $_POST['pdf_joomla'] )&& $_POST['pdf_joomla'] != '' ) ? $_POST['pdf_joomla'] :  $pdf ;
$analytics           = (isset( $_POST['analytics'] )&& $_POST['analytics'] != '' ) ? $_POST['analytics'] :  '' ;
$tratamiento         = (isset( $_POST['tratamiento'] )&& $_POST['tratamiento'] != '' ) ? $_POST['tratamiento'] :  '' ;
$last_name           = (isset( $_POST['last_name'] )&& $_POST['last_name'] != '' ) ? $_POST['last_name'] :  '' ;
$first_name          = (isset( $_POST['first_name'] )&& $_POST['first_name'] != '' ) ? $_POST['first_name'] :  '' ;
$email1              = (isset( $_POST['email'] )&& $_POST['email'] != '' ) ? $_POST['email'] :  '' ;
$fecha_viaje_c       = (isset( $_POST['fecha_viaje'] )&& $_POST['fecha_viaje'] != '' && $_POST['fecha_viaje'] != '1990/01/01') ? date_format(date_create(str_replace('/','-',$_POST['fecha_viaje'])),'Y-m-d') :  '';
$fecha_flexible_c    = (isset( $_POST['fecha_flexible'] )&& $_POST['fecha_flexible'] != '' ) ? $_POST['fecha_flexible'] :  '' ;
$habitaciones_c      = (isset( $_POST['numero_habitaciones'] )) ? $_POST['numero_habitaciones'] :  '' ;
$pasajeros_adultos_c =(isset( $_POST['pasajeros_adultos'] )&& $_POST['pasajeros_adultos'] != '' ) ? $_POST['pasajeros_adultos'] :  '' ;
$pasajeros_ninos_c   =(isset( $_POST['pasajeros_ninos'] )&& $_POST['pasajeros_ninos'] != '' ) ? $_POST['pasajeros_ninos'] :  '' ;
$comentarios_check   =(isset( $_POST['comentarios_check'] )&& $_POST['comentarios_check'] != '' ) ? $_POST['comentarios_check'] :  '' ;
$description         = (isset( $_POST['description_cliente'] )&& $_POST['description_cliente'] != '' ) ? $_POST['description_cliente'] :  '' ;
$agente_check        = (isset( $_POST['agente_check'] )&& $_POST['agente_check'] != '' ) ? $_POST['agente_check'] :  '' ;
$agente_c            = (isset( $_POST['agente'] )&& $_POST['agente'] != '' ) ? $_POST['agente'] :  '' ;
$decripcion_resumen  =(isset( $_POST['description_resumen'] )&& $_POST['description_resumen'] != '' ) ? $_POST['description_resumen'] :  '' ;
$monto_dolares       =(isset( $_POST['monto_dolares'] )&& $_POST['monto_dolares'] != '' ) ? $_POST['monto_dolares'] :  '' ;
$monto_pesos         =(isset( $_POST['monto_pesos'] )&& $_POST['monto_pesos'] != '' ) ? $_POST['monto_pesos'] :  '' ;
$destino             =(isset( $_POST['destino'] )&& $_POST['destino'] != '' ) ? $_POST['destino'] :  $destino_web ;
$hotel               =(isset( $_POST['nombre_hotel'] )&& $_POST['nombre_hotel'] != '' ) ? $_POST['nombre_hotel'] :  '' ;
$categoria_id        =(isset( $_POST['categoria_id'] )&& $_POST['categoria_id'] != '' ) ? $_POST['categoria_id'] :  '' ;
//$categoria_nombre    =(isset( $_POST['categoria_nombre']) && $_POST['categoria_nombre'] != '' ) ?  $_POST['categoria_nombre'] :  $categoria_nombre_web ;
if (isset( $_POST['categoria_nombre']) && $_POST['categoria_nombre'] != '' ){
	$categoria_nombre = $_POST['categoria_nombre'];
}else{
	if (isset($categoria_nombre_web) && $categoria_nombre_web != '' ){
		$categoria_nombre = $categoria_nombre_web;
	}else{
		$categoria_nombre = 'SIN CATEGORIA';
	}
}

$detalle_jsom        =(isset( $_POST['detalle_json'] )&& $_POST['detalle_json'] != '' ) ? $_POST['detalle_json'] :  '' ;

for($i=0;$i<11;$i++)
{
	if ($i== 0){
		$edad_nino= $_POST['edad_nino-'.$i];
	}else{
		if(isset($_POST['edad_nino-'.$i]) && $_POST['edad_nino-'.$i] != '')  $edad_nino= $edad_nino." - ".$_POST['edad_nino-'.$i];
	}
	
}

if ($email1){
		
	//leads
	 $leads = new Lead();
	 //es usado para validar por rut, victor solicito que sea por email.21/10/2015
			if (isset($_POST['rut']) && $_POST['rut'] != '' ){
				$query = "select id from leads where campaign_id = '4061e35f-dbab-e5c1-221c-56548dc162bd' and phone_fax = '".trim($rut_c)."'";
				$GLOBALS['log']->info('INICIO1 '.$query);
				$res = $db->Query($query);
				if($row = $db->fetchByAssoc($res)){
					$leads->id =  $row['id'];
				} 
			}
	//este query es utilizado para verificar si el contacto existe
		if (empty($leads->id) || $leads->id == ''){
			 $query = "SELECT l.id 
						FROM email_addresses e
						join email_addr_bean_rel er on er.email_address_id= e.id and er.bean_module='Leads' and  er.deleted=0
						join leads l on l.id = er.bean_id  and l.campaign_id = '4061e35f-dbab-e5c1-221c-56548dc162bd'
						WHERE e.email_address = '".$email1."' 
						AND e.deleted=0";
						$GLOBALS['log']->info('INICIO 2 '.$query);
				$res = $db->Query($query);
				
				if($row = $db->fetchByAssoc($res)){
					$leads->id =  $row['id'];
				}  	
		}
			
	//sino existe el contacto se crea
	if (empty($leads->id)){
	
		
		$leads->last_name=$last_name;
		$leads->first_name=$first_name;
		$leads->phone_fax=trim($rut_c);
		$leads->phone_home=$phone_home;
		if (isset($select_producto) && $select_producto != ''){
			$leads->lead_source=$select_producto;	 
		}else{ 
			$leads->lead_source=$lead_source;
		}
		$leads->email1=$email1;
		$leads->campaign_id='4061e35f-dbab-e5c1-221c-56548dc162bd';
		$leads->save();
	}
	
	//solicitudes
	$solicitud = new crm_solicitudes();
	$solicitud->name=$nombre_joomla;
	$solicitud->destino=$destino;
	$solicitud->fecha_viaje=$fecha_viaje_c;
	$solicitud->hotel=$hotel;
	if ($hotel){
		$solicitud->hotel_tarifas=$monto_dolares." ".$monto_pesos;
	}
	$solicitud->hotel_habitacion=$habitaciones_c;
	$solicitud->adultos=$pasajeros_adultos_c;
	$solicitud->ninos=$pasajeros_ninos_c;
	$solicitud->edades=$edad_nino;
	if (isset($select_producto) && $select_producto != ''){
		$solicitud->canal=$select_producto;	 
	}else{ 
		$solicitud->canal=$lead_source;
	}
	$solicitud->pdf_joomla=$pdf_joomla;
	$solicitud->analytics=$analytics;
	$solicitud->fecha_flexible=$fecha_flexible_c;
	$solicitud->comentarios_check=$comentarios_check;
	$solicitud->description=$description;
	$solicitud->agente_check=$agente_check;
	$solicitud->agente=$agente_c;
	$solicitud->url=$url_joomla;
	$solicitud->precio=$precio_joomla;
	$solicitud->producto_id=$id_joomla;
	$solicitud->pdf_producto=$pdf_producto;
	$solicitud->url_producto=$url_producto;
	$solicitud->tour_operador=$tour_operador;
	$solicitud->url_wufo=$url_wufo;
	$solicitud->numero_producto=$numero_producto;
	$solicitud->categoria_id=$categoria_id;
	$solicitud->categoria_nombre=$categoria_nombre;
	$solicitud->save();
	
	//opportunities
	$Opportunitys = new Opportunity();
	//condicion para el caso de una consulta en un periodo
	$query = "SELECT leads_opportunities_1opportunities_idb
		FROM leads_opportunities_1_c
		WHERE date_modified >= DATE_ADD( now( ) , INTERVAL -19 HOUR )
		AND date_modified <= DATE_ADD( now( ) , INTERVAL 5 HOUR )
		AND deleted =0 AND leads_opportunities_1leads_ida = '".$leads->id."' ORDER BY date_modified DESC 	LIMIT 0 , 5";
		$res = $db->Query($query);
		
		if($row = $db->fetchByAssoc($res)){
			$Opportunitys->id =  $row['leads_opportunities_1opportunities_idb'];
			if ($description != ''){
				$Opportunitys->retrieve($Opportunitys->id);
				$Opportunitys->description=$Opportunitys->description."\n".$nombre_joomla.': '.$description;
				$Opportunitys->save();
			}
		}else{
				$Opportunitys->id = null;
		}       
	if (empty($Opportunitys->id)){
		
		if (isset($select_producto) && $select_producto != ''){
			$Opportunitys->lead_source=$select_producto; 
		}else{ 
			$Opportunitys->lead_source=$lead_source;
		}
		if (isset($decripcion_resumen) && $decripcion_resumen != ''){
			$Opportunitys->resumen_c = $decripcion_resumen; 
		}else{ 
			$decripcion_resumen = "nombre_pack: [ ".$nombre_joomla." ]
									Destino: [ ".$destino." ]
									Salida: [ ".$fecha_viaje_c." ] 
									Monto total calculado [ ".$monto_dolares." ] (".$monto_pesos.")
									Habitación:  [ ".$habitaciones_c." ] 
									adulto(s): [ ".$pasajeros_adultos_c." ]
									niño(s):  [ ".$pasajeros_ninos_c." ]
									Edades de niños: [ ".$edad_nino." ]" ;
			
			$Opportunitys->resumen_c = $decripcion_resumen; 
		}
		if ($description != ''){
			$Opportunitys->description=$nombre_joomla.': '.$description;
		}else{
			$Opportunitys->description='';
		}
		$Opportunitys->analytics=$analytics;
		$Opportunitys->name=$categoria_nombre." / ".$first_name." ".$last_name;
		$Opportunitys->sales_stage='recepcionado';
		$Opportunitys->opportunity_type='solicitud_web';
		$Opportunitys->priority_c='Low';
		$Opportunitys->assigned_user_id = '1';
		if (isset($select_producto) && $select_producto != ''){
			$Opportunitys->lead_source=$select_producto;	 
		}else{ 
			$Opportunitys->lead_source=$lead_source;
		}
		$Opportunitys->resumen_c = $decripcion_resumen;
		$Opportunitys->amount = $precio_joomla;
		$Opportunitys->amount_usdollar = $precio_joomla;
		$Opportunitys->date_closed = date('Y-m-d',strtotime('+4 day'));
		$Opportunitys->resumen_json_c = $detalle_jsom;    
		$Opportunitys->crm_solicitud_id_c = $solicitud->id;
		$Opportunitys->save();
		
		//relacion lead oportunidad
		$leads->load_relationship('leads_opportunities_1');
		$leads->leads_opportunities_1->add($Opportunitys->id);
	}
	
	//relacion oportunidad solicitudes
	$Opportunitys->load_relationship('opportunities_crm_solicitudes_1');
    $Opportunitys->opportunities_crm_solicitudes_1->add($solicitud->id);
	
	echo "Se almacenaron los siguientes registros: </br> Oportunidad ID: ".$Opportunitys->id."</br> Leads ID: ".$leads->id."</br> Solicitudes id: ".$solicitud->id;
	// 
	die();
}else{
	echo "Debe enviar emial";
}

?>
