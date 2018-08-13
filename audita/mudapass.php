<?php
/*
 * carrega a classe a ser instanciada quando chamada
 */
function __autoload($classe)
{
    $pontos = array("./", "../", "../../");
    foreach ($pontos AS $ponto) {
        if ($classe == "FPDF") $diretorio = "fpdf/fpdf.php"; else $diretorio = "{$classe}.class.php";
        if (file_exists("{$ponto}class/{$diretorio}")) {
            //echo "{$ponto}class/{$diretorio}\n";
            include_once "{$ponto}class/{$diretorio}";
        }
    }
}


$msg = null;
$cls = null;

if ($_POST) {
    /**
     *  Criacao do Hash do link de email
     */
    $hash = formata::password_hash($_POST['usuario_cpf'], 'md5') . formata::password_hash($_POST['usuario_cpf'], 'crypt');
    if (mudapass::getUsuario($_POST['usuario_cpf'], $_POST['email_cpf'])) {
        if ($_POST['e'] == 1) {
            if (mudapass::regPassUsuario($_POST['password1'], $hash, $_POST['email_cpf'])) {
                $msg = "Um email foi enviado para o endereço " . $_POST['email_cpf'] . " com a senha e o link de validação.";
                $cls = "acerto";
            } else {
                $msg = "Ocorreu algum erro ao enviar o email para o endereço " . $_POST['email_cpf'] . ". Tente novamente.";
                $cls = "erro";
            }
        } elseif ($_POST['e'] == 2) {
            if (mudapass::verPassUsuario($_POST['usuario_cpf'], $_POST['email_cpf'])) {
                $msg = "Um email foi enviado para o endereço " . $_POST['email_cpf'] . " com a senha.";
                $cls = "acerto";
            } else {
                $msg = "Ocorreu algum erro ao enviar o email para o endereço " . $_POST['email_cpf'] . ". Tente novamente.";
                $cls = "erro";
            }
        }

    } else {
        $msg = "Usuário não encontrado. Verifique se o CPF ou Email estão corretos.";
        $cls = "erro";
    }
    //exit;
} ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
    <title>CORPO DE BOMBEIROS MILITAR DE SANTA CATARINA - SISTEMA AUDITA COMPRAS</title>
    <link href="css/audita.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="js/prototype.js"></script>
    <script type="text/javascript" src="js/audita.js"></script>
    <script type="text/javascript" src="js/validation.js"></script>
</head>
<script>
    function validaForm() {

        //alert("AQUI");
        var erro = "";

        if ($('usuario_cpf').value == "") {
            erro += "O campo CPF deve ser preenchido.\n";
        }
        if ($('email_cpf').value == "") {
            erro += "O campo Email deve ser preenchido.\n";
        }
        <?php if($_GET['e'] == 1){?>
        if ($('password1').value == $('password2').value) {
            if ($('password1').value.length < 5) {
                erro += "A senha deve no minimo 5 digitos.\n";
            }
        } else {
            erro += "As senhas digitadas possuem diferenças.\n";
        }
        <?php }?>
        if (erro != "") {
            alert("ERROS ENCONTRADOS:\r\n" + erro);
            return false;
        } else {
            return true;
        }


    }
</script>

<body>
<form target="_self" enctype="multipart/form-data" method="post" name="frm_pass" id="frm_pass"
      onsubmit="return validaForm(); return false;" onreset="" action="">
    <input type="hidden" name="e" id="e" value="<?php echo $_GET['e'] ?>"/>
    <table cellspacing="0" cellpadding="0">
        <tr>
            <td colspan="2" id="top">
                <div>
                    <h1><a href="index.php">CORPO DE BOMBEIROS MILITAR DE SANTA CATARINA<br>SISTEMA AUDITA COMPRAS</a>
                    </h1>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" width="95%" align="center">
                <fieldset class="login">
                    <label><b>ALTERAÇÃO DE SENHA</b></label>
                    <hr/>
                    <p><b>Deve ser digitado o CPF do Usuário e Email cadastrados no sistema.<br>
                            <?php if ($_GET['e'] == 1) { ?>
                                Após a confirmação será enviado para o email a senha digitada.
                            <?php } else { ?>
                                Após a confirmação será enviado para o email a senha atual do usuário.
                            <?php } ?>
                        </b></p>
                    <?php if ($msg) { ?><p class="<?php echo $cls ?>"><b><?php echo $msg ?></b></p><?php } ?>
                    <table border="0" width="100%" align="center" class="">

                        <tr>
                            <th>CPF/CNPJ:</th>
                            <td><input type="text" name="usuario_cpf" id="usuario_cpf" value="" onblur="cpfcnpj(this)"
                                       class="required">&nbsp;&nbsp;(Somente Numeros)
                            </td>
                        </tr>
                        <tr>
                            <th>EMAIL:</th>
                            <td><input type="text" name="email_cpf" id="email_cpf" value="" class="required"></td>
                        </tr>
                        <?php if ($_GET['e'] == 1) { ?>
                            <tr>
                                <th>DIGITE A SENHA:</th>
                                <td><input type="password" name="password1" id="password1" value="" class="required">&nbsp;&nbsp;(Números
                                    e Caracteres | Minimo 5 digitos)
                                </td>
                            </tr>
                            <tr>
                                <th>DIGITE A SENHA NOVAMENTE:</th>
                                <td><input type="password" name="password2" id="password2" value="" class="required">
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                    <p><input type="submit" name="btn_enviar" id="btn_enviar" class="botao" Value="Enviar"></p>
                    <hr>
                    <p align="center"><a href="index.php?acesso=0">INICIO DO AUDITA COMPRAS</a></p>
                    <br>
                </fieldset>
            </td>
        <tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
    </table>
</form>
<p align="center" id="pe">CBMSC</p>
</body>
</html>

