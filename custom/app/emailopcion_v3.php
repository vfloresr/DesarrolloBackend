<?php  
 //ini_set('error_reporting', E_ALL);
 //ini_set('display_errors', True);
 global $current_user;
 global $db, $sugar_config;



 $id_registro = trim($_REQUEST['id_registro']);
  $tipo = trim($_REQUEST['tipo']);
  $tipo_dashlet = trim($_REQUEST['tipo_dashlet']);
  $id_producto = trim($_REQUEST['id_producto']);
   $id_plantilla = 'cotizacion-formato cocha 2.html';//trim($_REQUEST['id_plantilla_select']);

   $tipo_dashlet='cotizacion';

   $oportunidad = new Opportunity();
   $oportunidad->retrieve($id_registro);
   


   $producto = new crm_solicitudes();
   
  $producto->retrieve($id_producto);
  $link_pdf = $producto->link_pdf_c;
  
  if(trim($producto->numero_producto) != '')
  {
    
    $id_producto_joomla = trim($producto->numero_producto);
  }    
  else {
    $id_producto_joomla = 0;


  //if($current_user->id ==1 ) $id_producto_joomla='1405'; 

  echo '<div class="container" id="wrap">'."<h2 style='color:red'>Por favor seleccione: ".$producto->name."</h2></div>";

  }

  $query2 = "SELECT ema.email_address,con.first_name,con.last_name,bean_module 
			FROM leads con
			inner join email_addr_bean_rel em_rel on em_rel.bean_id = con.id
			inner join email_addresses ema on ema.id= em_rel.email_address_id
			inner join leads_opportunities_1_c op_c on op_c.leads_opportunities_1leads_ida=con.id
			where em_rel.bean_module='Leads' 
			and con.deleted=0 
			and op_c.deleted=0
			and op_c.leads_opportunities_1opportunities_idb = '".$id_registro."'
			and ema.deleted=0 
			and em_rel.deleted=0";
			
        $res2 = $db->Query($query2);
        if($row2 = $db->fetchByAssoc($res2)){
          $correo= $row2['email_address'];
          $nombre_contacto= $row2['first_name']." ".$row2['last_name'];
        }
    


  $file = file_get_contents('http://cms.cocha.com/mobile-api/destinos');
  $array = json_decode($file);

  ?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Formulario de cotización</title>
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
<body >


<div class="container" id="wrap">

   <?php 
   $plantilla = new EmailTemplate();
   $bean = BeanFactory::getBean('EmailTemplates');
   $plantilla = $bean->get_full_list("", " email_templates.type='$tipo_dashlet'   ");
   $plantillas = scandir('custom/app/templates/');              
   ?>
       <h3>Formulario Envío Cotización</h3>
     <form name="frm3" id="frm3" action="http://backend.cochadigital.com/index.php?entryPoint=envio_email_plantilla" method="POST" enctype="multipart/form-data">
      <div class="row">
       
       
        <div class="col-md-6">
<!--              <select name="destino" id="destino"  class="form-control col-md-6" >
          <option>Seleccione destino</option>


        </select>  -->
         <select name="destino" id="destino"  class="form-control col-md-6" >
             <option>Seleccione destino</option>
             <?php 
              foreach ($array as $key => $value) {
                $destino = $value->title;
                $id = $value->id;
                echo "<option value='$id'>$destino</option>";
              }
             ?>
         </select> 


       

            <input type="hidden" id="id_registro" name="id_registro" value="<?php echo $id_registro?>">
            <input type="hidden" id="tipo" name="tipo" value="<?php echo $tipo?>">
            <input type="hidden" id="tipo_dashlet" name="tipo_dashlet" value="<?php echo $tipo_dashlet?>">
            <input type="hidden" id="pdfjoomla" name="pdfjoomla" value="">
            <input type="hidden" id="id_producto_joomla" name="id_producto_joomla" value="<?php echo $id_producto_joomla?>">
            
        
       </div>
       <div class="col-md-6"> 
          <select name="programa" id="programa"  class="form-control col-md-5" style="display:none">
          </select>
       </div> 
      
   </div>
   
   <br>
   
<div class="row">
     <div class="col-md-6"> 

          <input type="text" name="asunto" id="asunto"  class="form-control" style="display:" placeholder="Asunto">
          
       </div> 
      
   </div>
<br>
 <?php
          $id_plantilla='94b1733c-617d-078a-b480-55082a1201c9';
          $emailtemplate = new EmailTemplate();
          $emailtemplate->retrieve($id_plantilla);
