<?php
//echo "<pre>"; print_r($_POST); echo "</pre>";
$obms = null;
$sql = "SELECT id_unidade, nm_unidade FROM ".TBL_UNIDADE." ORDER BY id_unidade";
$global_conn->query($sql);
while($tupla = $global_conn->fetch_row()) $obms[] = $tupla;

$perfis = null;
$sql = "SELECT id_perfil, nm_perfil FROM ".TBL_PERFIL." ORDER BY nm_perfil";
$global_conn->query($sql);
while($tupla = $global_conn->fetch_row()) $perfis[] = $tupla;

$bancos = null;
$sql = "SELECT id_banco, to_char(id_banco, '000') AS cod_banco, nm_banco FROM ".TBL_BANCOS." ORDER BY id_banco";
$global_conn->query($sql);
while($tupla = $global_conn->fetch_row()) $bancos[] = $tupla;

$postos = null;
$sql = "SELECT id_posto, nm_posto FROM ".TBL_POSTO." ORDER BY id_posto DESC";
$global_conn->query($sql);
while($tupla = $global_conn->fetch_row()) $postos[] = $tupla;


$filtro = "'".implode("', '",
        array('btn_incluir', 'op_menu', 'filtro', 'hdn_acao', 'btn_reset')
    )."'";

?>
<body>
<fieldset>
<legend>CADASTRO DE USUARIOS</legend>
<form target="_self" enctype="multipart/form-data" method="post" name="frm_usuario" id="frm_usuario" onsubmit="submitForm(); return false;" action="./modulos/cadastros/inc_usuarios.php">
<input type="hidden" name="op_menu" id="op_menu" value="<?=$id_load?>">
<input type="hidden" name="hdn_acao" id="hdn_acao" value="inc">
<table border="0" width="100%" cellspacing="0" cellpadding="4" class="orgTableBorder">
<tr><th colspan="2" id="mesg">&nbsp;</th></tr>
<tr>
	<th width="20%">MATRICULA:</th>
	<td><input type="text" name="id_mtr_usuario" id="id_mtr_usuario" value="" size="20" onblur="busca_usuario()" class="required"></td>
</tr>
<tr>
	<th>NOME:</th>
	<td><input type="text" name="nm_usuario" id="nm_usuario" value="" size="60" class="required"><hr/>
        <div>
            <a style='cursor: pointer' onclick='busca_usuario()'><img src='./imagens/buttonright.jpeg' height='20' width='20' title='Consultar Nome do Usuario'></a>&nbsp;&nbsp;
            Usuários Cadastrados no Sistema de Empenhos
        </div>
        <div>
            <a style='cursor: pointer' onclick='busca_usuario_ldap()'><img src='./imagens/buttonright.jpeg' height='20' width='20' title='Consultar Nome do Usuario no LDAP'></a>&nbsp;&nbsp;
            Usuários Cadastrados na base do LDAP (Utilizar só para Cadastros)
        </div>
    </td>
</tr>
<tr>
	<th>CPF:</th>
	<td><input type="text" name="nr_cpf_usuario" id="nr_cpf_usuario" value="" size="15" maxlength="15" class="required" onblur="cpfcnpj(this)">&nbsp(Somente Numeros)</td>
</tr>
<tr>
     <th>UNIDADE DO GESTOR:</th>
     <td>
        <select name="id_unidade" id="id_unidade" class="required" title="Unidade do Usuário">
        <option value="">-------------------------------------</option>
        <? foreach($obms AS $obm){ ?>
        <option value="<?=$obm['id_unidade']?>"><?=$obm['nm_unidade']?></option>
        <? } ?>
        </select>
    </td>
</tr>
<tr>
     <th>POSTO / GRADUAÇÃO:</th>
     <td>
        <select name="id_posto" id="id_posto" class="required" title="Graduação do Usuário">
        <option value="">-----------------------------------------</option>
        <? foreach($postos AS $posto){ ?>
        <option value="<?=$posto['id_posto']?>"><?=$posto['nm_posto']?></option>
        <? } ?>
        </select>
    </td>
</tr>
<tr>
	<th>EMAIL:</th>
	<td><input type="text" name="ds_email_usuario" id="ds_email_usuario" value="" size="60" class="required" onblur="validaemail(this)"></td>
</tr>
<tr>
	<th>TELEFONE:</th>
	<td><input type="text" name="nr_telefone" id="nr_telefone" class="required" onkeyup="MascaraTelefone(this)" onblur="ValidarTelefone(this)" maxlength="13">&nbsp;(00)0000-0000</td>
</tr>
<tr>
	<th>CELULAR:</th>
	<td><input type="text" name="nr_celular" id="nr_celular" value="" class="required" onkeyup="MascaraTelefone(this)" onblur="ValidarTelefone(this)" maxlength="13">&nbsp;(00)0000-0000</td>
</tr>
</tr>
     <th>BANCO:</th>
     <td>
        <select name="id_banco" id="id_banco" title="Banco do Usuário" onchange="liberaBanco()">
        <option value="">----------------------------------------------</option>
        <? foreach($bancos AS $banco){ ?>
        <option value="<?=$banco['id_banco']?>"><?=$banco['cod_banco']?> - <?=$banco['nm_banco']?></option>
        <? } ?>
        </select>
    </td>
<tr>
<tr>
	<th>AGÊNCIA:</th>
	<td><input type="text" name="ds_agencia" id="ds_agencia" value="" size="6" maxlength="6" onblur="validaBanco(this, '6')">&nbsp;&nbsp;&nbsp;<input type="text" name="ds_agencia_digito" id="ds_agencia_digito" value="" size="1" maxlength="1" onkeyup="">&nbsp;&nbsp;<i id="dv_agencia">Dígito Verificador</i></td>
