<?php 

$con = mysql_connect("mysql.awktec.com","awktec02","crmadmin");

if (!$con){
  die('Could not connect: ' . mysql_error());
}

mysql_select_db("awktec02", $con);

?>