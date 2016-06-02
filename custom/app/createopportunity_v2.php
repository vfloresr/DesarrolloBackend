<?php


//header('Access-Control-Allow-Origin: http://admin.example.com');  //I have also tried the * wildcard and get the same response
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Origin, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers');



// 	bf75a2fe-3b13-d135-6438-544ffb617dd9


global $current_user;
global $db, $sugar_config;
$current_user->id=1;
$asignado_a=1;

$id_joomla='';

if(isset($_POST['Field230']))
{
	$id_joomla = $_POST['Field230'];//id_joomla
	$precio_joomla = $_POST['Field233'];//precio
	$url_joomla = $_POST['Field232'];//url
	$nombre_joomla = $_POST['Field234'];//nombre
	$tour_operador=$_POST['Field236'];//nombre
	$pdf_joomla=$_POST['Field238'];//pdf_joomla
	
	$simb = array("|","%7C","%7c");
	$simb2 = array("http:/","http://","http:");

	$pdf_joomla = str_replace($simb2, "", $pdf_joomla);
	$url_joomla = str_replace($simb, "?", $url_joomla);

}

echo "<br>Rut:".$_POST['Field223'] = trim($_POST['rut'])."-".trim($_POST['digito']);//Rut
echo "<br>Telefono:".$_POST['Field113'] = $_POST['cel_pais'].$_POST['cel_code'].$_POST['phone'];//telefono
echo "<br>Apellido:".$_POST['Field4'] = $_POST['Field4'];//apellido
echo "<br>Nombre:".$_POST['Field3'] = $_POST['Field3'];//nombre
echo "<br>Email:".$_POST['Field117'] = $_POST['Field117'];//email
echo "<br>Producto:".$_POST['Field120'] = $_POST['Field120'];//producto
echo "<br>Niños:".$_POST['Field110'] = $_POST['Field110'] ;//niños
echo "<br>Adultos:".$_POST['Field109'] = $_POST['Field109'] ;//adunltos
echo "<br>Canal:".$_POST['Field227'] = $_POST['Field227'];//Mobile
echo "<br>Fecha Viaje:".$_POST['Field225'] = $_POST['Field225'];//fecha viaje
echo "<br>Fecha flexible:".$_POST['fecha_flexible'] = $_POST['fecha_flexible'];//fecha viaje
echo "<br>habitaciones:".$_POST['habitaciones'] = $_POST['habitaciones'];//fecha viaje

echo "<br>Ejecutiva:".$_POST['Field118'] = $_POST['Field118'];//ejecutiva
echo "<br>Comentarios:".$_POST['Field111'] = $_POST['Field111'];//comentarios

$url_wufo = $_POST['Field240'];//url wufo domino

if(isset($_POST['Field300'])) $url_producto = $_POST['Field300']; else $url_producto='';//URL
if(isset($_POST['Field301'])) $pdf_producto = $_POST['Field301']; else $pdf_producto='';//URL

if($_POST['Field227'] =='')$_POST['Field227']= "Web";

 $div_fecha = explode("/",$_POST['Field225']); 
 echo "<br>fecha viaje final:". $_POST['Field225'] = $div_fecha[2]."-".$div_fecha[1]."-".$div_fecha[0];


$analytics = trim($_POST['analytics']);


//echo "<br>##############entro a createoportunity_v2###################";
for($i=0;$i<10;$i++)
{
	if(isset($_POST['edad-'.$i]))  $arr_edad[]= $_POST['edad-'.$i];
}


$fp = fopen("custom/app/historial_oportunidades.txt","a");
fwrite($fp, "IP:".$_SERVER["REMOTE_ADDR"]." Rut: ".$_POST['Field223']." ".date('d/m/Y H:i:s')." Email:".$_POST['Field117']." Telefono:".$_POST['Field113']."  Id_jooml:$id_joomla" . PHP_EOL);
fclose($fp);
//print_r($arr_edad);	
 //die();

if($id_joomla == '6578') $_POST['Field118']='Lia Orsini';

$query = "SELECT prod_cstm.numero_producto_c 
		FROM aos_products prod
		inner join aos_products_cstm prod_cstm on prod_cstm.id_c =prod.id
		WHERE   prod.deleted=0  and prod_cstm.operador_c='COCHAJOVEN'";

$res = $db->Query($query);
while($row = $db->fetchByAssoc($res)){
	$productos_cocha_joven[] = $row['numero_producto_c'];
}	

//print_r($productos_cocha_joven);
//die();
//$productos_cocha_joven = array('1587','1578','1579','1576','1580','1581','1582','1583','1585','1585','1577','1586',);


