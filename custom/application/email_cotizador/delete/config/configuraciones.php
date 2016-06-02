<?php session_start();

class Configuraciones 
{ 
	private $configuraciones;
	private $proyecto_path;
	
	public function __construct()
	{
		$this->setPathProyecto( dirname(dirname("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]")) );
		$this->setConfiguraciones($this->getParametros());
    }

    public function getConfiguraciones() 
	{ 
        echo json_encode($this->configuraciones);
    }

	public function setConfiguraciones($parametros)
	{
		$this->configuraciones = $parametros;
	}
	
	public function getPathProyecto() 
	{ 
        return $this->proyecto_path;
    }

	public function setPathProyecto($path)
	{
		$this->proyecto_path = $path;
	}
	
	public function getAvatarUsuario()
	{
		$avatar = $this->configuraciones['url_imagenes_usuarios'].$_SESSION['user_name'].'.jpg';
		return $avatar;
	}
	
	public function getActivarContrasenia()
	{
		return $this->configuraciones['cambiar_contrasenia'];
	}
	
	// public function getUrlCrm()
	// {
		// return $this->configuraciones['url_crm'];
	// }
	
	public function getUrlPrototipo()
	{
		return $this->configuraciones['url_prototipo'];
	}
	
	public function getFechaCarga()
	{
		$parametros = array(
			'session' => $_SESSION['session_id'],
			'usuario_asig' => $_SESSION['user_id']
		);
		
		$url = $this->configuraciones['url_prototipo'];
		
		$result = $this->call("get_Fecha_carga", $parametros, $url);

		$date = new DateTime($result->fecha_actualizacion);
		return $date->format('d/m/Y H:i:s');
	}
	
	public function validarUsuario($username,$p_password,$convertir_md5)
	{
		$url = $this->configuraciones['url_prototipo'];
		$session_id ='';
	
		$password = ($convertir_md5) ? md5($p_password) : $p_password;
	
		$login_parameters = array(
			 "user_auth" => array(
				  "user_name" => $username,
				  "password" => $password,
				  "version" => "1"
			 ),
			 "application_name" => "RestTest",
			 "name_value_list" => array(),
		);

		$login_result = $this->call("login", $login_parameters, $url);
		$login_result_array = (array)$login_result;
		
		if(isset($login_result_array['name']))
		{
			echo "0";
		}
		else
		{
			$user_name = $login_result->name_value_list->user_name->value;
			$user_id = $login_result->name_value_list->user_id->value;
			$session_id = $login_result->id;

			if($session_id != '')
			{ 
				$_SESSION['ULTIMA_ACTIVIDAD'] = time(); 
				$_SESSION['session_id'] = $session_id;
				$_SESSION['user_name']  = $user_name;
				$_SESSION['user_id']    = $user_id;
				$_SESSION['session']    = true;
				global $session_id;
				echo "1"; 
			}
			else 
			{
				echo json_encode($login_result);
			}
		}
	}
	
	public function LogOut()
	{
		if (isset($_SESSION['session_id']))
		{
			clearstatcache();
			unset ($_SESSION['session_id']);
		}
		$this->resetBanderaVentanaEmergente();
		echo "1";
	}
	
	public function call($method, $parameters, $url)
	{
		ob_start();
		$curl_request = curl_init();

		curl_setopt($curl_request, CURLOPT_URL, $url);
		curl_setopt($curl_request, CURLOPT_POST, 1);
		curl_setopt($curl_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		curl_setopt($curl_request, CURLOPT_HEADER, 1);
		curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 0);

		$jsonEncodedData = json_encode($parameters);

		$post = array(
			 "method" => $method,
			 "input_type" => "JSON",
			 "response_type" => "JSON",
			 "rest_data" => $jsonEncodedData
		);

		curl_setopt($curl_request, CURLOPT_POSTFIELDS, $post);
		$result = curl_exec($curl_request);
		curl_close($curl_request);

		$result = explode("\r\n\r\n", $result, 2);
		$response = json_decode($result[1]);
		ob_end_flush();

		return $response;
	}
	
