<?php
/*
 * classe connection
 *  gerencia conex�es com bancos de dados,
 *  atrav�s de arquivos de configura��o.
 */
 
final class connection {


    private static $conn = null;

    /*
     * m�todo __construct()
     */
	private function __construct(){}

	/*
	 *  Inicio da Conex�o
	 */
    public static function init($dir = 'conf', $name = 'audita'){

		// verifica se existe arquivo de configura��o
        // para este banco de dados
        $pontos = array("./", "../", "../../");
        foreach($pontos AS $ponto){
			if (file_exists("{$ponto}{$dir}/{$name}.ini")){
            // l� o INI e retorna um array
            $db = parse_ini_file("{$ponto}{$dir}/{$name}.ini");

			}
        }

		//echo "<pre>"; print_r($db); echo "</pre>"; exit;

		// l� as informa��es contidas no arquivo
        $user  = $db['user'];
        $pass  = $db['pass'];
        $name  = $db['name'];
        $host  = $db['host'];

		/*
		 *  Chama a class bd para conex�o e intera��o com o banco
		 *  Verifica se a conex�o ja n�o foi instanciada
		 */
		if(self::$conn == null){
			self::$conn = new bd($host, $user, $pass, $name);
		}

		//echo "<pre>"; print_r(self::$conn); echo "</pre>"; exit;

		return self::$conn;

	}


	/*
	 *  Finaliza da Conex�o
	 */
    public static function close(){
		self::$conn->close();
	}
	
}

?>
