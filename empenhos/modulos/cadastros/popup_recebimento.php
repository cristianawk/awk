<?php
require "../../lib/loader.php";
//echo "<pre>"; print_r($_GET); echo "</pre>"; exit;

$id_matricula = $_GET['id_matricula'];
$id_perfil = $_GET['id_perfil'];

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


	if($dados['ch_enviar_email'] == 'S'){
		$S = "selected";
		$N = null;
	}else{
		$S = null;
		$N = "selected";
	}

    /*
     *  Data de Recebimento
     */

    if($dados['data_recebimento']!=""){
        $dtrecebimento = $dados['data_recebimento'];
        //"&nbsp;&nbsp;<a style='cursor: pointer' onclick='ativaCampo(\"cmp_recebimento\", \"bt_auditoria\")'><img id='bt_auditoria' src='../../imagens/buttonright.jpeg' height='14' width='14' title='Adicionar Nova Data Recebimento Auditoria'></a>&nbsp;&nbsp;<div id='cmp_recebimento' style='display:none'><input type='text'  name='dt_recebimento' id='dt_recebimento' value='' size='10' class='required' onkeyup='checadata(this)' onblur='verificarData(this, \"".$dados['dt_pagamento']."\", \"Recebimento da Auditoria\", \"do Pagamento\", true)' maxlength='10'>&nbsp;<input type='button' value='' style='background-image : url(\"../../imagens/img.gif\");' onclick='return showCalendar(\"dt_recebimento\", \"dd/mm/y\");'></div>";
        $flag_recebimento = true;
    }else{
        $dtrecebimento="<input type='text'  name='dt_recebimento' id='dt_recebimento' value='' size='10' onkeyup='checadata(this)' onblur='verificarData(this, \"".$dados['dt_pagamento']."\", \"Recebimento da Auditoria\", \"do Pagamento\", true)' maxlength='10'>&nbsp;<input type='button' value='' style='background-image : url(\"../../imagens/img.gif\");' onclick='return showCalendar(\"dt_recebimento\", \"dd/mm/y\");'>";

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
                $dtdevolucao="<input type='hidden' name='id_recebimento' id='id_recebimento' value='".$dados['id_recebimento']."'>
                        <input type='text'  name='dt_devolucao' id='dt_devolucao' value='' size='10' onkeyup='desabilitaCampo(this, \"dt_aprovacao\", \"btn_aprovacao\",\"data\")' onblur='verificarData(this, \"".$dados['data_recebimento']."\", \"Devolução\", \"do Recebimento da Auditoria\", true), desabilitaCampo(this, \"dt_aprovacao\", \"btn_aprovacao\",\"data\")' maxlength='10'>
                        &nbsp;<input name='btn_devolucao' id='btn_devolucao' type='button' value='' style='background-image : url(\"../../imagens/img.gif\");' onclick='return showCalendar(\"dt_devolucao\", \"dd/mm/y\");'>
                        &nbsp;&nbsp;<input type='file' id='arquivo' name='arquivo' size='30'>";

        }else{
               $dtdevolucao = '&nbsp;';
        }
    }else{
        $dtdevolucao = '&nbsp;';
        $flag_devolucao = false;
    }


    /*
     *  Data de Aprovação
     */

    if(($flag_recebimento == true)&&($dados['dt_aprovacao'] == "")){
        //se data de recebimento
        $dtaprovacao="<input type='text'  name='dt_aprovacao' id='dt_aprovacao' value='' size='10' onfocus='desabilitaCampo(this, \"dt_devolucao\", \"btn_devolucao\",\"data\")' onkeyup='desabilitaCampo(this, \"dt_devolucao\", \"btn_devolucao\",\"data\")' onblur='verificarData(this, \"".$dados['data_recebimento']."\", \"Data Aprovação\", \"Recebimento da Auditoria\", true)' maxlength='10'>&nbsp;
                        <input name='btn_aprovacao' id='btn_aprovacao' type='button' value='' style='background-image : url(\"../../imagens/img.gif\");' onclick='return showCalendar(\"dt_aprovacao\", \"dd/mm/y\");'>";
        $flag_aprovacao = false;
    }elseif($dados['dt_aprovacao'] != ""){
        $dtaprovacao = $dados['dt_aprovacao'];
        $flag_aprovacao = true;
    }else{
        $dtaprovacao = '&nbsp;';
        $flag_aprovacao = false;
    }

    /*
     *  Data de Contabilidade
     */

    if($dados['dt_contabilidade']!=""){
        $dtcontabilidade = $dados['dt_contabilidade'];
        $botao = false;
        $select = 'disabled';
    }elseif($flag_aprovacao){
        /*
         * Se o perfil for de administrador ou de contador mostra data contabilidade
         */
        if(($id_perfil == 1)||($id_perfil == 4)){
            $dtcontabilidade="<input type='text'  name='dt_contabilidade' id='dt_contabilidade' value='' size='10' onkeyup='checadata(this)' onblur='verificarData(this, \"".$dados['dt_aprovacao']."\", \"Data da Contabilidade\", \"da Data Aprovação\", true)' maxlength='10'>&nbsp;<input type='button' value='' style='background-image : url(\"../../imagens/img.gif\");' onclick='return showCalendar(\"dt_contabilidade\", \"dd/mm/y\");'>";
        }else{
            $dtcontabilidade = '&nbsp;';
        }
    } else { $dtcontabilidade = '&nbsp;'; }



