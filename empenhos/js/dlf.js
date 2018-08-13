/*
 *  Validação de Data
 *
 */

    data = new Date();
    dia = data.getDate();
    mes = data.getMonth();
    ano = data.getFullYear();

    meses = new Array(12);

    meses[0] = '01';
    meses[1] = '02';
    meses[2] = '03';
    meses[3] = '04';
    meses[4] = '05';
    meses[5] = '06';
    meses[6] = '07';
    meses[7] = '08';
    meses[8] = '09';
    meses[9] = '10';
    meses[10] = '11';
    meses[11] = '12';

    DIA_ATUAL = dia+"/"+meses[mes]+"/"+ano;

        function verificarData(campo, comp, texto_1, texto_2, f){
        /**
         * campo -> campo de comparação
         * comp -> valor a ser comparado
         * texto -> titulos para ser preenchidos
         * f -> flag case queira comparar com o dia atual
         */
        if(validarData(campo.value)){
            var erro = false;
            var err = false;
            var msg = "";
            var arrDataCampo = new Array;
            var arrDataCompr = new Array;

            arrDataCampo = campo.value.split("/");
            arrDataCompr = comp.split("/");

            /*
             * Verifica com a data de comparação
             */
            if(arrDataCampo[2] < arrDataCompr[2]){
                erro = true;
            }else if(arrDataCampo[2] == arrDataCompr[2]){
				if(arrDataCampo[1] < arrDataCompr[1]){
					erro = true;
                }else if(arrDataCampo[1] == arrDataCompr[1]){
					if(arrDataCampo[0] < arrDataCompr[0]){ erro = true; }
                }
			}

            /*
             * Verifica com a data do dia atual
             */
            if(f){
                if(arrDataCampo[2] > ano){
                    err = true;
                }else if(arrDataCampo[2] == ano){
                    if(arrDataCampo[1] > meses[mes]){
                        err = true;
                    }else if(arrDataCampo[1] == meses[mes]){
                        if(arrDataCampo[0] > dia){ err = true; }
                    }
                }
            }

            if((erro)||(err)){
                if(erro) alert("ERRO! A data do campo " + texto_1 + " (" + campo.value + ") não pode ser menor em relação a data " + texto_2 + " (" + comp + ") .");
                if(err) alert("ERRO! A data do campo " + texto_1 + " (" + campo.value + ") não pode ser maior que a data atual (" + DIA_ATUAL + ").");
                campo.focus();
                campo.value = "";
            }else{
                //alert(arrDataCampo);
                //alert(arrDataCompr);
            }

        }else{
            campo.focus();
            campo.value = "";
        }
    }



    function validarData(date){

      if(date != ""){

        var erro = false;
        var ardt = new Array;
        var ExpReg = new RegExp("(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/[12][0-9]{3}");
        ardt = date.split("/");
        if (date.search(ExpReg)==-1){
            erro = true;
		}else if(((ardt[1]==4)||(ardt[1]==6)||(ardt[1]==9)||(ardt[1]==11))&&(ardt[0]>30)){
            erro = true;
        }else if ( ardt[1]==2) {
            if ((ardt[0]>28)&&((ardt[2]%4)!=0)) { erro = true; }
            if ((ardt[0]>29)&&((ardt[2]%4)==0)) { erro = true; }
        }

        if(erro){
            alert( "A data " + date + " não é válida!!!");
            return false;
        }

        return true;

      }
    }


/*
 * Validar Telefone
 */

function MascaraTelefone(element){

    var value = "";

    value = element.value.replace(/\D/gi, "");
    value = value.replace(/^(\d{2})/, "($1)");
    value = value.replace(/\b(\d{4})/, "$1-");

    element.value = value;
}

//valida telefone
function ValidarTelefone(element){
        exp = /\(?\d{2}\)?\d{4}-\d{4}/;
        if(!exp.test(element.value))
                alert('Numero de Telefone Invalido!');
}


/*
 *  Validar CPF
 */