?>

 <div class="row " >
    <div class="col-md-12">
      <div class="col-md-3 panel panel-primary">

         <!-- <div class="form-group has-success col-md-12">
                <label for="field-1" class=" control-label"><h4>Seleccione una plantilla</h4></label>
            <select id="id_plantilla_select" name="id_plantilla_select"  class="form-control">
               
                
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
              <hr>
         </div> -->

         <div class="form-group col-md-12">
                <label for="field-1" class="control-label">Texto Estimado(a)</label>
                  <select class="form-control" id="estimado">
                    <option value=""></option>
                    <option value="Estimado">Estimado</option>
                    <option value="Estimada">Estimada</option>
                  </select>
              
         </div>

         <div class="form-group col-md-12">
                <label for="field-1" class="control-label">Nombre del Cliente</label>
                  <input type="text" class="form-control" id="contact_name" value="<?php echo $nombre_contacto; ?>" >
               
                  
                
         </div>

        <div class="form-group col-md-12">
                <label for="field-1" class="control-label">Tarifas</label>
                
               
                  <select class="form-control" id="tarifas">
                    <option value="">Seleccione una opción</option>
                    <option value="Doble USD">Doble USD</option>
                    <option value="Triple USD">Triple USD</option>
                    <option value="Single USD">Single USD</option>

                    <option value="Plan Familiar 2 ADL + 2 NIÑOS compartiendo habitación">Plan Familiar</option>
                   
                  </select>
              
         </div>
    
              <div class="form-group col-md-12">
                <label for="field-1" class="control-label">Medios de Pago</label>
                
               
                  <select class="form-control" id="medio_pago" style="display:none">
                    <option value="">Seleccione una opción</option>
                    <option value="Tarjeta Crédito hasta 3 cuotas sin interés. Promoción vigente de cada

banco">Tarjeta Crédito hasta 3 cuotas sin interés. Promoción vigente de cada

banco</option>
                    <option value="Transferencia electrónica o depósito bancario en efectivo">Transferencia electrónica o depósito bancario en efectivo</option>
                    <option value="Depósito en cuenta corriente Scotiabank">Depósito en cuenta corriente Scotiabank</option>
                    <option value="Tarjeta Crédito Ripley">Tarjeta Crédito Ripley</option>
                   
                  </select>
              
         </div>

         <div class="form-group col-md-12">
                
                
               
                  <label for="field-1" class="control-label">Documentación</label>
                  <select class="form-control" id="condiciones_generales" >
                    <option value="">Seleccione una opción</option>
                    <option value="Para este viaje se requiere contar con pasaporte vigente (mínimo 6 meses a la fecha de regreso)

y en buen estado.">Contar con pasaporte</option>
                    <option value="Para este viaje se requiere pasaporte vigente (mínimo 6 meses a la fecha de regreso)  y visa.">Pasaporte vigente visa</option>
                    <option value="Por favor revise sus documentos de viaje, carnet de identidad, Pasaporte y Visas de acuerdo al

destino, teniendo presente que deben estar en buen estado y vigentes al menos 6 meses a la 

