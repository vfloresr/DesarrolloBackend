<?php
$entry_point_registry['formLetter'] = array('file' => 'modules/AOS_PDF_Templates/formLetterPdf.php' , 'auth' => '1'); 
$entry_point_registry['generatePdf'] = array('file' => 'modules/AOS_PDF_Templates/generatePdf.php' , 'auth' => '1'); 
$entry_point_registry['Reschedule'] = array('file' => 'modules/Calls_Reschedule/Reschedule_popup.php' , 'auth' => '1'); 
$entry_point_registry['Reschedule2'] = array('file' => 'custom/modules/Calls/Reschedule.php' , 'auth' => '1');
$entry_point_registry['social'] = array('file' => 'custom/include/social/get_data.php' , 'auth' => '1');
$entry_point_registry['social_reader'] = array('file' => 'custom/include/social/get_feed_data.php' , 'auth' => '1');
$entry_point_registry['add_dash_page'] = array('file' => 'custom/modules/Home/AddDashboardPages.php' , 'auth' => '1');
$entry_point_registry['retrieve_dash_page'] = array('file' => 'custom/include/MySugar/retrieve_dash_page.php' , 'auth' => '1');
$entry_point_registry['remove_dash_page'] = array('file' => 'custom/modules/Home/RemoveDashboardPages.php' , 'auth' => '1');
$entry_point_registry['rename_dash_page'] = array('file' => 'custom/modules/Home/RenameDashboardPages.php' , 'auth' => '1');
$entry_point_registry['nuevasoportunidades'] = array('file' => 'custom/application/asignador/nuevasoportunidades.php' , 'auth' => true);
$entry_point_registry['grabaoportunidades'] = array('file' => 'custom/application/asignador/grabaoportunidades.php' , 'auth' => true);
$entry_point_registry['actualizar'] = array('file' => 'custom/application/asignador/actualizar.php' , 'auth' => true);
$entry_point_registry['head'] = array('file' => 'custom/application/asignador/head.php' , 'auth' => true);
$entry_point_registry['scripts'] = array('file' => 'custom/application/asignador/scripts.php' , 'auth' => true);
$entry_point_registry['list_contact'] = array('file' => 'custom/application/asignador/list_contact.php' , 'auth' => true);
$entry_point_registry['actualizartabla'] = array('file' => 'custom/application/asignador/actualizartabla.php' , 'auth' => true);
$entry_point_registry['createopportunity'] = array('file' => 'custom/application/createopportunity.php' , 'auth' => false);
$entry_point_registry['createopportunity_desa'] = array('file' => 'custom/application/createopportunity_desa.php' , 'auth' => false);
$entry_point_registry['registro'] = array('file' => 'custom/application/registro.php' , 'auth' => true);
$entry_point_registry['actualiza_nombre_categoria_opp'] = array('file' => 'custom/application/actualiza_nombre_categoria_opp.php' , 'auth' => false);
$entry_point_registry['listar_usuario'] = array('file' => 'custom/application/listar_usuario.php' , 'auth' => false);
//$entry_point_registry['cotizador_email_modal'] = array('file' => 'custom/application/email_cotizador/cotizador_email_modal.php' , 'auth' => false);

$entry_point_registry['emailopcion_v3'] = array('file' => 'custom/app/emailopcion_v3.php', 'auth' => true);
$entry_point_registry['total_op'] = array('file' => 'custom/app/total_op.php', 'auth' => false);
$entry_point_registry['detalle_op'] = array('file' => 'custom/app/detalle_op.php', 'auth' => false);
$entry_point_registry['envio_email_plantilla'] = array('file' => 'custom/app/envio_email_plantilla.php', 'auth' => true);

//*******************************Entry Point del Envio de Plantilla **************************************************// 
$entry_point_registry['funciones'] = array('file' => 'custom/application/envio_plantilla/funciones.php', 'auth' => true);
$entry_point_registry['estilos'] = array('file' => 'custom/application/envio_plantilla/css/estilos.css', 'auth' => true);
$entry_point_registry['index'] = array('file' => 'custom/application/envio_plantilla/index.php', 'auth' => true);
$entry_point_registry['Template_nuevas_oportunidades_CRM'] = array('file' => 'custom/application/envio_plantilla/Template_nuevas_oportunidades_CRM.html', 'auth' => true);

//*******************************Entry Point del Envio de Plantilla **************************************************// 
$entry_point_registry['grabaoportunidades_new'] = array('file' => 'custom/application/asignador_new/grabaoportunidades_new.php' , 'auth' => true);
$entry_point_registry['actualizartabla_new'] = array('file' => 'custom/application/asignador_new/actualizartabla_new.php' , 'auth' => true);
$entry_point_registry['actualizartabla_High'] = array('file' => 'custom/application/asignador/actualizartabla_High.php' , 'auth' => true);
$entry_point_registry['actualizar_new'] = array('file' => 'custom/application/asignador_new/actualizar_new.php' , 'auth' => true);
$entry_point_registry['nuevasoportunidades_new'] = array('file' => 'custom/application/asignador_new/nuevasoportunidades_new.php' , 'auth' => true);
$entry_point_registry['nuevasoportunidades_High'] = array('file' => 'custom/application/asignador/nuevasoportunidades_High.php' , 'auth' => true);
$entry_point_registry['head_new'] = array('file' => 'custom/application/asignador_new/head_new.php', 'auth' => true);
$entry_point_registry['scripts_new'] = array('file' => 'custom/application/asignador_new/scripts_new.php' , 'auth' => true);
?>