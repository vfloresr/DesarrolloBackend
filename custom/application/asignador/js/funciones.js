var NuevasOportunidadesTabla = function() {
    var nueva_oportunidades;
    var grupos_contadores;
    var url = 'index.php?entryPoint=actualizartabla';

  return {
        contarAgrupaciones: function(nombre, tipo) {
            if (tipo == "Alta")
                NuevasOportunidadesTabla.grupos_contadores[nombre].alta = NuevasOportunidadesTabla.grupos_contadores[nombre].alta + 1;
            else if (tipo == "Baja")
                NuevasOportunidadesTabla.grupos_contadores[nombre].baja = NuevasOportunidadesTabla.grupos_contadores[nombre].baja + 1;
        },

        agregarAgrupaciones: function(nombre) {
            if (!NuevasOportunidadesTabla.grupos_contadores[nombre])
                NuevasOportunidadesTabla.grupos_contadores[nombre] = {
                    'alta': 0,
                    'baja': 0
                };
        },

        cargarTabla: function() {
            if (!$.fn.dataTable.isDataTable('#table-3')) {
                NuevasOportunidadesTabla.grupos_contadores = [];
                NuevasOportunidadesTabla.nueva_oportunidades = $('#table-3').dataTable({
                    ajax: {
                        'url': 'index.php?entryPoint=actualizartabla',
                        'dataSrc': function(json_response) {
                            var i;
                            var json = json_response.solicitudes
                            for (i = 0; i < json.length; i++) {
                                var json_aux = JSON.stringify(json[i]);

                                json[i].radio = "<input type='checkbox' class='checkbox1' name='solicitudes_asig[]' id='id-" + i + "' value='" + json[i].id + "'>";
                                json[i].oportunidad = "<a target='_blank' href='" + json_response.sugar_config.site_url + "/index.php?module=Opportunities&action=DetailView&record=" + json[i].id + "'>" + json[i].name + "</a>";
                          
							    //alert("agente:" + json[i].agente);
								//alert("ID agente:" + json[i].id_agente_c);
								json[i].agente;
								var id_agente_c = json[i].id_agente_c == undefined ? '' : json[i].id_agente_c.trim();
								json[i].nombre_agente =json[i].agente;
								//json[i].id_agente_c;
								json[i].agente ='';
							    // json[i].agente = ((json[i].agente == "") && (id_agente_c  == "")  ||  (json[i].agente == null) || 
								// (json[i].agente == null) || (json[i].id == "") ? json[i].agente: "<button type='button' onclick='modal_asignar_ejecutivo(this)' class='btn btn-blue' name='solicitudes_asig[]' value='" + json[i].id + "' data-oportunidad-id='" + json[i].id + "' data-ejecutivo-id='" + json[i].id_agente_c + "' data-id_usuario='" + json[i].id_user + "' data-agente='" + json[i].agente + "' style=width:60px;padding-top:3px;margin-left:15px;padding-left:7px;>Asignar </button>");
							  							  
							 /* if ((json[i].agente != "")) {
								 json[i].radio = "<input type='checkbox' class='checkbox1' name='solicitudes_asig[]' id='id-" + i + "' value='" + json[i].id + "' disabled>";
								//alert('disabled');
							    }else{
								 $('.checkbox1').prop("disabled", true); 
									
								}  */ 
							
							  }
							
                           if (typeof(json) == "string") {
                                json = {};
                                json.buttons = '';
                            }
                            var d = {};
                            return d['data'] = json;
                        }
                    },

                    columns: [{
                        data: "radio"
                    }, {
                        data: "oportunidad"
                    },  
					
					{
                        data: "destino"
                    }, 
					
					{
                        data: "date_entered"
                    }, {
                        data: "canal"
                    }, {
                        data: "usuario"  
                    }, {
					
                        data: "fecha_viaje"
                    }, {
					
                        data: "nombre_agente"
                    }, 
					{
                        data: "agente"
                    }
					
					, /*{
                        data: "buttons"
                    } */
					
					],
                    "order": [
                        [3, 'desc']
                    ],
					
						"columnDefs": [{
							"targets": [4],
							"visible": false
							
						
						},
						 {
							"targets": [5],
							"visible": true
						 },
						  						
				         {
							"targets": [6],
							"visible": true
						 },
										 				   
					   ],
					
					 "scrollY":        "500px",
					 "scrollCollapse": true,
					 "paging":         true,	
					 "iDisplayLength" : 50,
                     "bFilter": false,
                     "processing": true,
                    // "serverSide": true,
                    // "ajax": "../server_side/scripts/server_processing.php",
					 "lengthChange": false,
                     "fnRowCallback": function(nRow, aaData, iDisplayIndex) {
                        if (aaData['prioridad'] == "Alta") {
                            $('td', nRow).addClass('danger');
                        }

                        return nRow;
                    },
				
                });
            } else {
                NuevasOportunidadesTabla.grupos_contadores = [];
                NuevasOportunidadesTabla.nueva_oportunidades.api().ajax.reload();

            }
        }
    };
}();

