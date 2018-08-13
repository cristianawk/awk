<?php

/*
 * carrega a classe a ser instanciada quando chamada
 */
function __autoload($classe){
	$pontos = array("./", "../", "../../");
    foreach($pontos AS $ponto){
		if(file_exists("{$ponto}class/{$classe}.class.php")){
			//echo "{$ponto}class/{$classe}.class.php\n";
			include_once "{$ponto}class/{$classe}.class.php";
		}
	}
}
//echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
$dados_empenho = null;

if($_GET['empenho']){

		$conn = connection::init();

		$sql = "SELECT * FROM tipo_unidade_medida ORDER BY nm_unidade_medida";
		$conn->query($sql);
		$tipo_unidade_medida = $conn->get_tupla();

		$sql = "SELECT * FROM produtos ORDER BY nm_produto";
		$conn->query($sql);
		$produtos = $conn->get_tupla();


		//echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
		$sql = "SELECT a.id_empenho, a.ds_empenho, a.id_unidade, b.nm_unidade, a.id_fornecedor, c.nm_fornecedor, c.ds_cnpj, 
				nr_items_contratados, ch_bloqueio, d.id_requisicao, d.ds_requisicao, d.nr_requisicao,
				to_char(dt_requisicao, 'DD/MM/YYYY') AS data_requisicao, to_char(hr_requisicao, 'hh24:mi') AS hora_requisicao
				FROM empenhos AS a 
				LEFT JOIN unidades_beneficiadas AS b USING (id_unidade) 
				LEFT JOIN fornecedores AS c USING (id_fornecedor) 
				LEFT JOIN requisicoes AS d ON (a.id_unidade=d.id_unidade AND a.id_empenho=d.id_empenho)
				WHERE UPPER(ds_empenho) = '".strtoupper($_GET['empenho'])."' AND d.id_requisicao = '".$_GET['requisicao']."'";
		//echo $sql; exit;
		$conn->query($sql);
		$dados_empenho = $conn->fetch_row();
		connection::close();
		//echo "<pre>"; print_r($dados_empenho); echo "</pre>"; exit;
	}

?>
<link type="text/css" href="../../css/mtg/mytablegrid.css" rel="stylesheet">
<link href="../../css/audita.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/mtg/mytablegrid.js"></script>
<form target="_self" enctype="multipart/form-data" method="post" name="frm_requisicao" id="frm_requisicao" action="">
<input type="hidden" name="id_empenho" id="id_empenho" value="<?php echo $dados_empenho['id_empenho']?>">
<input type="hidden" name="id_requisicao" id="id_requisicao" value="<?php echo $dados_empenho['id_requisicao']?>">
<input type="hidden" name="id_unidade" id="id_unidade" value="<?php echo $_GET['unidade']?>">
<table border="0" class="orgTableJanela">
<tr>
	<td>EMPRESA:</td>
	<td><?php echo $dados_empenho['ds_cnpj']?>  <?php echo $dados_empenho['nm_fornecedor']?></td>
</tr>
<tr>
	<td width="20%">EMPENHO:</td>
	<td><?php echo $dados_empenho['ds_empenho']?></td>
</tr>
<tr>
	<td>REQUISIÇÃO:</td>
	<td><input type="text" name="ds_requisicao" id="ds_requisicao" value="<?php echo $dados_empenho['ds_requisicao']?>" readOnly="true" class="neutro"></td>
</tr>
<tr>
	<td>DATA DA REQUISIÇÃO:</td>
	<td><input type="text" name="dt_requisicao" id="dt_requisicao" value="<?php echo $dados_empenho['data_requisicao']?>" readOnly="true" class="neutro"></td>
</tr>
<tr>
	<td>HORA DA REQUISIÇÃO:</td>
	<td><input type="text" name="hr_requisicao" id="hr_requisicao" value="<?php echo $dados_empenho['hora_requisicao']?>" readOnly="true" class="neutro"></td>
</tr>
</table>
</form>
<br>
<div id="mytable1" class="table_grid" style="position:relative; height: 260px" align="left"></div>
<p align="center"><a href="#" onclick="javascript:fechar_janela()">FECHAR</a></p>
<script type="text/javascript">
function fechar_janela(){
   parent.globalWin.hide();
}

var largura = '';
if((screen.width - 320) > 900){
    largura = 900;
} else {
    largura = screen.width - 320;
}

var medidaList = [
        <?php foreach($tipo_unidade_medida AS $tipo){ ?>
	    {value: '<?php echo $tipo['id_unidade_medida']?>', text: '<?php echo $tipo['nm_unidade_medida']?>'},
        <?php } ?>
	];


