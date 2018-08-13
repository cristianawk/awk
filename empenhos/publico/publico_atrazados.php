<?php

	require_once "../lib/loader.php";

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

    $sql = "SELECT a.nm_usuario, to_char(b.dt_empenho, 'DD/MM/YYYY') AS dt_empenho, a.id_mtr_usuario,
            a.nr_cpf_usuario, to_char(b.dt_pagamento, 'DD/MM/YYYY') AS dt_pagamento, to_char(ts_empenho, 'DD/MM/YYYY') AS ts_empenho,
            UPPER(b.ds_empenho) AS ds_empenho, c.nm_unidade, b.ds_repasse, d.nm_posto, ds_situacao_empenho,
            (CURRENT_DATE - (dt_pagamento + 61)) AS dias_atraso
            FROM ".TBL_EMPENHO." AS b
            JOIN ".TBL_USUARIO." AS a ON (a.id_mtr_usuario = b.id_mtr_gestor)
            JOIN ".TBL_UNIDADE." AS c USING (id_unidade)
            JOIN ".TBL_POSTO." AS d USING (id_posto)
            JOIN ".TBL_TIPO_SITUACAO." AS e USING (cd_situacao_empenho)
            WHERE b.ch_visualizar = 'S' AND (dt_pagamento + 61 ) < CURRENT_DATE
            AND dt_aprovacao IS NULL
            $where
            ORDER BY dias_atraso DESC";
    //echo $sql; exit;
    $global_conn->query($sql);
    $num = $global_conn->num_rows();
    while($tupla = $global_conn->fetch_row()) $dados[] = $tupla;
    //echo "<pre>"; print_r($dados); echo "</pre>"; exit;



	$cabecalho = array(
	    //"nm_posto" 				=> "Graduação",
        //"id_mtr_usuario"		=> "Matrícula",
        "nm_usuario"			=> "Nome do Gestor",
        "nm_unidade"		   	=> "OBM",
        "ds_repasse"		   	=> "Valor Repassado",
        //"nr_cpf_usuario"	    => "CPF",
        //"ds_empenho"	        => "Nº do Empenho",
        "ds_situacao_empenho"	=> "Situação",
        //"dt_empenho"		   	=> "Data Empenho",
        //"dt_pagamento"		   	=> "Data Pagamento",
        "ts_empenho"		   	=> "Data Ultimo Registro",
        "dias_atraso"		   	=> "Dias Atraso"
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    <link rel="stylesheet" type="text/css" href="../css/dlf.css">
    <title>GESTORES ATRASADOS</title>
</head>
<body>
<form target="_self" enctype="multipart/form-data" method="POST" id="frm_pub_atrasados"  name="frm_pub_atrasados" action="" />
<!--<input type="hidden" name="colunas" id="colunas" value='<?/*JSONEncoder($cabecalho)*/?>'>-->
<!--<input type="hidden" name="valores" id="valores" value='<?/*JSONEncoder($dados)*/?>'>-->
<input type="hidden" name="ext" id="ext" value=''>
<?/*if($dados){?>
<ul id="icone">
	<? foreach($imagens AS $tp => $imagem){ ?>
		<li><a href="javascript:montaRelatorio('<?=$tp?>')"><img src="./imagens/<?=$imagem?>" width="16" height="16" title="Gerar <?=strtoupper($tp)?>"></a></li>
	<? } ?>
</ul>
<?}*/?>
<br>
<table align="center" cellpadding="7" cellspacing="1" border="0" width="90%" class="orgTab">
	<tr class="cab"><th colspan="12">GESTORES ATRASADOS</td></tr>
    <tr class="head"><td colspan="12" id="left">Numero de Registros Encontrados:&nbsp;<?=$num?></td></tr>
    <tr class="cab">
        <? foreach($cabecalho AS $cab){ ?>
        <th><?=$cab?></th>
        <? } ?>
    </tr>
<?

if($dados){
	foreach($dados AS $dado){ if($lin == "linA") $lin = "linB"; else $lin = "linA";?>
	<tr class="<?=$lin?>">
    <? foreach($cabecalho AS $k => $cab){ ?>
		<td><?=$dado[$k]?></td>
    <? } ?>
    </tr>
      <? } ?>
<?}else{?>
<tr><th colspan="12" class="erro">Nenhum registro Encontrado</th></tr>
<?}?>
<tr><th colspan="12">&#160;</th></tr>
</table>
</body>
</html>
