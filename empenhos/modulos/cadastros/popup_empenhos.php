<?php
require "../../lib/loader.php";

//echo "<pre>"; print_r($_GET); echo "</pre>"; exit;

if($_GET){

$ds_empenho = strtolower(formataCampo($_GET['ds_empenho']));

	if($ds_empenho){

		$sql = "SELECT a.id_empenho, UPPER(ds_empenho) AS ds_empenho,
			to_char(a.dt_empenho, 'DD/MM/YYYY') AS dt_empenho,
			to_char(dt_pagamento, 'DD/MM/YYYY') AS dt_pagamento,
			to_char(nr_orcamentario, '000000') AS nr_orcamentario,
			to_char(nr_subacao, '0000') AS nr_subacao,
			to_char(nr_despesa, '00000000') AS nr_despesa,
			to_char(nr_fonte_recursos, '0000') AS nr_fonte_recursos,
			id_mtr_gestor, b.nm_usuario, b.ds_email_usuario, b.nr_cpf_usuario,
			ds_email_comandante, ds_repasse, nm_posto, nm_unidade
			FROM ".TBL_EMPENHO." AS a
			JOIN ".TBL_USUARIO." AS b ON (a.id_mtr_gestor=b.id_mtr_usuario)
			JOIN ".TBL_TIPO_SITUACAO." AS e USING (cd_situacao_empenho)
			JOIN ".TBL_POSTO." AS c USING (id_posto)
			JOIN ".TBL_UNIDADE." AS d USING (id_unidade)
			WHERE ch_visualizar = 'S' AND UPPER(a.ds_empenho) = ".strtoupper($ds_empenho);


		//echo $sql;  exit;
		$global_conn->query($sql);
		while($tupla = $global_conn->fetch_row()) $dados = $tupla;

		//echo "<pre>"; print_r($dados); echo "</pre>";
		//exit;
	}
}
?>
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/dlf.js"> </script>
<script type="text/javascript" src="../../js/window.js"> </script>
<script type="text/javascript" src="../../js/window_effects.js"> </script>
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css" >
<link href="../../css/themes/alphacube.css" rel="stylesheet" type="text/css" >
<link rel="stylesheet" type="text/css" href="../../css/menu.css">
<link rel="stylesheet" type="text/css" href="../../css/dlf.css">
<p id="mesg" align="center"></p>
<div id="field">
<fieldset>
<form target="_self" name="frm_acao" id="frm_acao" action="./alt_empenho.php" onsubmit="submitForm(); return false;" method="POST" enctype="multipart/form-data">
<? if($dados){ ?>
<input type="hidden" name="id_empenho" id="id_empenho" value="<?=$dados['id_empenho']?>">
<input type="hidden" name="ds_empenho" id="ds_empenho" value="<?=$dados['ds_empenho']?>">
<table border="0" cellspacing="2" cellpadding="6" align="center" class="orgTableBorder" width="100%">
<tr><th width="25%">Matricula / Nome do Gestor:&nbsp;</th><td><?=$dados['id_mtr_gestor']?> - <?=$dados['nm_usuario']?></td></tr>
<tr><th>CPF Gestor:&nbsp;</th><td><?=$dados['nr_cpf_usuario']?></td></tr>
<tr><th>Posto:&nbsp;</th><td><?=$dados['nm_posto']?></td></tr>
<tr><th>Unidade:&nbsp;</th><td><?=$dados['nm_unidade']?></td></tr>
<tr><th>Email Gestor:&nbsp;</th><td><?=$dados['ds_email_usuario']?></td></tr>
<tr><th>Email Comandante:&nbsp;</th><td><?=$dados['ds_email_comandante']?></td></tr>
<tr><th>N. Empenho:&nbsp;</th><td><?=$dados['ds_empenho']?></td></tr>
<tr><th>Unidade gestora:&nbsp;</th><td><?=$dados['nr_orcamentario']?></td></tr>
<tr><th>Subação:&nbsp;</th><td><?=$dados['nr_subacao']?></td></tr>
<tr><th>Natureza da Despesa:&nbsp;</th><td><?=$dados['nr_despesa']?></td></tr>
<tr><th>Fonte do Recurso:&nbsp;</th><td><?=$dados['nr_fonte_recursos']?></td></tr>
<tr><th>Data do Empenho:&nbsp;</th><td><?=$dados['dt_empenho']?></td></tr>
<tr><th>Data do Pagamento:&nbsp;</th><td><?=$dados['dt_pagamento']?></td></tr>
<tr><th>Valor Repassado:&nbsp;</th><td><?=$dados['ds_repasse']?></td></tr>
</table>
<? }else{ ?>
<br><hr><br>
<p class="erro">Nenhum Empenho Encontrado</p>
<? } ?>
<br><hr><br>
<table align="center">
<tr>
  <th colspan="4">
	<? if($dados){
		if($_GET['v'] == 1){ ?>
	<input type="button" name="btn_editar" id="btn_editar" value="EDITAR" class="botao" onclick="carregar()">
	<input type="button" name="btn_excluir" id="btn_excluir" value="EXCLUIR" class="botao" onclick="excluir()">
	<? } } ?>
	<input type="button" name="btn_fechar" id="btn_fechar" value="FECHAR" class="botao" onclick="fechar()">
   </th>
</tr>
</table>
</form>
</fieldset>
</div>
<script type="text/javascript">

function submitForm(){

		$('frm_acao').request({
				method: 'post',
				onComplete: function(transport){
					//alert(transport.responseText); exit;
					var xmldoc=transport.responseXML;
					var flg = xmldoc.getElementsByTagName('flg')[0].firstChild.data;
					if(flg == 1){
						parent.loadMesg(xmldoc.getElementsByTagName('mesg')[0].firstChild.data, 'acerto');
						parent.$('frm_empenho').reset();
					}else{
						parent.loadMesg(xmldoc.getElementsByTagName('mesg')[0].firstChild.data, 'erro');
					}
				}
			});


		return false;
}




function excluir(){

	var d = Dialog.confirm("Deseja realmente excluir o empenho <?=strtoupper(str_replace("'", " ", $ds_empenho))?> do sistema de empenhos?", {
                            className: "alphacube",
                            id: "Dialogo",
                            buttonClass: "ButtonClass",
                            title:"AVISO",
                            width:480,
                            height:80,
                            showEffectOptions: {duration:0.1},
                            destroyOnClose: true,
                            okLabel: "SIM",
                            cancelLabel: "NÃO",
                            ok: function(){
									fechar();
									submitForm();
                                    d.hide();
                                    d.destroy();
                                    return true;
                                },
                            cancel: function(){
                                    d.hide();
                                    d.destroy();
                                    return true;
                                }
                        });


}


function carregar(){

	<?if($dados){ foreach($dados AS $key => $value){
		if($key == "ds_repasse"){ ?>
			parent.$('<?=$key?>').value = "<?=trim(substr($value, 2))?>";
		<? }else{ ?>
			parent.$('<?=$key?>').value = "<?=trim($value)?>";
		<? } ?>
	<? } ?>
		parent.$('btn_incluir').value = "ALTERAR";
	<? } ?>
	fechar();

}


function fechar(){
	parent.globalWin.hide();
}

</script>
