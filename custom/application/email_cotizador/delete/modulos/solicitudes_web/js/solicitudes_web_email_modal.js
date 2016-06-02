jQuery(document).ready(function() {
	$('select[name=mail_modal_solicitud_web_plantilla_value]').on('change', function() {
		$(".plantilla-mail").html('');
		$(".plantilla-mail").load(this.value,function(){
			$.each($('#contact_name'), function(){
				$(this).html(nombre);
			});
			
			$.each($('#user_detalle_fullname'), function(){
				$(this).html($('input[name=input_user_detalle_nombre]').val()+" "+$('input[name=input_user_detalle_apellido]').val());
			});
			
			$.each($('#user_detalle_email'), function(){
				$(this).html($('input[name=input_user_detalle_email]').val());
			});
			
			$.each($('#user_detalle_telefono'), function(){
				$(this).html($('input[name=input_user_detalle_telefono]').val());
			});
			
			$.each($('#user_detalle_user_cargo'), function(){
				$(this).html($('input[name=input_user_detalle_cargo]').val());
			});
			
			$.each($('#user_detalle_sucursal'), function(){
				$(this).html($('input[name=input_user_detalle_sucursal]').val());
			});
		});
	});
	
	$('#modal_enviar_mail_solicitud_web_success').on('click', function(){
		var $btn = $(this);
		$btn.button('loading');
		
		var pdf_url = "";
		if( typeof $("input[name=mail_modal_solicitud_web_incluye_pdf]")  != 'undefined')
		{
			if($("input[name=mail_modal_solicitud_web_incluye_pdf]").is(":checked"))
				var pdf_url = $("input[name=mail_modal_solicitud_web_incluye_pdf]").val();
		}
		
		$.post(Configuraciones.getUrlConfiguraciones(), {'pdf': pdf_url, 'v_class': 'download_pdf'}).done(function(response){
			var url = Configuraciones.getConfiguraciones().url_set_enviar_email_web;
			var formData = new FormData();
			formData.append("texto_hide", FuncionesGenerales.getCKEditorPlantillaCotizacion().getData());
			formData.append("asunto", $('input[name=mail_modal_solicitud_web_asunto_value]').val());
			formData.append("contacto_id", $('input[name=mail_modal_solicitud_web_contacto_id_value]').val());
			formData.append("procedencia_id", $('input[name=mail_modal_solicitud_we_oportunidad_id]').val());
			formData.append("procedencia", Configuraciones.getConfiguraciones().tag_procedencia_cotizacion);
			formData.append("email_cc", $('input[name=input_user_detalle_email]').val());

			formData.append("pdf", response);
			
			var files = document.getElementById('file-select_solicitud_web').files;
			
			for (var i = 0; i < files.length; i++) 
			{
			  var file = files[i];
			  formData.append('name_file[]', file, file.name);
			}
			
			var xhr = new XMLHttpRequest();
			xhr.open('POST', url, true);
			xhr.send(formData);		
			xhr.onload = function () {
			  if ( xhr.status === 200 && xhr.response.trim() == "0" ) 
			  {
				$btn.button('complete');
				$('#email_enviado_correcto_mensaje').show();
				$( "div.modal-body" ).scrollTop(0);
				
				if(!Configuraciones.getConfiguraciones().from_backend)
				{
					setTimeout(function(){ 
						var data = { 
							oportunidad_id: FuncionesGenerales.getRegistroSeleccionado().eq(0).children().eq(1).find('a').data('oportunidad-id')
						};
						
						$.post(Configuraciones.getConfiguraciones().url_get_oportunidad_detalle_web, data).done(function(response){
							var obj = jQuery.parseJSON( response );

							$('#btn_cerrado_perdido').removeClass('btn-danger');
							$('#btn_cerrado_ganado').removeClass('btn-success');
							$('#btn_estado').addClass('btn-warning').removeClass('active');
							$('#btn_estado_drop_down').addClass('btn-warning').removeClass('active');
							$('#btn_estado').html('<i class="fa fa-list-alt"></i> '+obj.estado);
						});
					}, 2000);
					
					$('.bootstrap-switch').attr('id','btn_solicitud');
					
					setTimeout(function(){
						$btn.button('reset');
						FuncionesGenerales.actualizarTabla();
						$('#email_solicitud_web').modal('toggle');
						$('#email_enviado_correcto_mensaje').hide();
					},2000); 
				} else {
				
					$btn.button('complete');
					$('h2#mensaje').html('<i class="fa fa-envelope-o text-success"></i> El mail ha sido enviado correctamente. Muchas gracias.')
					$('#email_solicitud_web').modal('toggle');
					setTimeout(function(){
						window.top.close();
					},3000); 
				}
			  } 
			  else 
			  {
				$btn.button('problem');
				setTimeout(function(){
					$btn.button('reset');
				},2000); 
			  }
		   }
		});
		
	});
	
	$('#modal_enviar_mail_solicitud_web_cancel').on('click', function(){
		$('#email_solicitud_web').modal('toggle');
	});
	
	//Cuando se abre el modal cargamos datos
	$('#email_solicitud_web').on('shown.bs.modal', function (e) {
		if($( "#headingTarifas" ).find('a').attr('aria-expanded') == "true")
			$( "#headingTarifas" ).find('a').trigger( "click" );
		if($( "#headingVuelos" ).find('a').attr('aria-expanded') == "true")
			$( "#headingVuelos" ).find('a').trigger( "click" );
		
		$('[data-toggle="popover"]').webuiPopover({
			content: function(){
				return $('#popover_detalles').html();
			},
			width:'800px',//can be set with  number
			height:'auto',//can be set with  number
			trigger:'hover',
		});
		
		//limpiamos datos de una llamada anterior
		$('.selector_programas_show').hide();
		$("select[name|='mail_modal_solicitud_web_destinos_value']").html('');
		$("select[name|='mail_modal_solicitud_web_programas_value']").html('');
		$('input[name=mail_modal_solicitud_web_telefonos_ejecutiva_value]').val('');
		$('textarea[name=mail_modal_solicitud_web_datos_practicos_value]').val('');
		$("select[name|='mail_modal_solicitud_web_estimado_value']").val($("select[name|='mail_modal_solicitud_web_estimado_value'] option:first").val());
		$("select[name|='mail_modal_solicitud_web_tarifa_value']").val($("select[name|='mail_modal_solicitud_web_tarifa_value'] option:first").val());
		$("select[name|='mail_modal_solicitud_web_documentacion_value']").val($("select[name|='mail_modal_solicitud_web_documentacion_value'] option:first").val());
		$('input[name=mail_modal_solicitud_web_incluye_value]').attr('checked', false);
		$('input[name=mail_modal_solicitud_web_imagen_value]').attr('checked', false);
	
		
		$("input[name='mail_modal_solicitud_tarifas']").each(function() {
			$(this).attr('checked', false);
			$(this).parent().parent().find('input[name="mail_modal_solicitud_tarifas_value"]').val('0.00');
		});
		
		$("input[name='mail_modal_solicitud_tarifas_comentarios']").each(function() {
			$(this).attr('checked', false);
			$(this).parent().parent().find('input[name="mail_modal_solicitud_tarifas_comentarios"]').val('');
		});
		
		$( "input[name='mail_modal_solicitud_tarifas_value']" ).change(function() {
			$('#tarifa').html('');
		
			var tarifa_html = "<ul>";
			$("input[name='mail_modal_solicitud_tarifas']").each(function() {
				if($(this).is(":checked")) 
				{
					var parent_container = $(this).parent().parent().find('input[name="mail_modal_solicitud_tarifas_value"]');
					var parent_container_comentario = $(this).parent().parent().find('input[name="mail_modal_solicitud_tarifas_comentarios"]');
					var parent_container_diviza = $(this).parent().parent().find('select[name="mail_modal_solicitud_web_diviza"]').val();
					tarifa_html += "<li>  " + $(this).val() + " " + accounting.formatMoney($(parent_container).val(), "", 2, ".", ",")+" "+parent_container_diviza+" "+$(parent_container_comentario).val()+"</li>";
				}
			});
			tarifa_html += "</ul>";
			FuncionesGenerales.getCKEditorPlantillaCotizacion().document.getById('tarifa').setHtml(tarifa_html);
			// $('#tarifa').html(tarifa_html);
		});
		
		$("input[name='mail_modal_solicitud_tarifas']" ).change(function() {
			$('#tarifa').html('');
		
			var tarifa_html = "<ul>";
			$("input[name='mail_modal_solicitud_tarifas']").each(function() {
				if($(this).is(":checked")) 
				{
					var parent_container = $(this).parent().parent().find('input[name="mail_modal_solicitud_tarifas_value"]');
					var parent_container_comentario = $(this).parent().parent().find('input[name="mail_modal_solicitud_tarifas_comentarios"]');
					var parent_container_diviza = $(this).parent().parent().find('select[name="mail_modal_solicitud_web_diviza"]').val();
					tarifa_html += "<li>  " + $(this).val() + " " + accounting.formatMoney($(parent_container).val(), "", 2, ".", ",")+" "+parent_container_diviza+" "+$(parent_container_comentario).val()+"</li>";
				}
			});
			tarifa_html += "</ul>";
			// $('#tarifa').html(tarifa_html);
			FuncionesGenerales.getCKEditorPlantillaCotizacion().document.getById('tarifa').setHtml(tarifa_html);
		});
		
		$( "select[name='mail_modal_solicitud_web_diviza']" ).change(function() {
			$('#tarifa').html('');
		
			var tarifa_html = "<ul>";
			$("input[name='mail_modal_solicitud_tarifas']").each(function() {
				if($(this).is(":checked")) 
				{
					var parent_container = $(this).parent().parent().find('input[name="mail_modal_solicitud_tarifas_value"]');
					var parent_container_comentario = $(this).parent().parent().find('input[name="mail_modal_solicitud_tarifas_comentarios"]');
					var parent_container_diviza = $(this).parent().parent().find('select[name="mail_modal_solicitud_web_diviza"]').val();
					tarifa_html += "<li>  " + $(this).val() + " " + accounting.formatMoney($(parent_container).val(), "", 2, ".", ",")+" "+parent_container_diviza+" "+$(parent_container_comentario).val()+"</li>";
				}
			});
			tarifa_html += "</ul>";
			// $('#tarifa').html(tarifa_html);
			FuncionesGenerales.getCKEditorPlantillaCotizacion().document.getById('tarifa').setHtml(tarifa_html);
		});
		
		$("input[name='mail_modal_solicitud_tarifas_comentarios']" ).change(function() {
			$('#tarifa').html('');
		
			var tarifa_html = "<ul>";
			$("input[name='mail_modal_solicitud_tarifas']").each(function() {
				if($(this).is(":checked")) 
				{
					var parent_container = $(this).parent().parent().find('input[name="mail_modal_solicitud_tarifas_value"]');
					var parent_container_comentario = $(this).parent().parent().find('input[name="mail_modal_solicitud_tarifas_comentarios"]');
					var parent_container_diviza = $(this).parent().parent().find('select[name="mail_modal_solicitud_web_diviza"]').val();
					tarifa_html += "<li>  " + $(this).val() + " " + accounting.formatMoney($(parent_container).val(), "", 2, ".", ",")+" "+parent_container_diviza+" "+$(parent_container_comentario).val()+"</li>";
				}
			});
			tarifa_html += "</ul>";
			// $('#tarifa').html(tarifa_html);
			FuncionesGenerales.getCKEditorPlantillaCotizacion().document.getById('tarifa').setHtml(tarifa_html);
		});
		
		//Cargamos los destinos
		$("select[name|='mail_modal_solicitud_web_destinos_value']").append('<option>Cargando...</option>');
		$.get( Configuraciones.getConfiguraciones().url_cocha_csm_mobile_api + '/destinos').done(function( destinos ) {
			 $("select[name|='mail_modal_solicitud_web_destinos_value']").html('');
			 $("select[name|='mail_modal_solicitud_web_destinos_value']").append('<option value="-999">Seleccionar...</option>');
			 for (var i in destinos) {
				$("select[name|='mail_modal_solicitud_web_destinos_value']").append('<option value="'+destinos[i].id+'">'+ destinos[i].title + '</option>');
			 }
		});
		
		
	});
	
	// ACCIONES PARA DESTINO
	$("select[name|='mail_modal_solicitud_web_destinos_value']").change(function() {
		 var destino_id = this.value;
		  FuncionesGenerales.getCKEditorPlantillaCotizacion().document.getById('programas').setHtml('');
		  FuncionesGenerales.getCKEditorPlantillaCotizacion().document.getById('incluye').setHtml('');
		  FuncionesGenerales.getCKEditorPlantillaCotizacion().document.getById('imagen').setHtml('');
		  
		 //$('#incluye').html('');
		 //$('#imagen').html('');
		 $('input[name=mail_modal_solicitud_web_incluye_value]').attr('checked', false);
		 $('input[name=mail_modal_solicitud_web_imagen_value]').attr('checked', false);
		 if(destino_id == -999)
		 {
			$('.selector_programas_show').hide();
			$('input[name=mail_modal_solicitud_web_asunto_value]').val('');
			$("select[name|='mail_modal_solicitud_web_programas_value']").html('');
			
			return false;
		 }
		 
		//Cargamos los programass
		$('.selector_programas_show').show();
		$('input[name=mail_modal_solicitud_web_asunto_value]').val('');
		$("select[name|='mail_modal_solicitud_web_programas_value']").html('').append('<option>Cargando...</option>');
		$.get(Configuraciones.getConfiguraciones().url_cocha_csm_mobile_api + "/programas?destino="+destino_id).done(function( programas ) {
			 $("select[name|='mail_modal_solicitud_web_programas_value']").html('');
			 $("select[name|='mail_modal_solicitud_web_programas_value']").append('<option value="-999">Seleccionar...</option>');
			 for (var i in programas) { 
				$("select[name|='mail_modal_solicitud_web_programas_value']").append('<option data-days="' + programas[i].days + '" data-title="' + programas[i].title + '" data-nights="' + (programas[i].days-1) + '" data-subtitle="' + programas[i].subtitle + '" value="'+programas[i].id+'">' + programas[i].days + ' Días, ' + programas[i].title + ', ' + programas[i].subtitle + '</option>');
			 }
		});
	});
	
	// ACCIONES PARA PROGRAMA
	$("select[name|='mail_modal_solicitud_web_programas_value']").change(function() {
		 var programa_id = $(this).val();
		 $('#incluye').html('');
		 $('#imagen').html('');
		 $('input[name=mail_modal_solicitud_web_incluye_value]').attr('checked', false);
		 $('input[name=mail_modal_solicitud_web_imagen_value]').attr('checked', false);
		 
		 if(programa_id == -999)
		 {
			$('input[name=mail_modal_solicitud_web_asunto_value]').val('');
			
			return false;
		 }	
		 $('#title').html('<h3><ins><strong>Detalle Programa</strong></ins></h3>' + $(this).find(':selected').data('title') + '<br/>'+ $(this).find(':selected').data('subtitle') + '<br/>' + $(this).find(':selected').data('days') + 'Días / ' + $(this).find(':selected').data('nights') +' Noches </br></br>');
		 $('input[name=mail_modal_solicitud_web_asunto_value]').val('Cotización '+ $(this).find(':selected').data('title') +' '+ $(this).find(':selected').data('days') +' Días / '+ $(this).find(':selected').data('nights') +' Noches');
	    	FuncionesGenerales.getCKEditorPlantillaCotizacion().document.getById('programas').setHtml('Cotización '+ $(this).find(':selected').data('title') +' '+ $(this).find(':selected').data('days') +' Días / '+ $(this).find(':selected').data('nights') +' Noches');
            
	
	});
	
	// ACCIONES PARA Asuntos
	$('input[name=mail_modal_solicitud_web_asunto_value]').change(function() {
		FuncionesGenerales.getCKEditorPlantillaCotizacion().document.getById('programas').setHtml($(this).val());
	});

	// ACCIONES PARA DOCUMENTACION
	$("select[name|='mail_modal_solicitud_web_documentacion_value']").change(function() {
		if( $(this).val() != -999)
			// $('#cgeneral').html("<br><h3><ins><strong>Documentación</strong></ins></h3>"+ $(this).val());
			FuncionesGenerales.getCKEditorPlantillaCotizacion().document.getById('cgeneral').setHtml("<br><h3><ins><strong>Documentación</strong></ins></h3>"+ $(this).val());
		else
			// $('#cgeneral').html("");
			FuncionesGenerales.getCKEditorPlantillaCotizacion().document.getById('cgeneral').setHtml("");
	});
	
	// ACCIONES PARA ESTIMADO
	$("select[name|='mail_modal_solicitud_web_estimado_value']").change(function() {
		if( $(this).val() != -999)
			FuncionesGenerales.getCKEditorPlantillaCotizacion().document.getById('estimado').setHtml($(this).val());
		else
			FuncionesGenerales.getCKEditorPlantillaCotizacion().document.getById('estimado').setHtml('');
	});
	
	// ACCIONES PARA TARIFA
	$("select[name|='mail_modal_solicitud_web_tarifa_value']").change(function() {
		if( $(this).val() != -999)
			FuncionesGenerales.getCKEditorPlantillaCotizacion().document.getById('estimado').setHtml($(this).val());
		else
			FuncionesGenerales.getCKEditorPlantillaCotizacion().document.getById('estimado').setHtml('');
	});
	
	// ACCIONES PARA INCLUYE
	$('input[name=mail_modal_solicitud_web_incluye_value]').change(function() {
	
		if($('.selector_programas_show').is(":hidden") && $('input[name=mail_modal_solicitud_web_asunto_value]').val().length > 0)
		{
			var programa_id = $("input[name=mail_modal_solicitud_web_programa_id_default]").val();
		}
		else
		{
			var destino_id = $("select[name|='mail_modal_solicitud_web_destinos_value'] option:selected").val();
			
			if(destino_id == -999)
				return false;
			
			var programa_id = $("select[name|='mail_modal_solicitud_web_programas_value'] option:selected").val();
			 
			if(programa_id == -999)
				return false;   
		}
		
		if($(this).is(":checked")) 
		{
			$.get(Configuraciones.getConfiguraciones().url_cocha_csm_mobile_api + "/programas?id="+programa_id).done(function( programa ) {
				
				if(typeof programa[0].category !== 'undefined' &&  programa[0].category.length > 0 && programa[0].category.toLowerCase().indexOf('ofertas - paquetes') > -1 )
				{	
					var obj =  programa[0].includeDescriptions;
					var incluye_description = programa[0].includeDescriptions;
					
					var keys = [], name;
					for (name in obj) {
						if (obj.hasOwnProperty(name)) {
							keys.push(name);
						}
					}
					var html_incluye = '<ul style="list-style-type: none; padding: 0% 5% !important;">';
					for(var i=0; i <= keys.length-1; i++)
					{
						html_incluye += '<li><img style="width: 20px !important;" src="' + Configuraciones.getConfiguraciones().url_cocha_csm + incluye_description[keys[i]][1] + '"/><span style="padding: 0px 10px; display: inline-block; vertical-align: top;">' + incluye_description[keys[i]][0] + '</span></li>';     
					}
					html_incluye += '</ul>';
					
					FuncionesGenerales.getCKEditorPlantillaCotizacion().document.getById('incluye').setHtml('</br><h3><ins><strong>Incluye </strong></ins></h3></br>' + html_incluye);				
				} 
				else if(typeof programa[0].description !== 'undefined')
				{
					var incluye_description = programa[0].description;
					FuncionesGenerales.getCKEditorPlantillaCotizacion().document.getById('incluye').setHtml('</br><h3><ins><strong>Incluye </strong></ins></h3></br><ul>' + incluye_description + '</ul>');
				}
				else
				{
					var incluye_description = "Sin Datos";
					FuncionesGenerales.getCKEditorPlantillaCotizacion().document.getById('incluye').setHtml('</br><h3><ins><strong>Incluye </strong></ins></h3></br><ul>' + incluye_description + '</ul>');
				}
			});
		}
		else
		{
			FuncionesGenerales.getCKEditorPlantillaCotizacion().document.getById('incluye').setHtml(""); 
		}
	});
	
	// ACCIONES PARA IMAGEN
	$('input[name=mail_modal_solicitud_web_imagen_value]').change(function() {
	
		if($('.selector_programas_show').is(":hidden") && $('input[name=mail_modal_solicitud_web_asunto_value]').val().length > 0)
		{
			var programa_id = $("input[name=mail_modal_solicitud_web_programa_id_default]").val();
		}
		else
		{
			var destino_id = $("select[name|='mail_modal_solicitud_web_destinos_value'] option:selected").val();
			
			if(destino_id == -999)
				return false;
			
			var programa_id = $("select[name|='mail_modal_solicitud_web_programas_value'] option:selected").val();
			 
			if(programa_id == -999)
				return false;
		}
		
		if($(this).is(":checked")) 
		{
			$.get(Configuraciones.getConfiguraciones().url_cocha_csm_mobile_api + "/programas?id="+programa_id).done(function( programa ) {
				var imagen = Configuraciones.getConfiguraciones().url_cocha_csm + programa[0].imgUrl;
				// $('#imagen').html('').html("<img src='" + imagen + "' style='width:610px'>"); 
				FuncionesGenerales.getCKEditorPlantillaCotizacion().document.getById('imagen').setHtml("<img src='" + imagen + "' style='width:610px'>"); 
			});
		}
		else
		{
			FuncionesGenerales.getCKEditorPlantillaCotizacion().document.getById('imagen').setHtml(""); 
		}
	});

	// ACCIONES PARA NOMBRE DEL CLIENTE
	$('input[name=mail_modal_solicitud_web_nombre_cliente_value]').change(function() {
		// $('#contact_name').html($(this).val());
		FuncionesGenerales.getCKEditorPlantillaCotizacion().document.getById('contact_name').setHtml($(this).val());
	});
	
	// ACCIONES PARA TELEFONO EJECUTIVA
	$('input[name=mail_modal_solicitud_web_telefonos_ejecutiva_value]').change(function() {
		// $('#tejecutiva').html($(this).val());
		FuncionesGenerales.getCKEditorPlantillaCotizacion().document.getById('tejecutiva').setHtml($(this).val());
	});

	$('#email_solicitud_web').on('hide.bs.modal', function (e) 
	{
		if(!Configuraciones.getConfiguraciones().from_backend)
		{
			if( typeof $("#solicitudes_web_oportunidades").data('bs.modal')  != 'undefined' && $("#solicitudes_web_oportunidades").data('bs.modal').isShown)
				$('#solicitudes_web_oportunidades').show();
				
			$('input[name=mail_modal_solicitud_web_incluye_pdf]').attr('checked', false);
			NuevasSolicitudes.init();
		}
	});

	//mostrar tarifas
	$('#tarifas').on('hide.bs.collapse', function(e){
		$(e.target)
			.prev('.panel-heading')
			.find("a")
			.html('<i class="indicator glyphicon glyphicon-chevron-up  pull-right"></i> Mostrar Tarifas');
	});
	
	//ocultar tarifas
	
	$('#tarifas').on('show.bs.collapse',  function(e){
		$(e.target)
			.prev('.panel-heading')
			.find("a")
			.html('<i class="indicator glyphicon glyphicon-chevron-down  pull-right"></i> Ocultar Tarifas');
	});
	
    //mostrar vuelos
	$('#vuelos').on('hide.bs.collapse', function(e){
		$(e.target)
			.prev('.panel-heading')
			.find("a")
			.html('<i class="indicator glyphicon glyphicon-chevron-up  pull-right"></i> Vuelos');
	      	
	});
	
	//ocultar vuelos
	$('#vuelos').on('show.bs.collapse',  function(e){
		$(e.target)
			.prev('.panel-heading')
			.find("a")
			.html('<i class="indicator glyphicon glyphicon-chevron-down  pull-right"></i> Ocultar Vuelos');
	});
	
		
	
});

function toggleChevron(e) {
    $(e.target)
     .prev('.panel-heading')
     .find("i.indicator")
     .toggleClass('glyphicon-chevron-down glyphicon-chevron-up');
}



