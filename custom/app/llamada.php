<?php  
 global $current_user;
  global $db, $sugar_config;

 $id_registro = trim($_GET['id_registro']);
 $tipo = trim($_GET['tipo']);
 $id_contacto = trim($_GET['id_contacto']);

if($id_contacto!=''){
   $query = "SELECT  c.id,c.name,c.description
        FROM calls c
        inner join calls_contacts cc on cc.call_id=c.id
        WHERE c.status = 'Planned'
        AND c.deleted =0 
        AND cc.contact_id= '".$id_contacto."' 
           LIMIT 0 , 1";
        $res = $db->Query($query);

        if($row = $db->fetchByAssoc($res)){
              $id_llamada=$row['id'];
              $name=$row['name'];
              $description=$row['description'];
              
        }      
       
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
</head>
<body>

<div class="container" id="wrap">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
       <div class="row">
        
          <h3>Genera Llamada</h3>
          <p id="mensajes" class="pull-left" style="padding-left:12px; padding-top:6px;"></p>
          <br>
          <br>
          <div class="col-xs-8 col-md-2">
            <label>Asunto</label>
            <input class="form-control"  id="id_registro" value="<?php echo $id_registro?>"   type="hidden" /> 
            <input class="form-control"  id="id_llamada" value="<?php echo $id_llamada?>"   type="hidden" /> 
            <input class="form-control"  id="tipo" value="<?php echo $tipo?>"   type="hidden" />            
            <input class="form-control" id="asunto" placeholder="Asunto"  value="<?php echo $name?>"  type="text" />
          </div>
  
      </div>

      
      
      
      <div class="row">
        <div class="col-xs-12 col-md-12">
          <label>Comentarios</label>
          <textarea cols="200" rows="4" class="form-control" id="descripcion" placeholder="Comentarios"><?php echo $description?></textarea>
        </div>
      </div>
      <div class="row"><label>Volver a Llamar</label>
        <div class="col-xs-12 col-md-12">
          
          <div class="col-xs-6 col-md-6">
          <input type="text" class="form-control" id="date_start">
         </div>
         <div class="col-xs-3 col-md-3">
          <select id="hora" class="form-control"> 
            <option ></option>
            <option value="07">07</option>
            <option value="08">08</option>
            <option value="09" >09</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
            <option value="13">13</option>
            <option value="14">14</option>
            <option value="15">15</option>
            <option value="16">16</option>
            <option value="17">17</option>
            <option value="18">18</option>
            <option value="19">19</option>
            <option value="20">20</option>
            <option value="21">21</option>
            <option value="22">22</option>
            <option value="23">23</option>

          </select>
         
         </div>
         <div class="col-xs-3 col-md-3">
          
                    <select id="minuto" class="form-control"> 
            <option value="00" selected>00</option>
            <option value="15">15</option>
            <option value="30">30</option>
            <option value="45">45</option>
            


          </select>
         </div>
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
              
                var asunto             	 = $("#asunto").val();
                var descripcion           	 = $("#descripcion").val();
                var tipo              = $("#tipo").val();
                var id_registro              = $("#id_registro").val();
                var date_start              = $("#date_start").val();
                var hora              = $("#hora").val();
                var minuto              = $("#minuto").val();
                 var id_llamada              = $("#id_llamada").val();




                  
                if(valida_campos()){  
                        $("#mensajes").removeClass("text-danger");
                        $("#mensajes").removeClass("text-success");
                        $("#mensajes").removeClass("error-pad")
                }else return false;

                $.ajax({
                    type: "POST",
                    url: "http://104.131.0.44/ripley2/index.php?entryPoint=llamada",
                    data: {
                        "asunto": asunto,
                        "descripcion": descripcion,
                        "id_registro": id_registro,
                        "tipo": tipo,
                        "date_start": date_start,
                        "hora": hora,
                        "minuto": minuto,
                        "id_llamada": id_llamada


               
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
                       //$("#mensajes").html(data);
                       
                    }
                });
            });

         function valida_campos(){

                $("#mensajes").addClass("text-danger");
                $("#mensajes").addClass(".error-pad")
                                
                var asunto                = $("#asunto").val();
                var descripcion             = $("#descripcion").val();
                if(descripcion =='' ){$("#mensajes").html("Debe ingresar descripcion de la llamada");return false;}
                if(asunto =='' ){$("#mensajes").html("Debe ingresar asunto de la llamada");return false;}
                
                return true;
                
         } 





        </script>
