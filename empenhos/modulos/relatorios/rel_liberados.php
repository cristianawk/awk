<?php

	//echo "<pre>"; print_r($_GET); echo "</pre>"; exit;

	require "../../lib/loader.php";

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
		$ORDER = " ORDER BY a.nm_usuario";
	}

	if($_GET['ord'] != "") $ORDER .= " ".$_GET['ord'];


    $sql = "SELECT a.nm_usuario, a.id_mtr_usuario, a.nr_cpf_usuario,c.nm_unidade,
            b.nm_banco, a.ds_agencia, a.ds_conta, d.nm_posto, ds_email_usuario,
            nr_telefone, nr_celular, ds_agencia_digito, ds_conta_digito,

            (SELECT CURRENT_DATE - dt_empenho FROM empenhos WHERE id_mtr_gestor=a.id_mtr_usuario AND dt_empenho =
			(SELECT MAX(dt_empenho) FROM empenhos WHERE id_mtr_gestor=a.id_mtr_usuario) LIMIT 1) AS sem_adiantamento


            FROM ".TBL_BANCOS." AS b JOIN ".TBL_USUARIO." AS a ON (a.id_banco = b.id_banco)
            JOIN ".TBL_UNIDADE." AS c USING (id_unidade)
            JOIN ".TBL_POSTO." AS d USING (id_posto)
            JOIN ".TBL_EMPENHO." AS e ON (a.id_mtr_usuario = e.id_mtr_gestor)
            WHERE e.ch_visualizar = 'S' AND a.id_mtr_usuario NOT IN (SELECT DISTINCT id_mtr_gestor FROM ".TBL_EMPENHO." WHERE a.id_mtr_usuario = id_mtr_gestor AND  (dt_pagamento + 60 )  < CURRENT_DATE AND dt_contabilidade IS NULL)
            $where
            AND (SELECT COUNT(id_mtr_gestor) FROM ".TBL_EMPENHO."  WHERE a.id_mtr_usuario = id_mtr_gestor AND dt_contabilidade IS NULL) <= 1
            GROUP BY nm_usuario, a.id_mtr_usuario, nr_cpf_usuario, nm_unidade, nm_banco, ds_agencia,ds_conta, nm_posto, ds_email_usuario, nr_telefone, nr_celular, ds_agencia_digito, ds_conta_digito, a.id_unidade
            $ORDER";
    //echo $sql; exit;
    $global_conn->query($sql);
    $num = $global_conn->num_rows();
    while($tupla = $global_conn->fetch_row()) $dados[] = $tupla;
    //echo "<pre>"; print_r($dados); echo "</pre>"; exit;

	$cabecalho_xls = array(
		"nm_posto" 				=> "Graduação",
        "id_mtr_usuario"		=> "Matrícula",
        "nm_usuario"			=> "Nome do Gestor",
        "nr_cpf_usuario"	    => "CPF",
        "ds_email_usuario"      => "Email",
        "nr_telefone"			=> "Telefone",
        "nr_celular"		   	=> "Celular",
		"nm_banco"			   	=> "Banco",
        "ds_agencia"		   	=> "Agência",
        "ds_agencia_digito"   	=> "AG. DV",
        "ds_conta"			   	=> "Conta Corrente",
        "ds_conta_digito"	   	=> "CC. DV",
        "nm_unidade"		   	=> "OBM",
        "sem_adiantamento"	   	=> "Sem Adiantamento"
	);


	$cabecalho = array(

	    "nm_posto" 				=> "Posto/Graduação",
        "nm_usuario"			=> "Matrícula / Nome do Gestor",
        "nr_cpf_usuario"	    => "CPF",
        "ds_email_usuario"	    => "Email",
        "nr_telefone"			=> "Telefone",
        "nr_celular"		   	=> "Celular",
        "nm_banco"		   		=> "Banco",
        "ds_agencia"		   	=> "Agência",
        "ds_conta"				=> "Conta Corrente",
        "nm_unidade"		   	=> "OBM",
        "sem_adiantamento"		=> "SA"

	);


	$link = array(

	    "nm_posto" 				=> "nm_posto",
        "nm_usuario"			=> "nm_usuario",
        "nr_cpf_usuario"	    => "nr_cpf_usuario",
        "ds_email_usuario"	    => "ds_email_usuario",
        "nr_telefone"			=> "nr_telefone",
        "nr_celular"		   	=> "nr_celular",
        "nm_banco"		   		=> "nm_banco",
        "ds_agencia"		   	=> "ds_agencia",
        "ds_conta"				=> "ds_conta",
        "nm_unidade"		   	=> "id_unidade",
        "sem_adiantamento"		=> "sem_adiantamento"

	);

	$largura = array(
	    "nm_posto" 				=> "120px",
        "nm_usuario"			=> "250px",
        "nr_cpf_usuario"	    => "100px",
        "ds_email_usuario"	    => "177px",
        "nr_telefone"			=> "100px",
        "nr_celular"		   	=> "100px",
        "nm_banco"		   		=> "100px",
        "ds_agencia"		   	=> "90px",
        "ds_conta"				=> "120px",
        "nm_unidade"		   	=> "120px",
        "sem_adiantamento"		=> ""

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
<form target="_self" enctype="multipart/form-data" method="POST" id="frm_cons_geral_lib" name="frm_cons_geral_lib" action="./modulos/relatorios/montaRel.php">
<input type="hidden" name="colunas" id="colunas" value='<?=JSONEncoder($cabecalho_xls)?>'>
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
    <tr class="head"><td colspan="11" id="left">Numero de Registros Encontrados:&nbsp;<?=$num?></td></tr>
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
	<td id="left"  width="<?=$largura['nm_posto']?>"><?=$dado['nm_posto']?></td>
	<td id="left"  width="<?=$largura['nm_usuario']?>"><?=$dado['id_mtr_usuario']?> - <?=$dado['nm_usuario']?></td>
        <td width="<?=$largura['nr_cpf_usuario']?>"><?=$dado['nr_cpf_usuario']?></td>
        <td width="<?=$largura['ds_email_usuario']?>"><?=$dado['ds_email_usuario']?></td>
        <td width="<?=$largura['nr_telefone']?>"><?=$dado['nr_telefone']?></td>
        <td width="<?=$largura['nr_celular']?>"><?=$dado['nr_celular']?></td>
        <td width="<?=$largura['nm_banco']?>"><?=$dado['nm_banco']?></td>
		<td width="<?=$largura['ds_agencia']?>"><?=$dado['ds_agencia']?><?if($dado['ds_agencia_digito'] != "") echo " - ".$dado['ds_agencia_digito']?></td>
        <td width="<?=$largura['ds_conta']?>"><?=$dado['ds_conta']?><?if($dado['ds_conta_digito'] != "") echo " - ".$dado['ds_conta_digito']?></td>
        <td width="<?=$largura['nm_unidade']?>"><?=$dado['nm_unidade']?></td>
        <th width="<?=$largura['sem_adiantamento']?>"><?=$dado['sem_adiantamento']?> dia(s)</th>
    </tr>
      <? } ?>
<?}else{?>
<tr><th colspan="15" class="erro" width="1500px">Nenhum registro Encontrado</th></tr>
<?}?>
<tr><th colspan="11">&#160;</th></tr>
</tbody>
</table>
</div>
</div>
</body>
