<?php 
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

global $current_language, $app_strings, $current_user;

if(is_admin($current_user)) {

	if(!empty($_REQUEST['record'])) {
		require_once('modules/Users/User.php');
		$log_user = new User();
		$log_user->retrieve($_REQUEST['record']);
		$module_menu[] = Array("index.php?module=Users&action=Masquerade&record=".$_REQUEST['record'], $app_strings['LBL_LOGIN_AS'].$log_user->user_name,"Masquerade");
	}

}

?>