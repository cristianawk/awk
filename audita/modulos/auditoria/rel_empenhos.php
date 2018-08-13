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


    $sql = "SELECT id_empenho, ds_empenho, ch_contrato, ch_requisicao, id_unidade, 
       dt_empenho, id_fornecedor, nr_items_contratados, nr_contrato, 
       dt_inicio, dt_final, ds_contrato, ds_cnpj_unidade_orcamentaria, 
       ch_bloqueio, to_char(vl_empenho,'LFM999G999G999G990D00') as vl_empenho, dt_contrato FROM empenhos $ORDER";
    //echo $sql; exit;
    $global_conn = connection::init();
    $global_conn->query($sql);
    $num = $global_conn->num_rows();
    while($tupla = $global_conn->fetch_row()) $dados[] = $tupla;
    //echo "<pre>"; print_r($dados); echo "</pre>"; exit;



	$cabecalho = array(
	"ds_empenho"=>"Empenho", 
	"ch_contrato"=>"Cont.", 
	"ch_requisicao"=>"Req.", 
	"id_unidade"=>"Id unid.", 
	"dt_empenho"=>"Data Emp.", 
	"id_fornecedor"=>"Id Fornec.", 
	"nr_contrato"=>"N° Contrato", 
	"dt_inicio"=>"Data Inicio", 
	"dt_final"=>"Data Fim", 
	"ds_contrato"=>"Contrato", 
	"ds_cnpj_unidade_orcamentaria"=>"CNPJ U_O",
	"ch_bloqueio"=>"Bloqueio", 
	"vl_empenho"=>"Valor", 
	"dt_contrato"=>"Data Contrato"
	);

	$link = array(
	"ds_empenho"=>"ds_empenho", 
	"ch_contrato"=>"ch_contrato", 
	"ch_requisicao"=>"ch_requisicao", 
	"id_unidade"=>"id_unidade", 
	"dt_empenho"=>"dt_empenho", 
	"id_fornecedor"=>"id_fornecedor", 
	"nr_items_contratados"=>"nr_items_contratados", 
	"nr_contrato"=>"nr_contrato", 
	"dt_inicio"=>"dt_inicio", 
	"dt_final"=>"dt_final", 
	"ds_contrato"=>"ds_contrato", 
	"ds_cnpj_unidade_orcamentaria"=>"ds_cnpj_unidade_orcamentaria",
	"ch_bloqueio"=>"ch_bloqueio", 
	"vl_empenho"=>"vl_empenho", 
	"dt_contrato"=>"dt_contrato"
	);

	$largura = array(
	"ds_empenho"=>"20px", 
	"ch_contrato"=>"45px", 
	"ch_requisicao"=>"45px", 
	"id_unidade"=>"20px", 
	"dt_empenho"=>"20px", 
	"id_fornecedor"=>"20px", 
	"nr_items_contratados"=>"20px", 
	"nr_contrato"=>"20px", 
	"dt_inicio"=>"20px", 
	"dt_final"=>"20px", 
	"ds_contrato"=>"20px", 
	"ds_cnpj_unidade_orcamentaria"=>"20px",
	"ch_bloqueio"=>"20px", 
	"vl_empenho"=>"20px", 
	"dt_contrato"=>"20px"
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
