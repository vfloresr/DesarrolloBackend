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

                                json[i].radio = "<input type='checkbox' name='solicitudes_asig[]' id='id-" + i + "' value='" + json[i].id + "'>";
                                json[i].oportunidad = "<a target='_blank' href='" + json_response.sugar_config.site_url + "/index.php?module=Opportunities&action=DetailView&record=" + json[i].id + "'>" + json[i].name + "</a>";
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
                        data: "etapa"
                    }, {
					
                        data: "fecha_viaje"
                    }, {
                        data: "agente"
                    }
					/**
					, {
                        data: "prioridad"
                    }
					*/
					],
                    "order": [
                        [3, 'desc']
                    ],
					/**
						"columnDefs": [{
							"visible": false,
							"targets": 2
						}],
					*/
					
					"iDisplayLength" : 100,
                    "bFilter": true,
                    "processing": true,
                    "lengthChange": false,
                    "fnRowCallback": function(nRow, aaData, iDisplayIndex) {
                        if (aaData['prioridad'] == "Alta") {
                            $('td', nRow).addClass('danger');
                        }

                        return nRow;
                    },
					/**
						"drawCallback": function(settings) {
							var api = this.api();
							var rows = api.rows({
								page: 'all'
							}).nodes();
							var last = null;

							api.column(2, {
								page: 'current'
							}).data().each(function(group, i) {
								var group_name = group;
								// group = group.split(' ').join('_');
								group = group.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '_').toLowerCase().split(' ').join('_');
								if (last !== group_name) {
									var estado = $(rows).eq(i).children().eq(7).html();
									NuevasOportunidadesTabla.agregarAgrupaciones(group);
									NuevasOportunidadesTabla.contarAgrupaciones(group, estado);

									$(rows).eq(i).before(
										'<tr class="group"><td colspan="7">' + group_name + '</td><td id="' + group + '_cantidades">Alta:1 - Baja: 2</td></tr>'
									);

									last = group_name;
									$('td#' + group + '_cantidades').html("Alta: " + NuevasOportunidadesTabla.grupos_contadores[group].alta + " - Baja: " + NuevasOportunidadesTabla.grupos_contadores[group].baja);

								}
							});
						}
					*/
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
		if(typeof($('div#ejecutivo_activo select').val()) != 'undefined' && $('div#ejecutivo_activo select').val() == "0")
			combo($('select#selectEjecutivos').val());
		
		if($('input[type=checkbox]:checked').length > 0)
			return false;
		
		NuevasOportunidadesTabla.cargarTabla();
    },60000); //30000
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

	// if (typeof($("#user_externo").val()) != 'undefined' && $("#user_externo").val() != '0')
		// return false;
	// if (typeof($("#user_contact").val()) != 'undefined' && $("#user_contact").val() != '0')
		// return false;
	// if (typeof($("#user_favorito").val()) != 'undefined' && $("#user_favorito").val() != '0')
		// return false;
	// if (typeof($("#user_workflow").val()) != 'undefined' && $("#user_workflow").val() != '0')
		// return false;
		
	if(option == "opc1")
	{
		url = "index.php?entryPoint=actualizar&opc=1";
		label_html = "<label>Ejecutivo Contact Center: </label>";
		select_html = "<select class='form-control' name='user_contact' id='user_contact'>";
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
					var cantidad = (json[i].tipo == "Ext") ? "Ext" : "";
				html += "<option value='"+ json[i].id +"|"+json[i].usuario+"'>"+ json[i].usuario + " Hoy: "+ " ("+ json[i].cantidad +")" + " N: "+"("+json[i].nuevas + ")"+"-"+ cantidad +"</option>";
				}
				
				$("div#btn_asignar").removeClass('hide');
			}
			else
			{
				html += "<option value='0'>«« SELECCIONE»»</option>";
				for(i = 0; i < json.length; i++)
				{
					html += "<option value='"+ json[i].id +"|"+json[i].usuario+"'>"+ json[i].usuario +" Hoy:" + " ("+ json[i].cantidad +")" + " N:"+"("+json[i].nuevas + ")</option>";
				}
				
				$("div#btn_asignar").removeClass('hide');
			}
		}
		else
		{
			html += "<option value='0'></option>";
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
            $("#tipo").val("user_externo");
            $("#div_combo").val("div_sucursales");
            div_ejecutivos = "div_sucursales";
        }

    } 
	else
	{
        user_contact = document.getElementById('user_contact');
    }
	
	
	if (typeof(user_contact) != 'undefined' && user_contact != null) {
        if ($("#user_contact").length) {
            asignado_a = document.getElementById('user_contact').value;
            $("#tipo").val("user_contact");
            $("#div_combo").val("div_contact");
            div_ejecutivos = "div_contact";
        }
    } 
	else
	{
		user_favorito = document.getElementById('user_favorito');
	}

        
    if (typeof(user_favorito) != 'undefined' && user_favorito != null) {
        if ($("#user_favorito").length) {

            asignado_a = document.getElementById('user_favorito').value;
            $("#tipo").val("user_favorito");
            $("#div_combo").val("div_favoritos");
            div_ejecutivos = "div_favoritos";
        }
    } 
	else
	{
        user_workflow = document.getElementById('user_workflow');
    }
	
	if (typeof(user_workflow) != 'undefined' && user_workflow != null) {
        if ($("#user_workflow").length) {
            asignado_a = document.getElementById('user_workflow').value;
            $("#tipo").val("user_workflow");
            $("#div_combo").val("div_workflow");
            div_ejecutivos = "div_workflow";

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
                        // $("#div_datatable").html(data);
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