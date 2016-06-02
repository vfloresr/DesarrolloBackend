<?php
$hook_version = 1;
$hook_array = Array();
$hook_array['after_save'] = Array(); 
$hook_array['after_save'][] = Array(2, 'Email Asignaciones', 'custom/Extension/modules/Opportunities/Ext/LogicHooksCode/EmailAsignaciones.php','EmailAsignaciones', 'EnvioEmailExternos');
?>