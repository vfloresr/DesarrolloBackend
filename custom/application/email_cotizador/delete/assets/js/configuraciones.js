/**
Este código nos permite obtener las configuraciones establecidas para toda la aplicación desde el archivo ubicado en config/configuraciones
**/

var Configuraciones = function() {
	
	var url_configuraciones;
	
	var configuraciones;
	
	var cargarConfiguraciones = function(value) {
		var data = {
			v_class : 'configuraciones' 
		}
		url_configuraciones = "config/configuraciones.php";
		
		$.get( url_configuraciones, data ).done(function(respuesta) {
			configuraciones = respuesta;
			
			if(value)
			{
				FuncionesGenerales.establecerModuloPrincipal();
				FuncionesGenerales.cargarContadores();
				Mensajes.init();
				NuevasSolicitudes.init();
				var data = {
					'update' : false,
					'estado' : 1
				}
				//Consulta el estado del ejecutivo
				$.post(Configuraciones.getConfiguraciones().url_disponibilidad_ejecutivo,data).done(function(response){
					var v_state = (response.trim() == "1") ? true : false;
					$("[name='check_disponibilidad']").bootstrapSwitch({
					  size: "normal",
					  state: v_state,
					  onSwitchChange: function(event, state) {
						var data = {
							'update' : true,
							'estado' : (state) ? 1 : 0
						};
					  
						$.post(Configuraciones.getConfiguraciones().url_disponibilidad_ejecutivo, data).done(function(response){
							//Hacer algo.
							var check = 1;
						
						});
					  }
					});
				});
			}
		});
	};
	
	
	return {
		init: function(value) {
			cargarConfiguraciones(value);
        },
		
		getConfiguraciones: function() {
            return jQuery.parseJSON(configuraciones);
        },
		
		setConfiguraciones: function(p_configuraciones) {
            configuraciones = p_configuraciones;
        },
		
		getUrlConfiguraciones: function() {
            return url_configuraciones;
        },
		
		setUrlConfiguraciones: function(p_url_configuraciones) {
            url_configuraciones = p_url_configuraciones;
        },
	};
}();