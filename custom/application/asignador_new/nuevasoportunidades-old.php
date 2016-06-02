<?php  
   clearstatcache();
    //ini_set('error_reporting', E_ALL);
    //ini_set('display_errors', True);
    global $current_user;
    global $db, $sugar_config;
   ?>
<!DOCTYPE html>
<html lang="en">
   <?php include ('custom/application/asignador/head.php'); ?>
   <body>
      <div class="container" id="wrap">
         <form  name="frm" id="frm"  method="POST" >
            <input type="hidden" id="asignado_a" 	name="asignado_a">
            <input type="hidden" id="tipo" 		name="tipo">
            <input type="hidden" id="div_combo" 	name="div_combo">
			<input id='ruta' type = 'hidden' value= '<?php echo $sugar_config['site_url'] ?>'></input>
            <br>
            <div class="form">
               <div class="row" >
                  <div class="col-md-5 form-inline">
                     <label for="seleccione_ejecutivo">Seleccione un Ejecutivo:</label>
                     <select class="form-control" id="selectEjecutivos">
                        <option value='0'>SELECCIONE </option>
                        <option id="opc1"   value ="opc1">Ejecutivo Contact Center</option>
                        <option id="opc2"   value ="opc2">Ejecutivos Sucursales</option>
                        <option id="opc3"   value ="opc3">Ejecutivos Favoritos</option>
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
			<div id="div_ejecutivos"></div>
			<div id="div_datatable">
               <div class="row">
                  <table class="table table-bordered datatable" id="table-3">
                     <thead>
						<tr>
                           <th></th>
                           <th>Nombre Oportunidad</th>
                           <th>Destino</th>
                           <th>Fecha Creaci&oacute;n</th>
                           <th>Canal</th>
                           <th>Etapa de Ventas</th>
                           <th>Fecha Viaje</th>
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
      <?php include ('custom/application/asignador/scripts.php');  ?>
   </body>
</html>