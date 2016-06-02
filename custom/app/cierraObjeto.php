<?php  
 global $current_user;
  global $db, $sugar_config;

 $id_registro = trim($_GET['id_registro']);
 $tipo = trim($_GET['tipo']);
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
         <h3>Cierre <?php echo $tipo?></h3>
         <div id="cierra_ganado" <?php if($estado != 'CerradoGanado' or $tipo != 'negocio' ) echo 'style="display:none"'?>>
         <div class="col-xs-6 col-md-6">
          <label>Producto</label>
             <select class="form-control" id="region"   name="region"        >
            <option value="" selected="selected"></option>
            <option value="Hotel"  >Hotel</option>
            <option value="Asistencia"  >Asistencia</option>
            <option value="Auto"  >Auto</option>
       
         
          </select>
        </div>
        <div class="col-xs-6 col-md-6">
          <label>Monto</label>
          <input class="form-control" id="monto" placeholder="monto" type="text" />
        </div>
      </div>
<div class="col-xs-6 col-md-6" <?php if($estado != 'CerradoPerdido' or $tipo != 'negocio' ) echo 'style="display:none"'?>>
          <label>Motivo no Ganado</label>
             <select class="form-control" id="region"   name="region"        >
            <option value="" selected="selected"></option>
            <option value="Destino Familiar"  >Destino Familiar</option>
            <option value="Negocios"  >Negocios</option>
            <option value="No Interesa"  >No Interesa</option>
       
         
          </select>
        </div>

        <div class="col-xs-12 col-md-12">
          <label>Comentarios</label>
          <input type="hidden" id="estado" value="<?php echo $estado?>">
          <input type="hidden" id="id_registro" value="<?php echo $id_registro?>">
          <input type="hidden" id="tipo" value="<?php echo $tipo?>">
          <textarea cols="200" rows="4" class="form-control" id="descripcion" placeholder="Comentarios"></textarea>
        </div>
      </div>
       <br>
      
      
      <button class="btn btn-primary pull-left" id="submit">Guardar</button>
     
     


<br>

  </div>
</div>

<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="http://internet.cocha.com/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
    <script src="http://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>

<script>
 $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '<Ant',
        nextText: 'Sig>',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
    $.datepicker.setDefaults($.datepicker.regional['es']);
  $(function() {
    $( "#date_start" ).datepicker({
      numberOfMonths: 1,
      showButtonPanel: true
    });
  });
   jQuery( function(){
      jQuery( document ).trigger( "enhance" );

    });
            decode = function(s) {
                return decodeURIComponent(s.split("+").join(" "));
            }
        
            $("#submit").click(function() {
              

                var tipo              = $("#tipo").val();
                var id_registro              = $("#id_registro").val();
                var descripcion              = $("#descripcion").val();
                var estado              = $("#estado").val();
                var region              = $("#region").val();
                var destino              = $("#destino").val();

               
                if(valida_campos()){  
                        $("#mensajes").removeClass("text-danger");
                        $("#mensajes").removeClass("text-success");
                        $("#mensajes").removeClass("error-pad")
                }else return false;

                $.ajax({
                    type: "POST",
                    url: "http://104.131.0.44/ripley2/index.php?entryPoint=cierraObjeto",
                    data: {
                       "descripcion": descripcion,
                        "estado":estado,
                        "id_registro":id_registro,
                        "tipo":tipo,
                        "region":region,
                        "destino":destino


               
                    },
                    
                    beforeSend: function(){
                        $("#submit").attr("disabled", true);
                    },
                    complete: function(){
                        $("#submit").removeAttr("disabled");
                    },
                    success: function( data ){
                       window.close();
                       window.opener.location.reload();
                       //$("#wrap").html(data);
                       
                    }
                });
            });

         function valida_campos(){

                var estado              = $("#estado").val();
                var descripcion              = $("#descripcion").val();
                if(estado == 'CerradoPerdido' && descripcion=='')
                {
                  alert('Para Cerrar es necesario el motivo de la no venta')
                  return false;
                }    
                return true;
                
         } 





        </script>
</body>
</html>
<?php 
if(isset($_POST['estado'])){
  ini_set('error_reporting', E_ALL);
  ini_set('display_errors', True);



 

  
 echo $descripcion = trim($_POST['descripcion']);//asunto
  $estado = trim($_POST['estado']);//asunto
 echo $id_registro = trim($_REQUEST['id_registro']);//asunto
 echo  $tipo = trim($_REQUEST['tipo']);//asunto


  if($tipo=='tarea'){
    $tarea = new Task();
    $tarea->retrieve($id_registro);
    $tarea->status='Cerrada';
    $tarea->description=$descripcion;
    $tarea->save();


  }else{

    $negocio = new CRM_negocios();
    $negocio->retrieve($id_registro);
    $negocio->estado_c=$estado;
    $negocio->description=$descripcion;
    $negocio->save();

  }

  
}//fin if post
?>