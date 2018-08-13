<?
require "../../lib/loader.php";

   	//echo "<pre>"; print_r($_GET); echo "</pre>"; exit;

	$where = null;

	if($_GET['tipo'] != "" && $_GET['dados'] != ""){
			switch($_GET['tipo']){

				case 'id_mtr_usuario': $where = " AND a.id_mtr_usuario = ".$_GET['dados']; break;

				case 'nm_usuario': $where = " AND a.nm_usuario LIKE '%".strtoupper($_GET['dados'])."%'"; break;

				case 'nr_cpf_usuario': $where = " AND a.nr_cpf_usuario = ".formataCampo($_GET['dados']); break;

				case 'id_unidade': $where = " AND a.id_unidade = ".$_GET['dados']; break;

			}
	}

	if($_GET['order_by'] != ""){
		$ORDER = " ORDER BY ".$_GET['order_by'];
	}else{
		$ORDER = " ORDER BY c.id_unidade, a.nm_usuario, b.dt_empenho";
	}

	if($_GET['ord'] != "") $ORDER .= " ".$_GET['ord'];


    $sql = "SELECT a.nm_usuario,
			to_char(b.dt_empenho, 'DD/MM/YYYY') AS dt_empenho, b.dt_empenho AS data_empenho,
    		a.id_mtr_usuario,
            a.nr_cpf_usuario,
            to_char(b.dt_pagamento, 'DD/MM/YYYY') AS dt_pagamento, b.dt_pagamento AS data_pagamento,
            to_char(ts_empenho, 'DD/MM/YYYY') AS ts_empenho, ts_empenho AS data_ts_empenho,
            UPPER(b.ds_empenho) AS ds_empenho, c.nm_unidade, b.ds_repasse, d.nm_posto, ds_situacao_empenho,
            (CURRENT_DATE - (dt_pagamento + 61)) AS dias_atraso,

            (CASE WHEN ch_tc28 = 'N' THEN 'NÃO EXIGIDO'
				ELSE (CASE WHEN dt_entrega_tc28 IS NULL THEN 'NÃO PREENCHIDO' ELSE to_char(dt_entrega_tc28, 'DD/MM/YYYY') END)
			END)
             AS dt_entrega_tc28,

            dt_entrega_tc28 AS data_entrega_tc28
            FROM ".TBL_EMPENHO." AS b
            JOIN ".TBL_USUARIO." AS a ON (a.id_mtr_usuario = b.id_mtr_gestor)
            JOIN ".TBL_UNIDADE." AS c USING (id_unidade)
            JOIN ".TBL_POSTO." AS d USING (id_posto)
            JOIN ".TBL_TIPO_SITUACAO." AS e USING (cd_situacao_empenho)
            WHERE b.ch_visualizar = 'S' AND (dt_pagamento + 61 ) < CURRENT_DATE
            AND dt_aprovacao IS NULL
            $where
            $ORDER";
    //echo $sql; exit;
    $global_conn->query($sql);
    $num = $global_conn->num_rows();
    while($tupla = $global_conn->fetch_row()) $dados[] = $tupla;
    //echo "<pre>"; print_r($dados); echo "</pre>"; exit;



	$cabecalho = array(
	    "nm_posto" 				=> "Graduação",
        "id_mtr_usuario"		=> "Matrícula",
        "nm_usuario"			=> "Nome do Gestor",
        "nr_cpf_usuario"	    => "CPF",
        "ds_empenho"	        => "Nº do Empenho",
        "ds_situacao_empenho"	=> "Situação",
        "dt_empenho"		   	=> "Data Empenho",
        "dt_pagamento"		   	=> "Data Pagamento",
        "ts_empenho"		   	=> "Data Ultimo Registro",
        "dt_entrega_tc28"		=> "Data Entrega TC-28",
        "ds_repasse"		   	=> "Valor Repassado",
        "nm_unidade"		   	=> "OBM",
        "dias_atraso"		   	=> "Dias Atraso"
	);

	$link = array(
	    "nm_posto" 				=> "nm_posto",
        "id_mtr_usuario"		=> "id_mtr_usuario",
        "nm_usuario"			=> "nm_usuario",
        "nr_cpf_usuario"	    => "nr_cpf_usuario",
        "ds_empenho"	        => "ds_empenho",
        "ds_situacao_empenho"	=> "ds_situacao_empenho",
        "dt_empenho"		   	=> "data_empenho",
        "dt_pagamento"		   	=> "data_pagamento",
        "ts_empenho"		   	=> "data_ts_empenho",
        "dt_entrega_tc28"		=> "dt_entrega_tc28",
        "ds_repasse"		   	=> "ds_repasse",
        "nm_unidade"		   	=> "id_unidade",
        "dias_atraso"		   	=> "dias_atraso"
	);

	$largura = array(
	    "nm_posto" 				=> "116px",
        "id_mtr_usuario"		=> "80px",
        "nm_usuario"			=> "139px",
        "nr_cpf_usuario"	    => "107px",
        "ds_empenho"	        => "110px",
        "ds_situacao_empenho"	=> "110px",
        "dt_empenho"		   	=> "80px",
        "dt_pagamento"		   	=> "80px",
        "ts_empenho"		   	=> "80px",
        "dt_entrega_tc28"		=> "100px",
        "ds_repasse"		   	=> "100px",
        "nm_unidade"		   	=> "139px",
        "dias_atraso"		   	=> "74px"
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
<p>Se deseja visualizar o relatório simplificado, <a href="./publico/publico_atrazados.php?tipo=<?=$_GET['tipo']?>&dados=<?=$_GET['dados']?>" target="_blank">click aqui</a></p>
</body>
