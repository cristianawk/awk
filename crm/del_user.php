<?php  session_start();


$id = $_GET["id"];

include_once('conexao.php');

mysql_query("DELETE FROM usuarios where id='$id'");

echo "

<script>
alert('Usuario Excluido com Sucesso!');
window.location.href='usuarios.php';
</script>

";

?>