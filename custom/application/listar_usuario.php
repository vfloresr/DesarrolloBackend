<?php
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Credentials: true");
	header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
	header('Access-Control-Max-Age: 1000');
	header('Access-Control-Allow-Headers: Origin, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers');
global $app_strings, $app_list_strings, $sugar_config, $timedate, $current_user,$db;

$user= new User();
//$list_user = $user->get_full_list("", " users.status = 'Active' and users.department is not null and users.deleted = 0",true);
//$list_user = $user->get_full_list("", " users.status = 'Active' and users.deleted = 0 and sucursal_c in ('EXTERNOS','WORKFLOW')",true);
if (!isset($_GET['datos'])){
	$dato = '99999999';
}else{
	$dato = $_GET['datos'];
}
$query = " SELECT
distinct
  concat(
    u.last_name,
    ' ',
    u.first_name)
    AS nombre_user,
  u.id AS id_user
FROM
  users u,
  users_cstm ud
WHERE
  u.id = ud.id_c AND
  u.status = 'Active' AND
  concat(
    u.first_name,
    ' ',
    u.last_name) LIKE
    '$dato%' AND
  u.deleted = 0 AND
  ud.sucursal_c IN ('EXTERNOS',
                    'WORKFLOW',
                    'CONTACT') AND
  u.id NOT IN ('1',
               'cb5dddb1-5a34-fc92-152d-541839f37905',
               '25e35342-406f-2095-4847-55638d163fc9',
               '588daaa8-bf2d-99ea-6028-55638d1b528f',
               '5e0d4d3a-db30-114f-8c2b-55638dda6e7e',
               '774eec7a-3b7c-d4b7-3181-55638d10aebc',
               'c6324bf9-1b0c-238f-9227-55638d74e933',
               'ca8e5416-a398-6465-6693-55638d9c9c09',
               'd9ef28ff-768b-ff69-0829-55638d0b5293',
               'bdbc32fa-200d-2aed-4611-5660b86e05dc')
ORDER BY
  1 ASC";
		  
		$query_db = $db->Query($query);

		$respuestas = array();
		
		while ( $row = $db->fetchByAssoc($query_db) ) 
		{
			$obj ['id_user']		=$row['id_user'];
			$obj ['nombre_user']	=$row['nombre_user'];
			$respuestas[] = $obj;
		}

// sort($list_user); 
// foreach ($list_user as $n) {
	// $obj ['id_user']		=$n->id;
	// $obj ['nombre_user']	=ucwords($n->full_name);
	// $objeto [] = $obj;
    
// }
echo json_encode ($respuestas);

?>

