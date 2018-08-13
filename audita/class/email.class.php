<?php
/*
 * Encontra a classe PHPMailer a ser chamada
 */
$pontos = array("./", "../", "../../");
foreach($pontos AS $ponto){
	if(file_exists("{$ponto}class/phpmailer/class.phpmailer.php")){
		//echo "{$ponto}class/phpmailer/class.phpmailer.php\n";
		include_once "{$ponto}class/phpmailer/class.phpmailer.php";
	}
}
/*
 * Classe que envia email
 */
class email extends PHPMailer {

	private $HostLink;

	/**
     * método construtor
     * inicializa uma seção
     */
    function __construct(){

		// verifica se existe arquivo de configuração
        // para o email

        $pontos = array("./", "../", "../../");
        foreach($pontos AS $ponto){
			// Verifica se o arquivo existe
			if (file_exists("{$ponto}conf/email.ini")){
				// lê o INI e retorna um array
				$ml = parse_ini_file("{$ponto}conf/email.ini");
			}
        }

        $this->Host 	= $ml['email_host']; // Pega o endereço do servidor de correio
		$this->FromName = $ml['email_nome']; // Nome de quem esta enviando
		$this->From 	= $ml['email_mail']; // Quem esta enviando o email
		$this->HostLink = $ml['email_host_link']; //host do link que vai junto com o email
	}

	/**
	 *  Retorna o valor do HostLink
	 */
	function getHostLink(){
		return $this->HostLink;
	}

}



?>
