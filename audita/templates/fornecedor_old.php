<?php
?>
<form target="_self" enctype="multipart/form-data" method="post" name="frm_fornecedor" id="frm_fornecedor" onsubmit="" onreset="" action="./index.php?acesso=<?php echo $_GET['acesso']?>">
<fieldset class="login">
<br>
<table>
<tr>
	<th>CNPJ:</th>
	<td><input type="text" name="fornecededor_cnpj" id="fornecededor_cnpj" value="" onblur="cpfcnpj(this)">&nbsp;&nbsp;(Somente Numeros)</td>
</tr>
<tr>
	<th>Nº EMPENHO:</th>
	<td><input type="text" name="fornecededor_empenho" id="fornecededor_empenho" value="" class="">&nbsp;&nbsp;(ex: <?php echo date("Y")?>NE000011)</td>
</tr>
</table>
<p><input type="submit" name="btn_login" id="btn_login" class="botao" Value="OK">&nbsp;<input type="reset" name="btn_limpar" class="botao" Value="Limpar"></p>
<br>
</fieldset>
</form>
