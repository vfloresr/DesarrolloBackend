<?php  
clearstatcache();
 //ini_set('error_reporting', E_ALL);
 //ini_set('display_errors', True);
 global $current_user;
 global $db, $sugar_config;

$id = trim($_REQUEST['id']);
$tipo = trim($_REQUEST['tipo']);
$negocio = new Opportunity();
$list = $negocio->get_full_list("opportunities.date_created desc", "opportunities.assigned_user_id = '1' and opportunities.sales_stage='RecepcionSolicitud' ",true);
            
$usuarios = new User();
$list_contact = $usuarios->get_full_list("users.full_name", "users_cstm.sucursal_c = 'CONTACT' and users.status='Active'  ",true);
$list_externos = $usuarios->get_full_list("users.last_name", " (users_cstm.sucursal_c = 'EXTERNOS' OR users_cstm.sucursal_c = 'PROTOTIPO') and users.status='Active'  ",true);
$list_favoritos = $usuarios->get_full_list("users.last_name", "users_cstm.favorito_c = 1 and users.status='Active' ",true);

//echo "-->".$sugar_config['site_url'];
//print_r($sugar_config);
  ?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Formulario de contacto</title>
<!-- Bootstrap core CSS -->
<link href="http://internet.cocha.com/css/bootstrap.css" rel="stylesheet">
<!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        <link rel="stylesheet" href="assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
  <link rel="stylesheet" href="assets/css/font-icons/entypo/css/entypo.css">
  <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
  <link rel="stylesheet" href="assets/css/bootstrap.css">
  <link rel="stylesheet" href="assets/css/neon-core.css">
  <link rel="stylesheet" href="assets/css/neon-theme.css">
  <link rel="stylesheet" href="assets/css/neon-forms.css">
  <link rel="stylesheet" href="assets/css/custom.css">
</head>
<body>



<form name="frm" id="frm"  method="POST" >
<div class="container" id="mensaje_asignando" style="display:none">
     <div class="row">
      <br>
      <br>
      <br>
      <br>
      <h1>Espere un momento, se están asignando los registro</h1>
      </div>  
</div>  
<div class="container" id="wrap">
     <div class="row">
      <h1>Oportunidades sin Asignar<br>
       
      </h1>

     </div> 
     <input type="hidden" id="asignado_a" name="asignado_a"  >
     <input type="hidden" id="cantidad" name="cantidad"  value="<?php echo count($list);?>" >
     <div class="row " >
        <div class="col-md-4 form-group">
           <label>Ejecutivos Contact Center</label>
            <select class="form-control" name="user_contact" id="user_contact">
              <option value="">Seleccione</option>
              <?php 
			  asort($list_contact); 
			  foreach ( $list_contact as $u ) { 
          $cantidad=0;
                $query2 = "SELECT count(*) as cantidad FROM opportunities o
        inner join opportunities_cstm oc  ON o.id = oc.id_c
        where o.assigned_user_id='".$u->id."' and o.deleted=0 and date(oc.fecha_asignacion_c)=date(now()) ";
        $res2 = $db->Query($query2);
        if($row2 = $db->fetchByAssoc($res2)){
          $cantidad= $row2['cantidad'];
         
        }

          ?>
              <option value="<?php echo $u->id?>"><?php echo $u->full_name?> - (<?php echo $cantidad?>) </option>
              <?php }?>

            </select>
        </div>
        
        <div class="col-md-4 form-group">
          <label>Ejecutivos Sucursales</label>
            <select class="form-control" name="user_externo" id="user_externo">
              <option value="">Seleccione</option>
               <?php 
			   asort($list_externos); 
			  // foreach ( $list_externos as $u ) { 

          foreach ( $list_externos as $u ) { 
            $cantidad=0;
                $query2 = "SELECT count(*) as cantidad FROM opportunities o
        inner join opportunities_cstm oc  ON o.id = oc.id_c
        where o.assigned_user_id='".$u->id."' and o.deleted=0 and date(oc.fecha_asignacion_c)=date(now()) ";
        $res2 = $db->Query($query2);
        if($row2 = $db->fetchByAssoc($res2)){
          $cantidad= $row2['cantidad'];
         
        }
          ?>
              <option value="<?php echo $u->id?>"><?php echo $u->full_name?> - (<?php echo $cantidad?>) </option>
              <?php }?>
            </select>
        </div>

        <div class="col-md-4 form-group">
          <label>Ejecutivos Favoritos</label>
            <select class="form-control" name="user_favorito" id="user_favorito">
              <option value="">Seleccione</option>
               <?php 
         asort($list_favoritos); 
        // foreach ( $list_externos as $u ) { 

          foreach ( $list_favoritos as $u ) { 
            $cantidad=0;
                $query2 = "SELECT count(*) as cantidad FROM opportunities o
        inner join opportunities_cstm oc  ON o.id = oc.id_c
        where o.assigned_user_id='".$u->id."' and o.deleted=0 and date(oc.fecha_asignacion_c)=date(now()) ";
        $res2 = $db->Query($query2);
        if($row2 = $db->fetchByAssoc($res2)){
          $cantidad= $row2['cantidad'];
         
        }
          ?>
              <option value="<?php echo $u->id?>"><?php echo $u->full_name?> - (<?php echo $cantidad?>) </option>
              <?php }?>
            </select>
        </div>
        <div class="col-md-12 form-group">
         <button type="button" onclick="asignar()" id="boton_asignar" class="btn btn-blue">Asignar</button>
            
        </div>
     </div>
      <div class="row">
      
        <table class="table table-bordered datatable" id="table-3">
          <thead>
            <tr>
              
              <th></th>
              <th>Nombre Oportunidad</th>
              <th>Fecha Creación</th>
              <th>Cuenta</th>
              <th>Canal</th>
              <th>Etapa de Ventas</th>
              <th>Fecha Viaje</th>
              <th>Agente</th>

             
            </tr>
          </thead>
          
          <tbody>
            <?php 
              
               