	public function getPlantillas($tag_name)
	{
		try
		{
			$directorio = "../".$this->configuraciones['url_plantillas_mail'];
			
			$plantillas = array();
			if (is_dir($directorio))
			{
			  if ($dh = opendir($directorio))
			  {
				while (($file = readdir($dh)) !== false)
				{
					if ((isset($_REQUEST['modulo_tag'])) ) 
					{
						if(strpos($file,$_REQUEST['modulo_tag']) !== false)
						{
							$file_name_show = ucwords(str_replace("_"," ",str_replace(".html", "", $file)));
							array_push($plantillas,array("file_name" => $file,"file_name_show" => $file_name_show));
						}
					} 
					else 
					{
						$file_name_show = ucwords(str_replace("_"," ",str_replace(".html", "", $file)));
						array_push($plantillas,array("file_name" => $file,"file_name_show" => $file_name_show));
					}
				}
				closedir($dh);
			  }
			}
			
			$respuesta = array(
				"estado" => 1,
				"plantillas" => $plantillas,
				"directorio" => $directorio
			);
			
			echo json_encode($respuesta);
		} 
		catch (Exception $e)
		{
			$respuesta = array(
				"estado" => 0,
				"error" => $e->getMessage()
			);
			
			echo json_encode($respuesta);
		}
	}
	
	public function CargarDetalleUser($request)
	{
		try
		{
			$_SESSION['nombre'] = $request['nombre'];
			$_SESSION['apellido'] = $request['apellido'];
			$_SESSION['email'] = $request['email'];
			$_SESSION['telefono'] = $request['telefono'];
			$_SESSION['sucursal'] = $request['sucursal'];
			$_SESSION['crosselling_sucursal'] = $request['crosselling_sucursal'];
			$_SESSION['crosselling_ejecutiva'] = $request['crosselling_ejecutiva'];
			$_SESSION['cargo'] = $request['cargo'];
			$_SESSION['solcitides_web'] = $request['solcitides_web'];
			$_SESSION['croseling'] = $request['croseling'];
			$_SESSION['recompra'] = $request['recompra'];
			$_SESSION['fugados'] = $request['fugados'];
			$_SESSION['retornos'] = $request['retornos'];
			$_SESSION['cumpleanio'] = $request['cumpleanio'];
			$_SESSION['cartera_cliente'] = $request['cartera_cliente'];
			$_SESSION['proximos_viajes'] = $request['proximos_viajes'];
			$_SESSION['boton_web'] = $request['boton_web'];
			$_SESSION['bandera_ventana_emergente'] = true;
			
			$respuesta = array(
				"estado" => 1
			);
			
			echo json_encode($respuesta);
		} 
		catch (Exception $e)
		{
			$respuesta = array(
				"estado" => 0,
				"error" => $e->getMessage()
			);
			
			echo json_encode($respuesta);
		}
	}
	
	public function downloadPdf($pdf_url)
	{
		$archivo = "";
		if(isset($pdf_url) && $pdf_url != "")
		{
			$array = split('/',$pdf_url);
			$archivo = date("h_i_s").'_'.trim($array[count($array)-1]);
			$url = trim('http:/'.$pdf_url);
		
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_POST, 0); 
			curl_setopt($ch,CURLOPT_URL,$url); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			$file_content = curl_exec($ch);
			curl_close($ch);
	 
			$downloaded_file = fopen(dirname(realpath("./")) . '/upload/'.$archivo, 'w');
			fwrite($downloaded_file, $file_content);
			fclose($downloaded_file);
		}
		
