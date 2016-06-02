<?php 
header('Content-Type: text/html; charset=iso-8859-1');


$link = conexion();
$sql = " select id,name from aos_product_categories where deleted=0  ";


$result = mysql_query($sql);

function conexion(){
  $servidor = "localhost";
  $user = "root";
  $pass = "c0ch4!";
  $db   = "crm2"; 

$conexion = mysql_connect($servidor,$user,$pass);
mysql_select_db($db, $conexion) or die ("Error de conexion n:".date('1'));

return $conexion;
}


?>   
<option value=""> -- </option>
<?php while($row = mysql_fetch_array($result)){      ?>
<option value="<?php echo $row['id']?>"><?php echo $row['name']?></option>

<?php }?>



