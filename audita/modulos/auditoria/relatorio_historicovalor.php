<?php header("Content-Type: text/html; charset=ISO-8859-1",true);
/*
 * carrega a classe a ser instanciada quando chamada
 */
 
 include_once "../../class/funcoes_uteis.php";
 
function __autoload($classe){
	$pontos = array("./", "../", "../../");
    foreach($pontos AS $ponto){
		if(file_exists("{$ponto}class/{$classe}.class.php")){
		//echo "{$ponto}class/{$classe}.class.php\n";
			include_once "{$ponto}class/{$classe}.class.php";
		}
	}
}
//echo "<pre>post: "; print_r($_POST); echo "</pre>";
//echo "<pre>get: "; print_r($_GET); echo "</pre>";

$where = null;
$condicoes = null;

if(@$_POST['id_unidade'][0] != ""){
	$condicoes[] = " a.id_unidade IN (".implode(',', $_POST['id_unidade']).")";
}

/*
 *  Se selecionar o fornecedor
 */
if($_POST['id_fornecedor']){ $condicoes[] = " b.id_fornecedor = ".$_POST['id_fornecedor']." "; }

if($condicoes != "") $where = " WHERE ".implode(" AND ", $condicoes);

//echo "<br> id_fornecedor: ".$_POST['id_fornecedor'];

$historicos = null;
//Classe de conexão com banco de dados
$conn = connection::init();
$sql = "SELECT a.ds_empenho, b.nm_fornecedor, a.nr_items_contratados, d.nm_produto, e.nm_unidade_medida,
		f.vl_unitario, to_char(f.dt_vigencia, 'DD/MM/YYYY') AS dt_vigencia
		FROM empenhos AS a
		JOIN fornecedores AS b USING (id_fornecedor)
		JOIN items_contratados AS c ON (a.id_empenho=c.id_empenho)
		JOIN produtos AS d ON (c.id_produto=d.id_produto)
		JOIN tipo_unidade_medida AS e ON (e.id_unidade_medida=c.id_unidade_medida)
		JOIN historico_valores AS f ON (c.id_item_contratado=f.id_item_contratado)
		$where
		ORDER BY nm_fornecedor, d.nm_produto, f.dt_vigencia";
//echo $sql; //exit;
$conn->query($sql);
$dados = $conn->get_tupla();


	$cabecalho_xls = array(
		"ds_empenho" 			=> "N. Empenho",
        "nm_fornecedor"			=> "Fornecedor",
        "nm_produto"			=> "Produto/Serviço",		
        "nr_items_contratados"	=> "Quantidade",
        "nm_unidade_medida"     => "Unidade",
        "vl_unitario"			=> "Valor",
        "dt_vigencia"		   	=> "Data Inicio Vigencia"
	);


	$cabecalho = array(

		"ds_empenho" 			=> "N. Empenho",
        "nm_fornecedor"			=> "Fornecedor",
        "nm_produto"			=> "Produto/Serviço",		
        "nr_items_contratados"	=> "Quantidade",
        "nm_unidade_medida"     => "Unidade",
        "vl_unitario"			=> "Valor",
        "dt_vigencia"		   	=> "Data Inicio Vigencia"

	);


	$link = array(

		"ds_empenho" 			=> "ds_empenho",
        "nm_fornecedor"			=> "nm_fornecedor",
        "nm_produto"			=> "nm_produto",		
        "nr_items_contratados"	=> "nr_items_contratados",
        "nm_unidade_medida"     => "nm_unidade_medida",
        "vl_unitario"			=> "vl_unitario",
        "dt_vigencia"		   	=> "dt_vigencia"

	);

	$largura = array(
	    "ds_empenho" 			=> "",
        "nm_fornecedor"			=> "",
        "nm_produto"	    	=> "",
        "nr_items_contratados"	=> "",
        "nm_unidade_medida"		=> "",
        "vl_unitario"		   	=> "",
        "dt_vigencia"		   	=> ""

	);


	/*
	 *  Logos dos tipos de relatorios
	 */
    $imagens = array(
		//"pdf" => "pdf_logo.gif",
		"pdf" => "pdf_logo.gif",
		//"doc" => "word_logo.gif",
		"txt" => "txt_logo.gif",
		//"xls" => "word_logo.gif",
		//"xls" => "xls_logo.gif"
	);


?>
<body>
<form target="_self" enctype="multipart/form-data" method="POST" id="frm_cons_geral_lib" 
name="frm_cons_geral_lib" action="./modulos/auditoria/montaRel.php">
<input type="hidden" name="colunas" id="colunas" value='<?php echo JSONEncoder($cabecalho_xls)?>'>
<input type="hidden" name="valores" id="valores" value='<?php echo JSONEncoder($dados)?>'>

<input type="hidden" name="ext" id="ext" value=''>
<br><hr><br>

<table border="0" width="100%" align="center" class="orgTable">
<tr>
<th colspan="7" align="left">
<?php if($dados){?>
<ul id="icone">
	<?php foreach($imagens AS $tp => $imagem){ ?>
		<a href="javascript:montaRelatorio('<?php echo $tp?>')"><img src="./imagens/<?php echo $imagem?>" width="16" height="16" title="Gerar <?php echo strtoupper($tp)?>"></a>
	<?php } ?>
</ul> 
<?php }?>
</th>
</tr>
<tr><th colspan="10">HISTÓRICO DE ALTERAÇÃO DE VALOR</th></th></tr>
<tr class="cab">
	<th width="">Nº EMPENHO</th>
	<th width="">NOME DO FORNECEDOR</th>
	<th width="">QT</th>
	<th>PRODUTO/SERVIÇO</th>
	<th>UNIDADE</th>
	<th>DATA INICIO VIG.</th>
	<th>VALOR</th>
</tr>
<?php if($dados){
	foreach($dados AS $dado){ ?>
	<tr class="lin">
		<td class="cen"><?php echo $dado['ds_empenho']?></td>
		<td><?php echo $dado['nm_fornecedor']?></td>
		<td class="cen"><?php echo $dado['nr_items_contratados']?></td>
		<td><?php echo $dado['nm_produto']?></td>
		<td><?php echo $dado['nm_unidade_medida']?></td>
		<td class="cen"><?php echo $dado['dt_vigencia']?></td>
		<td class="cen"><?php echo $dado['vl_unitario']?></td>
	</tr>
<?php }
 } else { ?>
 <tr><th colspan="6" class="erro">Nenhuma Histórico Encontrado</th></tr>
<?php } ?>
</table>

<script type="text/javascript">


</script>