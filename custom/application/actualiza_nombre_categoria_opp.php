<?php  
 ini_set('error_reporting', E_ALL);
 ini_set('display_errors', True);
 ini_set('max_execution_time', 0);
  
 global $current_user;
 global $db, $sugar_config;
/*
 $fecha= date("Y-m-d");
$oportunidades = new Opportunity(); 
$id_producto='';
$list_contact = $oportunidades->get_full_list("", "opportunities.date_entered >= '2016-02-01' and opportunities.amount = '' and opportunities.deleted = 0 and opportunity_type = 'solicitud_web' ",true);

foreach ($list_contact as $key) {
  

    $query = "select numero_producto as id_producto from crm_solicitudes where id = '".$key->crm_solicitud_id_c."'";
    $res = $db->Query($query);

    if($row = $db->fetchByAssoc($res)){
          $id_producto=$row['id_producto'];
    }      
    //echo "<br><br>id oportunidad : ".$key->id." --> ((((".$id_producto."))))";
    if($id_producto != '')
    {
	
		$json = file_get_contents('http://cms.cocha.com/mobile-api/programas?id='.$id_producto);
		$data = json_decode($json, TRUE);
		//echo "<br>id: ".$id = $data[0]['id'];
		$titulo = $data[0]['title'];
		$url_pdf = $data[0]['verMas'];
		$tags = $data[0]['tags'];
		 $nombre_categoria = trim($data[0]['category']);
		 $destino = $data[0]['destination'];
		 $precio =  str_replace('US$','',$data[0]['pricing'][0]['mainPrice']);
      if($nombre_categoria != '')
      {*/
        //echo "<br><br>";
    //  echo "<br>nombre: ". $nombre_oportunidad = $key->name;
     // echo " id: ". $key->id;
       // echo "   Nuevo: ". $nuevo_nombre=str_replace("SIN CATEGORIA", $categoria->name, $nombre_oportunidad) ;
		
		//$nombre = explode("/",$key->name); 
		
		//$query_2="update opportunities set name = '".$nombre_categoria.' / '.$nombre[1]."' where id = '".$key->id."'"; 
		//$query_2="update opportunities set amount = '".$precio."',amount_usdollar = '".$precio."' where id = '".$key->id."'"; 
		//$respt = $db->Query($query_2);
	  
		$query="select distinct crm.producto_id from crm_solicitudes crm where (crm.precio = '' or crm.precio is null or crm.precio = '0') and crm.producto_id <> ''";
			$respt = $db->Query($query);
			//$solicitud = new crm_solicitudes();
			while($value = $db->fetchByAssoc($respt)){
				
				//$solicitud->retrieve($value['id']);
				$json = file_get_contents('http://cms.cocha.com/mobile-api/programas?id='.$value['producto_id']);
				$data = json_decode($json, TRUE);
				$query2="update crm_solicitudes crm set crm.precio = '".str_replace('US$','',$data[0]['pricing'][0]['mainPrice'])."' where crm.numero_producto = '".$value['producto_id']."'";
				$respt_2 = $db->Query($query2);
				/*$solicitud->tour_operador= str_replace('"]', '',str_replace('["', '',str_replace('","', '',$data[0]['tourOperador'])));
				$solicitud->categoria_nombre = trim($data[0]['category']);
				$solicitud->destino = $data[0]['destination'];
				$solicitud->pdf_joomla= trim($data[0]['verMas']);*/
				// $solicitud->precio= str_replace('US$','',$data[0]['pricing'][0]['mainPrice']);
				// $solicitud->save();
			}	
      /*}
    }else echo "<br><br> En la oportunidad ".$key->id." No hay producto asignado";
    //print_r($producto);
    //die();

}*/


  ?>
