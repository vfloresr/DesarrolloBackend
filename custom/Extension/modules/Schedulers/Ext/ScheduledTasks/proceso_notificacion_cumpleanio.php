<?php

/*********************************************************************************
* This code was developed by:
* Audox Ingeniería Ltda.
* You can contact us at:
* Web: www.audox.cl
* Email: info@audox.cl
* Skype: audox.ingenieria
********************************************************************************/

$job_strings[] = 'proceso_notificacion_cumpleanio';


function proceso_notificacion_cumpleanio(){
	global $db,$sugar_config,$current_user;
	
					$query=" SELECT e.id
							 FROM
							   emails e
							 JOIN emails_text ema ON e.id = ema.email_id
							 WHERE
							  e.parent_type = 'contacts' AND
							  e.status = 'AGENDADO' AND 
							  SUBSTRING(e.name,1,11) = 'CUMPLEANIOS' and
							  DATE_FORMAT(e.date_sent, '%Y-%m-%d') =  DATE_FORMAT(NOW(),'%Y-%m-%d')";
	$res = $db->query($query, true, 'Error: ');
	while($row = $db->fetchByAssoc($res)){
			// recupero datos del email programado
			$Email= new Email();
			$Email->retrieve($row['id']);
			// recupero datos del contacto.
			$contacto = new Contact();
			$contacto->retrieve($Email->parent_id);
			// recupero datos del usuario asignado
			$user= new User();
			$user->retrieve($Email->assigned_user_id);
			
			// enviamos el email
			$emailObj = new Email();
			$defaults = $emailObj->getSystemDefaultEmail();
			$mail = new SugarPHPMailer();
			$mail->setMailerForSystem();
			$mail->From = $user->full_name.'<'.$user->email1.'>';
			$mail->FromName = $user->full_name;
			$mail->ClearAllRecipients();
			$mail->ClearReplyTos();
			$mail->Subject=substr($Email->name,12);
			$mail->Body=$Email->description_html;
			$mail->AltBody=$Email->description_html;
			$mail->prepForOutbound();
			$emailTo=array($contacto->email1);
			foreach ($emailTo as $value) {
				$mail->AddAddress($value);
			}
			$mail->AddReplyTo($user->email1,$user->full_name);
			$mail->AddBCC($user->email1);
			//$con= $con + 1;
			if($mail->Send()){
				//$GLOBALS['log']->fatal("actualizo email ".$con);
				$Email_save = new Email();
				$Email_save->retrieve($row['id']);
				$Email_save->status = 'sent';
				$Email_save->from_addr = $user->email1;
				$Email_save->to_addrs = $contacto->email1;
				$Email_save->cc_addrs = $user->email1;
				$Email_save->save();
			}
			//$GLOBALS['log']->fatal("fin proceso_1d...");
	}
		
	return true;
}

?>