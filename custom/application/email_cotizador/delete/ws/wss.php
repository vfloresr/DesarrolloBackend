<?php
// require_once('../config/funciones.php');
require_once('../config/configuraciones.php');

$funcion = $_REQUEST['func'];

// ejecutamos la función solicitada

return $funcion(); // retornamos el valor de la funcion 

function get_acceso_api()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();
	$parametros = array(
    	'user' => $_REQUEST['user'],
	);
	$result['resultado'] = $configuraciones->call("get_acceso_api", $parametros, $url);

	echo json_encode($result);
}


function get_cantidad_inicio_nuevos()
{
	$configuraciones = new Configuraciones;
	
	$url = $configuraciones->getUrlPrototipo();

	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'contacto_id' => $_SESSION['user_id']
	);
	
	$result['prototipo'] = $configuraciones->call("get_cantidad_inicio_nuevos", $parametros, $url);


	echo json_encode($result);
}

function set_carga_solicitud()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();
	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'usuario' => $_SESSION['user_id']
	);
	$result = $configuraciones->call("set_carga_solicitud", $parametros, $url);

	echo $result;
}


function get_cantidad_web_nuevas()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();
	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'contacto_id' => $_SESSION['user_id']
	);
	$result = $configuraciones->call("get_cantidad_web_nuevas", $parametros, $url);

	echo $result;
}

function get_cantidad_cumple_mes()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();

	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'contacto_id' => $_SESSION['user_id']
	);
	$result = $configuraciones->call("get_cantidad_cumple_mes", $parametros, $url);

	echo json_encode($result);
}

function get_solicitudes_web_nuevas()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();
	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'contacto_id' => $_SESSION['user_id']
	);
	$result = $configuraciones->call("get_solicitudes_web_nuevas", $parametros, $url);

	echo json_encode($result);
}

function get_solicitudes_web_pendientes()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();
	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'contacto_id' => $_SESSION['user_id']
	);
	$result = $configuraciones->call("get_solicitudes_web_pendientes", $parametros, $url);

	echo json_encode($result);
}

function get_solicitudes_web_cerradas()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();
	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'contacto_id' => $_SESSION['user_id']
	);
	$result = $configuraciones->call("get_solicitudes_web_cerradas", $parametros, $url);

	echo json_encode($result);
}

function get_cantidad_crossseling_nuevos()
{
	
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();

	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'contacto_id' => $_SESSION['user_id']
	);
	$result = $configuraciones->call("get_cantidad_crossseling_nuevos", $parametros, $url);

	echo json_encode($result);

}

function get_obtiene_crossseling_abiertos()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();

	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'contacto_id' => $_SESSION['user_id']
	);
	$result = $configuraciones->call("get_obtiene_crossseling_abiertos", $parametros, $url);

	echo json_encode($result);

}

function get_obtiene_crosselling_cerradas()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();

	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'contacto_id' => $_SESSION['user_id']
	);
	$result = $configuraciones->call("get_obtiene_crosselling_cerradas", $parametros, $url);

	echo json_encode($result);
}

function get_cantidad_recompra_nuevos()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();

	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'contacto_id' => $_SESSION['user_id']
	);
	$result = $configuraciones->call("get_cantidad_recompra_nuevos", $parametros, $url);

	echo json_encode($result);
}

function get_recompra_nuevos()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();

	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'contacto_id' => $_SESSION['user_id']
	);
	$result = $configuraciones->call("get_recompra_nuevos", $parametros, $url);

	echo json_encode($result);
}

function get_recompra_pendientes()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();

	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'contacto_id' => $_SESSION['user_id']
	);
	$result = $configuraciones->call("get_recompra_pendientes", $parametros, $url);

	echo json_encode($result);
}

function get_recompra_cerrados()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();

	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'contacto_id' => $_SESSION['user_id']
	);
	$result = $configuraciones->call("get_recompra_cerrados", $parametros, $url);

	echo json_encode($result);
}

