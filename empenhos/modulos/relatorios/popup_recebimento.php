<?php
require "../../lib/loader.php";
//echo "<pre>"; print_r($_GET); echo "</pre>"; exit;

$botao = true;

if($_GET['id']){

    $sql = "SELECT a.id_empenho, UPPER(ds_empenho) AS ds_empenho,
    to_char(a.dt_empenho, 'DD/MM/YYYY') AS data_empenho,
	to_char(dt_pagamento, 'DD/MM/YYYY') AS dt_pagamento,
	to_char(dt_contabilidade, 'DD/MM/YYYY') AS dt_contabilidade,
	to_char(dt_aprovacao, 'DD/MM/YYYY') AS dt_aprovacao,
	id_mtr_gestor, b.nm_usuario, b.ds_email_usuario, b.nr_cpf_usuario,
	ds_email_comandante, ds_repasse, c.id_recebimento, ds_situacao_empenho,


	(SELECT to_char(dt_recebimento, 'DD/MM/YYYY') FROM ".TBL_EMP_RECEBIMENTO."
	WHERE id_empenho=a.id_empenho
	AND id_recebimento = c.id_recebimento
	AND d.id_recebimento IS NULL
	ORDER BY id_recebimento DESC LIMIT 1) AS data_recebimento,

	(SELECT to_char(dt_recebimento, 'DD/MM/YYYY') FROM empenhos_recebimentos
	WHERE id_empenho=a.id_empenho AND id_recebimento = c.id_recebimento
	ORDER BY id_recebimento DESC LIMIT 1) AS data_ultimo_recebimento,

	(SELECT COUNT(id_recebimento) FROM empenhos_recebimentos
	WHERE id_empenho=a.id_empenho) AS count_recebimento,


	(SELECT to_char(dt_devolucao, 'DD/MM/YYYY') FROM ".TBL_EMP_DEVOLUCAO."
	WHERE id_empenho=a.id_empenho AND id_recebimento = c.id_recebimento
	AND c.ts_recebimento < ts_devolucao
	ORDER BY id_devolucao DESC LIMIT 1) AS data_devolucao,

	to_char(dt_entrega_tc28, 'DD/MM/YYYY') AS dt_entrega_tc28, nm_posto, ch_enviar_email, ch_tc28

	 FROM ".TBL_EMPENHO." AS a
	 JOIN ".TBL_USUARIO." AS b ON (a.id_mtr_gestor=b.id_mtr_usuario)
	 LEFT JOIN ".TBL_EMP_RECEBIMENTO."  AS c ON (a.id_empenho = c.id_empenho)
	 LEFT JOIN ".TBL_EMP_DEVOLUCAO." AS d ON (a.id_empenho = d.id_empenho AND c.id_recebimento = d.id_recebimento)
	 JOIN ".TBL_TIPO_SITUACAO." AS e USING (cd_situacao_empenho)
	 JOIN ".TBL_POSTO." AS p USING (id_posto)
	 WHERE a.id_empenho = ".$_GET['id']."
	 ORDER BY c.id_recebimento DESC, id_devolucao DESC LIMIT 1";

    //echo $sql;  exit;
    $global_conn->query($sql);
    while($tupla = $global_conn->fetch_row()) $dados = $tupla;

    //echo "<pre>"; print_r($dados); echo "</pre>"; exit;

    $id_empenho         = $dados['id_empenho'];
    $ds_empenho         = $dados['ds_empenho'];
    $ds_emailgestor		= $dados['ds_email_usuario'];
    $ds_emailcomandante	= $dados['ds_email_comandante'];


    /*
     *  Data de Recebimento
     */

    if($dados['data_recebimento']!=""){
        $dtrecebimento = $dados['data_recebimento'];
        $flag_recebimento = true;
    }else{
        $dtrecebimento="NÃO PREENCHIDO";

		if($dados['data_ultimo_recebimento'] != ""){
			$dtrecebimento .= "&nbsp;&nbsp;<i>Data do ultimo Recebimento: <b>".$dados['data_ultimo_recebimento']."</b>.&nbsp;&nbsp;Nº de Recebimentos: <b>".$dados['count_recebimento']."</b></i>";
		}
        $flag_recebimento = false;
    }


    /*
     *  Data de Devolução
     */

    if($dados['data_devolucao']!=""){

        $dtdevolucao = $dados['data_devolucao'];
        //"&nbsp;&nbsp;<a style='cursor: pointer' onclick='ativaCampo(\"cmp_devolucao\", \"bt_devolucao\")'><img id='bt_devolucao' src='../../imagens/buttonright.jpeg' height='14' width='14' title='Adicionar Nova Data Devolução'></a>&nbsp;&nbsp;<div id='cmp_devolucao' style='display:none'><input type='text'  name='dt_devolucao' id='dt_devolucao' value='' size='10' onkeyup='checadata(this)' onblur='verificarData(this, \"".$dados['data_recebimento']."\", \"Devolução\", \"do Recebimento da Auditoria\", false)' maxlength='10'>&nbsp;<input type='button' value='' style='background-image : url(\"../../imagens/img.gif\");' onclick='return showCalendar(\"dt_devolucao\", \"dd/mm/y\");'>&nbsp;&nbsp;<input type='file' id='arquivo' name='arquivo' size='30'></div>";
        $flag_devolucao = true;

    }elseif($flag_recebimento){
        if($dados['dt_aprovacao'] == ""){
                $dtdevolucao="NÃO PREENCHIDO";
        }else{
               $dtdevolucao = "NÃO PREENCHIDO";
        }
    }else{
        $dtdevolucao = "NÃO PREENCHIDO";
        $flag_devolucao = false;
    }


    /*
     *  Data de Aprovação
     */

    if(($flag_recebimento == true)&&($dados['dt_aprovacao'] == "")){
        //se data de recebimento
        $dtaprovacao="NÃO PREENCHIDO";
        $flag_aprovacao = false;
    }elseif($dados['dt_aprovacao'] != ""){
        $dtaprovacao = $dados['dt_aprovacao'];
        $flag_aprovacao = true;
    }else{
        $dtaprovacao = "NÃO PREENCHIDO";
        $flag_aprovacao = false;
    }

    /*
     *  Data de Contabilidade
     */

    if($dados['dt_contabilidade']!=""){
        $dtcontabilidade = $dados['dt_contabilidade'];
    }else{ $dtcontabilidade = 'NÃO PREENCHIDO'; }



if($dados['ch_tc28'] == 'S'){
		$dt_entrega_tc28 = "NÃO PREENCHIDO";
}else{
	$dt_entrega_tc28 = 'NÃO EXIGIDO';
}

}
//print_r($dados);
//echo "<br>mostra: ".$dados['ds_empenho'];
?>
<script language="JavaScript" type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/validation.js"></script>
<script type="text/javascript" src="../../js/jscalendar-0.9.3/calendar.js"></script>
<script type="text/javascript" src="../../js/jscalendar-0.9.3/lang/calendar-pt.js"></script>        <!--// escolher idioma (assim está brasileiro)     -->
<script type="text/javascript" src="../../js/dlf.js"> </script>
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css" >
<link href="../../css/themes/alphacube.css" rel="stylesheet" type="text/css" >
<link rel="stylesheet" type="text/css" href="../../css/menu.css">
<link rel="stylesheet" type="text/css" href="../../css/dlf.css">
<link rel="stylesheet" type="text/css" href="../../js/jscalendar-0.9.3/calendar-blue.css" />
<p id="mesg" align="center"></p>
<div id="field">
<fieldset>
<form name="frm_acao" id="frm_acao" onsubmit="submitForm(); return false;" action="./inc_recebimento.php" method="POST" enctype="multipart/form-data">
<table border="0" cellspacing="2" cellpadding="6" align="center" class="orgTableBorder" width="100%">
<tr><th width="25%">N. Empenho:&nbsp;</th><td><?=$dados['ds_empenho']?></td></tr>
<tr><th>Data do Empenho:&nbsp;</th><td><?=$dados['data_empenho']?></td></tr>
<tr><th>Matricula / Nome do Gestor:&nbsp;</th><td><?=$dados['id_mtr_gestor']?> - <?=$dados['nm_usuario']?></td></tr>
<tr><th>CPF Gestor:&nbsp;</th><td><?=$dados['nr_cpf_usuario']?></td></tr>
<tr><th>Situação:&nbsp;</th><td><?=$dados['ds_situacao_empenho']?></td></tr>
<tr><th>Valor Repassado:&nbsp;</th><td><?=$dados['ds_repasse']?></td></tr>
<tr><th>Data do Pagamento:&nbsp;</th><td><?=$dados['dt_pagamento']?></td></tr>
<tr><th>Data Recebimento Auditoria:&nbsp;</th><td><?=$dtrecebimento?></td></tr>
<tr><th>Data Devolução:&nbsp;</th><td><?=$dtdevolucao?></td></tr>
<tr><th>Data Aprovação:&nbsp;</th><td><?=$dtaprovacao?></td></tr>
<tr><th>Data Contabilidade:&nbsp;</th><td><?=$dtcontabilidade?></td></tr>
<tr><th>Data Entrega TC-28:&nbsp;</th><td><?=$dt_entrega_tc28?></td></tr>
</table>
<br><hr><br>
<table align="center">
<tr>
  <th colspan="4">
  <input type="button" name="btn_fechar" id="btn_fechar" value="FECHAR" class="botao" onclick="javascript:fechar()">
  </th>
</tr>
</table>
</form>
</fieldset>
</div>


<script type="text/javascript">

function fechar(){
	parent.globalWin.hide();
	parent.loadRecebimentos();
}

</script>
