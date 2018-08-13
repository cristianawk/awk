<?php
/*
 * Validação de encoding
 */

function validaEncoding($string, $encoding = "UTF-8"){
	if (true === mb_check_encoding ( $string, $encoding )){
		 $return = utf8_decode($string);
	}else{
		$return = $string;
	}
	return $return;
}

/*
 * Formatar valores dos campos
 */
function formataCampo($atributo,$tipo="t",$lowwer="") {
    $atributo=trim(str_replace("'","`",$atributo));
    $tipo=strtoupper($tipo);
    $lowwer=strtoupper($lowwer);
    $formatcmp="NULL";
    if ($tipo=="T") {
      if ($atributo=="") {
        $formatcmp='NULL';
      } else {
        if ($lowwer=="U") {
          $formatcmp="'".validaEncoding(strtoupper($atributo))."'";
        } elseif ($lowwer=="L") {
          $formatcmp="'".validaEncoding(strtolower($atributo))."'";
        } elseif ($lowwer=="W") {
          $formatcmp="'".validaEncoding(ucwords(strtolower($atributo)))."'";
        } else {
          $formatcmp="'".validaEncoding($atributo)."'";
        }
      }
    }
    if ($tipo=="NT") {
      if ($atributo=="") {
        $formatcmp='NULL';
      } else {
        $lowwer=$lowwer+0;
        $atributo=str_replace(",","",$atributo);
        $atributo=str_replace(".","",$atributo);
        $atributo=str_replace("/","",$atributo);
        $atributo=str_replace("(","",$atributo);
        $atributo=str_replace(")","",$atributo);
        $atributo=str_replace("-","",$atributo);
        while (strlen($atributo)<$lowwer) {
          $atributo="0".$atributo;
        }
        $formatcmp="'".$atributo."'";
      }
    }
    if ($tipo=="SN") {
      if ($atributo=="") {
        $formatcmp="'N'";
      } else {
        $formatcmp="'".strtoupper($atributo)."'";
      }
    }
    if ($tipo=="N") {
      $atributo=str_replace(",","",$atributo);
      $atributo=str_replace(".","",$atributo);
      $atributo=str_replace("/","",$atributo);
      $atributo=str_replace("(","",$atributo);
      $atributo=str_replace(")","",$atributo);
      $atributo=str_replace("-","",$atributo);
      if ((trim($atributo)=="")&&($lowwer=="D")) {
        $formatcmp=0;
      } elseif (trim($atributo)=="") {
        $formatcmp='NULL';
      } else {
        $formatcmp=$atributo;
      }
    }
    if ($tipo=="DT") {
      $aux=explode("/",$atributo);
      $formatcmp="'".$aux[2]."-".$aux[1]."-".$aux[0]."'";
    }
    if ($tipo=="TM") {
      $aux=explode(":",$atributo);
      $formatcmp="'".$aux[0].":".$aux[1].":00'";
    }
    if ($tipo=="D") {
      $atributo=str_replace(".","",$atributo);
      $atributo=str_replace(",",".",$atributo);
      if ((trim($atributo)=="")&&($lowwer=="D")) {
        $formatcmp=0.00;
      } elseif ((trim($atributo)=="")&&($lowwer=="O")) {
        $formatcmp='NULL';
      } else {
        $formatcmp=$atributo;
      }
    }
    if ($tipo=="VN") {
      $atributo=str_replace(",","",$atributo);
      $atributo=str_replace(".","",$atributo);
      $atributo=str_replace("/","",$atributo);
      $atributo=str_replace("(","",$atributo);
      $atributo=str_replace(")","",$atributo);
      $atributo=str_replace("-","",$atributo);
      if ($lowwer=="U") {
        $formatcmp="'".strtoupper($atributo)."'";
      } else {
        $formatcmp="'".$atributo."'";
      }
    }

    return $formatcmp;
  }

/*
 *  Formata as colunas
 */

function formataColuna($coluna, $arrOps){
	if(in_array($coluna, $arrOps)){
		return false;
	}else{
		return true;
	}
}

/*
 * Formata o Campo a partir da coluna
 */

