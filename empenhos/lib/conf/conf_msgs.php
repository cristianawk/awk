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
    define ('MSG_ERR_NOT_LOGGED_IN', '(102) N�o foi feito login.');
    define ('MSG_ERR_INTERNAL', '(103) Erro interno. Execu��o interrompida.');
    define ('MSG_ERR_SSL_REQUIRED', '(104) SSL � exigido para acessar o sistema.');
    define ('MSG_ERR_PERMISSION_NOT_ENOUGH', '(105) Permiss�o insuficiente.');
    define ('MSG_ERR_SCRIPT_HACK_ATTEMPT', '(106) Uso incorreto do script. Acesso negado.');
    define ("MSG_ERR_INC","echo '<tr><td align=\"center\" style=\"background-color : #f7ff05; color : #ff0000; font-weight : bold;\">USU�RIO SEM ACESSO A INCLUS�O</td></tr>';");
    define ("MSG_ERR_ALT","echo '<tr><td align=\"center\" style=\"background-color : #f7ff05; color : #ff0000; font-weight : bold;\">USU�RIO SEM ACESSO A ALTERA��O</td></tr>';");
    define ("MSG_ERR_EXC","echo '<tr><td align=\"center\" style=\"background-color : #f7ff05; color : #ff0000; font-weight : bold;\">USU�RIO SEM ACESSO A EXCLUS�O</td></tr>';");
    define ("MSG_ERR_OBR","echo '<tr><td align=\"center\" style=\"background-color : #f7ff05; color : #ff0000; font-weight : bold;\">OS CAMPOS ASSINALADOS S�O OBRIGAT�RIOS</td></tr>';");

   /**
    * LOGs: de Informacao
    */ 
    define ('MSG_USER_LOGGED_IN', 'O usu�rio entrou no sistema.');
    define ('MSG_USER_LOGGED_OUT', 'O usu�rio saiu do sistema.');
    define ('MSG_USER_EVENTS_LISTED', 'O usu�rio consultou o hist�rico de eventos do sistema.');
    define ('MSG_USER_CREATED', 'Criou o usu�rio de login "%%1".');
    define ('MSG_USER_DELETED', 'Excluiu o usu�rio de login "%%1".');

   /**
    * LOGs: de Alerta
    */
   define ('MSG_SCRIPT_MISSING_PARAMETER', 'Falta pelo menos um par�metro obrigat�rio para %%1.');
   define ('MSG_USER_AUTH_ERR_BLANK_LOGIN', 'N�o foi poss�vel fazer login porque o usu�rio n�o foi informado.');
   define ('MSG_USER_AUTH_ERR_NO_LOGIN', 'N�o foi poss�vel fazer login porque o usu�rio informado, "%%1", n�o existe.');
   define ('MSG_USER_AUTH_ERR_WRONG_PASSWD', 'O usu�rio "%%1" n�o p�de fazer login porque a senha estava incorreta.');
   define ('MSG_USER_AUTH_ERR_DISABLED_USER', 'O usu�rio n�o p�de fazer login porque est� desabilitado.');
   define ('MSG_USER_ACCESS_DENIED_DUE_TO_PERM', 'O usu�rio n�o p�de acessar "%%1" porque n�o tem permiss�o.');

?>
