<?php  
 ini_set('error_reporting', E_ALL);
 ini_set('display_errors', True);
 ini_set('max_execution_time', 0);
  
 global $current_user;
 global $db, $sugar_config;

 
$productos = new AOS_Products(); 
$list_contact = $productos->get_full_list("aos_products.full_name", "aos_products_cstm.origen_c = 'joomla' and  aos_products.date_modified >= DATE(DATE_ADD(now(),INTERVAL -2 DAY)) ",true);
$i=0;$k=0;
foreach ($list_contact as $key) {

     
   


    $json = file_get_contents('http://cms.cocha.com/mobile-api/programas?id='.$key->numero_producto_c);
    $data = json_decode($json, TRUE);;
    echo "<br>id: ".$id = $data[0]['id'];
    $titulo = $data[0]['title'];
    $url_pdf = $data[0]['verMas'];
    $tags = $data[0]['tags'];
     $nombre_categoria = trim($data[0]['category']);
     $destino = $data[0]['destination'];
     echo "   toru antes:". $tour_operador=$data[0]['tourOperador'];
     $tour_operador = str_replace('","', '',$tour_operador);
     $tour_operador = str_replace('["', '', $tour_operador);
     $tour_operador = str_replace('"]', '', $tour_operador);
     echo " ---> ". $tour_operador;
     
     if($id != '')
     {
        $i++;
        $categoria = new AOS_Product_Categories();
        $categoria->retrieve_by_string_fields(array('name' => $nombre_categoria));
        if($categoria->id) echo "";
        else{
      
        $categoria->name =$nombre_categoria;
        $categoria->assigned_user_id =1;
        $categoria->description ='creada automatica';
        $categoria->save();
        echo "<br>$k ".$nombre_categoria;
        $k++;
        
       }

      $produc = new AOS_Products();
      $produc->retrieve($key->id);
      $produc->name = $titulo;
      $produc->destinos_c = $destino;
      $produc->link_pdf_c = $url_pdf;
      $produc->tags_c = $tags;
      $produc->operador_c = $tour_operador;
      $produc->categoria_extra_c = $nombre_categoria;
      $produc->aos_product_category_id = $categoria->id;
      $produc->save();
     }
   



  
}
echo "<br>##########################
<br>TOTAL: ".$i;
echo "<br>NUEVAS CATEGORIAS: ".$k;



  ?>