function formataValor($col, $val){

	$cmpformat = null;

	//echo "$col => $val<br>";
	//Quando for codigo
	if(strstr($col, 'id_')){

		$cmpformat = formataCampo($val, 'n');

	//Quando for nome
	}elseif(strstr($col, 'nr_')){

		$cmpformat = formataCampo($val, 'n');

    //login
    }elseif(strstr($col, 'login')){

		$cmpformat = formataCampo($val, 't', 'l');

	//Quando for nome
	}elseif(strstr($col, 'nm_')){

		$cmpformat = formataCampo($val, 't', 'u');

	//quando for codigo
	}elseif(strstr($col, 'cd_')){

		$cmpformat = formataCampo($val, 't', 'u');

	//quando for senha
	}elseif(strstr($col, 'ps_')){

		$cmpformat = formataCampo(md5($val));

	//quando for descrição
	}elseif(strstr($col, 'email')){

		$cmpformat = formataCampo($val, 't', 'L');

	//quando for caracter
	}elseif(strstr($col, 'ds_')){

		$cmpformat = formataCampo($val, 't', null);

	//quando for caracter
	}elseif(strstr($col, 'ch_')){

		$cmpformat = formataCampo($val, 't', 'U');

	}elseif(strstr($col, 'dt_')){

		$cmpformat = formataCampo($val, 'DT');

	}elseif(strstr($col, 'tm_')){

		$cmpformat = formataCampo($val, 'TM');

	}else{

        $cmpformat = formataCampo($val, 't');

    }

	//retorna o valor
	return $cmpformat;

}

/*
 * Formatação de Numeros
 */

function formatNumero($valor,$decimais=2) {
    if (($valor=="NULL") || ($valor=="")) {
      return "0,00";
    }
    $vl_format= explode(".",$valor);
    if (count($vl_format)<2) {
      $vl_format[1]="00";
    }
    $NUM=0;
    $OUT="";
    for ($NNN=strlen($vl_format[0]); $NNN>-1; $NNN--) {
      $NN1=substr($vl_format[0],$NNN,1);
      //echo "<!--aqui 0:$valor==>$NN1-->\n";
      if ($NN1!='') {
        $NUM=$NUM+1;
        $OUT=$NN1.$OUT;
        if (($NUM==3) && ($NNN>0)) {
          $OUT='.'.$OUT;
          $NUM=0;
        }
      }
    }
    $vl_format[0]=$OUT;
    if (strlen($vl_format[1])<=$decimais) {
      for ($NNN=strlen($vl_format[1]);$NNN<$decimais;$NNN++) {
        $vl_format[1].="0";
      }
    } else {
      $vl_format[1]= substr($vl_format[1],0,$decimais);
    }
    $valor= implode(",",$vl_format);
    return $valor;
  }

/*
 *  Formatar CPF
 */

function formatCPFCNPJ($valor) {
    if (($valor=="NULL") || ($valor=="")) {
      return "";
    }
    if (strlen($valor)>11) {
      $valor=substr($valor,0,2).".".substr($valor,2,3).".".substr($valor,5,3)."/".substr($valor,8,4)."-".substr($valor,12,2);
    } else {
      $valor=substr($valor,0,3).".".substr($valor,3,3).".".substr($valor,6,3)."-".substr($valor,9,2);
    }
    return $valor;
  }

