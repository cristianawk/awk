<?php

$conn->query("SELECT * FROM produtos ORDER BY nm_produto");
$produtos = $conn->get_tupla();

$conn->query("SELECT * FROM tipo_unidade_medida ORDER BY nm_unidade_medida");
$tipo_unidades_medidas = $conn->get_tupla();
   
?>


<form target="_self" enctype="multipart/form-data" method="post" name="frm_itens_empenho" id="frm_itens_empenho" onsubmit="submitForm(); return false;" onreset="Limpar(); return true;" action="./modulos/administrativo/inc_items.php">
<input type="hidden" name="hdn_acao" id="hdn_acao" value="inc">
<input type="hidden" name="id_empenho" id="id_empenho" value="">
<table border="0" width="100%" align="center" class="orgTable">
<tr><th colspan="2"><?php echo $modulo_arquivo['nm_rotina']?></th></tr>
<tr>
	<td width="20%">Produto/Serviço</td>
	<td>
		<select id="id_produto" name="id_produto" class="required">
		<option value="">-------------------------------------------------</option>
		<?php foreach($produtos AS $produto){ ?>
		<option value="<?php echo $produto['id_produto']?>"><?php echo $produto['nm_produto']?></option>
		<?php } ?>
		</select>
	</td>
</tr>
<tr>
	<td>Tipo Unidade Medida</td>
	<td>
		<select id="id_unidade_medida" name="id_unidade_medida" class="required">
		<option value="">-------------------------------------------------</option>
		<?php foreach($tipo_unidades_medidas AS $tipo_unidade_medida){ ?>
		<option value="<?php echo $tipo_unidade_medida['id_unidade_medida']?>"><?php echo $tipo_unidade_medida['nm_unidade_medida']?></option>
		<?php } ?>
		</select>
	</td>
</tr>
<tr>
	<td>Quantidade Contratada</td>
	<td><input type="text" name="qt_item_contratado" id="qt_item_contratado" value="" size="9" maxlength="9" class="required"></td>
</tr>
<tr>
	<td>Valor Unitário Produto/Serviço</td>
	<td><input type="text" name="vl_item_contratado" id="vl_item_contratado" value="" size="40" class="required" /></td>
</tr>
<tr>
	<td>Quantidade Requerido</td>
	<td><input type="text" name="qt_produto" id="qt_produto" value="" size="35"  class="required"/></td>
</tr>

</table>

<br>
<p>Os campos sinalizados com <img src='./imagens/obriga.gif' /> são de preenchimento obrigatório</p>
<hr>
<p><input type="submit" name="btn_incluir" id="btn_incluir" class="botao" Value="Cadastrar">&nbsp;<input type="reset" name="btn_limpar" class="botao" Value="Limpar"></p>

</form>

<script type="text/javascript">

	/**
	 * Limpa o formulario
	 */
	function Limpar(){
		$('frm_itens_empenho').reset();
		frm_empenho.reset();
		$('id_empenho').value = "";
		$('ch_bloqueio').value = "";
		$('hdn_acao').value = 'inc';
		$('btn_incluir').value = 'Cadastrar';
    	}

</script>
