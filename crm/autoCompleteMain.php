<?php



session_start();



$host="186.202.152.65"; // Host name

$username="educagenesis1"; // Mysql username

$password="genesis1995"; // Mysql password

$db_name="educagenesis1"; // Database name



$con = mysql_connect($host,$username,$password) or die(mysql_error());

mysql_select_db($db_name, $con) or die(mysql_error());



$q = strtolower($_GET["q"]);

if (!$q) return;



$sql = "select DISTINCT prospecto_nome as prospecto_nome, prospecto_id from prospectos where prospecto_nome LIKE '%$q%' OR prospecto_responsavel LIKE '%$q%' OR prospecto_cidade LIKE '%$q%' and prospecto_status = '1'";

$rsd = mysql_query($sql);

while($rs = mysql_fetch_array($rsd)) {

$cname = $rs['prospecto_nome'];

$cid = $rs['prospecto_id'];



echo "$cid - $cname\n";



}

?>