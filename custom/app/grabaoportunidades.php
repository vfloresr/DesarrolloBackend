<?php  
 ini_set('error_reporting', E_ALL);
 ini_set('display_errors', True);
 global $current_user;
 global $db, $sugar_config;

echo "entro a graba";
if(isset($_POST['asignado_a'])) 
{
  echo "----------->asignado a: ". $asignado_a = $_POST['asignado_a'] ;
    echo "----------->". $cantidad = $_POST['cantidad'] ;
    for($i=1;$i<=$cantidad;$i++){
      echo "<br>i=".$i;
      if(isset($_POST['id-'.$i])){
          echo "<br>oportunidad ".$id_oportunidad=$_POST['id_oportunidad-'.$i];

          $oportunidad = new Opportunity();
          $oportunidad->retrieve($id_oportunidad);
          $oportunidad->assigned_user_id = $asignado_a;
         //n->sales_stage=;
          $user_time_format = $current_user->getPreference('timef');
          $current_user->setPreference('timef', 'H:i:s');
          $fecha = date($current_user->getPreference('datef').' '.$user_time_format, strtotime(date('Y-m-d H:i:s')));
          $oportunidad->fecha_asignacion_c=$fecha;

          /*if($current_user->id==1)
          {

              echo "<br>".$sql = "update  crm2.opportunities set assigned_user_id='".$asignado_a."' where id='".$id_oportunidad."'  ";
          $db->Query($sql);
          echo "<br>". $sql = "update  crm2.opportunities_cstm set fecha_asignacion_c=now() where id_c='".$id_oportunidad."'  ";
          $db->Query($sql);
   
          
         }else*/
          $oportunidad->save();
          $usuario= new User();
          $usuario->retrieve($asignado_a);
          if($usuario->sucursal_c =='CONTACT')
          {
            
            
            $texto = "
              <h2>Administrator, le ha asignado una Oportunidad.</h2>

                Nombre Oportunidad: ".$oportunidad->name." <br>
                Fecha de Viaje:".$oportunidad->fecha_viaje_c." <br>
                Estapa de la venta:".$oportunidad->sales_stage." <br>
                Cantidad Adultos:".$oportunidad->pasajeros_adultos_c." <br>
                Cantidad de niños:".$oportunidad->pasajeros_ninos_c." <br>
                Descripción:".$oportunidad->description." <br>
                <br>
                <br>
                Puede examinar esta Oportunidad en:
          <http://104.131.0.44/crm/index.php?module=Opportunities&action=DetailView&record=".$oportunidad->id.">
            ";
            $emailObj = new Email();
            $defaults = $emailObj->getSystemDefaultEmail();
            $mail = new SugarPHPMailer();
            $mail->setMailerForSystem();
            $mail->From = 'noticias@cocha.com';//$usuario->email1
            $mail->FromName = 'Turismo Cocha';//$usuario->full_name
            $mail->ClearAllRecipients();
            $mail->ClearReplyTos();
            $mail->Subject=from_html('Oportunidad de SugarCRM - '.$oportunidad->name);
           //  $mail->Subject=from_html( "hola");
            $mail->Body=$texto;
            $mail->AltBody=from_html($texto);
            $mail->prepForOutbound();
            $mail->AddAddress($usuario->email1);
            //$mail->AddBCC('jaime.fuentes@tecnich.cl');
            if (@$mail->Send()) echo "asignacion ok";
          }
          else
           if($usuario->sucursal_c =='PROTOTIPO')
          {
            
            
            $texto = "
              <h2>Estimada(o) ".$usuario->full_name."</h2>
                <br>El administrator, le ha asignado $i oportunidades.
            ";
            $emailObj = new Email();
            $defaults = $emailObj->getSystemDefaultEmail();
            $mail = new SugarPHPMailer();
            $mail->setMailerForSystem();
            $mail->From = 'noticias@cocha.com';//$usuario->email1
            $mail->FromName = 'Turismo Cocha';//$usuario->full_name
            $mail->ClearAllRecipients();
            $mail->ClearReplyTos();
            $mail->Subject=from_html('Oportunidad de SugarCRM - '.$oportunidad->name);
           //  $mail->Subject=from_html( "hola");
            $mail->Body=$texto;
            $mail->AltBody=from_html($texto);
            $mail->prepForOutbound();
            $mail->AddAddress($usuario->email1);
            //$mail->AddBCC('jaime.fuentes@tecnich.cl');
            if (@$mail->Send()) echo "asignacion ok";
          }



      }    
    }
}





?>