$(document).ready(function() {
	$( "select#selectEjecutivos" ).change(function() {
		combo($(this).val());
	});

    NuevasOportunidadesTabla.cargarTabla();
    setInterval(function() 
	{
		NuevasOportunidadesTabla.cargarTabla();
    },300000); //30000
	 setInterval(function() 
	{
		if(typeof($('div#ejecutivo_activo select').val()) != 'undefined' && $('div#ejecutivo_activo select').val() == "0")
			combo($('select#selectEjecutivos').val());
		
		if($('input[type=checkbox]:checked').length > 0)
			return false;


    },120000); //30000
});
function combo(option) 
{
	var url;
	var label_html;
	var select_html;
	
	if(option == "0")
	{
		$('div#ejecutivo_activo').html('');
		$("div#btn_asignar").addClass('hide');
		return false;
	}

	if(option == "opc1")
	{
		/*url = "index.php?entryPoint=actualizar_new&opc=1";
		label_html = "<label>Ejecutivo Contact Center: </label>";
		select_html = "<select class='form-control' name='user_contact' id='user_contact'>";*/
	}
	else if(option == "opc2")
	{
		url = "index.php?entryPoint=actualizar&opc=2";
		label_html = "<label>Ejecutivos Sucursales: </label>";
		select_html = "<select class='form-control' name='user_externo' id='user_externo' style=width:60%;>";
	}
	else if(option == "opc3")
	{
		url = "index.php?entryPoint=actualizar&opc=3";
		label_html = "<label>Ejecutivos Favoritos: </label>";
		select_html = "<select class='form-control' name='user_favorito' id='user_favorito' style=width:60%;>";
	}
	else if(option == "opc4")
	{
		url = "index.php?entryPoint=actualizar&opc=4";
		label_html = "<label>Ejecutivos Workflow: </label>";
		select_html = "<select class='form-control' name='user_workflow' id='user_workflow'>";
	}
	
	$("div#btn_asignar").addClass('hide');
	$('div#ejecutivo_activo').html('');
	var html = label_html+" "+select_html+ "<option value='0'>Cargando...</option></select>";
	
	$('div#ejecutivo_activo').html(html);
	
	$.get(url).done(function(response){
		var json = jQuery.parseJSON(response); 
		html = label_html+" "+select_html;
		
		if(json.length > 0)
		{
			if(option == "opc2" || option == "opc3")
			{ 
				html += "<option value='0'>«« SELECCIONE »»</option>";
				for(i = 0; i < json.length; i++)
				{
					var cantidad = (json[i].tipo == "Ext") ? "-Ext" : (json[i].tipo == "WF") ? "-WF" : "";
				html += "<option value='"+ json[i].usuario +"|"+json[i].id+"'>"+ json[i].usuario + " Hoy: "+ " ("+ json[i].cantidad +")" + " N: "+"("+json[i].nuevas + ")"+ cantidad +"</option>";
				}
				   
				$("div#btn_asignar").removeClass('hide');
			}
			else
			{
				html += "<option value='0'>«« SELECCIONE »»</option>";
				var valorAnte='';
				for(i = 0; i < json.length; i++)
				{
					if (valorAnte != json[i].estado){
						html += "<optgroup label="+json[i].estado+">";
						valorAnte = json[i].estado;
					}
					html += "<option value='"+ json[i].usuario +"|"+json[i].id+"'>"+ json[i].usuario +" Hoy:" + " ("+ json[i].cantidad +")" + " N:"+"("+json[i].nuevas + ")</option>";
				}
				
				$("div#btn_asignar").removeClass('hide');
			}
		}
		else
		{
			html += "<option value='0'>«« NO HAY DATOS »»</option>";
			$("div#btn_asignar").addClass('hide');
		}
		
		html += "</select>";
		
		$('div#ejecutivo_activo').html(html);
	});
}

