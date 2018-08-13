<?php

/**
 * Corpo de Bombeiros Militar de Santa Catarina
 *
 * Projeto Sigat Sistema de Gerenciamento de Atividades Tecnicas
 *
 * @categoria  Miscelania
 * @pacote     Bra
 * @autor      Edson Orivaldo Lessa Junior <edsonagem@cb.sc.gov.br>
 * @creditos   Agem Informatica
 * @versao     1.0
 * @data       05/04/2005 as 08:54:36
 * @atualiza   19/11/2005 as 22:11:55
 * @arquivo    lib/misc/bra.php
 */

function bra_dump_var ($var_name) {
    ob_start ();
    var_dump ($var_name);
    $dump_data = ob_get_contents ();
    ob_end_clean ();
    return $dump_data;
}

function bra_get_phpinfo () {
    ob_start ();
    phpinfo ();
    $dump_data = ob_get_contents ();
    ob_end_clean ();
    return $dump_data;
}

function bra_send_msg ($str_msg) {
    if (BRA_ATIVADO) {
        $md5sum = md5 ($str_msg);

       /**
        * Obtem as variaveis importantes para anexar
        */
        $dump_env     = bra_dump_var ($_ENV);
        $dump_server  = bra_dump_var ($_SERVER);
        $dump_session = bra_dump_var ($_SESSION);
        $dump_get     = bra_dump_var ($_GET);
        $dump_post    = bra_dump_var ($_POST);
        $php_info     = bra_get_phpinfo ();
        $md5          = md5($str_msg);

       /**
        * Constroi a mensagem de aviso
        */
        $msg = BRA_TEMPLATE;
        $msg = str_replace ('{SISTEMA}'    ,SISTEMA_NOME            ,$msg);
        $msg = str_replace ('{VERSAO}'     ,SISTEMA_VERSAO          ,$msg);
        $msg = str_replace ('{STATUS}'     ,SISTEMA_STATUS          ,$msg);
        $msg = str_replace ('{HOST}'       ,$_SERVER["HTTP_HOST"]   ,$msg);
        $msg = str_replace ('{SCRIPT}'     ,$_SERVER["SCRIPT_NAME"] ,$msg);
        $msg = str_replace ('{MD5}'        ,$md5                    ,$msg);
        $msg = str_replace ('{HORA}'       ,date ('Y-m-d H:i:s')    ,$msg);
        $msg = str_replace ('{ERRO}'       ,$str_msg                ,$msg);
        $msg = str_replace ('{_ENV}'       ,$dump_env               ,$msg);
        $msg = str_replace ('{_SERVER}'    ,$dump_server            ,$msg);
        $msg = str_replace ('{_SESSION}'   ,$dump_session           ,$msg);
        $msg = str_replace ('{_GET}'       ,$dump_get               ,$msg);
        $msg = str_replace ('{_POST}'      ,$dump_post              ,$msg);
        $msg = str_replace ('{PHPINFO}'    ,$php_info               ,$msg);

        // Nome do arquivo para anexar
        $filename = "phpinfo_$md5.html";

        // Unique boundary
        $boundary = uniqid(md5(rand()));

        // Assunto e remetente...
        $subject = '[BRA] ' . SISTEMA_NOME . ' ' . SISTEMA_VERSAO . '-' . SISTEMA_STATUS . " [$md5sum]";
        $headers = "From: Bug Report Agent <".BRA_DESTINO.">\n";

        // Prepara a mensagem formato MIME
        $headers .= "MIME-Version: 1.0\n";

        // Telefone, e-mail
        $headers .= "Content-Type: multipart/mixed;\n" .
                "\tboundary=\"$boundary\"\n\n";

        //$headers .= "This is a multi-part message in MIME format.";

        // Vamos salvar a parte de texto da mensagem
        $mime_msg = "--$boundary\n" .
                "Content-Type: text/plain;\n" .
                        "\tcharset=\"iso-8859-1\"\n" .
                //"Content-Transfer-Encoding: quoted-printable\n";
                "Content-Transfer-Encoding: 8bit\n";
                //"Content-Transfer-Encoding: base64\n";
        $mime_msg .= "\n$msg\n\n";

        // vamos anexar o arquivo com a saida do PHPINFO.
        $mime_msg .= "--$boundary\n" .
                "Content-Type: text/plain;\n" .
                "\tname=\"$filename\"\n".
                "Content-Transfer-Encoding: 8bit\n" .
                "Content-Disposition: attachment;\n" .
                        "\tfilename=\"$filename\"\n\n";
        $mime_msg .= "\n$php_info";
        $mime_msg .= "\n\n--".$boundary.'--';

        // Envia a mensagem para sigatreport
        return mail (BRA_DESTINO, $subject, $mime_msg, $headers);
    }
    return false;
}

?>
