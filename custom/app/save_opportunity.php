<?php


//header('Access-Control-Allow-Origin: http://admin.example.com');  //I have also tried the * wildcard and get the same response
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Origin, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers');
/*Editado por Tecnich.cl
telefono 68347963
jaime.fuentes@tecnich.cl*/


// 	bf75a2fe-3b13-d135-6438-544ffb617dd9


global $current_user;
global $db, $sugar_config;
$current_user->id=1;
$asignado_a=1;




$nombre = trim($_POST['nombre']);//
$apellido = $_POST['apellido'];//telefono
$rut = $_POST['rut'];//telefono
$email1 = $_POST['email1'];//apellido
$fecha_ida = $_POST['fecha_ida'];//nombre
$adultos = $_POST['adultos'];//email
$ninos = $_POST['ninos'];//producto
$comentarios = $_POST['comentarios'] ;//niños
$celular = $_POST['celular'] ;//adunltos
$region = $_POST['region'];//Mobile
$destino = $_POST['destino'];//fecha viaje
$clasificacion = $_POST['clasificacion'];//fecha viaje
$cantidad_pax_infante = $_POST['cantidad_pax_infante'];//fecha viaje




//formulario cocha
 $div_fecha = explode("/",$fecha_ida); 
  $fecha_ida = $div_fecha[2]."-".$div_fecha[1]."-".$div_fecha[0];


 
	
	$account = new Account();

	$account->retrieve_by_string_fields(array('phone_fax' => $rut));
	
	//die();	
	if(empty($account->id)){
		$GLOBALS['log']->fatal('nueva cuenta');
	}else{
		$GLOBALS['log']->fatal('cuenta existente');
	}
	

	// campos compuestos
	if(empty($account->id)){
		echo 
		$account->name = $nombre.' '.$apellido;
		$account->phone_fax = $rut;
		$account->email1 = $email1;
		$account->phone_office = $celular;

		$account->save();
		$GLOBALS['log']->fatal('carga de cuenta, id: '.$account->id);
	}else
		echo "";



	$contact = new Contact();
	$contact->retrieve_by_string_fields(array('phone_fax' => $rut));
	
	if(empty($contact->id))
	{
		$contact->first_name = $nombre;
		$contact->last_name = $apellido;
		$contact->phone_fax = $rut;
		$contact->clasificacion_c = $clasificacion;
		$contact->phone_work = $celular;
		$contact->email1 = $email1;

		$contact->save();
		//echo "<br>Grabo ##############";
		$GLOBALS['log']->fatal('carga de contacto, id: '.$contact->id);

	} //si el contacto existe no lo actualizo
		

	
	$opportunity = new Opportunity();
	
	
		$opportunity->name = "Oportunidad :".$nombre." ".$apellido;
		$opportunity->fecha_salida_c=$fecha_ida;
		$opportunity->description=$comentarios;
		$opportunity->adultos_c=$adultos;
		$opportunity->ninos_c = $ninos;
		$opportunity->destino_c = $destino;
		$opportunity->region_c = $region;
		$opportunity->cantidad_pax_infante_c = $cantidad_pax_infante;
		$opportunity->save();
	
	$opportunity->load_relationship('contacts');
	$opportunity->contacts->add($contact->id);
	$opportunity->save();
	
	/*//die();
	$GLOBALS['log']->fatal('carga de oportunidad, id: '.$opportunity->id);

	// relaciones
	// cuenta con el contacto
	$account->load_relationship('contacts');
	$account->contacts->add($contact->id);
	
	// cuenta con la oportunidad
	$account->load_relationship('opportunities');
	$account->opportunities->add($opportunity->id);
	$account->save();
	$GLOBALS['log']->fatal('carga de relacion cuenta con oportunidad');
	
	
	//var_dump($opportunity);
	
	$GLOBALS['log']->fatal('carga de relacion cuenta con oportunidad');

	//agrega producto
	$opportunity->load_relationship('opportunities_aos_products_1');
	$opportunity->opportunities_aos_products_1->add($producto->id);
	$opportunity->save();
	*/
echo "Formulario Enviado";