function asignar() {
    result = 1;

    var asignado_a = '';
    var tipo = '';
    var user_externo = '';
    var user_contact = '';
    var user_favorito = '';
    var user_workflow = '';
    var asignar_user = "";
    var div_ejecutivos = "";
    var ruta = '';
    
    if (typeof(document.getElementById('ruta').value) != 'undefined' && document.getElementById('ruta').value != null) {
        ruta = document.getElementById('ruta').value;
        url_ruta = ruta + '/index.php?entryPoint=grabaoportunidades';
    }

    if ($("#user_externo").val() == '' || $("#user_contact").val() == '' || $("#user_favorito").val() == '' || $("#user_workflow").val() == '') {
        alert('Debe seleccionar un usuario solamente')
        $("#asignado_a").val('');
        result = 0;
    } 
	else 
	{
		user_externo = document.getElementById('user_externo');
	}
        
    if (typeof(user_externo) != 'undefined' && user_externo != null) {
        if ($("#user_externo").length) {


            asignado_a = document.getElementById('user_externo').value;
            $("#tipo").val("directo");
           // $("#div_combo").val("div_sucursales");
           // div_ejecutivos = "div_sucursales";
        }

    } 
	else
	{
        user_contact = document.getElementById('user_contact');
    }
	
	
	if (typeof(user_contact) != 'undefined' && user_contact != null) {
        if ($("#user_contact").length) {
            asignado_a = document.getElementById('user_contact').value;
            $("#tipo").val("directo");
           // $("#div_combo").val("div_contact");
           // div_ejecutivos = "div_contact";
        }
    } 
	else
	{
		user_favorito = document.getElementById('user_favorito');
	}

        
    if (typeof(user_favorito) != 'undefined' && user_favorito != null) {
        if ($("#user_favorito").length) {

            asignado_a = document.getElementById('user_favorito').value;
            $("#tipo").val("directo");
            //$("#div_combo").val("div_favoritos");
           // div_ejecutivos = "div_favoritos";
        }
    } 
	else
	{
        user_workflow = document.getElementById('user_workflow');
    }
	
	if (typeof(user_workflow) != 'undefined' && user_workflow != null) {
        if ($("#user_workflow").length) {
            asignado_a = document.getElementById('user_workflow').value;
            $("#tipo").val("workflow");
            //$("#div_combo").val("div_workflow");
            //div_ejecutivos = "div_workflow";

        }
    }

    $("#asignado_a").val(asignado_a);

    if ($("#user_externo").val() == 0 || $("#user_contact").val() == 0 || $("#user_favorito").val() == 0 || $("#user_workflow").val() == 0) {
        alert('Debe seleccionar un ejecutivo')
        $("#asignado_a").val('');
        result = 0;
    } else {

        var opp = $('input:checkbox:checked').length
        if (opp == 0) {

            alert('Debe Asignar por lo menos un Destino');
        } else {
            if (result == 1) {
                $("#mensaje_asignando").show();
                $.ajax({
                    type: 'POST',
                    //url: ruta+'/index.php?entryPoint=grabaoportunidades',
                    url: url_ruta,
                    data: $("#frm").serialize(),
                    success: function(data) {
                        $("#div_ejecutivos").html(data);
                    	NuevasOportunidadesTabla.cargarTabla();
						$('div#ejecutivo_activo').html('');
                        $("#mensaje_asignando").hide();
                        $('input:checkbox:checked').removeAttr('checked');
                        $("#selectEjecutivos").prop('selectedIndex', 0);
                    }

                });
            }
        }
    }

}

function actualizardata_table() {
    NuevasOportunidadesTabla.cargarTabla();
}

