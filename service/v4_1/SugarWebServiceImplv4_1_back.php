<?php
if (!defined('sugarEntry')) define('sugarEntry', true);
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


/**
 * SugarWebServiceImplv4_1.php
 *
 * This class is an implementation class for all the web services.  Version 4_1 adds limit/off support to the
 * get_relationships function.  We also added the sync_get_modified_relationships function call from version
 * one to facilitate querying for related meetings/calls contacts/users records.
 *
 */
require_once('service/v4/SugarWebServiceImplv4.php');
require_once('service/v4_1/SugarWebServiceUtilv4_1.php');

class SugarWebServiceImplv4_1 extends SugarWebServiceImplv4
{

    /**
     * Class Constructor Object
     *
     */
    public function __construct()
    {
        self::$helperObject = new SugarWebServiceUtilv4_1();
    }

 
//-----------------------------------------------------------------
// creado por armando 23/12/2015
//evento utilizado para detectar la actividad del usuario
function actividad_user ($usuario_asig){
	global $db;
	$update= "update users_cstm u set u.ultimo_acceso_c = now() where u.id_c = '".$usuario_asig."'";
	$resul = $db->Query($update);
}
// creado por armando 02/11/2015	
	/*es utilizado para enviar los contadores al actualizar la pagina*/
	function get_cantidad_inicio_nuevos ($session,$usuario_asig){
							global $db, $sugar_config;
							$error = new SoapError();
							//authenticate
							if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_cantidad_inicio_nuevos', '', '', '',  $error))
							{
								$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
								return false;
							}
							//evento utilizado para detectar la actividad del usuario
							$this->actividad_user($usuario_asig);
							//------------------------------------------------------
							//llamo el procedimiento
							$query="CALL crm_cantidad_oportunidades_new('".$usuario_asig."', @cantidad_cross, @cantidad_recompra, @cantidad_fugados, @cantidad_solicitudes)";
							$respt = $db->Query($query);
							//recupero las variables del procedimiento
							 $query_2="   	select @cantidad_cross as cantidad_cross, 
											    @cantidad_recompra as cantidad_recompra, 
											    @cantidad_fugados as cantidad_fugados, 
											    @cantidad_solicitudes as cantidad_solicitudes from dual";
							$respt2 = $db->Query($query_2);
							
							while($value = $db->fetchByAssoc($respt2)){
								$cantidad['cantidad_cross'] = $value['cantidad_cross'];
								$cantidad['cantidad_recompra'] = $value['cantidad_recompra'];
								$cantidad['cantidad_fugados'] = $value['cantidad_fugados'];
								$cantidad['cantidad_web_nuevas'] = $value['cantidad_solicitudes'];
								
								$query_3=" select cantidad_proximos_viajes('".$usuario_asig."') as proximos from dual";	
								 $respt_3 = $db->Query($query_3); 
								 if($row_1 = $db->fetchByAssoc($respt_3)){
									  $cantidad['cantidad_proximos'] = $row_1['proximos'];
								 }	
								$tarea= new Task();
								$list_task = $tarea->get_full_list("", " tipo_c = 'Retornos' and tasks.status = 'Not Started' and tasks.assigned_user_id = '".$usuario_asig."' ",true);
								$cantidad['cantidad_retorno'] = count($list_task);
								
								$contacto = new Contact();
								$list_cum = $contacto->get_full_list("", "month(contacts.birthdate) = month(now()) and contacts.assigned_user_id = '".$usuario_asig."' ",true);					
								$cantidad['cantidad_cumple_mes'] = count($list_cum);  	
							 }
							return $cantidad;      
	}
// creado por armando 02/11/2015					
	/*solicitudes web*/
		/*obtiene la cantidad de solicitudes web nuevas*/
	function get_cantidad_web_nuevas($session,$usuario_asig){
				global $db, $sugar_config;
				$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_cantidad_web_nuevas', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
				//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
				$query="  select cantidad_solicitudes('".$usuario_asig."') as solicitudes from dual";	
				 $respt = $db->Query($query); 
				 if($row_1 = $db->fetchByAssoc($respt)){
					$cantidad=$row_1['solicitudes'];  
				}
				return $cantidad;  		  					
	}
// creado por armando 02/11/2015		
	 /*obtiene los datos de las solicitudes web nuevas*/
	function get_solicitudes_web_nuevas($session,$usuario_asig){
					global $db, $sugar_config;
					$error = new SoapError();
					//authenticate
					if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_solicitudes_web_nuevas', '', '', '',  $error))
					{
						$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
						return false;
					}
//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
					 $query=" 
					 select distinct o.id AS id_oportunidad,
									o.name AS nombre_oportunidad,
									o.lead_source AS origen_solcitud,
									o.sales_stage AS estado_solcitud,
									o.amount AS monto_oportunidad,
									s.description AS description,
									s.adultos AS pasajeros_adultos_c,
									s.ninos AS pasajeros_ninos_c,
									s.fecha_viaje AS fecha_viaje_c,
									s.hotel_habitacion AS habitaciones_c,
									-- date_format(DATE_ADD(do.date_assigned_c,INTERVAL -180 MINUTE), '%Y-%m-%d %H:%i:%s') AS fecha_asignacion_c,
									do.date_assigned_c AS fecha_asignacion_c,
									concat(l.first_name,' ',l.last_name) AS nombre_comprador,
									l.phone_home AS phone_home,
									l.phone_fax AS rut_comprador,
									l.id AS codigo_lead
									from opportunities o 
									join opportunities_cstm do on do.id_c = o.id 
									join leads_opportunities_1_c rc on rc.leads_opportunities_1opportunities_idb = do.id_c
									join leads l on l.id = rc.leads_opportunities_1leads_ida 
									join crm_solicitudes s on s.id = do.crm_solicitud_id_c
									where o.opportunity_type = 'solicitud_web'
									and o.deleted = 0
									and rc.deleted = 0 
									and l.deleted = 0
									and o.assigned_user_id = '".$usuario_asig."'  
									and o.sales_stage in ('recepcionado','asignado','AsignacionSolicitudSupervisor','RecepcionSolicitud')
									order by do.date_assigned_c desc";	

					$respt = $db->Query($query);
					
					while ($fila = $db->fetchByAssoc($respt)) {
						
						$obj ['nombre_oportunidad']   =$fila['nombre_oportunidad'];
						$obj ['id_oportunidad']       =$fila['id_oportunidad'];
						$obj ['fecha_viaje_c']        =$fila['fecha_viaje_c'];
						$obj ['fecha_asignacion_c']   =$fila['fecha_asignacion_c'];
						$obj ['nombre_comprador']     =ucwords($fila['nombre_comprador']);
						$obj ['rut_c']                =$fila['rut_comprador'];
						$obj ['id_cli'] 		 		=$fila['codigo_lead'];
						$obj ['description'] 		 		=$fila['description'];
						$obj ['estado_solcitud']=$fila['estado_solcitud'];
						
						if (!empty($fila['phone_home'])){
								$obj ['phone_office']         =$fila['phone_home'];
							}else{
								$obj ['phone_office']= 'Sin Telefono';
							}
								$contacto =new Lead();
								$contacto->retrieve($fila['codigo_lead']);
								if(!empty($contacto->email1)){
									$email=$contacto->email1;
								}else{
									$email = 'Sin Email';
								}
								$obj ['email_address']=$email;
								$obj ['c_envio_email'] = 0 ;
						$objeto []= $obj;
					}
					if (empty($objeto)){
						return '';
					}else{
						/*return $db->fetchByAssoc($respt);*/
						return $objeto;
					}
					
	}
// creado por armando 02/11/2015		
	/*obtiene los datos de las solicitudes web pendiente*/
	function get_solicitudes_web_pendientes($session,$usuario_asig){
					global $db, $sugar_config;
					$error = new SoapError();
					//authenticate
					if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_solicitudes_web_pendientes', '', '', '',  $error))
					{
						$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
						return false;
					}
					//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
					 $query=" select distinct 	o.id AS id_oportunidad,
												o.name AS nombre_oportunidad,
												o.lead_source AS origen_solcitud,
												o.sales_stage AS estado_solcitud,
												o.amount AS monto_oportunidad,
												s.description AS description,
												s.adultos AS pasajeros_adultos_c,
												s.ninos AS pasajeros_ninos_c,
												s.fecha_viaje AS fecha_viaje_c,
												s.hotel_habitacion AS habitaciones_c,
												-- date_format(DATE_ADD(do.date_assigned_c,INTERVAL -180 MINUTE), '%Y-%m-%d %H:%i:%s') AS fecha_asignacion_c,
												do.date_assigned_c AS fecha_asignacion_c,
												concat(l.first_name,' ',l.last_name) AS nombre_comprador,
												l.phone_home AS phone_home,
												l.phone_fax AS rut_comprador,
												l.id AS codigo_lead
												from opportunities o 
												join opportunities_cstm do on do.id_c = o.id 
												join leads_opportunities_1_c rc on rc.leads_opportunities_1opportunities_idb = do.id_c
												join leads l on l.id = rc.leads_opportunities_1leads_ida 
												join crm_solicitudes s on s.id = do.crm_solicitud_id_c
												where o.opportunity_type = 'solicitud_web'
												and o.deleted = 0
												and rc.deleted = 0 
												and l.deleted = 0
												and o.assigned_user_id = '".$usuario_asig."'  
												and o.sales_stage in ('PrimeraLlamada','EnvioCotizacion','Seguimiento1','Seguimiento2','AsignacionSolicitudSupervisor','contactado','cotizacion','negociacion')";	
							
					$respt = $db->Query($query);
					
					while ($fila = $db->fetchByAssoc($respt)) {
						$obj ['nombre_oportunidad']=$fila['nombre_oportunidad'];
						$obj ['id_oportunidad']       =$fila['id_oportunidad'];
						$obj ['fecha_viaje_c']=$fila['fecha_viaje_c'];
						$obj ['fecha_asignacion_c']=$fila['fecha_asignacion_c'];
						$obj ['nombre_comprador']=ucwords($fila['nombre_comprador']);
						$obj ['rut_c']=$fila['rut_comprador'];
						$obj ['estado_solcitud'] =$fila['estado_solcitud'];
						$obj ['id_cli'] =$fila['codigo_lead'];
						$obj ['description'] 		 		=$fila['description'];
												
						if (!empty($fila['phone_home'])){
								$obj ['phone_office']         =$fila['phone_home'];
							}else{
								$obj ['phone_office']= 'Sin Telefono';
							}
								$contacto =new Lead();
								$contacto->retrieve($fila['codigo_lead']);
								if(!empty($contacto->email1)){
									$email=$contacto->email1;
								}else{
									$email = 'Sin Email';
								}
								$obj ['email_address']=$email;
								$obj ['c_envio_email'] = 0 ;
						$objeto []= $obj;
					}
					if (empty($objeto)){
						return '';
					}else{
						/*return $db->fetchByAssoc($respt);*/
						return $objeto;
					}
	}
// creado por armando 02/11/2015		
	/*obtiene los datos de las solicitudes web cerradas*/
	function get_solicitudes_web_cerradas($session,$usuario_asig){
			global $db, $sugar_config;
				$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_solicitudes_web_cerradas', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
					//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
			$query=" 
					select distinct o.id AS id_oportunidad,
									o.name AS nombre_oportunidad,
									o.lead_source AS origen_solcitud,
									o.sales_stage AS estado_solcitud,
									o.amount AS monto_oportunidad,
									s.description AS description,
									s.adultos AS pasajeros_adultos_c,
									s.ninos AS pasajeros_ninos_c,
									s.fecha_viaje AS fecha_viaje_c,
									s.hotel_habitacion AS habitaciones_c,
									-- date_format(DATE_ADD(do.date_assigned_c,INTERVAL -180 MINUTE), '%Y-%m-%d %H:%i:%s') AS fecha_asignacion_c,
									do.date_assigned_c AS fecha_asignacion_c,
									concat(l.first_name,' ',l.last_name) AS nombre_comprador,
									l.phone_home AS phone_home,
									l.phone_fax AS rut_comprador,
									l.id AS codigo_lead,
									o.assigned_user_id AS usuario_asignado 
									from opportunities o 
									join opportunities_cstm do on do.id_c = o.id 
									join leads_opportunities_1_c rc on rc.leads_opportunities_1opportunities_idb = do.id_c 
									join leads l on l.id = rc.leads_opportunities_1leads_ida 
									join crm_solicitudes s on s.id = do.crm_solicitud_id_c
									where o.opportunity_type = 'solicitud_web' 
									and o.deleted = 0 
									and rc.deleted = 0 
									and l.deleted = 0 
									and o.assigned_user_id = '".$usuario_asig."'
									and o.sales_stage in ('CerradoExterno','CerradoPerdido','CerradoGanado')";		
			$respt = $db->Query($query);
			
			while ($fila = $db->fetchByAssoc($respt)) {
				$obj ['nombre_oportunidad']=$fila['nombre_oportunidad'];
				$obj ['id_oportunidad']       =$fila['id_oportunidad'];
				$obj ['fecha_viaje_c']=$fila['fecha_viaje_c'];
				$obj ['fecha_asignacion_c']=$fila['fecha_asignacion_c'];
				$obj ['nombre_comprador']=ucwords($fila['nombre_comprador']);
				$obj ['estado_solcitud']=$fila['estado_solcitud'];
				$obj ['rut_c']=$fila['rut_comprador'];
				//$obj ['phone_office']=$fila['phone_office'];
				$obj ['description'] 		 		=$fila['description'];
				$obj ['id_cli'] 		 =$fila['codigo_lead'];
				if (!empty($fila['phone_home'])){
						$obj ['phone_office']         =$fila['phone_home'];
					}else{
						$obj ['phone_office']= 'Sin Telefono';
					}
				$contacto =new Lead();
						$contacto->retrieve($fila['codigo_lead']);
						if(!empty($contacto->email1)){
							$email=$contacto->email1;
						}else{
							$email = 'Sin Email';
						}
						$obj ['email_address']=$email;					
				$objeto []= $obj;
			}
			if (empty($objeto)){
				return '';
			}else{
				return $objeto;
			}
	}
// creado por armando 02/11/2015		
	/*solicitudes crossseling*/
				/*obtiene la cantidad de solicitudes crossseling nuevas*/ 
	function get_cantidad_crossseling_nuevos ($session,$usuario_asig){
				global $db, $sugar_config;
				$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_cantidad_crossseling_nuevos', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------						   
				$query="select cantidad_cross('".$usuario_asig."') as crossseling from dual";	
				$respt = $db->Query($query); 
				if($row_1 = $db->fetchByAssoc($respt)){
					$cantidad=$row_1['crossseling'];  
				}
		return $cantidad;    
	}
// creado por armando 02/11/2015		
	/*obtiene los datos de las solicitudes crossseling_ nuevas*/
	function get_obtiene_crossseling_abiertos($session,$usuario_asig){
		global $db, $sugar_config;
		$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_obtiene_crossseling_abiertos', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
					//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
		 $query=" SELECT * FROM view_crm_crosseling_nuevas WHERE usuario_asignado = '".$usuario_asig."'";  
					 
		$respt = $db->Query($query);
		while($value = $db->fetchByAssoc($respt)){
			if (!empty($value['id_oportunidad'])){
				$obj ['id_negocio']=$value['id_negocio'];
				$obj ['id_oportunidad']=$value['id_oportunidad'];
				$obj ['fecha_modificacion']=$value['fecha_modificacion'];
				$obj ['id_contacto']=$value['id_contacto'];
				$obj ['id_pasajero']=$value['id_pasajero'];
				$obj ['numero_negocio']=$value['numero_negocio'];
				$obj ['rut_comprador']=$value['rut_comprador'];
				$obj ['nombre_comprador']=ucwords($value['nombre_comprador']);
				$obj ['monto_oportunidad']=number_format($value['monto_oportunidad'],0,"",".");
				$obj ['monto_venta']=number_format($value['monto_venta'],0,"",".");
				$obj ['clasificacion_cliente']=$value['clasificacion_cliente'];
				$obj ['fecha_salida']=$value['fecha_salida'];
				$obj ['destino']=$value['destino'];
				$obj ['ruta']=$value['RUTA'];
				$obj ['dias']=$value['duracion_viaje'];
				$obj ['estado']=$value['estado'];
				$obj ['oportunidad_new']=$value['oportunidad_new'];
				$obj ['productos_c']=$value['productos_c'];
				
				if (!empty($value['phone_home'])){
					$obj ['telefono']=$value['phone_home'];
				}else{
					$obj ['telefono']= 'Sin Telefono';
				}
					$contacto =new Contact();
					$contacto->retrieve($value['id_contacto']);
					if(!empty($contacto->email1)){
						$email=$contacto->email1;
					}else{
						$email = 'Sin Email';
					}
					$obj ['email']=$email;
				$query=" select count(*) c_email from contacts c 
						inner join email_addr_bean_rel ea on ea.bean_id=c.id
						inner join emails_email_addr_rel er on er.email_address_id = ea.email_address_id
						left join emails e on e.id=er.email_id
						left join emails y on y.id=er.email_id
						where c.id='".$contacto->id."' and er.address_type='to' 
						and (e.parent_id= '".$value['id_oportunidad']."'  
						or (y.parent_id = '".$contacto->id."' and y.date_entered < '2015-09-21'))  -- caso de transacciones antiguas
						and e.deleted=0 and ea.deleted=0 and er.deleted=0  and c.deleted=0";
				$res = $db->Query($query);
				if($row = $db->fetchByAssoc($res)){
					$obj ['c_envio_email'] = $row['c_email'];
				}
					$objeto []= $obj;
			}
		}
		if (empty($objeto)){
			return '';
		}else{
			return $objeto;
			//return arsort($objeto,$obj ['fecha_modificacion']);
		}
		
	}
// creado por armando 02/11/2015		
		/*obtiene los datos de las solicitudes crossseling cerradas*/
	function get_obtiene_crosselling_cerradas($session,$usuario_asig){
		global $db, $sugar_config;
		$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_obtiene_crosselling_cerradas', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
		 $query="SELECT * FROM view_crm_crosseling_cerrados WHERE usuario_asignado = '".$usuario_asig."'";
			
		
		$respt = $db->Query($query);
		while($value = $db->fetchByAssoc($respt)){
			if (!empty($value['id_oportunidad'])){
				$obj ['id_negocio']=$value['id_negocio'];
				$obj ['id_oportunidad']=$value['id_oportunidad'];
				$obj ['fecha_modificacion']=$value['fecha_modificacion'];
				$obj ['id_contacto']=$value['id_contacto'];
				$obj ['id_pasajero']=$value['id_pasajero'];
				$obj ['numero_negocio']=$value['numero_negocio'];
				$obj ['rut_comprador']=$value['rut_comprador'];
				$obj ['nombre_comprador']=ucwords($value['nombre_comprador']);
				$obj ['monto_oportunidad']=number_format($value['monto_oportunidad'],0,"",".");
				$obj ['monto_venta']=number_format($value['monto_venta'],0,"",".");
				$obj ['clasificacion_cliente']=$value['clasificacion_cliente'];
				$obj ['fecha_salida']=$value['fecha_salida'];
				$obj ['destino']=$value['destino'];
				$obj ['ruta']=$value['RUTA'];
				$obj ['dias']=$value['duracion_viaje'];	
				$obj ['estado']=$value['estado'];
				$obj ['oportunidad_new']=$value['oportunidad_new'];
				if (!empty($value['phone_home'])){
						$obj ['telefono']=$value['phone_home'];
					}else{
						$obj ['telefono']= 'Sin Telefono';
					}
				$obj ['description']=$value['description'];
				$obj ['motivo_cierre_c']=$value['motivo_cierre_c'];
				$obj ['productos_c']=$value['productos_c'];
				
				$contacto =new Contact();
					$contacto->retrieve($value['id_contacto']);
					if(!empty($contacto->email1)){
						$email=$contacto->email1;
					}else{
						$email = 'Sin Email';
					}
					$obj ['email']=$email;
				$query=" select count(*) c_email from contacts c 
						inner join email_addr_bean_rel ea on ea.bean_id=c.id
						inner join emails_email_addr_rel er on er.email_address_id = ea.email_address_id
						left join emails e on e.id=er.email_id
						left join emails y on y.id=er.email_id
						where c.id='".$contacto->id."' and er.address_type='to' 
						and (e.parent_id= '".$value['id_oportunidad']."'  
						or (y.parent_id = '".$contacto->id."' and y.date_entered < '2015-09-21'))  -- caso de transacciones antiguas
						and e.deleted=0 and ea.deleted=0 and er.deleted=0  and c.deleted=0";
				$res = $db->Query($query);
				if($row = $db->fetchByAssoc($res)){
					$obj ['c_envio_email'] = $row['c_email'];
				}
			}		$objeto []= $obj;
		}
		//return $query;
		if (empty($objeto)){
			return '';
		}else{
			return $objeto;
		}
		
	}
// creado por armando 02/11/2015	
	/*solicitudes recompra*/
				/*obtiene la cantidad de solicitudes recompra nuevas*/
	function get_cantidad_recompra_nuevos ($session,$usuario_asig){
		global $db, $sugar_config;
		$error = new SoapError();
		//authenticate
		if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_cantidad_recompra_nuevos', '', '', '',  $error))
		{
			$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
			return false;
		}
//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------					   
		$query="select cantidad_recompra('".$usuario_asig."') as recompra from dual";	
		$respt = $db->Query($query); 
		if($row_1 = $db->fetchByAssoc($respt)){
			$cantidad=$row_1['recompra'];  
		}
		return $cantidad;    
	}
// creado por armando 02/11/2015					
		 /*obtiene los datos de las solicitudes recompra nuevas*/
	function get_recompra_nuevos($session,$usuario_asig){
		global $db, $sugar_config;
		$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_recompra_nuevos', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
		 $query="   SELECT * FROM view_crm_recompra_nuevas WHERE usuario_asignado = '".$usuario_asig."'";  
					
		$respt = $db->Query($query);
		while($value = $db->fetchByAssoc($respt)){
			if (!empty($value['id_oportunidad'])){
				$obj ['id_negocio']=			$value['id_negocio'];
				$obj ['id_oportunidad']=		$value['id_oportunidad'];
				$obj ['fecha_modificacion']=$value['fecha_modificacion'];
				$obj ['id_contacto']=			$value['id_contacto'];
				$obj ['id_pasajero']=			$value['id_pasajero'];
				$obj ['numero_negocio']=		$value['numero_negocio'];
				$obj ['rut_comprador']=			$value['rut_comprador'];
				$obj ['nombre_comprador']=		ucwords($value['nombre_comprador']);
				$obj ['monto_venta']=			number_format($value['monto_venta'],0,"",".");
				$obj ['monto_oportunidad']=   number_format($value['monto_oportunidad'],0,"",".");
				$obj ['clasificacion_cliente']= $value['clasificacion_cliente'];
				$obj ['fecha_compra']=			$value['fecha_compra'];
				$obj ['destino']=				$value['destino'];
				$obj ['ruta']=					$value['RUTA'];
				$obj ['dias']=					$value['duracion_viaje'];
				$obj ['estado']=				$value['estado'];	
                $obj ['productos_c']=           $value['productos_c'];
				
				if (!empty($value['phone_home'])){
						$obj ['telefono']=$value['phone_home'];
					}else{
						$obj ['telefono']= 'Sin Telefono';
					}
				
				$contacto =new Contact();
					$contacto->retrieve($value['id_contacto']);
					if(!empty($contacto->email1)){
						$email=$contacto->email1;
					}else{
						$email = 'Sin Email';
					}
					$obj ['email']=$email;
				$query=" select count(*) c_email from contacts c 
						inner join email_addr_bean_rel ea on ea.bean_id=c.id
						inner join emails_email_addr_rel er on er.email_address_id = ea.email_address_id
						left join emails e on e.id=er.email_id
						left join emails y on y.id=er.email_id
						where c.id='".$contacto->id."' and er.address_type='to' 
						and (e.parent_id= '".$value['id_oportunidad']."'  
						or (y.parent_id = '".$contacto->id."' and y.date_entered < '2015-09-21'))  -- caso de transacciones antiguas
						and e.deleted=0 and ea.deleted=0 and er.deleted=0  and c.deleted=0";
				$res = $db->Query($query);
				if($row = $db->fetchByAssoc($res)){
					$obj ['c_envio_email'] = $row['c_email'];
				}
				$objeto []= $obj;
			}	
		}
		if (empty($objeto)){
			return '';
		}else{
			return $objeto;
		}
		
	}
// creado por armando 02/11/2015		
	/*obtiene los datos de las solicitudes recompra cerradas*/
	function get_recompra_cerrados($session,$usuario_asig){
		global $db, $sugar_config;
		$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_recompra_cerrados', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
		 $query=" SELECT * FROM view_crm_recompra_cerrados WHERE usuario_asignado = '".$usuario_asig."'"; 
					  
		
		$respt = $db->Query($query);
		while($value = $db->fetchByAssoc($respt)){
			if (!empty($value['id_oportunidad'])){
				$obj ['id_negocio']=$value['id_negocio'];
				$obj ['id_oportunidad']=$value['id_oportunidad'];
				$obj ['id_contacto']=$value['id_contacto'];
				$obj ['id_pasajero']=$value['id_pasajero'];
				$obj ['numero_negocio']=$value['numero_negocio'];
				$obj ['rut_comprador']=$value['rut_comprador'];
				$obj ['nombre_comprador']=ucwords($value['nombre_comprador']);
				$obj ['monto_oportunidad']=number_format($value['monto_oportunidad'],0,"",".");
				$obj ['monto_venta']=number_format($value['monto_venta'],0,"",".");
				$obj ['clasificacion_cliente']=$value['clasificacion_cliente'];
				$obj ['fecha_compra']=$value['fecha_compra'];
				$obj ['destino']=$value['destino'];
				$obj ['ruta']=$value['RUTA'];
				$obj ['dias']=$value['duracion_viaje'];	
				$obj ['estado']=$value['estado'];
				if (!empty($value['phone_home'])){
						$obj ['telefono']=$value['phone_home'];
					}else{
						$obj ['telefono']= 'Sin Telefono';
					}
				$obj ['description']=$value['description'];
				$obj ['motivo_cierre_c']=$value['motivo_cierre_c'];
				$obj ['productos_c']=$value['productos_c'];
				
				$contacto =new Contact();
					$contacto->retrieve($value['id_contacto']);
					if(!empty($contacto->email1)){
						$email=$contacto->email1;
					}else{
						$email = 'Sin Email';
					}
					$obj ['email']=$email;
				$query=" select count(*) c_email from contacts c 
						inner join email_addr_bean_rel ea on ea.bean_id=c.id
						inner join emails_email_addr_rel er on er.email_address_id = ea.email_address_id
						left join emails e on e.id=er.email_id
						left join emails y on y.id=er.email_id
						where c.id='".$contacto->id."' and er.address_type='to' 
						and (e.parent_id= '".$value['id_oportunidad']."'  
						or (y.parent_id = '".$contacto->id."' and y.date_entered < '2015-09-21'))  -- caso de transacciones antiguas
						and e.deleted=0 and ea.deleted=0 and er.deleted=0  and c.deleted=0";
				$res = $db->Query($query);
				if($row = $db->fetchByAssoc($res)){
					$obj ['c_envio_email'] = $row['c_email'];
				}
					$objeto []= $obj;
			}
		}
		if (empty($objeto)){
			return '';
		}else{
			return $objeto;
		}
		
	}
// creado por armando 02/11/2015		
	/*solicitudes fugados*/
				/*obtiene la cantidad de solicitudes fugados nuevas*/
	function get_cantidad_fugados_nuevos ($session,$usuario_asig){
		global $db, $sugar_config;
		$error = new SoapError();
		//authenticate
		if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_cantidad_fugados_nuevos', '', '', '',  $error))
		{
			$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
			return false;
		}
//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------		   
		$query="select cantidad_fugados('".$usuario_asig."') as fugados from dual";	
		$respt = $db->Query($query); 
		if($row_1 = $db->fetchByAssoc($respt)){
			$cantidad=$row_1['fugados'];  
		}
		return $cantidad;    
	}
// creado por armando 02/11/2015		
		  /*obtiene los datos de las solicitudes fugados nuevas*/
	function get_fugados_nuevos($session,$usuario_asig){
		global $db, $sugar_config;
		$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_fugados_nuevos', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
		 $query="  SELECT * FROM view_crm_fugados_nuevos WHERE usuario_asignado = '".$usuario_asig."'";  
					   
			
		$respt = $db->Query($query);
		while($value = $db->fetchByAssoc($respt)){
			if (!empty($value['id_oportunidad'])){
				$obj ['id_negocio']=$value['id_negocio'];
				$obj ['id_oportunidad']=$value['id_oportunidad'];
				$obj ['fecha_modificacion']=$value['fecha_modificacion'];
				$obj ['id_contacto']=$value['id_contacto'];
				$obj ['id_pasajero']=$value['id_pasajero'];
				$obj ['numero_negocio']=$value['numero_negocio'];
				$obj ['rut_comprador']=$value['rut_comprador'];
				$obj ['nombre_comprador']=ucwords($value['nombre_comprador']);
				$obj ['monto_oportunidad']=number_format($value['monto_oportunidad'],0,"",".");
				$obj ['monto_venta']=number_format($value['monto_venta'],0,"",".");
				$obj ['clasificacion_cliente']=$value['clasificacion_cliente'];
				$obj ['fecha_compra']=$value['fecha_compra'];
				$obj ['destino']=$value['destino'];
				$obj ['ruta']=$value['RUTA'];
				$obj ['dias']=$value['duracion_viaje'];	
				$obj ['estado']=$value['estado'];
				$obj ['productos_c']=$value['productos_c'];
				
				if (!empty($value['phone_home'])){
						$obj ['telefono']=$value['phone_home'];
					}else{
						$obj ['telefono']= 'Sin Telefono';
					}
												
				$contacto =new Contact();
					$contacto->retrieve($value['id_contacto']);
					if(!empty($contacto->email1)){
						$email=$contacto->email1;
					}else{
						$email = 'Sin Email';
					}
					$obj ['email']=$email;
				$query=" select count(*) c_email from contacts c 
						inner join email_addr_bean_rel ea on ea.bean_id=c.id
						inner join emails_email_addr_rel er on er.email_address_id = ea.email_address_id
						left join emails e on e.id=er.email_id
						left join emails y on y.id=er.email_id
						where c.id='".$contacto->id."' and er.address_type='to' 
						and (e.parent_id= '".$value['id_oportunidad']."'  
						or (y.parent_id = '".$contacto->id."' and y.date_entered < '2015-09-21'))  -- caso de transacciones antiguas
						and e.deleted=0 and ea.deleted=0 and er.deleted=0  and c.deleted=0";
				$res = $db->Query($query);
				if($row = $db->fetchByAssoc($res)){
					$obj ['c_envio_email'] = $row['c_email'];
				}
					$objeto []= $obj;
			}
		}
		if (empty($objeto)){
			return '';
		}else{
			return $objeto;
		}
		
	}
// creado por armando 02/11/2015		
	/*obtiene los datos de las solicitudes fugados cerradas*/
	function get_fugados_cerrados($session,$usuario_asig){
		global $db, $sugar_config;
		$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_fugados_cerrados', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
		 $query=" SELECT * FROM view_crm_fugados_cerrados WHERE usuario_asignado = '".$usuario_asig."'";  
					 
		$respt = $db->Query($query);
		while($value = $db->fetchByAssoc($respt)){
			if (!empty($value['id_oportunidad'])){
				$obj ['id_negocio']=$value['id_negocio'];
				$obj ['id_oportunidad']=$value['id_oportunidad'];
				$obj ['id_contacto']=$value['id_contacto'];
				$obj ['id_pasajero']=$value['id_pasajero'];
				$obj ['numero_negocio']=$value['numero_negocio'];
				$obj ['rut_comprador']=$value['rut_comprador'];
				$obj ['nombre_comprador']=ucwords($value['nombre_comprador']);
				$obj ['monto_oportunidad']=number_format($value['monto_oportunidad'],0,"",".");
				$obj ['monto_venta']=number_format($value['monto_venta'],0,"",".");
				$obj ['clasificacion_cliente']=$value['clasificacion_cliente'];
				$obj ['fecha_compra']=$value['fecha_compra'];
				$obj ['destino']=$value['destino'];
				$obj ['ruta']=$value['RUTA'];
				$obj ['dias']=$value['duracion_viaje'];	
				$obj ['estado']=$value['estado'];
				if (!empty($value['phone_home'])){
						$obj ['telefono']=$value['phone_home'];
					}else{
						$obj ['telefono']= 'Sin Telefono';
					}
				$obj ['description']=$value['description'];
				$obj ['motivo_cierre_c']=$value['motivo_cierre_c'];
				$obj ['productos_c']=$value['productos_c'];
				
				$contacto =new Contact();
					$contacto->retrieve($value['id_contacto']);
					if(!empty($contacto->email1)){
						$email=$contacto->email1;
					}else{
						$email = 'Sin Email';
					}
					$obj ['email']=$email;
				$query=" select count(*) c_email from contacts c 
						inner join email_addr_bean_rel ea on ea.bean_id=c.id
						inner join emails_email_addr_rel er on er.email_address_id = ea.email_address_id
						left join emails e on e.id=er.email_id
						left join emails y on y.id=er.email_id
						where c.id='".$contacto->id."' and er.address_type='to' 
						and (e.parent_id= '".$value['id_oportunidad']."'  
						or (y.parent_id = '".$contacto->id."' and y.date_entered < '2015-09-21'))  -- caso de transacciones antiguas
						and e.deleted=0 and ea.deleted=0 and er.deleted=0  and c.deleted=0";
				$res = $db->Query($query);
				if($row = $db->fetchByAssoc($res)){
					$obj ['c_envio_email'] = $row['c_email'];
				}
					$objeto []= $obj;
			}
		}
		if (empty($objeto)){
			return '';
		}else{
			return $objeto;
		}
		
	}
// creado por armando 02/11/2015		
	/*proximos_viajes*/
				/*obtiene la cantidad de proximos_viajes*/
	function get_cantidad_proximos_viajes_nuevos ($session,$usuario_asig){
					global $db, $sugar_config;
					$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_cantidad_proximos_viajes_nuevos', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
								//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
				$query="  select cantidad_proximos_viajes('".$usuario_asig."') as proximos from dual";	
				 $respt = $db->Query($query); 
				 if($row_1 = $db->fetchByAssoc($respt)){
					$cantidad=$row_1['proximos'];  
				}
				return $cantidad;       
		}
// creado por armando 02/11/2015					  
				  /*obtiene los datos de las tareas proximos_viajes*/
	function get_proximos_viajes_nuevos($session,$usuario_asig){
		global $db, $sugar_config;
		$anio = date('Y')-1;
			$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_proximos_viajes_nuevos', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
				//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
			 $query=" SELECT * FROM view_proximos_viajes_nuevos WHERE ususario_asig = '".$usuario_asig."'";  
					 
			$respt = $db->Query($query);
			while($value = $db->fetchByAssoc($respt)){
				 
				$negocio =new crm_negocios();
				$negocio->retrieve($value['crm_negocios_id_c']);
				
				$contacto =new Contact();
				$contacto->retrieve($value['id_contact']);
				

				$obj ['id_negocio']=$negocio->id;
				$obj ['id_tarea']=$value['id'];
				$obj ['id_contacto']=$contacto->id;
				$obj ['numero_negocio']=$negocio->name;
				$obj ['rut_contacto']=$contacto->phone_fax;
				$obj ['nombre_contacto']=ucwords($contacto->last_name);
				$obj ['voucher_c']=$value['voucher_c'];
				$obj ['monto_venta']=number_format($negocio->monto,0,"",".");
				$obj ['monto_oportunidad']= 0;
				$obj ['documentacion_c']=$value['documentacion_c'];
				$obj ['clasificacion_cliente']=$contacto->clasificacion_c;
				$segundos=strtotime($negocio->fecha_salida) - strtotime('now');
				$diferencia_dias=intval($segundos/60/60/24)+1;
				$obj ['dias_restante']=$diferencia_dias;
				if ($diferencia_dias > 0 && $diferencia_dias <= 20){
					$color='con_color';
				}else{
					$color='sin_color';
				}
				$obj ['color_fila']=$color;
				$obj ['fecha_salida']=$negocio->fecha_salida;
				$obj ['destino']=$negocio->destino;
				$obj ['descripciones']=$value['description'];
				$obj ['ruta']=$negocio->ruta;
					if(!empty($contacto->email1)){
							$email = $contacto->email1;
					}else{
						$email = 'Sin Email';
					}
					$obj ['email_contacto'] = $email;
				//$obj ['email_contacto']=$contacto->email1;
				if (!empty($contacto->phone_home)){
						$obj ['telefono_contacto']=$contacto->phone_home;;
					}else{
						$obj ['telefono_contacto']= 'Sin Telefono';
					}
				$query="select count(*) c_email from contacts c 
						inner join email_addr_bean_rel ea on ea.bean_id=c.id
						inner join emails_email_addr_rel er on er.email_address_id = ea.email_address_id
						left join emails e on e.id=er.email_id
						left join emails y on y.id=er.email_id
						where c.id='".$contacto->id."' and er.address_type='to' 
						and (e.parent_id= '".$value['id']."'  
						or (y.parent_id = '".$contacto->id."' and y.date_entered < '2015-09-21')) -- caso de transacciones antiguas
						and e.deleted=0 and ea.deleted=0 and er.deleted=0  and c.deleted=0";
				$res = $db->Query($query);
				if($row = $db->fetchByAssoc($res)){
					$obj ['c_envio_email'] = $row['c_email'];
				}
				$objeto []=$obj;
			}
		if (empty($objeto)){
			return '';
		}else{
			return $objeto;
		}
	}
// creado por armando 02/11/2015					
				/*obtiene los datos de las tareas proximos_viajes*/
	function get_proximos_viajes_cerrados($session,$usuario_asig){
		global $db, $sugar_config;
		$anio = date('Y')-1;
		$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_proximos_viajes_cerrados', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
					//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
			 $query=" SELECT * FROM view_proximos_viajes_cerrados WHERE ususario_asig = '".$usuario_asig."'";  
					 
			$respt = $db->Query($query);
			while($value = $db->fetchByAssoc($respt)){
			 
			$negocio =new crm_negocios();
			$negocio->retrieve($value['crm_negocios_id_c']);

			$contacto =new Contact();
			$contacto->retrieve($value['id_contact']);
			

			$obj ['id_negocio']				=$negocio->id;
			$obj ['id_tarea']				=$value['id'];
			$obj ['id_contacto']			=$contacto->id;
			$obj ['numero_negocio']			=$negocio->name;
			$obj ['rut_contacto']			=$contacto->phone_fax;
			$obj ['nombre_contacto']		=ucwords($contacto->last_name);
			$obj ['voucher_c']				=$value['voucher_c'];
			$obj ['monto_venta']=number_format($negocio->monto,0,"",".");
			$obj ['monto_oportunidad']= 0;
			$obj ['documentacion_c']		=$value['documentacion_c'];
			$obj ['descripciones']            =$value['description'];
			$obj ['clasificacion_cliente']	=$contacto->clasificacion_c;
			$obj ['fecha_salida']			=$negocio->fecha_salida;
			$obj ['destino']				=$negocio->destino;
			$obj ['ruta']					=$negocio->ruta;
			$obj ['dias']					=$negocio->duracion_viaje;
					if(!empty($contacto->email1)){
							$email=$contacto->email1;
					}else{
						$email = 'Sin Email';
					}
					$obj ['email_contacto']=$email;
			//$obj ['email_contacto']			=$contacto->email1;
			if (!empty($contacto->phone_home)){
					$obj ['telefono_contacto']=$contacto->phone_home;;
				}else{
					$obj ['telefono_contacto']= 'Sin Telefono';
				}
				$query="select count(*) c_email from contacts c 
						inner join email_addr_bean_rel ea on ea.bean_id=c.id
						inner join emails_email_addr_rel er on er.email_address_id = ea.email_address_id
						left join emails e on e.id=er.email_id
						left join emails y on y.id=er.email_id
						where c.id='".$contacto->id."' and er.address_type='to' 
						and (e.parent_id= '".$value['id']."'  
						or (y.parent_id = '".$contacto->id."' and y.date_entered < '2015-09-21')) -- caso de transacciones antiguas
						and e.deleted=0 and ea.deleted=0 and er.deleted=0  and c.deleted=0";
				$res = $db->Query($query);
				if($row = $db->fetchByAssoc($res)){
					$obj ['c_envio_email'] = $row['c_email'];
				}
			$objeto []=$obj;
		}
		if (empty($objeto)){
			return '';
		}else{
			return $objeto;
		}
	}                     
// creado por armando 02/11/2015				
			/*retornos*/
				/*obtiene la cantidad de retornos*/
	function get_cantidad_retorno_nuevos ($session,$usuario_asig){
					global $db, $sugar_config;
					$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_proximos_viajes_cerrados', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
					//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------			
				$tarea= new Task();
					$list_task = $tarea->get_full_list("", " tipo_c = 'Retorno' and tasks.status = 'Not Started' and tasks.assigned_user_id = '".$usuario_asig."' ",true);
					
					return count($list_task);      
		}  
// creado por armando 02/11/2015			
		/*obtiene los datos de las tareas proximos_viajes*/
	function get_retorno_nuevos($session,$usuario_asig){
		global $db, $sugar_config;
		$anio = date('Y')-1;
			$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_cantidad_retorno_nuevos', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
			 		//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
			 $query=" SELECT * FROM view_retorno_nuevos WHERE ususario_asig = '".$usuario_asig."'";  
					 
			$respt = $db->Query($query);
			while($value = $db->fetchByAssoc($respt)){
				 
				$negocio =new crm_negocios();
				$negocio->retrieve($value['crm_negocios_id_c']);

				$contacto =new Contact();
				$contacto->retrieve($value['id_contact']);
				

				$obj ['id_negocio']=$negocio->id;
				$obj ['id_tarea']=$value['id'];
				$obj ['id_contacto']=$value['id_contact'];
				$obj ['numero_negocio']=$negocio->name;
				$obj ['rut_contacto']=$contacto->phone_fax;
				$obj ['nombre_contacto']=ucwords($contacto->last_name);
				$obj ['clasificacion_cliente']=$contacto->clasificacion_c;
				$obj ['fecha_retorno']=$negocio->fecha_destino;
				$obj ['monto_venta']=number_format($negocio->monto,0,"",".");
				$obj ['monto_oportunidad']= 0;
				$obj ['descripciones']=$value['description'];
				$obj ['destino']=$negocio->destino;
				$obj ['ruta']=$negocio->ruta;
				$obj ['dias']=$negocio->duracion_viaje;
					if(!empty($contacto->email1)){
							$obj ['email_contacto']=$contacto->email1;
					}else{
							$obj ['email_contacto'] = 'Sin Email';
					}
				
				//$obj ['email_contacto']=$contacto->email1;
				if (!empty($contacto->phone_home)){
						$obj ['telefono_contacto']=$contacto->phone_home;;
					}else{
						$obj ['telefono_contacto']= 'Sin Telefono';
					}
					$query="select count(*) c_email from contacts c 
						inner join email_addr_bean_rel ea on ea.bean_id=c.id
						inner join emails_email_addr_rel er on er.email_address_id = ea.email_address_id
						left join emails e on e.id=er.email_id
						left join emails y on y.id=er.email_id
						where c.id='".$contacto->id."' and er.address_type='to' 
						and (e.parent_id= '".$value['id']."'  
						or (y.parent_id = '".$contacto->id."' and y.date_entered < '2015-09-21')) -- caso de transacciones antiguas
						and e.deleted=0 and ea.deleted=0 and er.deleted=0  and c.deleted=0";
						
				$res = $db->Query($query);
				if($row = $db->fetchByAssoc($res)){
					$obj ['c_envio_email'] = $row['c_email'];
				}
					$query_2="select case WHEN r.submitdate is not null 
								  THEN 2 
								  ELSE case WHEN r.datestamp  is not null 
									THEN 1 
									ELSE 0 
								  END 
								END AS encuesta
								FROM survey.survey_146131 r
								where r.token = '".$contacto->id."'";
						
				$res_2 = $db->Query($query_2);
				$row_2 = $db->fetchByAssoc($res_2);
				if($row_2['encuesta'] > 0){
					$obj ['encuesta'] = $row_2['encuesta'];
				}ELSE{
					$obj ['encuesta'] = 0;
				}
				$objeto []=$obj;
			}
		if (empty($objeto)){
			return '';
		}else{
			return $objeto;
		}
	}	
// creado por armando 02/11/2015				
			/*obtiene los datos de las tareas proximos_viajes*/
	function get_retorno_cerrados($session,$usuario_asig){
		global $db, $sugar_config;
		$anio = date('Y')-1;
		$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_retorno_cerrados', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
				//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------		 
		 $query=" SELECT * FROM view_retorno_cerrados WHERE ususario_asig = '".$usuario_asig."'";  
					 
			$respt = $db->Query($query);
			while($value = $db->fetchByAssoc($respt)){
			 
			$negocio =new crm_negocios();
			$negocio->retrieve($value['crm_negocios_id_c']);

			$contacto =new Contact();
			$contacto->retrieve($value['id_contact']);
			

			$obj ['id_negocio']=$negocio->id;
			$obj ['id_tarea']=$value['id'];
			$obj ['id_contacto']=$contacto->id;
			$obj ['numero_negocio']=$negocio->name;
			$obj ['rut_contacto']=$contacto->phone_fax;
			$obj ['nombre_contacto']=ucwords($contacto->last_name);
			$obj ['clasificacion_cliente']=$contacto->clasificacion_c;
			$obj ['monto_venta']=number_format($negocio->monto,0,"",".");
			$obj ['monto_oportunidad']= 0;
			$obj ['fecha_retorno']=$negocio->fecha_destino;
			$obj ['motivo_cierre_c']=$value['description'];
			
			$obj ['monto_venta']=$negocio->monto;
			$obj ['destino']=$negocio->destino;
			$obj ['ruta']=$negocio->ruta;
			$obj ['dias']=$negocio->duracion_viaje;
					if(!empty($contacto->email1)){
							$email=$contacto->email1;
					}else{
						$email = 'Sin Email';
					}
					$obj ['email_contacto']=$email;
			if (!empty($contacto->phone_home)){
					$obj ['telefono_contacto']=$contacto->phone_home;;
				}else{
					$obj ['telefono_contacto']= 'Sin Telefono';
				}
				$query="select count(*) c_email from contacts c 
						inner join email_addr_bean_rel ea on ea.bean_id=c.id
						inner join emails_email_addr_rel er on er.email_address_id = ea.email_address_id
						left join emails e on e.id=er.email_id
						left join emails y on y.id=er.email_id
						where c.id='".$contacto->id."' and er.address_type='to' 
						and (e.parent_id= '".$value['id']."'  
						or (y.parent_id = '".$contacto->id."' and y.date_entered < '2015-09-21')) -- caso de transacciones antiguas
						and e.deleted=0 and ea.deleted=0 and er.deleted=0  and c.deleted=0";
				$res = $db->Query($query);
				if($row = $db->fetchByAssoc($res)){
					$obj ['c_envio_email'] = $row['c_email'];
				}
					$query_2="select case WHEN r.submitdate is not null 
								  THEN 2 
								  ELSE case WHEN r.datestamp  is not null 
									THEN 1 
									ELSE 0 
								  END 
								END AS encuesta
								FROM survey.survey_146131 r
								where r.token = '".$contacto->id."'";
						
				$res_2 = $db->Query($query_2);
				$row_2 = $db->fetchByAssoc($res_2);
				if($row_2['encuesta'] > 0){
					$obj ['encuesta'] = $row_2['encuesta'];
				}ELSE{
					$obj ['encuesta'] = 0;
				}
			$objeto []=$obj;
		}
		if (empty($objeto)){
			return '';
		}else{
			return $objeto;
		}
	}                   
// creado por armando 02/11/2015				
			/* proceso de ingresar data*/
				/*este procceso edita la informacion del contacto, email y telefono*/
	/*function set_editar_contactos($session,$usuario_asig,$contacto_id,$campos){
		global $db, $sugar_config;
		$GLOBALS['log']->info('Begin: SugarWebServiceImplv4_1_custom->example_method');
				$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session set_editar_contactos', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
		if(!empty($contacto_id)){
			/*en esta ruta se encuentran los posiblea campos a utiilizar (C:\xampp\htdocs\prototipo\modules\Contacts)*/
			/*$contacto = new Contact();
			$contacto->retrieve($contacto_id);
			foreach ($campos as $key => $value) {
				$contacto->$key = $value;
			}
			$contacto->save();
			return 1;
		} else { 
			return 0;
		}
	}
	*/
	function set_editar_contactos($session,$usuario_asig,$contacto_id,$campos){
		global $db, $sugar_config;
		$GLOBALS['log']->info('Begin: SugarWebServiceImplv4_1_custom->example_method');
				$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session set_editar_contactos', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
					//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
		if(!empty($contacto_id)){
			/*en esta ruta se encuentran los posiblea campos a utiilizar (C:\xampp\htdocs\prototipo\modules\Contacts)*/
			$contacto = new Contact();
			$contacto->retrieve($contacto_id);
			foreach ($campos as $key => $value) {
				$contacto->$key = $value;
			}
			$contacto->save();
			return 1;
		} else { 
			return 0;
		}
	}
		
// creado por armando 02/11/2015				
				/* etse proceso se encarga de cambiar el password del usuario*/
	function set_cambia_password ($session,$usuario_asig,$password){
		global $db, $sugar_config;
		$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session set_cambia_password', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
					//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
		if (empty($password)){
				$query=" update users 
					set users.user_hash = md5('1234') 
					where id = '".$usuario_asig."'";	
		}else{
				$query=" update users 
					set users.user_hash = md5('".$password."') 
					where id = '".$usuario_asig."'";	
		}
		$user = new User();
		$user->retrieve($usuario_asig);
				
		if($db->Query($query)){
			$emailObj = new Email();//*
			$defaults = $emailObj->getSystemDefaultEmail();//*
			$mail = new SugarPHPMailer();//*
			$mail->setMailerForSystem();//*
			$mail->From = 'Turismo Cocha <vflores@cocha.com>';//*//$current_user->email1;//$usuario->email1
			$mail->FromName ='Turismo Cocha';//*//$current_user->full_name;//$usuario->full_name
			$mail->ClearAllRecipients();//*
			$mail->ClearReplyTos();//*
			$mail->Subject=from_html('Cambio de Contraseña');//*
			$mail->Body='Estimado usuario '.$user->first_name.' '.$user->last_name.', la presente es para informarle que el cambio de contraseña se ha realizado satisfactoriamente.';//*
			$mail->prepForOutbound();//*
			$mail->AddAddress($user->email1);//*
		if(@$mail->Send()) return '1';						
		}else{
			return '0';	
		}
		 
		
	}
// creado por armando 02/11/2015				 
	function notificacion_acceso ($session,$usuario_asig,$password){
		global $db, $sugar_config;
		$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session notificacion_acceso', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
					//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
		$query="select case WHEN count(*) = 1
						THEN '1' 
						ELSE '0'
						END AS  aviso
						from users 
						where user_hash = MD5('".$password."') 
						and id = '".$usuario_asig."' ";
				$res = $db->Query($query);
				if($row = $db->fetchByAssoc($res)){
					$obj ['respuesta'] = $row['aviso'];
				}
			return $obj;
	}
// creado por armando 02/11/2015					
	function get_acceso_api ($user){
		global $db, $sugar_config;
		/*$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_acceso_api', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}*/
		$query="select user_name
						,user_hash
						,id
						from users 
						where MD5(user_name) = '".$user."'
						and deleted = 0 ";
				$res = $db->Query($query);
				if($row = $db->fetchByAssoc($res)){
					$obj ['user_name'] = $row['user_name'];
					$obj ['user_hash'] = $row['user_hash'];
					//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
				}
			return $obj;
		}
// creado por armando 02/11/2015				
				/*este procceso edita la informacion del estado de la oportunidad*/
	function set_editar_oportunidad($session,$usuario_asig,$oportunidad_id,$campos){
		global $db, $sugar_config;
		$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session set_editar_oportunidad', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
		//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
		//return $oportunidad_id.'-'.$campos."<br>";
		
		if(!empty($oportunidad_id)){
			//return $oportunidad_id;
			/*en esta ruta se encuentran los posiblea campos a utiilizar (C:\xampp\htdocs\prototipo\modules\Opportunity)*/
			$oportunidad= new Opportunity();
			//return "oportunidad:".$oportunidad_id."<br>"; //retorna la oportunidad id correcto
			$oportunidad->retrieve($oportunidad_id);
			//return "retrieve:".$oportunidad->retrieve($oportunidad_id)."<br>"; //retorna null
			
			foreach ($campos as $key => $value) {  
                //return "entro";	//entra		
				$oportunidad->$key = $value;
				//return "foreach:".$oportunidad->$key."<br>";//le asigna el valor q colocas en el campo numero negocio
			} 
			//return $oportunidad->$key;  
			
			$oportunidad->save();   
			return 1;
		} else { 
			return 0;
		}
	}
// creado por armando 02/11/2015				
				/*este procceso edita la informacion del estado de la tarea*/
	function set_editar_tarea ($session,$usuario_asig,$tarea_id,$campos){
		global $db, $sugar_config;
		$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session set_editar_tarea', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
				//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
		if(!empty($tarea_id)){
			/*en esta ruta se encuentran los posiblea campos a utiilizar (C:\xampp\htdocs\prototipo\modules\Task)*/
			$tarea= new Task();
			$tarea->retrieve($tarea_id);
			foreach ($campos as $key => $value) {
				$tarea->$key = $value;
			}
			$tarea->save();
			return 1;
		} else { 
			return 0;
		}
	}
// creado por armando 02/11/2015				
				/* reportes*/
	function get_cantidad_cumple_mes ($session,$usuario_asig){
					global $db, $sugar_config;
					$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_cantidad_cumple_mes', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
			//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
			$contacto = new Contact();
			$list_task = $contacto->get_full_list("", "month(contacts.birthdate) = month(now()) and contacts.assigned_user_id = '".$usuario_asig."' ",true);					
					return count($list_task);    						
		}  
// creado por armando 02/11/2015	
				/*obtiene los datos de los cumpleaños deñ mes*/
	function get_cumple_mes($session,$usuario_asig){
			global $db, $sugar_config;
			$anio = date('Y')-1;
			$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_cumple_mes', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
			 //evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
			 $contacto = new Contact();
			$list_task = $contacto->get_full_list("", "  month(contacts.birthdate) = month(now()) and contacts.assigned_user_id = '".$usuario_asig."' ",true);
			//$cantidad_cumpleanos = count($cumpleanos_list);

			 foreach ($list_task as $n) {
				$obj ['rut']		=$n->id;
				$obj ['nombre']		=ucwords($n->last_name);
				$obj ['cumple_mes']	=$n->birthdate;
				$obj ['description']	=$n->description;
				$obj ['telefono']		=$n->phone_home;
				//$obj ['email']		=$n->email1;
				$contacto =new Contact();
					$contacto->retrieve($n->id);
					if(!empty($contacto->email1)){
						$email=$contacto->email1;
					}else{
						$email = 'Sin Email';
					}
					$obj ['email']=$email;

					$query="select count(*) c_email from contacts c 
						inner join email_addr_bean_rel ea on ea.bean_id=c.id
						inner join emails_email_addr_rel er on er.email_address_id = ea.email_address_id
						left join emails e on e.id=er.email_id
						where c.id='".$n->id."' and er.address_type='to' and e.parent_id='".$n->id."'
						and e.deleted=0 and ea.deleted=0 and er.deleted=0  and c.deleted=0";
				$res = $db->Query($query);
				if($row = $db->fetchByAssoc($res)){
					$obj ['c_envio_email'] = $row['c_email'];
				}				
					//$obj ['c_envio_email'] = 0;
				$objeto [] = $obj ;
			}
	
			if (empty($objeto)){
				return '';
			}else{
				return $objeto;
			}
	
	}
// creado por armando 02/11/2015				
				/*obtiene los datos del contacto*/
	function get_buscar_contacto($session,$usuario_asig,$tipo){
			global $db, $sugar_config;
			$anio = date('Y')-1;
			$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_buscar_contacto', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
					//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
							$query="
									select * 
									from 
									(SELECT c.phone_fax as rut,
									c.last_name as nombre,
									c.birthdate as f_nacimiento,
									e.email_address,
									concat(u.first_name,' ',u.last_name) AS ejecutivo,
									ifnull(t.negocio,c.description) as negocio
									FROM contacts c 
									JOIN users u ON u.id = c.assigned_user_id 
									LEFT JOIN (SELECT pa.contact_id_c as rut,
												n.name as negocio,
										   pa.tipo_pax,
												max(n.date_entered ) as fecha
												FROM  crm_negocios       n
												JOIN crm_negocios_crm_pasajeros_c rn on rn.crm_negocios_crm_pasajeroscrm_negocios_ida = n.id 
												JOIN crm_pasajeros       pa ON pa.id = rn.crm_negocios_crm_pasajeroscrm_pasajeros_idb and pa.tipo_pax = '".$tipo."' AND pa.deleted = '0' and pa.assigned_user_id = '".$usuario_asig."'
										  group by  pa.contact_id_c, pa.tipo_pax) t on t.rut = c.id 
									 LEFT JOIN email_addr_bean_rel er ON er.bean_id= t.rut and er.deleted = '0' and er.bean_module = 'Contacts'
									 LEFT JOIN email_addresses e ON e.id = er.email_address_id and e.deleted = '0' 
									where c.deleted = '0' and c.assigned_user_id = '".$usuario_asig."') pax where pax.negocio is not null";
						$respt = $db->Query($query);
						while($value = $db->fetchByAssoc($respt)){
								$obj ['rut']		=$value ['rut'];
								$obj ['nombre']		=ucwords($value ['nombre']);
								$obj ['f_nacimiento']	=$value ['f_nacimiento'];
								$obj ['email'] = $value['email_address'];
								$obj ['ejecutivo']		=$value['ejecutivo'];
								$obj ['numero_negocio'] = $value['negocio'];
								$objeto [] = $obj ;
						}
			
			if (empty($objeto)){
				return '';
			}else{
				return $objeto;
			}
	
	}
// creado por armando 02/11/2015				
				/* negocio*/
	function get_negocio($session,$usuario_asig,$negocio_id){
		global $db, $sugar_config;
		$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_negocio', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
				//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
		 $query="  select 
							n.name,
							n.fecha_salida,
							n.destino,
							n.ruta,
							n.duracion_viaje as dias
					 FROM crm_negocios  n
					 where n.id = '".$negocio_id."' ";	
						
		
		$respt = $db->Query($query);
		while($value = $db->fetchByAssoc($respt)){
				$objeto []= $value;
		}
		if (empty($objeto)){
			return '';
		}else{
			return $objeto;
		}
		
	}
// creado por armando 02/11/2015				
				/* detalle negocio*/
	function get_negocio_detalle($session,$usuario_asig,$negocio_id){
		global $db, $sugar_config;
		$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_negocio_detalle', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
		 //evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
		 $query=" select distinct
						nd.name,
						ROUND(nd.precio) as precio,
						nd.operador,
						nd.ruta,
						nd.description
				 FROM crm_negocio_detalle nd 
				 where nd.negocio_id = '".$negocio_id." ' ";	
		$respt = $db->Query($query);
		while($value = $db->fetchByAssoc($respt)){
				$objeto []= $value;
		}
		if (empty($objeto)){
			return '';
		}else{
			return $objeto;
		}
		
	}
// creado por armando 02/11/2015				
				/* detalle user*/
	function get_user_detalle($session,$usuario_asig){
		global $db, $sugar_config;
		$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_user_detalle', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
		//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
		$user = new User();
		$user->retrieve($usuario_asig);
		
				$obj ['nombre']		=$user->first_name;
				$obj ['apellido']		=$user->last_name;
				$obj ['email']		=$user->email1;
				$obj ['telefono']		=$user->phone_work;
				$obj ['sucursal']		=$user->department;
				$obj ['cargo']		=$user->title;
				$obj ['crosselling_sucursal'] = $user->crosselling_sucursal_c;
				$obj ['crosselling_ejecutiva'] = $user->crosselling_ejecutiva_c;
				$obj ['direccion_sucursal'] = $user->direccion_sucursal_c;
				
				//llamo el procedimiento
							$query="CALL crm_acceso_user('".$usuario_asig."', @solcitides_web, @crosseling, @recompra, @fugados, @retornos, @cumpleanio, @cartera_cliente, @proximos_viajes,@boton_web)";
							$respt = $db->Query($query);
							//recupero las variables del procedimiento
							 $query_2="   	select 	 @solcitides_web as solcitides_web, 
													 @crosseling as croseling, 
													 @recompra as recompra, 
													 @fugados as fugados, 
													 @retornos  as retornos, 
													 @cumpleanio as cumpleanio, 
													 @cartera_cliente as cartera_cliente, 
													 @proximos_viajes as proximos_viajes,
													 @boton_web as boton_web";
							$respt2 = $db->Query($query_2);
							
							while($value = $db->fetchByAssoc($respt2)){
								$obj ['solcitides_web']= $value['solcitides_web'];
								$obj ['croseling']= $value['croseling'];
								$obj ['recompra']= $value['recompra'];
								$obj ['fugados']= $value['fugados'];
								$obj ['retornos']= $value['retornos'];
								$obj ['cumpleanio']= $value['cumpleanio'];
								$obj ['cartera_cliente']= $value['cartera_cliente'];
								$obj ['proximos_viajes']= $value['proximos_viajes'];
								$obj ['boton_web']= $value['boton_web'];
							}							
				$objeto [] = $obj ;
				
			return $objeto;
	}
// creado por armando 02/11/2015				
				/* detalle pasajero*/
	function get_detalle_pasajeros ($session,$usuario_asig,$negocio_id,$id_trx){
		global $db, $sugar_config;
		$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_detalle_pasajeros', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
		 //evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
		 $query=" SELECT contact_id_c,tipo_pax 
					FROM crm_pasajeros p
					JOIN crm_negocios_crm_pasajeros_c np on np.crm_negocios_crm_pasajeroscrm_pasajeros_idb = p.id AND np.deleted=0
					WHERE np.crm_negocios_crm_pasajeroscrm_negocios_ida = '".$negocio_id."' AND p.deleted=0";	
			
		$respt = $db->Query($query);
		$contacto = new Contact();
	
		while($value = $db->fetchByAssoc($respt)){
		
		$contacto->retrieve($value['contact_id_c']);
		$obj ['rut']=$contacto->id;
		$obj ['nombre']=ucwords($contacto->last_name);
		$obj ['clasificacion']=$contacto->title;
		$obj ['telefono']=$contacto->phone_home;
		$obj ['email']=$contacto->email1;
		$obj ['tipo_pax']=$value['tipo_pax'];
		$query="select count(*) c_email from contacts c 
						inner join email_addr_bean_rel ea on ea.bean_id=c.id
						inner join emails_email_addr_rel er on er.email_address_id = ea.email_address_id
						left join emails e on e.id=er.email_id
						left join emails y on y.id=er.email_id
						where c.id='".$contacto->id."' and er.address_type='to' 
						and (e.parent_id= '".$id_trx."'  
						or (y.parent_id = '".$contacto->id."' and y.date_entered < '2015-09-21')) -- caso de transacciones antiguas
						and e.deleted=0 and ea.deleted=0 and er.deleted=0  and c.deleted=0";
					
				$res = $db->Query($query);
				if($row = $db->fetchByAssoc($res)){
					$obj ['c_envio_email'] = $row['c_email'];
				}
		$objeto []=$obj;
		}
		
		if (empty($objeto)){
			return '';
		}else{
			return $objeto;
		}
	}
// creado por armando 02/11/2015				
				/*  proceso de enviar email */ 
	function envia_email($session,$usuario_asig,$correo,$asunto,$texto,$email_cc,$name_file_array,$contacto_id,$ruta_upload,$from,$nombre_user,$procedencia,$procedencia_id){
				//return $session.'_'.$usuario_asig.'_'.$correo.'_'.$asunto.'_'.$email_cc.'_'.$name_file_array.'_'.$contacto_id.'_'.$ruta_upload.'_'.$from.'_'.$nombre_user.'_'.$procedencia.'_'.$procedencia_id;
				//return $session.'_'.$usuario_asig.'_'.$correo.'_'.$asunto.'_'.$email_cc.'_'.$name_file_array.'_'.$contacto_id.'_'.$ruta_upload.'_'.$from.'_'.$nombre_user.'_'.$procedencia.'_'.$procedencia_id;
					global $db, $sugar_config,$current_user;
					$error = new SoapError();
						//authenticate
						if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session envia_email', '', '', '',  $error))
						{
							$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
							return false;
						}
						//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
					if($contacto_id!= ''){
						$contacto = new Contact();
						$contacto->retrieve($contacto_id);
						if($contacto->id =='') return 'Error, contacto no existe';
					}
				   
				   $emailObj = new Email();//*
								$defaults = $emailObj->getSystemDefaultEmail();//*
								$mail = new SugarPHPMailer();//*
								$mail->setMailerForSystem();//*
								$mail->From = $nombre_user.' <'.$from.'>';//*//$current_user->email1;//$usuario->email1
								$mail->FromName = $nombre_user;//*//$current_user->full_name;//$usuario->full_name
								$mail->ClearAllRecipients();//*
								$mail->ClearReplyTos();//*
								$mail->Subject=from_html($asunto);//*
								$mail->Body=$texto;//*
								$mail->AltBody=from_html($texto);
								$mail->prepForOutbound();//*
								 if($contacto_id!= ''){
										$mail->AddAddress($contacto->email1);
								 }else{
										$mail->AddAddress($correo);
								}//*
								 #########################adjunta documento##################################
										if(count($name_file_array) > 0 )
										{
											foreach ($name_file_array as $key => $value)
											{
												if($ruta_upload != '')
												{    
													$ch = curl_init($ruta_upload.$value);
													$fp = fopen('upload/'.$value, 'wb');
													curl_setopt($ch, CURLOPT_FILE, $fp);
													curl_setopt($ch, CURLOPT_HEADER, 0);
													curl_exec($ch);
													curl_close($ch);
													fclose($fp);
													
												
												   
												}
												
													$location   = "upload/".$value;
													$mime_type  = mime_content_type("upload/".$value); 
													$mail->AddAttachment($location, $value, 'base64', $mime_type);//si hay un adjunto
												
											}
										} 
									#############################################################################   
								
								/* if(count($email_cc) >0)//entra aqui si viene un listado de correos para copia oculta
									{
										foreach ($email_cc as $key => $value)
										{
											$mail->AddBCC($value);
										}
										
									}*/
									$mail->AddBCC($email_cc);
							if($res = @$mail->Send()){
								$email = new Email();
								$email->name = $asunto;
								$email->type='out';
								$email->status='sent';
								$email->intent='pick';
								$email->from_addr= $nombre_user.' <'.$from.'>';
								$email->to_addrs= $contacto->first_name." ".$contacto->last_name."<".$contacto->email1.">";
								$email->description_html=$texto;
								$email->assigned_user_id=$current_user->id;
								$email->assigned_user_name=$current_user->user_name;
								$email->from_name=$current_user->full_name;
								$email->parent_name=$contacto->first_name." ".$contacto->last_name;
								$email->date_sent=TimeDate::getInstance()->getNow(true)->modify("+0 days")->asDb();
								$email->parent_type=$procedencia;
								$email->parent_id=$procedencia_id;

								$email->from_addr_name=$current_user->full_name;

								$email->save();
									// eliminamos los archivos que se enviaron
								  if(count($name_file_array) > 0 )
										{
											foreach ($name_file_array as $key => $value)
											{
													unlink('upload/'.$value);
											}
										} 
								return 0;
							}else{
								return '1';
							}		
	   
	   
	}
// creado por armando 02/11/2015	
				/*este proceso es utilizado para almacenar los mensaje de chat*/
	function set_enviar_msj($session,$usuario_asig,$campo){
			global $db, $sugar_config;
				
				$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session set_enviar_msj', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
			 //evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
			  //return $campo['description'];
				 $query=" 	SELECT c.id AS case_id
							FROM cases c
							WHERE c.assigned_user_id = '".$campo['created_by']."'
							AND c.state = 'Open' 
							limit 1 ";
				$respt = $db->Query($query);
				$value = $db->fetchByAssoc($respt);
				$id_caso=$value['case_id'];
				if (!empty ($value)){
					$objeto ['case_id']= $value['case_id'];
					$objeto ['assigned_user_id']= $campo['created_by'];
					$objeto ['internal']= '0';
					$objeto ['description']= '<p>'.$campo['description'].'</p>';
					
					$det_case= new AOP_Case_Updates();
					foreach ($objeto as $key => $value) {
						$det_case->$key = $value;
					}
					
					
					$case= new aCase();
					$case->retrieve($id_caso);
					foreach ($caso as $key => $value) {
						$case->$key = $value;
					}
					$case->save();
					if($det_case->save()){
						return 1;
					} else { 
						return 0;
					}
								
				}
				if (empty ($value)){
					
					$caso ['name']= $campo['description'];
					$caso ['assigned_user_id']= $campo['created_by'];
					$caso ['type']= 'Consulta';
					$caso ['status']= 'Open_New';
					$caso ['priority']= 'P1';
					$caso ['state']= 'Open';
					
					$case= new aCase();
					foreach ($caso as $key => $value) {
						$case->$key = $value;
					}
					$case_id=$case->save();
					
					
					$d_caso ['case_id']= $case_id;
					$d_caso ['assigned_user_id']= $campo['created_by'];
					$d_caso ['internal']= '0';
					$d_caso ['description']= '<p>'.$campo['description'].'</p>';
					
					$det_case= new AOP_Case_Updates();
					foreach ($d_caso as $key => $value) {
						$det_case->$key = $value;
					}
					$det_case->save();
					if(!$isset($case_id)){
						return 1;
					} else { 
						return 0;
					}
				}
	}
// creado por armando 02/11/2015				
				/*obtiene los datos de los menjsajes*/
	function get_msj($session,$usuario_asig,$destino,$limit,$page){
			global $db, $sugar_config;
			$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_msj', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
			//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
			$anio = date('Y')-1;
			if($page != "1") {
				$limit= $limit*1;
				$offset = ($limit * ($page-1))+1;
			}else{
				$limit= $limit*1;
				$offset = 0;
			}
		
				$query="select replace(replace(cd.description,'<p>',''),'</p>','') as description,
							   cd.created_by,
							   cd.date_entered ,
							   concat(u.first_name,' ',u.last_name) as name
						from cases c
						join aop_case_updates cd on cd.case_id = c.id
						join users u on u.id = cd.assigned_user_id
						where c.created_by = '".$usuario_asig."'
						and c.deleted=0
						ORDER BY cd.date_entered DESC
						LIMIT ".$limit." 
						OFFSET ".$offset;
						$respt = $db->Query($query);
						while($value = $db->fetchByAssoc($respt)){
							$obj ['name']= $value['name'];
							$obj ['date_entered']= $value['date_entered'];
							$obj ['description']= $value['description'];
							//$obj ['created_by']= $value['created_by'];
							
													
					if ($value['created_by'] != $usuario_asig){
						$obj ['tipo']	= 'R';
					}else{
						$obj ['tipo']	= 'E';
					}
					$objeto [] = $obj ;
				}	
	
			if (empty($objeto)){
				return '';
			}else{
				// $query="  update mo_mensajes
						// set estado = 0
					  // where estado = 1 and assigned_user_id = '".$remitente."' ";	
					// $respt = $db->Query($query);
				return $objeto;
			}
	
	}
	// creado por armando 02/11/2015			
	function get_cantidad_msj_nuevo ($session,$usuario_asig){
				global $db, $sugar_config;
					$error = new SoapError();
			//authenticate
			if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_cantidad_msj_nuevos', '', '', '',  $error))
			{
				$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
				return false;
			}
			//evento utilizado para detectar la actividad del usuario
				//	$this->actividad_user($usuario_asig);
				//------------------------------------------------------
		 $query="  select case WHEN cd.created_by = '1'
							THEN '1' 
							ELSE '0'
							END
							AS cantidad 
					FROM cases c
					JOIN aop_case_updates cd on cd.case_id = c.id
					WHERE c.assigned_user_id = '".$usuario_asig."'
					AND c.state = 'Open'
					ORDER by cd.date_entered desc
					LIMIT 1 ; ";
			$respt = $db->Query($query);
			$value = $db->fetchByAssoc($respt);		
				return $value['cantidad'] ;    						
	}  
// creado por armando 02/11/2015
	function set_log_acceso($session,$usuario_asig,$campos){
		global $db, $sugar_config;
				$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session set_log_acceso', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
			//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
			//return $oportunidad_id;
			/*en esta ruta se encuentran los posiblea campos a utiilizar (C:\xampp\htdocs\prototipo\modules\Opportunity)*/
			$login= new mo_login();
		
			//$login->retrieve();
			foreach ($campos as $key => $value) {
				$login->$key = $value;
			}
			//return 'prueba'; 
			//$login->save();
			//return 'prueba'; 
			if($login->save()){
			return 1;
		} else { 
			return 0;
		}
				
	}
		
