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
		$ORDER = " ORDER BY id_unidade";
	}

	if($_GET['ord'] != "") $ORDER .= " ".$_GET['ord'];


    $sql = "SELECT id_unidade, cd_unidade, nm_unidade, nm_ordenador, ds_cargo_ordenador, nr_telefone_1, nr_telefone_2, ds_email, id_endereco
	  FROM unidades_beneficiadas $ORDER";
    //echo $sql; exit;
    $global_conn = connection::init();
    $global_conn->query($sql);
    $num = $global_conn->num_rows();
    while($tupla = $global_conn->fetch_row()) $dados[] = $tupla;
    //echo "<pre>"; print_r($dados); echo "</pre>"; exit;



	$cabecalho = array(
	"id_unidade"		=>"Id",
	"cd_unidade"		=>"Cd",
	"nm_unidade"		=>"Unidade",
	"nm_ordenador"		=>"Ordenador",
	"ds_cargo_ordenador"	=>"Cargo Ordenador",
	"nr_telefone_1"		=>"Tel. 1",
	"nr_telefone_2"		=>"Tel. 2",
	"ds_email"		=>"E-mail",
	"id_endereco"		=>"Endereço"
	);

	$link = array(
	"id_unidade"		=>"id_unidade",
	"cd_unidade"		=>"cd_unidade",
	"nm_unidade"		=>"nm_unidade",
	"nm_ordenador"		=>"nm_ordenador",
	"ds_cargo_ordenador"	=>"ds_cargo_ordenador",
	"nr_telefone_1"		=>"nr_telefone_1",
	"nr_telefone_2"		=>"nr_telefone_2",
	"ds_email"		=>"ds_email",
	"id_endereco"		=>"id_endereco"
	);

	$largura = array(
	"id_unidade"		=> "142px",
        "cd_unidade"		=> "98px",
        "nm_unidade"		=> "240px",
        "nm_ordenador"		=> "120px",
        "ds_cargo_ordenador"	=> "134px",
        "nr_telefone_1"		=> "134px",
        "nr_telefone_2"		=> "120px",
        "ds_email"		=> "132px",
        "id_endereco"		=> "120px"
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
<!--<form target="_blank" enctype="multipart/form-data" method="POST" id="frm_cons_geral_oco"  name="frm_cons_geral_oco" action="./modulos/auditoria/montaRel.php" />
 <input type="hidden" name="colunas" id="colunas" value='<?=json_encode($cabecalho)?>'>
 <input type="hidden" name="valores" id="valores" value='<?=json_encode($dados)?>'>
 <input type="hidden" name="ext" id="ext" value=''>-->
<?php if($dados){?>
<ul id="icone">
	<?php  foreach($imagens AS $tp => $imagem){ ?>
		<li><a href="javascript:montaRelatorio('<?php echo $tp?>','<?php echo urlencode(serialize($cabecalho));?>','<?php echo urlencode(serialize($dados));?>')"><img src="./imagens/<?php echo $imagem?>" width="16" height="16" title="Gerar <?php echo strtoupper($tp)?>"></a></li>
		<!--<li><INPUT TYPE="image" SRC="./imagens/<?php echo $imagem?>" width="16" height="16" title="Gerar <?php echo strtoupper($tp)?>" onclick="montaArquivo('<?php echo $tp?>')";return false;"></li>-->
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
<?php

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
