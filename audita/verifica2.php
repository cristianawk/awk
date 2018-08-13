<?php
/*
 * carrega a classe a ser instanciada quando chamada
 */ 

//echo "<pre>"; print_r($_POST); echo "</pre>";
//echo "<pre>"; print_r($_GET); echo "</pre>";

function __autoload($classe){
	$pontos = array("./", "../", "../../");
    foreach($pontos AS $ponto){
		if(file_exists("{$ponto}class/{$classe}.class.php")){
			//echo "{$ponto}class/{$classe}.class.php\n";
			include_once "{$ponto}class/{$classe}.class.php";
		}
	}
}
$msg = "";
$lnk = 0;
$id_fornecedor= $tupla['id_fornecedor'];

if($_GET['h'] != ""){
    //Classe de conexão com banco de dados
    $conn = connection::init();
    $sql = sprintf("SELECT id, fornecedor_email.id_fornecedor, nm_fornecedor, ds_empenho, ds_cnpj, ch_visto, ds_email FROM fornecedor_email LEFT JOIN fornecedores USING (id_fornecedor) WHERE ds_codigo = '%s'", preg_replace("/\W/", "", $_GET['h']));
    $conn->query($sql);
    $tupla = $conn->fetch_row();
    $rows = $conn->num_rows();
    //print_r($tupla);
    if($rows > 0){
        if($tupla['ch_visto'] == 'N'){
            $sql = "UPDATE fornecedor_email SET ts_visto = now(), ch_visto = 'S' WHERE id = ".$tupla['id']." AND id_fornecedor = ".$tupla['id_fornecedor'];
            //echo $sql;
            $conn->query($sql);
            $msg = "ESSE EMAIL FOI VALIDADO COM SUCESSO";
            $lnk = 1;
        }else{
            $msg = "ESSE EMAIL JÁ FOI VALIDADO";
            $lnk = 1;
        }
    }else{
        $msg = "LINK INVÁLIDO";
        $lnk = 0;
    } 


}else{
    $msg = "LINK INVÁLIDO";
    $lnk = 0;
}
/*
id	id_fornecedor	nm_fornecedor	ds_empenho	ds_cnpj			ch_visto	ds_email
218	2260		AWK TECNOL	2012NE001994	08.732.540/0001-71	S		ORDEM DA REQUISIÇÃ

id_requisicao	ds_requisicao	dt_requisicao	nr_notafiscal	ds_status_nota			nm_unidade
58		2012.5043	27/06/2012	1994		APROVADO PELO BENEFICIÁRIO	DITI
*/
$sql_pdf = "SELECT a.id_requisicao, ds_empenho, ds_requisicao, to_char(dt_requisicao, 'DD/MM/YYYY') AS dt_requisicao,
		b.nr_notafiscal,
		(CASE WHEN c.ds_status_nota IS NULL THEN 'AGUARDA NOTA  FISCAL DO FORNECEDOR OU REGISTRO DE FORNECIMENTO'
		ELSE c.ds_status_nota END) AS ds_status_nota,
		u.nm_unidade
		FROM requisicoes AS a LEFT JOIN notas_fiscais AS b ON (a.id_requisicao=b.id_requisicao)
		LEFT JOIN status_notas AS c USING (id_status_nota)
		JOIN empenhos AS e ON (a.id_empenho=e.id_empenho)
		JOIN unidades_beneficiadas AS u ON (u.id_unidade=a.id_unidade AND u.id_unidade=e.id_unidade)
		WHERE e.id_fornecedor = ".$tupla['id_fornecedor']." and ds_empenho = '".$tupla['ds_empenho']."' ORDER BY 3 DESC";