				/*obtiene los datos de las tareas cerradas*/
	function get_agenda_tlf($session,$usuario_asig){
	global $db, $sugar_config;
	$anio = date('Y')-1;
		$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_agenda_tlf', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
		 //evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
		 $tlf = new Call();
		$list_tlf = $tlf->get_full_list("", " calls.assigned_user_id = '".$usuario_asig."' ",true);
		//$cantidad_cumpleanos = count($cumpleanos_list);

		 foreach ($list_tlf as $n) {
			 
			$objeto []=$n;
		}
		if (empty($objeto)){
			return '';
		}else{
			return $objeto;
		}
	}  
	// creado por armando 02/11/2015			
				/* detalle negocio*/
	function get_Fecha_carga($session,$usuario_asig){
		global $db, $sugar_config;
		$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_Fecha_carga', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
		 //evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
		/* $query=" SELECT max(date_entered) as fecha_actualizacion
					FROM crm_negocios 
					where assigned_user_id = '".$usuario_asig." ' ";*/
					$query="select max(car.LOGDATE) as fecha_actualizacion 
							from etl_temp.cargas_log car 
							where car.STATUS = 'end' 
							and car.ERRORS = 0";
		$respt = $db->Query($query);
		while($value = $db->fetchByAssoc($respt)){
				$objeto ['fecha_actualizacion']= $value['fecha_actualizacion'];
		}
		if (empty($objeto)){
			return '';
		}else{
			return $objeto;
		}
		
	}
		// creado por armando 02/11/2015		
					/*obtiene los datos de las solicitudes crossseling_ nuevas*/
	function busca_oportunidad($session,$usuario_asig,$id_oportunidad){
		global $db, $sugar_config;
		$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session busca_oportunidad', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
		 //evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
		 $query="   SELECT * FROM view_busca_oportunidad where assigned_user_id = '".$usuario_asig."' and id_oportunidad = '".$id_oportunidad."'";
		$respt = $db->Query($query);
		while($value = $db->fetchByAssoc($respt)){
			if (!empty($value['id_oportunidad'])){
				$obj ['id_negocio']=$value['id_negocio'];
				$obj ['id_oportunidad']=$value['id_oportunidad'];
				$obj ['id_contacto']=$value['id_contacto'];
				$obj ['id_pasajero']=$value['id_pasajero'];
				$obj ['numero_negocio']=$value['numero_negocio'];
				$obj ['rut_comprador']=$value['rut_comprador'];
				$obj ['fecha_compra']=$value['fecha_compra'];
				$obj ['nombre_comprador']=ucwords($value['nombre_comprador']);
				$obj ['monto_oportunidad']=number_format($value['monto_oportunidad'],0,"",".");
				$obj ['monto_venta']=number_format($value['monto_venta'],0,"",".");
				$obj ['clasificacion_cliente']=$value['clasificacion_cliente'];
				$obj ['fecha_salida']=$value['fecha_salida'];
				$obj ['destino']=$value['destino'];
				$obj ['ruta']=$value['RUTA'];
				$obj ['dias']=$value['duracion_viaje'];
				$obj ['estado']=$value['estado'];
				$obj ['productos_c']=$value['productos_c'];
				if (!empty($value['phone_home'])){
					$obj ['telefono']=$value['phone_home'];
				}else{
					$obj ['telefono']= 'Sin Telefono';
				}
					$contacto =new Contact();
					$contacto->retrieve($value['id_contacto']);
					if(!empty($contacto->email1)){
						$email=$contacto->email1;
					}else{
						$email = 'Sin Email';
					}
					$obj ['email']=$email;
				$query=" select count(*) c_email from contacts c 
						inner join email_addr_bean_rel ea on ea.bean_id=c.id
						inner join emails_email_addr_rel er on er.email_address_id = ea.email_address_id
						left join emails e on e.id=er.email_id
						left join emails y on y.id=er.email_id
						where c.id='".$contacto->id."' and er.address_type='to' 
						and (e.parent_id= '".$value['id_oportunidad']."'  
						or (y.parent_id = '".$contacto->id."' and y.date_entered < '2015-09-21'))  -- caso de transacciones antiguas
						and e.deleted=0 and ea.deleted=0 and er.deleted=0  and c.deleted=0";
				$res = $db->Query($query);
				if($row = $db->fetchByAssoc($res)){
					$obj ['c_envio_email'] = $row['c_email'];
				}
					$objeto []= $obj;
			}
		}
		if (empty($objeto)){
			return '';
		}else{
			return $objeto;
		}
		
	}
	// creado por armando 02/11/2015			
	function busca_tarea ($session,$usuario_asig,$id_tarea){
		global $db, $sugar_config;
		$anio = date('Y')-1;
		$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session busca_tarea', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
			//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
			 $query=" SELECT * FROM view_universo_tareas WHERE ususario_asig = '".$usuario_asig."' and id = '".$id_tarea."'";  
					 
			$respt = $db->Query($query);
			while($n = $db->fetchByAssoc($respt)){
				 
				$negocio =new crm_negocios();
				$negocio->retrieve($n['crm_negocios_id_c']);
				
				$contacto =new Contact();
				$contacto->retrieve($n['id_contact']);
								

				$obj ['id_negocio']=$negocio->id;
				$obj ['id_tarea']=$n['id'];
				$obj ['id_contacto']=$contacto->id;
				$obj ['numero_negocio']=$negocio->name;
				$obj ['rut_contacto']=$contacto->phone_fax;
				$obj ['nombre_contacto']=ucwords($contacto->last_name);
				$obj ['voucher_c']=$n['voucher_c'];
				$obj ['monto_venta']=number_format($negocio->monto,0,"",".");
				$obj ['fecha_retorno']=$negocio->fecha_destino;
				$obj ['monto_oportunidad']= 0;
				$obj ['documentacion_c']=$n['documentacion_c'];
				$obj ['clasificacion_cliente']=$contacto->clasificacion_c;
				$segundos=strtotime($negocio->fecha_salida) - strtotime('now');
				$diferencia_dias=intval($segundos/60/60/24)+1;
				$obj ['dias_restante']=$diferencia_dias;
				if ($diferencia_dias > 0 && $diferencia_dias <= 20){
					$color='con_color';
				}else{
					$color='sin_color';
				}
				$obj ['color_fila']=$color;
				$obj ['fecha_salida']=$negocio->fecha_salida;
				$obj ['dias']=$negocio->duracion_viaje;
				$obj ['destino']=$negocio->destino;
				$obj ['descripciones']=$n['description'];
				$obj ['ruta']=$negocio->ruta;
					if(!empty($contacto->email1)){
							$email = $contacto->email1;
					}else{
						$email = 'Sin Email';
					}
					$obj ['email_contacto'] = $email;
				//$obj ['email_contacto']=$contacto->email1;
				if (!empty($contacto->phone_home)){
						$obj ['telefono_contacto']=$contacto->phone_home;;
					}else{
						$obj ['telefono_contacto']= 'Sin Telefono';
					}
				$query="select count(*) c_email from contacts c 
						inner join email_addr_bean_rel ea on ea.bean_id=c.id
						inner join emails_email_addr_rel er on er.email_address_id = ea.email_address_id
						left join emails e on e.id=er.email_id
						left join emails y on y.id=er.email_id
						where c.id='".$contacto->id."' and er.address_type='to' 
						and (e.parent_id= '".$n['id']."'  
						or (y.parent_id = '".$contacto->id."' and y.date_entered < '2015-09-21')) -- caso de transacciones antiguas
						and e.deleted=0 and ea.deleted=0 and er.deleted=0  and c.deleted=0";
				$res = $db->Query($query);
				if($row = $db->fetchByAssoc($res)){
					$obj ['c_envio_email'] = $row['c_email'];
				}
				$objeto []=$obj;
			}
			if (empty($objeto)){
				return '';
			}else{
				return $objeto;
			}
	}
		// creado por armando 02/11/2015		
	function get_busca_cliente ($session,$usuario_asig,$limit,$page,$campo){
		
		global $db, $sugar_config;
			$anio = date('Y')-1;
			$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_busca_cliente', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
			//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
			
			if ($campo['campo'] != 'ne'){ 
				$nombre=$campo['dato'];
			}else{
				$negocio=$campo['dato'];
			}	
				$query=" SELECT * 
						from view_busca_trx_cli  
						WHERE  user_contact = '".$usuario_asig."'
						AND nombre LIKE '%".urldecode($nombre)."%'
						AND negocio LIKE '%".urldecode($negocio)."%'
						AND rut LIKE '%".urldecode($campo['rut'])."%'
						LIMIT ".$limit." 
						OFFSET ".$page;
				//return $query;
						$respt = $db->Query($query);
						$objeto = array();
						while($value = $db->fetchByAssoc($respt)){
							$obj ['modulo']= $value['modulo'];
							$obj ['estado']= $value['estado'];
							$obj ['rut']= $value['rut'];
							$obj ['nombre']= $value['nombre'];
							$obj ['negocio']= $value['negocio'];
							$obj ['rol']= $value['rol'];
							$obj ['activo']= $value['activo'];
							$objeto [] = $obj;
						}	
						
						$query_2=" select count(*) as cantidad
									FROM contacts c
									LEFT JOIN crm_pasajeros p        ON p.contact_id_c = c.id AND p.deleted = '0' AND p.tipo_pax IS NOT NULL and p.assigned_user_id = '".$usuario_asig."'
									WHERE c.assigned_user_id = '".$usuario_asig."'
									AND c.deleted = '0'
									and c.last_name LIKE '%".urldecode($nombre)."%'
									AND p.crm_negocios LIKE '%".urldecode($negocio)."%'
									AND c.id LIKE '%".urldecode($campo['rut'])."%'";
						$respt_2 = $db->Query($query_2);
						$cantidad = $db->fetchByAssoc($respt_2);
						
					if (empty($objeto)){
						return array(
								 "datos" => $objeto,
								 "cantidad" => 0
								);
					}else{
						return array(
								 "datos" => $objeto,
								 "cantidad" => $cantidad['cantidad']
								);
					}
	
	}
		// creado por armando 02/11/2015		
	function get_detalle_cliente ($session,$usuario_asig,$id_contacto){
		global $db, $sugar_config;
			$anio = date('Y')-1;
			$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_detalle_cliente', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
				//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
				$query="SELECT 
								 c.phone_fax as rut
								,c.last_name as nombre
								,c.birthdate as f_nacimiento
								,e.email_address
								,c.phone_home
								,c.description
								FROM contacts c 
								LEFT JOIN email_addr_bean_rel er ON er.bean_id=c.id 
								LEFT JOIN email_addresses e ON e.id = er.email_address_id and e.deleted = '0'
								where c.id = '".$id_contacto."' 
								and c.assigned_user_id = '".$usuario_asig."'";
						$respt = $db->Query($query);
						while($value = $db->fetchByAssoc($respt)){
							$obj ['rut']= $value['rut'];
							$obj ['nombre']= $value['nombre'];
							$obj ['email_address']= $value['email_address'];
							$obj ['f_nacimiento']= $value['f_nacimiento'];
							$obj ['phone_home']= $value['phone_home'];
							$obj ['description']= $value['description'];
								$query_2="  select * 
											from view_oportunidades_rut
											where pasajeros = '".$value['rut']."'
											and usuario_asig = '".$usuario_asig."'
											and deleted = '0'";
								$respt_2 = $db->Query($query_2);
									$oppor_o = array();
									while($value_2 = $db->fetchByAssoc($respt_2)){
										$oppor ['negocio']= $value_2['negocio'];
										$oppor ['tipo']= $value_2['tipo'];
										$oppor ['rol']= $value_2['rol'];
										$oppor ['destino']= $value_2['destino'];
										$oppor ['f_destino']= '';
										$oppor ['ruta']= $value_2['ruta'];
										$oppor ['f_salida']= $value_2['f_salida'];
										$segundos=strtotime($value_2['f_salida']) - strtotime('now');
										$diferencia_dias=intval($segundos/60/60/24);
										$oppor ['dias_restante']=$diferencia_dias;
										$oppor ['monto_oportunidad']= $value_2['monto_oportunidad'];
										$oppor ['monto_venta']= $value_2['monto_venta'];
										$oppor ['id']= $value_2['id'];
										$oppor ['comprador']= $value_2['comprador'];
										$oppor ['pasajeros']= $value_2['pasajeros'];													
										$oppor_o [] = $oppor;
									}
																		
								$obj ['p']= $oppor_o;
								$query_3="select * 
										  from view_tareas_rut where 
										  where pasajeros = '".$value['rut']."'
											and usuario_asig = '".$usuario_asig."'
											and deleted = '0'";
								$respt_3 = $db->Query($query_3);
									$tarea_t = array();
									while($value_3 = $db->fetchByAssoc($respt_3)){
										$tarea ['negocio']= $value_3['negocio'];
										$tarea ['tipo']= $value_3['tipo'];
										$tarea ['rol']= $value_3['rol'];
										$tarea ['destino']= $value_3['destino'];
										$tarea ['ruta']= $value_3['ruta'];
										$tarea ['f_salida']= $value_3['f_salida'];
										$segundos=strtotime($value_3['f_salida']) - strtotime('now');
										$diferencia_dias=intval($segundos/60/60/24);
										$tarea ['dias_restante']=$diferencia_dias;
										$tarea ['f_destino']= $value_3['f_destino'];
										$tarea ['id']= $value_3['id'];
										$tarea ['monto_oportunidad']= '';
										$tarea ['monto_venta']= $value_3['monto_venta'];
										$tarea ['comprador']= $value_3['comprador'];
										$tarea ['pasajeros']= $value_3['pasajeros'];
										$tarea_t [] = $tarea;
									}
										
								$obj ['ta']= $tarea_t;
							
							$objeto [] = $obj;
						}	
						
						
					if (empty($objeto)){
						return '';
					}else{
						return $objeto;
					}
	
	}
		// creado por armando 02/11/2015		
				/*obtiene los datos del contacto*/
	function get_buscar_encuesta($session,$usuario_asig,$rut){
			global $db, $sugar_config;
			$anio = date('Y')-1;
			$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_buscar_encuesta', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
				//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
						$query=" 
						SELECT  
								token as rut, 
								submitdate as fecha_respuesta, 
								startdate as fecha_envio, 
								datestamp as fecha_vista, 
								(SELECT  question
								FROM survey.questions
								WHERE  concat(sid,'X',gid,'X',qid) = '146131X1X1') AS PREGUNTA_1,
								rf.answer as respuesta_1,
								(SELECT  question
								FROM survey.questions
								WHERE  concat(sid,'X',gid,'X',qid) = '146131X1X17') AS PREGUNTA_2,
								146131X1X17  AS  respuesta_2,
								(SELECT  question
								FROM survey.questions
								WHERE  concat(sid,'X',gid,'X',qid) = '146131X1X18') AS PREGUNTA_3,
								146131X1X18  AS  respuesta_3
						FROM survey.survey_146131 r
						left join survey.answers rf on rf.code = r.146131X1X1
						where r.token = '".$rut."'";
						$respt = $db->Query($query);
						while($value = $db->fetchByAssoc($respt)){
								$obj ['rut']		=$value ['rut'];
								$obj ['fecha_respuesta']		=$value ['fecha_respuesta'];
								$obj ['fecha_envio']	=$value ['fecha_envio'];
								$obj ['fecha_vista'] = $value['fecha_vista'];
								$obj ['pregunta_1']		=$value['PREGUNTA_1'];
								$obj ['respuesta_1']		=$value ['respuesta_1'];
								$obj ['pregunta_2']		=$value ['PREGUNTA_2'];
								$obj ['respuesta_2']	=$value ['respuesta_2'];
								$obj ['pregunta_3'] = $value['PREGUNTA_3'];
								$obj ['respuesta_3']		=$value['respuesta_3'];
								$obj ['pregunta_4']		=$value ['PREGUNTA_4'];
								$obj ['respuesta_4']		=$value ['respuesta_4'];
								$obj ['pregunta_5']	=$value ['PREGUNTA_5'];
								$obj ['respuesta_5'] = $value['respuesta_5'];
								$obj ['pregunta_6']		=$value['PREGUNTA_6'];
								$obj ['respuesta_6']		=$value ['respuesta_6'];
								$obj ['pregunta_7']		=$value ['PREGUNTA_7'];
								$obj ['respuesta_7']	=$value ['respuesta_7'];
								
								$objeto [] = $obj ;
						}
			
			if (empty($objeto)){
				return '';
			}else{
				return $objeto;
			}
	
	}
	// creado por armando 02/11/2015
	/*obtiene los datos de las solicitudes web pendiente*/
	function set_carga_solicitud($session,$usuario_asig){
		global $db, $sugar_config;
			$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session set_carga_solicitud', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
				//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
			 $query="  select count(*) cantidad
					from opportunities o
					join opportunities_cstm od on od.id_c = o.id
					where o.assigned_user_id = '".$usuario_asig."'  
					AND o.opportunity_type = 'solicitud_web'
					AND ( o.sales_stage in ('asignado') or  DATE_FORMAT(od.date_assigned_c,'%m-%d-%Y') = DATE_FORMAT(NOW(),'%m-%d-%Y'))
					AND o.deleted=0";	
			//$GLOBALS['log']->special($query);
			$res = $db->Query($query);
				$x=0;
				if($row = $db->fetchByAssoc($res)){
					if ($row['cantidad'] < 5){
						$cantidad = 5-$row['cantidad'];
						$query2="select id from opportunities o where o.sales_stage = 'asignado' and o.deleted=0 and o.assigned_user_id = '1' limit ".$cantidad;	
						$respt2 = $db->Query($query2);
						//$GLOBALS['log']->special($query2);
						while ($fila = $db->fetchByAssoc($respt2)) {
								$x++;
								$oportunidad= new Opportunity();
								$oportunidad->retrieve($fila['id']);
								$oportunidad->assigned_user_id = $usuario_asig;
								$oportunidad->date_assigned_c = date('Y-m-d H:i:s');
								$oportunidad->save();
								
						}
						$objeto ="Total oportunidades asignadas: ".$x;
					}else {
						$objeto = " Usted Llego al Tope de Oportunidades Gestionada ";
					}
		
				}
			/*return $db->fetchByAssoc($respt);*/
			return $objeto;
	}
	// creado por armando 02/11/2015
	/*este procceso edita la informacion del estado de la oportunidad*/
	function set_editar_oportunidad_web($session,$usuario_asig,$oportunidad_id,$campos_opor,$campos_soli){
		
		
		global $db, $sugar_config;
		//authenticate
		if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session set_editar_oportunidad_web', '', '', '',  $error))
		{
			$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
			return false;
		}	
		//return $oportunidad_id;
		//
	//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
	
		if(!empty($oportunidad_id)){
			/*en esta ruta se encuentran los posiblea campos a utiilizar (C:\xampp\htdocs\prototipo\modules\Contacts)*/
			$oportunidad = new Opportunity();
			$oportunidad->retrieve($oportunidad_id);
			$solicitud = new crm_solicitudes();
			$solicitud->retrieve($oportunidad->crm_solicitud_id_c);
			foreach ($campos_opor as $key => $value) {
				$oportunidad->$key = $value;
			}
			if ($oportunidad->save()){
				foreach ($campos_soli as $key => $value) {
					$solicitud->$key = $value;
				}
				if ($solicitud->save()){
					return 1;
				}else{
					return 0;		
				}
			}else{
				return 0;		
			}
		} else { 
			return 0;
		}	
	}
	
	
	//Esta Funcion Cambia el Estado de la Oportunidad en Cross Selling a Abierto//
	// Creado por Jose Gregorio 11-01-2016
function set_editar_cambio_estado_cross_selling($session,$usuario_asig,$oportunidad_id,$campos_opor,$campos_soli){
		
		//return "Estado:".$campos_opor[1]."<br>";
		//die();
		global $db, $sugar_config;
		//authenticate
		if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session set_editar_cambio_estado_cross_selling', '', '', '',  $error))
		{
			$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
			return false;
		}	
		//return $oportunidad_id;
		//
	//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
	
		if(!empty($oportunidad_id)){
			/*en esta ruta se encuentran los posiblea campos a utiilizar (C:\xampp\htdocs\prototipo\modules\Contacts)*/
			$oportunidad = new Opportunity();
			$oportunidad->retrieve($oportunidad_id);
			$solicitud = new crm_solicitudes();
			$solicitud->retrieve($oportunidad->crm_solicitud_id_c);
			foreach ($campos_opor as $key => $value) {
				$oportunidad->$key = $value;
				//return "foreach:".$oportunidad->$key."<br>";//le asigna el valor q colocas en el campo numero neg
			}
			if ($oportunidad->save()){
				foreach ($campos_soli as $key => $value) {
					$solicitud->$key = $value;
				}
				if ($solicitud->save()){
					return 1;
				}else{
					return 0;		
				}
			}else{
				return 0;		
			}
		} else { 
			return 0;
		}	
	}
//***************************************************************************************************************************//
		//Esta Funcion Cambia el Estado de la Oportunidad en Recompra a Abierto//
	// Creado por Jose Gregorio 11-01-2016
function set_editar_cambio_estado_recompra($session,$usuario_asig,$oportunidad_id,$campos_opor,$campos_soli){
		
		//return "Estado:".$campos_opor[1]."<br>";
		//die();
		global $db, $sugar_config;
		//authenticate
		if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session set_editar_cambio_estado_recompra', '', '', '',  $error))
		{
			$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
			return false;
		}	
		//return $oportunidad_id;
		//  
	//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
	
		if(!empty($oportunidad_id)){
			/*en esta ruta se encuentran los posiblea campos a utiilizar (C:\xampp\htdocs\prototipo\modules\Contacts)*/
			$oportunidad = new Opportunity();
			$oportunidad->retrieve($oportunidad_id);
			$solicitud = new crm_solicitudes();
			$solicitud->retrieve($oportunidad->crm_solicitud_id_c);
			foreach ($campos_opor as $key => $value) {
				$oportunidad->$key = $value;
				//return "foreach:".$oportunidad->$key."<br>";//le asigna el valor q colocas en el campo numero neg
			}
			if ($oportunidad->save()){
				foreach ($campos_soli as $key => $value) {
					$solicitud->$key = $value;
				}
				if ($solicitud->save()){
					return 1;
				}else{
					return 0;		
				}
			}else{
				return 0;		
			}
		} else { 
			return 0;
		}	
	}
	
	
//***********************************************************************************************//
		//Esta Funcion Cambia el Estado de la Oportunidad en Fugados a Abierto//
	// Creado por Jose Gregorio 12-01-2016
function set_editar_cambio_estado_fugados($session,$usuario_asig,$oportunidad_id,$campos_opor,$campos_soli){
		
		//return "Estado:".$campos_opor[1]."<br>";
		//die();
		global $db, $sugar_config;
		//authenticate
		if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session set_editar_cambio_estado_fugados', '', '', '',  $error))
		{
			$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
			return false;
		}	
		//return $oportunidad_id;
		//  
	//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
	
		if(!empty($oportunidad_id)){
			/*en esta ruta se encuentran los posiblea campos a utiilizar (C:\xampp\htdocs\prototipo\modules\Contacts)*/
			$oportunidad = new Opportunity();
			$oportunidad->retrieve($oportunidad_id);
			$solicitud = new crm_solicitudes();
			$solicitud->retrieve($oportunidad->crm_solicitud_id_c);
			foreach ($campos_opor as $key => $value) {
				$oportunidad->$key = $value;
				//return "foreach:".$oportunidad->$key."<br>";//le asigna el valor q colocas en el campo numero neg
			}
			if ($oportunidad->save()){
				foreach ($campos_soli as $key => $value) {
					$solicitud->$key = $value;
				}
				if ($solicitud->save()){
					return 1;
				}else{
					return 0;		
				}
			}else{
				return 0;		
			}
		} else { 
			return 0;
		}	
	}	
	
//**************************************************************************************************************************//
					
// creado por armando 02/11/2015
	function envia_email_solicitud ($session,$usuario_asig,$correo,$asunto,$texto,$email_cc,$name_file_array,$contacto_id,$ruta_upload,$from,$nombre_user,$procedencia,$procedencia_id,$pdf,$estado){
     //return $session.'_'.$usuario_asig.'_'.$correo.'_'.$asunto.'_'.$email_cc.'_'.$name_file_array.'_'.$contacto_id.'_'.$ruta_upload.'_'.$from.'_'.$nombre_user.'_'.$procedencia.'_'.$procedencia_id.'_'.$pdf.'_'.$estado;
		global $db, $sugar_config,$current_user;
		//authenticate
		if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session envia_email_solicitud', '', '', '',  $error))
		{
			$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
			return false;
		}	
		//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
		if($contacto_id!= ''){
			$contacto = new Lead();
		//	$contacto->retrieve_by_string_fields(array('phone_fax' => $contacto_id));
			$contacto->retrieve($contacto_id);
			if($contacto->id =='') return 'Error, contacto no existe '.$contacto_id;
		}
	   
					$emailObj = new Email();
					$defaults = $emailObj->getSystemDefaultEmail();
					$mail = new SugarPHPMailer();
					$mail->setMailerForSystem();
					$mail->From = $nombre_user.' <'.$from.'>';
					$mail->FromName = $nombre_user;
					$mail->ClearAllRecipients();
					$mail->ClearReplyTos();
					$mail->Subject=from_html($asunto);
					$mail->Body=$texto;
					$mail->AltBody=from_html($texto);
					$mail->prepForOutbound();
					 if($contacto_id!= ''){
							$mail->AddAddress($contacto->email1);
					 }else{
							$mail->AddAddress($correo);
					}
					 #########################adjunta documento##################################
							if(count($name_file_array) >0 )
							{
								foreach ($name_file_array as $key => $value)
								{
									if($ruta_upload != '')
									{    
										$ch = curl_init($ruta_upload.$value);
										$fp = fopen('upload/'.$value, 'wb');
										curl_setopt($ch, CURLOPT_FILE, $fp);
										curl_setopt($ch, CURLOPT_HEADER, 0);
										curl_exec($ch);
										curl_close($ch);
										fclose($fp);
									}
										$location   = "upload/".$value;
										$mime_type  = mime_content_type("upload/".$value); 
										$mail->AddAttachment($location, $value, 'base64', $mime_type);//si hay un adjunto
								}
							} 
						#############################################################################  
						#########################producto joomla##################################
							if ($pdf != null && $pdf != ''){
								/* $array = split('/',($pdf)); */
								/* $archivo = trim($array[count($array)-1]); */
								

								$ch = curl_init($ruta_upload.$pdf);
								$fp = fopen('upload/'.$pdf, 'wb');
								curl_setopt($ch, CURLOPT_FILE, $fp);
								curl_setopt($ch, CURLOPT_HEADER, 0);
								curl_exec($ch);
								curl_close($ch);
								fclose($fp);
								$location   = "upload/".$pdf;
								$mime_type  = mime_content_type("upload/".$pdf); 
								$mail->AddAttachment($location, $pdf, 'base64', $mime_type);//si hay un adjunto									
							}
					/* if(count($email_cc) >0)//entra aqui si viene un listado de correos para copia oculta
						{
							foreach ($email_cc as $key => $value)
							{
								$mail->AddBCC($value);
							}
							
						}*/
					$mail->AddBCC($email_cc);
					
				if(@$mail->Send()){
					$email = new Email();
					$email->name = $asunto;
					$email->type='out';
					$email->status='sent';
					$email->intent='pick';
					//$mail->From = 'Turismo Cocha <aytriago@cocha.com>';//*//$current_user->email1;//$usuario->email1
					$email->from_addr= $nombre_user.' <'.$from.'>';//$current_user->full_name."<".$current_user->email1.">";
					$email->to_addrs= $contacto->first_name." ".$contacto->last_name."<".$contacto->email1.">";
					$email->description_html=$texto;
					$email->assigned_user_id=$current_user->id;
					$email->assigned_user_name=$current_user->user_name;
					$email->from_name=$current_user->full_name;
					$email->parent_name=$contacto->first_name." ".$contacto->last_name;
					$email->date_sent=TimeDate::getInstance()->getNow(true)->modify("+0 days")->asDb();
					
					// $email->parent_type='Contacts';
					// $email->parent_id=$contacto->id;
					// if ($procedencia != 'EnvioCotizacion') {
						// $email->parent_type=$procedencia;
					// }else{
						$email->parent_type='opportunities';	
					// }
					$email->parent_id=$procedencia_id;
					$email->from_addr_name=$current_user->full_name;
					$email->save();
					
					if ($procedencia == 'EnvioCotizacion') {
						$oportunidad= new Opportunity();
						$oportunidad->retrieve($procedencia_id);
						$oportunidad->sales_stage = 'cotizacion';
						$oportunidad->save();
					}
					
					if ($estado == 'asignado'){
						$oportunidad= new Opportunity();
						$oportunidad->retrieve($procedencia_id);
						$oportunidad->sales_stage = 'contactado';
						$oportunidad->save();
					}
					
					return 0;
				}else{
					return 1;
				}	
					
	}

