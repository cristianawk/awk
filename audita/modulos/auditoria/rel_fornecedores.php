<link href="./css/dlf.css" rel="stylesheet" type="text/css" />
<?php
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

	if($_GET['order_by'] != ""){
		$ORDER = " ORDER BY ".$_GET['order_by'];
	}else{
		$ORDER = " ORDER BY nm_fornecedor";
	}

	if($_GET['ord'] != "") $ORDER .= " ".$_GET['ord'];


    $sql = "SELECT id_fornecedor, nm_fornecedor, ds_cnpj, nm_responsavel, ds_cpf, 
       ds_email1, ds_email2, id_funcao, id_banco, ds_agencia, ds_agencia_dv, 
       ds_conta, ds_conta_dv, nr_telefone1, nr_telefone2, id_endereco
       FROM fornecedores $ORDER limit 10";
    //echo $sql; exit;
    $global_conn = connection::init();
    $global_conn->query($sql);
    $num = $global_conn->num_rows();
    while($tupla = $global_conn->fetch_row()) $dados[] = $tupla;
    //echo "<pre>"; print_r($dados); echo "</pre>"; exit;



	$cabecalho = array(
	"nm_fornecedor"		=>"Fornecedor",
	"ds_cnpj"		=>"CNPJ",
	"nm_responsavel"	=>"Responsável", 
	"ds_cpf"		=>"cpf",
	"ds_email1"		=>"E-mail1",
	"ds_email2"		=>"E-mail2", 
	"id_funcao"		=>"Função",
	"id_banco"		=>"Banco",
	"ds_agencia"		=>"Agência",
	"ds_agencia_dv"		=>"Agência_dv",
	"ds_conta"		=>"Conta",
	"ds_conta_dv"		=>"Conta_dv",
	"nr_telefone1"		=>"Tel.1",
	"nr_telefone2"		=>"Tel.2",
	"id_endereco"		=>"Endeteço"
	);

	$link = array(
	"nm_fornecedor"		=>"nm_fornecedor",
	"ds_cnpj"		=>"ds_cnpj",
	"nm_responsavel"	=>"nm_responsavel", 
	"ds_cpf"		=>"ds_cpf",
	"ds_email1"		=>"ds_email1",
	"ds_email2"		=>"ds_email2", 
	"id_funcao"		=>"id_funcao",
	"id_banco"		=>"id_banco",
	"ds_agencia"		=>"ds_agencia",
	"ds_agencia_dv"		=>"ds_agencia_dv",
	"ds_conta"		=>"ds_conta",
	"ds_conta_dv"		=>"ds_conta_dv",
	"nr_telefone1"		=>"nr_telefone1",
	"nr_telefone2"		=>"nr_telefone2",
	"id_endereco"		=>"id_endereco"
	);

	$largura = array(
	"nm_fornecedor"		=>"2px",
	"ds_cnpj"		=>"2px",
	"nm_responsavel"	=>"2px", 
	"ds_cpf"		=>"2px",
	"ds_email1"		=>"2px",
	"ds_email2"		=>"2px", 
	"id_funcao"		=>"2px",
	"id_banco"		=>"2px",
	"ds_agencia"		=>"2px",
	"ds_agencia_dv"		=>"2px",
	"ds_conta"		=>"2px",
	"ds_conta_dv"		=>"2px",
	"nr_telefone1"		=>"2px",
	"nr_telefone2"		=>"2px",
	"id_endereco"		=>"2px"
	);

	/*
	 *  Logos dos tipos de relatorios
	 */
    $imagens = array(
		"pdf" => "pdf_logo.gif",
		"xls" => "xls_logo.gif",
		//"doc" => "word_logo.gif",
		"txt" => "txt_logo.gif"
	);

?>
<body>

<?php if($dados){?>
<ul id="icone">
	<?php foreach($imagens AS $tp => $imagem){ ?>
		<li><a href="javascript:montaRelatorio('<?php echo $tp?>','<?php echo urlencode(serialize($cabecalho));?>','<?php echo urlencode(serialize($dados));?>')"><img src="./imagens/<?php echo $imagem?>" width="16" height="16" title="Gerar <?php echo strtoupper($tp)?>"></a></li>
	<?php } ?>
</ul>
<?php }?>
<br><br>
<div id="relatorio">
	<table align="left" cellpadding="7" cellspacing="1" border="0" class="cabContainer">
		<thead>
		<tr class="head"><td colspan="15" id="left">Numero de Registros Encontrados:&nbsp;<?php echo $num?></td></tr>
		<tr class="cab">
			<?php foreach($cabecalho AS $k => $cab){ if($_GET['order_by'] == $link[$k]){ if($_GET['ord'] == 'ASC') $ORD = 'DESC'; else $ORD = 'ASC'; } else { $ORD = 'ASC'; }
				?>
			<th width="<?php echo $largura[$k]?>"><a class="link_cab" onclick="ordenar('<?php echo $link[$k]?>', '<?php echo $ORD?>')"><?php echo $cab?></a></th>
			<?php } ?>
		</tr>
		</thead>
	</table>
	<div class="scrollContainer">
	<table align="left" cellpadding="7" cellspacing="1" border="0" class="orgTab">
	<tbody>
<?

if($dados){
	foreach($dados AS $dado){ if($lin == "linA") $lin = "linB"; else $lin = "linA";?>
	<tr class="<?php echo $lin?>">
    <?php foreach($cabecalho AS $k => $cab){ ?>
		<td width="<?php echo $largura[$k]?>"><?php echo $dado[$k]?></td>
    <?php } ?>
    </tr>
      <?php } ?>
<?php }else{?>
<tr><th colspan="15" class="erro" width="1500px">Nenhum registro Encontrado</th></tr>
<?php }?>
<tr><th colspan="15">&#160;</th></tr>
</tbody>
</table>
</div>
</div>
<p>Se deseja visualizar o relatório simplificado, <a href="./publico/publico_atrazados.php?tipo=<?php echo $_GET['tipo']?>&dados=<?php echo $_GET['dados']?>" target="_blank">click aqui</a></p>
</body>
