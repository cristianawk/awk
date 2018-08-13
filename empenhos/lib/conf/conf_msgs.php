<?php

/**
 * Corpo de Bombeiros Militar de Santa Catarina
 *
 * Projeto Sigat Sistema de Gerenciamento de Atividades Tecnicas
 *
 * @categoria  Configuracoes
 * @pacote     ConfMsgs
 * @autor      Edson Orivaldo Lessa Junior <edsonagem@cb.sc.gov.br>
 * @creditos   Agem Informatica
 * @versao     1.0
 * @data       27/06/2005 as 15:22:33
 * @atualiza   10/11/2005 as 18:22:08
 * @arquivo    lib/conf/conf_msgs.php
 */


   /**
    * Mensagens de Erro do Sistema
    */
    define ('MSG_ERR_DENIED', '(101) Acesso negado.');
    define ('MSG_ERR_NOT_LOGGED_IN', '(102) Não foi feito login.');
    define ('MSG_ERR_INTERNAL', '(103) Erro interno. Execução interrompida.');
    define ('MSG_ERR_SSL_REQUIRED', '(104) SSL é exigido para acessar o sistema.');
    define ('MSG_ERR_PERMISSION_NOT_ENOUGH', '(105) Permissão insuficiente.');
    define ('MSG_ERR_SCRIPT_HACK_ATTEMPT', '(106) Uso incorreto do script. Acesso negado.');
    define ("MSG_ERR_INC","echo '<tr><td align=\"center\" style=\"background-color : #f7ff05; color : #ff0000; font-weight : bold;\">USUÁRIO SEM ACESSO A INCLUSÃO</td></tr>';");
    define ("MSG_ERR_ALT","echo '<tr><td align=\"center\" style=\"background-color : #f7ff05; color : #ff0000; font-weight : bold;\">USUÁRIO SEM ACESSO A ALTERAÇÃO</td></tr>';");
    define ("MSG_ERR_EXC","echo '<tr><td align=\"center\" style=\"background-color : #f7ff05; color : #ff0000; font-weight : bold;\">USUÁRIO SEM ACESSO A EXCLUSÃO</td></tr>';");
    define ("MSG_ERR_OBR","echo '<tr><td align=\"center\" style=\"background-color : #f7ff05; color : #ff0000; font-weight : bold;\">OS CAMPOS ASSINALADOS SÃO OBRIGATÓRIOS</td></tr>';");

   /**
    * LOGs: de Informacao
    */ 
    define ('MSG_USER_LOGGED_IN', 'O usuário entrou no sistema.');
    define ('MSG_USER_LOGGED_OUT', 'O usuário saiu do sistema.');
    define ('MSG_USER_EVENTS_LISTED', 'O usuário consultou o histórico de eventos do sistema.');
    define ('MSG_USER_CREATED', 'Criou o usuário de login "%%1".');
    define ('MSG_USER_DELETED', 'Excluiu o usuário de login "%%1".');

   /**
    * LOGs: de Alerta
    */
   define ('MSG_SCRIPT_MISSING_PARAMETER', 'Falta pelo menos um parâmetro obrigatório para %%1.');
   define ('MSG_USER_AUTH_ERR_BLANK_LOGIN', 'Não foi possível fazer login porque o usuário não foi informado.');
   define ('MSG_USER_AUTH_ERR_NO_LOGIN', 'Não foi possível fazer login porque o usuário informado, "%%1", não existe.');
   define ('MSG_USER_AUTH_ERR_WRONG_PASSWD', 'O usuário "%%1" não pôde fazer login porque a senha estava incorreta.');
   define ('MSG_USER_AUTH_ERR_DISABLED_USER', 'O usuário não pôde fazer login porque está desabilitado.');
   define ('MSG_USER_ACCESS_DENIED_DUE_TO_PERM', 'O usuário não pôde acessar "%%1" porque não tem permissão.');

?>