/*
 *  FORMATAR CEP
 */
  function formatCEP($valor){
    if (($valor=="NULL") || ($valor=="")) {
      return "";
    }
    $valor=substr($valor,0,2).".".substr($valor,2,3)."-".substr($valor,5,3);
    return $valor;
  }


  function formtData($valor) {
    if (($valor=="NULL") || ($valor=="")) {
      return "";
    }
    $valor=substr($valor,4)."/".substr($valor,2,2)."/".substr($valor,0,2);
    return $valor;
  }
  function formatCREA($valor) {
    if (($valor=="NULL") || ($valor=="")) {
      return "";
    }
    $valor=substr($valor,0,6)."-".substr($valor,6);
    return $valor;
  }
  function formatChar($valor) {
    if (($valor=="NULL") || ($valor=="") || ($valor=="N") || ($valor=="0")) {
      return "NÃO PREVISTO";
    } else {
      return "PREVISTO";
    }
  }
  function extenso($IN, $maiusculas=false,$decimal=true,$moeda=true) {
    /*
    echo extenso("1242812");       //retorna: $valor = doze mil, quatrocentos e vinte e oito reais e doze centavos
    echo extenso("1242812", true); //     ou: $valor = Doze Mil, Quatrocentos E Vinte E Oito Reais E Doze Centavos
    echo "<BR>aqui: R$ 0,01 => ".extenso("1")."<BR>\n";
    */
    if (strpos(".",$IN)>(-1)) {
      $aux=explode(".",$IN);
      if (strlen($aux[1])==1) {
        $aux[1].="0";
      } else {
        $aux[1]=substr($aux[1],0,2);
      }
    }
    if (strpos(",",$IN)>(-1)) {
      $aux=explode(",",$IN);
      if (strlen($aux[1])==1) {
        $aux[1].="0";
      } else {
        $aux[1]=substr($aux[1],0,2);
      }
    }
    if (isset($aux)) {
      $IN=(trim(implode(".",$aux))*100);
    } else {
      $IN=$IN*100;
    }
    global $valor;
    $singular=array('centavo', 'real', 'mil', 'milhão', 'bilhão', 'trilhão', 'quatrilhão', 'quintilhão', 'sextilhão', 'septilhão', 'octilhão', 'eneatilhão');
    $plural=array('centavos', 'reais', 'mil', 'milhões', 'bilhões', 'trilhões', 'quatrilhões', 'quintilhões', 'sextilhões', 'septilhões', 'octilhões', 'eneatilhões');
    $c=array('', 'cento', 'duzentos', 'trezentos', 'quatrocentos', 'quinhentos', 'seiscentos', 'setecentos', 'oitocentos', 'novecentos');
    $d=array('', 'dez', 'vinte', 'trinta', 'quarenta', 'cinquenta', 'sessenta', 'setenta', 'oitenta', 'noventa');
    $d10=array('dez', 'onze', 'doze', 'treze', 'quatorze', 'quinze', 'dezesseis', 'dezesete', 'dezoito', 'dezenove');
    $u=array('', 'um', 'dois', 'três', 'quatro', 'cinco', 'seis', 'sete', 'oito', 'nove');
    $z=0;
    $rt='';
    if (strlen($IN)==1) {
      $IN='0'.$IN;
    }
    $DEC=','.substr($IN,(strlen($IN)-2),2);
    $VAL=substr($IN,0,(strlen($IN)-2));
    $OUT=''; $NUM=0;
    for ($NNN=strlen($VAL); $NNN>-1; $NNN--) {
      $NN1=substr($VAL,$NNN,1);
      if ($NN1!='') {
        $NUM=$NUM+1;
        $OUT=$NN1.$OUT;
        if ($NUM==3) {
          $OUT='.'.$OUT; $NUM=0;
        }
      }
    }
    $OUT=$OUT.$DEC;
    if (substr($OUT,0,1)==',') {
      $OUT='0'.$OUT;
    }
    if (substr($OUT,0,1)=='.') {
      $OUT=substr($OUT,1,strlen($OUT));
    }
    if (strlen($OUT)=='3') {
      $OUT=$OUT.'0';
    }
    $valor0=str_replace(",",".",$OUT); // troca a virgula decimal por ponto decimal
    $inteiro=explode(".", $valor0);
    $tx=(count($inteiro)-1);
    for ($i=0;$i<count($inteiro);$i++) {
      if ($decimal==false) {
        if ($i==(count($inteiro)-1)) {
          continue;
        }
      }
      if ($inteiro[$i]>0) {
        $valor1=1000+($inteiro[$i]*1);
        $r='';
        $rc='';
        $rd='';
        $ru='';
        $rru=0;
        if (substr($valor1,1,1)>0) {
          $rc=$c[substr($valor1,1,1)];
        }
        if (substr($valor1,1,3)==100) {
          $rc='cem';
        }
        if (substr($valor1,2,1)>0) {
          $rd=$d[substr($valor1,2,1)];
        }
        if ((substr($valor1,2,2)<20)&&(substr($valor1,2,2)>10)) {
          $rd=$d10[substr($valor1,3,1)];
          $rru=1;
        }
        if ($rru==0) {
          if (substr($valor1,3,1)>0) {
            $ru=$u[substr($valor1,3,1)];
          }
        }
        $r=$rc;
        if ($rd!='') {
          $r=$r.' e '.$rd;
        }
        if ($ru!='') {
          $r=$r.' e '.$ru;
        }
        $t=((count($inteiro)-1)-$i);
        if ($valor1>1001) {
          $r=$r.' '.$plural[$t];
        } else {
          $r=$r.' '.$singular[$t];
        }
        $rt=$rt.' '.$r;
      }
    }
    if ($t>0) {
      if ($t<3) {
        $rt=$rt.' reais';
      } else {
        $rt=$rt.' de reais';
      }
    }
    if (substr($rt,0,10)=='  real  e ') {
      $rt=substr($rt,10,strlen($rt)+1);
    }
    if (substr($rt,0,4)=='  e ') {
      $rt=substr($rt,4,strlen($rt)+1);
    }
    if (substr($rt,0,3)=='um ') {
      $rt='h'.$rt;
    }
    if (substr($rt,0,4)==' ') {
      $rt=substr($rt,1,strlen($rt)+1);
    }
    $rt=str_replace("real reais","reais",$rt);
    $rt=str_replace("reais reais","reais",$rt);
    if ($valor1==1001) {
      if ($tx==1) {
        $rt=str_replace("reais","real",$rt);
      }
    }
    if ($moeda==false) {
      $rt=str_replace("de reais","",$rt);
      $rt=str_replace("de real","",$rt);
      $rt=str_replace("reais","",$rt);
      $rt=str_replace("real","",$rt);
    }
    if (!$maiusculas) {
      return $rt;
    } else {
      return ucwords($rt);
    }
  }