function get_cantidad_fugados_nuevos()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();

	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'contacto_id' => $_SESSION['user_id']
	);
	$result = $configuraciones->call("get_cantidad_fugados_nuevos", $parametros, $url);

	echo json_encode($result);
}

function get_fugados_nuevos()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();

	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'contacto_id' => $_SESSION['user_id']
	);
	$result = $configuraciones->call("get_fugados_nuevos", $parametros, $url);

	echo json_encode($result);
}

function get_fugados_pendientes()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();

	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'contacto_id' => $_SESSION['user_id']
	);
	$result = $configuraciones->call("get_fugados_pendientes", $parametros, $url);

	echo json_encode($result);
}

function get_fugados_cerrados()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();

	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'contacto_id' => $_SESSION['user_id']
	);
	$result = $configuraciones->call("get_fugados_cerrados", $parametros, $url);

	echo json_encode($result);
}

function get_cantidad_proximos_viajes_nuevos()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();

	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'contacto_id' => $_SESSION['user_id']
	);
	$result = $configuraciones->call("get_cantidad_proximos_viajes_nuevos", $parametros, $url);

	echo json_encode($result);
}

function get_proximos_viajes_nuevos()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();

	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'contacto_id' => $_SESSION['user_id']
	);
	$result = $configuraciones->call("get_proximos_viajes_nuevos", $parametros, $url);

	echo json_encode($result);
}

function get_proximos_viajes_pendientes()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();

	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'contacto_id' => $_SESSION['user_id']
	);
	$result = $configuraciones->call("get_proximos_viajes_pendientes", $parametros, $url);

	echo json_encode($result);
}

function get_proximos_viajes_cerrados()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();

	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'contacto_id' => $_SESSION['user_id']
	);
	$result = $configuraciones->call("get_proximos_viajes_cerrados", $parametros, $url);

	echo json_encode($result);
}

function get_cantidad_retorno_nuevos()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();

	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'contacto_id' => $_SESSION['user_id']
	);
	$result = $configuraciones->call("get_cantidad_retorno_nuevos", $parametros, $url);

	echo json_encode($result);
}

function get_retorno_nuevos()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();

	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'contacto_id' => $_SESSION['user_id']
	);
	$result = $configuraciones->call("get_retorno_nuevos", $parametros, $url);

	echo json_encode($result);
}

function get_retorno_pendientes()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();

	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'contacto_id' => $_SESSION['user_id']
	);
	$result = $configuraciones->call("get_retorno_pendientes", $parametros, $url);

	echo json_encode($result);
}

function get_retorno_cerrados()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();

	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'contacto_id' => $_SESSION['user_id']
	);
	$result = $configuraciones->call("get_retorno_cerrados", $parametros, $url);

	echo json_encode($result);
}

function set_editar_contactos()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();

	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'contacto_id' => $_REQUEST['user_id'],
    	'campos' => array(
			'phone_home' =>$_REQUEST['phone_home'],/*aqui enviamos el telefono nuevo*/
			'email1'=>$_REQUEST['email1']/*aqui enviamos el email nuevo*/
		    ),
	);
	$result = $configuraciones->call("set_editar_contactos", $parametros, $url);

	echo json_encode($result);
}

function set_editar_contactos_web()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();
	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'contacto_id' => $_REQUEST['contacto_id'],
    	'campos' => array(
			'first_name' =>$_REQUEST['first_name'],/*aqui enviamos el telefono nuevo*/
			'last_name' =>$_REQUEST['last_name'],/*aqui enviamos el telefono nuevo*/
			'phone_fax'=>$_REQUEST['rut_c'],/*aqui enviamos el email nuevo*/
			'email1' =>$_REQUEST['email1'],/*aqui enviamos el telefono nuevo*/
			'phone_home'=>$_REQUEST['phone_home'],/*aqui enviamos el email nuevo*/
		),
	);
	$result = $configuraciones->call("set_editar_contactos_web", $parametros, $url);

	echo json_encode($result);
}

