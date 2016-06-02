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
$GLOBALS['log']->info('INICIO');

global $app_strings, $app_list_strings, $sugar_config, $timedate, $current_user,$db;


$mod_strings = return_module_language($sugar_config['default_language'], 'Leads');

$app_list_strings['record_type_module'] = array('Contact'=>'Contacts', 'Account'=>'Accounts', 'Opportunity'=>'Opportunities', 'Case'=>'Cases', 'Note'=>'Notes', 'Call'=>'Calls', 'Email'=>'Emails', 'Meeting'=>'Meetings', 'Task'=>'Tasks', 'Lead'=>'Leads','Bug'=>'Bugs',

);

/**
 * To make your changes upgrade safe create a file called leadCapture_override.php and place the changes there
 */
$users = array(
	'PUT A RANDOM KEY FROM THE WEBSITE HERE' => array('name'=>'PUT THE USER_NAME HERE', 'pass'=>'PUT THE USER_HASH FOR THE RESPECTIVE USER HERE'),
);
	
/*
$numero_producto_c =  	$_POST['Field120'];
$url_wufo = 			$_POST['Field240'];
$url_producto = 		$_POST['Field300'];
$pdf_producto = 		$_POST['Field301'];
$lead_source = 			$_POST['Field227'];
$id_joomla = 			$_POST['Field230'];
$precio_joomla = 		$_POST['Field233'];
$url_joomla = 			$_POST['Field232'];
$nombre_joomla = 		$_POST['Field234'];
$tour_operador = 		$_POST['Field236'];
$pdf_joomla = 			$_POST['Field238'];
$analytics = 			$_POST['analytics'];
$tratamiento = 			$_POST['tratamiento'];
$last_name = 			$_POST['Field4'];
$first_name = 			$_POST['Field3'];
$rut_c = 				trim($_POST['rut']).'-'.trim($_POST['digito']);
$phone_home = 			$_POST['cel_pais'].$_POST['cel_code'].$_POST['phone'];
$email1 = 				$_POST['Field117'];
$fecha_viaje_c = 		$_POST['Field225'];
$fecha_flexible_c = 	$_POST['fecha_flexible'];
$habitaciones_c = 		$_POST['habitaciones'];
$pasajeros_adultos_c =	$_POST['Field109'];
$pasajeros_ninos_c = 	$_POST['Field110'];
$comentarios_check = 	$_POST['comentarios_check'];
$description = 			$_POST['Field111'];
$destino=				$_POST['Field111'];
$hotel=					$_POST['Field111'];
$hotel_tarifas=			$_POST['Field111'];
$numero_producto=		$_POST['Field230'];;
$precio=				$_POST['Field111'];
$categoria_id=			$_POST['Field111'];
$categoria_nombre=		$_POST['Field111'];
$agente_check = 		$_POST['agente_check'];
$agente_c = 			$_POST['Field118'];
*/
echo "inactivo";
die();
$numero_producto_c   =	$_POST['numero_producto_c'];
$url_wufo            = 	$_POST['url_wufo'];
$url_producto        = 	$_POST['url_producto'];
$pdf_producto        = 	$_POST['pdf_producto'];
$lead_source         =  $_POST['canal'];
$id_joomla           = 	$_POST['id_joomla'];
$numero_producto     =	$_POST['id_joomla'];
$precio_joomla       = 	$_POST['monto_pack'];
$url_joomla          = 	$_POST['url_pack'];
$nombre_joomla       = 	$_POST['nombre_pack'];
$tour_operador       = 	$_POST['touroperador'];
$pdf_joomla          = 	$_POST['pdf_joomla'];
$analytics           = 	$_POST['analytics'];
$tratamiento         = 	$_POST['tratamiento'];
$last_name           = 	$_POST['last_name'];
$first_name          = 	$_POST['first_name'];
$rut_c               = 	$_POST['rut'];
$phone_home          = 	$_POST['phone'];
$email1              = 	$_POST['email'];
$fecha_viaje_c       = 	$_POST['fecha_viaje'];
$fecha_flexible_c    = 	$_POST['fecha_flexible'];
$habitaciones_c      = 	$_POST['numero_habitaciones'];
$pasajeros_adultos_c =	$_POST['pasajeros_adultos'];
$pasajeros_ninos_c   =	$_POST['pasajeros_ninos'];
//$edad_nino           =	$_POST['edad_nino'];
$comentarios_check   =	$_POST['comentarios_check'];
$description         = 	$_POST['description_cliente'];
$agente_check        = 	$_POST['agente_check'];
$agente_c            = 	$_POST['agente'];
$decripcion_resumen  =	$_POST['description_resumen'];
$monto_dolares       =	$_POST['monto_dolares'];
$monto_pesos         =	$_POST['monto_pesos'];
$destino             =	$_POST['destino'];
$hotel               =	$_POST['nombre_hotel'];
$categoria_id        =	$_POST['categoria_id'];
$categoria_nombre    =	$_POST['categoria_nombre'];
$detalle_jsom        =	$_POST['detalle_json'];


