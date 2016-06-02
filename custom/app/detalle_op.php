<?php header('Access-Control-Allow-Origin: *'); ?>
<?php  
header('Content-Type: application/json');
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', True);

global $db, $sugar_config;
$fecha = $_REQUEST['fecha'] ;
$fecha2= $_REQUEST['fecha2'] ;

$query = "select o.date_entered as fecha, o.sales_stage as estado,crm.producto_id as id_programa, crm.analytics 
			from opportunities  o
			join opportunities_crm_solicitudes_1_c opp on o.id = opp.opportunities_crm_solicitudes_1opportunities_ida 
			join crm_solicitudes crm on crm.id = opp.opportunities_crm_solicitudes_1crm_solicitudes_idb 
			where o.deleted=0 AND o.opportunity_type='solicitud_web' 
					   AND DATE_FORMAT(o.date_entered , '%Y-%m-%d') >= '".$fecha."'
					   AND  DATE_FORMAT(o.date_entered , '%Y-%m-%d') <= '".$fecha2."'";

		   
if(isset($_REQUEST['fecha'])) 
{
	$res2 = $db->Query($query);
	while($row = $db->fetchByAssoc($res2)){
	   $obj ['fecha']=$row['fecha'];
	   $obj ['estado']=$row['estado'];
	   $obj ['id_programa']=$row['id_programa'];
	   $obj ['analytics']=$row['analytics'];
	   $objeto []= $obj;
	}
	echo json_encode($objeto);
}
	
  






?>