//echo "<br> sql_pdf: ".$sql_pdf;
//echo "<br> sql: ".$sql;
$conn->query($sql_pdf);
$tupula = $conn->fetch_row();
//while($tupula = $conn->fetch_row()) $dados[] = $tupula;
// echo "<br>id: ".$tupla['id_fornecedor'];
// echo "<br> requisicao: ".$dados_requisicao;
//echo "<br> id_requisicao: ".$tupula['id_requisicao'];
//echo "<br> id_requisicao: ".$tupula['ds_requisicao'];
//echo "<br> empenho: ".$tupula['ds_empenho'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CORPO DE BOMBEIROS MILITAR DE SANTA CATARINA - SISTEMA AUDITA COMPRAS</title>
<link href="css/audita.css" rel="stylesheet" type="text/css" />
</head>
<form target="_self" enctype="multipart/form-data" method="post" name="frm_requisicao" id="frm_requisicao" onsubmit="" action="./gerar_ordem_requisicao.php">
<input type="hidden" name="nm_usuario" id="nm_usuario" value="<?=$_GET['nm_usuario']?>">
<input type="hidden" name="nm_unidade" id="nm_unidade" value="<?=$_GET['nm_unidade']?>">

<input type="hidden" name="ds_cnpj_unidade_orcamentaria" id="ds_cnpj_unidade_orcamentaria" value="<?=$tupla['nm_fornecedor']?>">
<input type="hidden" name="dados_empenho" id="dados_empenho" value='<?=$tupla['ds_empenho']?>'>
<input type="hidden" name="dados_requisicao" id="dados_requisicao" value='<?=formata::encodeJSON($dados_requisicao)?>'>
<input type="hidden" name="hdn_acao" id="hdn_acao" value="">
<body>
	<table cellspacing="0" cellpadding="0">
		<tr>
			<td colspan="2" id="top">
				<div>
					<h1><a href="index.php">CORPO DE BOMBEIROS MILITAR DE SANTA CATARINA<br>SISTEMA AUDITA COMPRAS</a></h1>
				</div>
			</td>
		</tr>
        <tr><td colspan="2">&nbsp;</td></tr>
		<tr>
            <td colspan="2" width="95%" align="center">
            <fieldset>
                <label><b>VERIFICAÇÃO</b></label>
                <hr/>
                <table border="0" width="100%" align="center" class="orgTableJanela">
                    <tr class="erro"><th colspan="2"><?php echo $msg?></th></tr>
                    <?php  if($lnk == 1){ ?>
                    <tr><th width="20%">FORNECEDOR</th><td><?php echo $tupla['nm_fornecedor']?></td></tr>
                    <tr><th width="20%">CNPJ</th><td><?php echo $tupla['ds_cnpj']?></td></tr>
                    <tr><th width="20%">NUNERO EMPENHO</th><td><?php echo $tupla['ds_empenho']?></td></tr>
		  <tr><th width="20%">DESCRIÇÃO EMAIL</th><td><?php echo $tupla['ds_email']." - ".$tupula['id_requisicao'];?></td></tr>
                    <tr><th width="20%">ARQUIVO DE REQUISIÇÃO</th>
<!--<td><input type="button" name="btn_print" id="btn_print" class="botao" Value="Visualizar Requisição" onclick="enviar(".'$dados_requisicao.'",'id_requisicao')"></td></tr> -->
<?php  if($tupula){ //echo "<br>ds_requisicao: ".$tupula['ds_requisicao']."<br>";
		echo "<td style='cursor:pointer;' onclick='Gera_pdf(".$tupula['id_requisicao'].", \"id_requisicao\")' ><img src='./imagens/070.ico' height='12' width='12' title='Ordem da Requisição ".$tupula['ds_requisicao']."' ></td></tr>";
} ?>
                    <?php } ?>
                </table>
                <p align="center"><a href="index.php?acesso=2">INICIO DO AUDITA COMPRAS</a></p>
                <br>
            </fieldset>
            </td>
        <tr>
		<tr><td colspan="2">&nbsp;</td></tr>
</table>
</form>
<script type="text/javascript">
function fechar_janela(){
   parent.globalWin.hide();
}

function Gera_pdf(valor, campo){ alert('1');
alert(valor);
	//$('id_requisicao').value = valor; alert('2');
//alert($('id_requisicao'));
	$('frm_emp').action = "./modulos/fornecedor/gerar_nota_requisicao_fornecedor.php"
	//alert($('id_requisicao').value );
alert('2');
	$('frm_emp').submit();
alert('3');
}

</script>
<p align="center" id="pe">CBMSC</p>
</body>
</html>