function set_editar_contactos_cartera()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();
	if(isset($_REQUEST['birthdate']) && $_REQUEST['birthdate'] != "")
	{
		$date = new DateTime($_REQUEST['birthdate']);
		$birthdate = $date->format('Y-m-d');
	}
	else
	{
		$birthdate = $_REQUEST['birthdate'];
	}
	
	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'contacto_id' => $_REQUEST['user_id'],
    	'campos' => array(
			'email1'=>$_REQUEST['email1'],/*aqui enviamos el email nuevo*/
			'birthdate' => $birthdate
		),
	);
	$result = $configuraciones->call("set_editar_contactos", $parametros, $url);

	echo json_encode($result);
}

function set_editar_contactos_cumple()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();

	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'contacto_id' => $_REQUEST['user_id'],
    	'campos' => array(
			'last_name' =>$_REQUEST['last_name'],
			'phone_home' =>$_REQUEST['phone_home'],/*aqui enviamos el telefono nuevo*/
			'email1'=>$_REQUEST['email1'],/*aqui enviamos el email nuevo*/
			'description' =>$_REQUEST['description'],
			'birthdate' =>$_REQUEST['birthdate']
		    ),
	);
	$result = $configuraciones->call("set_editar_contactos", $parametros, $url);

	echo json_encode($result);
}

function set_cambia_password()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();

	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'contacto_id' => $_SESSION['user_id'],
    	'password' => $_REQUEST['password']
	);
	$result = $configuraciones->call("set_cambia_password", $parametros, $url);

	echo json_encode($result);
}

function set_notificacion_acceso()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();

	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'contacto_id' => $_SESSION['user_id'],
    	'password' => $_REQUEST['password']
	);
	$result = $configuraciones->call("notificacion_acceso", $parametros, $url);

	echo json_encode($result);
}

function set_editar_oportunidad()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();

	$parametros = array(
			'session' => $_SESSION['session_id'],
			'oportunidad_id' => $_REQUEST['oportunidad_id'],
			'campos' => array(
					'numero_negocio_c' => $_REQUEST['numero_negocio_c'],
					'sales_stage' => $_REQUEST['sales_stage'],
					'description' => $_REQUEST['description'],
					'amount' => $_REQUEST['amount'],
					'productos_c' => $_REQUEST['productos_c'],
					'motivo_cierre_c' => $_REQUEST['motivo_cierre_c']),
					);
	$result = $configuraciones->call("set_editar_oportunidad", $parametros, $url);

	echo json_encode($result);
}

function get_cumple_mes()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();


	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'contacto_id' => $_SESSION['user_id']
	);
	$result = $configuraciones->call("get_cumple_mes", $parametros, $url);

	echo json_encode($result);
}

function get_buscar_contacto()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();


	$parametros = array(
    	'session' => $_SESSION['session_id'],
		'user_id' => $_SESSION['user_id'],
		'tipo' => $_REQUEST['tipo']
		//'parametros_paginacion' => $_REQUEST
	);
	$result = $configuraciones->call("get_buscar_contacto", $parametros, $url);

	echo json_encode($result);
}

function get_user_detalle()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();

	$parametros = array(
    	'session' => $_SESSION['session_id'],
		'user_id' => $_SESSION['user_id']
	);
	$result = $configuraciones->call("get_user_detalle", $parametros, $url);

	echo json_encode($result);
}

function set_editar_tarea()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();
	
	if(isset($_REQUEST['comentarios_anteriores']))
		$description = $_REQUEST['comentarios_anteriores']. ' *|*|* ' . $_SESSION['user_name'].' - '.date('d-m-Y H:i:s').' - '.$_REQUEST['description'];
	else
		$description = $_REQUEST['description'];

	$parametros = array(
       'session' => $_SESSION['session_id'],
       'tarea_id' => $_REQUEST['tarea_id'],
       'campos' => array(
			'status' => $_REQUEST['status'],
			'description' => $description,
		),
	);
	
	$result = $configuraciones->call("set_editar_tarea", $parametros, $url);

	echo json_encode($result);
}

