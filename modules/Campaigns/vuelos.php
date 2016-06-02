<?php
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
require_once('custom/application/acceso.php');
require_once('service/v4/SugarWebServiceImplv4.php');
require_once('service/v4_1/SugarWebServiceUtilv4_1.php');
   
   
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Origin, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers');


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


if(isset($_POST['Field120'])){
	if($_POST['Field120'] != ''){//todas las solicitudes del forumlario wufo entel
		$_POST['campaign_id'] = $_POST['Field120'];//campaña
		$_POST['first_name'] = $_POST['Field3'];//nombre
		$_POST['last_name'] = $_POST['Field4'];//apellido
		$_POST['rut_c'] = $_POST['Field223'];//rut
		$_POST['email1'] = $_POST['Field117'];
		$_POST['phone_home'] = $_POST['Field113'];
		$_POST['description'] = $_POST['Field111'];
		$_POST['fecha_regreso_c'] = $_POST['Field111'];
		$_POST['Field225'] = $_POST['Field225'];//fecha ida
		$_POST['Field230'] = $_POST['Field230'];//fecha regreso
		$recibir_informacion=0;
		if(isset($_POST['Field227']))$recibir_informacion = $_POST['Field227'];
		if($recibir_informacion == '1')  $_POST['do_not_call'] = '1'; else $_POST['do_not_call'] = 0;//No recibir informacion

		if( strpos($_POST['Field225'], "/")==false && strpos($_POST['Field225'], "-") ==false){//wufo
			$_POST['Field225']=substr($_POST['Field225'],0,4).'-'.substr($_POST['Field225'],4,2).'-'.substr($_POST['Field225'],6,2);
		}
		if( strpos($_POST['Field230'], "/")==false && strpos($_POST['Field230'], "-") ==false){//wufo
			$_POST['Field230']=substr($_POST['Field230'],0,4).'-'.substr($_POST['Field230'],4,2).'-'.substr($_POST['Field230'],6,2);
		}

		$_POST['fecha_ida_c'] = $_POST['Field225'];//fecha ida
		$_POST['fecha_regreso_c'] = $_POST['Field230'];//fecha regreso

		$_POST['assigned_user_id'] = '1';//cambio realizado 26-1-15
	   // $_POST['assigned_user_id'] = '16ac453a-70da-d78d-117a-5475cfb0a2b4';//este estaba antes
		
	}
}
//caso vuelos por parte del backend
$json = file_get_contents('php://input');
//$GLOBALS['log']->fatal($json);
$data=json_decode($json); 
if(isset($data->id_transaccion) && $data->id_transaccion != ''){
	echo 'vuelos_backend';
	$_POST['campaign_id']=$data->id_campana;
	$_POST['lead_source']='Web'; 
	$_POST['lead_source_description']='vuelos_backend';       
	$_POST['first_name']=$data->names;     
	$_POST['status_description']=$data->pnr;
	$_POST['account_description']=$data->id_transaccion;                            
	$_POST['last_name']=$data->surnames;       
	$_POST['email1']=$data->email;
	$_POST['description']=$json; 
}else  if(isset($data->notifications) && ($data->notifications != '')) {
	echo 'vuelos_frontend';
	$_POST['campaign_id']=$data->campaign;
	$_POST['lead_source']='Web'; 
	$_POST['lead_source_description']='vuelos_frontend';       
	$_POST['first_name']=$data->flightData->contact->names;     
	$_POST['status_description']=$data->flightData->pnr;
	$_POST['account_description']=$data->id_transaccion;                            
	$_POST['last_name']=$data->flightData->contact->surnames;       
	$_POST['email1']=$data->flightData->contact->email;
	$_POST['description']=$json;   
}
	if(!isset($_POST['lead_source'])){
		$_POST['lead_source']= 'web';
	}
	$_POST['ip_registro_c']= $_SERVER['REMOTE_ADDR'];
	
	if(!isset($_POST['website'])){
		$_POST['website']= $_SERVER['HTTP_REFERER'];
	}