function validaCPF(cpf,campo) {
//  cpf = document.validacao.cpfID.value;
  erro = new String;
  var aux="";
  while ((cpf.indexOf('.')>-1) || (cpf.indexOf('/')>-1) || (cpf.indexOf('-')>-1)) {
    if (cpf.indexOf('.')>-1) {
      aux=cpf.replace('.','');
      cpf=aux;
    }
    if (cpf.indexOf('/')>-1) {
      aux=cpf.replace('/','');
      cpf=aux;
    }
    if (cpf.indexOf('-')>-1) {
      aux=cpf.replace('-','');
      cpf=aux;
    }
  }
  if (cpf.length < 11) erro += "Sao necessarios 11 digitos para verificacao do CPF! ";
  var nonNumbers = /\D/;
  if (nonNumbers.test(cpf)) erro += "A verificacao de CPF suporta apenas numeros! ";
  if (cpf == "00000000000" || cpf == "11111111111" || cpf == "22222222222" || cpf == "33333333333" || cpf == "44444444444" || cpf == "55555555555" || cpf == "66666666666" || cpf == "77777777777" || cpf == "88888888888" || cpf == "99999999999"){
    erro += "Numero de CPF invalido!"
  }
  var a = [];
  var b = new Number;
  var c = 11;
  for (i=0; i<11; i++){
    a[i] = cpf.charAt(i);
    if (i < 9) b += (a[i] * --c);
  }
  if ((x = b % 11) < 2) { a[9] = 0 }
  else { a[9] = 11-x }
  b = 0;
  c = 11;
  for (y=0; y<10; y++) b += (a[y] * c--);
  if ((x = b % 11) < 2) { a[10] = 0; } else { a[10] = 11-x; }
  if ((cpf.charAt(9) != a[9]) || (cpf.charAt(10) != a[10])){
    erro +="Digito verificador com problema!";
  }
  if (erro.length > 0){
    alert(erro);
    campo.value="";
    campo.focus();
    return false;
  }
  campo.value=cpf.substr(0,3)+"."+cpf.substr(3,3)+"."+cpf.substr(6,3)+"-"+cpf.substr(9,2);
  return true;
}
function validaCNPJ(CNPJ,campo) {
  erro = new String;
  if ((CNPJ.charAt(2) == ".") || (CNPJ.charAt(6) == ".") || (CNPJ.charAt(10) == "/") || (CNPJ.charAt(15) == "-")){
  //substituir os caracteres que nï¿½o sï¿½o nï¿½meros
    if(document.layers && parseInt(navigator.appVersion) == 4){
      x = CNPJ.substring(0,2);
      x += CNPJ. substring (3,6);
      x += CNPJ. substring (7,10);
      x += CNPJ. substring (11,15);
      x += CNPJ. substring (16,18);
      CNPJ = x;
    } else {
      CNPJ = CNPJ. replace (".","");
      CNPJ = CNPJ. replace (".","");
      CNPJ = CNPJ. replace ("-","");
      CNPJ = CNPJ. replace ("/","");
    }
    if (CNPJ.length != 14) { erro += "é necessário preencher corretamente o número do CNPJ! "; }
  }
  var nonNumbers = /\D/;
  if (nonNumbers.test(CNPJ)) {
    erro += "A verificação de CNPJ suporta apenas números! ";
  } else {
    var a = [];
    var b = new Number;
    var c = [6,5,4,3,2,9,8,7,6,5,4,3,2];
    for (i=0; i<12; i++){
      a[i] = CNPJ.charAt(i);
      b += a[i] * c[i+1];
    }
    if ((x = b % 11) < 2) { a[12] = 0 } else { a[12] = 11-x }
    b = 0;
    for (y=0; y<13; y++) {
      b += (a[y] * c[y]);
    }
    if ((x = b % 11) < 2) { a[13] = 0; }
    else { a[13] = 11-x; }
    if ((CNPJ.charAt(12) != a[12]) || (CNPJ.charAt(13) != a[13])){
      erro +="Dígito verificador com problema!";
    }
  }
  if (CNPJ=="00000000000000") { erro=""; }
  if (erro.length > 0){
    alert(erro);
    campo.value="";
    campo.focus();
    return false;
  }
  campo.value=CNPJ.substr(0,2)+"."+CNPJ.substr(2,3)+"."+CNPJ.substr(5,3)+"/"+CNPJ.substr(8,4)+"-"+CNPJ.substr(12,2)
  return true;
}

function checkDate(campo) {
  if (campo.value=="") {
    return true;
  }
      var datas=campo.value.replace("/","");
      datas = datas.replace("/","");
      campo.value=datas;
      if (campo.value.length==6) {
	campo.value=datas.substr(0,2)+"/"+datas.substr(2,2)+"/20"+datas.substr(4,2);
      } else {
	if (campo.value.length==8) {
	  campo.value=datas.substr(0,2)+"/"+datas.substr(2,2)+"/"+datas.substr(4,4);
	}
      }
  if (campo.value.length<8){
    alert("Data Invalida");
    campo.value="";
    campo.focus();
  }
  return false;
}

function cpfcnpj(campo) {
  if (campo.value.length>0) {
    var smascara= campo.value;
    if(document.layers && parseInt(navigator.appVersion) == 4){
      smascara = campo.value.substring(0,2);
      smascara += campo.value.substring (3,6);
      smascara += campo.value.substring (7,10);
      smascara += campo.value.substring (11,15);
      smascara += campo.value.substring (16,18);
    } else {
      smascara = campo.value.replace(".","");
      smascara = smascara.replace(".","");
      smascara = smascara.replace("-","");
      smascara = smascara.replace("/","");
    }
    if (smascara.length>11) {
      validaCNPJ(smascara,campo);
    } else {
      validaCPF(smascara,campo);
    }
  }
}