var produtoList = [
        <?php foreach($produtos AS $produto){ ?>
	    {value: '<?php echo $produto['id_produto']?>', text: '<?php echo $produto['nm_produto']?>'},
        <?php } ?>
	];

//Variavel de mensagem de erro
var erro_msg = '';

var tableModel = {

    options : {

            width: largura,
            //title: 'Nova Requisi??o',

            /*pager: {
	            total: 10,
	            currentPage: 1
	        },*/

            toolbar : {

                    elements: [MyTableGrid.SAVE_BTN], //MyTableGrid.DEL_BTN,

                    onSave: function(){
                            /**
                             * Pega os chaves e valores das linhas modificadas
                             */
                            var modifiedRows = tableGrid.getModifiedRows();
                            var obj = {};
                            for (var i = 0; i < modifiedRows.length; i++) {
                                var temp = {};
                                for (var p in modifiedRows[i]) {
                                   //alert(modifiedRows[i][p]);
                                    temp[p] = modifiedRows[i][p];
                                }
                                obj[i] = temp;
                                /*
                                 * Verifica se a quantidade de items da requisi??o n?o ? maior
                                 * que a quantidade de items.
                                 */
                                if(parseInt(temp.qt_item_contratado) <= 0){
                                    erro_msg += "Quantidade Contratada referente ao item "+temp.id_item_contratado+" j? terminou.\n";
                                }else if(parseInt(temp.qt_produto_requisicao) == 0){
                                    erro_msg += "Quantidade Inv?lida para a Requisi??o no item "+temp.id_item_contratado+".\n";
                                }/*else if(parseInt(temp.qt_produto_requisicao) > parseInt(temp.qt_item_contratado)){
                                    //alert(temp.qt_produto_requisicao+" "+temp.qt_item_contratado);
                                    erro_msg += "A Quantidade de items requisitados do item "+temp.id_item_contratado+" ? maior que a quantidade de items contratados.\n";
                                }*/

                            }
                            var modified_rows = Object.toJSON(obj);
                            //exit;

                            /*
                             * Envia os dados para ser incluidos na base de dados
                             */
                            if(erro_msg == ''){
                            new Ajax.Request("./alt_items.php", {
                                    method : "POST",
                                    parameters:  $('frm_requisicao').serialize()+"&modifiedRows="+modified_rows,
                                    onComplete: function(transport){
                                        //alert(transport.responseText); exit;
                                        var xmldoc = transport.responseXML;
                                        //alert(xmldoc);
                                        var flg = xmldoc.getElementsByTagName('flg')[0].firstChild.data;
                                        //alert(flg);
                                        if(flg == 1){
                                            alert(xmldoc.getElementsByTagName('mesg')[0].firstChild.data);
                                            //loadTabela();
                                            parent.loadEmpReq();
                                            fechar_janela();
                                        }else{
                                            alert(xmldoc.getElementsByTagName('mesg')[0].firstChild.data);
                                        }

                                    }
                            });

                        } else {
                            alert("OS SEGUINTES ERROS FORAM ENCONTRADOS:\n"+erro_msg);
                            erro_msg = '';
                        }

                    }

            }

        },
		/*
		 * Colunas da Tabela
		 */
        columnModel : [

            {
                id : 'id_item_contratado',
                title : 'Item',
                width : 50,
                editable: false
            },
            {
                id : 'id_produto',
                title : 'Produto / Serviço',
                width : 350,
                editable: false,
	            editor: new MyTableGrid.ComboBox({
	                list: produtoList
	            })
            },
            {
                id : 'qt_item_contratado',
                title : 'Quantidade Contratada',
                width : 180,
                editable: false
            },
            {
                id : 'id_unidade_medida',
                title : 'Unidade',
                width : 150,
                editable: false,
	            editor: new MyTableGrid.ComboBox({
	                list: medidaList
	            })
            },
            {
                id : 'qt_produto_requisicao',
                title : 'Quantidade Requerida',
                width : 170,
                editable: true
            },


        ],

		/*
		 * Busca os dados para preencher a tabela
		 */
        url: './cons_items.php?id=<?php echo $dados_empenho['id_empenho']?>&idr=<?php echo $dados_empenho['id_requisicao']?>'

}

/*
 * Carrega a Tabela de Edi??o
 */
window.onload = function() { loadTabela() }

function loadTabela(){
    tableGrid = new MyTableGrid(tableModel);
    tableGrid.render('mytable1');
}
</script>
