<?php  
 global $current_user;
 global $db, $sugar_config;

 $id_registro = trim($_GET['id_registro']);
 $tipo = trim($_GET['tipo']);
 

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
        <h3>Elija una opción</h3>
        <div class="col-md-6">
      <button class="btn btn-primary pull-left" id="submit" onclick="emailPlantilla('<?php echo $id_registro?>','<?php echo $tipo?>')">Email Plantilla</button>
      </div>
      <div class="col-md-6">
      <button class="btn btn-danger pull-left" id="button" onclick="emailTradicional('<?php echo $id_registro?>','<?php echo $tipo?>')">Redactar <img src='custom/themes/default/images/mail.png'></button>
     </div>
   </div>
   <br>
      <div class="row" style="border:solid 1px">
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


          if($tarea->tipo_c == 'CUMPLEANOS') $id_plantilla='726aaf18-42d7-ab85-0f7d-549aca4d3257';
          if($tarea->tipo_c == 'RETORNO') $id_plantilla='183da926-7ee4-e9c9-15b6-54a9f476951f';
          if($tarea->tipo_c == 'ANTERIOR') $id_plantilla='cf5769f3-1f14-c544-b6d2-54a69cb83050';
          if($tarea->tipo_c == 'MAILING') $id_plantilla='da824a69-b840-c9f5-2e00-54b6e491fab3';
		

          }
		$encuesta_c='<a href="http://google.com">google.com</a>';
          $emailtemplate = new EmailTemplate();
          $emailtemplate->retrieve($id_plantilla);
          $emailtemplate->body_html=str_replace('$contact_name', $nombre_contacto, $emailtemplate->body_html);
          $emailtemplate->body_html=str_replace('$contact_user_full_name', $usuario->full_name, $emailtemplate->body_html);
          $emailtemplate->body_html=str_replace('$task_encuesta_c', $encuesta_c, $emailtemplate->body_html);
          echo '<textarea rows="4" cols="50">'.html_entity_decode($emailtemplate->body_html).'</textarea>';
        ?>
      </div> 

  <form role="form" method="post">
      <textarea class="form-control ckeditor">
        &lt;h1&gt;&lt;img alt=&quot;Saturn V carrying Apollo 11&quot; class=&quot;right&quot; src=&quot;assets/images/sample.jpg&quot;/&gt; Apollo 11&lt;/h1&gt; &lt;p&gt;&lt;b&gt;Apollo 11&lt;/b&gt; was the spaceflight that landed the first humans, Americans &lt;a href=&quot;http://en.wikipedia.org/wiki/Neil_Armstrong&quot; title=&quot;Neil Armstrong&quot;&gt;Neil Armstrong&lt;/a&gt; and &lt;a href=&quot;http://en.wikipedia.org/wiki/Buzz_Aldrin&quot; title=&quot;Buzz Aldrin&quot;&gt;Buzz Aldrin&lt;/a&gt;, on the Moon on July 20, 1969, at 20:18 UTC. Armstrong became the first to step onto the lunar surface 6 hours later on July 21 at 02:56 UTC.&lt;/p&gt; &lt;p&gt;Armstrong spent about &lt;s&gt;three and a half&lt;/s&gt; two and a half hours outside the spacecraft, Aldrin slightly less; and together they collected 47.5 pounds (21.5&amp;nbsp;kg) of lunar material for return to Earth. A third member of the mission, &lt;a href=&quot;http://en.wikipedia.org/wiki/Michael_Collins_(astronaut)&quot; title=&quot;Michael Collins (astronaut)&quot;&gt;Michael Collins&lt;/a&gt;, piloted the &lt;a href=&quot;http://en.wikipedia.org/wiki/Apollo_Command/Service_Module&quot; title=&quot;Apollo Command/Service Module&quot;&gt;command&lt;/a&gt; spacecraft alone in lunar orbit until Armstrong and Aldrin returned to it for the trip back to Earth.&lt;/p&gt; &lt;h2&gt;Broadcasting and &lt;em&gt;quotes&lt;/em&gt; &lt;a id=&quot;quotes&quot; name=&quot;quotes&quot;&gt;&lt;/a&gt;&lt;/h2&gt; &lt;p&gt;Broadcast on live TV to a world-wide audience, Armstrong stepped onto the lunar surface and described the event as:&lt;/p&gt; &lt;blockquote&gt;&lt;p&gt;One small step for [a] man, one giant leap for mankind.&lt;/p&gt;&lt;/blockquote&gt; &lt;p&gt;Apollo 11 effectively ended the &lt;a href=&quot;http://en.wikipedia.org/wiki/Space_Race&quot; title=&quot;Space Race&quot;&gt;Space Race&lt;/a&gt; and fulfilled a national goal proposed in 1961 by the late U.S. President &lt;a href=&quot;http://en.wikipedia.org/wiki/John_F._Kennedy&quot; title=&quot;John F. Kennedy&quot;&gt;John F. Kennedy&lt;/a&gt; in a speech before the United States Congress:&lt;/p&gt; &lt;blockquote&gt;&lt;p&gt;[...] before this decade is out, of landing a man on the Moon and returning him safely to the Earth.&lt;/p&gt;&lt;/blockquote&gt; &lt;h2&gt;Technical details &lt;a id=&quot;tech-details&quot; name=&quot;tech-details&quot;&gt;&lt;/a&gt;&lt;/h2&gt; &lt;table align=&quot;right&quot; border=&quot;1&quot; bordercolor=&quot;#ccc&quot; cellpadding=&quot;5&quot; cellspacing=&quot;0&quot; style=&quot;border-collapse:collapse;margin:10px 0 10px 15px;&quot;&gt; &lt;caption&gt;&lt;strong&gt;Mission crew&lt;/strong&gt;&lt;/caption&gt; &lt;thead&gt; &lt;tr&gt; &lt;th scope=&quot;col&quot;&gt;Position&lt;/th&gt; &lt;th scope=&quot;col&quot;&gt;Astronaut&lt;/th&gt; &lt;/tr&gt; &lt;/thead&gt; &lt;tbody&gt; &lt;tr&gt; &lt;td&gt;Commander&lt;/td&gt; &lt;td&gt;Neil A. Armstrong&lt;/td&gt; &lt;/tr&gt; &lt;tr&gt; &lt;td&gt;Command Module Pilot&lt;/td&gt; &lt;td&gt;Michael Collins&lt;/td&gt; &lt;/tr&gt; &lt;tr&gt; &lt;td&gt;Lunar Module Pilot&lt;/td&gt; &lt;td&gt;Edwin &amp;quot;Buzz&amp;quot; E. Aldrin, Jr.&lt;/td&gt; &lt;/tr&gt; &lt;/tbody&gt; &lt;/table&gt; &lt;p&gt;Launched by a &lt;strong&gt;Saturn V&lt;/strong&gt; rocket from &lt;a href=&quot;http://en.wikipedia.org/wiki/Kennedy_Space_Center&quot; title=&quot;Kennedy Space Center&quot;&gt;Kennedy Space Center&lt;/a&gt; in Merritt Island, Florida on July 16, Apollo 11 was the fifth manned mission of &lt;a href=&quot;http://en.wikipedia.org/wiki/NASA&quot; title=&quot;NASA&quot;&gt;NASA&lt;/a&gt;&amp;#39;s Apollo program. The Apollo spacecraft had three parts:&lt;/p&gt; &lt;ol&gt; &lt;li&gt;&lt;strong&gt;Command Module&lt;/strong&gt; with a cabin for the three astronauts which was the only part which landed back on Earth&lt;/li&gt; &lt;li&gt;&lt;strong&gt;Service Module&lt;/strong&gt; which supported the Command Module with propulsion, electrical power, oxygen and water&lt;/li&gt; &lt;li&gt;&lt;strong&gt;Lunar Module&lt;/strong&gt; for landing on the Moon.&lt;/li&gt; &lt;/ol&gt; &lt;p&gt;After being sent to the Moon by the Saturn V&amp;#39;s upper stage, the astronauts separated the spacecraft from it and travelled for three days until they entered into lunar orbit. Armstrong and Aldrin then moved into the Lunar Module and landed in the &lt;a href=&quot;http://en.wikipedia.org/wiki/Mare_Tranquillitatis&quot; title=&quot;Mare Tranquillitatis&quot;&gt;Sea of Tranquility&lt;/a&gt;. They stayed a total of about 21 and a half hours on the lunar surface. After lifting off in the upper part of the Lunar Module and rejoining Collins in the Command Module, they returned to Earth and landed in the &lt;a href=&quot;http://en.wikipedia.org/wiki/Pacific_Ocean&quot; title=&quot;Pacific Ocean&quot;&gt;Pacific Ocean&lt;/a&gt; on July 24.&lt;/p&gt; &lt;hr/&gt; &lt;p style=&quot;text-align: right;&quot;&gt;&lt;small&gt;Source: &lt;a href=&quot;http://en.wikipedia.org/wiki/Apollo_11&quot;&gt;Wikipedia.org&lt;/a&gt;&lt;/small&gt;&lt;/p&gt;
      </textarea>
    </form>
    <!-- Footer -->
    <footer class="main">
      
      &copy; 2014 <strong>Neon</strong> Admin Theme by <a href="http://laborator.co" target="_blank">Laborator</a>
    
    </footer>
  </div>
     



</div>
<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="http://internet.cocha.com/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<script>
 
         function emailPlantilla(id_registro,tipo){

                window.opener.generaEmailPlantilla(id_registro,tipo);
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
