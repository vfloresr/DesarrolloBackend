<?php header('Access-Control-Allow-Origin: *'); ?>
<?php  
header('Content-Type: application/json');
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', True);

global $db, $sugar_config;
$fecha = $_REQUEST['fecha'] ;
$fecha2= $_REQUEST['fecha2'] ;

$query1 = "SELECT count(*) as cantidad 
           FROM opportunities where deleted=0 AND opportunity_type='solicitud_web' 
           AND DATE_ADD( date_entered , INTERVAL -3 HOUR )>= '".$fecha."'
		   AND DATE(DATE_ADD( date_entered , INTERVAL -3 HOUR ))<= '".$fecha2."'";
		   
$query2 = "SELECT count(*) as cantidad FROM opportunities where deleted=0 AND opportunity_type='solicitud_web' AND DATE(DATE_ADD( date_entered , INTERVAL -3 HOUR ))= '".$fecha."'";

		   
if(isset($_REQUEST['fecha'])) 
{
	if(isset($_REQUEST['fecha2'])) {
		$query = $query1;
	}else{
		$query = $query2;
	}

	$res2 = $db->Query($query);
	if($row = $db->fetchByAssoc($res2)){
	  echo json_encode($row['cantidad']);
	}
}
	
  






?>