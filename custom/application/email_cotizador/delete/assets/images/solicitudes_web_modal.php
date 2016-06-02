<div class="modal fade" id="solicitudes_web_oportunidades" tabindex="-1" data-width="760" role="dialog" data-backdrop="static" aria-labelledby="solicitudes_web_oportunidades" aria-hidden="true">
  <div style="width:99%; height: 90%; margin-top: 1% !important; text-rendering: auto;" class="modal-dialog">
    <div class="modal-content">
     <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></span></button>
        <h2 class="modal-title custom_align"><span class="glyphicon glyphicon-tags" style="color:#FACC2E;"></span>&nbsp;&nbsp;Producto:<strong>Viajes de Verano 2016</strong> <span id="perdido_title_number"></span></h2>
      </div><!--modal-header !-->                                                                                                         
    
	  <div class="modal-body">
		        
       <div class="row">
        	<div class="col-md-1" style="border-left:1px solid #cccc";>
			 <p class="dropcap dropcap-info dropcap-fill";><?php echo($_SESSION['nombre']) ?></p>
		  		  
		    </div><!--col-md-3 !-->	
		    
			<div class="col-md-11">
		      <div class="admin-form theme-primary">
				   <div class="panel heading-border panel-primary">
					 <div class="panel-body bg-light">
					  <form method="post" action="" id="form-ui"><!--style="margin-right:580px;" !-->
						<!--div class="section-divider mb40" id="spy1">
						   <h3>Solicitud asignada</h3>
						 
						</div-->
						
						<!--div class="section-divider mb40" id="spy1">
						   <h3>Datos del contacto</h3>
						 
						</div-->
						<div class="row">
							<div class="col-md-7" >
							 <h3>Datos del contacto</h3>
						   </div>
						
						  <div class="col-md-5" style="margin-left:-524px;">
							<h3>Solicitud asignada</h3>
						  </div>
					   </div>
					   <!--div id ="datos_contacto" style="position:absolute;top:50px";>
						  <span style="font-size:14px">Datos del contacto</span>
					   </div-->
							
						<hr style="margin: 0px 0px !important;">
										
						 <div class="row">
						   <div class="col-xs-12 col-sm-6 col-md-6">
								<ul class="icon-list">
								  <li id="solicitudes_web_datos_contacto_nombre">
									<!--i class="fa fa-check-circle text-success fa-lg pr10"></i-->
									<span> Nombre:</span> <span id="contacto_nombre"></span>
								  </li>
								  <li id="solicitudes_web_datos_contacto_telefono">
									<!--i class="fa fa-check-circle text-success fa-lg pr10"></i-->
									<span> Teléfono:</span> <span id="contacto_telefono"></span>
								  </li>
								  
								</ul> 
							</div>
							
							<div class="col-xs-12 col-sm-6 col-md-6" style="margin-top:2px;margin-left:-370px;">
								<ul class="icon-list">
								  <li id="solicitudes_web_datos_contacto_rut">
									<!--i class="fa fa-check-circle text-success fa-lg pr10"></i-->
									<span> Rut:</span><span id="contacto_rut"></span>
								  </li>
								  <li id="solicitudes_web_datos_contacto_email">
									<!--i class="fa fa-check-circle text-success fa-lg pr10"></i-->
									<span> Email:</span><span id="contacto_email"></span>
								  </li>
								</ul>
							</div>
						</div><!--row -->
					  
					   <div id="solicitudes_web_datos_contacto_acciones" class="row">
							<div class="col-xs-12 col-sm-1 col-md-1 col-sm-offset-5 col-md-offset-5" style="margin-top:-73px;left:85px;">
								<a onclick="editarDatosContacto();" class="btn btn-xs btn-warning btn-block"><i class="fa fa-edit"></i> Editar</a>
							</div>
					   </div>
					 <!--div class row -->
					<!--div id="solicitudes_web_datos_contacto_acciones" class="row">
							<div class="col-xs-12 col-sm-1 col-md-1 col-sm-offset-5 col-md-offset-5">
								<a onclick="editarDatosContacto();" class="btn btn-xs btn-warning btn-block"><i class="fa fa-edit"></i> Editar</a>
							</div>
					</div-->
					
					<!--div id ="datos_solicitud" style="position:absolute;top:172px";-->	
					   <!--h4>Datos de la solicitud</h4-->
					   <!--span style="font-size:14px">Datos de la solicitud</span-->
					 <!--/div-->	
					 <h3>Datos de la solicitud</h3>
					<hr style="margin: 0px 0px !important;">
						<div class="row">
							<div class="col-xs-12 col-sm-6 col-md-6">
								<ul class="icon-list">
								  <li>
									<!--i class="fa fa-check-circle text-success fa-lg pr10"></i-->
									<span> Fecha:</span><span id="solicitud_fecha"></span>
								  </li>
								  <li>
									<!--i class="fa fa-check-circle text-success fa-lg pr10"></i-->
									<span> Canal:</span><span id="solicitud_canal"></span>
								  </li>
								  <li>
									<!--i class="fa fa-check-circle text-success fa-lg pr10"></i-->
									<span> Adultos:</span><span id="solicitud_adultos"></span>
								  </li>
								  <li>
									<!--i class="fa fa-check-circle text-success fa-lg pr10"></i-->
									<span> Edad Niños:</span><span id="solicitud_edad_ninos"></span>
								  </li>
								  <li>
									<!--i class="fa fa-check-circle text-success fa-lg pr10"></i-->
									<span> Fecha Asignación:</span><span id="solicitud_fecha_asignacion"></span>
								  </li>
								  <li>
									<!--i class="fa fa-check-circle text-success fa-lg pr10"></i-->
									<span> Descripción:</span><span id="solicitud_descripcion"></span>
									<!--div style="overflow-x: scroll; max-height: 300px; min-height: 200px;"><div id="solicitud_descripcion"></div></div!-->
								  </li>
								</ul>
							</div>
							
							<div class="col-xs-12 col-sm-6 col-md-6" style="margin-top:2px;margin-left:-370px;">
								<ul class="icon-list">
								  <li>
									<!--i class="fa fa-check-circle text-success fa-lg pr10"></i-->
									<span> Fecha Flexible:</span> <span id="solicitud_fecha_flexible"></span>
								  </li>
								  <li>
									<!--i class="fa fa-check-circle text-success fa-lg pr10"></i-->
									<span> Habitaciones:</span> <span id="solicitud_habitaciones"></span>
								  </li>
								  <li>
									<!--i class="fa fa-check-circle text-success fa-lg pr10"></i-->
									<span> Niños:</span> <span id="solicitud_ninos"></span>
								  </li>
								  <li>
									<!--i class="fa fa-check-circle text-success fa-lg pr10"></i-->
									<span> Fecha Fomulación:</span> <span id="solicitud_fecha_formulacion"></span>
								  </li>
								  <li>
									<!--i class="fa fa-check-circle text-success fa-lg pr10"></i-->
									<span> Agente de viajes:</span> <span id="solicitud_agente_viaje"></span>
								  </li>
								   <li>
									<!--i class="fa fa-check-circle text-success fa-lg pr10"></i-->
									<span> Estado:</span> <span id="solicitud_estado"></span>
								  </li>
								  <li id="estados_solicitudes_web">
									<div class="btn-group"  style="margin-top:-275px;left:255px;">
									  <button style="margin-top: 2px !important; margin-left: 40px !important;" type="button" class="btn btn-success btn-sm"><i class='fa fa-list-alt'></i> Estados</button>
									  <button style="margin-top: 2px !important;" type="button" class="btn btn-success btn-sm dark dropdown-toggle" data-toggle="dropdown">
										<span class="caret"></span>
										<span class="sr-only">Estados</span>
									  </button>
									  <ul style="margin-left: 25%;" class="dropdown-menu" role="menu">
										<li class="solicitudes_web_opciones_primera_llamada">
										  <a onclick="modal_primera_llamada_oportunidades_web(this);" class="solicitudes_web_estados" href="#"><i class="fa fa-crosshairs"></i> Primera Llamada</a>
										</li>
										<li class="solicitudes_web_opciones_seguimiento_1">
										  <a onclick="modal_seguimiento_uno_oportunidades_web(this);" class="solicitudes_web_estados" href="#"><i class="fa fa-crosshairs"></i> Seguimiento 1</a>
										</li>
										<li class="solicitudes_web_opciones_seguimiento_2">
										  <a onclick="modal_seguimiento_dos_oportunidades_web(this);" class="solicitudes_web_estados" href="#"><i class="fa fa-crosshairs"></i> Seguimiento 2</a>
										</li>
										<li class="solicitudes_web_opciones_cerrados_perdidos">
										  <a class="solicitudes_web_estados" onclick="modal_cierre_perdido_oportunidades_web(this);" href="#"><i class="fa fa-crosshairs"></i> Cerrado Perdido</a>
										</li>
										<li class="solicitudes_web_opciones_cerrados_ganados">
										  <a class="solicitudes_web_estados" onclick="modal_cierre_ganador_oportunidades_web(this);" href="#"><i class="fa fa-crosshairs"></i> Cerrado Ganado</a>
										</li>
									  </ul>
									</div>
								  </li>
								</ul>
							</div>
						</div>
						
						
						<!--span style="font-size:14px;margin-bottom: 20px !important;">Productos Solicitados</span!-->
						<h3 style="margin-bottom: 20px !important;">Productos Solicitados</h3>
						
						 <hr style="margin: 0px 0px !important;">
						<br><br>
						<div class="row">
							<table id="ver_detalle_negocio_pasajeros" class="table table-bordered table-striped">
							<thead>
							  <tr>
								<th>Nombre producto</th>
								<th>Operador</th>
								<th>Producto en la Web</th> 
								<th>Link al Programa</th>
								<th>Enviar cotización</th>
							  </tr>
							</thead>
							<tbody id="productos_solicitudes_web">
							  
							</tbody>
						  </table>
						</div>
					  </form>
					</div><!--panel-body bg-light !--> 
				  </div>
			   </div><!--admin-form theme-primary !-->
		
		   </div><!-- col-md-10 -->
        </div><!-- row!--> 
	 </div><!-- modal-body!--> 
	  <input type="hidden" name="solicitudes_web_comentarios" value="">
	  <input type="hidden" name="solicitudes_web_cliente_id" value="">
	  <input type="hidden" name="solicitudes_web_oportunidad_id" value="">
      <div class="modal-footer ">
        <button type="button" id="modal_solicitudes_web_oportunidades_cancel"  class="btn btn-success btn-sm"><span class="glyphicon glyphicon-remove-sign"></span> Listo</button>
	  </div><!-- /.modal-footer -->
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!--modal fade -->