function set_editar_tarea_voucher()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();


	
	$parametros = array(
       		'session' => $_SESSION['session_id'],
       		'tarea_id' => $_REQUEST['pk'],
       		'campos' => array(
					'voucher_c' => $_REQUEST['value'],
    			    ),
	);
	
	$result = $configuraciones->call("set_editar_tarea", $parametros, $url);

	echo json_encode($result);
}

function set_editar_tarea_documentacion()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();
	
	$parametros = array(
       'session' => $_SESSION['session_id'],
       'tarea_id' => $_REQUEST['pk'],
       'campos' => array(
					'documentacion_c' => $_REQUEST['value'],
    			),
	);
	
	$result = $configuraciones->call("set_editar_tarea", $parametros, $url);

	echo json_encode($result);
}

function get_oportunidad_detalle_web()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();
	
		
	$parametros = array(
		'session' => $_REQUEST['session_id'],
		'oportunidad_id' => $_REQUEST['oportunidad_id'],
	);

	$result = $configuraciones->call("get_oportunidad_detalle_web", $parametros, $url);

	echo json_encode($result);
}

function get_oportunidad_detalle_producto_web()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();
	
	$parametros = array(
		'session' => $_SESSION['session_id'],
		'oportunidad_id' => $_REQUEST['oportunidad_id'],
	);
	
	$result = $configuraciones->call("get_oportunidad_detalle_producto_web", $parametros, $url);

	echo json_encode($result);
}

function set_editar_tarea_description()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();

	$parametros = array(
		'session' => $_SESSION['session_id'],
		'tarea_id' => $_REQUEST['pk'],
		'campos' => array(
				'description' => $_REQUEST['value'],
		),
	);
	
	$result = $configuraciones->call("set_editar_tarea", $parametros, $url);

	echo json_encode($array_comentarios);
}

function get_negocio()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();

	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'contacto_id' => $_SESSION['user_id'],
    	'negocio_id' => $_REQUEST['negocio_id']
	);
	$result = $configuraciones->call("get_negocio", $parametros, $url);

	echo json_encode($result);
}

function get_negocio_detalle()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();

	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'negocio_id' => $_REQUEST['negocio_id'],
	);
	$result = $configuraciones->call("get_negocio_detalle", $parametros, $url);

	echo json_encode($result);
}

function get_detalle_pasajeros()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();

	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'negocio_id' => $_REQUEST['negocio_id'],
		'id_trx'	=> $_REQUEST['id_trx'],
	);
	$result = $configuraciones->call("get_detalle_pasajeros", $parametros, $url);

	echo json_encode($result);
}

