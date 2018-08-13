<?php

/**
 * Corpo de Bombeiros Militar de Santa Catarina
 *
 * Projeto Sigat Sistema de Gerenciamento de Atividades Tecnicas
 *
 * @categoria  Classes
 * @pacote     CtrlUsuarios
 * @autor      Edson Orivaldo Lessa Junior <edsonagem@cb.sc.gov.br>
 * @creditos   Agem Informatica  
 * @versao     1.0
 * @data       27/06/2005 as 15:22:33
 * @atualiza   19/11/2005 as 22:24:08
 * @arquivo    lib/class/class_usuarios.php
 */

class Ctrl_Usuarios {
    function ctrl_usuarios () {
        // Instancia objeto de acesso a dados
        $this->bd = new BD (BD_HOST, BD_USER, BD_PASS, BD_NOME_ACESSOS);
        $this->lista_campos_validos = array('ID_USUARIO', 'NM_USUARIO');

    }

    function get_id_by_login ($str_login) {
        $str_login = $this->bd->escape_string ($str_login);
        $sql = "SELECT ID_USUARIO FROM ".TBL_USUARIOS." WHERE ID_USUARIO = '$str_login'";
        if ($this->bd->query($sql))
            if ($this->bd->num_rows())
                return $this->bd->fetch_first_field();
        return false;
    }

    function get_usuario_by_id ($id) {
        if (!is_numeric($id))
            return false;
        $id = $this->bd->escape_string($id);
        $sql = "SELECT ID_USUARIO,PS_SENHA,NM_USUARIO,ID_BATALHAO,ID_COMPANIA,ID_PELOTAO,ID_PERFIL,ID_POSTO,ID_GRUPAMENTO FROM ".TBL_USUARIOS." WHERE ID_USUARIO = '$id'";
        if ($this->bd->query($sql))
            return new usuario ($this->bd->fetch_row());
        return false;
    }

    function get_usuario_by_login ($str_login) {
        if (!strlen($str_login) || strlen($str_login) > 16)
            return false;
        $str_login = $this->bd->escape_string($str_login);
        $sql = "SELECT ID_USUARIO,PS_SENHA,NM_USUARIO,ID_BATALHAO,ID_COMPANIA,ID_PELOTAO,ID_PERFIL,ID_POSTO,ID_GRUPAMENTO FROM ".TBL_USUARIOS." WHERE ID_USUARIO = '$str_login'";
        if ($this->bd->query($sql))
            return new usuario ($this->bd->fetch_row());
        return false;
    }

    function get_lista_usuarios () {
        $arr_usuarios = array();
        $sql = "SELECT ID_USUARIO,PS_SENHA,NM_USUARIO,ID_BATALHAO,ID_COMPANIA,ID_PELOTAO,ID_PERFIL,ID_POSTO,ID_GRUPAMENTO FROM ".TBL_USUARIOS;
        if (!$this->bd->query($sql))
            return false;
        while ($this->bd->num_rows_left())
            array_push ($arr_usuarios, new usuario($this->bd->fetch_row()));
        return $arr_usuarios;
    }

    function create_login ($novo_login, & $cod_falha) {
        /**
         * Codigos de falha:
         * 0 - nenhum erro
         * 1 - erro desconhecido
         * 2 - erro de BD generico
         * 3 - login invalido
         * 4 - login ja existente
         */

        // E ou nao um login que ja existe? Valor padrao
        $cod_falha = 1;

        // Verifica validade da entrada
        if (strlen($novo_login) < 3 || strlen($novo_login) > 16) {
            $cod_falha = 3;
            return false;
        }

        $sql = "INSERT INTO ".TBL_USUARIOS." (ID_USUARIO) VALUES ('$novo_login')";
        echo $sql.'<br>';
        $operacao = $this->bd->query ($sql);
        if (!$operacao) {
            echo "<b>Falhou operação no BD (classe controladora).\n";
            $cod_falha = 2;
            if ($this->bd->get_status() == 1062) // Entrada duplicada = login existente
                $cod_falha = 4;
            return false;
        }
        else {
            echo "<b>Operação no BD bem-sucedida (classe controladora).\n";
            $cod_falha = 0;
            return $this->bd->insert_id();
        }
    }
    
    function excluir_usuario ($login) {
        $sql = "DELETE FROM ".TBL_USUARIOS." WHERE ID_USUARIO = '$login'";
        return $this->bd->query ($sql);
    }
}

?>