fecha de regreso.">Revise documentos viaje</option>
                    
                   
                  </select>
             
         </div>
         <div class="form-group col-md-12">
                <label for="field-1" class="control-label">Datos Practicos</label>
                
               
                  <textarea  class="form-control" id="datos_practicos" placeholder="Los espacios son limitados…"></textarea>
                
         </div>

         <div class="form-group col-md-12" style="display:none">
                <label for="field-1" class="control-label">Precio</label>
                <input  type="text" class="form-control"  id="precio" name="precio" value="">  
                <label for="field-1" class="control-label" id="precio_subtitulo"></label>             
         </div>
         <div class="form-group col-md-12" style="display:none">
                <label for="field-1" class="control-label">Nombre Ejecutiva</label>
                
                
                  <input type="text"  class="form-control" id="nombre_ejecutiva" placeholder="Nombre Ejecutiva">
               
         </div>
         <div class="form-group col-md-12" style="display:">
                <label for="field-1" class="control-label">Telefono Ejecutiva</label>
                
               
                  <input type="text"  class="form-control" id="telefono_ejecutiva" placeholder="224641300" value="224641300">
             
         </div>
         <div class="form-group col-md-12" style="display:none">
                <label for="field-1" class="control-label">Email Ejecutiva</label>
                 <input type="text"  class="form-control" id="email_ejecutiva" placeholder="ejecutiva@cocha.com">

         </div>
        <div id="progrma_seleccionado" style="display:">
         <div class="form-group col-md-12">
                <label for="field-1" class="control-label">Programa</label><br>
         <!--         <?php if($link_pdf!='') { ?>
                <label><a  href="<?php echo $link_pdf?>" target="_blank">Ver Programa</a></label>
                 <input type="button" class="btn btn-danger"  onclick="addpdf('<?php echo $id_producto?>','<?php echo $link_pdf?>')" value="Adjuntar">
                 <?php }else
                 {?>
                    <label class="text-danger">Sin Programa</label>
                 <?php }  ?>  -->

                 <label class="text-info"><a id="pdf_joomla"></a></label>
                  <input type="button" class="btn btn-danger"  onclick="addpdf('<?php echo $id_producto?>')" value="Adjuntar">
         </div>
         <div class="form-group col-md-6" id="div_imagen">
                <label for="field-1" class="control-label">Imagen</label><br>
                <input type="checkbox" id="imagen" >

         </div>
         <div class="form-group col-md-6" id="div_incluye">
                <label for="field-1" class="control-label">Incluye</label><br>
                <input type="checkbox" id="incluye" >

         </div>
       </div>
         
      </div>

      <div class="col-md-9 ">
        
      <textarea class="form-control ckeditor" name="texto" id="texto">
          <table border="0" cellpadding="1" cellspacing="1" style="width: 610px;">
  <tbody>
    <tr>
      <td><a href="http://www.cocha.com?email=*|EMAIL|*" title="" class="" target="_blank">
                                        <img align="left" alt="" src="https://gallery.mailchimp.com/cf8e3f784ee65fee4bea0e12f/images/0fee65c6-af13-4c82-9d48-49cded8986c2.jpg" style="max- padding-bottom: 0; display: inline !important; vertical-align: bottom; width:610px" class="mcnImage">
                                    </a></td>
      
    </tr>
    <tr>
      <td><span id="estimado">Estimado</span> <span id="nombre_contacto_estimado"> </span>,
        <p >Junto con saludarle, tengo el agrado de informar valor para el programa solicitado.</p></td>
    </tr>
    <tr>
      <td>
        
        <div id="title"></div>
        <div id="incluye"></div>
        </td>
    </tr>
    <tr>
      <td>
        <h3><ins><strong>Tarifa</strong></ins></h3>
        <div>Valor por persona en base a:</div>
        <div id="tarifa" ></div>
        <div id="medio_pago" >
          <h3><ins><strong>Medios de Pago</strong></ins></h3>
			<li>Tarjeta Crédito hasta 3 cuotas sin interés. Promoción vigente de cada banco</li>
			<li>Transferencia electrónica o depósito bancario en efectivo</li>
			<li>Depósito en cuenta corriente Scotiabank</li>
			<li>Tarjeta Crédito Ripley</li>
        </div>
<div id="cgeneral" ></div>
<div id="imagen"></div>
<div id="comentarios" ></div>
<h3><ins><strong>Otros</strong></ins></h3>
      </td>
    </tr>  

    <tr>
      <td>
        Para otras alternativas s&iacute;rvase consultar documento adjunto.<br>


Los espacios a&eacute;reos son limitados por lo que para confirmar una reserva requiero me informe:<br>
<li>Nombre Completo</li>
<li>Edades s&oacute;lo en caso de los menores de 12 a&ntilde;os</li>
<li>Fecha tentativa</li>
<li>N&uacute;mero de contacto</li>
<p>Sin otro particular le saluda cordialmente,</p>
</td>


      
    </tr>
    <tr>

<td>
 <table border="0" cellpadding="1" cellspacing="1" style="width: 30%;">
      <tr>
      <td style="width: 100px;">
        <img src="http://internet.cocha.com/produccion/modulos.nsf/vw_Objetos_Codigo/IBE_HEADER/$file/cocha_ibe_logo2.gif">
      </td>
       <td style=" border-left: 1px solid grey; padding-left:10px">
        <div id="nejecutiva">$user_fullname</div>
        <div id="cejecutiva">$user_cargo</div>
        <div id="sejecutiva">$user_sucursal</div>
        <div id="tejecutiva">224641300</div>
        <div id="eejecutiva">$user_email</div>
      </td>
    </tr>
 </table>

</td>

    </tr>
  </tbody>
