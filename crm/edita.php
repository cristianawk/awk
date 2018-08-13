<?php session_start();

$atendente = $_SESSION['usuarioID'];
$nivel = $_SESSION['usuarioNivel'];

$senha = $_POST["senha"];
$edita = $_GET["edita"];

include_once('conexao.php');

$query = mysql_query("SELECT * FROM usuarios where id = '$atendente'") or die(mysql_error());
$row = mysql_fetch_array($query);

if($edita != "" and $senha != ""){

mysql_query("UPDATE usuarios SET senha='$senha' where id = '$atendente'") or die(mysql_error());

echo "

<script>
alert('Senha alterada com sucesso!');
window.close();
</script>

";

}



mysql_close($con);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Editar Dados</title>

<style type="text/css">
body {background-color: #EEE;}

.texto {border:1px solid #CCC; height: 20px;}

</style>

</head>

<body>


<form name="edita" id="edita" method="POST" action="<?php $_SESSION['PHP_SELF'] ?>?edita=s">

<table width="200" border="0" align="center" style="font-family:Arial, Helvetica, sans-serif; font-size: 12px; background-color:#EEE; border: 1px solid #CCC;">
  <tr>
    <td colspan="2" style="border: none; background-color:#CCC; text-align:center;">Alterar Senha de Acesso</td>
    </tr>
  <tr>
    <td>Nome</td>
    <td><input type="text" name="nome" id="nome" readonly="readonly" value="<?php echo $row["nome"]; ?>"  class="texto"/></td>
  </tr>
  <tr>
    <td>Usuario</td>
    <td><input type="text" name="usuario" id="usuario" readonly="readonly" value="<?php echo $row["usuario"]; ?>"   class="texto"/></td>
  </tr>
  <tr>
    <td>Senha</td>
    <td><input type="password" name="senha" id="senha" value="<?php echo $row["senha"]; ?>"   class="texto"/></td>
  </tr>
  <tr>
    <td colspan="2" align="right"><input type="submit" name="envia" value="Alterar" /></td>
  </tr>
</table><br /><br /><br />
</form>

</body>
</html>