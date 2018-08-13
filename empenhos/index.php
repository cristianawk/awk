<?
//echo "<pre>"; print_r($_POST); echo "</pre>"; //exit;
require "lib/loader.php";

$id_load = 0;

$msg_erro = "";

if($_POST){

    if($_POST['login'] && $_POST['senha']){

        $login = trim($_POST['login']);
        $senha = trim($_POST['senha']);

		$ldap = checkldapuser($login, $senha, LDAP_SERVIDOR);
		//echo $ldap; exit;
		if($ldap){
            $global_obj_sessao->authenticate($login, $senha, 1);
		}else{
            $global_obj_sessao->err_msg_ses = "O SERVIDOR DE LDAP NÃO CONSEGUIU AUTENTICAR SEU LOGIN E SENHA.";
        }

        if($global_obj_sessao->started()){
            $modulo = "./modulos/inicio.php";
        }else{
            $modulo = "login.php";
        }

    }elseif($_POST['op_menu']){
        //echo "<pre>"; print_r($_POST); echo "</pre>"; //exit;
        //echo $_POST['op_menu']; exit;

        switch ($_POST['op_menu']) {

            case '1'     : $modulo = "./modulos/inicio.php";                    $id_load = 1;       break;

            case '2'     : $modulo = "./modulos/cadastros/usuarios.php";        $id_load = 2;       break;

            case '3'     : $modulo = "./modulos/cadastros/empenhos.php";        $id_load = 3;       break;

            case '4'     : $modulo = "./modulos/cadastros/recebimento.php";     $id_load = 4;       break;

            case '5'     : $modulo = "./modulos/relatorios/gestores_atrazados.php";     $id_load = 5;       break;

            case '6'     : $modulo = "./modulos/relatorios/rel_res.php";        $id_load = 6;       break;

            case '7'     : $modulo = "./modulos/relatorios/gestores_liberados.php";  $id_load = 7;       break;

            case '8'     : $modulo = "./modulos/relatorios/rel_obm.php";	$id_load = 8;       break;

			case '9'     : $modulo = "./modulos/relatorios/empenhos_emitidos.php";	$id_load = 9;       break;

            case 'logout'           : $global_obj_sessao->logout(); $id_load = 0; http_redir('index.php');  break;

            default : $modulo = "./modulos/inicio.php"; $id_load = 1; break;

        }

        $global_obj_sessao->load($id_load);

    }elseif($_POST['logout']){

        $global_obj_sessao->logout();
        //header('Location: index.php');
        http_redir('index.php');

    }else{

        $modulo = "login.php";

    }

}elseif($_GET){
    if($_GET['erro'] != ""){
        $msg_erro = "../../erro/erro.php?e=".$_GET['erro'];
        $modulo = "./modulos/inicio.php";
        $id_load = 1;
    }

    //echo "ERRO: $msg_erro";

    $global_obj_sessao->load($id_load);

}else{

    $modulo = "login.php";

}

/**
 *  Se a Sessão iniciou, carrega as variaveis do sistema
 */

if($global_obj_sessao->started()){

    $login_usuario      = $global_obj_sessao->is_logged_in();
    $matricula_usuario  = $global_obj_sessao->is_mtr_in();
    $perfil_usuario     = $global_obj_sessao->is_perfiled_in();
    $nome_usuario       = $global_obj_sessao->is_user_in();

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    <script language="JavaScript" type="text/javascript" src="js/prototype.js"></script>
    <script type="text/javascript" src="js/effects.js"></script>
    <script type="text/javascript" src="js/fabtabulous.js"></script>
	<script type="text/javascript" src="js/validation.js"></script>
	<script type="text/javascript" src="js/window.js"> </script>
	<script type="text/javascript" src="js/window_effects.js"> </script>
    <script type="text/javascript" src="js/jscalendar-0.9.3/calendar.js"></script>
    <script type="text/javascript" src="js/jscalendar-0.9.3/lang/calendar-pt.js"></script>        <!--// escolher idioma (assim está brasileiro)     -->
    <script type="text/javascript" src="js/dlf.js"> </script>
    <link href="./css/themes/default.css" rel="stylesheet" type="text/css" >
	<link href="./css/themes/alphacube.css" rel="stylesheet" type="text/css" >
    <link rel="stylesheet" type="text/css" href="./css/menu.css">
    <link rel="stylesheet" type="text/css" href="./css/dlf.css">
    <link rel="stylesheet" type="text/css" media="all" href="js/jscalendar-0.9.3/calendar-blue.css" />
    <title>EMPENHOS</title>
</head>
<body>
<table align="center" width="90%" border="0">
    <tr>
        <td>
            <? require './templates/cabecalho.php'; ?>
            <? if($global_obj_sessao->started()){ require './templates/menu.php'; } ?>
        </td>
    </tr>
    <tr>
		<td>
			<? if($global_obj_sessao->started()) { ?>
			<table width="99%" align="center" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td align="right">
						<?=ucwords(strtolower($nome_usuario));?>&nbsp;|&nbsp;<a href="javascript:submeter('logout');" title="Sair do Sistema">Sair</a>
					</td>
                </tr>
			</table>
			<? } ?>
			</td>
		</tr>
    <tr>
        <td align="center" height="400" valign="top">
            <? if ($modulo) require $modulo; ?>
        </td>
    </tr>
    <tr><td>&nbsp;</td></tr>
        <tr>
            <td align="center">
        <? require './templates/rodape.php'; ?>
        </td>
        </tr>
</table>
</body>
</html>
