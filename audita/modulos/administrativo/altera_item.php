<?php
$conn->query("SELECT id_empenho, ds_empenho FROM empenhos ORDER BY ds_empenho");
$empenhos = $conn->get_tupla();
?>
<form target="_self" enctype="multipart/form-data" method="post" name="frm_item" id="frm_item" action="index.php?acesso=3&rotina=15">
<table border="0" width="100%" align="center">
<tr><th colspan="2"><?php echo $modulo_arquivo['nm_rotina']?></th></tr>
<tr>
	<td width="20%">Numero Empenho</td>
	<td>
		<select name="id_empenho" id="id_empenho" value="" />
		<option value="">-------------------------</option>
		<?php foreach($empenhos AS $empenho){ ?>
		<option value="<?php echo $empenho['id_empenho']?>"><?php echo $empenho['ds_empenho']?></option>
		<?php } ?>
		</select>
	</td>
</tr>
</table>
<br>
<hr>
<p><input type="submit" name="btn_button" id="btn_button" class="botao" Value="Buscar">&nbsp;<input type="reset" name="btn_limpar" class="botao" Value="Limpar"></p>
</form>
