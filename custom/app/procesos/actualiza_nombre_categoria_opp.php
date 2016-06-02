<?php  
 ini_set('error_reporting', E_ALL);
 ini_set('display_errors', True);
 ini_set('max_execution_time', 0);
  
 global $current_user;
 global $db, $sugar_config;

 $fecha= date("Y-m-d");
$oportunidades = new Opportunity(); 
$id_producto='';
$list_contact = $oportunidades->get_full_list("", "opportunities.name like '%SIN CATEGORIA%' and date(opportunities.date_entered)>=DATE(DATE_ADD(now(),INTERVAL -7 DAY))  ",true);
foreach ($list_contact as $key) {
  

    $query = "SELECT aos_products_opportunities_1aos_products_ida as id_producto 
    FROM  aos_products_opportunities_1_c
    WHERE  aos_products_opportunities_1opportunities_idb =  '".$key->id."'";
    $res = $db->Query($query);

    if($row = $db->fetchByAssoc($res)){
          $id_producto=$row['id_producto'];
    }      
    //echo "<br><br>id oportunidad : ".$key->id." --> ((((".$id_producto."))))";
    if($id_producto != '')
    {
      $producto = new AOS_Products(); 
      $producto->retrieve($id_producto);
      $id_categoria = $producto->aos_product_category_id;
      $categoria = new AOS_Product_Categories();
      $categoria->retrieve($producto->aos_product_category_id);
      if($categoria->name != '')
      {
        echo "<br><br>";
      echo "<br>nombre: ". $nombre_oportunidad = $key->name;
      echo " id: ". $key->id;
        echo "   Nuevo: ". $nuevo_nombre=str_replace("SIN CATEGORIA", $categoria->name, $nombre_oportunidad) ;
        $opp = new Opportunity(); 
        $opp->retrieve($key->id);
        $key->name=$nuevo_nombre;

        echo "<br>".$query = "update opportunities set name='".$nuevo_nombre."' where  id='".$key->id."'";
        $res = $db->Query($query);

          


      //$key->save();
      }
    }else echo "<br><br> En la oportunidad ".$key->id." No hay producto asignado";
    //print_r($producto);
    //die();

}


  ?>
