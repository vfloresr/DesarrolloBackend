/**
	En este código vamos a ir agregando funciones que se pueden usar en todo la aplicación
**/
var FuncionesGenerales = function() {

	// Cada vez que haga click sobre un registro de alguna tabla se almacenará acá.
	var contadores;
	var nRegistroSeleccionado;
	var nNumeroNegocioFiltro = {
		'estado' : false,
		'tabla' : '',
		'numero_negocio' : ''
	};
	var nCKEditorPlantillaCotizacion;

	return {
		
		setCKEditorPlantillaCotizacion: function(pCKEditorPlantillaCotizacion)
		{
			nCKEditorPlantillaCotizacion = pCKEditorPlantillaCotizacion;
		},
		
		getCKEditorPlantillaCotizacion : function()
		{
			return nCKEditorPlantillaCotizacion;
		},
		
		empezarLoading: function() {
			$('#preloader').show();
			$('#status').show();
		},
		
		terminarLoading: function() {
			$('#preloader').hide();
			$('#status').hide();
		},
		
		getDetalleOportunidad: function(p_oportunidad_id) {
			var obj;
			var data = { 
				oportunidad_id: p_oportunidad_id
			};
			
			$.post(Configuraciones.getConfiguraciones().url_get_oportunidad_detalle_web, data).done(function(response){
				obj = response;
			});
			
			setTimeout(function(){ 
				return obj;
			}, 3000);
		},
		
		getProcedenciaId: function(){
			var modulo = FuncionesGenerales.getModuloActual();
			
			if(modulo == Configuraciones.getConfiguraciones().solicitudes_web_tag)
			{
				return FuncionesGenerales.getRegistroSeleccionado().eq(0).children().eq(1).find('a').data('oportunidad-id');
			}	
			
			if(modulo == Configuraciones.getConfiguraciones().crosselling_tag) 
			{
				return FuncionesGenerales.getRegistroSeleccionado().eq(0).children().eq(1).find('a').data('oportunidad-id');
			}
			
			if(modulo == Configuraciones.getConfiguraciones().recompra_tag)
			{
				return FuncionesGenerales.getRegistroSeleccionado().eq(0).children().eq(1).find('a').data('oportunidad-id')
			}
			
			if(modulo == Configuraciones.getConfiguraciones().fugados_tag)
			{
				return FuncionesGenerales.getRegistroSeleccionado().eq(0).children().eq(1).find('a').data('oportunidad-id')
			}
			
			if(modulo == Configuraciones.getConfiguraciones().proximos_viajes_tag)
			{
				return FuncionesGenerales.getRegistroSeleccionado().eq(0).children().eq(2).find('a').data('tarea-id')
			}
			
			if(modulo == Configuraciones.getConfiguraciones().retorno_tag)
			{
				return FuncionesGenerales.getRegistroSeleccionado().eq(0).children().eq(1).find('a').data('tarea-id')
			}
			
			if(modulo == Configuraciones.getConfiguraciones().cumpleanos_tag)
			{
				return FuncionesGenerales.getRegistroSeleccionado().eq(0).children().eq(1).find('a').data('rut')
			}
			
			if(modulo == Configuraciones.getConfiguraciones().cartera_clientes_tag)
			{
				return FuncionesGenerales.getRegistroSeleccionado().eq(0).children().eq(1).find('a').data('cliente-id')
			}
		},
		
		validateEmail: function(el) 
		{
			// FUNCIÓN PARA VALIDAR QUE EL VALOR INGRESADO EN UN INPUT TENGA UN FORMATO DE EMAIL VÁLIDO	
            var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
			var test_result = re.test($(el).val());
			
			if($(el).val().length == 0)
			{
				$(el).removeAttr('style');
				$(el).parent().find('.required-field-modal-product').hide();
				return true;
			}
				
			if(!test_result)
			{
				$(el).css({"background-color": "rgba(224, 5, 5, 0.2)"});
				$(el).css({"margin-bottom": "20px"});
				$(el).parent().find('.required-field-modal-product').show();
				return false;
			}
			else
			{
				$(el).removeAttr('style');
				$(el).parent().find('.required-field-modal-product').hide();
				return true;
			}
        },
		
		validarDecimal: function(el) 
		{
			// FUNCION PARA VALIDAR QUE EL VALOR INGRESADO EN UN INPUT SEA SOLO NÚMEROS ENTEROS O DECIMALES
			if (event.shiftKey == true) {
				event.preventDefault();
			}
			
			if ((event.keyCode >= 48 && event.keyCode <= 57) || 
				(event.keyCode >= 96 && event.keyCode <= 105) || 
				event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 ||
				event.keyCode == 39 || event.keyCode == 46 || event.keyCode == 190) {
			} else {
				event.preventDefault();
			}

			if($(el).val().indexOf('.') !== -1 && event.keyCode == 190)
			{
				event.preventDefault();
			}
		},
		
		getFechaHoraActual: function() 
		{
			var currentdate = new Date(); 
			var datetime = currentdate.getDate() + "/"
                + (currentdate.getMonth()+1)  + "/" 
                + currentdate.getFullYear() + "  "  
                + currentdate.getHours() + ":"  
                + currentdate.getMinutes() + ":" 
                + currentdate.getSeconds();
				
			return datetime;
		},
		
		mostrarMotivoCierre : function()
		{
			var mostrar_motivo_cierre  = true;
			var modulo = FuncionesGenerales.getModuloActual();
			
			if( modulo == Configuraciones.getConfiguraciones().retorno_tag)
				mostrar_motivo_cierre  = false;
			if( modulo == Configuraciones.getConfiguraciones().recompra_tag)
				mostrar_motivo_cierre  = false;
			if( modulo == Configuraciones.getConfiguraciones().fugados_tag)
				mostrar_motivo_cierre  = false;
				
			return mostrar_motivo_cierre;
		},
		
		mostrarVuelosNegocioGanado : function()
		{
			var mostrar_vuelo  = false;
			var modulo = FuncionesGenerales.getModuloActual();
			
			if( modulo == Configuraciones.getConfiguraciones().fugados_tag)
				mostrar_vuelo  = true;
			if( modulo == Configuraciones.getConfiguraciones().recompra_tag)
				mostrar_vuelo  = true;
				
			return mostrar_vuelo;
		},
		
		getTablaModuloActual : function() 
		{
			if($('table#abierto_table_solicitudes_web').is(':visible'))
				return 'abierto_table_solicitudes_web';
				
			if($('table#negociacion_table_solicitudes_web').is(':visible'))
				return 'negociacion_table_solicitudes_web';	
			
			if($('table#cerrados_table_solicitudes_web').is(':visible'))
				return 'cerrados_table_solicitudes_web';
				
			if($('table#abierto_table').is(':visible'))
				return 'abierto_table';
				
			if($('table#negociacion_table').is(':visible'))
				return 'negociacion_table';	
			
			if($('table#cerrados_table').is(':visible'))
				return 'cerrados_table';
				
			if($('table#abierto_table_recompra').is(':visible'))
				return 'abierto_table_recompra';
				
			if($('table#negociacion_table_recompra').is(':visible'))
				return 'negociacion_table_recompra';	
			
			if($('table#cerrados_table_recompra').is(':visible'))
				return 'cerrados_table_recompra';
				
			if($('table#abierto_table_fugados').is(':visible'))
				return 'abierto_table_fugados';
				
			if($('table#negociacion_table_fugados').is(':visible'))
				return 'negociacion_table_fugados';	
			
			if($('table#cerrados_table_fugados').is(':visible'))
				return 'cerrados_table_fugados';
				
			if($('table#abierto_table_proximos_viajes').is(':visible'))
				return 'abierto_table_proximos_viajes';
				
			if($('table#negociacion_table_proximos_viajes').is(':visible'))
				return 'negociacion_table_proximos_viajes';	
			
			if($('table#cerrados_table_proximos_viajes').is(':visible'))
				return 'cerrados_table_proximos_viajes';
			
			if($('table#abierto_table_retorno').is(':visible'))
				return 'abierto_table_retorno';
				
			if($('table#negociacion_table_retorno').is(':visible'))
				return 'negociacion_table_retorno';	
			
			if($('table#cerrados_table_retorno').is(':visible'))
				return 'cerrados_table_retorno';
				
		},
		
		getModuloActual: function()
		{
			//SOLICITUD WEB
			if($('table#abierto_table_solicitudes_web').is(':visible')||$('table#cerrados_table_solicitudes_web').is(':visible')||$('table#negociacion_table_solicitudes_web').is(':visible'))
				return Configuraciones.getConfiguraciones().solicitudes_web_tag;
			
			//CROSSELLING
			if($('table#abierto_table').is(':visible')||$('table#cerrados_table').is(':visible')||$('table#negociacion_table').is(':visible'))
				return Configuraciones.getConfiguraciones().crosselling_tag;
				
			//RECOMPRA
			if($('table#abierto_table_recompra').is(':visible')||$('table#cerrados_table_recompra').is(':visible')||$('table#negociacion_table_recompra').is(':visible'))
				return Configuraciones.getConfiguraciones().recompra_tag;
			
			//FUGADOS
			if($('table#abierto_table_fugados').is(':visible')||$('table#cerrados_table_fugados').is(':visible')||$('table#negociacion_table_fugados').is(':visible'))
				return Configuraciones.getConfiguraciones().fugados_tag;
			
			//PROXIMOS VIAJES
			if($('table#abierto_table_proximos_viajes').is(':visible')||$('table#cerrados_table_proximos_viajes').is(':visible')||$('table#negociacion_table_proximos_viajes').is(':visible'))
				return Configuraciones.getConfiguraciones().proximos_viajes_tag;
			
			//RETORNO
			if($('table#abierto_table_retorno').is(':visible')||$('table#cerrados_table_retorno').is(':visible')||$('table#negociacion_table_retorno').is(':visible'))
				return Configuraciones.getConfiguraciones().retorno_tag;
			
			//CUMPLEANIOS
			if($('table#table_cumpleanios').is(':visible'))
				return Configuraciones.getConfiguraciones().cumpleanos_tag;
			
			//CARTERA DE CLIENTES
			if($('table#table_pasajeros').is(':visible') || $('table#table_compradores').is(':visible'))
				return Configuraciones.getConfiguraciones().cartera_clientes_tag;
				
			//CUMPLEANIOS
			if($('table#table_buscador').is(':visible'))
			{
				// return Configuraciones.getConfiguraciones().buscador_tag;
				var nRow = FuncionesGenerales.getRegistroSeleccionado();
				var modulo_perteneciente = $(nRow).children().eq(1).text();
				if(modulo_perteneciente == "crosselling")
					return Configuraciones.getConfiguraciones().crosselling_tag;
				else if(modulo_perteneciente == "Recompra")
					return Configuraciones.getConfiguraciones().recompra_tag;
				else if(modulo_perteneciente == "Fugados")
					return Configuraciones.getConfiguraciones().fugados_tag;
				else if(modulo_perteneciente == "Proximos")
					return Configuraciones.getConfiguraciones().proximos_viajes_tag;
				else if(modulo_perteneciente == "Retornos")
					return Configuraciones.getConfiguraciones().retorno_tag;
			}
		},
		
		getPlantillaPorDefector: function() 
		{
			var modulo = this.getModuloActual();
		
			if(modulo == Configuraciones.getConfiguraciones().solicitudes_web_tag)
			{
				return Configuraciones.getConfiguraciones().plantilla_default_solicitudes_web_tag
			}	
			
			if(modulo == Configuraciones.getConfiguraciones().crosselling_tag) 
			{
				return Configuraciones.getConfiguraciones().plantilla_default_crosselling_tag
			}
			
			if(modulo == Configuraciones.getConfiguraciones().recompra_tag)
			{
				return Configuraciones.getConfiguraciones().plantilla_default_recompra_tag
			}
			
			if(modulo == Configuraciones.getConfiguraciones().fugados_tag)
			{
				return Configuraciones.getConfiguraciones().plantilla_default_fugados_tag
			}
			
			if(modulo == Configuraciones.getConfiguraciones().proximos_viajes_tag)
			{
				return Configuraciones.getConfiguraciones().plantilla_default_proximos_viajes_tag
			}
			
			if(modulo == Configuraciones.getConfiguraciones().retorno_tag)
			{
				return Configuraciones.getConfiguraciones().plantilla_default_retorno_tag
			}
			
			if(modulo == Configuraciones.getConfiguraciones().cumpleanos_tag)
			{
				return Configuraciones.getConfiguraciones().plantilla_default_cumpleanos_tag
			}
			
			if(modulo == Configuraciones.getConfiguraciones().cartera_clientes_tag)
			{
				return Configuraciones.getConfiguraciones().plantilla_default_cartera_clientes_tag
			}
		},
		
		establecerModuloPrincipal : function()
		{
			if(Configuraciones.getConfiguraciones().privilegio_solcitides_web == "1")
				$('a#solicitudes_section_menu').addClass('initial-click');
			else if(Configuraciones.getConfiguraciones().privilegio_croseling == "1")
				$('a#cross_selling_section_menu').addClass('initial-click');
			else if(Configuraciones.getConfiguraciones().privilegio_recompra == "1")
				$('a#recompra_section_menu').addClass('initial-click');
			else if(Configuraciones.getConfiguraciones().privilegio_fugados == "1")
				$('a#fugados_section_menu').addClass('initial-click');
			else if(Configuraciones.getConfiguraciones().privilegio_proximos_viajes == "1")
				$('a#proximos_viajes_section_menu').addClass('initial-click');
			else if(Configuraciones.getConfiguraciones().privilegio_retornos == "1")
				$('a#retorno_section_menu').addClass('initial-click');
			else if(Configuraciones.getConfiguraciones().privilegio_cumpleanio == "1")
				$('a#cumpleanios_section_menu').addClass('initial-click');
			else if(Configuraciones.getConfiguraciones().privilegio_cartera_cliente == "1")
				$('a#clientes_section_menu').addClass('initial-click');
				
			$('.initial-click ').click();
		},
		
		establecerProcedenciaMail : function(datos)
		{
			var modulo = this.getModuloActual();
		
			if(modulo == Configuraciones.getConfiguraciones().solicitudes_web_tag)
			{
				$('input[name=mail_modal_procedencia_id]').val(datos.id_oportunidad);
				$('input[name=mail_modal_procedencia]').val(Configuraciones.getConfiguraciones().tag_procedencia_oportunidad);
			}	
			
			if(modulo == Configuraciones.getConfiguraciones().crosselling_tag) 
			{
				$('input[name=mail_modal_procedencia_id]').val(datos.id_oportunidad);
				$('input[name=mail_modal_procedencia]').val(Configuraciones.getConfiguraciones().tag_procedencia_oportunidad);
			}
			
			if(modulo == Configuraciones.getConfiguraciones().recompra_tag)
			{
				$('input[name=mail_modal_procedencia_id]').val(datos.id_oportunidad);
				$('input[name=mail_modal_procedencia]').val(Configuraciones.getConfiguraciones().tag_procedencia_oportunidad);
			}
			
			if(modulo == Configuraciones.getConfiguraciones().fugados_tag)
			{
				$('input[name=mail_modal_procedencia_id]').val(datos.id_oportunidad);
				$('input[name=mail_modal_procedencia]').val(Configuraciones.getConfiguraciones().tag_procedencia_oportunidad);
			}
			
			if(modulo == Configuraciones.getConfiguraciones().proximos_viajes_tag)
			{
				$('input[name=mail_modal_procedencia_id]').val(datos.id_tarea);
				$('input[name=mail_modal_procedencia]').val(Configuraciones.getConfiguraciones().tag_procedencia_tarea);
			}
			
			if(modulo == Configuraciones.getConfiguraciones().retorno_tag)
			{
				$('input[name=mail_modal_procedencia_id]').val(datos.id_tarea);
				$('input[name=mail_modal_procedencia]').val(Configuraciones.getConfiguraciones().tag_procedencia_tarea);
			}
			
			if(modulo == Configuraciones.getConfiguraciones().cumpleanos_tag)
			{
				$('input[name=mail_modal_procedencia_id]').val(datos.rut);
				$('input[name=mail_modal_procedencia]').val(Configuraciones.getConfiguraciones().tag_procedencia_contacto);
			}
		},
		
		setRegistroSeleccionado : function(pRegistro)
		{
			nRegistroSeleccionado = pRegistro;
		},
		
		getRegistroSeleccionado : function()
		{
			return nRegistroSeleccionado;
		},
		
		refrescarRegistroTabla : function() {
			var modulo = this.getModuloActual();
			var tabla = this.getTablaModuloActual();
			
			if(modulo == Configuraciones.getConfiguraciones().solicitudes_web_tag)
			{
				if(tabla == 'abierto_table_solicitudes_web')
					$.fn.dataTableExt.oApi.fnActualizarRegistroTablaSolicitudWebNuevas();
				if(tabla == 'negociacion_table_solicitudes_web')
					$.fn.dataTableExt.oApi.fnActualizarRegistroTablaSolicitudWebPendientes();
				if(tabla == 'cerrados_table_solicitudes_web')
					$.fn.dataTableExt.oApi.fnActualizarRegistroTablaSolicitudWebCerrados();
			}
			else if(modulo == Configuraciones.getConfiguraciones().crosselling_tag)
			{
				if(tabla == 'abierto_table')
					$.fn.dataTableExt.oApi.fnActualizarRegistroTablaCrossSellingNuevas();
				if(tabla == 'negociacion_table')
					$.fn.dataTableExt.oApi.fnActualizarRegistroTablaCrossSellingPendientes();
				if(tabla == 'cerrados_table')
					$.fn.dataTableExt.oApi.fnActualizarRegistroTablaCrossSellingCerrados();
			}
			else if(modulo == Configuraciones.getConfiguraciones().recompra_tag)
			{
				if(tabla == 'abierto_table_recompra')
					$.fn.dataTableExt.oApi.fnActualizarRegistroTablaRecompraNuevas();
				if(tabla == 'negociacion_table_recompra')
					$.fn.dataTableExt.oApi.fnActualizarRegistroTablaRecompraPendientes();
				if(tabla == 'cerrados_table_recompra')
					$.fn.dataTableExt.oApi.fnActualizarRegistroTablaRecompraCerrados();
			}
			else if(modulo == Configuraciones.getConfiguraciones().fugados_tag)
			{
				if(tabla == 'abierto_table_fugados')
					$.fn.dataTableExt.oApi.fnActualizarRegistroTablaFugadosNuevas();
				if(tabla == 'negociacion_table_fugados')
					$.fn.dataTableExt.oApi.fnActualizarRegistroTablaFugadosPendientes();
				if(tabla == 'cerrados_table_fugados')
					$.fn.dataTableExt.oApi.fnActualizarRegistroTablaFugadosCerrados();
			}
			else if(modulo == Configuraciones.getConfiguraciones().proximos_viajes_tag)
			{
				if(tabla == 'abierto_table_proximos_viajes')
					$.fn.dataTableExt.oApi.fnActualizarRegistroTablaProximosViajesNuevas();
				if(tabla == 'negociacion_table_proximos_viajes')
					$.fn.dataTableExt.oApi.fnActualizarRegistroTablaProximosViajesPendientes();
				if(tabla == 'cerrados_table_proximos_viajes')
					$.fn.dataTableExt.oApi.fnActualizarRegistroTablaProximosViajesCerrados();
			}
			else if(modulo == Configuraciones.getConfiguraciones().retorno_tag)
			{
				if(tabla == 'abierto_table_retorno')
					$.fn.dataTableExt.oApi.fnActualizarRegistroTablaRetornoNuevas();
				if(tabla == 'negociacion_table_retorno')
					$.fn.dataTableExt.oApi.fnActualizarRegistroTablaRetornoPendientes();
				if(tabla == 'cerrados_table_retorno')
					$.fn.dataTableExt.oApi.fnActualizarRegistroTablaRetornoCerrados();
			}				
		},
		
		refrescarTabla: function() 
		{
			var modulo = this.getModuloActual();
		
			//REFRESCAR TABLA SOLICITUD WEB Y CONTADOR
			if(modulo == Configuraciones.getConfiguraciones().solicitudes_web_tag)
			{
				$('#solicitudes_section').callMeLoad();
				this.refrescarContadores(false,modulo);
			}	
			
			//REFRESCAR TABLA CROSSELLING Y CONTADOR
			if(modulo == Configuraciones.getConfiguraciones().crosselling_tag) 
			{
				$('#cross_selling_section').callMeLoad();
				this.refrescarContadores(false,modulo);
			}
			
			//REFRESCAR TABLA RECOMPRA Y CONTADOR
			if(modulo == Configuraciones.getConfiguraciones().recompra_tag)
			{
				$('#recompra_section').callMeLoad();
				this.refrescarContadores(false,modulo);
			}
			
			//REFRESCAR TABLA FUGADOS Y CONTADOR
			if(modulo == Configuraciones.getConfiguraciones().fugados_tag)
			{
				$('#fugados_section').callMeLoad();
				this.refrescarContadores(false,modulo);
			}
			
			//REFRESCAR TABLA PROXIMOS VIAJES Y CONTADOR
			if(modulo == Configuraciones.getConfiguraciones().proximos_viajes_tag)
			{
				$('#proximos_viajes_section').callMeLoad();
				this.refrescarContadores(false,modulo);
			}
			
			//REFRESCAR TABLA RETORNO Y CONTADOR
			if(modulo == Configuraciones.getConfiguraciones().retorno_tag)
			{
				$('#retorno_section').callMeLoad();
				this.refrescarContadores(false,modulo);
			}
			
			//REFRESCAR TABLA CUMPLEAÑOS Y CONTADOR
			if(modulo == Configuraciones.getConfiguraciones().cumpleanos_tag)
			{
				$('#cumpleanios_section').callMeLoad();
				this.refrescarContadores(false,modulo);
			}
			
			//REFRESCAR TABLA CARTERA DE CLIENTES
			if(modulo == Configuraciones.getConfiguraciones().cartera_clientes_tag)
			{
				$('#clientes_section').callMeLoad();
				this.refrescarContadores(false,Configuraciones.getConfiguraciones().cumpleanos_tag);
			}
		},		
		
		actualizarTabla: function()
		{
			var modulo = this.getModuloActual();
			var tabla = this.getTablaModuloActual();
			
			if(modulo == Configuraciones.getConfiguraciones().solicitudes_web_tag)
			{
				if(tabla == 'abierto_table_solicitudes_web')
				{
					FuncionesGenerales.refrescarTabla();
				}
				else if(tabla == 'negociacion_table_solicitudes_web')
				{
					FuncionesGenerales.refrescarRegistroTabla();
				}
			}
		},
		
		cargarContadores : function() 
		{
			$.get(Configuraciones.getConfiguraciones().url_cargar_contadores,function(data)
			{
				var data = JSON.parse(data);
				if(data.prototipo.cantidad_cross > 0) {
					$('#cross_selling_count').html(data.prototipo.cantidad_cross);
					$('#cross_selling_count').show();
				} else {
					$('#cross_selling_count').hide();
				}
				
				if(data.prototipo.cantidad_recompra > 0) {
					$('#recompra_count').html(data.prototipo.cantidad_recompra);
					$('#recompra_count').show();
				} else {
					$('#recompra_count').hide();
				}
				
				if(data.prototipo.cantidad_fugados > 0) {
					$('#fugados_count').html(data.prototipo.cantidad_fugados);
					$('#fugados_count').show();
				} else {
					$('#fugados_count').hide();
				}
				
				if(data.prototipo.cantidad_proximos > 0) {
					$('#proximos_viajes_count').html(data.prototipo.cantidad_proximos);
					$('#proximos_viajes_count').show();
				} else {
					$('#proximos_viajes_count').hide();
				}
				
				if(data.prototipo.cantidad_retorno > 0) {
					$('#retorno_count').html(data.prototipo.cantidad_retorno);
					$('#retorno_count').show();
				} else {
					$('#retorno_count').hide();
				}
				
				if(data.prototipo.cantidad_cumple_mes > 0) {
					$('#cumpleanios_count').html(data.prototipo.cantidad_cumple_mes);
					$('#cumpleanios_count').show();
				} else {
					$('#cumpleanios_count').hide();
				}
				
				if(data.prototipo.cantidad_web_nuevas > 0) {
					$('#solicitudes_web_count').html(data.prototipo.cantidad_web_nuevas);
					$('#solicitudes_web_count').show();
				} else {
					$('#solicitudes_web_count').hide();
				}
			});
		},
		
		refrescarContadores: function (refrescar_todo, modulo)
		{
			if( refrescar_todo || modulo == Configuraciones.getConfiguraciones().solicitudes_web_tag)
			{
				$.get(Configuraciones.getConfiguraciones().url_cantidad_solicitudes_web,function(data){
					if(data > 0)
					{
						$('#solicitudes_web_count').html(data);
						$('#solicitudes_web_count').show();
					}
					else
					{
						$('#solicitudes_web_count').hide();
					}
				});
			}
			
			if( refrescar_todo || modulo == Configuraciones.getConfiguraciones().crosselling_tag)
			{
				$.get(Configuraciones.getConfiguraciones().url_cantidad_cross_selling,function(data){
					var data = JSON.parse(data);
				//alert('data:'+ data);
					if(data > 0)
					{
						$('#cross_selling_count').html(data);
						$('#cross_selling_count').show();
					}
					else
					{
						$('#cross_selling_count').hide();
					}
				});
			}
			
			if( refrescar_todo || modulo == Configuraciones.getConfiguraciones().recompra_tag)
			{		
				$.get(Configuraciones.getConfiguraciones().url_cantidad_recompra,function(data)
				{
					var data = JSON.parse(data);
					if(data > 0)
					{
						$('#recompra_count').html(data);
						$('#recompra_count').show();
					}
					else
					{
						$('#recompra_count').hide();
					}
				});
			}
			
			if( refrescar_todo || modulo == Configuraciones.getConfiguraciones().fugados_tag)
			{
				$.get(Configuraciones.getConfiguraciones().url_cantidad_fugados,function(data){
					
					var data = JSON.parse(data);
					if(data > 0)
					{
						$('#fugados_count').html(data);
						$('#fugados_count').show();
					}
					else
					{
						
						$('#fugados_count').hide();
					}
				});
			}
			
			if( refrescar_todo || modulo == Configuraciones.getConfiguraciones().proximos_viajes_tag)
			{
				$.get(Configuraciones.getConfiguraciones().url_cantidad_proximos_viajes,function(data){
					if(data > 0)
					{
						$('#proximos_viajes_count').html(data);
						$('#proximos_viajes_count').show();
					}
					else
					{
						$('#proximos_viajes_count').hide();
					}
				});
			}
				
			if( refrescar_todo || modulo == Configuraciones.getConfiguraciones().retorno_tag)
			{	
				$.get(Configuraciones.getConfiguraciones().url_cantidad_retorno,function(data){
					if(data > 0)
					{
						$('#retorno_count').html(data);
						$('#retorno_count').show();
					}
					else
					{
						$('#retorno_count').hide();
					}
				});
			}
			
			if( refrescar_todo || modulo == Configuraciones.getConfiguraciones().cumpleanos_tag)
			{
				$.get(Configuraciones.getConfiguraciones().url_cantidad_cumpleanos,function(data){
					if(data > 0)
					{
						$('#cumpleanios_count').html(data);
						$('#cumpleanios_count').show();
					}
					else
					{
						$('#cumpleanios_count').hide();
					}
				});
			}
		},
		
		setTituloModalCierre: function(el)
		{
			var modulo = this.getModuloActual();	
			
			if(modulo == Configuraciones.getConfiguraciones().crosselling_tag)
			{
				$(".title_ganado_modal").html('Cierre Cross Selling<span id="perdido_title_number"> ('+ el.id_negocio +') </span>');
			}
			else if(modulo == Configuraciones.getConfiguraciones().recompra_tag)
			{
				$(".title_ganado_modal").html('Cierre Recompra<span id="perdido_title_number"> ('+ el.id_negocio +') </span>');
			}
			else if(modulo == Configuraciones.getConfiguraciones().fugados_tag)
			{
				$(".title_ganado_modal").html('Cierre Fugado<span id="perdido_title_number"> ('+ el.id_negocio +') </span>');
			}
			else if(modulo == Configuraciones.getConfiguraciones().proximos_viajes_tag)
			{
				$(".title_ganado_modal").html('Cierre Próximo Viajes<span id="perdido_title_number"> ('+ el.id_negocio +') </span>');
			}
			else if(modulo == Configuraciones.getConfiguraciones().retorno_tag)
			{
				$(".title_ganado_modal").html('Cierre Retorno<span id="perdido_title_number"> ('+ el.id_negocio +') </span>');
			}
		},
		
		cargarConversacionesChat: function() 
		{
			if($(".scroller-content").length > 0)
			{
				$(".scroller-content").scrollTop($('#nav-tab2')[0].scrollHeight);
				$.get( Configuraciones.getConfiguraciones().url_get_msj, { assigned_user_id : '1', page: "1", limit : "5" } ).done(function(data) 
				{
					var mensajes = ''
					var avatar_usuario = Configuraciones.getConfiguraciones().url_imagenes_usuarios+$('input[name=input_username_detalle_apellido]').val()+'.jpg'
					var avatar_administrador = Configuraciones.getConfiguraciones().url_imagenes_administrador;
					
					$.each(JSON.parse(data), function(i,e)
					{
						if(e.tipo == "R")
							var plantilla = '<div class="media"><div class="media-left"><a href="#"><img class="media-object" alt="64x64" src="'+avatar_administrador+'"></a></div> <div class="media-body"><span class="media-status online"></span> <h5 class="media-heading">'+ e.nombre +' <small> - '+ e.fecha_creacion +'</small></h5> ' + e.mensaje + ' </div></div>';
						else if(e.tipo == "E")
							var plantilla = '<div class="media"><div class="media-body"> <span class="media-status offline"></span> <h5 class="media-heading">'+ e.nombre +'  <small> - '+ e.fecha_creacion +'</small> </h5> ' + e.mensaje + ' </div> <div class="media-right"> <a href="#"> <img class="media-object" alt="64x64" src="'+avatar_usuario+'"> </a> </div> </div>';
						
						mensajes += plantilla;
						$('#nav-tab2').html(mensajes);
						$(".scroller-content").scrollTop($('#nav-tab2')[0].scrollHeight);
					});
				});
			}
		},
		
		cargarCantidadMensajesNuevos: function() 
		{
			$.get( Configuraciones.getConfiguraciones().url_get_cantidad_msj_nuevo).done(function(data) 
			{
				var respuesta = JSON.parse(data);
				var title = $('title').html();
				
				if(respuesta > 0)
				{
					$('#cantidad_msj_nuevo').show();
					$('#cantidad_msj_nuevo').html(respuesta);
					$('title').html("("+respuesta+") "+title);
				}
				else
				{	
					$('title').html(title);
					$('#cantidad_msj_nuevo').hide();
				}
			});
		},
		
		cambiarFormatoFecha: function(fecha_s, formato_s){
			if(fecha_s != "")
				return Date.parse(fecha_s).toString(formato_s);
			else
				return fecha_s;
		}, 
		
		setNumeroNegocioFiltro : function(pNumeroNegocioFiltro,pTablaEstado) 
		{
			nNumeroNegocioFiltro = {
				'estado' : true,
				'tabla' : pTablaEstado,
				'numero_negocio' : pNumeroNegocioFiltro,
				'cantidad' : 0
			};
		},
		
		setLimpiarNumeroNegocioFiltro : function(pNumeroNegocioFiltro) 
		{
			nNumeroNegocioFiltro = {
				'estado' : false,
				'tabla' : '',
				'numero_negocio' : '',
				'cantidad' : 0
			};
		},
		
		getNumeroNegocioFiltro : function(pNumeroNegocioFiltro) 
		{
			return nNumeroNegocioFiltro;
		},
		
		filtrarTablasDesdeBuscador : function()
		{
			var modulo = this.getModuloActual();
			if(FuncionesGenerales.getNumeroNegocioFiltro().estado)
			{
				if(modulo == Configuraciones.getConfiguraciones().crosselling_tag)
				{
					if(FuncionesGenerales.getNumeroNegocioFiltro().tabla == Configuraciones.getConfiguraciones().tablas_nuevas_tag)
					{
						$('#cross_selling_tab li:eq(0) a').tab('show');
						CrossSellingTabla.cargarTablaNuevas();
						CrossSellingTabla.abierto.fnFilter(FuncionesGenerales.getNumeroNegocioFiltro().numero_negocio);
					}
					else if(FuncionesGenerales.getNumeroNegocioFiltro().tabla == Configuraciones.getConfiguraciones().tablas_cerradas_tag)		
					{
						$('#cross_selling_tab li:eq(2) a').tab('show');
						CrossSellingTabla.cargarTablaCerradas();
						CrossSellingTabla.cerrado.fnFilter(FuncionesGenerales.getNumeroNegocioFiltro().numero_negocio);
					}
				}
				else if(modulo == Configuraciones.getConfiguraciones().recompra_tag)
				{
					if(FuncionesGenerales.getNumeroNegocioFiltro().tabla == Configuraciones.getConfiguraciones().tablas_nuevas_tag)
					{
						$('#recompra_tab li:eq(0) a').tab('show');
						RecompraTabla.cargarTablaNuevas();
						RecompraTabla.abierto_recompra.fnFilter(FuncionesGenerales.getNumeroNegocioFiltro().numero_negocio);
					}
					else if(FuncionesGenerales.getNumeroNegocioFiltro().tabla == Configuraciones.getConfiguraciones().tablas_cerradas_tag)		
					{	
						$('#recompra_tab li:eq(2) a').tab('show');
						RecompraTabla.cargarTablaCerradas();
						RecompraTabla.cerrado_recompra.fnFilter(FuncionesGenerales.getNumeroNegocioFiltro().numero_negocio);
					}
				}
				else if(modulo == Configuraciones.getConfiguraciones().fugados_tag)
				{
					if(FuncionesGenerales.getNumeroNegocioFiltro().tabla == Configuraciones.getConfiguraciones().tablas_nuevas_tag)
					{
						$('#fugados_tab li:eq(0) a').tab('show');
						FugadosTabla.cargarTablaNuevas();
						FugadosTabla.abierto_fugados.fnFilter(FuncionesGenerales.getNumeroNegocioFiltro().numero_negocio);
					}
					else if(FuncionesGenerales.getNumeroNegocioFiltro().tabla == Configuraciones.getConfiguraciones().tablas_cerradas_tag)	
					{
						$('#fugados_tab li:eq(2) a').tab('show');
						FugadosTabla.cargarTablaCerradas();
						FugadosTabla.cerrado_fugados.fnFilter(FuncionesGenerales.getNumeroNegocioFiltro().numero_negocio);
					}
				}
				else if(modulo == Configuraciones.getConfiguraciones().proximos_viajes_tag)
				{
					if(FuncionesGenerales.getNumeroNegocioFiltro().tabla == Configuraciones.getConfiguraciones().tablas_nuevas_tag)
					{
						$('#proximos_viajes_tab li:eq(0) a').tab('show');
						ProximosViajesTabla.cargarTablaNuevas();
						ProximosViajesTabla.abierto_viajes.fnFilter(FuncionesGenerales.getNumeroNegocioFiltro().numero_negocio);
					}
					else if(FuncionesGenerales.getNumeroNegocioFiltro().tabla == Configuraciones.getConfiguraciones().tablas_cerradas_tag)		
					{	
						$('#proximos_viajes_tab li:eq(2) a').tab('show');
						ProximosViajesTabla.cargarTablaCerradas();
						ProximosViajesTabla.cerrado_viajes.fnFilter(FuncionesGenerales.getNumeroNegocioFiltro().numero_negocio);
					}
				}
				else if(modulo == Configuraciones.getConfiguraciones().retorno_tag)
				{
					if(FuncionesGenerales.getNumeroNegocioFiltro().tabla == Configuraciones.getConfiguraciones().tablas_nuevas_tag)
					{
						$('#retorno_tab li:eq(0) a').tab('show');
						RetornoTabla.cargarTablaNuevas();
						RetornoTabla.abierto_retorno.fnFilter(FuncionesGenerales.getNumeroNegocioFiltro().numero_negocio);
					}
					else if(FuncionesGenerales.getNumeroNegocioFiltro().tabla == Configuraciones.getConfiguraciones().tablas_cerradas_tag)		
					{	
						$('#retorno_tab li:eq(2) a').tab('show');
						RetornoTabla.cargarTablaCerradas();
						RetornoTabla.cerrado_retorno.fnFilter(FuncionesGenerales.getNumeroNegocioFiltro().numero_negocio);
					}
				}
				
				this.setLimpiarNumeroNegocioFiltro();
			}
			else
			{
				if(modulo == Configuraciones.getConfiguraciones().crosselling_tag)
				{
					$('#cross_selling_tab li:eq(0) a').tab('show');
					// CrossSellingTabla.abierto.fnFilter('');
					// CrossSellingTabla.cerrado.fnFilter('');
					CrossSellingTabla.cargarTablaNuevas();
				}
				else if(modulo == Configuraciones.getConfiguraciones().recompra_tag)
				{
					$('#recompra_tab li:eq(0) a').tab('show');
					// RecompraTabla.abierto_recompra.fnFilter('');
					// RecompraTabla.cerrado_recompra.fnFilter('');
					RecompraTabla.cargarTablaNuevas();
				}
				else if(modulo == Configuraciones.getConfiguraciones().fugados_tag)
				{
					$('#fugados_tab li:eq(0) a').tab('show');
					// FugadosTabla.abierto_fugados.fnFilter('');
					// FugadosTabla.cerrado_fugados.fnFilter('');
					FugadosTabla.cargarTablaNuevas();
				}
				else if(modulo == Configuraciones.getConfiguraciones().proximos_viajes_tag)
				{
					$('#proximos_viajes_tab li:eq(0) a').tab('show');
					// ProximosViajesTabla.abierto_viajes.fnFilter('');
					// ProximosViajesTabla.cerrado_viajes.fnFilter('');
					ProximosViajesTabla.cargarTablaNuevas();
				}
				else if(modulo == Configuraciones.getConfiguraciones().retorno_tag)
				{
					$('#retorno_tab li:eq(0) a').tab('show');
					// RetornoTabla.abierto_retorno.fnFilter('');
					// RetornoTabla.cerrado_retorno.fnFilter('');
					RetornoTabla.cargarTablaNuevas();
				}
			}
		}
	};
}();