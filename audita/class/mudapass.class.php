<?php
/**
 * classe fornpass
 * Valida e envia um email coma a senha para o usuario
 */
class mudapass{
    /**
     * método construtor
     * 
     */
    public static $tupla;
    
    function __construct(){
        //session_start();
    }

    /**
     * método getUsuario()
     * Armazena uma variável na seção
     * @param  $cpf   = cpf do usuario
     * @param  $email   = email do usuario
     */
    function getUsuario($cnpj, $email){
		
	$sql = sprintf("SELECT * FROM usuarios WHERE ds_cpf_usuario = '%s' AND ds_email = '%s' LIMIT 1;", trim($cnpj), trim($email), trim($email));
        //echo "<br>sql 1: ".$sql;
		//inicia a conexão
        $c = connection::init();
		//executa a query
        $c->query($sql);
        
		//pega o numero de registro
        $n = $c->num_rows();
		//echo $n;
        if($n > 0){
			//pega o registro
			self::$tupla = $c->fetch_row();
			return true;

		}else{

			//Limpa a sessão
			//self::freeSession();
			return false;

		}

		connection::close();

    }

    /**
     * método regPassUsuario()
     * Armazena uma variável na seção
     * @param  $pass   = pass do fornecedor
     * @param  $hash
     */
    function regPassUsuario($pass, $hash, $email){
		
		$sql = sprintf("UPDATE usuarios SET ps_senha = '%s', cd_hash_senha = '%s' WHERE id_usuario ='".self::$tupla['id_usuario']."';", trim($pass), trim($hash));
        //echo "<br>sql 2: ".$sql; //exit;
		//inicia a conexão
        $c = connection::init();
		//executa a query
        if($c->query($sql)){
			//pega o registro
			$eml = self::enviar_email($pass, $hash, $email);
			return $eml;

		}else{
			//
			return false;
		}

		connection::close();

    }

    /**
     * método verPassUsuario()
     * Armazena uma variável na seção
     * @param  $pass   = pass do usuario
     * @param  $hash
     */
    function verPassUsuario($cnpj, $email){
		/**
		 *  Enviar o Email
		 *
		 */
		// Instancia o objeto $mail a partir da Classe PHPMailer
		$mail = new email();
		
		//echo "<pre>"; print_r($mail); echo "</pre>"; exit;
		/*
		 *  Busca o host para o link
		 */
		// Recupera os dados do formulário
		$mensagem  = "<table border=\"0\" width=\"80%\" align='center' style=\"
							font-family         : arial, sans-serif, verdana;
							font-size			: 14px;
							border-collapse		: collapse;
							border		        : 1px solid #ADD8E6;\">
						<tr>
							<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">CPF:</td>
							<td style=\"'border: 1px solid #ADD8E6;\">".self::$tupla['ds_cpf_usuario']."</td>
						</tr>
						<tr>
							<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">Usuario:</td>
							<td style=\"'border: 1px solid #ADD8E6;\">".self::$tupla['nm_usuario']."</td>
						</tr>
						<tr>
							<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">Senha:</td>
							<td style=\"'border: 1px solid #ADD8E6;\">".self::$tupla['ps_senha']."</td>
						</tr>
						<tr>
							<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">Data da Requisição:</td>
							<td style=\"'border: 1px solid #ADD8E6;\">".date('d/m/Y H:i:s')."</td>
						</tr>
					</table>
					<br>
					

				";

		// Monta a mensagem que será enviada
		$corpo = "$mensagem";
		$corpoSimples = "$mensagem";

		// Informo o Host, FromName, From, subject e para quem o e-mail será enviado
		//$mail->Host = 'correio.cbm.sc.gov.br';
		//$mail->FromName = $nome;
		//$mail->From = $email;
		$mail->Subject = 'SENHA DO USUÁRIO';
		//$mail->AddAddress("cristian@awktec.com");
		//$mail->AddCC("cristianawk@cbm.sc.gov.br");
		$mail->AddAddress($email);
		//$mail->AddCC(self::$tupla['ds_email2']);

		// Informa que a mensagem deve ser enviada em HTML
		$mail->IsHTML(true);

		// Informa o corpo da mensagem
		$mail->Body = $corpo;

		// Se o e-mail destino não suportar HTML ele envia o texto simples
		$mail->AltBody = $corpoSimples;

		// Anexa o arquivo
		//$mail->AddAttachment($arquivo_caminho, $arquivo_nome);

		//echo "<pre>"; print_r($mail); echo "</pre>"; exit;
		if($mail->Send()){
			return true;
		}else{
			return false;
		}		
		
    }

    /**
     * método enviar_email()
     */
    function enviar_email($pass, $hash, $email){
		
		/**
		 *  Enviar o Email
		 *
		 */
		// Instancia o objeto $mail a partir da Classe PHPMailer
		$mail = new email();
		
		//echo "<pre>"; print_r($mail); echo "</pre>"; exit;
		/*
		 *  Busca o host para o link
		 */
		$texto_hash = $mail->getHostLink()."valida.php?h=".$hash;

		// Recupera os dados do formulário
		$mensagem  = "<table border=\"0\" width=\"80%\" align='center' style=\"
							font-family         : arial, sans-serif, verdana;
							font-size			: 14px;
							border-collapse		: collapse;
							border		        : 1px solid #ADD8E6;\">
						<tr>
							<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">CPF:</td>
							<td style=\"'border: 1px solid #ADD8E6;\">".self::$tupla['ds_cnpj']."</td>
						</tr>
						<tr>
							<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">Usuário:</td>
							<td style=\"'border: 1px solid #ADD8E6;\">".self::$tupla['nm_usuario']."</td>
						</tr>
						<tr>
							<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">Senha:</td>
							<td style=\"'border: 1px solid #ADD8E6;\">".$pass."</td>
						</tr>
						<tr>
							<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">Data da Requisição:</td>
							<td style=\"'border: 1px solid #ADD8E6;\">".date('d/m/Y H:i:s')."</td>
						</tr>
					</table>
					<br>
					

				<p align='center'>PARA VALIDAR A SENHA DO USUÁRIO <a href='$texto_hash' target='_blank'>CLIQUE AQUI</a></p>

				";

		// Monta a mensagem que será enviada
		$corpo = "$mensagem";
		$corpoSimples = "$mensagem";

		// Informo o Host, FromName, From, subject e para quem o e-mail será enviado
		//$mail->Host = 'correio.cbm.sc.gov.br';
		//$mail->FromName = $nome;
		//$mail->From = $email;
		$mail->Subject = 'NOVA SENHA DO USUÁRIO';
		//$mail->AddAddress("cristian@awktec.com");
		//$mail->AddCC("cristianawk@cbm.sc.gov.br");
		$mail->AddAddress($email);
		//$mail->AddCC(self::$tupla['ds_email2']);

		// Informa que a mensagem deve ser enviada em HTML
		$mail->IsHTML(true);

		// Informa o corpo da mensagem
		$mail->Body = $corpo;

		// Se o e-mail destino não suportar HTML ele envia o texto simples
		$mail->AltBody = $corpoSimples;

		// Anexa o arquivo
		//$mail->AddAttachment($arquivo_caminho, $arquivo_nome);

		//echo "<pre>"; print_r($mail); echo "</pre>"; exit;
		if($mail->Send()){
			return true;
		}else{
			return false;
		}

    }

}
?>