function set_enviar_email()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();
	
	$correo = (isset($_REQUEST['email'])) ? $_REQUEST['email']  :  '';
	$asunto = (isset($_REQUEST['asunto'])) ? $_REQUEST['asunto']  :  '';
	$texto = (isset($_REQUEST['texto_hide'])) ? $_REQUEST['texto_hide']  :  '';
	
	$contacto_id = (isset($_REQUEST['contacto_id'])) ? $_REQUEST['contacto_id']  :  '';
	$email_cc = (isset($_REQUEST['email_cc'])) ? $_REQUEST['email_cc']  :  '';
	$ruta_upload = (isset($_REQUEST['t_accion'])) ? $_REQUEST['t_accion']  :  '';
	$procedencia = (isset($_REQUEST['procedencia'])) ? $_REQUEST['procedencia']  :  '';
	$procedencia_id = (isset($_REQUEST['procedencia_id'])) ? $_REQUEST['procedencia_id']  :  '';
	
	$t_accion = $configuraciones->getPathProyecto()."/upload/";
	
	if(isset($_FILES['name_file']))
		$name_file_array = $_FILES['name_file'];
	else
		$name_file_array = array();
		
	for($i=0;$i<count($_FILES['name_file']['name']);$i++)
	{
	  if($_FILES['name_file']['name'][$i] != '')
	  {
		  $name_file = str_replace(" ", "_", date('His-').basename($_FILES['name_file']['name'][$i]));
		  $tmp_path = $_FILES['name_file']['tmp_name'][$i];

		  $name_file_array[] = $name_file; 

		  $path_of_uploaded_file = "../upload/".$name_file;
		  if(is_uploaded_file($tmp_path))
		  {
			copy($tmp_path,$path_of_uploaded_file);
		  }
		}
	}
	
	$parametros = array(
		'session' => $_SESSION['session_id'],
		'email' => $correo,
		'asunto' => $asunto,
		'texto' => $texto,
		'email_cc' => $email_cc,
		'name_file_array' => $name_file_array,
		'contacto_id' => $contacto_id,
		't_accion' => $t_accion,
		'from' => $_SESSION['email'],
		'nombre_user' => $_SESSION['nombre'].' '.$_SESSION['apellido'],
		'procedencia' => $procedencia,
		'procedencia_id' => $procedencia_id,
	);
	
	$result = $configuraciones->call("envia_email", $parametros, $url);

	echo json_encode($result);
}

function set_enviar_email_web()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();
	
	$correo = (isset($_REQUEST['email'])) ? $_REQUEST['email']  :  '';
	$asunto = (isset($_REQUEST['asunto'])) ? $_REQUEST['asunto']  :  '';
	$texto = (isset($_REQUEST['texto_hide'])) ? $_REQUEST['texto_hide']  :  '';

	$contacto_id = (isset($_REQUEST['contacto_id'])) ? $_REQUEST['contacto_id']  :  '';
	$email_cc = (isset($_REQUEST['email_cc'])) ? $_REQUEST['email_cc']  :  '';
	$ruta_upload = (isset($_REQUEST['t_accion'])) ? $_REQUEST['t_accion']  :  '';
	$procedencia = (isset($_REQUEST['procedencia'])) ? $_REQUEST['procedencia']  :  '';
	$procedencia_id = (isset($_REQUEST['procedencia_id'])) ? $_REQUEST['procedencia_id']  :  '';
	$pdf = (isset($_REQUEST['pdf'])) ? $_REQUEST['pdf']  :  '';
	$estado = (isset($_REQUEST['estado'])) ? $_REQUEST['estado']  :  '';
	
	$t_accion = $configuraciones->getPathProyecto()."/upload/";
	
	if(isset($_FILES['name_file']))
		$name_file_array = $_FILES['name_file'];
	else
		$name_file_array = array();
	
	for($i=0;$i<count($_FILES['name_file']['name']);$i++)
	{
	  if($_FILES['name_file']['name'][$i] != '')
	  {
		  $name_file = str_replace(" ", "_", date('His-').basename($_FILES['name_file']['name'][$i]));
		  $tmp_path = $_FILES['name_file']['tmp_name'][$i];

		  $name_file_array[] = $name_file; 

		  $path_of_uploaded_file = "../upload/".$name_file;
		  if(is_uploaded_file($tmp_path))
		  {
			copy($tmp_path,$path_of_uploaded_file);
		  }
		}
	}

	$parametros = array(
		'session' => $_SESSION['session_id'],
		'email' => $correo,
		'asunto' => $asunto,
		'texto' => $texto,
		'email_cc' => $email_cc,
		'name_file' => $name_file_array ,
		'contacto_id' => $contacto_id,
		't_accion' => $t_accion,
		'from' => $_SESSION['email'],
		'nombre_user' => $_SESSION['nombre'].' '.$_SESSION['apellido'],
		'procedencia' => $procedencia,
		'procedencia_id' => $procedencia_id,
		'pdf' => $pdf,
		'estado'=>$estado
	);
	
	$result = $configuraciones->call("envia_email_solicitud", $parametros, $url);

	echo json_encode($result);
}