</tr>
<tr>
	<th>CONTA:</th>
	<td><input type="text" name="ds_conta" id="ds_conta" value="" size="10" maxlength="10" onblur="validaBanco(this, '10')">&nbsp;&nbsp;&nbsp;<input type="text" name="ds_conta_digito" id="ds_conta_digito" value="" size="1" maxlength="1" onkeyup="">&nbsp;&nbsp;<i id="dv_conta">Dígito Verificador</i></td>
</tr>
<tr><th colspan="3"><hr /></th></tr>
<tr>
	<th>LOGIN:</th>
	<td><input type="text" name="nm_login" id="nm_login" value="" size="12" class="required" onblur=""></td>
</tr>
</tr>
     <th>PERFIL:</th>
     <td>
        <select name="id_perfil" id="id_perfil" class="required" title="Perfil do Usuário">
        <option value="">-------------------------------------</option>
        <? foreach($perfis AS $perfil){ ?>
        <option value="<?=$perfil['id_perfil']?>"><?=$perfil['nm_perfil']?></option>
        <? } ?>
        </select>
    </td>
<tr>
</table>
<br><hr/><br>
<table>
<tr>
    <th colspan="4">
        <input type="submit" name="btn_incluir" id="btn_incluir" value="CADASTRAR" class="botao">
        <input type="button" name="btn_reset" id="btn_reset" value="LIMPAR" class="botao" onclick="RESET()">
    </th>
</tr>
</table>
</form>
</fieldset>
</body>
<script type="text/javascript">

    document.observe("dom:loaded", liberaBanco());

    //Variavel para janela
    var globalWin = "";
    var largura = window.innerWidth - ((window.innerWidth / 100) * 20);
    var altura = window.innerHeight - ((window.innerHeight / 100) * 20);

	var frm_usuario = new Validation('frm_usuario');

	function submitForm(){

		if(frm_usuario.validate()){
			$('frm_usuario').request({
				method: 'get',
				parameters: { 'filtro[]':[<?=$filtro?>] },
				onComplete: function(transport){
					//alert(transport.responseText); exit;
					var xmldoc=transport.responseXML;
					//alert(xmldoc);
					var flg = xmldoc.getElementsByTagName('flg')[0].firstChild.data;
					//alert(flg);
					if(flg == 1){
						loadMesg(xmldoc.getElementsByTagName('mesg')[0].firstChild.data, 'acerto');
						RESET();
					}else{
						loadMesg(xmldoc.getElementsByTagName('mesg')[0].firstChild.data, 'erro');
					}
				}
			});
		}

		return false;
	}

	function loadMesg(msg, optClass){

		$('mesg').innerHTML = "<div class='"+optClass+"'>"+msg+"</div>"

		location.href='index.php#';

		setTimeout(function(){ $('mesg').innerHTML = '&nbsp;' }, 8000);

	}


    function busca_usuario(){

        if(globalWin != ""){ globalWin.destroy(); }//alert('entra aqui');

        //if(($('id_mtr_usuario').value != "")||($('nm_usuario').value != "")){

            globalWin = new Window("usuario", {
                className: "alphacube",
                width:largura,
                height:altura,
                title:"Pesquisa de Gestores",
                url: "./modulos/cadastros/cons_usuarios.php?id_mtr_usuario="+$('id_mtr_usuario').value+"&nm_usuario="+$('nm_usuario').value,
                showEffectOptions: {duration:0.1},
                //destroyOnClose: true,
                minimizable: false
            });

            globalWin.showCenter();
        //}
    }


    function busca_usuario_ldap(){

        if(globalWin != ""){ globalWin.destroy(); } //alert('entra aqui');

        if($('nm_usuario').value != ""){

            globalWin = new Window("usuario", {
                className: "alphacube",
                width:largura,
                height:altura,
                title:"Pesquisa de Usuários do LDAP",
                url: "./modulos/cadastros/cons_ldap_usuarios.php?nm_usuario="+$('nm_usuario').value,
                showEffectOptions: {duration:0.1},
                //destroyOnClose: true,
                minimizable: false
            });

            globalWin.showCenter();
        }
    }

    function RESET(){
        frm_usuario.reset();
        $('frm_usuario').reset();
        $('hdn_acao').value = 'inc';
        $('btn_incluir').value = 'CADASTRAR';
    }


    function liberaBanco(){

        if($('id_banco').value == ""){
            $('ds_agencia').readOnly = true;
            $('ds_conta').readOnly = true;
            $('ds_agencia_digito').readOnly = true;
            $('ds_conta_digito').readOnly = true;
            $('ds_agencia').value = "";
            $('ds_conta').value = "";
            $('ds_agencia_digito').value = "";
            $('ds_conta_digito').value = "";
            $('ds_agencia').addClassName('neutro');
            $('ds_conta').addClassName('neutro');
            $('ds_agencia_digito').addClassName('neutro');
            $('ds_conta_digito').addClassName('neutro');
            $('dv_agencia').addClassName('neutro');
            $('dv_conta').addClassName('neutro');
        }else{
            $('ds_agencia').readOnly = false;
            $('ds_conta').readOnly = false;
            $('ds_agencia_digito').readOnly = false;
            $('ds_conta_digito').readOnly = false;
            $('ds_agencia').removeClassName('neutro');
            $('ds_conta').removeClassName('neutro');
            $('ds_agencia_digito').removeClassName('neutro');
            $('ds_conta_digito').removeClassName('neutro');
            $('dv_agencia').removeClassName('neutro');
            $('dv_conta').removeClassName('neutro');

        }

    }

</script>
