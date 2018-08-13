<?php

class bd {

   /**
    * Variaveis utilizadas:
    * $this->link          Link da conexao do PostgreSql
    * $this->status        Valor opcional do retorno da ultima operacao
    * $this->err_msg       Armazena a mensagem de um erro que encerrou um metodo
    * $this->row_count     Conta quandos registros ainda tem para serem lidos
    *                      e decrementado a cada $this ->fetch_row()
    */

	public $resposta; 

    function __construct($host, $user, $pass, $db){
        // Status inicial: falso
        $this->status = false;

        // Inicializa o contador de registros
        $this->row_count = 0;

        // Tenta conectar com o servidor Postgres
        $this->link = pg_connect("host=$host port=5432 dbname=$db user=$user password=$pass");

        if (pg_connection_status($this->link)!==0) {
            $this->status = false;
            $this->err_msg = pg_last_error();
            return false;
        }else{
			// Tudo correu bem, a conexao esta dispononivel
			$this->status = true;
		}

    }

    function check_server () {
        return $this->status;
    }

    // Retorna a mensagem de erro de um metodo que tenha falhado
    function get_msg () {
        return $this->err_msg;
    }

    function get_status () {
        return $this->status;
    }

	function set_status ($val) {
        $this->status = $val;
    }


    // Executa uma query SQL
    function query ($sql) {
        $select = (strtoupper(substr(trim($sql), 0, 6)) == 'SELECT');
        if ($select) {
             $this->resposta = pg_query($this->link, $sql);
             /**
              0 = PGSQL_EMPTY_QUERY
              1 = PGSQL_COMMAND_OK
              2 = PGSQL_TUPLES_OK
              3 = PGSQL_COPY_TO
              4 = PGSQL_COPY_FROM
              5 = PGSQL_BAD_RESPONSE
              6 = PGSQL_NONFATAL_ERROR
              7 = PGSQL_FATAL_ERROR
             */
             if ((pg_result_status($this->resposta)!=0) && (pg_result_status($this->resposta)!=1) && (pg_result_status($this->resposta)!=2)) {
               $this->status = false;
             }
        }
        else {
             $this->resposta = @pg_query($this->link, $sql);
             if ((pg_result_status($this->resposta)!=0) && (pg_result_status($this->resposta)!=1) && (pg_result_status($this->resposta)!=2)) {
               $this->status = false;
             }
        }
        $this->err_msg = pg_result_error($this->resposta).' (requisicao original: '.$sql.')';
        if (pg_last_error($this->link)!="") {
          $this->err_msg = pg_last_error($this->link).' (requisicao original: '.$sql.')';
          $this->status = false;
        }

        if (($this->status) && ($select)) {
            $this->row_count = $this->num_rows();
        } else {
            $this->row_count = 0;
        }
        return true;
    }

    // Recupera uma entrada da resposta do SQL
    function fetch_row () {
        if ($this->num_rows_left()) {
           $this->row_count--;
           return pg_fetch_assoc($this->resposta);
        }
        return false;
    }

    // Recupera o primeiro valor de um registro
    function fetch_first_field () {
        if ($this->num_rows_left()) {
           $this->row_count--;
           return pg_fetch_row($this->resposta); // Retorna o primeiro campo
        }
        return false;
    }

	//recupera o numero de rows
    function num_rows(){
        $valor = 0;
		//echo $this->resposta;
		$valor = pg_num_rows($this->resposta);
        return $valor;
    }

	//recupera os valores da tupla
	function get_tupla(){

		$var = null;

		while($tupla = $this->fetch_row()){

			$var[] = $tupla;

		}

		return $var;
	}


    function num_rows_left () {
        return $this->row_count;
    }

    function escape_string ($string) {
        return pg_escape_string($string);
    }

    function affected_rows () {
        return pg_affected_rows($this->link);
    }

	/* Campos */

	function field_name ($n) {
        return pg_field_name($this->resposta, $n);
    }

    function num_fields () {
        return pg_num_fields($this->resposta);
    }

	/*	Fecha a conexao	*/
	function close(){
		pg_close($this->link);
	}


}

?>
