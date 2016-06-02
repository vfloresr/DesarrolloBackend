<?php  
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Credentials: true");
	header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
	header('Access-Control-Max-Age: 1000');
	header('Access-Control-Allow-Headers: Origin, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers');
   clearstatcache();
    //ini_set('error_reporting', E_ALL);
    //ini_set('display_errors', True);
    global $current_user;
    global $db, $sugar_config;
   ?>
<!DOCTYPE html>
<html lang="en">
   <?php include ('custom/application/asignador_new/head_new.php'); ?>


   <body>
      <div class="container" id="wrap" style="width:85%">
         <form  name="frm" id="frm"  method="POST" >
            <input type="hidden" id="asignado_a" 	name="asignado_a">
            <input type="hidden" id="tipo" 		name="tipo">
            <!--input type="hidden" id="div_combo" 	name="div_combo"!-->
			<input id='ruta' type = 'hidden' value= '<?php echo $sugar_config['site_url'] ?>'></input>
            <br>
            <div class="form">
               <div class="row" >
                  <div class="col-md-5 form-inline">
                     <label for="seleccione_ejecutivo"><strong>Seleccione un Ejecutivo:</strong></label>
                     <select class="form-control" id="selectEjecutivos"> 
                        <option value='0'>«« SELECCIONE »» </option>
                        <!--option id="opc1"   value ="opc1">Ejecutivo Contact Center</option!-->
                        <option id="opc2"   value ="opc2">Ejecutivos Sucursales</option>
                       <!--  <option id="opc3"   value ="opc3">Ejecutivos Favoritos</option> -->
                        <option id="opc4"   value ="opc4">Ejecutivos Workflow</option>
                     </select>
                  </div>
				 								
				  <div id="ejecutivo_activo" class="col-md-5 form-inline"></div>
				  <div id="btn_asignar" class="col-md-2 form-inline hide">
					<button type='button' onclick='asignar()' id='boton_asignar' class='btn btn-blue'>Asignar</button>
				  </div>
               </div>
            </div>
            <div class="container" id="mensaje_asignando" style="display:none">
               <div class="row">
                  <p class="texto_normal">!!!Espere un Momento, Se Est&aacute;n Asignando los Registros.........</p>
               </div>
            </div>
			 <div class="container" id="mensaje_ejecutivo" style="display:none">
               <div class="row">
                  <p class="texto_normal">!!!Asignacion se asigno Exitosamente........</p>
               </div>
            </div>
			
			<div id="div_ejecutivos"></div>
			<div id="div_asignacion"></div>
					
			<div id="div_datatable">
               <div class="row">
                  <table class="table table-bordered datatable" id="table-3">
                     <thead>
						<tr>
                           <th></th>
                           <th>Nombre Oportunidad</th>
                           <th>Destino</th>
                           <th>Fecha</th>
                           <th>Canal</th>
                           <th>User</th>
                           <th>Viaje</th>
                           <th>Agente</th>
                          <!--<th>Prioridad</th> -->
                        </tr>
                     </thead>
                  </table>
               </div>
            </div>
            <!--div_datatable!-->
         </form>
      </div>
      <!--container !-->
      <div id="resultado"></div>
	  
	<DIV id="popup" style="display: none;">
	  <form  name="form_asignacion" id="form_asignacion"  method="POST" >	
		<div class="content-popup" style="background-color:#E6E6E6;">
		
		  <DIV id='vista' class='vista'>
			<TABLE class='vista' BORDER='0' WIDTH='100%' CELLSPACING='0' CELLPADDING='0'>
			  <TR>
			   <TD WIDTH='100%' class='titulo' align='left'>Asignaci&oacute;n de Supervisores
				 <input type='text' id='material_aux' name='material_aux' style='display:none' value = '0'></input>
			    </TD>
         	   </TR>
			   <br>
			   <TR>
			    <TD WIDTH='100%' colspan='2'>
			      <TABLE class='forms' BORDER='0' WIDTH='100%' CELLSPACING='0' valign='top' CELLPADDING='0'>
	     		   <TR>
				   <TD WIDTH='100%'> 
				    <TABLE width="100%" cellspacing="2" cellpadding="0" BORDER='0'>
					<br>
					  <form class="form-horizontal">
						 <div class="form-group">
							 <label for="inputEmail" class="control-label col-xs-12" style="color:black;">Descripci&oacute;n del Ejecutivo:</label>
							 <div class="col-xs-10">
								 <input type="text" class="form-control" id="desc_agente" disabled style="width:120%;">
							 </div>
						 </div>
						 <br><br><br>
						 <div class="form-group">
							 <label for="inputName" class="control-label col-xs-12" style="color:black;">Nombre:</label>
							 <div class="col-xs-10">
								<SELECT name="mail_modal_ejecutivos_value" id="mail_modal_ejecutivos_value" class="form-control" style="width:120%;" ></SELECT>
							 </div>
						 </div>
						
						<br><br>
						 <div class="form-group">
							 <div class="col-xs-offset-4 col-xs-10" style="margin-top:10px;">
								 <input type="button" class="btn btn-primary" id="asignacion" style="background-color:#005993;" value="Asignar" >
								  <input type="button" class="btn btn-primary" id="close"     style="background-color:#005993;" value="Cerrar">
							 </div>
						 </div>
						 <input type="hidden" id="id_oportunidades" value="">
					   </form>
				        </TABLE>
			           </TD>
				      </TR>
				     </TABLE>
			       </TD>
				  </TR>
			    </TABLE>
			  </DIV>
		     </DIV>
		  </form>
	   </DIV>
      <?php include ('custom/application/asignador_new/scripts_new.php');  ?>
   </body>
</html>