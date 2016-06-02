<?php
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Credentials: true");
	header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
	header('Access-Control-Max-Age: 1000');
	header('Access-Control-Allow-Headers: Origin, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers');
global $app_strings, $app_list_strings, $sugar_config, $timedate, $current_user,$db;
$GLOBALS['log']->info('prueba');						
?>