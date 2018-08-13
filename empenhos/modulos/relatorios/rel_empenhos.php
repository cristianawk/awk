<?
require "../../lib/loader.php";

   	//echo "<pre>"; print_r($_GET); echo "</pre>"; exit;

	$where = null;

	if($_GET['tipo'] != "" && $_GET['dados'] != ""){
			switch($_GET['tipo']){

				case 'id_mtr_gestor': $where = " AND a.id_mtr_gestor = ".$_GET['dados']; break;

				case 'nm_usuario': $where = " AND b.nm_usuario LIKE '%".strtoupper($_GET['dados'])."%'"; break;

				case 'nr_cpf_usuario': $where = " AND b.nr_cpf_usuario = ".formataCampo($_GET['dados']); break;

				case 'id_unidade': $where = " AND c.id_unidade = ".$_GET['dados']; break;

			}
	}

	if($_GET['dt_inicial'] != "" && $_GET['dt_final'] != ""){
		$where .= " AND dt_empenho BETWEEN ".formataCampo($_GET['dt_inicial'], 'DT')." AND ".formataCampo($_GET['dt_final'], 'DT');
	}

	if($_GET['order_by'] != ""){
		$ORDER = " ORDER BY ".$_GET['order_by'];
	}else{
		$ORDER = " ORDER BY id_empenho";
	}

	if($_GET['ord'] != "") $ORDER .= " ".$_GET['ord'];


    $sql = "SELECT id_empenho, UPPER(ds_empenho) AS ds_empenho,
			to_char(dt_empenho, 'DD/MM/YYYY') AS dt_empenho, dt_empenho AS data_empenho,
			to_char(dt_pagamento, 'DD/MM/YYYY') AS dt_pagamento, dt_pagamento AS data_pagamento,
			(CASE WHEN ch_tc28 = 'N' THEN 'NÃO EXIGIDO'
				ELSE (CASE WHEN dt_entrega_tc28 IS NULL THEN 'NÃO PREENCHIDO' ELSE to_char(dt_entrega_tc28, 'DD/MM/YYYY') END)
			END)
             AS dt_entrega_tc28,
			dt_entrega_tc28 AS data_entrega_tc28,
            id_mtr_gestor, b.nm_usuario, ds_repasse, b.nr_cpf_usuario, c.nm_unidade, d.ds_situacao_empenho
            FROM ".TBL_EMPENHO."  AS a JOIN ".TBL_USUARIO." AS b ON (a.id_mtr_gestor=b.id_mtr_usuario)
            JOIN ".TBL_UNIDADE." AS c ON (c.id_unidade=b.id_unidade)
            JOIN ".TBL_TIPO_SITUACAO." AS d USING (cd_situacao_empenho)
            WHERE ch_visualizar = 'S' $where
            $ORDER";
    //echo $sql; exit;
    $global_conn->query($sql);
    $num = $global_conn->num_rows();
    while($tupla = $global_conn->fetch_row()) $dados[] = $tupla;
    //echo "<pre>"; print_r($dados); echo "</pre>"; exit;



	$cabecalho = array(
	    "ds_empenho"			=> "Nº Empenho",
        "id_mtr_gestor" 		=> "Matrícula",
        "nm_usuario"			=> "Nome do Gestor",
        "nr_cpf_usuario"	    => "CPF",
        "dt_empenho"	        => "Data do Empenho",
        "dt_pagamento"		   	=> "Data Pagamento",
        "ds_situacao_empenho"	=> "Situação",
        "dt_entrega_tc28"		=> "Data Entrega TC-28",
        "ds_repasse"		   	=> "Valor Repassado",
        "nm_unidade"		   	=> "OBM"
	);

	$link = array(
	    "ds_empenho"			=> "ds_empenho",
        "id_mtr_gestor"			=> "id_mtr_gestor",
        "nm_usuario"			=> "nm_usuario",
        "nr_cpf_usuario"	    => "nr_cpf_usuario",
        "dt_empenho"	        => "data_empenho",
        "dt_pagamento"		   	=> "data_pagamento",
        "ds_situacao_empenho"	=> "ds_situacao_empenho",
        "dt_entrega_tc28"		=> "data_entrega_tc28",
        "ds_repasse"		   	=> "ds_repasse",
        "nm_unidade"		   	=> "c.id_unidade"
	);

	$largura = array(
	    "ds_empenho"			=> "142px",
        "id_mtr_gestor"			=> "98px",
        "nm_usuario"			=> "240px",
        "nr_cpf_usuario"	    => "120px",
        "dt_empenho"	        => "134px",
        "dt_pagamento"		   	=> "134px",
        "ds_situacao_empenho"	=> "120px",
        "dt_entrega_tc28"		=> "132px",
        "ds_repasse"		   	=> "120px",
        "nm_unidade"		   	=> "90px"
	);

	/*
	 *  Logos dos tipos de relatorios
	 */
    $imagens = array(
		//"pdf" => "pdf_logo.gif",
		"xls" => "xls_logo.gif",
		//"doc" => "word_logo.gif",
		"txt" => "txt_logo.gif"
	);