/////////////////////////////////////////////
if (isset($_POST['campaign_id']) && !empty($_POST['campaign_id'])) {
				// busqueda del id lead
			/*	$sql =	"select lea.id as lead  
						from email_addr_bean_rel ema
						join email_addresses ema1 on ema.email_address_id = ema1.id and ema1.email_address = '".$_POST['email1']."'
						join leads lea on lea.id = ema.bean_id and lea.campaign_id = '".$_POST['campaign_id']."'
						where ema.bean_module = 'Leads'";

					  $resultado = $db->query($sql);
					  $row = $db->fetchByAssoc($resultado);	  



				// si existe el email se actualizaran los registros	  
			if(isset($row['lead'])){
				foreach ($_POST as $key => $value){
					//excluyo los campos que no son de la tabla
					if (substr($key,0,5) != 'Field'){
						if($_POST[$key] != ''){
							$leads->$key = $value;
						}
					}
				}
				$leads->modulo = 'leads';
				$leads->session = $login_result->id;
				$leads->id = $row['lead'];
				$parametros = array(
					'datos' => $leads
				);	
				
				//llamo a la funcion call para realizar la conexion del webservice
				$leads->id = call("acceso_user",$parametros, $_SESSION['url']);
				$rep['respuesta']="Registros Actualizado";
				echo json_encode($rep);
				die();


			} */
	    //adding the client ip address
	    $_POST['client_id_address'] = query_client_ip();
		$campaign_id=$_POST['campaign_id'];
		$campaign = new Campaign();
		$camp_query  = "select name,id from campaigns where id='$campaign_id'";
		$camp_query .= " and deleted=0";
        $camp_result=$campaign->db->query($camp_query);
        $camp_data = $campaign->db->fetchByAssoc($camp_result);
        // Bug 41292 - have to select marketing_id for new lead
        $db = DBManagerFactory::getInstance();
        $marketing = new EmailMarketing();
        $marketing_query = $marketing->create_new_list_query(
                'date_start desc, date_modified desc',
                "campaign_id = '{$campaign_id}' and status = 'active' and date_start < " . $db->convert('', 'today'),
                array('id')
        );
        $marketing_result = $db->limitQuery($marketing_query, 0, 1, true);
        $marketing_data = $db->fetchByAssoc($marketing_result);
        // .Bug 41292
		if (isset($_REQUEST['assigned_user_id']) && !empty($_REQUEST['assigned_user_id'])) {
			$current_user = new User();
			$current_user->retrieve($_REQUEST['assigned_user_id']);
		} 

	    if(isset($camp_data) && $camp_data != null ){
			$leadForm = new LeadFormBase();
            $lead = new Lead();
			$prefix = '';
			if(!empty($_POST['prefix'])){
				$prefix = $_POST['prefix'];
			}

			if(empty($lead->id)) {
                $lead->id = create_guid();
                $lead->new_with_id = true;
            }
            $GLOBALS['check_notify'] = true;

            //bug: 47574 - make sure, that webtolead_email1 field has same required attribute as email1 field
            if(isset($lead->required_fields['email1'])){
                $lead->required_fields['webtolead_email1'] = $lead->required_fields['email1'];
            }
            
            //bug: 42398 - have to unset the id from the required_fields since it is not populated in the $_POST
            unset($lead->required_fields['id']);
            unset($lead->required_fields['team_name']);
            unset($lead->required_fields['team_count']);

            // Bug #52563 : Web to Lead form redirects to Sugar when duplicate detected
            // prevent duplicates check
            $_POST['dup_checked'] = true;

            // checkRequired needs a major overhaul before it works for web to lead forms.
            $lead = $leadForm->handleSave($prefix, false, false, false, $lead);
            
			if(!empty($lead)){
				
	            //create campaign log
	            $camplog = new CampaignLog();
	            $camplog->campaign_id  = $_POST['campaign_id'];
	            $camplog->related_id   = $lead->id;
	            $camplog->related_type = $lead->module_dir;
	            $camplog->activity_type = "lead";
	            $camplog->target_type = $lead->module_dir;
	            $campaign_log->activity_date=$timedate->now();
	            $camplog->target_id    = $lead->id;
                if(isset($marketing_data['id']))
                {
                    $camplog->marketing_id = $marketing_data['id'];
                }
	            $camplog->save();

		        //link campaignlog and lead

		        if (isset($_POST['email1']) && $_POST['email1'] != null)
                {
                    $lead->email1 = $_POST['email1'];
		        } 
                //in case there are old forms used webtolead_email1
                elseif (isset($_POST['webtolead_email1']) && $_POST['webtolead_email1'] != null)
                {
                    $lead->email1 = $_POST['webtolead_email1'];
                }
                
		        if (isset($_POST['email2']) && $_POST['email2'] != null)
                {
                    $lead->email2 = $_POST['email2'];
		        } 
                //in case there are old forms used webtolead_email2
                elseif (isset($_POST['webtolead_email2']) && $_POST['webtolead_email2'] != null)
                {
                    $lead->email2 = $_POST['webtolead_email2'];
                }
                
		        $lead->load_relationship('campaigns');
		        $lead->campaigns->add($camplog->id);
                if(!empty($GLOBALS['check_notify'])) {
                    $lead->save($GLOBALS['check_notify']);
					$rep['respuesta']="Registros Guardado";
					echo json_encode($rep);
                }
                else {
                    $lead->save(FALSE);
                }
				//aqui se debe insertar los datos de loa cliente y las relaciones del leads
            }

            //in case there are forms out there still using email_opt_out
            if(isset($_POST['webtolead_email_opt_out']) || isset($_POST['email_opt_out'])){
                    
                if(isset ($lead->email1) && !empty($lead->email1)){
                    $sea = new SugarEmailAddress();
                    $sea->AddUpdateEmailAddress($lead->email1,0,1);
                }   
                if(isset ($lead->email2) && !empty($lead->email2)){
                    $sea = new SugarEmailAddress();
                    $sea->AddUpdateEmailAddress($lead->email2,0,1);
                    
                }
            }              
			if(isset($_POST['redirect_url']) && !empty($_POST['redirect_url'])){
			    // Get the redirect url, and make sure the query string is not too long
		        $redirect_url = $_POST['redirect_url'];
		        $query_string = '';
				$first_char = '&';
				if(strpos($redirect_url, '?') === FALSE){
					$first_char = '?';
				}
				$first_iteration = true;
				$get_and_post = array_merge($_GET, $_POST);
				foreach($get_and_post as $param => $value) {

					if($param == 'redirect_url' && $param == 'submit')
						continue;
					
					if($first_iteration){
						$first_iteration = false;
						$query_string .= $first_char;
					}
					else{
						$query_string .= "&";
					}
					$query_string .= "{$param}=".urlencode($value);
				}
				if(empty($lead)) {
					if($first_iteration){
						$query_string .= $first_char;
					}
					else{
						$query_string .= "&";
					}
					$query_string .= "error=1";
				}
				
				$redirect_url = $redirect_url.$query_string;


				// Check if the headers have been sent, or if the redirect url is greater than 2083 characters (IE max URL length)
				//   and use a javascript form submission if that is the case.
			    if(headers_sent() || strlen($redirect_url) > 2083){
    				echo '<html ' . get_language_header() . '><head><title>SugarCRM</title></head><body>';
    				echo '<form name="redirect" action="' .$_POST['redirect_url']. '" method="GET">';
    
    				foreach($_POST as $param => $value) {
    					if($param != 'redirect_url' ||$param != 'submit') {
    						echo '<input type="hidden" name="'.$param.'" value="'.$value.'">';
    					}
    				}
    				if(empty($lead)) {
    					echo '<input type="hidden" name="error" value="1">';
    				}
    				echo '</form><script language="javascript" type="text/javascript">document.redirect.submit();</script>';
    				echo '</body></html>';
    			}
				else{
    				header("Location: {$redirect_url}");
    				die();
			    }
			}
			else{
				//echo $mod_strings['LBL_THANKS_FOR_SUBMITTING_LEAD'];
				if ($_POST['campaign_id'] =! 'b019ca1c-0ad6-b892-916c-56b1029a7c1a'){
					echo '<div class="texto-titulo" style="font-size: 18px; padding: 12px; width: 98%; display: block; margin-top: -35px; color: #FFF;"><br />
							<p style="text-align: center;"><span style="font-family: Arial, Helvetica, sans-serif; font-size: 18px; color: #FFF;">Gracias por inscribirte.<br/> ¡Ya estás participando por un viaje a Punta Cana!</span></p>';
				}
			}
			sugar_cleanup();
			// die to keep code from running into redirect case below
			die();
	    }
	   else{
	  	  echo $mod_strings['LBL_SERVER_IS_CURRENTLY_UNAVAILABLE'];
	  }
}

if (!empty($_POST['redirect'])) {
    if(headers_sent()){
    	echo '<html ' . get_language_header() . '><head><title>SugarCRM</title></head><body>';
    	echo '<form name="redirect" action="' .$_POST['redirect']. '" method="GET">';
    	echo '</form><script language="javascript" type="text/javascript">document.redirect.submit();</script>';
    	echo '</body></html>';
    }
    else{
    	header("Location: {$_POST['redirect']}");
    	die();
    }
}

echo $mod_strings['LBL_SERVER_IS_CURRENTLY_UNAVAILABLE'];

?>
