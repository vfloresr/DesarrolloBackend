<?php


global $current_user;
global $db, $sugar_config;




	$id_joomla= 7102;

    $json = file_get_contents('http://cms.cocha.com/mobile-api/programas?id='.$id_joomla);
    $data = json_decode($json, TRUE);;
    echo "<br>id: ".$id = $data[0]['id'];
 echo "<br>titulo:".   $titulo = $data[0]['subtitle'];
  echo  $url_pdf = $data[0]['verMas'];
  echo  $tags = $data[0]['tags'];
   echo $nombre_categoria = trim($data[0]['category']);
   echo "<pre>";
   echo "<br>====>". $precio1 = str_replace(".", "", str_replace("US$","",$data[0]['pricing'][0]['mainPrice']));
     echo "<br>====>". $precio2 = str_replace(".", "", str_replace("US$","",$data[0]['pricing'][1]['mainPrice']));
   

     echo "<br>". $destino = $data[0]['destination'];
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
        //$categoria->save();
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
      if($precio1 != '')
  	    $produc->price = $precio1;
  	  if($precio2 != '')
      	$produc->precio2_c = $precio2;
      $produc->aos_product_category_id = $categoria->id;
     // $produc->save();
     }
