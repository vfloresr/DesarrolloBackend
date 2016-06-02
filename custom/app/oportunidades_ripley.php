<?php  
clearstatcache();
 //ini_set('error_reporting', E_ALL);
 //ini_set('display_errors', True);
 global $db, $sugar_config;

$user_name = trim($_REQUEST['user_name']);
$cerradas = trim($_REQUEST['cerradas']);
$cantidad = trim($_REQUEST['cantidad']);


$usuario = new User();
$usuario->retrieve_by_string_fields(array('user_name' => $user_name));

$negocio = new Opportunity();
$query = "opportunities.assigned_user_id = '".$usuario->id."' ";//and opportunities_cstm.fecha_asignacion_c >= DATE_ADD( now( ) , INTERVAL -4 WEEK )
if($cerradas==1) $query .= " and opportunities_cstm.procesado_externo_c='1' " ;
else $query .= " and opportunities_cstm.procesado_externo_c <> '1' " ;
//echo $query;
$list = $negocio->get_full_list("opportunities.fecha_asignacion_c desc", $query,true);
 

if($cantidad==1){
  echo count($list);die();
}            




  ?>

<table class="table table-bordered table-responsive">
        <thead>
               <tr class="success">
                  <th style="color:#0B610B"></th>
                  <th style="color:#0B610B">Fecha Viaje</th>
                  <th style="color:#0B610B">Nombre Contacto</th>
                <th style="color:#0B610B">Rut</th>
               <th style="color:#0B610B">Telefono</th>
               <th style="color:#0B610B">Email</th>
               <th style="color:#0B610B">Fecha Asignación</th>
               <th style="color:#0B610B"></th>


                   
                   
              </tr>
        </thead>
        
        <tbody>

              <?php foreach($list as $n){
                $oportunidad = new Opportunity();
                $oportunidad->retrieve($n->id);
                $cuenta = new Account();
                
                $cuenta->retrieve($oportunidad->account_id);
                ?>
              <tr >
                <td>
        

                <button type="button" onclick="cierra_solicitud('<?php echo $n->id;?>','CerradoGanado')" class="btn btn-success btn-xs">
                <i class="entypo-check"></i>
              </button>
                  <button type="button" onclick="cierra_solicitud('<?php echo $n->id;?>','CerradoPerdido')" class="btn btn-danger btn-xs">
                <i class="entypo-cancel"></i>
              </button>

            </td>
                
                <td><?php echo substr($n->fecha_viaje_c, 3, 2)."/".substr($n->fecha_viaje_c, 0, 2)."/".substr($n->fecha_viaje_c, 6, 4);?></td>  
                <td><?php echo $n->name;?></td>
                <td><?php echo $cuenta->rut_c;?></td>
                <td><?php echo $cuenta->phone_office;?></td>
                <td><?php echo $cuenta->email1;?></td>
                <td><?php echo substr($n->fecha_asignacion_c, 3, 2)."/".substr($n->fecha_asignacion_c, 0, 2)."/".substr($n->fecha_asignacion_c, 6, 4)." ".substr($n->fecha_asignacion_c, 11, 5);?></td>
                <td style="color:#0B610B">
                  <a href="javascript:void(0)" onclick="oportunidad_asignada('http://104.131.0.44/crm/index.php?entryPoint=oportunidad_asignada&id=<?php echo $n->id?>')"    ><input type="button" class='btn btn-red' value="Ver Detalle"></a></td>
               
              </tr>
              <?php }?>
              
        </tbody>
      </table>  