		// creado por armando 02/11/2015
	function get_oportunidad_detalle_web($session,$usuario_asig,$oportunidad_id){
				global $db, $sugar_config;
				//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
				//authenticate
					// if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_oportunidad_detalle_web', '', '', '',  $error))
					// {
						// $GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
						// return false;
					// }	
					 
				$oportunidad = new Opportunity();
				$oportunidad->retrieve($oportunidad_id);
				 
				$query=" select leads_opportunities_1leads_ida as lead 
							from leads_opportunities_1_c 
							where leads_opportunities_1opportunities_idb = '".$oportunidad_id."'";	
				 $respt = $db->Query($query); 
				 if($row_1 = $db->fetchByAssoc($respt)){
					$potencial=$row_1['lead'];  
				}
				
				$cliente = new Lead();
				$cliente->retrieve($potencial);
				
				$solicitud = new crm_solicitudes();
				$solicitud->retrieve($oportunidad->crm_solicitud_id_c);
				
				$cadena = $solicitud->description;
			
				 
				$obj['nombre']				=ucwords($cliente->first_name);
				$obj['apellido']			=ucwords($cliente->last_name);
				$obj['rut']				    =$cliente->phone_fax;
				$obj['email']				=$cliente->email1;
				$obj['telefono']			=$cliente->phone_home;
				$obj['id_cli']			    =$cliente->id;
				$obj['fecha']			    =$solicitud->fecha_viaje;
				$obj['fecha_flexible']		=$solicitud->fecha_flexible;
				$obj['canal']				=$oportunidad->lead_source;
				$obj['habitaciones']		=$solicitud->hotel_habitacion;  
				$obj['adulto']				=$solicitud->adultos;
				$obj['ninos']				=$solicitud->ninos;
				$obj['edad_ninos']			=$solicitud->edades;
				$obj['fecha_asignacion']	=$oportunidad->date_assigned_c;
				$obj['fecha_formulario']	=$oportunidad->date_entered;
				$obj['estado']				=$oportunidad->sales_stage;							
				$obj['agente_viaje']		=$solicitud->agente;
				$obj['description']			= $solicitud->description;					
		        
		                        
		//$negocio =new crm_negocios();
		  return $obj;
	}
		// creado por armando 02/11/2015
	function get_oportunidad_detalle_producto_web($session,$usuario_asig,$oportunidad_id){
			global $db, $sugar_config;
				//authenticate
				// if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_oportunidad_detalle_producto_web', '', '', '',  $error))
				// {
					// $GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					// return false;
				// }
				//$oportunidad_id='5a9e0877-2eb1-334d-b9d5-54666ab34fa1';
				// $query = " select crm_solicitud_id_c as id_solicitud from opportunities_cstm where id_c = '".$oportunidad_id."'";
				 // $respt = $db->Query($query); 
				 // if($row_1 = $db->fetchByAssoc($respt)){
					// $id_solicitud=$row_1['id_solicitud'];  
				// }
				
				// $solicitud = new crm_solicitudes();
				// $list_soli = $solicitud->get_full_list("", " crm_solicitudes.id = '".$id_solicitud."' ",true);
			 // foreach ($list_soli as $row) {	

			 //evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
			$query="select opp.assigned_user_id as id_user ,l.id as id_lead, UPPER(concat(l.first_name,' ',l.last_name)) as nombre ,s.* 
					from crm_solicitudes s
					join opportunities_crm_solicitudes_1_c rs on rs.opportunities_crm_solicitudes_1crm_solicitudes_idb = s.id
					join leads_opportunities_1_c lea on lea.leads_opportunities_1opportunities_idb = rs.opportunities_crm_solicitudes_1opportunities_ida
					join opportunities opp on opp.id = lea.leads_opportunities_1opportunities_idb
					join leads l on l.id = lea.leads_opportunities_1leads_ida
					where rs.opportunities_crm_solicitudes_1opportunities_ida =  '".$oportunidad_id."'";
			$respt = $db->Query($query);
			while($value = $db->fetchByAssoc($respt)){	
				$usuario = new User();
				$usuario->retrieve($value['id_user']);
			
				  $link_web = str_replace("crm.cochadigital.com/"," ", $value['url']);
				  $link_pdf = str_replace("' target='_blank'><button style='background:red'>Ver PDF</button></a>","",str_replace("' target='_blank'><button >Ver URL</button></a>","", str_replace("<a href='http://", "", $value['pdf_joomla'],str_replace("http://http://","",$value['pdf_joomla']))));
				  $detalle['id'] = $value['id'];
				  $detalle['id_user'] = $usuario->id;
				  $detalle['id_lead'] = $value['id_lead'];
				  $detalle['email1'] = $usuario->email1;
				  $detalle['nombre_usuario'] = $usuario->first_name .' '.$usuario->last_name;
				  $detalle['phone_work'] = $usuario->phone_work;
				  $detalle['department'] = $usuario->department;
				  $detalle['cargo'] = '';
				  $detalle['nombre'] = $value['nombre'];
				  $detalle['id_producto'] = $value['producto_id'];
				  $detalle['name'] = $value['name'];
				  $detalle['operador'] = $value['agente'];
				  $detalle['url'] = $link_web; 
				  $detalle['pdf'] = $link_pdf;
				
				 if (empty($detalle)){
						//echo 'Excepción capturada: ',  $e->getMessage(), "\n";
						$detalle['id_producto'] = ' ';
						$detalle['name'] = ' ';
						$detalle['operador'] = ' ';
						$detalle['url'] = ' '; 
						$detalle['pdf'] =' ';
				 }
				 $obj[] = $detalle;
			} 
			//$negocio =new crm_negocios();
			  return $obj;
		//return $link_pdf;
	}
		// creado por armando 02/11/2015
	/*este procceso edita la informacion del contacto, email y telefono*/
	function set_editar_contactos_web($session,$usuario_asig,$contacto_id,$campos){
		global $db, $sugar_config;
				
				//authenticate
					if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session set_editar_contactos_web', '', '', '',  $error))
					{
						$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
						return false;
					}	
					//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
		if(!empty($contacto_id)){
			/*en esta ruta se encuentran los posiblea campos a utiilizar (C:\xampp\htdocs\prototipo\modules\Contacts)*/
			$contacto = new Lead();
			$contacto->retrieve($contacto_id);
			foreach ($campos as $key => $value) {
				$contacto->$key = $value;
			}
			if ($contacto->save()){
			return 1;
			}else{
			return 0;		
			}
		} else { 
			return 0;
		}
	}
	
