<?php  
$admin_option_defs = array();

$admin_option_defs['SugarPrint']['config'] = array('SugarPrint', 'LBL_SUGARPRINT_CONFIG_TITLE', 'LBL_SUGARPRINT_CONFIG_INFO', './index.php?module=Administration&action=SugarPrint_manage');


$admin_group_header[]= array('LBL_SUGARPRINT_TITLE', '', false, $admin_option_defs, 'LBL_SUGARPRINT_ADMIN_DESC');

