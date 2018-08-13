<?php  session_start();



$id = $_GET["id"];
$edita = $_GET["edita"];
$atendente = $_SESSION['usuarioID'];
$nivel = $_SESSION['usuarioNivel'];
$senha = $_SESSION['senha'];

if($nivel == "1"){

$nome = $_POST["nome"];
$usuario = $_POST["usuario"];
$senha = $_POST["senha"];
$nivel2 = $_POST["nivel"];



include_once('conexao.php');

$query = mysql_query("SELECT * FROM usuarios where id = '$id'");
$row = mysql_fetch_array($query);


if($row["nivel"] == "0"){
	$nivel1 = "Operador";
}
		
if($row["nivel"] == "1"){
	$nivel1 = "Administrador";
}		

if($edita != ""){

$id2 = $_POST["id"];

mysql_query("UPDATE usuarios SET nome='$nome', usuario='$usuario', senha='$senha', nivel='$nivel2' where id='$id2'");

echo "

<script>
alert('Dados alterados com sucesso!');
window.location.href='usuarios.php';
</script>

";

}
mysql_close($con);

}else{
	
	echo "
	<script>
	alert('Você não tem permissão para acessar esta página!');
	window.location.href='index.php';
	 </script>
	";
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<META NAME="robots" CONTENT="noindex, nofollow">

<META NAME="robots" CONTENT="noarchive">

<META NAME="robots" CONTENT="index, nofollow, noarchive">
<title>Editar Usuario</title>
</head>

<body>

<form name="edita" id="edita" method="POST" action="<?php $_SESSION['PHP_SELF'] ?>?edita=s">

<table border="0" align="center" style="font-family:Arial, Helvetica, sans-serif; font-size: 12px; background-color:#EEE; border: 1px solid #CCC;">

<input type="hidden" name="id" value="<?php echo $row["id"]; ?>" />
  <tr>
    <td colspan="2" style="border: none; background-color:#CCC; text-align:center;">Editar Usuario</td>
    </tr>
  <tr>
    <td>Nome</td>
    <td><input type="text" name="nome" id="nome" value="<?php echo $row["nome"]; ?>"  class="texto"/></td>
  </tr>
  <tr>
    <td>Usuario</td>
    <td><input type="text" name="usuario" id="usuario" value="<?php echo $row["usuario"]; ?>"   class="texto"/></td>
  </tr>
  <tr>
    <td>Senha</td>
    <td><input type="text" name="senha" id="senha" value="<?php echo $row["senha"]; ?>"   class="texto"/></td>
  </tr>
  <tr>
    <td>Nivel de Acesso</td>
    <td><select name="nivel">
      <option value="<?php echo $row["nivel"]; ?>"><?php echo $nivel1; ?></option>
      <option value="0">Operador</option>
      <option value="1">Administrador</option>
    </select></td>
  </tr>
  <tr>
    <td colspan="2" align="right"><input type="submit" name="envia" value="Alterar" /></td>
  </tr>
</table><br /><br /><br />
</form>

</body>
</html>