		// creado por armando 02/11/2015
	function set_editar_detalle_web($session,$usuario_asig,$oportunidad_id,$dato_opor,$dato_soli){
		global $db, $sugar_config;
		
		//authenticate
		if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session set_editar_detalle_web', '', '', '',  $error))
		{
			$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
			return false;
		}	
		//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
		if(!empty($oportunidad_id)){
			/*en esta ruta se encuentran los posiblea campos a utiilizar (C:\xampp\htdocs\prototipo\modules\Contacts)*/
			$oportunidad = new Opportunity();
			$oportunidad->retrieve($oportunidad_id);
			foreach ($dato_opor as $key => $value) {
				$oportunidad->$key = $value;
			}
			if ($oportunidad->save()){
				$solicitud = new crm_solicitudes();
				$solicitud->retrieve($oportunidad->crm_solicitud_id_c);
				foreach ($dato_soli as $key => $value) {
					$solicitud->$key = $value;
				}
				if ($solicitud->save()){
					return 1;
				}else{
					return 0;		
				}
			}else{
				return 0;		
			}
			
		} else { 
			return 0;
		}	
	}
// creado por armando 02/11/2015
	//utilizado para aprobar las solicitudes asignadas al usuario
	function set_aprueba_solicitud($session,$usuario_asig,$campos){
		global $db,$current_user;
		$timeDate = new TimeDate();
		$error = new SoapError();
		$i=0;
		 self::$helperObject = new SugarWebServiceUtilv4_1();
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session set_aprueba_solicitud', '', '', '',  $error))
		{
			$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
			return false;
		}
		//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
		
			$query_2=" select count(*) as tarea from tasks where status = 'Completed' and  id = '".$campos['id_tarea']."'";	
			$resul = $db->Query($query_2);
			$rechazada=$db->fetchByAssoc($resul);
			
			if ($rechazada['tarea'] > 0){
				return 'Lo Sentimos Ha Excedido el Tiempo Limite';
			}
			// si es aprobada la asignacion
			$tarea = new Task();
			$oportunidad = new Opportunity();
			if ($campos['respuesta'] == '1'){
				$tarea->retrieve($campos['id_tarea']);
				$tarea->status='Completed';
				$tarea->cierre_workflow_c= 'aceptado';
				$tarea->description=$row['description'].'/['.date('Y-m-d H:i:s').'] Solicitud aceptada por el usuario: '.$campos['user_name'];
				$tarea->save();
				
				$query_2=" 	SELECT distinct rto.tasks_opportunities_1opportunities_idb as oportunidad, ls.leads_opportunities_1leads_ida as id_contacto 
							FROM tasks_opportunities_1_c rto
							join  leads_opportunities_1_c ls on rto.tasks_opportunities_1opportunities_idb = ls.leads_opportunities_1opportunities_idb
							where tasks_opportunities_1tasks_ida = '".$campos['id_tarea']."'";	
					$respt_2 = $db->Query($query_2);
					
				while($value2 = $db->fetchByAssoc($respt_2)){
					$oportunidad->retrieve($value2['oportunidad']);
					$oportunidad->sales_stage='asignado';
					$oportunidad->estado_wf_c='asignado';
					$oportunidad->date_assigned_c = date('Y-m-d H:i:s');
					if($oportunidad->save()){
						$contacto =new Lead();
						$contacto->retrieve($value2['id_contacto']);
						
						$user= new User();
						$user->retrieve($usuario_asig);
						
						// plnatilla de email
						$template = new EmailTemplate();
						$template->retrieve_by_string_fields(array('name' => 'SW_3_ClienteAsignado' ));	
						// Parse Body HTML
						$template->body_html= str_replace('$lead_name',$contacto->full_name, $template->body_html);
						$template->body_html= str_replace('$contact_user_full_name',$user->full_name, $template->body_html);
						$template->body_html= str_replace('$contact_user_phone_work',$user->phone_work, $template->body_html);
						$template->body_html= str_replace('$contact_user_email1',$user->email1, $template->body_html);

						// enviamos el email
						$emailObj = new Email();
						$defaults = $emailObj->getSystemDefaultEmail();
						$mail = new SugarPHPMailer();
						$mail->setMailerForSystem();
						$mail->From = $defaults['email'];
						$mail->FromName = $user->full_name;
						$mail->ClearAllRecipients();
						$mail->ClearReplyTos();
						$mail->Subject=$template->subject;
						$mail->Body=$template->body_html;
						$mail->AltBody=$template->body_html;
						$mail->prepForOutbound();
						$emailTo=array($user->email1);
						foreach ($emailTo as &$value) {
							$mail->AddAddress($value);
						}
						// $mail->Send();
						if(@$mail->Send()){
							$email = new Email();
							$email->name = $template->subject;
							$email->type='out';
							$email->status='sent';
							$email->intent='pick';
							$email->from_addr = $defaults['email'];
							$email->to_addrs= $user->full_name."<".$user->email1.">";
							$email->description_html=$template->body_html;
							$email->assigned_user_id='1';
							$email->assigned_user_name='admin';
							$email->from_name=$current_user->full_name;
							$email->parent_name=$user->full_name;
							$email->date_sent=TimeDate::getInstance()->getNow(true)->modify("+0 days")->asDb();
							$email->parent_type='Opportunity';
							$email->parent_id=$oportunidad->id;
							$email->from_addr_name=$current_user->full_name;
							$email->save();
						}	
						
						$template_sup = new EmailTemplate();
							// plnatilla de email
						$template_sup->retrieve_by_string_fields(array('name' => 'SW_4_Correo_Supervisor' ));	
						// Parse Subject If we used variable in subject
						$template_sup->subject= str_replace('$contact_user_full_name', $user->full_name, $template_sup->subject);
						// Parse Body HTML
						//$GLOBALS['log']->fatal($super->full_name);
						$template_sup->body_html= str_replace('$nombre_supervisor', $super->full_name, $template_sup->body_html);
						$template_sup->body_html= str_replace('$contact_user_full_name', $user->full_name, $template_sup->body_html);
						$template_sup->body_html= str_replace('$contact_user_department', $user->department, $template_sup->body_html);
						$template_sup->body_html= str_replace('$cantidad',$i, $template_sup->body_html);
						
					}
					
					$i=$i+1;
				}
				$user= new User();
				$user->retrieve($usuario_asig);
				
				$super= new User();
				$super->retrieve($user->reports_to_id);
				
				// plnatilla de email
				$template = new EmailTemplate();
				$template->retrieve_by_string_fields(array('name' => 'SW_5_Correo_Consultor_Con_Aviso' ));	
				// Parse Subject If we used variable in subject
				$template->subject= str_replace('$contact_user_full_name',$user->full_name, $template->subject);
				// Parse Body HTML
				$template->body_html= str_replace('$contact_user_full_name',$user->full_name, $template->body_html);
				$template->body_html= str_replace('$cantidad',$i, $template->body_html);

				// enviamos el email
				$emailObj = new Email();
				$defaults = $emailObj->getSystemDefaultEmail();
				$mail = new SugarPHPMailer();
				$mail->setMailerForSystem();
				$mail->From = $defaults['email'];
				$mail->FromName = $user->full_name;
				$mail->ClearAllRecipients();
				$mail->ClearReplyTos();
				$mail->Subject=$template->subject;
				$mail->Body=$template->body_html;
				$mail->AltBody=$template->body_html;
				$mail->prepForOutbound();
				$emailTo=array($user->email1);
				foreach ($emailTo as &$value) {
					$mail->AddAddress($value);
				}
				//$mail->Send();
				
				if(@$mail->Send()){
							$email = new Email();
							$email->name = $template->subject;
							$email->type='out';
							$email->status='sent';
							$email->intent='pick';
							$email->from_addr = $defaults['email'];
							$email->to_addrs= $user->full_name."<".$user->email1.">";
							$email->description_html=$template->body_html;
							$email->assigned_user_id='1';
							$email->assigned_user_name='admin';
							$email->from_name=$current_user->full_name;
							$email->parent_name=$user->full_name;
							$email->date_sent=TimeDate::getInstance()->getNow(true)->modify("+0 days")->asDb();
							$email->parent_type='Task';
							$email->parent_id=$tarea->id;
							$email->from_addr_name=$current_user->full_name;
							$email->save();
				}	
				
				
				$template_sup = new EmailTemplate();
					// plnatilla de email
				$template_sup->retrieve_by_string_fields(array('name' => 'SW_4_Correo_Supervisor' ));	
				// Parse Subject If we used variable in subject
				$template_sup->subject= str_replace('$contact_user_full_name', $user->full_name, $template_sup->subject);
				// Parse Body HTML
				//$GLOBALS['log']->fatal($super->full_name);
				$template_sup->body_html= str_replace('$nombre_supervisor', $super->full_name, $template_sup->body_html);
				$template_sup->body_html= str_replace('$contact_user_full_name', $user->full_name, $template_sup->body_html);
				$template_sup->body_html= str_replace('$contact_user_department', $user->department, $template_sup->body_html);
				$template_sup->body_html= str_replace('$cantidad',$i, $template_sup->body_html);

				//enviamos el email
				$emailObj = new Email();
				$defaults = $emailObj->getSystemDefaultEmail();
				$mail = new SugarPHPMailer();
				$mail->setMailerForSystem();
				$mail->From = $defaults['email'];
				$mail->FromName = $super->full_name;
				$mail->ClearAllRecipients();
				$mail->ClearReplyTos();
				$mail->Subject=$template_sup->subject;
				$mail->Body=$template_sup->body_html;
				$mail->AltBody=$template_sup->body_html;
				$mail->prepForOutbound();
				$emailTo=array($super->email1);
				foreach ($emailTo as &$value) {
					$mail->AddAddress($value);
				}
			/*	if(@$mail->Send()){
							$email = new Email();
							$email->name = $template->subject;
							$email->type='out';
							$email->status='sent';
							$email->intent='pick';
							$email->from_addr = $defaults['email'];
							$email->to_addrs= $super->full_name."<".$super->email1.">";
							$email->description_html=$template->body_html;
							$email->assigned_user_id='1';
							$email->assigned_user_name='admin';
							$email->from_name=$current_user->full_name;
							$email->parent_name=$super->full_name;
							$email->date_sent=TimeDate::getInstance()->getNow(true)->modify("+0 days")->asDb();
							$email->parent_type='Task';
							$email->parent_id=$tarea->id;
							$email->from_addr_name=$current_user->full_name;
							$email->save();
				}	*/
				return 1;
			}else{
				$tarea->retrieve($campos['id_tarea']);
				$tarea->cierre_workflow_c= 'rechazado';
				$tarea->status='Completed';
				$tarea->description=$row['description'].'/['.date('Y-m-d H:i:s').'] Solicitud rechazada por el usuario: '.$campos['user_name'];
				$tarea->save();
				
				$query_2="SELECT tasks_opportunities_1opportunities_idb as oportunidad
							FROM tasks_opportunities_1_c 
							where tasks_opportunities_1tasks_ida = '".$campos['id_tarea']."'";	
				$respt_2 = $db->Query($query_2);
				while($value2 = $db->fetchByAssoc($respt_2)){
					$oportunidad->retrieve($value2['oportunidad']);
					$oportunidad->assigned_user_id = '1';
					$oportunidad->sales_stage = 'recepcionado';
					$oportunidad->priority_c= 'High';
					$oportunidad->estado_wf_c = null;
					$oportunidad->save();
				}
				return 1;
			}
				
		return 0;
	}
	// creado por armando 02/11/2015
	//cuenta la solicitudes asignadas recientemente al usuario
	function get_contar_solicitud($session,$usuario_asig){
		global $db,$current_user;
		
		$error = new SoapError();
		 self::$helperObject = new SugarWebServiceUtilv4_1();
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session get_contar_solicitud', '', '', '',  $error))
		{
			$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
			return false;
		}
				//evento utilizado para detectar la actividad del usuario
				//	$this->actividad_user($usuario_asig);
				//------------------------------------------------------
				$query_2=" 	SELECT  t.id,count(o.id) as cantidad
							FROM opportunities o
							JOIN tasks_opportunities_1_c rt ON rt.tasks_opportunities_1opportunities_idb = o.id and rt.deleted = 0 
							JOIN tasks t ON t.id = rt.tasks_opportunities_1tasks_ida AND t.status = 'Not Started'
							JOIN tasks_cstm td ON t.id = td.id_c AND td.tipo_tarea_c = 'WORKFLOW'
							WHERE o.assigned_user_id = '".$usuario_asig."'
							group by t.id";	
					$respt_2 = $db->Query($query_2);
					while($value = $db->fetchByAssoc($respt_2)){
						$obj['id_tarea']=$value['id'];
						$obj['cantidad']=$value['cantidad'];
						$objeto[]=$obj;
					}
					return $objeto;
					//return $query_2;
	}	
	//// creado por armando 02/11/2015
	// se usa para refrescar una sola fila de la tabla y no refrescar toda la tabla
	function busca_oportunidad_web ($session,$usuario_asig,$id_oportunidad){
					global $db, $sugar_config;
					$error = new SoapError();
					 self::$helperObject = new SugarWebServiceUtilv4_1();
					if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session busca_oportunidad_web', '', '', '',  $error))
					{
						$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
						return false;
					}
					//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
					 $query="select distinct 	o.id AS id_oportunidad,
												o.name AS nombre_oportunidad,
												o.lead_source AS origen_solcitud,
												o.sales_stage AS estado_solcitud,
												o.amount AS monto_oportunidad,
												o.description AS description,
												coc.adultos AS pasajeros_adultos_c,
												coc.ninos AS pasajeros_ninos_c,
												coc.fecha_viaje AS fecha_viaje_c,
												coc.hotel_habitacion AS habitaciones_c,
												c.date_assigned_c AS fecha_asignacion_c,
												concat(co.first_name,' ',co.last_name) AS nombre_comprador,
												co.phone_home AS phone_home,co.phone_fax AS rut_comprador,
												co.id AS codigo_lead,
												o.assigned_user_id AS usuario_asignado 
												from opportunities o 
												join opportunities_cstm c on c.id_c = o.id
												join leads_opportunities_1_c r on r.leads_opportunities_1opportunities_idb = c.id_c
												join leads co on co.id = r.leads_opportunities_1leads_ida
												join crm_solicitudes coc on coc.id = c.crm_solicitud_id_c 
												where o.deleted = 0 and 
												r.deleted = 0 and 
												co.deleted = 0 and
												co.deleted = 0 and 
												o.assigned_user_id ='".$usuario_asig."' and 
												o.id = '".$id_oportunidad."'";	
							 
					$respt = $db->Query($query);
					
					while ($fila = $db->fetchByAssoc($respt)) {
						
						$obj ['nombre_oportunidad']   =$fila['nombre_oportunidad'];
						$obj ['id_oportunidad']       =$fila['id_oportunidad'];
						$obj ['fecha_viaje_c']        =$fila['fecha_viaje_c'];
						$obj ['fecha_asignacion_c']   =$fila['fecha_asignacion_c'];
						$obj ['nombre_comprador']     =ucwords($fila['nombre_comprador']);
						$obj ['rut_c']                =$fila['rut_comprador'];
						$obj ['id_cli'] 		 		=$fila['usuario_asignado'];
						$obj ['description'] 		 		=$fila['description'];
						$obj ['estado_solcitud']= $fila['estado_solcitud'];
						
						if (!empty($fila['phone_home'])){
								$obj ['phone_office']         =$fila['phone_home'];
							}else{
								$obj ['phone_office']= 'Sin Telefono';
							}
								$contacto =new Lead();
								$contacto->retrieve($fila['codigo_lead']);
								if(!empty($contacto->email1)){
									$email=$contacto->email1;
								}else{
									$email = 'Sin Email';
								}
								$obj ['email_address']=$email;
								$obj ['c_envio_email'] = 0 ;
						$objeto []= $obj;
					}
					if (empty($objeto)){
						return '';
					}else{
						return $objeto;
					}
					
				}
	
