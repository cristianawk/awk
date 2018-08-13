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
//echo "<pre>"; print_r($_GET); echo "</pre>"; exit;
$requisicao = null;
$conn = connection::init();

$conn->query("SELECT * FROM questoes_notas ORDER BY id_questao");
$questoes = $conn->get_tupla();


//echo $_POST['id_empenho']." - ".$_POST['id_requisicao']."<br>";
$conn->query("SELECT a.id_requisicao, a.ds_requisicao, to_char(a.dt_requisicao, 'DD/MM/YYYY') AS dt_requisicao,
			b.id_empenho, b.ds_empenho, b.ds_cnpj_unidade_orcamentaria, c.id_fornecedor, c.nm_fornecedor, c.ds_cnpj, c.ds_email1, c.ds_email2,
			d.id_notafiscal, d.nr_notafiscal, to_char(d.dt_notafiscal, 'DD/MM/YYYY') AS dt_notafiscal, d.vl_notafiscal, e.nm_unidade
			FROM requisicoes AS a JOIN empenhos AS b ON (a.id_empenho=b.id_empenho AND a.id_unidade=b.id_unidade)
			JOIN fornecedores AS c ON (b.id_fornecedor=c.id_fornecedor)
			JOIN notas_fiscais AS d ON (d.id_requisicao=a.id_requisicao AND d.id_fornecedor=b.id_fornecedor)
			JOIN unidades_beneficiadas AS e ON (a.id_unidade=e.id_unidade)
			WHERE a.id_requisicao =".$_GET['id_requisicao']." AND a.id_empenho = ".$_GET['id_empenho']);
$requisicao = $conn->fetch_row();

$itens = null;
$sql = "SELECT DISTINCT d.id_item_contratado, e.nm_produto, a.qt_produto_requisicao, f.nm_unidade_medida,
		d.vl_item_contratado, h.qt_produto_recebido, h.vl_produto_recebido
		FROM items_requisicao AS a
		JOIN requisicoes AS b ON (a.id_requisicao=b.id_requisicao AND a.id_empenho=b.id_empenho)
		JOIN empenhos AS c ON (a.id_empenho=c.id_empenho AND b.id_unidade=c.id_unidade)
		JOIN items_contratados AS d ON (a.id_item_contratado = d.id_item_contratado AND a.id_empenho=d.id_empenho)
		JOIN produtos AS e USING (id_produto)
		JOIN tipo_unidade_medida AS f USING (id_unidade_medida)
		JOIN notas_fiscais AS g ON (g.id_requisicao=b.id_requisicao)
		JOIN items_recebidos AS h ON (g.id_requisicao = h.id_requisicao AND h.id_notafiscal = g.id_notafiscal AND h.id_item_contratado = d.id_item_contratado)
		WHERE b.id_requisicao =".$_GET['id_requisicao']." AND c.id_empenho = ".$_GET['id_empenho'];
//echo $sql; exit;
$conn->query($sql);
$itens = $conn->get_tupla();
//echo "<pre>"; print_r($itens); echo "</pre>"; exit;


connection::close();
?>
<link href="../../css/audita.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/prototype.js"></script>
<form target="_self" enctype="multipart/form-data" method="post" name="frm_nota" id="frm_nota" onsubmit="fechar_janela()" onreset="" action="">
<input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_GET['id_usuario']?>">
<input type="hidden" name="nm_usuario" id="nm_usuario" value="<?php echo $_GET['nm_usuario']?>">
<input type="hidden" name="nm_unidade" id="nm_unidade" value="<?php echo $_GET['nm_unidade']?>">
<input type="hidden" name="id_requisicao" id="id_requisicao" value='<?php echo $requisicao['id_requisicao']?>'>
<input type="hidden" name="id_notafiscal" id="id_notafiscal" value='<?php echo $requisicao['id_notafiscal']?>'>
<input type="hidden" name="dados_requisicao" id="dados_requisicao" value='<?php echo formata::encodeJSON($requisicao)?>'>
<input type="hidden" name="dados_item" id="dados_item" value='<?php echo formata::encodeJSON($itens)?>'>
<input type="hidden" name="dados_questao" id="dados_questao" value='<?php echo formata::encodeJSON($questoes)?>'>
<table border="0" width="100%" class="orgTableJanela">
<tr>
	<td>BENEFICIÁRIO:</td>
	<td><?php echo $requisicao['nm_unidade']?></td>
</tr>
<tr>
	<td>CNPJ:</td>
	<td><?php echo $requisicao['ds_cnpj']?></td>
</tr>
<tr>
	<td>CNPJ UNID. ORÇ.:</td>
	<td><?php echo $requisicao['ds_cnpj_unidade_orcamentaria']?></td>
</tr>
<tr>
	<td>FORNECEDOR:</td>
	<td><?php echo $requisicao['nm_fornecedor']?></td>
</tr>
<tr>
	<td width="20%">REQUISIÇÃO:</td>
	<td><?php echo $requisicao['ds_requisicao']?></td>
</tr>
<tr>
	<td width="20%">DATA REQUISIÇÃO:</td>
	<td><?php echo $requisicao['dt_requisicao']?></td>
</tr>
<tr>
	<td>Nº NOTA FISCAL:</td>
	<td><?php echo $requisicao['nr_notafiscal']?></td>
</tr>
<tr>
	<td>DATA DA NOTA FISCAL:</td>
	<td><?php echo $requisicao['dt_notafiscal']?></td>
</tr>
</table>
<br>
<table border="0" width="100%" class="orgTable">
<tr class="cab">
    <th width="6%">Item</th>
    <th width="30%">Produto</th>
    <th width="6%">Qt</th>
    <th width="10%">Qt Recebida</th>
    <th>Unidade</th>
    <th width="12%">Valor Unitário</th>
    <th width="10%">Valor</th>
</tr>
<?php foreach($itens AS $item){ ?>
<tr class="lin">
    <td class="cen"><?php echo $item['id_item_contratado']?></td>
    <td><?php echo $item['nm_produto']?></td>
    <td class="cen"><?php echo $item['qt_produto_requisicao']?></td>
    <td class="cen"><?php echo $item['qt_produto_recebido']?></td>
    <td><?php echo $item['nm_unidade_medida']?></td>
    <td class="cen"><?php echo str_replace(".", ",", $item['vl_item_contratado'])?></td>
    <td class="cen"><?php echo str_replace(".", ",", $item['vl_produto_recebido'])?></td>
</tr>
<?php } ?>
<tr class="cab">
    <td colspan="8">VALOR TOTAL:&nbsp;&nbsp;&nbsp;<?php echo str_replace(".", ",", $requisicao['vl_notafiscal'])?></td>
</tr>
</table>
<hr>
<table border="0" align="center" id="tabperfil">
<tr><th colspan="4">Questões Sobre a Nota Fiscal</th></tr>
<?php $t = 0; foreach($questoes AS $questao){
    if($t==0){
        echo "<tr><th width='3%'><input type='checkbox' name='id_questao[]' value='".$questao['id_questao']."'></th><td id='left'>&nbsp;&nbsp;".$questao['ds_questao']."</td>";
        $t++;
    } else {
        echo "<th width='3%'><input type='checkbox' name='id_questao[]' value='".$questao['id_questao']."'></th><td id='left'>&nbsp;&nbsp;".$questao['ds_questao']."</td></tr>\n";
        $t = 0;
    }
 } ?>
</table>
<table border="0" width="100%" class="orgTable">
<tr>
    <td valign="top" width="25%">Outras exigências constatadas:</td>
    <td><textarea name="ds_pendencias" id="ds_pendencias"></textarea></td>
</tr>
</table>
<hr>
<p align="center">
    <input type="button" name="btn_print" id="btn_print" class="botao" Value="Termo de Análise" onclick="enviar_arq('gerar_analise_auditoria.php')">&nbsp;
    <input type="button" name="btn_print" id="btn_print" class="botao" Value="Termo de Devolução" onclick="enviar_arq('gerar_analise_devolucao.php')">&nbsp;
    <input type="button" name="btn_fechar" class="botao" Value="Fechar" onclick="javascript:fechar_janela()"></p>
</form>
<script type="text/javascript">
function fechar_janela(){
   parent.globalWin.hide();
}

function enviar_arq(arq){

	$('frm_nota').action = arq;
    $('frm_nota').submit();

}
</script>
