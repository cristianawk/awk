<?php

/**
 * Corpo de Bombeiros Militar de Santa Catarina
 *
 * Projeto Sigat Sistema de Gerenciamento de Atividades Tecnicas
 *
 * @categoria  Classes
 * @pacote     CtrlEventos
 * @autor      Edson Orivaldo Lessa Junior <edsonagem@cb.sc.gov.br>
 * @creditos   Agem Informatica
 * @versao     1.0
 * @data       11/07/2005 as 14:30:05
 * @atualiza   19/11/2005 as 22:11:55
 * @arquivo    lib/class/ctrleventos.php
 */

class Ctrl_Eventos {

    function ctrl_eventos () {
          // Instancia objeto de acesso a dados
   	  $this->bd = new BD (BD_HOST, BD_USER, BD_PASS, BD_NOME);
    }

    function reg_evento ($str_evento, $int_nivel, $str_login, $arr_info_adic) {

         /**
          * $msg   - conteudo do evento
          * $tipo  - info, warn ou crit
          * $login - login de um usuario autenticado
          * $opc   - detalhes do evento
	  */

        // Aborta caso o nivel informado seja invalido.
        if ($int_nivel != EVENT_INFO && $int_nivel != EVENT_WARN && $int_nivel != EVENT_SECURITY && $int_nivel != EVENT_CRIT)
            return false;

      if ($arr_info_adic != null)
            if (is_array($arr_info_adic)) { // Se for array...
                for ($i=0; $i<count($arr_info_adic);$i++) {
                    echo 'Ocorrência '.($i+1).': '.$arr_info_adic[$i].'<br>';
                    $str_evento = str_replace ('%%'.($i+1), $arr_info_adic[$i], $str_evento);
                }
            }
            else
                $str_evento = str_replace ('%%1', $arr_info_adic, $str_evento);

        $str_evento = str_replace ('%%login', $str_login, $str_evento);

       /**
        * Garante que as variaveis sao seguras para serem
        * inseridas em queries SQL
        */
        $evento_safe = $this->bd->escape_string ($str_evento);

       /**
        * Se o login for nulo use o usuario padrao de
        * eventos do sistema (EVENT_DEFAULT_USER).
        */
        if ($str_login != null && strlen($str_login))
            $login_safe = $this->bd->escape_string ($str_login);
        else
            $login_safe = EVENT_DEFAULT_USER;

        $ip = get_ip_cliente();
        $info_adic = '';
        if (cliente_usa_proxy())
            $info_adic = "Endereço(s) interno(s): ".get_ip_interno_cliente();

       /**
        * Monta e executa o SQL
        */
        /**
        $sql = "INSERT INTO ".TBL_EVENTOS." (LOGIN, NIVEL, IP, INFO_ADIC, DATA_HORA, DESCRICAO)
                VALUES ('$login_safe', $int_nivel, '$ip', '$info_adic', CURRENT_TIMESTAMP, '$evento_safe')";
        $this->bd->query ($sql);
        */

    }

    function get_lista_eventos () {
        $arr_lista = array();
        $sql = "SELECT EVENTO_ID, LOGIN, NIVEL, IP, INFO_ADIC, DATA_HORA, DESCRICAO FROM ".TBL_EVENTOS." ORDER BY EVENTO_ID DESC LIMIT 0,300";
        if ($this->bd->query ($sql))
            while ($this->bd->num_rows_left())
                array_push ($arr_lista, $this->bd->fetch_row());
        else
            echo 'Erro!'.$this->bd->get_msg();
        return $arr_lista;
    }
}

?>
