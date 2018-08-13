<?php 

$con = mysql_connect("crm_awk.mysql.dbaas.com.br","crm_awk","awkloca17");

if (!$con) {
  die('Could not connect: ' . mysql_error());
  }
mysql_select_db("crm_awk", $con);

?>