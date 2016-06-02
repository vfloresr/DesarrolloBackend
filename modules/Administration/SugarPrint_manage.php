<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
 
  $filename='custom/modules/SugarPrint/css/reportstyle.css';
  global $current_user, $beanList,  $current_language, $mod_strings, $beanList;
  if (!is_admin($current_user)) sugar_die("Unauthorized access to administration.");
  
  require_once('include/Sugar_Smarty.php');
  $sugar_smarty   = new Sugar_Smarty();
  require_once 'modules/Configurator/Configurator.php';
  $configurator = new Configurator();
  $configurator->loadConfig(); 
  
  echo get_module_title('', $mod_strings['LBL_SUGARPRINT_TITLE'], false);
  
  $configurationsaved="";

  if(isset($_REQUEST['process']) && $_REQUEST['process'] == 'true') {
   $configurator->config['SugarPrint_fontname'] = $_REQUEST['input_fontname'];      
   $configurator->config['SugarPrint_fontsize'] = $_REQUEST['input_fontsize'];
   $configurator->saveConfig();
   $fontname= $_REQUEST['input_fontname'];
   $fontsize= $_REQUEST['input_fontsize'];
   $css= $_REQUEST['css'];
   $sugar_smarty->assign('show_logo', $logo);

 
   if(file_exists($filename)) {
     file_put_contents($filename, $css);  
    }
 

   $configurationsaved="Your changes have been saved."; 
  }
  else
  {
  
  if(empty($configurator->config['SugarPrint_fontname']))
    $fontname='helvetica';
  else
    $fontname=$configurator->config['SugarPrint_fontname'];
 
   if(empty($configurator->config['SugarPrint_fontsize']))
    $fontsize=12;
  else
    $fontsize=$configurator->config['SugarPrint_fontsize'];

   $css= file_get_contents($filename);
  }
   
  $tpl = 'modules/Administration/SugarPrint_manage.tpl';
  $sugar_smarty->assign('MOD', $mod_strings);
  $sugar_smarty->assign('APP', $app_strings);
  $sugar_smarty->assign('fontsize', $fontsize);  
  $sugar_smarty->assign('fontname', $fontname);
  $sugar_smarty->assign('css', $css);  
  $sugar_smarty->assign('configurationsaved', $configurationsaved);
  $sugar_smarty->display($tpl);

?>                            