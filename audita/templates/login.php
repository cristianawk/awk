<?php
?>
<form target="_self" enctype="multipart/form-data" method="post" name="frm_login"
      id="frm_login" onsubmit="" onreset="" action="./index.php?acesso=<?php echo $_GET['acesso']?>">
<fieldset class="login">
<br>
<table align="center">
<?php if($erro_sessao){?><tr class="erro"><th colspan="2" style="text-align:center;"><?php echo $erro_sessao?></th></tr><?php }?>
<tr>
	<th>LOGIN:</th>
	<td><input type="text" name="username" id="username" value="" class="username"></td>
</tr>
<tr>
	<th>SENHA:</th>
	<td><input type="password" name="password" id="password" value="" class="password"></td>
</tr>
</table>
<p><input type="submit" name="btn_login" id="btn_login" class="botao" Value="Login">&nbsp;<input type="reset" name="btn_limpar" class="botao" Value="Limpar"></p>
<hr>
<p><a href="mudapass.php?e=1">Quero gerar uma nova senha</a><!--&nbsp;&nbsp;|&nbsp;&nbsp;<a href="mudapass.php?e=2">Esqueci minha senha</a>--></p>
</fieldset>
</form>