######################

// FUNÇÃO DE FONETIZAÇÃO EM LINGUA PORTUGUESA
function fonetica($texto) {
  $texto = trim(strtoupper($texto));
  $texto.=" ";
  $fonetica="";


  $vog = array("A","E","I","O","U","À","Á","Â","Ã","Ä","Å","Æ","È","É","Ê","Ë","Ì","Í","Î","Ï","Ò","Ó","Ô","Õ","Ö","Y","Ý","U","Ú","Ù","Û","Ü");
  $vogA = array("A","À","Á","Â","Ã","Ä","Å");
  $vogIE = array("E","I","È","É","Ê","Ë","Ì","Í","Î","Ï","Y","Ý");
  $vogSA=array("E","I","O","U","È","É","Ê","Ë","Ì","Í","Î","Ï","Ò","Ó","Ô","Õ","Ö","Y","Ý","U","Ú","Ù","Û","Ü");
   for ( $i=0 ; $i < strlen(trim($texto)); $i++) {
    if ($i>0) {
      if ($texto[$i-1]==$texto[$i]) {
        continue;
      }
    }
    $par=$i-1;
    if ($i==0) {
      $par=0;
    }
    switch($texto[$i]) {
      case 'À': $fonetica.='A'; break;
      case 'Á': $fonetica.='A'; break;
      case 'Â': $fonetica.='A'; break;
      case 'Ã': $fonetica.='A'; break;
      case 'Ä': $fonetica.='A'; break;
      case 'Å': $fonetica.='A'; break;
      case 'Æ': $fonetica.='E'; break;
      case 'È': $fonetica.='E'; break;
      case 'É': $fonetica.='E'; break;
      case 'Ê': $fonetica.='E'; break;
      case 'Ë': $fonetica.='E'; break;
      case 'Ì': $fonetica.='I'; break;
      case 'Í': $fonetica.='I'; break;
      case 'Î': $fonetica.='I'; break;
      case 'Ï': $fonetica.='I'; break;
      case 'Ò': $fonetica.='O'; break;
      case 'Ó': $fonetica.='O'; break;
      case 'Ô': $fonetica.='O'; break;
      case 'Õ': $fonetica.='O'; break;
      case 'Ö': $fonetica.='O'; break;
      case 'Ý': $fonetica.='I'; break;
      case 'Y': $fonetica.='I'; break;
      case ' ': $fonetica.='0';  break;
      case 'A': $fonetica.='A'; break;
      case 'E': $fonetica.='I'; break;
      case 'I': $fonetica.='I'; break;
      case 'B': $fonetica.='B'; break;
      case 'Ç': $fonetica.='S'; break;
      case 'D': $fonetica.='D'; break;
      case 'F': $fonetica.='F'; break;
      case 'G': $fonetica.='G';  break;
      case 'J': $fonetica.='J';  break;
      case 'K': $fonetica.='K';  break;
      case 'L':
        //$vog = array("A","E","I","O","U");
        if (($texto[$i+1]=='H') or (in_array($texto[$i+1], $vog))) {
          $fonetica.='L';
        } else {
          $fonetica.='U';
        }
        break;
      case 'M':  $fonetica.='M';  break;
      case 'R': $fonetica.='R';  break;
      case 'T': $fonetica.='T';  break;
      case 'V': $fonetica.='V';  break;
      case 'X': $fonetica.='S';  break;
      case 'C':
        if ($texto[$i+1]=='H') {
          $fonetica.='X';
        } elseif (in_array($texto[$i+1],$vogIE)) {
          $fonetica.='S';
        } else {
          $fonetica.='K';
        }
        break;
      case 'Ñ':
        $fonetica.='Ñ';
        break;
      case 'N':
        //$vog = array("A","E","I","O","U");
        if ($texto[$i+1]=='H') {
          $fonetica.='Ñ';
        } elseif (in_array($texto[$i+1], $vog)) {
          $fonetica.='N';
        } else {
          $fonetica.='M';
        }
        break;
      case 'P':
        if ($texto[$i+1]=='H') {
          $fonetica.='F';
        } else {
          $fonetica.='P';
        }
        break;
      case 'Š':
        //$vog = array("A","E","I","O","U");
        if ($texto[$i+1]=='H') {
          $fonetica.='X';
        } elseif (($texto[$par]=='S') or ($texto[$i+1]=='S') or (!in_array($texto[$i+1], $vog)) ) {
          $fonetica.='S';
        } elseif (($i=='0') or (!in_array($texto[$i+1], $vog))) {
          $fonetica.='S';
        } else {
          $fonetica.='S';
        }
        break;
      case 'S':
        //$vog = array("A","E","I","O","U");
        if ($texto[$i+1]=='H') {
          $fonetica.='X';
        } elseif (($texto[$par]=='S') or ($texto[$i+1]=='S') or (!in_array($texto[$i+1], $vog)) ) {
          $fonetica.='S';
        } elseif (($i=='0') or (!in_array($texto[$i+1], $vog))) {
          $fonetica.='S';
        } else {
          $fonetica.='S';
        }
        break;
      case 'Q':
        //$vog = array("E","I","O","U");
        if (($texto[$i+1]=='U') && (in_array($texto[$i+2], $vogSA))) {
          $fonetica.='K';
        } elseif (($texto[$par]=='Q') or (!in_array($texto[$i+1], $vogSA)) ) {
          $fonetica.='K';
        } else {
          $fonetica.='Q';
        }
        break;
      case 'O':
        if ($texto[$i+1]=='U') {  $fonetica.='O'; }
        else { $fonetica.='U'; }
        break;
      case 'Ù':
        if ($texto[$par]=='Q') {  break; }
        else { $fonetica.='U'; }
        break;
      case 'Ú':
        if ($texto[$par]=='Q') {  break; }
        else { $fonetica.='U'; }
        break;
      case 'Û':
        if ($texto[$par]=='Q') {  break; }
        else { $fonetica.='U'; }
        break;
      case 'Ü':
        if ($texto[$par]=='Q') {  break; }
        else { $fonetica.='U'; }
        break;
      case 'U':
        if ($texto[$par]=='Q') {  break; }
        else { $fonetica.='U'; }
        break;
      case 'W':
        //$vog = array("A","E","I","O","U");
        if ($i==0) { $fonetica.='U'; break; }
        if (in_array($texto[$i+1], $vog)) { $fonetica.='V'; }
        break;
        /*
      case 'Ž':
        $vog = array("A","E","I","O","U");
        if (!in_array($texto[$i+1], $vog)) {
          $fonetica.='S';
        } elseif ($texto[$i+1]=='') {
          $fonetica.='s';
        } else {
          $fonetica.='Z';
        }
        break;*/
      case 'Z':
        $vog = array("A","E","I","O","U");
        if (!in_array($texto[$i+1], $vog)) { $fonetica.='S'; }
        else { $fonetica.='Z'; }
        break;
    }
  }
  $fonetica1='';
  for ( $i=0 ; $i < strlen($fonetica); $i++) {
    if ($i>0) {
      if ($fonetica[$i-1]==$fonetica[$i]) {
        continue;
      }
    }
    $fonetica1.=$fonetica[$i];
  }
  return $fonetica1;
}
function limpa_texto($tipot,$texto) {
  $char1='<<'; $char2='>>';
  if ($tipot==33) {
    $char1='&lt°&lt°';
    $char2='&gt°&gt°';
  }
  $pedacos0=array();
  $pedacos1=array();
  $pedacos0=explode($char1,$texto);
  $texto='';
  for ($x=1; $x<count($pedacos0); $x++) {
    $pedacos1=explode($char2,$pedacos0[$x]);
    $texto=$texto.' '.$pedacos1[0];
  }
  // RETIRA TAGS DO EDITOR DE TEXTOS
  if ($tipot==33) {
    $pedacos=explode('<',$texto);
    for ($x=1; $x<count($pedacos); $x++) {
      $posicao=strpos($pedacos[$x],'>');
      $tag=substr($pedacos[$x],0,$posicao+1);
      $pedacos[$x]=str_replace($tag,'',$pedacos[$x]);
      $pedacos[$x]=str_replace('  ',' ',$pedacos[$x]);
    }
    unset($texto);
    $texto=implode(' ',$pedacos);
  }
  $texto=trim($texto);
  return $texto;
}
function nr_txt_fonetica($texto) {
  $text= explode(" ",$texto);
  for ($i=0;$i<count($text);$i++) {
    if (is_numeric($text[$i])) {
      $text[$i]=extenso($text[$i],false,false,false);
    }
  }
  $texto= implode(" ",$text);
  return fonetica($texto);
}
function diferenca_dias($inicial, $final) {
  list($dia_inicial, $mes_inicial, $ano_inicial) = explode("/", $inicial);
  list($dia_final, $mes_final, $ano_final) = explode("/", $final);

  $inicial2 = mktime(0,0,0,$mes_inicial,$dia_inicial,$ano_inicial);
  $final2 = mktime(0,0,0,$mes_final,$dia_final,$ano_final);

  $dias = ($final2 - $inicial2)/86400;

  return $dias;
}
/**
 *  Função LDAP
 */
