<?php  
 ini_set('error_reporting', E_ALL);
 ini_set('display_errors', True);
 global $current_user;
 global $db, $sugar_config;

 $id_registro = trim($_GET['id_registro']);
 $estado = trim($_GET['estado']);
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Formulario de Cierre</title>
<!-- Bootstrap core CSS -->
<link href="http://internet.cocha.com/css/bootstrap.css" rel="stylesheet">
<!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
</head>
<body>

<div class="container" id="wrap">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
       

      
      
      
      <div class="row">
      	<h3>Cierre Solicitud Web</h3>
       
        
         <div id="cierra_ganado" <?php if($estado != 'CerradoGanado'  ) echo 'style="display:none"'?>>
         
        </div>
        <div class="col-xs-6 col-md-6" <?php if($estado == 'CerradoPerdido'  ) echo 'style="display:none"'?>>
          <label>Monto [US$]</label>
          <input class="form-control" id="monto" placeholder="monto" type="text" />
        </div>

        <div class="col-xs-6 col-md-6" <?php if($estado != 'CerradoGanado'  ) echo 'style="display:none"'?>>
                   <label>Número Negocio </label>
                    <input type="text" class="form-control" name="negocio" id="negocio"   >
          </div>
      </div>
<div class="col-xs-6 col-md-6" <?php if($estado != 'CerradoPerdido' ) echo 'style="display:none"'?>>
          <label>Motivo no Ganado</label>
          <select class="form-control" id="motivo_cierre"   name="motivo_cierre"        >
            <option value="" selected="selected"></option>
            <option value="Visita Familiar o Amigo"  >Visita Familiar o Amigo</option>
            <option value="Negocios"  >Viaje de Negocios</option>
            <option value="No Interesa"  >No Interesa</option>
            <option value="Convenio Directo"  >Convenio Directo</option>
            <option value="Otra"  >Otra</option>
          </select>
        </div>

        
        <div class="col-xs-12 col-md-12">
          <label>Comentarios</label>
          <input type="hidden" name="estado" id="estado" value="<?php echo $estado?>">
          <input type="hidden" id="id_registro" value="<?php echo $id_registro?>">

          <textarea cols="200" rows="4" class="form-control" id="descripcion" name="descripcion" placeholder="Comentarios"></textarea>
        </div>
      </div>
       <br>
      
      
      <button class="btn btn-primary pull-left" id="submit">Guardar</button>
     


  </div>
</div>

<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="http://internet.cocha.com/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
    <script src="http://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>

<script>

 

    

            jQuery("#submit").click(function() {
              


                var id_registro              = jQuery("#id_registro").val();
                var descripcion              = jQuery("#descripcion").val();
                var estado              = jQuery("#estado").val();
                var negocio              = jQuery("#negocio").val();
                var monto              = jQuery("#monto").val();
                var motivo_cierre              = jQuery("#motivo_cierre").val();
                 var descripcion              = jQuery("#descripcion").val();


                

              //  var asistencia              = jQuery("#asistencia").val();
              //  var auto              = jQuery("#auto").val();

               
                if(valida_campos()){  
                        jQuery("#mensajes").removeClass("text-danger");
                        jQuery("#mensajes").removeClass("text-success");
                        jQuery("#mensajes").removeClass("error-pad")
                }else return false;

                jQuery.ajax({
                    type: "POST",
                    url: "http://104.131.0.44/crm/index.php?entryPoint=cierra_solicitud_ripley",
                    data: {
                       "descripcion": descripcion,
                        "estado":estado,
                        "id_registro":id_registro,
                        "negocio":negocio,
                        "monto":monto,
      					"motivo_cierre":motivo_cierre


               
                    },
                    
                    beforeSend: function(){
                        jQuery("#submit").attr("disabled", true);
                    },
                    complete: function(){
                        jQuery("#submit").removeAttr("disabled");
                    },
                    success: function( data ){
                        window.close();
                     	window.opener.location.replace("http://104.131.0.44/ripley/index.php?entryPoint=HomeContact");
                       
                       //window.opener.location.reload();
                       
                       //jQuery("#wrap").html(data);
                       
                    }
                });
            });

         function valida_campos(){

                var estado              = jQuery("#estado").val();
                var descripcion              = jQuery("#descripcion").val();
                
                if(estado == 'CerradoPerdido'  && jQuery("#motivo_cierre").val() =='' )
                {
                  alert('Para Cerrar es necesario el motivo de la no venta')
                  return false;
                }    
               /* if(estado == 'CerradoGanado'  &&	 jQuery("#negocio").val() =='' )
                {
                  alert('Para Cerrar es necesario el numero de negocio')
                  return false;
                }    */
                return true;
                
         } 





        </script>
</body>
</html>

<?php

if(isset($_POST['id_registro']))
 {

	echo "<br>".$descripcion = trim($_POST['descripcion']);//asunto
	$estado = trim($_POST['estado']);//asunto
	$negocio = trim($_POST['negocio']);//asunto
	echo "<br>id registro ". $id_registro = trim($_REQUEST['id_registro']);//asunto

	echo "<br>-->".$motivo_cierre = trim($_REQUEST['motivo_cierre']);//asunto
		echo "<br>-->".$monto = trim($_REQUEST['monto']);//asunto


     $sql = "update opportunities_cstm set procesado_externo_c='1' , observacion_cierre_c='$descripcion'";
 if($monto != '')$sql .= " , monto_cierre_c=$monto ";
  if($estado != '')$sql .= " , estado_cierre_c='$estado' ";
  if($motivo_cierre != '')$sql .= " , motivo_no_ganado_c='$motivo_cierre' ";
  if($negocio != '') $sql .= " , negocio_c='$negocio' ";

  echo "<br><br>". $sql .= " where id_c='".$id_registro."'";
     $db->Query($sql);
 } 



  ?>