		echo $archivo;
	}

	private function getParametros()
	{
		/** INICIO: URL ENTORNOS */
			//$parametros['url_crm'] = "http://104.131.85.230/desarrollo/service/v4_1/rest.php";
			$parametros['url_prototipo'] = "http://backend.cochadigital.com/service/v4_1/rest.php";
		/** FIN */
		
		/** INICIO: URL PARA ACTUALIZAR TABLAS POR FILAS */
			$parametros['url_busca_oportunidad_web'] = 'ws/wss.php?func=busca_oportunidad_web'; 
			$parametros['url_busca_oportunidad'] = 'ws/wss.php?func=busca_oportunidad'; 
			$parametros['url_busca_tarea'] = 'ws/wss.php?func=busca_tarea'; 
		/** FIN */
		
		/** INICIO: ACTIVAR CAMBIAR CONTRASEÑA */
			$parametros['cambiar_contrasenia'] = false; 
		/** FIN */
	
		/** INICIO: URL IMAGENES DE USUARIOS */
			$parametros['url_imagenes_usuarios'] = "assets/images/usuarios/";
			$parametros['url_imagenes_administrador'] = "assets/images/usuarios/administrador.jpg";
		/** FIN */
		
		/** INICIO: TAGS DE MÓDULOS */
			$parametros['solicitudes_web_tag'] = "solicitudes_web";
			$parametros['crosselling_tag'] = "crosselling";
			$parametros['recompra_tag'] = "recompra";
			$parametros['fugados_tag'] = "fugados";
			$parametros['proximos_viajes_tag'] = "proximos_viajes";
			$parametros['retorno_tag'] = "retorno";
			$parametros['cumpleanos_tag'] = "cumpleanos";
			$parametros['cartera_clientes_tag'] = "cartera_clientes";
			$parametros['general_tag'] = "general";
			$parametros['buscador_tag'] = "buscador";
		/** FIN */
		
		/** INICIO: PLANTILLAS DEFAULT POR MODULOS */
			$parametros['plantilla_default_solicitudes_web_tag'] = 0;
			$parametros['plantilla_default_crosselling_tag'] = 0;
			$parametros['plantilla_default_recompra_tag'] = 0;
			$parametros['plantilla_default_fugados_tag'] = 0;
			$parametros['plantilla_default_proximos_viajes_tag'] = 0;
			$parametros['plantilla_default_retorno_tag'] = 1;
			$parametros['plantilla_default_cumpleanos_tag'] = 0;
			$parametros['plantilla_default_cartera_clientes_tag'] = 0;
			$parametros['plantilla_default_general_tag'] = 0;
		/** FIN */
		
		/** INICIO: PRIVILEGIOS DE USUARIOS */
			$parametros['privilegio_solcitides_web'] = isset($_SESSION['solcitides_web']) ? $_SESSION['solcitides_web'] : "";
			$parametros['privilegio_croseling'] = isset($_SESSION['croseling']) ? $_SESSION['croseling'] : "";
			$parametros['privilegio_recompra'] = isset($_SESSION['recompra']) ? $_SESSION['recompra'] : "";
			$parametros['privilegio_fugados'] = isset($_SESSION['fugados']) ? $_SESSION['fugados'] : "";
			$parametros['privilegio_retornos'] = isset($_SESSION['retornos']) ? $_SESSION['retornos'] : "";
			$parametros['privilegio_cumpleanio'] = isset($_SESSION['cumpleanio']) ? $_SESSION['cumpleanio'] : "";
			$parametros['privilegio_cartera_cliente'] = isset($_SESSION['cartera_cliente']) ? $_SESSION['cartera_cliente'] : "";
			$parametros['privilegio_proximos_viajes'] = isset($_SESSION['proximos_viajes']) ? $_SESSION['proximos_viajes'] : "";
		/** FIN */
		
		
		/** INICIO: CARGAR CONTADORES */
			$parametros['url_cargar_contadores'] = "ws/wss.php?func=get_cantidad_inicio_nuevos";
		/** FIN */
		
		/** INICIO: TAGS DE PROCEDENCIA DE MAILS */
			$parametros['tag_procedencia_oportunidad'] = 'opportunities';
			$parametros['tag_procedencia_tarea'] = 'tasks';
			$parametros['tag_procedencia_contacto'] = 'contacts';
			$parametros['tag_procedencia_cotizacion'] = 'EnvioCotizacion';
			// $parametros['tag_procedencia_cotizacion'] = 'cotizacion';
		/** FIN */
	
		/** INICIO: CONFIGURACIONES PARA SOLICITUDES WEB */
				/** INICIO: VISUALIZACIÓN DE LAS TABLAS */
					$parametros['mostrar_abierto_table_solicitudes_web'] = true;
					$parametros['mostrar_pendiente_table_solicitudes_web'] = true;
					$parametros['mostrar_cerrado_table_solicitudes_web'] = true;
				/** FIN */
				
				/** INICIO: BOTONES CIERRE NEGOCIO */
					/** NUEVOS */
						$parametros['mostrar_boton_solicitudes_web_perdido_nuevos'] = true;
						$parametros['mostrar_boton_solicitudes_web_ganado_nuevos'] = true;
					    $parametros['mostrar_boton_solicitudes_web_estados'] = true;
					
					
					/** PENDIENTES */
						$parametros['mostrar_boton_solicitudes_web_perdido_pendientes'] = true;
						$parametros['mostrar_boton_solicitudes_web_ganado_pendientes'] = true;			
				/** FIN */
				
				/** INICIO: URL OBTENER DATOS PARA LAS TABLAS */
					/** NUEVOS */
						$parametros['url_abierto_table_solicitudes_web'] = 'ws/wss.php?func=get_solicitudes_web_nuevas';
					/** PENDIENTES */
						$parametros['url_pendiente_table_solicitudes_web'] = 'ws/wss.php?func=get_solicitudes_web_pendientes';
					/** CERRADOS */
						$parametros['url_cerrado_table_solicitudes_web'] = 'ws/wss.php?func=get_solicitudes_web_cerradas';		
				/** FIN */
				
				/** INICIO: URL EDITAR CLIENTE*/
					$parametros['url_set_editar_contactos_web'] = 'ws/wss.php?func=set_editar_contactos_web';
				/** FIN */
				
				 /** INICIO: URL EDITAR DATOS DE SOLICITUD*/
					$parametros['url_set_editar_detalle_web'] = 'ws/wss.php?func=set_editar_detalle_web';
				/** FIN */
				
				
				/** INICIO: URL OBTENER CONTADORES */
					$parametros['url_cantidad_solicitudes_web'] = 'ws/wss.php?func=get_cantidad_web_nuevas';
				/** FIN */
				
				/** INICIO: URL PARA MODAL GANADO Y PERDIDO */
					$parametros['url_set_editar_oportunidad_web'] = 'ws/wss.php?func=set_editar_oportunidad_web';
				/** FIN */
				
				/** INICIO: MODAL SOLICITUDES WEB */
					$parametros['url_get_oportunidad_detalle_web'] = 'ws/wss.php?func=get_oportunidad_detalle_web';
					$parametros['url_get_oportunidad_detalle_producto_web'] = 'ws/wss.php?func=get_oportunidad_detalle_producto_web';
					$parametros['url_get_detalles_programas'] = 'http://cms.cocha.com/mobile-api/programas';
					$parametros['url_set_carga_solicitud'] = 'ws/wss.php?func=set_carga_solicitud';
				/** FIN */
				
				/** INICIO: MODAL MAIL COTIZACIÓN WEB */
					$parametros['url_cocha_csm_mobile_api_desarrollo'] = 'http://cms-desa.cocha.com/mobile-api';
					$parametros['url_cocha_csm_mobile_api'] = 'http://cms.cocha.com/mobile-api';
					$parametros['url_cocha_csm'] = 'http://cms.cocha.com/';
				/** FIN */
				
					$parametros['url_disponibilidad_ejecutivo'] = 'ws/wss.php?func=disponibilidad_ejecutivo';
				
				/** INICIO: TAGS sale_stage */
					//contactado
						$parametros['tag_sale_stage_primera_llamada'] = "contactado";
					//cotizacion
						$parametros['tag_sale_stage_seguimiento_1'] = "cotizacion";
					//negociacion
						$parametros['tag_sale_stage_seguimiento_2'] = "negociacion";
					$parametros['tag_cerrado_ganado'] = "CerradoGanado";
					$parametros['tag_cerrado_perdido'] = "CerradoPerdido";
					$parametros['solicitudes_web_plantilla_solicitud_web'] = "templates/solicitudes_web_plantilla_solicitud_web.html";
					$parametros['config_cotizador_configuraciones'] = "config/configuraciones.php";
				/** FIN */
		/** FIN: CONFIGURACIONES PARA SOLICITUDES WEB */
		
		/** INICIO: CONFIGURACIONES PARA CROSS SELLING */
				/** INICIO: VISUALIZACIÓN DE LAS TABLAS */
					$parametros['mostrar_abierto_table_cross_selling'] = true;
					$parametros['mostrar_pendiente_table_cross_selling'] = false;
					$parametros['mostrar_cerrado_table_cross_selling'] = true;
				/** FIN */
				
				/** INICIO: BOTONES CIERRE NEGOCIO */
					/** NUEVOS */
						$parametros['mostrar_boton_cross_selling_perdido_nuevos'] = true;
						$parametros['mostrar_boton_cross_selling_ganado_nuevos'] = true;
					/** PENDIENTES */
						$parametros['mostrar_boton_cross_selling_perdido_pendientes'] = true;
						$parametros['mostrar_boton_cross_selling_ganado_pendientes'] = true;			
				/** FIN */
				
				/** INICIO: URL OBTENER DATOS PARA LAS TABLAS */
					/** NUEVOS */
						$parametros['url_abierto_table_cross_selling'] = 'ws/wss.php?func=get_obtiene_crossseling_abiertos';
					/** PENDIENTES */
						$parametros['url_pendiente_table_cross_selling'] = 'ws/wss.php?func=get_obtiene_crossseling_pendientes';
					/** CERRADOS */
						$parametros['url_cerrado_table_cross_selling'] = 'ws/wss.php?func=get_obtiene_crosselling_cerradas';		
				/** FIN */
				
				/** INICIO: URL OBTENER CONTADORES */
					$parametros['url_cantidad_cross_selling'] = 'ws/wss.php?func=get_cantidad_crossseling_nuevos';
				/** FIN */
		/** FIN */
		
		/** INICIO: CONFIGURACIONES PARA RECOMPRA */
				/** INICIO: VISUALIZACIÓN DE LAS TABLAS */
					$parametros['mostrar_abierto_table_recompra'] = true;
					$parametros['mostrar_pendiente_table_recompra'] = false;
					$parametros['mostrar_cerrado_table_recompra'] = true;
				/** FIN */
				
				/** INICIO: BOTONES CIERRE NEGOCIO */
					/** NUEVOS */
						$parametros['mostrar_boton_recompra_perdido_nuevos'] = true;
						$parametros['mostrar_boton_recompra_ganado_nuevos'] = true;
					/** PENDIENTES */
						$parametros['mostrar_boton_recompra_perdido_pendientes'] = true;
						$parametros['mostrar_boton_recompra_ganado_pendientes'] = true;			
				/** FIN */
				
				/** INICIO: URL OBTENER DATOS PARA LAS TABLAS */
					/** NUEVOS */
						$parametros['url_abierto_table_recompra'] = 'ws/wss.php?func=get_recompra_nuevos';
					/** PENDIENTES */
						$parametros['url_pendiente_table_recompra'] = 'ws/wss.php?func=get_recompra_pendientes';
					/** CERRADOS */
						$parametros['url_cerrado_table_recompra'] = 'ws/wss.php?func=get_recompra_cerrados';		
				/** FIN */
				
				/** INICIO: URL OBTENER CONTADORES */
					$parametros['url_cantidad_recompra'] = 'ws/wss.php?func=get_cantidad_recompra_nuevos';
				/** FIN */
		/** FIN: CONFIGURACIONES PARA RECOMPRA */
		
		/** INICIO: CONFIGURACIONES PARA FUGADOS */
				/** INICIO: VISUALIZACIÓN DE LAS TABLAS */
					$parametros['mostrar_abierto_table_fugados'] = true;
					$parametros['mostrar_pendiente_table_fugados'] = false;
					$parametros['mostrar_cerrado_table_fugados'] = true;
				/** FIN: VISUALIZACIÓN DE LAS TABLAS */
				
				/** INICIO: BOTONES CIERRE NEGOCIO */
					/** NUEVOS */
						$parametros['mostrar_boton_fugados_perdido_nuevos'] = true;
						$parametros['mostrar_boton_fugados_ganado_nuevos'] = true;
					/** PENDIENTES */
						$parametros['mostrar_boton_fugados_perdido_pendientes'] = true;
						$parametros['mostrar_boton_fugados_ganado_pendientes'] = true;			
				/** FIN: BOTONES CIERRE NEGOCIO */
				
				/** INICIO: URL OBTENER DATOS PARA LAS TABLAS */
					/** NUEVOS */
						$parametros['url_abierto_table_fugados'] = "ws/wss.php?func=get_fugados_nuevos";
					/** PENDIENTES */
						$parametros['url_pendiente_table_fugados'] = "ws/wss.php?func=get_fugados_pendientes";
					/** CERRADOS */
						$parametros['url_cerrado_table_fugados'] = "ws/wss.php?func=get_fugados_cerrados";		
				/** FIN: URL OBTENER DATOS PARA LAS TABLAS */
				
				/** INICIO: URL OBTENER CONTADORES */
					$parametros['url_cantidad_fugados'] = 'ws/wss.php?func=get_cantidad_fugados_nuevos';
				/** FIN: URL OBTENER CONTADORES */
		/** FIN: CONFIGURACIONES PARA FUGADOS */
		
		/** INICIO: CONFIGURACIONES PARA PROXIMOS VIAJES */
				/** INICIO: VISUALIZACIÓN DE LAS TABLAS */
					$parametros['mostrar_abierto_table_proximos_viajes'] = true;
					$parametros['mostrar_pendiente_table_proximos_viajes'] = false;
					$parametros['mostrar_cerrado_table_proximos_viajes'] = true;
				/** FIN: VISUALIZACIÓN DE LAS TABLAS */
				
				/** INICIO: BOTONES CIERRE NEGOCIO */
					/** NUEVOS */
						$parametros['mostrar_boton_proximos_viajes_cierre_nuevos'] = true;
					/** PENDIENTES */
						$parametros['mostrar_boton_proximos_viajes_cierre_pendientes'] = true;
				/** FIN: BOTONES CIERRE NEGOCIO */
				
				/** INICIO: URL OBTENER DATOS PARA LAS TABLAS */
					/** NUEVOS */
						$parametros['url_abierto_table_proximos_viajes'] = "ws/wss.php?func=get_proximos_viajes_nuevos";
					/** PENDIENTES */
						$parametros['url_pendiente_table_proximos_viajes'] = "ws/wss.php?func=get_proximos_viajes_pendientes";
					/** CERRADOS */
						$parametros['url_cerrado_table_proximos_viajes'] = "ws/wss.php?func=get_proximos_viajes_cerrados";		
				/** FIN: URL OBTENER DATOS PARA LAS TABLAS */
				
				
				/** INICIO: URL EDITAR TAREAS */
					/** VOUCHER */
						$parametros['url_editar_proximos_viajes_voucher'] = $this->getPathProyecto()."/ws/wss.php?func=set_editar_tarea_voucher";
					/** DESCRIPTION */
						$parametros['url_editar_proximos_viajes_description'] = $this->getPathProyecto()."/ws/wss.php?func=set_editar_tarea_description";
					/** DOCUMENTACION */
						$parametros['url_editar_proximos_viajes_documentacion'] = $this->getPathProyecto()."/ws/wss.php?func=set_editar_tarea_documentacion";
				/** FIN: URL EDITAR TAREAS */
				
				/** INICIO: URL OBTENER CONTADORES */
					$parametros['url_cantidad_proximos_viajes'] = 'ws/wss.php?func=get_cantidad_proximos_viajes_nuevos';
				/** FIN: URL OBTENER CONTADORES */
				
				/** INICIO: URL MODAL CERRAR NEGOCIO */
					$parametros['url_set_editar_tarea'] = 'ws/wss.php?func=set_editar_tarea';
				/** FIN: URL MODAL CERRAR NEGOCIO */
				
		/** FIN: CONFIGURACIONES PARA PROXIMOS VIAJES */
		
		/** INICIO: CONFIGURACIONES PARA RETORNO */
				/** INICIO: VISUALIZACIÓN DE LAS TABLAS */
					$parametros['mostrar_abierto_table_retorno'] = true;
					$parametros['mostrar_pendiente_table_retorno'] = false;
					$parametros['mostrar_cerrado_table_retorno'] = true;
				/** FIN: VISUALIZACIÓN DE LAS TABLAS */
				
				/** INICIO: BOTONES CIERRE NEGOCIO */
					/** NUEVOS */
						$parametros['mostrar_boton_retorno_ganado_nuevos'] = false;
						$parametros['mostrar_boton_retorno_perdido_nuevos'] = true;
					/** PENDIENTES */
						$parametros['mostrar_boton_retorno_ganado_pendientes'] = false;			
						$parametros['mostrar_boton_retorno_perdido_pendientes'] = true;
				/** FIN: BOTONES CIERRE NEGOCIO */
				
				/** INICIO: URL OBTENER DATOS PARA LAS TABLAS */
					/** NUEVOS */
						$parametros['url_abierto_table_retorno'] = "ws/wss.php?func=get_retorno_nuevos";
					/** PENDIENTES */
						$parametros['url_pendiente_table_retorno'] = "ws/wss.php?func=get_retorno_pendientes";
					/** CERRADOS */
						$parametros['url_cerrado_table_retorno'] = "ws/wss.php?func=get_retorno_cerrados";		
				/** FIN: URL OBTENER DATOS PARA LAS TABLAS */
				
				/** INICIO: URL OBTENER CONTADORES */
					$parametros['url_cantidad_retorno'] = 'ws/wss.php?func=get_cantidad_retorno_nuevos';
					$parametros['url_get_buscar_encuesta'] = 'ws/wss.php?func=get_buscar_encuesta';
				/** FIN: URL OBTENER CONTADORES */
		/** FIN: CONFIGURACIONES PARA RETORNO */
		
		/** INICIO: CONFIGURACIONES PARA CUMPLEAÑOS */
			/** INICIO: URL OBTENER DATOS PARA LAS TABLAS */
				/** NUEVOS */
					$parametros['url_table_cumpleanos'] = "ws/wss.php?func=get_cumple_mes";
			/** FIN: URL OBTENER DATOS PARA CUMPLEAÑOS */
			
			/** INICIO: URL OBTENER CONTADORES */
					$parametros['url_cantidad_cumpleanos'] = 'ws/wss.php?func=get_cantidad_cumple_mes';
				/** FIN: URL OBTENER CONTADORES */
		/** FIN: CONFIGURACIONES PARA RETORNO */
		
		/** INICIO: CONFIGURACIONES PARA CARTERA DE CLIENTES */
			/** INICIO: URL OBTENER DATOS PARA LAS TABLAS */
				/** NUEVOS */
					$parametros['url_table_pasajeros'] = "ws/wss.php?func=get_buscar_contacto&tipo=Pasajero";
					$parametros['url_table_compradores'] = "ws/wss.php?func=get_buscar_contacto&tipo=Comprador";
					$parametros['url_get_detalle_cliente'] = "ws/wss.php?func=get_detalle_cliente";
			/** FIN: URL OBTENER DATOS PARA CUMPLEAÑOS */
		/** FIN: CONFIGURACIONES PARA CARTERA DE CLIENTES */
		
		/** INICIO: URL PARA CANTIDAD DE NUEVAS SOLICITUDES*/
			$parametros['url_get_cantidad_nuevas_solicitudes'] = "ws/wss.php?func=get_cantidad_nuevas_solicitudes";
			$parametros['url_set_respuesta_nuevas_solicitudes'] = "ws/wss.php?func=set_respuesta_nuevas_solicitudes";
			$parametros['intervalo_refrescar_nuevas_solicitudes'] = 20000;
		/** FIN: URL PARA CANTIDAD DE NUEVAS SOLICITUDES */
		
		/** INICIO: CONFIGURACIONES PARA PAGINA BLOQUEADA (TIMEOUT) */
			$parametros['url_table_buscador'] = "ws/wss.php";
			$parametros['tablas_nuevas_tag'] = "nuevas";
			$parametros['tablas_cerradas_tag'] = "cerradas";
		
		/** FIN: CONFIGURACIONES PARA PAGINA BLOQUEADA (TIMEOUT) */
		
		/** INICIO: URL DE LAS PLANTILLAS DE MAIL */
			$parametros['url_plantillas_mail'] = "templates/";
		/** INICIO: URL DE LAS PLANTILLAS DE MAIL */
		
		/** INICIO: URL CAMBIAR CONTRASEÑA */
			$parametros['url_cambiar_contrasenia'] = "ws/wss.php?func=set_cambia_password";
			$parametros['url_set_notificacion_acceso'] = "ws/wss.php?func=set_notificacion_acceso";
			$parametros['expresion_regular_contrasenia'] = "[^A-Za-z0-9]";
			$parametros['expresion_regular_contrasenia_mensaje'] = "* La contraseña de ser alfanumérico";
		/** FIN: URL CAMBIAR CONTRASEÑA */
		
		/** INICIO: URL DETALLES NEGOCIOS MODAL */
			$parametros['url_get_negocio_detalle'] = "ws/wss.php?func=get_negocio_detalle";
			$parametros['url_get_detalle_pasajeros'] = "ws/wss.php?func=get_detalle_pasajeros";
			$parametros['url_set_editar_contactos'] = "ws/wss.php?func=set_editar_contactos";
		/** FIN: URL DETALLES NEGOCIOS MODAL */
		
		/** INICIO: URL ENVIAR EMAIL*/
			$parametros['url_set_enviar_email_web'] = "ws/wss.php?func=set_enviar_email_web";
			$parametros['url_set_enviar_email'] = "ws/wss.php?func=set_enviar_email";
		/** FIN: URL ENVIAR EMAIL */
		
		/** INICIO: URL EDITAR NEGOCIO */
			$parametros['url_set_editar_tarea'] = "ws/wss.php?func=set_editar_tarea";
			$parametros['url_set_editar_oportunidad'] = "ws/wss.php?func=set_editar_oportunidad";
		/** FIN: URL EDITAR NEGOCIO */
		
		/** INICIO: URL ENVIAR MENSAJE DE CONVERSACION */
			$parametros['limit_mensajes'] = 5;
			$parametros['intervalo_refrescar_cantidad_mensajes'] = 20000;
			$parametros['url_get_msj'] = "ws/wss.php?func=get_msj";
			$parametros['url_set_enviar_msj'] = "ws/wss.php?func=set_enviar_msj";
			$parametros['url_get_cantidad_msj_nuevo'] = "ws/wss.php?func=get_cantidad_msj_nuevo";
		/** FIN: URL ENVIAR MENSAJE DE CONVERSACION */
		
		/** INICIO: URL LOGIN */
			$parametros['url_get_user_detalle'] = "ws/wss.php?func=get_user_detalle";
			$parametros['url_set_log_acceso'] = "ws/wss.php?func=set_log_acceso";
			$parametros['url_get_acceso_api'] = "ws/wss.php?func=get_acceso_api";
			$parametros['contrasenia_default'] = "12345";
		/** FIN: URL LOGIN */
		
		/** INICIO: PLANTILLAS DEFAULT*/
		
		/** FIN: PLANTILLAS DEFAULT*/
		return $parametros;
	}
	
	public function getBanderaVentanaEmergente()
	{
		echo $_SESSION['bandera_ventana_emergente'];
	}
	
	public function resetBanderaVentanaEmergente()
	{
		echo $_SESSION['bandera_ventana_emergente'] = false;
	}
	
	public function setDisponibilidadSession($estado)
	{
		$_SESSION['estado_disponibilidad_ejecutivo'] = $estado;
	}
	

}
 
