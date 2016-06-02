var NuevasSolicitudes = function() {

	var nIdTarea;

	return {
	
		setIdTarea: function(pIdTarea)
		{
			nIdTarea = pIdTarea;
		},
		
		getIdTarea : function()
		{
			return nIdTarea;
		},
	
		init: function() {
			setInterval(function(){
				$.post(Configuraciones.getConfiguraciones().url_get_cantidad_nuevas_solicitudes).done(function( p_response ) {
					var json = jQuery.parseJSON( p_response );
					
					//alert(json);
					
					if(json !== null)
					{
						if(json[0].cantidad.trim() > 0)
						{
							
							if(json[0].cantidad.trim() == 1){
								$("#cantidad_nuevas_solicitudes").html("una solicitud nueva.");
							}else{
								$("#cantidad_nuevas_solicitudes").html(json[0].cantidad.trim()+"&nbsp;solicitudes nuevas.");
							}							
							
							NuevasSolicitudes.setIdTarea(json[0].id_tarea.trim());
						
							if( typeof $("#alerta_emergente").data('bs.modal')  == 'undefined')
								$('#alerta_emergente').modal('toggle');
							else if( typeof $("#alerta_emergente").data('bs.modal')  != 'undefined' && !$("#alerta_emergente").data('bs.modal').isShown)
								$('#alerta_emergente').modal('toggle');
						
							
							
						}
					}
					
					
					
				});
			},Configuraciones.getConfiguraciones().intervalo_refrescar_nuevas_solicitudes);
        }
	};
}();