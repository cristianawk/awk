<?php
/*
 * carrega a classe a ser instanciada quando chamada
 */
function __autoload($classe){
	$pontos = array("./", "../", "../../");
    foreach($pontos AS $ponto){
		if(file_exists("{$ponto}class/{$classe}.class.php")){
			//echo "{$ponto}class/{$classe}.class.php\n";
			include_once "{$ponto}class/{$classe}.class.php";
		}
	}
}
//echo "<pre>"; print_r($_GET); echo "</pre>"; //exit;
//echo "<pre>"; print_r($_POST); echo "</pre>";

$dado = null;
$dados_requisicao = null;

$conn = connection::init();

$sql = "SELECT nm_produto, nm_unidade_medida, SUM(qt_produto_requisicao) AS qt_produto, a.id_item_contratado
        FROM items_requisicao AS A
		JOIN items_contratados AS b ON (a.id_item_contratado=b.id_item_contratado AND a.id_empenho=b.id_empenho)
		JOIN produtos USING (id_produto)
		JOIN tipo_unidade_medida USING (id_unidade_medida)
		WHERE id_requisicao = ".$_GET['id_requisicao']."
		GROUP BY nm_produto, nm_unidade_medida, a.id_item_contratado";
//echo $sql; exit;
$conn->query($sql);
$dados_requisicao = $conn->get_tupla();
//echo "<pre>"; print_r($dados_requisicao); echo "</pre>"; //exit;

$sql = "SELECT a.id_requisicao, a.ds_requisicao,
		to_char(a.dt_requisicao, 'DD/MM/YYYY') AS dt_requisicao, c.nm_fornecedor, c.id_fornecedor,
		b.ds_empenho, b.id_empenho, b.ds_cnpj_unidade_orcamentaria ,c.ds_cnpj, to_char(b.dt_empenho , 'DD/MM/YYYY') AS dt_empenho,
		SUM(d.qt_produto_requisicao) AS qt_produto, ds_email1, ds_email2
		FROM requisicoes AS a
		JOIN empenhos AS b ON (a.id_empenho=b.id_empenho AND a.id_unidade=b.id_unidade)
		JOIN fornecedores AS c ON (c.id_fornecedor=b.id_fornecedor)
		JOIN items_requisicao AS d ON (a.id_requisicao=d.id_requisicao AND a.id_empenho=d.id_empenho)
		WHERE a.id_requisicao = ".$_GET['id_requisicao']."
		GROUP BY a.id_requisicao, a.ds_requisicao, a.dt_requisicao, c.nm_fornecedor , c.id_fornecedor,
        b.ds_empenho, b.id_empenho, b.ds_cnpj_unidade_orcamentaria, c.ds_cnpj, b.dt_empenho, ds_email1, ds_email2
		ORDER BY nm_fornecedor, id_requisicao";
//echo $sql; exit;
$conn->query($sql);
$dado = $conn->fetch_row();

connection::close();
?>
<link type="text/css" href="../../css/mtg/mytablegrid.css" rel="stylesheet">
<link href="../../css/audita.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/mtg/mytablegrid.js"></script>
<form target="_self" enctype="multipart/form-data" method="post" name="frm_requisicao" id="frm_requisicao" onsubmit="" action="./gerar_ordem_requisicao.php">

<input type="hidden" name="nm_usuario" id="nm_usuario" value="<?=$_GET['nm_usuario']?>">
<input type="hidden" name="nm_unidade" id="nm_unidade" value="<?=$_GET['nm_unidade']?>">

<input type="hidden" name="ds_cnpj_unidade_orcamentaria" id="ds_cnpj_unidade_orcamentaria" value="<?=$dado['ds_cnpj_unidade_orcamentaria']?>">
<input type="hidden" name="dados_empenho" id="dados_empenho" value='<?=formata::encodeJSON($dado)?>'>
<input type="hidden" name="dados_requisicao" id="dados_requisicao" value='<?=formata::encodeJSON($dados_requisicao)?>'>
<input type="hidden" name="hdn_acao" id="hdn_acao" value="">
<table border="0" width="100%" class="orgTableJanela">
<tr>
	<th colspan="2">ORDEM DA REQUISI&Ccedil;&Atilde;O <?php echo $dado['ds_requisicao']?></th>
</tr>
<tr>
	<td width="20%">FORNECEDOR:</td>
	<td><?php echo $dado['nm_fornecedor']?></td>
</tr>
<tr>
	<td width="20%">CNPJ:</td>
	<td><?php echo $dado['ds_cnpj']?></td>
</tr>
<tr>
	<td width="20%">NUMERO EMPENHO:</td>
	<td><?php echo $dado['ds_empenho']?></td>
</tr>
<tr>
	<td width="20%">DATA DO EMPENHO:</td>
	<td><?php echo $dado['dt_empenho']?></td>
</tr>
</table>
<table border="0" width="100%" align="center" class="orgTable">
<tr class="cab">
	<th>Item</th>
	<th>Produto</th>
	<th>Quantidade</th>
	<th>Unidade</th>
</tr>
<?php foreach($dados_requisicao AS $requisicao){ ?>
	<tr class="lin">
		<td class="cen"><?php echo $requisicao['id_item_contratado']?></td>
		<td class="lef"><?php echo $requisicao['nm_produto']?></td>
		<td class="cen"><?php echo $requisicao['qt_produto']?></td>
		<td class="lef"><?php echo $requisicao['nm_unidade_medida']?></td>
	</tr>
<?php } ?>
</table>
<hr>
<p align="center">
    <input type="button" name="btn_print" id="btn_print" class="botao" Value="Enviar / Gerar PDF" onclick="aviso()">
    <!--
	<input type="button" name="btn_print" id="btn_print" class="botao" Value="Enviar / Gerar PDF" onclick="enviar(1)">
	&nbsp;
	<input type="button" name="btn_rela" id="btn_rela" class="botao" Value="Reenviar / Sem PDF" onclick="enviar(2)">-->
	&nbsp;
	<input type="button"  name="btn_fechar" class="botao" Value="Fechar" onclick="javascript:fechar_janela()" ></p>
</form>
<script type="text/javascript">
function fechar_janela(){
   parent.globalWin.hide();
}
function aviso() {
    $('btn_print').value = 'Função Desabilitada!';
}
function enviar(n){
	$('hdn_acao').value = n;
	$('frm_requisicao').submit();
}

</script>
