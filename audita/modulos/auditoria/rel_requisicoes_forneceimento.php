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
		$ORDER = " ORDER BY  ds_empenho";
	}

	if($_GET['ord'] != "") $ORDER .= " ".$_GET['ord'];


    $sql = "SELECT id_requisicao, nr_requisicao, ds_empenho, to_char(dt_requisicao,'DD/MM/YYYY') as data, hr_requisicao, 
	nr_itens_requisicao, ds_requisicao, nm_unidade_medida
	FROM requisicoes r
	left join empenhos e on (r.id_empenho = e.id_empenho)
	left join tipo_unidade_medida um on (r.id_unidade = um.id_unidade_medida) $ORDER";
    //echo $sql; exit;
    $global_conn = connection::init();
    $global_conn->query($sql);
    $num = $global_conn->num_rows();
    while($tupla = $global_conn->fetch_row()) $dados[] = $tupla;
    //echo "<pre>"; print_r($dados); echo "</pre>"; exit;



	$cabecalho = array(
	"nr_requisicao"=>"N° Requisição",
	"ds_empenho"=>"Empenho",
	"data"=>"Data",
	"hr_requisicao"=>"Hora",
	"nr_itens_requisicao"=>"N° Item",
	"ds_requisicao"=>"DS Requisição",
	"nm_unidade_medida"=>"Unidade"
	);

	$link = array(
	"nr_requisicao"=>"nr_requisicao",
	"ds_empenho"=>"ds_empenho",
	"data"=>"data",
	"hr_requisicao"=>"hr_requisicao",
	"nr_itens_requisicao"=>"nr_itens_requisicao",
	"ds_requisicao"=>"ds_requisicao",
	"nm_unidade_medida"=>"nm_unidade_medida"
	);

	$largura = array(
	"nr_requisicao"=>"20px",
	"ds_empenho"=>"20px",
	"data"=>"20px",
	"hr_requisicao"=>"20px",
	"nr_itens_requisicao"=>"20px",
	"ds_requisicao"=>"20px",
	"nm_unidade_medida"=>"20px"
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
