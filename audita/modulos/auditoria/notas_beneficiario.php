<?php

$conn->query("SELECT id_fornecedor, nm_fornecedor FROM vw_fornecedores ORDER BY nm_fornecedor");
$fornecedores = $conn->get_tupla();

?>
<form target="_self" enctype="multipart/form-data" method="post" name="frm_nota" id="frm_nota" onsubmit="" onreset="Limpar()" action="./index.php?acesso=<?php echo $_GET['acesso']?>&rotina=<?php echo $_GET['rotina']?>">
<input type="hidden" name="id_unidade" id="id_unidade" value="<?php echo $sessao_unidade?>">
<table border="0" width="100%" align="center" class="orgTable">
<tr><th colspan="2"><?php echo $modulo_arquivo['nm_rotina']?></th></tr>
<tr><th colspan="2">&nbsp;</th></tr>
<tr>
	<td>Nome do Fornecedor</td>
	<td>
		<select id="id_fornecedor" name="id_fornecedor" class="validate-selection" onchange="loadNumeroNota()">
		<option value="">-------------------------------------------------------</option>
		<?php foreach($fornecedores AS $fornecedor){ ?>
		<option value="<?php echo $fornecedor['id_fornecedor']?>"><?php echo $fornecedor['nm_fornecedor']?></option>
		<?php } ?>
		</select>
	</td>
</tr>
<tr>
	<td width="25%">Nota Fiscal</td>
    <td align="left">
		<select id="id_notafiscal" name="id_notafiscal">
		<option value="">---------------</option>
		</select>
	</td>
</tr>
</table>
<hr>
<p><input type="button" name="btn_incluir" id="btn_incluir" class="botao" Value="Pesquisar" onclick="loadNotaBeneficiado()">&nbsp;<input type="reset" name="btn_limpar" class="botao" Value="Limpar"></p>
<div id="lista_notas">&nbsp;</div>
</form>
<script type="text/javascript">

document.observe("dom:loaded", loadNumeroNota());

/**
 * Funcao que carrega a lista de requisicoes
 */
function loadNotaBeneficiado(){

	if($('id_notafiscal').value != ""){
		new Ajax.Request('./modulos/auditoria/lista_notas_beneficiado.php', {
			method: 'post',
			parameters: { 'id_fornecedor' : $('id_fornecedor').value, 'id_notafiscal' : $('id_notafiscal').value },
			onLoading : $('lista_notas').innerHTML =  "<p align='center'><img src='./imagens/loader2.gif' width='21' height='21'/></p>",
			onComplete: function(transport){
				//alert(transport.responseText); exit;
				$('lista_notas').innerHTML = transport.responseText;
			}
		});
	}else{
		alert("Selecione um numero de nota fiscal.");
	}
}

/**
 * Funcao que carrega as notas fiscais enviadas do beneficiario
 */
function loadNumeroNota(){

	new Ajax.Request('./modulos/auditoria/lista_numero_notas.php', {
			method: 'post',
			parameters: { 'id_fornecedor' : $('id_fornecedor').value, 'id_notafiscal' : $('id_notafiscal').value },
			onComplete: function(transport){
				//alert(transport.responseText); exit;
				$('id_notafiscal').innerHTML = transport.responseText;
			}
		});
}

/**
 * Funcao que carrega a janela para o recebimento da nota
 */
function loadNotaRecebida(requisicao, empenho, nota){

    //alert(requisicao + " " + empenho);
    if(globalWin != ""){ globalWin.destroy(); }

		globalWin = new Window("req", {
        className: "alphacube",
        width:largura,
        height:altura,
        title:"Nota Recebida pelo Beneficiário",
        url: "./modulos/auditoria/nova_nota_recebida.php?id_requisicao="+requisicao+"&id_empenho="+empenho+"&nr_notafiscal="+nota,
        showEffectOptions: {duration:0.2},
        //destroyOnClose: true,
        minimizable: false
    });
    //globalWin.setDestroyOnClose();
    globalWin.showCenter(true);

}

function Limpar(){
    $('lista_notas').innerHTML = "&nbsp;";
}

</script>
