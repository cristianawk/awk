<?php

?>
<p><b><?php echo $modulo_arquivo['nm_rotina']?></b></p>
<p>Importar produtos através de um arquivo (.xls, .txt).</p>
<p align="center">
Selecione o arquivo e depois click no botão <b><i>IMPORTAR</i></b>
<br><br>
<input name="upload" id="upload" type="file" size="50"/>
<br><br>
<input type="button" name="imp" id="imp" onclick="importFile()" Value="IMPORTAR" class='botao'>
<p>
<font color="red">
<p>As Colunas do arquivo devem seguir esta ordem:</p>
<p><b>id_produto | nm_produto | id_tipo</b></p></font>
<div id="aqui"></div>
<script>
	function importFile(){
		if($F('upload') != ""){
			extensoes_permitidas = new Array(".txt", ".xls");
			 //recupero a extensão deste nome de arquivo
			extensao = ($F('upload').substring($F('upload').lastIndexOf("."))).toLowerCase();
			//alert($F('upload'));
			//alert (extensao);
			for (var i = 0; i < extensoes_permitidas.length; i++) {
				if (extensoes_permitidas[i] == extensao) {
					permitida = true;
					break;
				}else{
					permitida = false;
				}
			}
			/*
			 * Se o arquivo é valido executa a função, senão alerta o erro
			 */
			if(permitida){

				$('aqui').innerHTML = '';

				var form_upload = new Element("form", { id:"form_upload", name:"form_upload", method:"POST", enctype:"multipart/form-data", target:"iframe_upload", action:"./modulos/administrativo/inc_import_produto.php", style:"display: none;"});
				var iframe = new Element("iframe", {name:"iframe_upload", id:"iframe_upload", style:"display: block;"});

				var up = Element.clone($('upload'));

				form_upload.appendChild(up);

				//insere na página o iframe
				document.body.insert(form_upload);
				$("aqui").insert(iframe);
				form_upload.submit();
				

			}else{
				alert("Somente são permitidos arquivos com extensões .xls e .txt!")
			}

		}
	}

</script>
