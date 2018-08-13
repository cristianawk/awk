<?php

$empenhos = null;
$requisicao = null;
$items = null;
$sessao_unidade = '1';

$conn->query("SELECT DISTINCT a.id_fornecedor, a.nm_fornecedor, a.ds_cnpj
				FROM fornecedores AS a JOIN empenhos AS b ON (a.id_fornecedor=b.id_fornecedor)
				WHERE b.id_unidade = {$sessao_unidade}
			");
//echo "sql: ".$sql;
$fornecedores = $conn->get_tupla();
//echo "<pre>"; print_r($fornecedores); echo "</pre>"; exit;

if($_POST){//echo "entra aqui!";


		//echo "<pre>"; print_r($requisicao); echo "</pre>"; exit;

		/*$conn->query("SELECT a.id_item_requisicao, a.id_requisicao, a.qt_produto_requisicao, a.id_item_contratado, a.id_empenho,
						b.ds_requisicao, c.ds_empenho, d.vl_item_contratado, e.nm_produto, f.nm_unidade_medida
						FROM items_requisicao AS a
						JOIN requisicoes AS b ON (a.id_requisicao=b.id_requisicao AND a.id_empenho=b.id_empenho)
						JOIN empenhos AS c ON (a.id_empenho=c.id_empenho AND b.id_unidade=c.id_unidade)
						JOIN itens_contratados AS d ON (a.id_item_contratado = d.id_item_contratado AND a.id_empenho=d.id_empenho)
						JOIN produtos AS e USING (id_produto)
						JOIN tipo_unidade_medida AS f USING (id_unidade_medida)
						WHERE b.id_unidade = {$sessao_unidade}
						AND a.id_requisicao =".$_POST['id_requisicao']." AND a.id_empenho = ".$_POST['id_empenho']);

		$items = $conn->get_tupla();
		*/
		//echo "<pre>"; print_r($items); echo "</pre>"; exit;

	}


?>
<form target="_self" enctype="multipart/form-data" method="post" name="frm_nota" id="frm_nota" onsubmit="" onreset="Limpar()"  action="./index.php?acesso=<?=$_GET['acesso']?>&rotina=<?=$_GET['rotina']?>">
<table border="0" width="100%" align="center" class="orgTable">
<tr><th colspan="2"><?php echo $modulo_arquivo['nm_rotina']?></th></tr>
<tr>
	<td>Nome do Fornecedor</td>
	<td>
		<select id="id_fornecedor" name="id_fornecedor" class="validate-selection">
		<option value="">-------------------------------------------------------------------</option>
		<?php foreach($fornecedores AS $fornecedor){ ?>
		<option value="<?php echo $fornecedor['id_fornecedor']?>"><?php echo $fornecedor['ds_cnpj']?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $fornecedor['nm_fornecedor']?></option>
		<?php } ?>
		</select>
	</td>
</tr>
</table>
<hr>
<p><input type="button" name="btn_incluir" id="btn_incluir" class="botao" Value="Pesquisar" onclick="loadNotaReq()">&nbsp;<input type="reset" name="btn_limpar" class="botao" Value="Limpar"></p>
<div id="lista_nota">&nbsp;</div>
<script type="text/javascript">
function loadNotaReq(){

    if($('id_fornecedor').value != ""){
	new Ajax.Request('./modulos/orgao/lista_nota_requisicao.php', {
			method: 'post',
			parameters: "id_fornecedor="+$('id_fornecedor').value+"&id_unidade=<?php echo $sessao_unidade?>",
			onLoading : $('lista_nota').innerHTML =  "<p align='center'><img src='./imagens/loader2.gif' width='21' height='21'/></p>",
			onComplete: function(transport){
				//alert(transport.responseText); exit;
				$('lista_nota').innerHTML = transport.responseText;
			}
		});
    }
}

function novaNota(id_requisicao, id_empenho){
    //alert(globalWin);
    if(globalWin != ""){globalWin.destroy(); }
		globalWin = new Window("req", {
        className: "alphacube",
        width:largura,
        height:altura,
        title:"Nota Requisi&ccedil;&atilde;o",
        url: "./modulos/orgao/nova_nota_requisicao.php?id_requisicao="+id_requisicao+"&id_empenho="+id_empenho+"&id_unidade=<?php echo $sessao_unidade?>&usuario=<?php echo $sessao_usuario?>&nm_usuario=<?php echo $sessao_nome_usuario?>&nm_unidade=<?php echo $sessao_nome_unidade?>",
        showEffectOptions: {duration:0.1},
        //destroyOnClose: true,
        minimizable: false
    });
    //globalWin.setDestroyOnClose();
    globalWin.showCenter(true);

}

function Limpar(){
    $('lista_nota').innerHTML = "&nbsp;";
}
</script>