	// creado por armando 02/11/2015				
				/* etse proceso se encarga de cambiar el password del usuario*/
	function set_estado_usuario ($session,$usuario_asig,$campos){
		global $db, $sugar_config;
		$error = new SoapError();
				//authenticate
				if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session set_estado_usuario', '', '', '',  $error))
				{
					$GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->example_method.');
					return false;
				}
			
				//evento utilizado para detectar la actividad del usuario
					$this->actividad_user($usuario_asig);
				//------------------------------------------------------
			
		if ($campos['update']=="true"){
			//para actualizar update:1,estado:1 o 0
				$query=" update users_cstm 
					set disponible_sw_c = ".$campos['estado']." 
					where id_c = '".$campos['usuario_asig']."'";
				if($db->Query($query)){
					return (int)$campos['estado'];
				} else {
					return 'error';
				}
		}else{
			//cuando se inicie session update:0,estado:0
				$query=" select disponible_sw_c 
				         from users_cstm  
					where id_c = '".$campos['usuario_asig']."'";
					$resul = $db->Query($query);
					$estado_new=$db->fetchByAssoc($resul);
					return (int)$estado_new['disponible_sw_c'];
		}
			

	}


	//-----------------------------------------------------------------------------------------------------------------------------------
	
	
    /**
     * Retrieve a collection of beans that are related to the specified bean and optionally return relationship data for those related beans.
     * So in this API you can get contacts info for an account and also return all those contact's email address or an opportunity info also.
     *
     * @param String $session -- Session ID returned by a previous call to login.
     * @param String $module_name -- The name of the module that the primary record is from.  This name should be the name the module was developed under (changing a tab name is studio does not affect the name that should be passed into this method)..
     * @param String $module_id -- The ID of the bean in the specified module
     * @param String $link_field_name -- The name of the lnk field to return records from.  This name should be the name the relationship.
     * @param String $related_module_query -- A portion of the where clause of the SQL statement to find the related items.  The SQL query will already be filtered to only include the beans that are related to the specified bean.
     * @param Array $related_fields - Array of related bean fields to be returned.
     * @param Array $related_module_link_name_to_fields_array - For every related bean returrned, specify link fields name to fields info for that bean to be returned. For ex.'link_name_to_fields_array' => array(array('name' =>  'email_addresses', 'value' => array('id', 'email_address', 'opt_out', 'primary_address'))).
     * @param Number $deleted -- false if deleted records should not be include, true if deleted records should be included.
     * @param String $order_by -- field to order the result sets by
     * @param Number $offset -- where to start in the return
     * @param Number $limit -- number of results to return (defaults to all)
     * @return Array 'entry_list' -- Array - The records that were retrieved
     *               'relationship_list' -- Array - The records link field data. The example is if asked about accounts contacts email address then return data would look like Array ( [0] => Array ( [name] => email_addresses [records] => Array ( [0] => Array ( [0] => Array ( [name] => id [value] => 3fb16797-8d90-0a94-ac12-490b63a6be67 ) [1] => Array ( [name] => email_address [value] => hr.kid.qa@example.com ) [2] => Array ( [name] => opt_out [value] => 0 ) [3] => Array ( [name] => primary_address [value] => 1 ) ) [1] => Array ( [0] => Array ( [name] => id [value] => 403f8da1-214b-6a88-9cef-490b63d43566 ) [1] => Array ( [name] => email_address [value] => kid.hr@example.name ) [2] => Array ( [name] => opt_out [value] => 0 ) [3] => Array ( [name] => primary_address [value] => 0 ) ) ) ) )
     * @exception 'SoapFault' -- The SOAP error, if any
     */
    function get_relationships($session, $module_name, $module_id, $link_field_name, $related_module_query, $related_fields, $related_module_link_name_to_fields_array, $deleted, $order_by = '', $offset = 0, $limit = false)
    {
        $GLOBALS['log']->info('Begin: SugarWebServiceImpl->get_relationships');
        self::$helperObject = new SugarWebServiceUtilv4_1();
        global  $beanList, $beanFiles;
    	$error = new SoapError();

    	if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', $module_name, 'read', 'no_access', $error)) {
    		$GLOBALS['log']->info('End: SugarWebServiceImpl->get_relationships');
    		return;
    	} // if