$i=1;
  foreach ( $list as $opp ) { 
      $oport = new Opportunity();
      $oport->retrieve($opp->id);
      //echo "<pre>"; print_r($oport);die();

            ?>
            <tr>
              
              <td>
              <input type="hidden" name="id_oportunidad-<?php echo $i?>" id="id_oportunidad-<?php echo $i?>" value="<?php echo $opp->id?>"  >
              <input type="checkbox" name="id-<?php echo $i?>" id="id-<?php echo $i?>"  ></td>
              <td><a href="http://104.131.0.44/crm/index.php?module=Opportunities&action=DetailView&record=<?php echo $opp->id?>" target="_blank"><?php echo $opp->name?></a></td>
              <td><?php echo $opp->date_entered?></td>
              <td><?php echo $oport->account_name?></td>
              <td><?php echo $opp->lead_source?></td>
              <td><?php echo $opp->sales_stage?></td>
              <td><?php echo $opp->fecha_viaje_c?></td>
               <td><?php echo $opp->agente_c?></td>

           
            </tr>
            <?php  $i++?>
          <?php }?>
          </tbody>
        </table>
         
</div>     



</div>
</form>

<div id="resultado"></div>
<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="http://internet.cocha.com/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<script src="http://104.131.0.44/crm/custom/modules/Opportunities/cambia_estado.js"></script>
<script>

  $( document ).ready(function() {
    
      var table = $("#table-3").dataTable({
          "sPaginationType": "bootstrap",
          "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
          "sDom": "<'row'<'col-xs-6 col-left'l><'col-xs-6 col-right'>r>t<'row'<'col-xs-6 col-left'i><'col-xs-6 col-right'p>>",
           "bStateSave": false,
           "order": [[ 2, "asc" ]],
           "paging":   false,
         
        });


        

    $( "#textoemail" ).hide();
        $( "#id_plantilla_select" ).change(function() {
              id_plantilla= $( "#id_plantilla_select" ).val();
                       carga_plantilla(id_plantilla)
        });

  });

  
 
	 function asignar(){
    result=1;
    if($("#user_externo").val() != '' && $("#user_contact").val() != '')
      {
        alert('Debe seleccionar un usuario solamente')
          $("#asignado_a").val('');
          result=0;
     }   
    else if($("#user_externo").val() != '' )  
      asignado_a = $("#user_externo").val();
    else if($("#user_contact").val() != '' )  
      asignado_a = $("#user_contact").val();
    else if($("#user_favorito").val() != '' )  
      asignado_a = $("#user_favorito").val();

    $("#asignado_a").val(asignado_a);


    if($("#user_externo").val() == '' && $("#user_contact").val() == '' && $("#user_favorito").val() == '')
      {
        alert('Debe seleccionar un ejecutivo')
          $("#asignado_a").val('');
          result=0;
     }   

    var opp = $('input:checkbox:checked').length
    if(result==1)
    {
      $("#wrap").hide();
      $("#mensaje_asignando").show();

    if( confirm("Se asignará "+opp+" oportunidade(s)")){
        $('#boton_asignar').attr("disabled", true);
          $.ajax({
            type: 'POST',
        	url: "http://104.131.0.44/crm/index.php?entryPoint=grabaoportunidades",
          data: $("#frm").serialize() ,
      	
          success: function( data ) {
              
              window.location.reload();
             //$("#resultado").html(data);    

          }

        });
      }
     }
         } 

 




        </script>

  <!-- Imported styles on this page -->
  <link rel="stylesheet" href="assets/js/wysihtml5/bootstrap-wysihtml5.css">
  <link rel="stylesheet" href="assets/js/codemirror/lib/codemirror.css">
  <link rel="stylesheet" href="assets/js/uikit/css/uikit.min.css">
  <link rel="stylesheet" href="assets/js/uikit/addons/css/markdownarea.css">
  <link rel="stylesheet" href="assets/js/datatables/responsive/css/datatables.responsive.css">

  <!-- Bottom scripts (common) -->
  <script src="assets/js/jquery.dataTables.min.js"></script>
  <script src="assets/js/datatables/TableTools.min.js"></script>
    <script src="assets/js/dataTables.bootstrap.js"></script>
  <script src="assets/js/datatables/jquery.dataTables.columnFilter.js"></script>
  <script src="assets/js/datatables/lodash.min.js"></script>
  <script src="assets/js/datatables/responsive/js/datatables.responsive.js"></script>

  <script src="assets/js/gsap/main-gsap.js"></script>
  <script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
  <script src="assets/js/bootstrap.js"></script>
  <script src="assets/js/joinable.js"></script>
  <script src="assets/js/resizeable.js"></script>
  <script src="assets/js/neon-api.js"></script>
  <script src="assets/js/wysihtml5/wysihtml5-0.4.0pre.min.js"></script>
