<?php
//custom/modules/AOS_Products/Ext
// JDJ 2013-02-09
$hook_version = 1;
$hook_array = Array();
//$hook_array['before_save'][] = Array(1,"update_self", "custom/Extension/modules/Cases/Ext/LogicHooksCode/Cases_Hook.php","Cases_Hook","update_self");


$hook_array['process_record'] = Array(); 
$hook_array['process_record'][] = Array(1, 'despues de grabar', 'custom/Extension/modules/crm_solicitudes/Ext/LogicHooksCode/Hook.php','List_Hook', 'crea_tarea'); 

//$hook_array['after_retrieve'] = Array(); 
//$hook_array['after_retrieve'][] = Array(1, 'despues de grabar', 'custom/Extension/modules/crm_solicitudes/Ext/LogicHooksCode/Hook.php','List_Hook', 'boton_cotizar'); 


?>