function set_editar_oportunidad_web()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();
	$parametros = array(
		'session' => $_SESSION['session_id'],
		'oportunidad_id' => $_REQUEST['oportunidad_id'],
		'campos_opor' => array(
			'numero_negocio_c' => $_REQUEST['numero_negocio_c'],
			'amount' => 		  $_REQUEST['amount'],
			'motivo_cierre_c' =>  $_REQUEST['motivo_c'],
		    'sales_stage'     =>  (isset($_REQUEST['sale_stage'])) ? $_REQUEST['sale_stage']:  '',
			'tipo_hotel_c'    =>  (isset($_REQUEST['tipo_hotel_c'])) ? $_REQUEST['tipo_hotel_c']  :  '',
			'destino_c'       =>  (isset($_REQUEST['destino_c'])) ? $_REQUEST['destino_c']  :  ''
		),
		'campos_soli' => array(
			'description' => $_REQUEST['description'],
		)
	);

	$result = $configuraciones->call("set_editar_oportunidad_web", $parametros, $url);

	echo json_encode($result);
}

function set_editar_detalle_web()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();
	$parametros = array(
		'session' => $_SESSION['session_id'],
		'oportunidad_id' => $_REQUEST['oportunidad_id'],
		'campos_opor' => array(
			'lead_source' => $_REQUEST['canal'],
			'date_assigned_c' => $_REQUEST['fecha_asignacion']
		),
		'campos_soli' => array(
			'fecha_viaje' => $_REQUEST['fecha'],
			'fecha_flexible' => $_REQUEST['fecha_flexible'],
			'hotel_habitacion' => $_REQUEST['habitaciones'],
			'adultos' => $_REQUEST['adultos'],
			'ninos' => $_REQUEST['ninos'],
			'edades' => $_REQUEST['edad_nino'],
			'agente' => $_REQUEST['agente_viaje']
		)
	);
	 
	$result = $configuraciones->call("set_editar_detalle_web", $parametros, $url);

	echo json_encode($result);
}

function set_enviar_msj()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();


	$parametros = array(
	'session' => $_SESSION['session_id'],
    'campos' => array(
						'name' => $_SESSION['nombre'].' '.$_SESSION['apellido'],
						'modified_user_id' => $_SESSION['user_id'],
						'created_by' =>  $_SESSION['user_id'],
						'description' => $_REQUEST['description'],
						'assigned_user_id' => $_REQUEST['assigned_user_id'],
    			)
	);
	
	$result = $configuraciones->call("set_enviar_msj", $parametros, $url);

	echo json_encode($result);
}

function get_msj()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();


	$parametros = array(
		'session' => $_SESSION['session_id'],
    	'remitente' => $_SESSION['user_id'],
    	'destino' => $_REQUEST['assigned_user_id'],
		'limit' => $_REQUEST['limit'],
    	'page' => $_REQUEST['page']
	);
	$result = $configuraciones->call("get_msj", $parametros, $url);

	echo json_encode($result);
}

function get_cantidad_msj_nuevo()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();


	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'remitente' => $_SESSION['user_id']
	);
	$result = $configuraciones->call("get_cantidad_msj_nuevo", $parametros, $url);

	echo json_encode($result);
}

function get_Fecha_carga()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();


	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'usuario_asig' => $_SESSION['user_id']
	);
	$result = $configuraciones->call("get_Fecha_carga", $parametros, $url);

	echo json_encode($result);
}

function set_log_acceso()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();

	
	$parametros = array(
       'session' => $_SESSION['session_id'],
       'campos' => array(
			'name' => $_REQUEST['acceso'],
			'description' => $_REQUEST['ubicacion_acceso'],
			'assigned_user_id' => $_SESSION['user_id']
		),
	);
	
	$result = $configuraciones->call("set_log_acceso", $parametros, $url);

	echo json_encode($result);
}

