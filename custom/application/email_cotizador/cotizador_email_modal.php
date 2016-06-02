<?php 
	clearstatcache();
	global $current_user;
	// echo json_encode($current_user);
	$ruta = "http://crm.cochadigital.com/"; 
?>
<!DOCTYPE html>
<html>
   <!-- INICIO: HEAD -->
   <head>
      <!-- Meta, title, CSS, favicons, etc. -->
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <title>Envio Cotización</title>
      <meta name="viewport"  content="width=device-width, initial-scale=1.0, no-cache">
      <!-- Font CSS (Via CDN) -->
      <link rel='stylesheet' type='text/css' href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700'>

      <!-- Theme CSS -->
      <link rel="stylesheet" type="text/css" href="<?php echo $ruta; ?>assets/skin/default_skin/css/theme.css">
      <link rel="stylesheet" type="text/css" href="<?php echo $ruta; ?>assets/admin-tools/admin-forms/css/admin-forms.css">
      <!-- CUSTOM CSS -->
      <link rel="stylesheet" type="text/css" href="<?php echo $ruta; ?>assets/skin/default_skin/css/custom.css">
	  <link rel="stylesheet" type="text/css" href="<?php echo $ruta; ?>vendor/plugins/webui-popover-master/src/jquery.webui-popover.css">

      <!-- Favicon -->
      <link rel="shortcut icon" href="assets/img/favicon.ico">
      <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
      <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
      <![endif]-->
   </head>
   
   <!-- FIN: HEAD -->
   <body id="top" class="dashboard-page" >
      <!-- Start: Main -->
      <div id="main">
         <!-- INICIO: CONTENEDOR PRINCIPAL -->
         <section id="content_wrapper">
            <!-- INICIO: CONTENIDO -->
            <section id="content" class="animated fadeIn">
				<div class="row">
					<div class="col-xs-12">
						<h2 id="mensaje" class="text-dark mbn confirmation-header"><i class="fa fa-spinner fa-spin text-success"></i> Cargando...</h2>
					</div>
				</div>
               <?php
                  include($ruta.'/modulos/solicitudes_web/solicitudes_web_email_modal.php');
               ?>
			   <input type="hidden" name="oportunidad_id" value="<?php echo($_GET['id_registro']); ?>" />
			   <input type="hidden" name="id_solicitud" value="<?php echo($_GET['id_solicitud']); ?>" />
			   <input type="hidden" name="ruta" value="<?php echo $ruta; ?>" />
			   
			   <input type="hidden" name="input_user_detalle_nombre"/>
			   <input type="hidden" name="input_user_detalle_apellido"/>
			   <input type="hidden" name="input_user_detalle_email"/>
			   <input type="hidden" name="input_user_detalle_telefono"/>
			   <input type="hidden" name="input_user_detalle_cargo"/>
			   <input type="hidden" name="input_user_detalle_sucursal"/>
            </section>
            <!-- FIN: CONTENIDO -->
         </section>
         <!-- FIN: CONTENEDOR PRINCIPAL -->
      </div>
      <!-- End: Main -->
      <!-- BEGIN: PAGE SCRIPTS -->
      <!-- PLUGINS DE DEFECTO DE LA PLANTILLA -->	
      <!-- jQuery -->
      <script src="<?php echo $ruta; ?>vendor/jquery/jquery-1.11.1.min.js"></script>
      <script src="<?php echo $ruta; ?>vendor/jquery/jquery_ui/jquery-ui.min.js"></script>
	  <script src="<?php echo $ruta; ?>vendor/plugins/date.js"></script>
      <!-- Theme Javascript -->
      <script src="<?php echo $ruta; ?>assets/js/utility/utility.js"></script>
	  <script src="<?php echo $ruta; ?>assets/js/demo/demo.js"></script>
	  <script src="<?php echo $ruta; ?>assets/js/main.js"></script>
	  <script src="<?php echo $ruta; ?>assets/js/custom.js"></script>
      <!-- Xedit JS -->
      <script src="<?php echo $ruta; ?>vendor/plugins/moment/moment.min.js"></script>

      <!-- Validacion -->
      <script src="<?php echo $ruta; ?>assets/admin-tools/admin-forms/js/jquery.validate.min.js"></script>

      <!-- CKEditor - Editor de html en los mails -->
      <script src="<?php echo $ruta; ?>vendor/plugins/ckeditor/ckeditor.js"></script>
	  <script src="<?php echo $ruta; ?>assets/js/configuraciones.js"></script>
	  <script src="<?php echo $ruta; ?>assets/js/funciones_generales.js"></script>
	  <script src="<?php echo $ruta; ?>assets/js/auxiliar.js"></script>
	  
	  <script src="<?php echo $ruta; ?>modulos/solicitudes_web/js/solicitudes_web_email_modal.js"></script>
	  <script src="<?php echo $ruta; ?>vendor/plugins/webui-popover-master/src/jquery.webui-popover.js"></script>
	  <script src="<?php echo $ruta; ?>vendor/plugins/accounting.min.js"></script>
      <!-- FIN PLUGINS DE DEFECTO DE LA PLANTILLA -->		
	  <script>
		jQuery(document).ready(function() {
		
			var ruta = $('input[name=ruta]').val();
			var url_configuraciones = ruta+"config/configuraciones.php";
			var data = {
				v_class : 'configuraciones' 
			}
			
			$.get( url_configuraciones, data ).done(function(respuesta) {
				
				var configuraciones = JSON.parse(respuesta);
				//modificar el path de las url para que sean usadas desde backend.
					configuraciones.url_get_oportunidad_detalle_producto_web = ruta+configuraciones.url_get_oportunidad_detalle_producto_web;
					configuraciones.url_get_oportunidad_detalle_web = ruta+configuraciones.url_get_oportunidad_detalle_web;
					configuraciones.url_set_enviar_email_web = ruta+configuraciones.url_set_enviar_email_web;
					configuraciones.solicitudes_web_plantilla_solicitud_web = ruta+configuraciones.solicitudes_web_plantilla_solicitud_web;
					configuraciones.config_cotizador_configuraciones = ruta+configuraciones.config_cotizador_configuraciones;
					configuraciones['from_backend'] = true;
				
				Configuraciones.setConfiguraciones(JSON.stringify(configuraciones));
				Configuraciones.setUrlConfiguraciones(ruta+"config/configuraciones.php");
								
				
				var id_solicitud = $('input[name=id_solicitud]').val();
				var data = { 
					oportunidad_id: $('input[name=oportunidad_id]').val()
				};
				
				$.post(Configuraciones.getConfiguraciones().url_get_oportunidad_detalle_producto_web, data).done(function(response){
					
					var json = jQuery.parseJSON(response);
					var productos_html = "" ;
					var pdf_cocha = "";
					var url_cocha = "";
					
					for(i = 0; i < json.length; i++)
					{
						if(id_solicitud == json[i].id)
						{
							pdf_cocha = (json[i].pdf).replace("http://","");
							var pdf_link = pdf_cocha.trim();
							var nombre_oportunidad = json[i].name;
							
							url_cocha = (json[i].url).replace("http://","");
							
							var enviar_cotizacion = "<a id='up_modal' data-modulo-tag='solicitudes_web' data-producto-id='"+json[i].id_producto.trim()+"' data-path='"+ruta+"'  data-pdf='"+pdf_cocha+"' data-oportunidad-id='" + data.oportunidad_id + "' data-contacto-id='" + json[i].id_lead + "' data-contacto-nombre='"+ json[i].nombre +"' onclick='modal_email_solicitudes_web(this);' style='display:none;'></a>";
							$('section#content').append(enviar_cotizacion);
							
							$('input[name=input_user_detalle_nombre]').val(json[i].nombre_usuario);
							$('input[name=input_user_detalle_email]').val(json[i].email1);
							$('input[name=input_user_detalle_telefono]').val(json[i].phone_work);
							$('input[name=input_user_detalle_cargo]').val(json[i].cargo);
							$('input[name=input_user_detalle_sucursal]').val(json[i].department);
							$('button.close').hide();
							$('button#modal_enviar_mail_solicitud_web_cancel').hide();
							$( "a#up_modal" ).trigger( "click" );
							
							$('span.contacto_nombre').html(json[i].nombre);
							setTimeout(function(){
								$.post(Configuraciones.getConfiguraciones().url_get_oportunidad_detalle_web, data).done(function(response){
									var obj = jQuery.parseJSON( response );
									// Datos contacto
									$('div.webui-popover-inner span.contacto_nombre').html(obj.nombre);  
									$('.contacto_apellido').html(obj.apellido);  
									$('.contacto_telefono').html(obj.telefono);
									$('.contacto_email').html(obj.email);
									$('.contacto_rut').html(obj.rut);
									$('.producto_solicitud').html(nombre_oportunidad);
									
									// Datos solicitud
									$('.solicitud_fecha').html( FuncionesGenerales.cambiarFormatoFecha(obj.fecha,'yyyy-MM-dd'));
									$('.solicitud_canal').html(obj.canal);
									$('.solicitud_adultos').html(obj.adulto);
											
									if(obj.edad_ninos == ''  || obj.edad_ninos == ', , ,' || obj.edad_ninos == ' , '  || obj.edad_ninos == ' , ,')
										$('.solicitud_edad_ninos').html("N/A");
									else
										$('.solicitud_edad_ninos').html(obj.edad_ninos);
									  
									$('.solicitud_fecha_asignacion').html(FuncionesGenerales.cambiarFormatoFecha(obj.fecha_asignacion,'yyyy-MM-dd'));
									 
									if (obj.fecha_flexible == "1")
										$('.solicitud_fecha_flexible').html("SI");
									else if(obj.fecha_flexible == "0") 
										$('.solicitud_fecha_flexible').html("NO");
									else
										$('.solicitud_fecha_flexible').html("SIN DATOS");
									
									$('.solicitud_habitaciones').html(obj.habitaciones);
									$('.solicitud_ninos').html(obj.ninos);
									$('.solicitud_fecha_formulacion').html(FuncionesGenerales.cambiarFormatoFecha(obj.fecha_formulario,'yyyy-MM-dd'));
									$('.solicitud_agente_viaje').html(obj.agente_viaje);
									$('.solicitud_estado').html(obj.estado);
								});
							},3000);
						}
					} 
				});
			});
		});
	  </script>
   </body>
</html>