</body>
</html>
<?php 
if(isset($_POST['asunto'])){
  ini_set('error_reporting', E_ALL);
  ini_set('display_errors', True);



 

  $asunto = trim($_POST['asunto']);//asunto
  $descripcion = trim($_POST['descripcion']);//asunto
 echo "(((((((((((((((((((((((". $id_registro = trim($_REQUEST['id_registro']);//asunto
  $tipo = trim($_REQUEST['tipo']);//asunto
  $fecha_volver = trim($_REQUEST['date_start']);//asunto
  $hora = trim($_REQUEST['hora']);//asunto
  $minuto = trim($_REQUEST['minuto']);//asunto
  echo "################".$id_llamada = trim($_REQUEST['id_llamada']);//asunto}

 if($tipo=='postventa'){
    $tarea = new CRM_postventa();
    $relacion = 'CRM_postventa';
    if($id_llamada !='')  $tarea->retrieve($id_llamada);
    else $tarea->retrieve($id_registro);
    echo "--->id_contact ".$id_contacto = $tarea->contact_id_c;


  }else
  if($tipo=='tarea'){
    $tarea = new Task();
    $relacion = 'Tasks';
    if($id_llamada !='')  $tarea->retrieve($id_llamada);
    else $tarea->retrieve($id_registro);
    echo "--->id_contact ".$id_contacto = $tarea->contact_id;


  }else{

    $negocio = new CRM_negocios();

    if($id_llamada !='')  $negocio->retrieve($id_llamada);
    else $negocio->retrieve($id_registro);


 
    $relacion = 'CRM_negocios';
    $contact_obj = $negocio->get_linked_beans('crm_negocios_contacts','Contact'); 


    foreach ( $contact_obj as $contacto ) { 
          if($contacto->tipo_c == 'Comprador' or $contacto->tipo_c == '')
          { 
              $id_contacto= $contacto->id;

          } 
    }


   



  }

  $div = explode("/",$fecha_volver); 
   $nueva_fecha = $div[2]."-".$div[1]."-".$div[0]." $hora:$minuto:00";

   echo "<br><br>".$relacion;
//die();
   $TimeDate = new TimeDate(); 
    $llamada = new Call();
   if($id_llamada !='')
    $llamada->retrieve($id_llamada);
 
  $llamada->name=$asunto;
  $llamada->description=$descripcion;
  $llamada->status="Held";
  $llamada->direction="Outbound";
  $llamada->parent_type=$relacion;
  $llamada->parent_id=$id_registro;
  $llamada->duration_hous=0;
  $llamada->duration_minutes=15;
  $llamada->assigned_user_id=$current_user->id;
  if($fecha_volver != ''){ 
    $llamada->date_start= $nueva_fecha;
    //TimeDate::getInstance()->to_db_date($nueva_fecha, true);
    $llamada->status="Planned";
    
  }else
  $llamada->date_start=TimeDate::getInstance()->getNow(true)->modify("+0 days")->asDb();
 $llamada->date_end=TimeDate::getInstance()->getNow(true)->modify("+15 min")->asDb();
  $llamada->save();
  $llamada->load_relationship('contacts');
  $llamada->contacts->add($id_contacto);
  $llamada->save();

   $query = "update calls set date_start='$nueva_fecha $hora:$minuto'
        WHERE id= '".$llamada->id."' 
        ";
       //$db->Query($query);


  





}//fin if post
?>