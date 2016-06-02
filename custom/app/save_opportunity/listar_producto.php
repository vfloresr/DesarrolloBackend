<?php 
header('Content-Type: text/html; charset=iso-8859-1');
$categoria = trim($_REQUEST['categoria']);//Rut
$nombre_producto = trim($_REQUEST['nombre_producto']);//Rut

$link = conexion();
$sql = " Select p.name, pcstm.numero_producto_c, pc.name as categoria, pcstm.operador_c from aos_products p
inner join aos_products_cstm pcstm on pcstm.id_c=p.id
inner join aos_product_categories pc on pc.id=p.aos_product_category_id
where p.deleted=0   ";
if ($categoria != '') $sql .= " and pc.id like '%$categoria%' ";
if ($nombre_producto != '') $sql .= " and p.name like '%$nombre_producto%' ";
//echo $sql;

$result = mysql_query($sql);

function conexion(){
  $servidor = "localhost";
  $user = "root";
  $pass = "c0ch4!";
  $db  = "crm2"; 

$conexion = mysql_connect($servidor,$user,$pass);
mysql_select_db($db, $conexion) or die ("Error de conexion n:".date('1'));

return $conexion;
}


?>



        
         <table>
          <thead>
            <th>Nombre Producto</th>
            <th>Categoria</th>
            <th>Operador</th>
            <th></th>
          </thead>
          <tbody>
            <?php while($row = mysql_fetch_array($result)){
              $numero_producto_c= $row['numero_producto_c'];
              $n= $row['name'];
              $ca= $row['categoria'];
              $op= $row['operador_c'];
            ?>
            <tr style="border-top:1pt solid grey;">
              <td><?php echo $row['name']?></td>
              <td><?php echo $row['categoria']?></td>
              <td><?php echo $row['operador_c']?></td>
              <td> <button class="btn btn-primary pull-left" id="id_producto" onclick="agrega_referencia('<?php echo $numero_producto_c?>','<?php echo $n?>','<?php echo $ca?>','<?php echo $op?>')" >Ok</button></td>
            </tr>
            <?php }?>
          </tbody>

         </table>
        


