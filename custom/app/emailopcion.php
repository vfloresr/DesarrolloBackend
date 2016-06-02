<?php  
 //ini_set('error_reporting', E_ALL);
 //ini_set('display_errors', True);
 global $current_user;
 global $db, $sugar_config;

  $id_registro = trim($_REQUEST['id_registro']);
  $tipo = trim($_REQUEST['tipo']);
  $tipo_dashlet = trim($_REQUEST['tipo_dashlet']);
   $id_plantilla = trim($_REQUEST['id_plantilla_select']);

 if($tipo == 'tarea')
 {
          $tarea = new Task();
          $tarea->retrieve($id_registro);
         

          if($tarea->tipo_c == 'CUMPLEANOS') $tipo_dashlet='Cumpleano';
          if($tarea->tipo_c == 'RETORNO') $tipo_dashlet='Retorno';
          if($tarea->tipo_c == 'ANTERIOR') $tipo_dashlet='Anterior';
          if($tarea->tipo_c == 'MAILING') $tipo_dashlet='Mailing';
 }else $tipo_dashlet="negocio";

 
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




<div class="container" id="wrap">

   <?php 
   $plantilla = new EmailTemplate();
               
                //$plantilla->get_full_list("", " email_templates.assigned_user_id='1' ");
                // print_r($plantilla);

               $bean = BeanFactory::getBean('EmailTemplates');
                  $plantilla = $bean->get_full_list("", " email_templates.type='$tipo_dashlet'   ");
                 // print_r($negocios);
       $plantillas = scandir('custom/app/templates/');

 

                 
   ?>
      
      <div class="row">
        <h3>Elija una opción</h3>
        <div class="col-md-6">
          <form name="frm3" id="frm3" action="http://104.131.0.44/ripley2/index.php?entryPoint=envio_email_plantilla" method="POST">

            <input type="hidden" id="id_registro" name="id_registro" value="<?php echo $id_registro?>">
            <input type="hidden" id="tipo" name="tipo" value="<?php echo $tipo?>">
            <input type="hidden" id="tipo_dashlet" name="tipo_dashlet" value="<?php echo $tipo_dashlet?>">
            <select id="id_plantilla_select" name="id_plantilla_select"  class="form-control">
                <option value="">Seleccione</option>
                 <option  value="cocha.html">cocha.html</option>
              <?php

                  foreach ($plantillas as $key ) {
                    $div = explode("-",$key);

                    if($div[1] != '' and $div[0]==$tipo_dashlet){
                    
              ?>
                      <option  value="<?php echo $key?>"><?php echo $div[1]?></option>
              <?php 
            }
            }?>     
            </select>
        
       </div>
      <div class="col-md-6 ">
      <button class="btn btn-danger pull-right" id="button" onclick="emailTradicional('<?php echo $id_registro?>','<?php echo $tipo?>')">Redactar <img src='custom/themes/default/images/mail.png'></button>
     </div>
   </div>
   <br>
    <button class="btn btn-primary pull-left" id="submit" onclick="submit()">Enviar Plantilla</button>

      <div class="row" >
        <?php

          if($tipo =='negocio'){
          $negocio = new CRM_negocios();
          $negocio->retrieve($id_registro);
          $nombre_registro = $negocio->name;
          $nombre_asunto_email = 'Cross-Selling';
          $contact_obj = $negocio->get_linked_beans('crm_negocios_contacts','Contact'); 

          foreach ( $contact_obj as $contacto ) { 
          if($contacto->tipo_c == 'Comprador' or $contacto->tipo_c == ''){ 
          $nombre_contacto=$contacto->full_name;
          $email_contacto=$contacto->email1;

          }  

          }
          $usuario = new User();
          $usuario->retrieve($negocio->assigned_user_id);

          if($id_plantilla =='')
            $id_plantilla='2490ce98-ff32-5293-bd2d-54ad4871c318';
          }else if($tipo =='tarea'){
          $tarea = new Task();
          $tarea->retrieve($id_registro);
          $nombre_registro = $tarea->name;
          $nombre_asunto_email = $tarea->tipo_c;
          $encuesta_c=$tarea->encuesta_c;
          $id_contacto = $tarea->contact_id;
          $contacto = new Contact();
          $contacto->retrieve($id_contacto);
          $nombre_contacto= $contacto->full_name;
          $email_contacto= $contacto->email1;
          $usuario = new User();
          $usuario->retrieve($tarea->assigned_user_id);

/*
          if($tarea->tipo_c == 'CUMPLEANOS') $id_plantilla='726aaf18-42d7-ab85-0f7d-549aca4d3257';
          if($tarea->tipo_c == 'RETORNO') $id_plantilla='183da926-7ee4-e9c9-15b6-54a9f476951f';
          if($tarea->tipo_c == 'ANTERIOR') $id_plantilla='cf5769f3-1f14-c544-b6d2-54a69cb83050';
          if($tarea->tipo_c == 'MAILING') $id_plantilla='da824a69-b840-c9f5-2e00-54b6e491fab3';
    */

          }
    $encuesta_c='<a href="http://google.com">google.com</a>';
          $emailtemplate = new EmailTemplate();
          $emailtemplate->retrieve($id_plantilla);
          $emailtemplate->body_html=str_replace('$contact_name', $nombre_contacto, $emailtemplate->body_html);
          $emailtemplate->body_html=str_replace('$contact_user_full_name', $usuario->full_name, $emailtemplate->body_html);
          $emailtemplate->body_html=str_replace('$task_encuesta_c', $encuesta_c, $emailtemplate->body_html);
          //html_entity_decode($emailtemplate->body_html)
        ?>
      </div> 

       <textarea id="comentarios" name="comentarios" class="form-control" rows="6">
      </textarea>
      <div id="contenido_plantilla">
        
        
       </div> 

      
      <button class="btn btn-primary pull-left" id="submit" onclick="submit()">Enviar Plantilla</button>
   
    </form>
  
 
  </div>
     


