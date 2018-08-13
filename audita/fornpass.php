<?php
/*
 * carrega a classe a ser instanciada quando chamada
 */
function __autoload($classe){
	$pontos = array("./", "../", "../../");
    foreach($pontos AS $ponto){
        if($classe == "FPDF") $diretorio = "fpdf/fpdf.php"; else $diretorio = "{$classe}.class.php";
		if(file_exists("{$ponto}class/{$diretorio}")){
			//echo "{$ponto}class/{$diretorio}\n";
			include_once "{$ponto}class/{$diretorio}";
		}
	}
}


$msg = null;
$cls = null;

if($_POST){
/**
 *  Criação do Hash do link de email
 */

//echo "<pre><br>post: "; print_r($_POST); echo "</pre>";
 
	$hash = formata::password_hash($_POST['password1'], 'md5') . formata::password_hash($_POST['password1'], 'crypt');
	//echo "<br>hash: ".$hash;
	//if(fornpass::getFornecedor($_POST['fornecededor_cnpj'], $_POST['ds_email1'])){
	//if(($_POST['ds_cnpj'])&&($_POST['ds_email1'])){
	if(fornpass::getFornecedor($_POST['fornecededor_cnpj'], $_POST['ds_email1'])){
		if($_POST['e'] == 1){
			if(fornpass::regPassFornecedor($_POST['password1'], $hash, $_POST['ds_email1'])){
				$msg = "Um email foi enviado para o endereço ".$_POST['ds_email1']." com a senha e o link de validação.";
				$cls = "acerto";
			}else{
				$msg = "Ocorreu algum erro ao enviar o email para o endereço ".$_POST['ds_email1'].". Tente novamente.";
				$cls = "erro";
			}
		}elseif($_POST['e'] == 2){
			if(fornpass::verPassFornecedor($_POST['fornecededor_cnpj'], $_POST['ds_email1'])){
				$msg = "Um email foi enviado para o endereço ".$_POST['ds_email1']." com a senha.";
				$cls = "acerto";
			}else{
				$msg = "Ocorreu algum erro ao enviar o email para o endereço ".$_POST['ds_email1'].". Tente novamente.";
				$cls = "erro";
			}
		}
			
	}else{
		$msg = "Fornecedor não encontrado. Verifique se o CNPJ ou Email estão corretos.";
		$cls = "erro";
	}
	//exit;
}?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CORPO DE BOMBEIROS MILITAR DE SANTA CATARINA - SISTEMA AUDITA COMPRAS</title>
<link href="css/audita.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/prototype.js"></script>
<script type="text/javascript" src="js/audita.js"></script>
<script type="text/javascript" src="js/validation.js"></script>
</head>
<script>
function validaForm(){
	
		var erro = "";
		
		if($('fornecededor_cnpj').value == ""){ 
			erro += "O campo CNPJ deve ser preenchido.\n";
		}
		if($('ds_email1').value == ""){
			erro += "O campo Email deve ser preenchido.\n";
		}
		<?php if($_GET['e'] == 1){?>
		if($('password1').value == $('password2').value){
			if($('password1').value.length < 5){
				erro += "A senha deve no minimo 5 digitos.\n";
			}			
		}else{
			erro += "As senhas digitadas possuem diferenças.\n";
		}
		<?php }?>
		if(erro != ""){
			alert("ERROS ENCONTRADOS:\r\n" + erro);
			return false;
		}else{
			return true;
		}

}

	function consultaCnpj(campo){ //alert(campo.value);
		if(campo.value != ""){
			var valor = campo.value;
			new Ajax.Request("./modulos/administrativo/cons_fornecedor.php", {
				method: "POST",
				parameters: "id="+campo.value,
				onLoading: Element.insert(campo.name, {after:"<div id='load_"+campo.name+"' class='loading'><img src='./imagens/loader.gif' width='14' height='14'/></div>"}),
				onSuccess: function(transport){
				//alert(transport.responseText); exit;
					var xmldoc = transport.responseXML;
					//Se existir registro
					if(xmldoc.getElementsByTagName('lib')[0].firstChild.data == 1) {
						//alert($('frm_fornecedor').length);
						for(i=0; i < $('frm_pass').length; i++){
							try{
								$('frm_pass')[i].value = xmldoc.getElementsByTagName($('frm_pass')[i].name)[0].firstChild.data;
							}catch(e){
								//alert(e);
							}
						}
					//Senão existir nenhum registro
					}
					//Terminou o carregamento retira a imagem de load
					Element.remove($('load_'+campo.name));
				}
			});
		}
	}

</script>
<body>
<form target="_self" enctype="multipart/form-data" method="post" name="frm_pass" id="frm_pass" onsubmit="return validaForm(); return false;" onreset="" action="">
	<input type="hidden" name="e" id="e" value="<?=$_GET['e']?>"/>
	<table cellspacing="0" cellpadding="0">
		<tr>
			<td colspan="2" id="top">
				<div>
					<h1><a href="index.php">CORPO DE BOMBEIROS MILITAR DE SANTA CATARINA<br>SISTEMA AUDITA COMPRAS</a></h1>
				</div>
			</td>
		</tr>
        <tr><td colspan="2">&nbsp;</td></tr>
		<tr>
            <td colspan="2" width="95%" align="center">
            <fieldset class="login">
                <label><b>NOVA SENHA FORNECEDOR</b></label>
                <hr/>
                <p><b>O fornecedor deve digitar o CNPJ e Email cadastrados no sistema.<br>
					<?php if($_GET['e'] == 1){?>
						Após a confirmação será enviado para o email a senha digitada.
					<?php }else{?>
						Após a confirmação será enviado para o email a senha atual do fornecedor.
					<?php }?>
				</b></p>
				<?php if($msg){?><p class="<?php echo $cls?>"><b><?php echo $msg?></b></p><?php }?>
                <table border="0" width="100%" align="center" class="">
               
                  <tr>
						<th>CPF/CNPJ:</th>
						<td><input type="text" name="ds_cnpj" id="ds_cnpj" value="" onblur="cpfcnpj(this),consultaCnpj(this)" class="required">&nbsp;&nbsp;(Somente Numeros)</td>
					</tr>
					<tr>
						<th>EMAIL:</th>
						<td><input type="text" name="ds_email1" id="ds_email1" value="" class="required" readonly="readonly"></td>
					</tr>
					<?php if($_GET['e'] == 1){?>
					<tr>
						<th>DIGITE A SENHA:</th>
						<td><input type="password" name="password1" id="password1" value="" class="required">&nbsp;&nbsp;(Números e Caracteres | Minimo 5 digitos)</td>
					</tr>
										<tr>
						<th>DIGITE A SENHA NOVAMENTE:</th>
						<td><input type="password" name="password2" id="password2" value="" class="required"></td>
					</tr>
					<?}?>
                </table>
                <p><input type="submit" name="btn_enviar" id="btn_enviar" class="botao" Value="Enviar"></p>
                <hr>
                <p align="center"><a href="index.php?acesso=2">INICIO DO AUDITA COMPRAS</a></p>
                <br>
            </fieldset>
            </td>
        <tr>
		<tr><td colspan="2">&nbsp;</td></tr>
</table>
</form>
<p align="center" id="pe">CBMSC</p>
</body>
</html>

