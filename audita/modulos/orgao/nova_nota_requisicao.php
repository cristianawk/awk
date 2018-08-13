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
$requisicao = null;
$conn = connection::init();

$conn->query("SELECT * FROM tipo_motivo_cancelamento ORDER BY id_motivo");
$motivos = $conn->get_tupla();

$conn->query("SELECT * FROM questoes_notas ORDER BY id_questao");
$questoes = $conn->get_tupla();


//echo $_POST['id_empenho']." - ".$_POST['id_requisicao']."<br>";
$conn->query("SELECT a.id_requisicao, a.ds_requisicao, to_char(a.dt_requisicao, 'DD/MM/YYYY') AS dt_requisicao,
			b.id_empenho, b.ds_empenho, b.ds_cnpj_unidade_orcamentaria, c.id_fornecedor, c.nm_fornecedor, c.ds_cnpj,
			ds_email1, ds_email1
			FROM requisicoes AS a JOIN empenhos AS b ON (a.id_empenho=b.id_empenho AND a.id_unidade=b.id_unidade)
			JOIN fornecedores AS c ON (b.id_fornecedor=c.id_fornecedor)
			WHERE a.id_unidade = ".$_GET['id_unidade']."
			AND a.id_requisicao =".$_GET['id_requisicao']." AND a.id_empenho = ".$_GET['id_empenho']);
$requisicao = $conn->fetch_row();

$itens = null;
$sql = "SELECT d.id_item_contratado, e.nm_produto, a.qt_produto_requisicao, f.nm_unidade_medida,
        d.vl_item_contratado FROM items_requisicao AS a
        JOIN requisicoes AS b ON (a.id_requisicao=b.id_requisicao AND a.id_empenho=b.id_empenho)
		JOIN empenhos AS c ON (a.id_empenho=c.id_empenho AND b.id_unidade=c.id_unidade)
		JOIN items_contratados AS d ON (a.id_item_contratado = d.id_item_contratado AND a.id_empenho=d.id_empenho)
		JOIN produtos AS e USING (id_produto)
		JOIN tipo_unidade_medida AS f USING (id_unidade_medida)
		WHERE b.id_requisicao =".$_GET['id_requisicao']." AND c.id_empenho = ".$_GET['id_empenho'];
//echo $sql; //exit;
$conn->query($sql);
$itens = $conn->get_tupla();
//echo "<pre>"; print_r($itens); echo "</pre>"; exit;
$qt_produtos = 0;

connection::close();
?>
  <link type="text/css" href="../../css/mtg/mytablegrid.css" rel="stylesheet">
  <link href="../../css/audita.css" rel="stylesheet" type="text/css" />
  <link href="../../css/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="../../js/prototype.js"></script>
  <script type="text/javascript" src="../../js/scriptaculous.js"></script>
  <script type="text/javascript" src="../../js/mtg/mytablegrid.js"></script>
  <script type="text/javascript" src="dhtmlgoodies_calendar.js"></script>
  <script type="text/javascript" src="../../js/audita.js"></script>
<form target="_self" enctype="multipart/form-data" method="post" name="frm_nota" id="frm_nota" onsubmit="fechar_janela()" onreset="" action="">
  <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_GET['usuario']?>">
  <input type="hidden" name="nm_usuario" id="nm_usuario" value="<?php echo $_GET['nm_usuario']?>">
  <input type="hidden" name="nm_unidade" id="nm_unidade" value="<?php echo $_GET['nm_unidade']?>">
  <input type="hidden" name="dados_requisicao" id="dados_requisicao" value='<?php echo formata::encodeJSON($requisicao)?>'>
  <input type="hidden" name="dados_item" id="dados_item" value='<?php echo formata::encodeJSON($itens)?>'>
  <input type="hidden" name="dados_questao" id="dados_questao" value='<?php echo formata::encodeJSON($questoes)?>'>
<table border="0" width="100%" class="orgTableJanela">
<tr>
	<td>CNPJ:</td>
	<td><?php echo $requisicao['ds_cnpj']?></td>
</tr>
<tr>
	<td>CNPJ UNID. OR&Ccedil;.:</td>
	<td><?php echo $requisicao['ds_cnpj_unidade_orcamentaria']?></td>
</tr>
<tr>
	<td>FORNECEDOR:</td>
	<td><?php echo $requisicao['nm_fornecedor']?></td>
</tr>
<tr>
	<td width="20%">REQUISI&Ccedil;&Atilde;O:</td>
	<td><?php echo $requisicao['ds_requisicao']?></td>
</tr>
<tr>
	<td width="20%">DATA REQUISI&Ccedil;&Atilde;O:</td>
	<td><?php echo $requisicao['dt_requisicao']?></td>
</tr>
<tr>
	<td>N&deg; NOTA FISCAL:</td>
	<td><input type="text" name="nr_notafiscal" id="nr_notafiscal" value="" onkeyup="validanum(this, 0)" class="required"/></td>
</tr>
<tr>
	<td>DATA DA NOTA FISCAL:</td>
	<td><input type="text" name="dt_notafiscal" id="dt_notafiscal" value="" size="10" maxlength="10"> <a name="btn" onclick="javascript:displayCalendar($('dt_notafiscal'),'dd/mm/yyyy',this);"><img src="../../imagens/iconCalendar.gif" align="top"></img></a></td>
</tr>
</table>

<table border="0" width="100%" class="orgTable">
<tr class="cab">
    <th width="6%">Item</th>
    <th width="30%">Produto</th>
    <th width="6%">Qt</th>
    <th>Unidade</th>
    <th width="12%">Valor Unit&aacute;rio</th>
    <th width="10%">Qt Recebida</th>
    <th width="10%">Valor</th>
    <th width="6%">Motivo</th>
</tr>
<?php foreach($itens AS $item){ ?>
<tr class="lin">
    <td class="cen"><?php echo $item['id_item_contratado']?></td>
    <td><?php echo $item['nm_produto']?></td>
    <td class="cen"><?php echo $item['qt_produto_requisicao']?></td>
    <td><?php echo $item['nm_unidade_medida']?></td>
    <td class="cen"><?php echo str_replace(".", ",", $item['vl_item_contratado'])?></td>
    <td>
<input type="text" onblur="comparaQt(this,'<?php echo $item['qt_produto_requisicao']?>','<?php echo $item['id_item_contratado']?>')" name="qt_produto_recebido_<?php echo $item['id_item_contratado']?>" id="qt_produto_recebido_<?php echo $item['id_item_contratado']?>" value="0" onfocus="this.value=''" size="10%" onkeyup="validanum(this, 0), mudaValor('<?php echo $item['id_item_contratado']?>', '<?php echo $item['vl_item_contratado']?>')">
</td>
    <td>
<input type="text" name="vl_produto_recebido_<?php echo $item['id_item_contratado']?>" id="vl_produto_recebido_<?php echo $item['id_item_contratado']?>" value="0,00" size="10%" readonly="true">
</td>
    <td class="cen">
        <select  name="id_motivo_<?php echo $item['id_item_contratado']?>" id="id_motivo_<?php echo $item['id_item_contratado']?>">
        <option value="">----</option>
        <?php foreach($motivos AS $motivo){ ?>
        <option value="<?php echo $motivo['id_motivo']?>"><?php echo $motivo['ds_motivo']?></option>
        <?php } ?>
        </select>
    </td>
</tr>
<?php
	//Somando a quantidade de produtos da requisicao
	//$qt_produtos += $item['qt_produto_requisicao'];
} 
?>
<tr class="cab">
    <td colspan="8">VALOR TOTAL:&nbsp;&nbsp;&nbsp;
<input type="text" name="vl_notafiscal" id="vl_notafiscal" value="0,00" readonly="true"></td>
</tr>
</table>
<hr>
<table border="0" align="center" id="tabperfil">
<tr><th colspan="4">Quest&otilde;es Sobre a Nota Fiscal</th></tr>
<?php $t = 0; foreach($questoes AS $questao){
    if($t==0){
        echo "<tr><th width='3%'><input onchange='check(this);' type='checkbox' name='id_questao' id='id_questao' value='".$questao['id_questao']."'></th><td id='left'>&nbsp;&nbsp;".$questao['ds_questao']."</td>";
        $t++;
    } else {
        echo "<th width='3%'><input onchange='check(this);' type='checkbox' name='id_questao' id='id_questao' value='".$questao['id_questao']."'></th><td id='left'>&nbsp;&nbsp;".$questao['ds_questao']."</td></tr>\n";
        $t = 0;
    }
 } ?>
</table>
<table border="0" width="100%" class="orgTable">
<tr>
    <td valign="top" width="25%">Outras exig&ecirc;ncias constatadas:</td>
    <td><textarea name="ds_pendencias" id="ds_pendencias"></textarea></td>
</tr>
</table>
<hr>
<p align="center">
    <input type="button" name="btn_aceita" id="btn_aceita" class="botao"  Value="Aceitar" onclick="enviar_arq22('gerar_aceite_auditoria.php')">&nbsp;
    <input type="button" name="btn_print" id="btn_print" class="botao" Value="Termo de Devolu&ccedil;&atilde;o" onclick="enviar_arq('gerar_nota_devolucao.php')">&nbsp;
    <input type="button" name="btn_fechar" class="botao" Value="Fechar" onclick="javascript:fechar_janela()"></p>
</form>
<?php //Se a quantidade entregue for igual à quantidade requisitada, o campo MOTIVO deve ficar bloqueado.?>
<script type="text/javascript">
function fechar_janela(){
   parent.globalWin.hide();
}

function enviar_arq22(){
	alert('Botao Aceita Funcionando');
}

function check(q){
	if (q.checked == true){alert('checado');
		$('btn_aceita').disabled = true;
	}else{alert('nao checado');
		$('btn_aceita').disabled = false;
	}
}

function comparaQt(qt,req,motivo){
	if(qt.value == req){
		$('id_motivo_'+motivo).disabled = true;
	}else{
		$('id_motivo_'+motivo).disabled = false;
	}
}

function enviar_arq(arq){

	var erro_env = "";

	if($('nr_notafiscal').value == "" ){erro_env += "O numero da nota fiscal deve ser preenchido.\r\n";}
	if($('dt_notafiscal').value == ""){erro_env += "A data da nota fiscal deve ser preenchida.\r\n";}
 
    else{
		if(comparaDataMaior($('dt_notafiscal').value, '<?php echo $requisicao['dt_requisicao']?>') == 'MENOR'){
			erro_env += "A data da nota fiscal é menor que a data da Requisi&ccedil;&atilde;o.\r\n";
		}
		
		<?php foreach($itens AS $item){ ?>
			if(<?php echo $item['qt_produto_requisicao']?> < $('qt_produto_recebido_<?php echo $item['id_item_contratado']?>').value){
				erro_env += "A quantidade recebida n&atilde;o pode ser maior que a quantidade requisitada para o item <?php echo $item['id_item_contratado']?>.\r\n";
			}
			if((<?php echo $item['qt_produto_requisicao']?> > $('qt_produto_recebido_<?php echo $item['id_item_contratado']?>').value) && ($('id_motivo_<?php echo $item['id_item_contratado']?>').value == "")){
				erro_env += "O motivo deve ser preenchido quando a quantidade recebida for menor que a quantidade requisitada para o item <?php echo $item['id_item_contratado']?>.\r\n";
			}
		<?php } ?>
	}
    if(erro_env == ""){
	$('frm_nota').action = arq;
        $('frm_nota').submit();
        //fechar_janela();
    }else{
		alert("ERRO!!!!\n\n" + erro_env);
	}
}

function mudaValor(item, valor){
    //alert(item + " " + valor);
    var num = $('qt_produto_recebido_'+item).value;
    var res = valor * num;
    $('vl_produto_recebido_'+item).value = res.toFixed(2).replace(".", ",");
//alert($('vl_produto_recebido_'+item).value);
    // Muda o Valor Total
    mudaValorTotal();
}

function mudaValorTotal(){

    var total = 0.00;
<?php foreach($itens AS $item){ ?>
    total += parseFloat($('vl_produto_recebido_<?php echo $item['id_item_contratado']?>').value);
<?php } ?>
    //Fixa o numero decimal em 2
    $('vl_notafiscal').value = total.toFixed(2).replace(".", ",");
}



</script>
