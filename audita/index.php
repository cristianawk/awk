<?php

//echo "audita/index.php";

/*
 * carrega a classe a ser instanciada quando chamada
 */
function __autoload($classe){
	$pontos = array("./", "../", "../../");
    foreach($pontos AS $ponto){
		if(file_exists("{$ponto}class/{$classe}.class.php")){
			//echo "{$ponto}class";
			// "{$classe}.class.php\n";
			$inclui = "{$ponto}class/{$classe}.class.php";
			//echo "<br>inclui: ".$inclui;
			include_once $inclui;
		}
	}
}
//classe de sessão
//Instancia a classe de sessão

new session;

/**
 * Inicia a verificação de login e senha
 */
if(isset($_POST['username']) && isset($_POST['password'])){
	/**
	 * Verifica se existe o usuario
	 */

	if(session::setLogin($_POST['username'], $_POST['password'])){
		/**
		 *  Caso exista o usuario seta os valor da sessao e o modulo
		 */
		//aqui ele vai fazer o login
        //vindo da pagina /templates/login.php
		session::setValue(
						array(
							'sessao' => true,
							'acesso' => $_GET['acesso'],
							'template' => 1
						)
					);
	}

}elseif(isset($_POST['fornecededor_cnpj']) && isset($_POST['fornecededor_empenho'])){
	//echo "<pre>"; print_r($_POST); echo "<pre>"; exit;
	/**
	 * Verifica se existe o usuario
	 */
	if(session::setEmpenho($_POST['fornecededor_cnpj'], $_POST['fornecededor_empenho'])){
		/**
		 *  Caso exista o usuario seta os valor da sessao e o modulo
		 */
		session::setValue(
						array(
							'sessao' => true,
							'acesso' => $_GET['acesso'],
							'template' => 2
						)
					);
	}

}

/*
 *  Sair do Sistema (Logout)
 */
if(isset($_GET['logout']) == 1){

	session::freeSession();

}

//Verifica se a sessão foi iniciada
$sessao = session::getValue('sessao');
//Verifica o modulo
//echo "<br>verifica sessao: ".$sessao;
$sessao_modulo = session::getValue('acesso');
//echo "<br>verifica modulo: ".$sessao_modulo;
/**
 * 	Verifica o template
 *  1 => Para Auditor, Fornecedor e Administrador
 *  2 => Fornecedor
 */
$sessao_template = session::getValue('template');
//echo "<br>verifica template: ".$sessao_template;

/*
 * Verifica o Perfil do Usuário
 *
 * 100 - Administrador
 * 200 - Auditoria - Operacional 1
 * 300 - Auditoria - Gerencial
 * 400 - Auditoria - Controle
 * 500 - Unidade Beneficiada
 * 600 - Fornecedores
 *
 */
$sessao_perfil = session::getValue('id_perfil');//echo "<br>verifica perfil: ".$sessao_perfil;
// Verifica a unidade beneficiada do Usuário
$sessao_unidade = session::getValue('id_unidade');//echo "<br>verifica unidade: ".$sessao_unidade;
// Verifica a identificação do Usuário
$sessao_usuario = session::getValue('id_usuario');//echo "<br>verifica usuario: ".$sessao_usuario;


//Classe de conexão com banco de dados
$conn = connection::init();

$sql = "SELECT * FROM modulos WHERE ch_ativacao = 'S' ORDER BY id_modulos";
if($conn->query($sql)){
	$modulos = $conn->get_tupla();
}else{
	$modulos = null;
}

//echo "modulos: <pre>"; print_r($modulos); echo "</pre>"; //exit;

if($_GET){
	//echo "acessos: ".$_GET['acesso'];
    $content = "./modulos/inicio.php";
	"./modulos/{$modulos[$acesso]['nm_diretorio']}/index.php";
}else{

	$content = "null";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CORPO DE BOMBEIROS MILITAR DE SANTA CATARINA - SISTEMA AUDITA COMPRAS</title>

<link href="css/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css" />
<link href="css/audita.css" rel="stylesheet" type="text/css" />
<link href="./css/themes/default.css" rel="stylesheet" type="text/css" >
<link href="./css/themes/alphacube.css" rel="stylesheet" type="text/css" >
<link type="text/css" href="css/mtg/calendar.css" rel="stylesheet">

<script type="text/javascript" src="js/prototype.js"></script>
<script type="text/javascript" src="js/scriptaculous.js"></script>
<script type="text/javascript" src="js/mtg/mytablegrid.js"></script>
<script type="text/javascript" src="js/dhtmlgoodies_calendar.js"></script>
<script type="text/javascript" src="js/validation.js"></script>
<script type="text/javascript" src="js/audita.js"></script>
<script type="text/javascript" src="js/window.js"> </script>
<script type="text/javascript" src="js/window_effects.js"> </script>

</head>
<body>
	<table cellspacing="0" cellpadding="0">
		<tr>
			<td colspan="2" id="top">
				<div>
					<h1><a href="index.php">CORPO DE BOMBEIROS MILITAR DE SANTA CATARINA<br>SISTEMA AUDITA COMPRAS</a></h1>
				</div>
			</td>
		</tr>
		<tr>
			<td valign="top" align="left">

			<?php
				if($modulos){
				echo "<div id='leftSide'>\n";
					foreach($modulos AS $id => $modulo){
					echo "<div id='{$modulo['nm_diretorio']}'>\n";
					echo "<a href='index.php?acesso={$id}'><h2>{$modulo['nm_modulo']}</h2></a>";
					echo "</div>\n";
					}
				echo "</div>\n";
			}?>
			</td>
			<td align="center" valign="top" id="content">
				<?php if(file_exists($content)) { include_once($content); } ?>
			</td>
		</tr>
		<tr><td colspan="2">&nbsp;</td></tr>
</table>
<p align="center" id="pe">CBMSC</p>
</body>
</html>