</div>

<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="http://internet.cocha.com/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<script>

  $( document ).ready(function() {
    
        
    $( "#textoemail" ).hide();

        $( "#id_plantilla_select" ).change(function() {
          
/*             id_registro= $( "#id_registro" ).val();
              tipo= $( "#tipo" ).val();
              tipo_dashlet= $( "#tipo_dashlet" ).val();*/
              id_plantilla= $( "#id_plantilla_select" ).val();

              //d = document.getElementById("frm3");
             // d.action= "http://104.131.0.44/ripley/index.php?entryPoint=emailopcion";
                       //d.submit();
$.ajax({
  url: "http://104.131.0.44/ripley2/custom/app/templates/"+id_plantilla,
    success: function( data ) {
         
       $("#contenido_plantilla").html(data);    

    }

  });

        });

  });

 
         function emailPlantilla(id_registro,tipo){
                contenido = $("#textoemail").val();
                
                window.opener.generaEmailPlantilla(id_registro,tipo,contenido);
                
                window.close();
                
         } 

         function emailTradicional(id_registro,tipo){

                window.opener.generaEmail2(id_registro,tipo);
                window.close();
                
         } 




        </script>

  <!-- Imported styles on this page -->
  <link rel="stylesheet" href="assets/js/wysihtml5/bootstrap-wysihtml5.css">
  <link rel="stylesheet" href="assets/js/codemirror/lib/codemirror.css">
  <link rel="stylesheet" href="assets/js/uikit/css/uikit.min.css">
  <link rel="stylesheet" href="assets/js/uikit/addons/css/markdownarea.css">

  <!-- Bottom scripts (common) -->
  <script src="assets/js/gsap/main-gsap.js"></script>
  <script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
  <script src="assets/js/bootstrap.js"></script>
  <script src="assets/js/joinable.js"></script>
  <script src="assets/js/resizeable.js"></script>
  <script src="assets/js/neon-api.js"></script>
  <script src="assets/js/wysihtml5/wysihtml5-0.4.0pre.min.js"></script>


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