if(isset($_REQUEST['v_class']))
{
	if( trim($_REQUEST['v_class']) == 'configuraciones')
	{
		$configuraciones = new Configuraciones;
		return $configuraciones->getConfiguraciones();
	}
	if( trim($_REQUEST['v_class']) == 'plantillas')
	{
		$configuraciones = new Configuraciones;
		return $configuraciones->getPlantillas($_REQUEST['modulo_tag']);
	}

	if( trim($_REQUEST['v_class']) == 'validar_usuario')
	{	
		$convertir_md5 = (isset($_REQUEST['convertir_md5'])) ? false : true;
		$configuraciones = new Configuraciones;
		return $configuraciones->validarUsuario($_REQUEST['user'],$_REQUEST['pass'],$convertir_md5);
	}
	
	if( trim($_REQUEST['v_class']) == 'cargar_detalle_user')
	{
		$configuraciones = new Configuraciones;
		return $configuraciones->CargarDetalleUser($_REQUEST);
	}
	
	if( trim($_REQUEST['v_class']) == 'log_out')
	{
		$configuraciones = new Configuraciones;
		return $configuraciones->LogOut();
	}
	
	if( trim($_REQUEST['v_class']) == 'download_pdf')
	{
		$configuraciones = new Configuraciones;
		return $configuraciones->downloadPdf(trim($_REQUEST['pdf']));
	}
	
	if( trim($_REQUEST['v_class']) == 'get_bandera_ventana_emergente')
	{
		$configuraciones = new Configuraciones;
		return $configuraciones->getBanderaVentanaEmergente();
	}
	
	if( trim($_REQUEST['v_class']) == 'reset_bandera_ventana_emergente')
	{
		$configuraciones = new Configuraciones;
		return $configuraciones->resetBanderaVentanaEmergente();
	}
}
?> 