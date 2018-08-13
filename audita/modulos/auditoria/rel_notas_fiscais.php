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
		$ORDER = " ORDER BY nr_notafiscal";
	}

	if($_GET['ord'] != "") $ORDER .= " ".$_GET['ord'];


    $sql = "SELECT id_notafiscal, nr_notafiscal, dt_notafiscal, nr_requisicao, 
	to_char(vl_notafiscal,'LFM999G999G999G990D00') as vl_notafiscal,
	ds_andamento, nm_usuario, ch_notafiscal, ds_pendencias, id_fornecedor, 
	id_status_nota
	FROM notas_fiscais nf
	left join usuarios u on (nf.id_usuario = u.id_usuario)
	left join requisicoes r on (nf.id_requisicao = r.id_requisicao) $ORDER";
    //echo $sql; exit;
    $global_conn = connection::init();
    $global_conn->query($sql);
    $num = $global_conn->num_rows();
    while($tupla = $global_conn->fetch_row()) $dados[] = $tupla;
    //echo "<pre>"; print_r($dados); echo "</pre>"; exit;



	$cabecalho = array(
	"nr_notafiscal"=>"N° Nota Fiscal",
	"dt_notafiscal"=>"Data",
	"nr_requisicao"=>"N° Requisição",
	"vl_notafiscal"=>"Valor",
	"ds_andamento"=>"Andamento",
	"nm_usuario"=>"Usuário",
	"ch_notafiscal"=>"CH NF",
	"ds_pendencias"=>"Pendência",
	"id_fornecedor"=>"Id Fornec.",
	"id_status_nota"=>"Status"
	);

	$link = array(
	"id_notafiscal"=>"id_notafiscal",
	"nr_notafiscal"=>"nr_notafiscal",
	"dt_notafiscal"=>"dt_notafiscal",
	"nr_requisicao"=>"nr_requisicao",
	"vl_notafiscal"=>"vl_notafiscal",
	"ds_andamento"=>"ds_andamento",
	"nm_usuario"=>"nm_usuario",
	"ch_notafiscal"=>"ch_notafiscal",
	"ds_pendencias"=>"ds_pendencias",
	"id_fornecedor"=>"id_fornecedor",
	"id_status_nota"=>"id_status_nota"
	);

	$largura = array(
	"id_notafiscal"=>"30px",
	"nr_notafiscal"=>"30px",
	"dt_notafiscal"=>"30px",
	"nr_requisicao"=>"30px",
	"vl_notafiscal"=>"30px",
	"ds_andamento"=>"30px",
	"nm_usuario"=>"30px",
	"ch_notafiscal"=>"30px",
	"ds_pendencias"=>"30px",
	"id_fornecedor"=>"30px",
	"id_status_nota"=>"30px"
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