<script src="assets/js/fileinput.js"></script>

  <!-- Imported scripts on this page -->
  <script src="assets/js/wysihtml5/bootstrap-wysihtml5.js"></script>
  <script src="assets/js/ckeditor/ckeditor.js"></script>
  <script src="assets/js/ckeditor/adapters/jquery.js"></script>
  <script src="assets/js/uikit/js/uikit.min.js"></script>
  <script src="assets/js/codemirror/lib/codemirror.js"></script>
  <script src="assets/js/marked.js"></script>
  <script src="assets/js/uikit/addons/js/markdownarea.min.js"></script>
  <script src="assets/js/codemirror/mode/markdown/markdown.js"></script>
  <script src="assets/js/codemirror/addon/mode/overlay.js"></script>
  <script src="assets/js/codemirror/mode/xml/xml.js"></script>
  <script src="assets/js/codemirror/mode/gfm/gfm.js"></script>
  <script src="assets/js/icheck/icheck.min.js"></script>
  <script src="assets/js/neon-chat.js"></script>


  <!-- JavaScripts initializations and stuff -->
  <script src="assets/js/neon-custom.js"></script>


  <!-- Demo Settings -->
  <script src="assets/js/neon-demo.js"></script>
</body>
</html>
<?php
if(isset($_POST['asignado_a'])) 
{
  echo "----------->". $asignado_a = $_POST['asignado_a'] ;
}





?>
