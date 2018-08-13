<?php


$user = "euportal1";
$pass = "awk2011terra";
$host = "pgsql.euportal.com.br";
$dbname = "euportal1";
$conexao = pg_connect("host=$host user=$user
password=$pass dbname=$dbname");


$arquivo = file('un_ben.txt');


foreach($arquivo as $i => $linha) {
	$linha = explode(",", $linha);
	$linhas[$i] = utf8_decode($linha[0]);
}

//print_r($linhas);

/*foreach ($linhas as $valor) {
	$sql = "INSERT INTO unidades_beneficiadas (nm_unidade) VALUES ('$valor');";
	$query = pg_query($conexao, $sql);
}*/
$cidade1=utf8_decode('SÃO LOURENÇO DOESTE');
$cidade2=utf8_decode('SÃO MIGUEL DOESTE');
$sql2 = "INSERT INTO unidades_beneficiadas (nm_unidade) VALUES ('$cidade1');";
$sql3 = "INSERT INTO unidades_beneficiadas (nm_unidade) VALUES ('$cidade2');";
$query2 = pg_query($conexao, $sql2);
$query3 = pg_query($conexao, $sql3);

?>