function busca_oportunidad_web()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();
	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'contacto_id' => $_SESSION['user_id'],
		'id_oportunidad' => $_REQUEST['id_oportunidad']
	);
	$result = $configuraciones->call("busca_oportunidad_web", $parametros, $url);

	echo json_encode($result);
}

function busca_oportunidad()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();
	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'contacto_id' => $_SESSION['user_id'],
		'id_oportunidad' => $_REQUEST['id_oportunidad']
	);
	$result = $configuraciones->call("busca_oportunidad", $parametros, $url);

	echo json_encode($result);
}

function busca_tarea()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();
	$parametros = array(
    	'session' => $_SESSION['session_id'],
    	'contacto_id' => $_SESSION['user_id'],
		'id_tarea' => $_REQUEST['id_tarea']
	);
	$result = $configuraciones->call("busca_tarea", $parametros, $url);

	echo json_encode($result);
}

function get_busca_cliente()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();
	$parametros = array(
		'session' => $_SESSION['session_id'],
	    'usuario_asig' => $_SESSION['user_id'],
	    'limit' => $_REQUEST['length'],
    	'page' => $_REQUEST['start'],
		'campos' => array(
			'campo' => $_REQUEST['campo'],
			'dato' => $_REQUEST['dato'],
			'rut' => $_REQUEST['rut'],
		 )
	);
	
	$datos = $configuraciones->call("get_busca_cliente", $parametros, $url);
	
	$result = array(
		"datos" => $datos,
		"draw" => $_REQUEST['draw'],
		"recordsFiltered" => $datos->cantidad,
		"recordsTotal" => $datos->cantidad
	);

	echo json_encode($result);
}


function get_detalle_cliente()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();
	$parametros = array(
		'session' => $_SESSION['session_id'],
	    'usuario_asig' => $_SESSION['user_id'],
		'id_contacto' => $_REQUEST['rut'],
	);
	
	$result = $configuraciones->call("get_detalle_cliente", $parametros, $url);

	echo json_encode($result);
}

function get_buscar_encuesta()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();
	$parametros = array(
		'session' => $_SESSION['session_id'],
		'rut' => $_REQUEST['rut'],
	);
	
	$result = $configuraciones->call("get_buscar_encuesta", $parametros, $url);

	echo json_encode($result);
}

function get_cantidad_nuevas_solicitudes()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();
	$parametros = array(
		'session' => $_SESSION['session_id'],
		'usuario_asig' => $_SESSION['user_id'],
	);
	
	$result = $configuraciones->call("get_contar_solicitud", $parametros, $url);

	echo json_encode($result);
}

function set_respuesta_nuevas_solicitudes()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();
	$parametros = array(
		'session' 		 => $_SESSION['session_id'],
		'usuario_asig'	 =>  $_SESSION['user_id'],
		'campos' => array(
			'id_tarea' =>  $_REQUEST['id_tarea'],
			'respuesta' => $_REQUEST['respuesta'],
			'user_name' => $_SESSION['user_name']			
		),
	);
	
	$result = $configuraciones->call("set_aprueba_solicitud", $parametros, $url);

	echo json_encode($result);
}

function disponibilidad_ejecutivo()
{
	$configuraciones = new Configuraciones;
	$url = $configuraciones->getUrlPrototipo();
	$configuraciones->setDisponibilidadSession($_REQUEST['estado']);
		
	$parametros = array(
		'session' => $_SESSION['session_id'],
		'campos' => array(
		    'usuario_asig'	 =>  $_SESSION['user_id'],
			'update'		 =>  $_REQUEST['update'],
            'estado'		 =>  $_REQUEST['estado'],
		),
	);
	
	$result = $configuraciones->call("set_estado_usuario", $parametros, $url);

	echo json_encode($result);
}
?>