if($dados['ch_tc28'] == 'S'){
    
    /*if($dados['dt_entrega_tc28'] != ""){
	
		$dt_entrega_tc28 = $dados['dt_entrega_tc28'];
	
	}else{*/
	
		if($botao){
			$dt_entrega_tc28 = "<input type='text'  name='dt_entrega_tc28' id='dt_entrega_tc28' value='{$dados['dt_entrega_tc28']}' size='10' onkeyup='checadata(this)' maxlength='10'>&nbsp;<input type='button' value='' style='background-image : url(\"../../imagens/img.gif\");' onclick='return showCalendar(\"dt_entrega_tc28\", \"dd/mm/y\");'>";
		}else{
			$dt_entrega_tc28 = '&nbsp;';
		}
	
	//}
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
<tr><th  colspan="2"><hr></th></tr>
<tr><th>Enviar email desse Empenho:&nbsp;</th>
	<td>
		<select name="ch_enviar_email" id="ch_enviar_email" <?=$select?>>
		<option value="S" <?=$S?>>SIM</option>
		<option value="N" <?=$N?>>NÃO</option>
		</select>
	</td>
</tr>
</table>
<br><hr><br>
<table align="center">
<tr>
  <th colspan="4">
<? if($botao){?>
<input type="hidden" id="id_matricula" name="id_matricula" value="<?=$id_matricula?>">
<input type="hidden" id="id_mtr_gestor" name="id_mtr_gestor" value="<?=$dados['id_mtr_gestor']?>">
<input type="hidden" id="nm_usuario" name="nm_usuario" value="<?=$dados['nm_usuario']?>">
<input type="hidden" id="nm_posto" name="nm_posto" value="<?=$dados['nm_posto']?>">
<input type="hidden" id="id_empenho" name="id_empenho" value="<?=$id_empenho?>">
<input type="hidden" id="ds_empenho" name="ds_empenho" value="<?=$ds_empenho?>">
<input type='hidden' id='ds_emailgestor' name='ds_emailgestor' value='<?=$ds_emailgestor?>'>
<input type='hidden' id='ds_emailcomandante' name='ds_emailcomandante' value='<?=$ds_emailcomandante?>'>
  <input type="submit" name="btn_incluir" id="btn_incluir" value="ATUALIZAR" class="botao">&nbsp;&nbsp;
  <? } ?>
  <input type="button" name="btn_fechar" id="btn_fechar" value="FECHAR" class="botao" onclick="javascript:fechar()">
    </th>
</tr>
</table>
</form>
</fieldset>
</div>


<script type="text/javascript">

    var frm_acao = new Validation('frm_acao');

    function submitForm(){

        var par = "";


        if(($('dt_devolucao') != null)||($('dt_devolucao') != undefined)){

            if(($F('dt_devolucao') != "")&&($F('arquivo') == "")){
                alert("Atenção! Ao preencher o campo da Data da Devolução, carregue o arquivo de devolução.");
                return true;
            }else if(($F('dt_devolucao') != "")&&($F('arquivo') != "")){
                par = 'arquivo='+$F('arquivo');
            }

        }

        if(frm_acao.validate()){

            $('frm_acao').request({
				method: 'post',
                parameters: par,
                onComplete: function(transport){
					//alert(transport.responseText); exit;
					var xmldoc=transport.responseXML;
					//alert(xmldoc);
					var flg = xmldoc.getElementsByTagName('flg')[0].firstChild.data;
					//alert(flg);
					if(flg == 1){
						loadMesg(xmldoc.getElementsByTagName('mesg')[0].firstChild.data, 'acerto', true, 1000);
                        //Caso exista arquivo executa a função UPLOAD
                        if(($F('dt_devolucao') != "")&&($F('arquivo') != "")) upload();
                        ///$('frm_acao').reset();
					}else{
						loadMesg(xmldoc.getElementsByTagName('mesg')[0].firstChild.data, 'erro', false, 3000);
					}
				}
			});

        }

		return false;
	}

    /*
     * Função que carrega o formulario para enviar um email com o anexo
     */

    function upload(){

        var form_upload = new Element("form", { id:"form_upload", name:"form_upload", method:"POST", enctype:"multipart/form-data", target:"iframe_upload", action:"./mail_recebimento.php", style:"display: none;"});
        //alert(form_upload);
        var iframe = new Element("iframe", {name:"iframe_upload", id:"iframe_upload", style:"display: none;"});


		form_upload.appendChild($('id_mtr_gestor'));
        form_upload.appendChild($('nm_usuario'));
        form_upload.appendChild($('nm_posto'));
        form_upload.appendChild($('ds_empenho'));
        form_upload.appendChild($('arquivo'));
        form_upload.appendChild($('ds_emailgestor'));
        form_upload.appendChild($('ds_emailcomandante'));
        form_upload.appendChild($('ch_enviar_email'));

        //insere na página o iframe
        document.body.insert(form_upload);
        document.body.insert(iframe);

        form_upload.submit();

    }

	function loadMesg(msg, optClass, b, t){
		//alert(msg);
		//alert($('mesg'));
		$('mesg').innerHTML = "<div class='"+optClass+"'>"+msg+"</div>";

		setTimeout(function(){ $('mesg').innerHTML = '&nbsp;'; if(b) fechar(); }, t);

	}

	function checadata(campo){
	  if(campo.value.length==2){
	    campo.value=campo.value +"/";
	  }
	  if(campo.value.length==5){
	    campo.value=campo.value +"/";
	  }
	}


function desabilitaCampo(campo, alvo, botao, tipo){

    if(tipo == "data") checadata(campo);

    if($(campo.name).value != ""){
        $(alvo).readOnly = true;
        $(botao).disabled = true;
        $(alvo).addClassName('disabled');
        $(botao).addClassName('disabled');

        if(alvo == "dt_devolucao"){
            $('arquivo').readOnly = true;
            $('arquivo').addClassName('disabled');
        }

    }else{
        $(alvo).readOnly = false;
        $(botao).disabled = false;
        $(alvo).removeClassName('disabled');
        $(botao).removeClassName('disabled');

        if(alvo == "dt_devolucao"){
            $('arquivo').readOnly = false;
            $('arquivo').removeClassName('disabled');
        }
    }

    return false;

}



function ativaCampo(campo, botao, arquivo){

    if($(campo).style.display == 'none'){
        $(botao).src = "../../imagens/buttonleft.jpeg";
        $(campo).show();
        if(arquivo != "") $(arquivo).show();
    }else{
        $(botao).src = "../../imagens/buttonright.jpeg";
        $(campo).hide();
        if(arquivo != "") $(arquivo).hide();
    }
}


function fechar(){
	parent.globalWin.hide();
	parent.loadRecebimentos();
}

</script>