function  modal_asignar_ejecutivo (el){
   
    
	var $object          = $(el);
	var url_usuarios     = '../../../../index.php?entryPoint=listar_usuario&datos';
	//var url_usuarios     = $sugar_config['site_url'];
	var id_oportunidad 	 =  $object.data('oportunidad-id');
	var desc_agente      =  (($object.data('agente') == null) ? "" :$object.data('agente').trim());
	var id_agente        =   ($object.data('ejecutivo-id') == null ? "":$object.data('ejecutivo-id').trim());
	var ruta = '';
    
    if (typeof(document.getElementById('ruta_modal').value) != 'undefined' && document.getElementById('ruta_modal').value != null) {
        ruta = document.getElementById('ruta_modal').value;
        url_ruta = ruta + '/index.php?entryPoint=grabaoportunidades';
    }	 
	  
	var data = { 
		oportunidad_id: $object.data('oportunidad-id')
	};
	$('#id_oportunidad').val($object.data('oportunidad-id'));
	
	var valor_oportunidad =$('#id_oportunidad').val($object.data('oportunidad-id'));
	
	$('#popup').fadeIn('slow');
        $('.popup-overlay').fadeIn('slow');
        $('.popup-overlay').height($(window).height());
		
		//Cargamos los Usuarios
		$("select[name|='mail_modal_ejecutivos_value']").append('<option>Cargando...</option>');
		$.get(url_usuarios).done(function(response) {
			var json = jQuery.parseJSON(response);
			 $("select[name|='mail_modal_ejecutivos_value']").html('');
			 //$('#desc_agente').val('');
			if ((desc_agente != "") &&(id_agente == "")|| (desc_agente == "no") || (id_agente == "undefined")){ 
			    ($('#desc_agente').val(desc_agente) == "" ? "NO HAY DATOS":$('#desc_agente').val(desc_agente));
				
			    $("select[name|='mail_modal_ejecutivos_value']").append('<option value="-999"> «« SELECCIONE »»</option>');
			   
				for (var i in response) {
					 
					  $("select[name|='mail_modal_ejecutivos_value']").append('<option value="'+ json[i].nombre_user +"|"+json[i].id_user+'">'+ json[i].nombre_user + '</option>');
				}
				 $('#desc_agente').val('');	 
			}else{
				 ($('#desc_agente').val(desc_agente) == "" ? "NO HAY DATOS":$('#desc_agente').val(desc_agente));
 			    $("select[name|='mail_modal_ejecutivos_value']").append('<option value="'+desc_agente +"|"+ id_agente +'">'+ desc_agente +  '</option>');
				
				$("select[name|='mail_modal_ejecutivos_value']").append('<option value="-999">«« SELECCIONE »»</option>');
							 
				 for (var i in response) {
					
           $("select[name|='mail_modal_ejecutivos_value']").append('<option value="'+ json[i].nombre_user +"|"+json[i].id_user+'">'+ json[i].nombre_user + '</option>');
				
				}
					   
			   $('#desc_agente').val('');
			   
			 }
             		 
		});  
				
		$('#id_oportunidad_ej').html($object.data('oportunidad-id'));
		$('#id_oportunidades').attr("value",$object.data('oportunidad-id'));
}

 $(document).ready(function(){
	    var inicio=0; var contador = 0;
		$(document).on('click','#asignacion',function(){
          var contador = 0;			
         var id_oportunidades = $('input#id_oportunidades').val();
		 var valor = $( "#mail_modal_ejecutivos_value option:selected" ).val();
		   //  alert("asignado a:" + " "  + valor);	   
			var parametros = { 
				"id_oportunidad"  :id_oportunidades,
				"asignado_a"      :valor,
				"tipo"  :"directo",
			}; 
			 
			if (valor == '-999'){
				 
				 alert('Por Favor Seleccione un Ejecutivo...');
				 return false;
			 }else{
				contador++; 
				
        if(contador == 1){	
			$.ajax({
                    type: 'POST',
                    url: url_ruta,
                    data: parametros,
					beforeSend: function () {
				    $("#div_asignacion").html("Procesando, espere por favor...");
			       },
                    success: function(response) {
						$("#div_asignacion").html(response);
						$("#div_asignacion").css("display", "none");
						 NuevasOportunidadesTabla.nueva_oportunidades.api().ajax.reload();
						$('#popup').fadeOut('slow');
						$('.popup-overlay').fadeOut('slow');
					  	
                   }
                 
                });
				inicio++;
		      }else{
			    return false;
			  } 
		  	      
		} 
	});
}); 
         

$(document).ready(function(){
 $('#close').click(function(){
	$('#popup').fadeOut('slow');
    $('.popup-overlay').fadeOut('slow');
        return false;
  });
  
}); 	