    	$mod = BeanFactory::getBean($module_name, $module_id);

        if (!self::$helperObject->checkQuery($error, $related_module_query, $order_by)) {
    		$GLOBALS['log']->info('End: SugarWebServiceImpl->get_relationships');
        	return;
        } // if

        if (!self::$helperObject->checkACLAccess($mod, 'DetailView', $error, 'no_access')) {
    		$GLOBALS['log']->info('End: SugarWebServiceImpl->get_relationships');
        	return;
        } // if

        $output_list = array();
    	$linkoutput_list = array();

    	// get all the related modules data.
        $result = self::$helperObject->getRelationshipResults($mod, $link_field_name, $related_fields, $related_module_query, $order_by, $offset, $limit);

        if (self::$helperObject->isLogLevelDebug()) {
    		$GLOBALS['log']->debug('SoapHelperWebServices->get_relationships - return data for getRelationshipResults is ' . var_export($result, true));
        } // if
    	if ($result) {

    		$list = $result['rows'];
    		$filterFields = $result['fields_set_on_rows'];

    		if (sizeof($list) > 0) {
    			// get the related module name and instantiate a bean for that
    			$submodulename = $mod->$link_field_name->getRelatedModuleName();
                $submoduletemp = BeanFactory::getBean($submodulename);

    			foreach($list as $row) {
    				$submoduleobject = @clone($submoduletemp);
    				// set all the database data to this object
    				foreach ($filterFields as $field) {
    					$submoduleobject->$field = $row[$field];
    				} // foreach
    				if (isset($row['id'])) {
    					$submoduleobject->id = $row['id'];
    				}
    				$output_list[] = self::$helperObject->get_return_value_for_fields($submoduleobject, $submodulename, $filterFields);
    				if (!empty($related_module_link_name_to_fields_array)) {
    					$linkoutput_list[] = self::$helperObject->get_return_value_for_link_fields($submoduleobject, $submodulename, $related_module_link_name_to_fields_array);
    				} // if

    			} // foreach
    		}

    	} // if