</table>












      </textarea>
      <textarea style="display:none" class="form-control" name="texto_hide" id="texto_hide">
      </textarea>

           <div class="col-md-9" >
    <div class="form-group " id="div_pdf_adjunto" style="display:none">

        <input type="hidden" name="id_product" id="id_product" >
        <span id="link_pdf"></span>
     </div> 
    <div class="form-group ">
          
                
                  <div class="fileinput fileinput-new" data-provides="fileinput"><input type="hidden">
                    <span class="btn btn-info btn-file">
                      <span class="fileinput-new">Seleccione Archivo <i class="entypo-doc-text"></i></span>
                      <span class="fileinput-exists">Cambiar Archivo</span>
                      <input type="file" name="name_file">
                    </span>
                    <span class="fileinput-filename"></span>
                    <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">×</a>
                  </div>
              
                  
       </div> 
        
      </div> 
       <div class="col-md-3 ">
          <button type="button" class="btn btn-primary btn-icon btn-lg pull-right"  onclick="enviar()">
                Enviar
                <i class="entypo-mail"></i>
              </button>
       </div>
    </div>
    
</div>
</div>
      
      
   
    </form>
  
 
  </div>
     


</div>

<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="http://internet.cocha.com/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<script>
$( document ).mousemove(function() {
    carga_contenido_programa($( "#id_producto_joomla" ).val());
});


  $( document ).ready(function() {
      CKEDITOR.config.allowedContent = true; 
      addpdf('','')
      id_plantilla= $( "#id_plantilla_select" ).val();
       //carga_plantilla(id_plantilla,1) 
       
  

    $( "#textoemail" ).hide();

        $( "#destino" ).change(function() {
              destino= $( "#destino" ).val();
              carga_programas(destino)
        });

        $( "#id_plantilla_select" ).change(function() {
              id_plantilla= $( "#id_plantilla_select" ).val();
                       carga_plantilla(id_plantilla,0)
        });


        $( "#programa" ).change(function() {
          if($( "#programa" ).val() != '')
          {
            $( "#id_producto_joomla" ).val($( "#programa" ).val())
            $("#progrma_seleccionado").show();

            carga_contenido_programa($( "#programa" ).val());
          }else{
            alert('debe seleccionar un programa');
            $("#progrma_seleccionado").hide();
          }
            

          
        });

        $( "#destino" ).mouseover(function() {
          
            

           

          
        });

        $( "#imagen" ).click(function() {
            id=$( "#programa" ).val();
            
            if(id==null) id=$( "#id_producto_joomla" ).val();
            
                
            if (this.checked) {
              carga_imagen(id);
           }
 
        });

        $( "#incluye" ).click(function() {
            id=$( "#programa" ).val();
            
            if(id==null) id=$( "#id_producto_joomla" ).val();
            
                
            if (this.checked) {
              carga_incluye(id);
           }
 
        });


         $( "#estimado" ).change(function() {
          var editor = CKEDITOR.instances.texto;
          if(!editor.document.getById('estimado'))
              editor.insertText($( "#estimado" ).val());
          else
              editor.document.getById('estimado').setHtml($( "#estimado" ).val()); 
          
          $( "#texto_hide" ).val($( "#texto" ).val());        
        }); 

         $( "#contact_name" ).keyup(function() {
          var editor = CKEDITOR.instances.texto;
          if(!editor.document.getById('nombre_contacto_estimado'))
              editor.insertText($( "#contact_name" ).val());
          else
              editor.document.getById('nombre_contacto_estimado').setHtml($( "#contact_name" ).val()); 
          
          $( "#texto_hide" ).val($( "#texto" ).val());        
        }); 

        $( "#tarifas" ).change(function() {
          //alert(CKEDITOR.instances['texto'].getData())
          var editor = CKEDITOR.instances.texto;
          if(!editor.document.getById('tarifa'))
              editor.insertText($( "#tarifas" ).val());
          else
              editor.document.getById('tarifa').setHtml($( "#tarifas" ).val()); 
          
              
          $( "#texto_hide" ).val($( "#texto" ).val());
        });
      $( "#medio_pago" ).change(function() {
          //alert(CKEDITOR.instances['texto'].getData())
          var editor = CKEDITOR.instances.texto;
          if(!editor.document.getById('medio_pago'))
              editor.insertText("<h3><ins><strong>Medios de Pago</strong></ins></h3>"+$( "#medio_pago" ).val());
          else
              editor.document.getById('medio_pago').setHtml("<h3><ins><strong>Medios de Pago</strong></ins></h3>"+$( "#medio_pago" ).val()); 
          
              
          $( "#texto_hide" ).val($( "#texto" ).val());
        });

        $( "#condiciones_generales" ).change(function() {
          var editor = CKEDITOR.instances.texto;
          if(!editor.document.getById('cgeneral'))
              editor.insertText("<h3><ins><strong>Documentación</strong></ins></h3>"+$( "#condiciones_generales" ).val());
          else  
           editor.document.getById('cgeneral').setHtml("<h3><ins><strong>Documentación</strong></ins></h3>"+$( "#condiciones_generales" ).val()); ;
          $( "#texto_hide" ).val($( "#texto" ).val());
        });   
        $( "#datos_practicos" ).focusout(function() {
          var editor = CKEDITOR.instances.texto;
          if(!editor.document.getById('comentarios'))
              editor.insertText("<h3><ins><strong>Comentarios</strong></ins></h3>"+$( "#datos_practicos" ).val());
          else  
            editor.document.getById('comentarios').setHtml("<h3><ins><strong>Comentarios</strong></ins></h3>"+$( "#datos_practicos" ).val()); ;
          
          if($( "#datos_practicos" ).val() == '') editor.document.getById('comentarios').setHtml("");

          $( "#texto_hide" ).val($( "#texto" ).val());  


        });    
        $( "#telefono_ejecutiva" ).focusout(function() {
          var editor = CKEDITOR.instances.texto;
          editor.document.getById('tejecutiva').setHtml($( "#telefono_ejecutiva" ).val()); ; 
          $( "#texto_hide" ).val($( "#texto" ).val());       
        });    
        $( "#nombre_ejecutiva" ).focusout(function() {
          var editor = CKEDITOR.instances.texto;
          editor.document.getById('nejecutiva').setHtml('<strong>'+$( "#nombre_ejecutiva" ).val()+'</strong>'); ; 
          $( "#texto_hide" ).val($( "#texto" ).val());
        });    
        $( "#email_ejecutiva" ).focusout(function() {
          var editor = CKEDITOR.instances.texto;
          editor.document.getById('eejecutiva').setHtml('<strong>'+$( "#email_ejecutiva" ).val()+'</strong>'); ;
          $( "#texto_hide" ).val($( "#texto" ).val());
                  });    

                                                   

  });

        function carga_imagen(id){
             $.ajax({
            url: "http://cms.cocha.com/mobile-api/programas?id="+id,
            dataType: "json",
            success: function( data ) {
            var editor = CKEDITOR.instances.texto;
              $.each(data, function (index, data) {
                 imagen = "http://cms.cocha.com/"+data['imgUrl'];
                 editor.document.getById('imagen').setHtml("<img src='"+imagen+"' style='width:610px'>"); 
                


              })
              $( "#texto_hide" ).val($( "#texto" ).val());
            }

            });
        } 

        function carga_incluye(id){
             $.ajax({
            url: "http://cms.cocha.com/mobile-api/programas?id="+id,
            dataType: "json",
            success: function( data ) {
            var editor = CKEDITOR.instances.texto;
              $.each(data, function (index, data) {
                 incluye2 = data['description'];
                // incluye = incluye.replace("[","");
                // incluye = incluye.replace("]","");
                // incluye = incluye.replace("\"","");
                // incluye = incluye.replace('"',"");incluye = incluye.replace("&quot;","");
                // incluye = incluye.replace('&',"");incluye = incluye.replace('_'," ");

                // div = incluye.split(',');
                 incluye='<ul>';
                 //for(i = 0; i < div.length; i++)
                 //{
                    //div[i] = div[i].replace('"',"");
                    // div[i] = div[i].replace(/["']/g, "");
                    //incluye = incluye + "<li>"+capitalize(div[i])+"</li>";
                 //}
                 incluye = '<ul>'+incluye2 + "</ul>";

                 editor.document.getById('incluye').setHtml('Incluye: '+incluye); 
                


              })
              $( "#texto_hide" ).val($( "#texto" ).val());
            }

            });
        }  

         function carga_plantilla(id_plantilla,inicio){
                 /*$.ajax({
            url: "http://104.131.0.44/crm/custom/app/templates/"+id_plantilla,
            success: function( data ) {
             // alert(data)
            $("#texto").val(data);    
            $( "#texto_hide" ).val($( "#texto" ).val());

            }

            });*/
$( "#texto_hide" ).val($( "#texto" ).val());
                // if(inicio ==1){
                 // carga_contenido_programa('<?php echo $id_producto_joomla?>');
                // }
                    
                 
                
         } 

         function carga_programas(destino){

                 $.ajax({
            url: "http://cms.cocha.com/mobile-api/programas?destino="+destino,
            dataType: "json",
            success: function( data ) {
              //alert(data)
              $("#programa").html('');
              $("#programa").show();
               $("#programa").append('<option>Seleccione un programa </option>');

              $.each(data, function (index, data) {
                 subtitle = data['subtitle'];
                 title = data['title'];
                 days = data['days'];
                 id = data['id'];
                 
                 $("#programa").append('<option value="'+id+'">'+days+' Días, '+title+', '+subtitle+' </option>');


              })
            }

            });
                
         }   

         function carga_contenido_programa(id){

            $.ajax({
            url: "http://cms.cocha.com/mobile-api/programas?id="+id,
            dataType: "json",
            success: function( data ) {

              var editor = CKEDITOR.instances.texto;
          /*if(!editor.document.getById('tarifa'))
              editor.insertText($( "#tarifas" ).val());
          else
              editor.document.getById('tarifa').setHtml($( "#tarifas" ).val()); */
          
              
          $( "#texto_hide" ).val($( "#texto" ).val());

              $.each(data, function (index, data) {
                 imagen = "http://cms.cocha.com/"+data['imgUrl'];
                subtitle = data['subtitle'];
                 title = data['title'];
                 days = data['days'];
                 nights=days-1;
                 id = data['id'];
                 pdf_joomla = data['verMas'];

                  if(data['imgUrl'] =='')
                    $( "#div_imagen" ).hide();
                 else  
                    $( "#div_imagen" ).show();

         
                
                  obj = data.pricing;
                 
                  obj.forEach(function(entry) {
                  
                 
                   precio = entry.mainPrice;
                   precio_subtitulo = entry.subtitle;

                   });
                 
                 
                 $( "#pdf_joomla" ).html('Programa Joomla');
                 $( "#pdf_joomla" ).attr("href", pdf_joomla);
                 $( "#pdf_joomla" ).attr("target", '_blank');
                 $( "#pdfjoomla" ).val(pdf_joomla);


                 $( "#precio" ).val(precio);
                 $( "#precio_subtitulo" ).html(precio_subtitulo);
                 //editor.document.getById('imagen').setHtml("<img src='"+imagen+"'>"); 
                 editor.document.getById('title').setHtml('<h3><ins><strong>Programa</strong></ins></h3>'+title+''+'<br/>'+subtitle+''+'<br/>'+days+' Días /'+nights+' Noches' +'');
                // editor.document.getById('subtitle').setHtml('<strong>'+subtitle+'</strong>'); 
                 //editor.document.getById('days').setHtml('<strong>'+days+' Días /'+nights+' Noches' +'</strong>' );
                 
                 $( "#asunto" ).val("Cotización "+title+" "+days+' Días /'+nights+' Noches');
                 $( "#texto_hide" ).val($( "#texto" ).val());
                 $("#id_product").val(pdf_joomla);  

              })
            }

            });
                
         }          



 

 
         function emailPlantilla(id_registro,tipo){
                contenido = $("#textoemail").val();
                
                window.opener.generaEmailPlantilla(id_registro,tipo,contenido);
                
                window.close();
                
         } 

         function emailTradicional(id_registro,tipo){

                window.opener.generaEmail2(id_registro,tipo);
                window.close();
                
         } 

         function delete_program(){
                $("#link_pdf").html('');  
                $("#id_product").val('');  
                
                $("#div_pdf_adjunto").hide(); 
                
         } 
          function enviar(){
            error=0;
              if($("#tarifas").val() =='' )
              {
                alert('No ha seleccionado una tarifa');
                error=1;
                return false;
              }
              if($("#estimado").val() =='' )
              {
                alert('No ha seleccionado texto estimado(a)');
                error=1;
                return false;
              }

              if(error==0)
              {
                d = document.frm3;
                d.submit();
              }
                
                
         } 
    function addpdf(id,link){
            if(link!=''){
                $("#link_pdf").html('<h2 ><span class="label label-success"><i class="entypo-cancel" onclick="delete_program()"></i>Programa Adjunto</span></h2>');  
                $("#id_product").val(link);  
                
                $("#div_pdf_adjunto").show(); 
            }    
    }          


function capitalize(s)
{
    return s[0].toUpperCase() + s.slice(1);
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
