<?php
/*
 * Classe que controla a formata��o dos campos
 */

class formata{

	/*
	 * Inicia a classe
	 */
	public function __construct(){}

	/*
	 * Valida��o de encoding
	 */
	private static function validaEncoding($string, $encoding = "UTF-8"){

		if (true === mb_check_encoding ( $string, $encoding )){

			$return = utf8_decode($string);

		}else{

			$return = $string;

		}

		return $return;
	}

	/*
	 *  Converte para maiuscula todos os caraceteres incluindo os com acentua��o
	 */
	public static function fullUpper($string) {
		return strtr(strtoupper($string), array("�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�"));
    }

	/*
	 *  Converte para minuscula todos os caraceteres incluindo os com acentua��o
	 */
	public static function fullLower($string) {
		return strtr(strtolower($string), array("�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�"));
    }

	/*
	 * Formatar valores dos campos
	 */
	private static function formataCampo($atributo, $tipo="t", $lowwer=""){


		$atributo = trim(str_replace("'","`",$atributo));

		$tipo = strtoupper($tipo);

		$lowwer = strtoupper($lowwer);

		$formatcmp="NULL";

		if ($tipo=="T"){
			if ($atributo==""){
				$formatcmp='NULL';
			} else {
				if ($lowwer=="U") {
					$formatcmp="'".self::fullUpper(self::validaEncoding($atributo))."'";
				} elseif ($lowwer=="L") {
					$formatcmp="'".self::validaEncoding(self::fullLower($atributo))."'";
				} elseif ($lowwer=="W") {
					$formatcmp="'".self::validaEncoding(ucwords(self::fullLower($atributo)))."'";
				} else {
					$formatcmp="'".self::validaEncoding($atributo)."'";
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
			//Substitui tudo que n�o � numero
			$atributo=preg_replace("/[\D]/", "", $atributo);
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
	 * Formata o Campo a partir da coluna
	 */

	public static function formataValor($col, $val){ 

		//Quando for codigo
		if(strstr($col, 'id_')){

			$resposta = self::formataCampo($val, 'n');

		//Quando for nome
		}elseif(strstr($col, 'nr_')){

			$resposta = self::formataCampo($val, 'n');

		//login
		}elseif(strstr($col, 'login')){

			$resposta = self::formataCampo($val, 't', 'l');

		//Quando for nome
		}elseif(strstr($col, 'nm_')){

			$resposta = self::formataCampo($val, 't', 'u');

		//quando for codigo
		}elseif(strstr($col, 'cd_')){

			$resposta = self::formataCampo($val, 't', 'u');

		//quando for senha
		}elseif(strstr($col, 'ps_')){

			$resposta = self::formataCampo(md5($val));

		//quando for descri��o
		}elseif(strstr($col, 'email')){

			$resposta = self::formataCampo($val, 't', 'L');

		//quando for caracter
		}elseif(strstr($col, 'ds_')){

			$resposta = self::formataCampo($val, 't', null);

		//quando for caracter
		}elseif(strstr($col, 'ch_')){

			$resposta = self::formataCampo($val, 't', 'U');

		}elseif(strstr($col, 'dt_')){

			$resposta = self::formataCampo($val, 'DT');

		}elseif(strstr($col, 'tm_')){

			$resposta = self::formataCampo($val, 'TM');

		}elseif(strstr($col, 'vl_')){

			$resposta = self::formataCampo(str_replace(array(",", "R$ ", "R$"), array(".", "", ""), $val));

		}else{

			$resposta = self::formataCampo($val, 't');

		}

		//retorna o valor
		return $resposta;

	}


	/*
	 *  valida as colunas
	 */
	public static function validaColuna($coluna, $arrOps){

		if(in_array($coluna, $arrOps)){

			return false;

		}else{

			return true;
		}

	}


	/*
	 *  Fun��o que converte o array para o formato JSON
	 */
	public static function encodeJSON($array){
		//return json_encode($array); S� converte dados com codifica��o UTF-8
		return self::my_json_encode($array);
	}


	/*
	 *  Fun��o que converte o JSON para Array
	 */
	public static function decodeJSON($json){
		$json_return = str_replace(array('\"', '\/'),array('"', '/'),$json);
		//return json_decode($json_return, true); Substituido por oferecer problemas
		return self::my_json_decode($json_return);
	}


	/*
	 *  Fun��o para converter um array ou um objeto em formato JSON substituindo a fun��o do PHP "json_encode"
	 *  A fun��o do PHP "json_encode" s� consegue converter dados com codifica��o UTF-8.
	 *  fonte: http://br.php.net/manual/pt_BR/function.json-encode.php
	 */

	//Fun��o para escape
	private static function escape($str){
	    return addcslashes($str, "\v\t\n\r\f\"\\/");
	}

	private static function my_json_encode($in){

		$out = "";

		if (is_object($in)) {
			$class_vars = get_object_vars(($in));
			$arr = array();
			foreach ($class_vars as $key => $val) {
				$arr[$key] = "\"".self::escape($key)."\":\"{$val}\"";
			}
			$val = implode(',', $arr);
			$out .= "{{$val}}";
		}elseif (is_array($in)) {
			$obj = false;
			$arr = array();
			foreach($in AS $key => $val) {
				if(!is_numeric($key)) {
					$obj = true;
				}
				$arr[$key] = self::my_json_encode($val);
			}
			if($obj) {
				foreach($arr AS $key => $val) {
					$arr[$key] = "\"".self::escape($key)."\":{$val}";
				}
			$val = implode(',', $arr);
			$out .= "{{$val}}";
			}else {
				$val = implode(',', $arr);
				$out .= "[{$val}]";
			}
			}elseif (is_bool($in)) {
				$out .= $in ? 'true' : 'false';
			}elseif (is_null($in)) {
				$out .= 'null';
			}elseif (is_string($in)) {
				$out .= "\"".self::escape($in)."\"";
			}else {
				$out .= $in;
			}
		return "{$out}";
	}

	/*
	 *  Fun��o para converter um json em formato array substituindo a fun��o do PHP "json_decode"
	 *  A fun��o do PHP "json_decode" s� consegue converter dados com codifica��o UTF-8.
	 *  fonte: http://br.php.net/manual/pt_BR/function.json-decode.php
	 */
	private static function my_json_decode ($json){
		$json = str_replace(array("\\\\", "\\\""), array("&#92;", "&#34;"), $json);
		$parts = preg_split("@(\"[^\"]*\")|([\[\]\{\},:])|\s@is", $json, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
		//print_r($parts);
		foreach ($parts as $index => $part){
          if (strlen($part) == 1){
              switch ($part){
                  case "[":
                  case "{":
                      $parts[$index] = "array(";
                      break;
                  case "]":
                  case "}":
                      $parts[$index] = ")";
                      break;
                  case ":":
                    $parts[$index] = "=>";
                    break;
                  case ",":
                    break;
                  default:
                      return null;
              }
          } else {
              if ((substr($part, 0, 1) != "\"") || (substr($part, -1, 1) != "\"")){
                  //return null;
              }
          }
      }
		$json = str_replace(array("&#92;", "&#34;", "$"), array("\\\\", "\\\"", "\\$"), implode("", $parts));
		//return eval("return $json;");
		return eval("return str_replace('\/','/',$json);");
  }


    // ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
    // Fun��o: password_hash
    // Prop�sito: Gerar hash da senha conforme o tipo de criptografia.
    // Utiliza��o: password_hash($password_clear, $enc_type)
    // - $password_clear: A senha em texto plano � ser codificada.
    // - $enc_type: Tipo de hash para criptografar.
    // - md5, sha, ssha ou clear.
    // Retorno: String do hash gerado.
    // Fonte : http://www.pierin.com/Publicacoes-9-php-ldap_criar_hash_da_senha_em_md5_sha_e_ssha.html
    // ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
	public function password_hash($password_clear, $enc_type){
        $enc_type = strtolower( $enc_type );
        switch( $enc_type ) {
            case 'md5':
                $new_value = md5($password_clear);
                break;

            case 'sha':
                if( function_exists('sha1') ) {
                    // use php 4.3.0+ sha1 function, if it is available.
                    $new_value = sha1( $password_clear);

                } elseif( function_exists( 'mhash' ) ) {
                    $new_value = mhash( MHASH_SHA1, $password_clear);

                } else {
                    print('Your PHP install does not have the mhash() function. Cannot do SHA hashes.');
                }
                break;

            case 'crypt':
                $new_value = preg_replace("/\W/", "", crypt($password_clear));
                break;

            case 'clear':
            default:
                $new_value = $password_clear;
        }

        return $new_value;
    }


}




/*
 * Formata��o de Numeros
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
      return "N�O PREVISTO";
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
    $singular=array('centavo', 'real', 'mil', 'milh�o', 'bilh�o', 'trilh�o', 'quatrilh�o', 'quintilh�o', 'sextilh�o', 'septilh�o', 'octilh�o', 'eneatilh�o');
    $plural=array('centavos', 'reais', 'mil', 'milh�es', 'bilh�es', 'trilh�es', 'quatrilh�es', 'quintilh�es', 'sextilh�es', 'septilh�es', 'octilh�es', 'eneatilh�es');
    $c=array('', 'cento', 'duzentos', 'trezentos', 'quatrocentos', 'quinhentos', 'seiscentos', 'setecentos', 'oitocentos', 'novecentos');
    $d=array('', 'dez', 'vinte', 'trinta', 'quarenta', 'cinquenta', 'sessenta', 'setenta', 'oitenta', 'noventa');
    $d10=array('dez', 'onze', 'doze', 'treze', 'quatorze', 'quinze', 'dezesseis', 'dezesete', 'dezoito', 'dezenove');
    $u=array('', 'um', 'dois', 'tr�s', 'quatro', 'cinco', 'seis', 'sete', 'oito', 'nove');
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

// FUN��O DE FONETIZA��O EM LINGUA PORTUGUESA
function fonetica($texto) {
  $texto = trim(strtoupper($texto));
  $texto.=" ";
  $fonetica="";

//echo "texto ".$texto;
  $vog = array("A","E","I","O","U","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","Y","�","U","�","�","�","�");
  $vogA = array("A","�","�","�","�","�","�");
  $vogIE = array("E","I","�","�","�","�","�","�","�","�","Y","�");
  $vogSA=array("E","I","O","U","�","�","�","�","�","�","�","�","�","�","�","�","�","Y","�","U","�","�","�","�");
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
      case '�': $fonetica.='A'; break;
      case '�': $fonetica.='A'; break;
      case '�': $fonetica.='A'; break;
      case '�': $fonetica.='A'; break;
      case '�': $fonetica.='A'; break;
      case '�': $fonetica.='A'; break;
      case '�': $fonetica.='E'; break;
      case '�': $fonetica.='E'; break;
      case '�': $fonetica.='E'; break;
      case '�': $fonetica.='E'; break;
      case '�': $fonetica.='E'; break;
      case '�': $fonetica.='I'; break;
      case '�': $fonetica.='I'; break;
      case '�': $fonetica.='I'; break;
      case '�': $fonetica.='I'; break;
      case '�': $fonetica.='O'; break;
      case '�': $fonetica.='O'; break;
      case '�': $fonetica.='O'; break;
      case '�': $fonetica.='O'; break;
      case '�': $fonetica.='O'; break;
      case '�': $fonetica.='I'; break;
      case 'Y': $fonetica.='I'; break;
      case ' ': $fonetica.='0';  break;
      case 'A': $fonetica.='A'; break;
      case 'E': $fonetica.='I'; break;
      case 'I': $fonetica.='I'; break;
      case 'B': $fonetica.='B'; break;
      case '�': $fonetica.='S'; break;
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
      case '�':
        $fonetica.='�';
        break;
      case 'N':
        //$vog = array("A","E","I","O","U");
        if ($texto[$i+1]=='H') {
          $fonetica.='�';
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
      case '�':
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
      case '�':
        if ($texto[$par]=='Q') {  break; }
        else { $fonetica.='U'; }
        break;
      case '�':
        if ($texto[$par]=='Q') {  break; }
        else { $fonetica.='U'; }
        break;
      case '�':
        if ($texto[$par]=='Q') {  break; }
        else { $fonetica.='U'; }
        break;
      case '�':
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
      case '�':
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
    $char1='&lt�&lt�';
    $char2='&gt�&gt�';
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

function fullUpper($string){
  return strtr(strtoupper($string), array(
      "�" => "�",
      "�" => "�",
      "�" => "�",
      "�" => "�",
      "�" => "�",
      "�" => "�",
      "�" => "�",
      "�" => "�",
      "�" => "�",
      "�" => "�",
      "�" => "�",
      "�" => "�",
      "�" => "�",
      "�" => "�",
      "�" => "�",
      "�" => "�",
    ));
} 


/*
 *  Fun��o que converte array para JSON
 */

    function JSONEncoder($campos){

		$aux = null;
		$auxp = null;

		if(is_array($campos)){

			foreach($campos AS $key => $vle){

				if(is_array($vle)){

					foreach($vle AS $k => $v){
						$auxp[$k] = utf8_encode($v);
					}

					$aux[$key] = $auxp;

				}else{

					$aux[$key] = utf8_encode($vle);

				}

			}

		}

		return json_encode($aux);

	}

/*
 *  Fun��o que converte JSON para Array
 */

    function JSONDecoder($campos){

		$campos = str_replace('\"', '"', $campos);
		$array = null;
		$arr = null;
		$aux = json_decode($campos);

		if((is_array($aux))||(is_object($aux))){

			foreach($aux AS $key => $vle){

				if((is_array($vle))||(is_object($vle))){

					foreach($vle AS $k => $v){
						$arr[$k] = utf8_decode($v);
					}

					$array[$key] = $arr;

				}else{

					$array[$key] = utf8_decode($vle);

				}

			}

		}

		return $array;

	}

?>
