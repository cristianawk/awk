<?php
/**
 * classe fornpass
 * Valida e envia um email coma a senha para o fornecedor
 */
	//echo "<pre><br>post: "; print_r($_POST); echo "</pre>";
	//echo "<pre><br>get: "; print_r($_GET); echo "</pre>";	 

class fornpass{
    /**
     * m�todo construtor
     * 
     */
    public static $tupla;

    function __construct(){
        session_start();
    }

    /**
     * m�todo getFornecedor()
     * Armazena uma vari�vel na se��o
     * @param  $cnpj   = cnpj do fornecedor
     * @param  $email   = email do fornecedor
     */


    function getFornecedor($cnpj, $email){
	$cnpj = $_POST['ds_cnpj'];
	//echo "<br>cnpj: ".$cnpj;	
		$sql = sprintf("SELECT * FROM fornecedores WHERE ds_cnpj = '$cnpj' AND (ds_email1 = '$email') LIMIT 1;", trim($cnpj), trim($email));
        //echo "<br>sql: ".$sql; 
		//inicia a conex�o
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

			//Limpa a sess�o
			//self::freeSession();
			return false;

		}

		connection::close();

    }

    /**
     * m�todo regPassFornecedor()
     * Armazena uma vari�vel na se��o
     * @param  $pass   = pass do fornecedor
     * @param  $hash
     */
    function regPassFornecedor($pass, $hash, $email){
		
		$sql = sprintf("UPDATE fornecedores SET ps_senha = '%s', cd_hash_senha = '%s' WHERE id_fornecedor ='".self::$tupla['id_fornecedor']."';", trim($pass), trim($hash));
        //echo $sql; exit;
		//inicia a conex�o
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
     * m�todo verPassFornecedor()
     * Armazena uma vari�vel na se��o
     * @param  $pass   = pass do fornecedor
     * @param  $hash
     */
    function verPassFornecedor($cnpj, $email){
		/**
		 *  Enviar o Email
		 *
		 */
		// Instancia o objeto $mail a partir da Classe PHPMailer
		$mail = new email();
		//$cnpj = $_POST['ds_cnpj'];
		//echo "<pre>"; print_r($mail); echo "</pre>"; exit;
		/*
		 *  Busca o host para o link
		 */
		// Recupera os dados do formul�rio
		$mensagem  = "<table border=\"0\" width=\"80%\" align='center' style=\"
							font-family         : arial, sans-serif, verdana;
							font-size			: 14px;
							border-collapse		: collapse;
							border		        : 1px solid #ADD8E6;\">
						<tr>
							<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">CNPJ:</td>
							<td style=\"'border: 1px solid #ADD8E6;\">".self::$tupla['ds_cnpj']."</td>
						</tr>
						<tr>
							<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">Fornecedor:</td>
							<td style=\"'border: 1px solid #ADD8E6;\">".self::$tupla['nm_fornecedor']."</td>
						</tr>
						<tr>
							<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">Senha:</td>
							<td style=\"'border: 1px solid #ADD8E6;\">".self::$tupla['ps_senha']."</td>
						</tr>
						<tr>
							<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">Data da Requisi��o:</td>
							<td style=\"'border: 1px solid #ADD8E6;\">".date('d/m/Y H:i:s')."</td>
						</tr>
					</table>
					<br>
					

				";

		// Monta a mensagem que ser� enviada
		$corpo = "$mensagem";
		$corpoSimples = "$mensagem";

		// Informo o Host, FromName, From, subject e para quem o e-mail ser� enviado
		//$mail->Host = 'correio.cbm.sc.gov.br';
		//$mail->FromName = $nome;
		//$mail->From = $email;
		$mail->Subject = 'SENHA DO FORNECEDOR';
		//$mail->AddAddress("phproger@gmail.com");
		//$mail->AddCC("rogerawk@cbm.sc.gov.br");
		$mail->AddAddress($email);
		//$mail->AddCC(self::$tupla['ds_email2']);

		// Informa que a mensagem deve ser enviada em HTML
		$mail->IsHTML(true);

		// Informa o corpo da mensagem
		$mail->Body = $corpo;

		// Se o e-mail destino n�o suportar HTML ele envia o texto simples
		$mail->AltBody = $corpoSimples;

		// Anexa o arquivo
		//$mail->AddAttachment($arquivo_caminho, $arquivo_nome);

		//echo "<pre>"; print_r($mail); echo "</pre>"; //exit;
		if($mail->Send()){
			return true;
		}else{
			return false;
		}		
		
    }

    /**
     * m�todo enviar_email()
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

		// Recupera os dados do formul�rio
		$mensagem  = "<table border=\"0\" width=\"80%\" align='center' style=\"
							font-family         : arial, sans-serif, verdana;
							font-size			: 14px;
							border-collapse		: collapse;
							border		        : 1px solid #ADD8E6;\">
						<tr>
							<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">CNPJ:</td>
							<td style=\"'border: 1px solid #ADD8E6;\">".self::$tupla['ds_cnpj']."</td>
						</tr>
						<tr>
							<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">Fornecedor:</td>
							<td style=\"'border: 1px solid #ADD8E6;\">".self::$tupla['nm_fornecedor']."</td>
						</tr>
						<tr>
							<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">Senha:</td>
							<td style=\"'border: 1px solid #ADD8E6;\">".$pass."</td>
						</tr>
						<tr>
							<td width=\"20%\" style=\"border: 1px solid #ADD8E6;\">Data da Requisi��o:</td>
							<td style=\"'border: 1px solid #ADD8E6;\">".date('d/m/Y H:i:s')."</td>
						</tr>
					</table>
					<br>
					

			";
//				<p align='center'>PARA VALIDAR A SENHA DO FORNECEDOR <a href='$texto_hash' target='_blank'>CLIQUE AQUI</a></p>
		// Monta a mensagem que ser� enviada
		$corpo = "$mensagem";
		$corpoSimples = "$mensagem";

		// Informo o Host, FromName, From, subject e para quem o e-mail ser� enviado
		//$mail->Host = 'correio.cbm.sc.gov.br';
		//$mail->FromName = $nome;
		//$mail->From = $email;
		$mail->Subject = 'NOVA SENHA DO FORNECEDOR';
		//$mail->AddAddress("phproger@gmail.com");
		//$mail->AddCC("rogerawk@cbm.sc.gov.br");
		$mail->AddAddress($email);
		//$mail->AddCC(self::$tupla['ds_email2']);

		// Informa que a mensagem deve ser enviada em HTML
		$mail->IsHTML(true);

		// Informa o corpo da mensagem
		$mail->Body = $corpo;

		// Se o e-mail destino n�o suportar HTML ele envia o texto simples
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
