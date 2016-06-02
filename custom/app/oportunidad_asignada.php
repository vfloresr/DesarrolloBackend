<?php  

 ini_set('error_reporting', E_ALL);
 ini_set('display_errors', True);
 global $current_user;
 global $db, $sugar_config;

$id = trim($_REQUEST['id']);

$oportunidad = new Opportunity();
$oportunidad->retrieve($id);

$cuenta = new Account();
$cuenta->retrieve($oportunidad->account_id);

//$oportunidad->fecha_visualizacion_ejecutiva = date('Y-m-d H:i:s');

if($oportunidad->fecha_visualizacion_c =='')
{
  $sql = "update opportunities_cstm set fecha_visualizacion_c=DATE_ADD( now( ) , INTERVAL 4 HOUR ) where id_c='".$id."'";
  $db->Query($sql);
}

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
  
</div>  
<div class="container" id="wrap">
     <div class="row">
      <h2>Solicitud Asignada      </h2>

     </div> 
     <input type="hidden" id="asignado_a" name="asignado_a"  >
     <input type="hidden" id="cantidad" name="cantidad"  value="<?php echo count($list);?>" >
     <div class="row " >
      <h3>Datos del contacto</h3>
      <hr>
        <div class="col-md-6 form-group">
           <label>Nombre:</label> <?php echo $cuenta->name?>
        </div>
        <div class="col-md-6 form-group">
           <label>Rut:</label> <?php echo $cuenta->rut_c?>
        </div>
        <div class="col-md-6 form-group">
           <label>Telefono:</label> <?php echo $cuenta->phone_office?>
        </div>
        <div class="col-md-6 form-group">
           <label>Email:</label> <?php echo $cuenta->email1?>
        </div>
        

     </div>

     <div class="row " >
      <h3>Datos de la solicitud</h3>
      <hr>
        <div class="col-md-6 form-group">
           <label>Fecha:</label> <?php echo $oportunidad->fecha_viaje_c?>
        </div>
        <div class="col-md-6 form-group">
           <label>Fecha Flexible:</label> <?php echo $oportunidad->fecha_flexible_c?>
        </div>
        <div class="col-md-6 form-group">
           <label>Canal:</label> <?php echo $oportunidad->lead_source?>
        </div>
        <div class="col-md-6 form-group">
           <label>Habitaciones:</label> <?php echo $oportunidad->habitaciones_c?>
        </div>
        <div class="col-md-6 form-group">
           <label>Adultos:</label> <?php echo $oportunidad->pasajeros_adultos_c?>
        </div>
        <div class="col-md-6 form-group">
           <label>Niños:</label> <?php echo $oportunidad->pasajeros_ninos_c?>
        </div>
        <div class="col-md-6 form-group">
           <label>Edad Niños:</label> <?php if($oportunidad->edad_1_c!='') echo $oportunidad->edad_1_c?>
           <?php if($oportunidad->edad_2_c!='') echo ",".$oportunidad->edad_2_c?>
           <?php if($oportunidad->edad_3_c!='') echo ",".$oportunidad->edad_3_c?>
           <?php if($oportunidad->edad_4_c!='') echo ",".$oportunidad->edad_4_c?>
           <?php if($oportunidad->edad_5_c!='') echo ",".$oportunidad->edad_5_c?>
           <?php if($oportunidad->edad_6_c!='') echo ",".$oportunidad->edad_6_c?>
           <?php if($oportunidad->edad_7_c!='') echo ",".$oportunidad->edad_7_c?>
           <?php if($oportunidad->edad_8_c!='') echo ",".$oportunidad->edad_8_c?>
           <?php if($oportunidad->edad_9_c!='') echo ",".$oportunidad->edad_9_c?>
           <?php if($oportunidad->edad_10_c!='') echo ",".$oportunidad->edad_10_c?>

        </div>
        <div class="col-md-6 form-group">
           <label>Fecha Formulario:</label> <?php echo $oportunidad->date_entered?>
        </div>
        <div class="col-md-6 form-group">
           <label>Fecha Asignación:</label> <?php echo $oportunidad->fecha_asignacion_c?>
        </div>
        <div class="col-md-6 form-group">
           <label>Agente de viajes:</label> <?php echo $oportunidad->agente_c?>
        </div>
        <div class="col-md-6 form-group">
           <label>Descripción:</label> <?php echo $oportunidad->description?>
        </div>
        

     </div>
      <div class="row">
        <h3>Productos Solicitados</h3>
        <table class="table table-bordered datatable" id="table-3">
          <thead>
            <tr>
              
              <th>Nombre producto</th>
              <th>Operador</th>
              <th>Producto en la Web</th>
              <th>Link al Programa</th>
      
            </tr>
          </thead>
          
          <tbody>
            <?php 
              
              $query = "SELECT prod.id,prod.name , prod_cstm.numero_producto_c,prod_cstm.operador_c,
              prod_cstm.link_producto_c,prod_cstm.link_pdf_c,categ.name as categoria,prod.url
        FROM opportunities_aos_products_1_c rel_op
        inner join aos_products prod on prod.id =rel_op.opportunities_aos_products_1aos_products_idb
        inner join aos_products_cstm prod_cstm on prod_cstm.id_c =prod.id
        left outer join aos_product_categories categ on categ.id =prod.aos_product_category_id
        WHERE rel_op.deleted = 0 and prod.deleted=0 and rel_op.opportunities_aos_products_1opportunities_ida ='".$id."' ";
        $res = $db->Query($query);
        while($row = $db->fetchByAssoc($res)){
            if($row['link_producto_c']=='') $texto_link='-'; else $texto_link="Ir a la web";
          if($row['link_pdf_c']=='') $texto_pdf='-'; else $texto_pdf="Link al producto";


              $simb = array("|","%7C","%7c");
              $simb2 = array("http:/","http://","http:");

              $link_web = str_replace($simb2, "", $row['link_producto_c']);
              $link_pdf = str_replace($simb2, "", $row['link_pdf_c']);

              $posicion = strpos($link_pdf, "/");
              if($posicion == 0){ 
                $link_pdf = "http://".substr($link_pdf, 1);

              }
            ?>
            <tr>
              
          
              <td><?php echo $row['name']?></td>
              <td><?php echo $row['operador_c']?></td>
              <td><?php echo "<a href='http://".$link_web."' target='_blank'>$texto_link</a>"?></td>
              <td>
                <?php 
                  if($row['url']!='') 
                    echo "<a href='".$row['url']."' target='_blank' ><input type='button' class='btn btn-red' value='Cotizar'></a>";
                  else 
                    echo "<a href='".$link_pdf."' target='_blank'>$texto_pdf</a>";

              
                
                ?>
            </td>
            

           
            </tr>
        
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