    	$GLOBALS['log']->info('End: SugarWebServiceImpl->get_relationships');
    	return array('entry_list'=>$output_list, 'relationship_list' => $linkoutput_list);
    }


    /**
     * get_modified_relationships
     *
     * Get a list of the relationship records that have a date_modified value set within a specified date range.  This is used to
     * help facilitate sync operations.  The module_name should be "Users" and the related_module one of "Meetings", "Calls" and
     * "Contacts".
     *
     * @param xsd:string $session String of the session id
     * @param xsd:string $module_name String value of the primary module to retrieve relationship against
     * @param xsd:string $related_module String value of the related module to retrieve records off of
     * @param xsd:string $from_date String value in YYYY-MM-DD HH:MM:SS format of date_start range (required)
     * @param xsd:string $to_date String value in YYYY-MM-DD HH:MM:SS format of ending date_start range (required)
     * @param xsd:int $offset Integer value of the offset to begin returning records from
     * @param xsd:int $max_results Integer value of the max_results to return; -99 for unlimited
     * @param xsd:int $deleted Integer value indicating deleted column value search (defaults to 0).  Set to 1 to find deleted records
     * @param xsd:string $module_user_id String value of the user id (optional, but defaults to SOAP session user id anyway)  The module_user_id value
     * here ought to be the user id of the user initiating the SOAP session
     * @param tns:select_fields $select_fields Array value of fields to select and return as name/value pairs
     * @param xsd:string $relationship_name String value of the relationship name to search on
     * @param xsd:string $deletion_date String value in YYYY-MM-DD HH:MM:SS format for filtering on deleted records whose date_modified falls within range
     * this allows deleted records to be returned as well
     *
     * @return Array records that match search criteria
     */
    function get_modified_relationships($session, $module_name, $related_module, $from_date, $to_date, $offset, $max_results, $deleted=0, $module_user_id = '', $select_fields = array(), $relationship_name = '', $deletion_date = ''){
        global  $beanList, $beanFiles, $current_user;
        $error = new SoapError();
        $output_list = array();

        if(empty($from_date))
        {
            $error->set_error('invalid_call_error, missing from_date');
            return array('result_count'=>0, 'next_offset'=>0, 'field_list'=>$select_fields, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
        }

        if(empty($to_date))
        {
            $error->set_error('invalid_call_error, missing to_date');
            return array('result_count'=>0, 'next_offset'=>0, 'field_list'=>$select_fields, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
        }

        self::$helperObject = new SugarWebServiceUtilv4_1();
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', $module_name, 'read', 'no_access', $error))
        {
       		$GLOBALS['log']->info('End: SugarWebServiceImpl->get_modified_relationships');
       		return;
       	} // if

        if(empty($beanList[$module_name]) || empty($beanList[$related_module]))
        {
            $error->set_error('no_module');
            return array('result_count'=>0, 'next_offset'=>0, 'field_list'=>$select_fields, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
        }

        global $current_user;
        if(!self::$helperObject->check_modules_access($current_user, $module_name, 'read') || !self::$helperObject->check_modules_access($current_user, $related_module, 'read')){
            $error->set_error('no_access');
            return array('result_count'=>0, 'next_offset'=>0, 'field_list'=>$select_fields, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
        }

        if($max_results > 0 || $max_results == '-99'){
            global $sugar_config;
            $sugar_config['list_max_entries_per_page'] = $max_results;
        }

        // Cast to integer
        $deleted = (int)$deleted;
        $query = "(m1.date_modified > " . db_convert("'".$GLOBALS['db']->quote($from_date)."'", 'datetime'). " AND m1.date_modified <= ". db_convert("'".$GLOBALS['db']->quote($to_date)."'", 'datetime')." AND {0}.deleted = $deleted)";
        if(isset($deletion_date) && !empty($deletion_date)){
            $query .= " OR ({0}.date_modified > " . db_convert("'".$GLOBALS['db']->quote($deletion_date)."'", 'datetime'). " AND {0}.date_modified <= ". db_convert("'".$GLOBALS['db']->quote($to_date)."'", 'datetime')." AND {0}.deleted = 1)";
        }

        if(!empty($current_user->id))
        {
            $query .= " AND m2.id = '".$GLOBALS['db']->quote($current_user->id)."'";
        }

        //if($related_module == 'Meetings' || $related_module == 'Calls' || $related_module = 'Contacts'){
        $query = string_format($query, array('m1'));
        //}

        require_once('soap/SoapRelationshipHelper.php');
        $results = retrieve_modified_relationships($module_name, $related_module, $query, $deleted, $offset, $max_results, $select_fields, $relationship_name);

        $list = $results['result'];

        foreach($list as $value)
        {
             $output_list[] = self::$helperObject->array_get_return_value($value, $results['table_name']);
        }

        $next_offset = $offset + count($output_list);

        return array(
            'result_count'=> count($output_list),
            'next_offset' => $next_offset,
            'entry_list' => $output_list,
            'error' => $error->get_soap_array()
        );
    }

}