?>
<body>
<form target="_blank" enctype="multipart/form-data" method="POST" id="frm_cons_geral_oco"  name="frm_cons_geral_oco" action="./modulos/relatorios/montaRel.php" />
<input type="hidden" name="colunas" id="colunas" value='<?=JSONEncoder($cabecalho)?>'>
<input type="hidden" name="valores" id="valores" value='<?=JSONEncoder($dados)?>'>
<input type="hidden" name="ext" id="ext" value=''>
<?if($dados){?>
<ul id="icone">
	<? foreach($imagens AS $tp => $imagem){ ?>
		<li><a href="javascript:montaRelatorio('<?=$tp?>')"><img src="./imagens/<?=$imagem?>" width="16" height="16" title="Gerar <?=strtoupper($tp)?>"></a></li>
	<? } ?>
</ul>
<?}?>
<br><br>
<div id="relatorio">
	<table align="left" cellpadding="7" cellspacing="1" border="0" class="cabContainer">
		<thead>
		<tr class="head"><td colspan="15" id="left">Numero de Registros Encontrados:&nbsp;<?=$num?></td></tr>
		<tr class="cab">
			<? foreach($cabecalho AS $k => $cab){ if($_GET['order_by'] == $link[$k]){ if($_GET['ord'] == 'ASC') $ORD = 'DESC'; else $ORD = 'ASC'; } else { $ORD = 'ASC'; }
				?>
			<th width="<?=$largura[$k]?>"><a class="link_cab" onclick="ordenar('<?=$link[$k]?>', '<?=$ORD?>')"><?=$cab?></a></th>
			<? } ?>
			<th width="18px">&nbsp;</th>
		</tr>
		</thead>
	</table>
	<div class="scrollContainer">
	<table align="left" cellpadding="7" cellspacing="1" border="0" class="orgTab">
	<tbody>
<?

if($dados){
	foreach($dados AS $dado){ if($lin == "linA") $lin = "linB"; else $lin = "linA";?>
	<tr class="<?=$lin?>">
    <? foreach($cabecalho AS $k => $cab){ ?>
		<td width="<?=$largura[$k]?>"><?=$dado[$k]?></td>
    <? } ?>
		<th style="cursor:pointer;" onclick="javascript:recebimento('<?=$dado['id_empenho']?>')"/><img src="./imagens/combo.gif"/></th>
    </tr>
      <? } ?>
<?}else{?>
<tr><th colspan="15" class="erro" width="1500px">Nenhum registro Encontrado</th></tr>
<?}?>
<tr><th colspan="15">&#160;</th></tr>
</tbody>
</table>
</div>
</div>
</body>