function FormatNumero(campo) {
  if (campo.type=='text') {
    var textFormat = "";
    var t=0;
    var str=campo.value;
    var dec= new Array;
    for (var j = 0; j < campo.value.length ; j++) {
      if (campo.value.indexOf(",")>(-1)) {
        dec=campo.value.split(",");
        str=dec[0];
      }
    }
    for (var j = 0; j < str.length ; j++) {
      str=str.replace('.','');
    }
    if (str.length != 0) {
      for (var k = str.length-1; k>=0 ; k--) {
        t++;
        if (t % 3 == 0) {
          textFormat = "." + str.substr(k,1) + textFormat;
        } else {
          textFormat =  str.substr(k,1) + textFormat;
        }
      }
      if (textFormat.substr(0,1) == ".") {
        campo.value = textFormat.substr(1,textFormat.length-1);
      } else {
        campo.value = textFormat;
      }
    }
    if (dec.length>0) {
      campo.value+=","+dec[1];
    }
  }
}

function decimal(campo, precisao){

if (campo.value != ""){
	//Tira todo digito alpha
	campo.value = campo.value.replace(/[A-Z]/gi, "");

	var dec = campo.value.split(",");
	var str = ",";

	if (precisao==0) {
		campo.value=dec[0];
		return true;
	}

	if (dec.length>1) {
		for (var i=0; i < precisao;i++) {
			if(dec[1].substr(i,1) != ""){
				str+=dec[1].substr(i,1);
			}else{
				str+="0";
			}
		}

	}else{
    	for(var i=0;i<precisao;i++){
			str+="0";
		}
	}

	campo.value=dec[0]+str;
	return true;
}
}


function checaNE(campo){
	if(campo.value.length==4){
	    campo.value=campo.value +"NE";
	}
}

	function validaNE(campo){
	var valor1=campo.value.substr(0,6);
	var valor2=campo.value.substr(6,6);

	  while(valor2.length!='6'){
	    valor2='0'+valor2;
	  }
	campo.value=valor1+valor2;
	}

	function checadata(campo){
	  if(campo.value.length==2){
	    campo.value=campo.value +"/";
	  }
	  if(campo.value.length==5){
	    campo.value=campo.value +"/";
	  }
	}

	function validanum(dados,flag){//alert(flag);
	  /*if (isNaN(dados.value)) {//alert('entrou');
	    alert('Somente numeros sao permitidos');
	    dados.focus();
	    dados.value="";
	  }*/

	  value = dados.value.replace(/\D/gi, "");



	  if(flag!='0'){//alert('incrementa 4');
	    while(value.length!=flag){//alert('4');
	      value='0'+value;
	      //alert(dados.value);
	    }
	  }

	  dados.value = value;

	}

	function validaemail(dados){
	  if(dados.value=="" || dados.value.indexOf('@')==-1 || dados.value.indexOf('.')==-1 ){
	    alert( "Preencha campo E-MAIL corretamente!" );
	    dados.focus();
	    dados.value="";
	    return false;
	  }
	}

    function validaBanco(element, f){

		var value = "";

		value = element.value.replace(/\D/gi, "");

		if(value != ""){
			if(value.length != f){
				for(;value.length < f;){
					value = '0'+value;
				}
			}

        }

		element.value = value;

    }


//  FUNCOES DO CALENDARIO... ......................................................//......................//.........CALENDARIO...........//.........................//.....................//................

function selected(cal, date) {
  cal.sel.value = date; // just update the date in the input field.
  //if (cal.sel.id == "sel1" || cal.sel.id == "sel3")
  cal.sel.focus();
  cal.callCloseHandler();
}

function closeHandler(cal) {
  cal.hide();
  cal = null;
  calendar = null;                        // hide the calendar
}

function showCalendar(id, format) {
  var el = document.getElementById(id);
  if (calendar != null) {
    // we already have some calendar created
    calendar.hide();
    calendar = null;
  } else {
    // first-time call, create the calendar.
    var cal = new Calendar(false, null, selected, closeHandler);

    calendar = cal;                  // remember it in the global var
    cal.setRange(1900, 2070);        // min/max year allowed.
    cal.create();

    calendar.setDateFormat(format);    // set the specified date format
    calendar.parseDate(el.value);      // try to parse the text in field
    calendar.sel = el;                 // inform it what input field we use
    calendar.showAtElement(el);        // show the calendar below it

  }

  return false;
}

var MINUTE = 60 * 1000;
var HOUR = 60 * MINUTE;
var DAY = 24 * HOUR;
var WEEK = 7 * DAY;

function isDisabled(date) {
  var today = new Date();
  return (Math.abs(date.getTime() - today.getTime()) / DAY) > 10;
}

//******************************** FINAL CALENDARIO *********************************//
