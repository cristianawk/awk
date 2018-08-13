<?php

    //echo "<pre>"; print_r($_FILES); echo "</pre>"; exit;
    //echo "<pre>"; print_r($_POST); echo "</pre>"; exit;

    // Chama a classe PHPMailer (pode baixar ela aqui: http://phpmailer.sourceforge.net)
    include "phpmailer/class.phpmailer.php";

    $devolucao 		= $_POST['dt_devolucao'];
    $destinatario	= strtolower($_POST['ds_emailgestor']);
    $ds_emailcomandante	= strtolower($_POST['ds_emailcomandante']);

	$ds_empenho = $_POST['ds_empenho'];
    $id_mtr_gestor = $_POST['id_mtr_gestor'];
    $nm_usuario = $_POST['nm_usuario'];
    $nm_posto = $_POST['nm_posto'];
    
    $ch_enviar_email = $_POST['ch_enviar_email'];


    if($ch_enviar_email == "S"){
    // Instancia o objeto $mail a partir da Classe PHPMailer
    $mail = new PHPMailer();

    // Recupera os dados do formul�rio
    $nome       = " Dlf Sistema Controle de Prazos ";
    $email      = " dlf@cbm.sc.gov.br";
    $mensagem   = "<html>
					<head>
						<title>Controle de Prazos</title>
					</head>
					<body>
						<p>Senhor Cmt, Diretor ou Chefe, CC ao Gestor de adiantamento,</p>
						<p><b>A Diretoria de Log�stica e Finan�as do CBMSC alerta:</b>
						detectamos que o gestor ".ucwords(strtolower($nm_posto))." Mtcl ".$id_mtr_gestor." - ".ucwords(strtolower($nm_usuario))." encontra-se em atraso
						com a Presta��o de Contas dos recursos repassados atrav�s
						do Empenho N� ".$ds_empenho.", estando assim sujeito a penalidade de multa e san��es administrativas.
						</p>
					<p>Solicitamos provid�ncias urgentes, de forma a regularizar a situa��o o mais breve poss�vel.</p>
					<p>DIRETORIA DE LOG�STICA E FINAN�AS/CBMSC<br>
						Fone: (48)3271-1196 / (48)3271-1181</p>
					</body>
					</html>";

    $arquivo = $_FILES['arquivo'];
    // Recupera o nome do arquivo
    $arquivo_nome = $arquivo['name'];

    // Recupera o caminho temporario do arquivo no servidor
    $arquivo_caminho = $arquivo['tmp_name'];

    // Monta a mensagem que ser� enviada
    $corpo = "$mensagem";
    $corpoSimples = "$mensagem";

    // Informo o Host, FromName, From, subject e para quem o e-mail ser� enviado
    $mail->Host = 'correio.cbm.sc.gov.br';
    $mail->FromName = $nome;
    $mail->From = $email;
    $mail->Subject = 'Diretoria de Log�stica e Finan�as do CBMSC - Devolu��o do Empenho N� '.$ds_empenho;
    //$mail->AddAddress("phproger@gmail.com");
    //$mail->AddCC("rogerawk@cbm.sc.gov.br");
    $mail->AddAddress($ds_emailcomandante);
    $mail->AddCC($destinatario);
    if(EMAIL_ATIVACAO){ $mail->AddBCC(EMAIL_PRESTACAO); }

    // Informa que a mensagem deve ser enviada em HTML
    $mail->IsHTML(true);

    // Informa o corpo da mensagem
    $mail->Body = $corpo;

    // Se o e-mail destino n�o suportar HTML ele envia o texto simples
    $mail->AltBody = $corpoSimples;

    // Anexa o arquivo
    $mail->AddAttachment($arquivo_caminho, $arquivo_nome);

    $a = $mail->Send();

    // Tenta enviar o e-mail e analisa o resultado
    if ($a){
         echo 'E-mail enviado com sucesso';
    }else{
         echo 'Erro:' . $mail->ErrorInfo;
    }

    }
?>
