<?php
 global $db, $sugar_config;
 
$nombre_usuario = trim($_REQUEST['usuario']); 
 $query = "SELECT id from users where  deleted=0 and user_name='$nombre_usuario' ";
    $res = $db->Query($query);
   
    if($row = $db->fetchByAssoc($res)){
         $id_usuario =  $row['id'];
    }       


 $u = new User();
 $u->retrieve($id_usuario);
 
 $fecha = date('Y-m-d');

 $total_asignar = 5;//es la cantidad maxima a asignar por dia

 $user = new User();
 $user->retrieve($u->id);
 
    $total_asginadas_dia=0;
    $total_abiertas_dia=0;
     $query2 = "SELECT count(*) as cantidad FROM crm2.opportunities o
        inner join opportunities_cstm oc on oc.id_c = o.id
        where assigned_user_id='".$u->id."' and o.deleted=0 
        and date(oc.fecha_asignacion_c)='".$fecha."' and oc.procesado_externo_c <> 1 ";
        $res2 = $db->Query($query2);
        if($row2 = $db->fetchByAssoc($res2)){
         $total_asginadas_dia= $row2['cantidad'];
        }

    $query2 = "SELECT count(*) as cantidad FROM crm2.opportunities o
    inner join crm2.opportunities_cstm oc on oc.id_c = o.id
        where assigned_user_id='".$u->id."' and o.deleted=0 
        and   oc.procesado_externo_c <>'1'";
        $res2 = $db->Query($query2);
        if($row2 = $db->fetchByAssoc($res2)){
          $total_abiertas_dia= $row2['cantidad'];
        } 

        //$total_abiertas_dia=3;
   if($total_asginadas_dia < 5 and $total_abiertas_dia<5)//pregunta si se le puede asignar
   {
      //echo "====>".$total_abiertas_dia;
      $i=0;
      $oportunidades = new Opportunity(); 
      $list_contact = $oportunidades->get_full_list("opportunities.date_entered", "opportunities.assigned_user_id = '1' and opportunities.date_entered >= '2015-06-10'  ",true);
      $i=0;$k=0;
      foreach ($list_contact as $key) {
         // print_r($key->id);
         //echo "<br>-->". 
         $total_abiertas_dia++;
          $i++;
          
          //echo "<br>".
          $sql = "update  crm2.opportunities set assigned_user_id='".$u->id."' where id='".$key->id."'  ";
          $db->Query($sql);
          //echo "<br>". now()
          $fecha_asignacion = date('Y-m-d H:i:s');
          $sql = "update  crm2.opportunities_cstm set fecha_asignacion_c='$fecha_asignacion' where id_c='".$key->id."'  ";
          $db->Query($sql);

          if($total_abiertas_dia == 5)
              break;
           
      }
      if($i>0)
      {
      //envia email
              $texto = "
              <h2>Estimada(o) ".$user->full_name."</h2>
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
            $mail->Subject=from_html($i.' Oportunidades asignadas');
           //  $mail->Subject=from_html( "hola");
            $mail->Body=$texto;
            $mail->AltBody=from_html($texto);
            $mail->prepForOutbound();
            $mail->AddAddress($user->email1);
            //$mail->AddBCC('jaime.fuentes@tecnich.cl');
            if (@$mail->Send()) echo "";
       }     

      echo "Total oportunidades asignadas: ".$i;
   }//fin if 
   else  echo "Usted dispone de mas de 5 oportunidades abiertas";


?>