if (in_array($_POST['Field120'], $productos_cocha_joven)) {
	$canal = 'CochaJoven';
	$asignado_a='9692dca3-f6e4-0ca8-8bc6-54636fd61620';
	$_POST['Field227'] = 'CochaJoven';//Web
}else{	
	$canal = $_POST['Field227'];
}

	$map = array(
		'Contact'=>array(
			'Field4'=>'last_name',
			'Field3'=>'first_name',
			'Field117'=>'email1',
			'Field113'=>'phone_home',
			'Field223'=>'rut_c',
		),
		'Account'=>array(
			'Field113'=>'phone_office',
			'Field223'=>'rut_c',
			'Field117'=>'email1',
			//array('Field223','phone_fax'),
		),
		'Opportunity' => array(
			
//			'Field120'=>'name', // compuesto referencia
			'Field109'=>'pasajeros_adultos_c',
			'Field110'=>'pasajeros_ninos_c',
			'Field227'=>'lead_source',
			'Field225'=>'fecha_viaje_c',
			'Field111'=>'description',
			'Field118'=>'agente_c',
			'fecha_flexible'=>'fecha_flexible_c',
			'habitaciones'=>'habitaciones_c',
			// campaña, nombre de producto, operador
		),
	);
	
	//var_dump($map['Account']);	
	$account = new Account();

	if($canal == 'Web' or $canal == 'CochaJoven'){//consulta si hay un cliente con el mismo rut, si hay no crea uno nuevo

		$account->retrieve_by_string_fields(array('rut_c' => $_POST['Field223']));
	}
	else if($canal != 'Web' and $canal != 'CochaJoven'){//consulta si hay un cliente con el mismo email, si hay no crea otro cliente
		$email = new EmailAddress();
		

		
	

		 $query = "SELECT email_addresses.*, email_addr_bean_rel.bean_id 
		FROM email_addresses inner join email_addr_bean_rel on email_address_id=email_addresses.id
		WHERE email_addresses.email_address = '".$_POST['Field117']."' AND email_addresses.deleted=0
		and bean_module='Accounts' and  email_addr_bean_rel.deleted=0";
		$res = $db->Query($query);
		
		if($row = $db->fetchByAssoc($res)){

			$id_cliente_email =  $row['bean_id'];
		
		}         
		//die();

		$account->retrieve($id_cliente_email);

	}
	//die();	
	if(empty($account->id)){
		$GLOBALS['log']->fatal('nueva cuenta');
	}else{
		$GLOBALS['log']->fatal('cuenta existente');
	}
	

	// campos compuestos
	if(empty($account->id)){
		foreach ($map['Account'] as $key => $value){
		$account->$value = $_POST[$key];
		//$GLOBALS['log']->fatal('value = '.$value.' key: '.$key);
		}
		$account->name = $_POST['Field4'].' '.$_POST['Field3'];
		$account->save();
		//$GLOBALS['log']->fatal('carga de cuenta, id: '.$account->id);
	}else{
		if($_POST['Field3']!='' and $_POST['Field4']!='')
			$account->name = $_POST['Field3'].' '.$_POST['Field4'];

		if($_POST['Field117']!=''){
			$account->email1 = $_POST['Field117'];	
			
		}	
		if($_POST['Field113']!='')
			$account->phone_office = $_POST['Field113'];			

		$account->save();
	} 


	$contact = new Contact();
	if($canal != 'Web' and $canal != 'CochaJoven'){
		 $query = "SELECT email_addresses.*, email_addr_bean_rel.bean_id 
		FROM email_addresses inner join email_addr_bean_rel on email_address_id=email_addresses.id
		WHERE email_addresses.email_address = '".$_POST['Field117']."' AND email_addresses.deleted=0
		and bean_module='Contacts' and  email_addr_bean_rel.deleted=0";
		$res = $db->Query($query);
		
		if($row = $db->fetchByAssoc($res)){
			$id_contacto_email =  $row['bean_id'];
		}  

		$contact->retrieve($id_contacto_email);

	}else{	
		$contact->retrieve_by_string_fields(array('rut_c' => $_POST['Field223']));
	}
	if(empty($contact->id)){
		$GLOBALS['log']->fatal('nuevo contacto');
	}else{
		$GLOBALS['log']->fatal('contacto existente');
	}
	// campos simples
	foreach ($map['Contact'] as $key => $value){
		$contact->$value = $_POST[$key];
	}
	if(empty($contact->id)) //si el contacto existe no lo actualizo
		$contact->save();
	//$GLOBALS['log']->fatal('carga de contacto, id: '.$contact->id);


	$id_oportunidad_cliente='';

		$query = "SELECT id,  date_modified  , opportunity_id
		FROM `accounts_opportunities`
		WHERE date_modified >= DATE_ADD( now( ) , INTERVAL -19 HOUR )
		AND date_modified <= DATE_ADD( now( ) , INTERVAL 5 HOUR )
		AND deleted =0 AND account_id = '".$account->id."' ORDER BY date_modified DESC 	LIMIT 0 , 5";
		$res = $db->Query($query);
		
		if($row = $db->fetchByAssoc($res)){
				$id_oportunidad_cliente =  $row['opportunity_id'];
		}       
		//die();  

	//aqui entra si el cliente no tiene ninguna oportunidad en la ultimas 24 hrs	

	$producto = new AOS_Products();
	

	if($id_joomla =='')
	{
		$producto->retrieve_by_string_fields(array('numero_producto_c' => $_POST['Field120']));
		//if($url_producto!='' and $_POST['Field227']='Mobile'){
			if($url_producto != '')
				$producto->link_producto_c=$url_producto;
			if($pdf_producto != '')
				$producto->link_pdf_c=$pdf_producto;
			$producto->save();
		//}





	}else//entra si es joomla
	{
		//echo "<br>query<br><br>".
		$query = "SELECT aos_products_cstm.numero_producto_c,  aos_products.id 
		FROM aos_products inner join aos_products_cstm on aos_products_cstm.id_c = aos_products.id
		WHERE aos_products_cstm.numero_producto_c='".$id_joomla."' and aos_products_cstm.origen_c='joomla' and aos_products.deleted=0 ";
		$res = $db->Query($query);
		//echo "<br>";
		if($row = $db->fetchByAssoc($res)){
				$id_producto_joomla =  $row['id'];
		}       
		if($id_producto_joomla != '') //si producto existe
		{
			$producto->retrieve($id_producto_joomla);
			if($_POST['Field227'] == 'Web')
				$producto->link_pdf_c=$pdf_joomla;
			else
				$producto->link_pdf_c=$pdf_producto;
			
			if($url_wufo!='')
				$producto->url=$url_wufo;
			if($nombre_joomla!='')
				$producto->name=$nombre_joomla;
			$producto->save();
		}
		else
		{ // si el producto no existe
			$producto->link_producto_c=$url_joomla;
			$producto->name=$nombre_joomla;
			$producto->price=$precio_joomla;
			$producto->numero_producto_c=$id_joomla;
			$producto->origen_c='joomla';
			if($_POST['Field227'] == 'Web' and $pdf_joomla != '')
				$producto->link_pdf_c=$pdf_joomla;
			else if($_POST['Field227'] != 'Web')
					$producto->link_pdf_c=$pdf_producto;
			$producto->operador_c=$tour_operador;
			$producto->url=$url_wufo;
			$producto->save();
		}
	}	
	//$producto->load_relationship('product_categories');
	//$nombre_categoria = $producto->product_categories->name;
	$categoria = new AOS_Product_Categories();
	$categoria->retrieve($producto->aos_product_category_id);
	$nombre_categoria = $categoria->name;

	$opportunity = new Opportunity();
	
	if($id_oportunidad_cliente==''){
		
		//aos_product_category_name
		//$opportunity->name = $producto->name.' / '.$_POST['Field4'].' '.$_POST['Field3'];
		if($nombre_categoria =='') $nombre_categoria='SIN CATEGORIA';
		$opportunity->name = $nombre_categoria.' / '.$_POST['Field3'].' '.$_POST['Field4'];
		foreach ($map['Opportunity'] as $key => $value){
			
			$opportunity->$value = $_POST[$key];

		}
		$opportunity->assigned_user_id = $asignado_a;
		$opportunity->analytics_c = $analytics;

		for($i=0;$i<count($arr_edad);$i++)
		{	
			$j=$i+1;
			switch ($j) {
				case '1':
					 $opportunity->edad_1_c= $arr_edad[$i]; break;
				case '2':
					 $opportunity->edad_2_c= $arr_edad[$i]; break;
				case '3':
					 $opportunity->edad_3_c= $arr_edad[$i]; break;
				case '4':
					 $opportunity->edad_4_c= $arr_edad[$i]; break;
				case '5':
					 $opportunity->edad_5_c= $arr_edad[$i]; break;
				case '6':
					 $opportunity->edad_6_c= $arr_edad[$i]; break;
				case '7':
					 $opportunity->edad_7_c= $arr_edad[$i]; break;
				case '8':
					 $opportunity->edad_8_c= $arr_edad[$i]; break;	
				case '9':
					 $opportunity->edad_9_c= $arr_edad[$i]; break;	
				case '10':
					 $opportunity->edad_10_c= $arr_edad[$i]; break;		 	 						
			}
			
		}

		$opportunity->save();
	}else{
		$opportunity->retrieve($id_oportunidad_cliente);
		$opportunity->description = $opportunity->description."    New: ".$_POST['Field111'];
	}
	
	
	//die();
	//$GLOBALS['log']->fatal('carga de oportunidad, id: '.$opportunity->id);

	// relaciones
	// cuenta con el contacto
	$account->load_relationship('contacts');
	$account->contacts->add($contact->id);
	
	// cuenta con la oportunidad
	$account->load_relationship('opportunities');
	$account->opportunities->add($opportunity->id);
	$account->save();
	//$GLOBALS['log']->fatal('carga de relacion cuenta con oportunidad');
	
	$opportunity->load_relationship('contacts');
	//var_dump($opportunity);
	$opportunity->contacts->add($contact->id);
	$opportunity->save();
	//$GLOBALS['log']->fatal('carga de relacion cuenta con oportunidad');

	//agrega producto
	$opportunity->load_relationship('opportunities_aos_products_1');
	$opportunity->opportunities_aos_products_1->add($producto->id);
	$opportunity->save();
	//
	if($producto->id != '' and $id_oportunidad_cliente=='')
		{
			$id = date("dmYHis")."".$_POST['Field223'];
			$query = "insert into aos_products_opportunities_1_c 
			(id,date_modified,deleted,aos_products_opportunities_1aos_products_ida,aos_products_opportunities_1opportunities_idb) 
			values('".$id."',now(),0,'".$producto->id."','".$opportunity->id."')";
			
			$res =$db->Query($query);

			/*
			//if($id_joomla=='7102'){
				echo "";

					    $json = file_get_contents('http://cms.cocha.com/mobile-api/programas?id='.$id_joomla);
					    $data = json_decode($json, TRUE);;
					    echo "<br>id: ".$id = $data[0]['id'];
					    $titulo = $data[0]['subtitle'];
					  echo  $url_pdf = $data[0]['verMas'];
					  echo  $tags = $data[0]['tags'];
					   echo $nombre_categoria = trim($data[0]['category']);
					   echo "<pre>";
					   echo "<br>====>". $precio1 = str_replace(".", "", str_replace("US$","",$data[0]['pricing'][0]['mainPrice']));
     					echo "<br>====>". $precio2 = str_replace(".", "", str_replace("US$","",$data[0]['pricing'][1]['mainPrice']));
					   
     					//if(strpos($nombre_categoria, 'oferta'))

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
					        $categoria->save();
					        echo "<br>$k ".$nombre_categoria;
					        $k++;
					        
					       }

					      $produc = new AOS_Products();
					      $produc->retrieve($producto->id);
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
					      $produc->save();
					     }
 			//		}//fin if
 			*/
		
			      
			
		}else $GLOBALS['log']->fatal('formulario nuevo => No realiza insert  id producto:'.$producto->id.' id opp cliente:'.$id_oportunidad_cliente);	

//$id_prod = $producto->id;
$producto = new AOS_Products();
	$producto->retrieve($producto->id);

		$new_producto= new t2_productos_oportunidad();
	$new_producto->name = $producto->name;
	$new_producto->numero_producto = $producto->numero_producto_c;
	$new_producto->origen = $producto->origen_c;
	$new_producto->categoria = $nombre_categoria;
	$new_producto->operador = $producto->operador_c;
	$new_producto->tags = $producto->tags_c; 
	$new_producto->precio1_c = number_format($producto->price,0,"",""); 
	$new_producto->precio2_c = number_format($producto->precio2_c,0,"",""); 
	$new_producto->destino = $producto->destinos_c; 
	$new_producto->canal = $canal;

	$new_producto->url_joomla = $producto->url;
	$new_producto->link_pdf = $producto->link_pdf_c;
	$new_producto->link_al_producto = $producto->link_producto_c;
	$new_producto->description = $producto->description;
	$new_producto->save();
	$new_producto->load_relationship('t2_productos_oportunidad_opportunities');
	$new_producto->t2_productos_oportunidad_opportunities->add($opportunity->id);

echo "################ ok ###########";