if ($email1){

		for($i=0;$i<10;$i++)
		{
			if ($i== 0){
				$edad_nino= $_POST['edad_nino-'.$i];
			}else{
				if(isset($_POST['edad_nino-'.$i]))  $edad_nino= $edad_nino.",".$_POST['edad_nino-'.$i];
			}
			
		}
	
	//leads
	 $leads = new Lead();
	 //es usado para validar por rut, victor solicito que sea por email.21/10/2015
			$query = "select id from leads where phone_fax = '".trim($rut_c)."'";
			$res = $db->Query($query);
			if($row = $db->fetchByAssoc($res)){
				$leads->id =  $row['id'];
			} 
	//este query es utilizado para verificar si el contacto existe
		if (empty($leads->id)){
			 $query = "SELECT email_addr_bean_rel.bean_id 
				FROM email_addresses inner join email_addr_bean_rel on email_address_id=email_addresses.id
				WHERE email_addresses.email_address = '".$email1."' AND email_addresses.deleted=0
				and bean_module='Leads' and  email_addr_bean_rel.deleted=0";
				$res = $db->Query($query);
				
				if($row = $db->fetchByAssoc($res)){
					$leads->id =  $row['bean_id'];
				}  	
		}
			
	//sino existe el contacto se crea
	if (empty($leads->id)){
	
		
		$leads->last_name=$last_name;
		$leads->first_name=$first_name;
		$leads->phone_fax=trim($rut_c);
		$leads->phone_home=$phone_home;
		$leads->email1=$email1;
		$leads->save();
	}
	
	//solicitudes
	$solicitud = new crm_solicitudes();
	$solicitud->name=$nombre_joomla;
	$solicitud->destino=$destino;
	$solicitud->fecha_viaje=$fecha_viaje_c;
	$solicitud->hotel=$hotel;
	$solicitud->hotel_tarifas=$monto_dolares." ".$monto_pesos;
	$solicitud->hotel_habitacion=$habitaciones_c;
	$solicitud->adultos=$pasajeros_adultos_c;
	$solicitud->ninos=$pasajeros_ninos_c;
	$solicitud->edades=$edad_nino;
	$solicitud->canal=$lead_source;
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
		}else{
				$Opportunitys->id = null;
		}       
	if (empty($Opportunitys->id)){
	
		$Opportunitys->lead_source=$lead_source;
		$Opportunitys->description=$detalle_jsom;
		$Opportunitys->analytics=$analytics;
		$Opportunitys->name=$nombre_joomla." / ".$first_name." ".$last_name;
		$Opportunitys->sales_stage='recepcionado';
		$Opportunitys->opportunity_type='solicitud_web';
		$Opportunitys->priority_c='Low';
		$Opportunitys->assigned_user_id = '1';
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