function checkldapuser($username,$password,$ldap_server){

	global $erro_ldap;
	$erro_ldap="";
	if($connect=@ldap_connect($ldap_server)){ // if connected to ldap server

		ldap_set_option($connect, LDAP_OPT_PROTOCOL_VERSION, 3);

		// bind to ldap connection
		if(($bind=@ldap_bind($connect)) == false){
            $erro_ldap.="ERRO NA PRIMEIRA NO BIND ANONIMO<br>\n";
			return false;
		}

		// search for user cn=E193,ou=Groups,dc=cb,dc=sc,dc=gov,dc=br
		if (($res_id = ldap_search( $connect,"ou=Users,dc=cbm,dc=sc,dc=gov,dc=br", "uid=$username")) == false) {
			//    if (($res_id = ldap_search( $connect,"cn=E193,ou=Groups,dc=cb,dc=sc,dc=gov,dc=br", "uid=$username")) == false) {
			$erro_ldap.="ERRO: PROCURA DA ÁRVORE NO LDAP<br>";
			return false;
		}

		if (ldap_count_entries($connect, $res_id) != 1) {
            $erro_ldap.="ERRO: USUARIO $username EXISTE MAIS DE UM USUÁRIO<br>\n";
			return false;
		}

		if (( $entry_id = ldap_first_entry($connect, $res_id))== false) {
            $erro_ldap.="ERRO: NÃO FOI POSSIVEL ESTABELECER A ENTRADA PARA O LINK<br>\n";
			return false;
		}

		if (( $user_dn = ldap_get_dn($connect, $entry_id)) == false) {
            $erro_ldap.="ERRO: NÃO FOI POSSÍVEL ESTABELECER A ÁRVORE DO USUÁRIO<br>\n";
			return false;
		}

		/* Authentifizierung des User */
		if (($link_id = @ldap_bind($connect, $user_dn, $password)) == false) {
			$erro_ldap.="ERRO: USUÁRIO OU SENHA INVÁLIDOS<br>\n";
			return false;
		}

		@ldap_close($connect);
		return true;

  } else {                                  // no conection to ldap server
		$erro_ldap.="NÃO FOI POSSÍVEL ESTABELECER A CONEXÃO: '$ldap_server'<br>\n";
  }

  $erro_ldap.="ERRO FATAL: ".ldap_error($connect)."<BR>\n";
  //echo $erro_ldap; exit;

  @ldap_close($connect);
  return(false);
}


?>
