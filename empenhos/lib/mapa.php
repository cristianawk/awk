<?php
/**
 *	Classe para buscar a chave do Google Maps
 *	Autor: Roger J Estefani
 * 	
 *
 */
class KEYMAPA{

	public $key = null;

	/*
	 * Busca chave no banco de dados
	 */
	function __construct(){
	
		$conn = new BD (BD_HOST, BD_USER, BD_PASS, BD_NOME);
		//if ($conn->get_status()==false) die($conn->get_msg());

		$q = "SELECT key_mapa FROM servidores WHERE ip_servidor = '".$_SERVER['SERVER_ADDR']."'";
		//Executa a sql e verifica se não possue erros
		if($conn->query($q)){
			$tupla = $conn->fetch_row();
			$this->key = $tupla['key_mapa'];
		}	
	}
	
	/*
	 * Retorna a chave
	 */
	function getKeyMap(){
		return $this->key;
	}

}
?>
