var Mensajes = function() {
	
	var limit;
	var pagina_actual;
	var existe_mas;

	var setLimit = function(p_limit) {
		this.limit = p_limit;
	};
	
	var getLimit = function() {
		return this.limit;
	};
	
	var setPaginaActual = function(p_pagina_actual) {
		this.pagina_actual = p_pagina_actual;
	};
	
	var getPaginaActual = function() {
		return (this.pagina_actual+1);
	};
		
	return {
		
		init: function() {
			setLimit(Configuraciones.getConfiguraciones().limit_mensajes);
			setPaginaActual(0);
			Mensajes.cargarCantidadMensajesNuevos();
			Mensajes.existe_mas = true;
			
			setInterval(function(){
				Mensajes.cargarCantidadMensajesNuevos();
			},Configuraciones.getConfiguraciones().intervalo_refrescar_cantidad_mensajes);
			
			$('#nav-tab2').html('');
        },
		
		cargarConversacionesChat : function() {
		
			if(!Mensajes.existe_mas)
			{
				$('.cargar-conversacion').first().html('<span style="margin-left: 30%;">Fin de la conversación</span>');
				return false;
			}
			
			var data = { 
				assigned_user_id : '1', 
				page: getPaginaActual(), 
				limit : getLimit() 
			};

			$('.cargar-conversacion').first().html('<span style="margin-left: 35%;"><i class="fa fa-spinner fa-spin"></i> Cargando...</span>');
			$.get( Configuraciones.getConfiguraciones().url_get_msj, data).done(function(data) 
			{
				$('.cargar-conversacion').first().html('');
				var mensajes = '<div class="cargar-conversacion"><a style="margin-left: 35%;" href="javascript:void(0);" class="cargar-mas">Cargar Mas</a></div>'
				var avatar_usuario = Configuraciones.getConfiguraciones().url_imagenes_usuarios+$('input[name=input_username_detalle_apellido]').val()+'.jpg'
				var avatar_administrador = Configuraciones.getConfiguraciones().url_imagenes_administrador;
				
				var data_json = JSON.parse(data);
				
				if(data_json.length == Configuraciones.getConfiguraciones().limit_mensajes)
				{
					setPaginaActual(getPaginaActual());
				}
				else
				{
					Mensajes.existe_mas = false;
					setPaginaActual(getPaginaActual());
				}
				
				for(i = data_json.length-1; i > -1; i = i-1){
					var fecha = data_json[i].date_entered;
					var nombre = data_json[i].name;
					var mensaje = data_json[i].description;
				
					if(data_json[i].tipo == "R")
						var plantilla = '<div class="media"><div class="media-left"><a href="#"><img class="media-object" alt="64x64" src="'+avatar_administrador+'"></a></div> <div class="media-body"><span class="media-status online"></span> <h5 class="media-heading">'+ nombre +' <small> - '+ fecha +'</small></h5> ' + mensaje + ' </div></div>';
					else if(data_json[i].tipo == "E")
						var plantilla = '<div class="media"><div class="media-body"> <span class="media-status offline"></span> <h5 class="media-heading">'+ nombre +'  <small> - '+ fecha +'</small> </h5> ' + mensaje + ' </div> <div class="media-right"> <a href="#"> <img class="media-object" alt="64x64" src="'+avatar_usuario+'"> </a> </div> </div>';
					
					mensajes += plantilla;
				}
				
				$('#nav-tab2').prepend(mensajes);
				
				if((getPaginaActual()-1) == 1)
					$(".scroller-content").scrollTop($('#nav-tab2')[0].scrollHeight);
					
				$('.cargar-mas').click(function() {
				    if(Mensajes.existe_mas)
					{
						Mensajes.cargarConversacionesChat();
					}
					else
					{
						$('.cargar-conversacion').first().html('<span style="margin-left: 30%;">Fin de la conversación</span>');
						return false;
					}
				});

			});
		},
		
		cargarCantidadMensajesNuevos : function() {
			$.get( Configuraciones.getConfiguraciones().url_get_cantidad_msj_nuevo).done(function(data) 
			{
				var respuesta = JSON.parse(data);
				
				if(respuesta > 0)
				{
					$('#cantidad_msj_nuevo').show();
					$('#cantidad_msj_nuevo').html(respuesta);
					$('title').html("("+respuesta+") Inicio | Cocha CRM");
				}
				else
				{	
					$('title').html("Inicio | Cocha CRM");
					$('#cantidad_msj_nuevo').hide();
				}
			});
		}
	};
}();