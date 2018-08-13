<?php
//echo "<pre>"; print_r($_POST); echo "</pre>"; exit;
$sql = "SELECT a.id_empenho, a.ds_empenho, a.id_unidade, b.nm_unidade, a.dt_inicio,
		to_char(a.dt_empenho, 'DD/MM/YYYY') AS data_empenho, a.id_fornecedor, c.nm_fornecedor,
        nr_items_contratados, ch_bloqueio
		FROM empenhos AS a JOIN unidades_beneficiadas AS b USING (id_unidade)
		JOIN fornecedores AS c USING (id_fornecedor)
		WHERE id_empenho = ".$_POST['id_empenho'];
//echo $sql; exit;
$conn->query($sql);
$dados = $conn->fetch_row();

$sql = "SELECT * FROM tipo_unidade_medida ORDER BY nm_unidade_medida";
$conn->query($sql);
$tipo_unidade_medida = $conn->get_tupla();

$sql = "SELECT * FROM produtos ORDER BY nm_produto";
$conn->query($sql);
$produtos = $conn->get_tupla();

if($dados['ch_bloqueio'] == 'S'){
    $edicao = 'false';
} else {
    $edicao = 'true';
}


//echo "<pre>"; print_r($dados); echo "</pre>"; exit;
?>

<script type="text/javascript">

var largura = '';
if((screen.width - 320) > 950){
    largura = 950;
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

var tableModel = {

    options : {

            width: largura,
            //title: '<?php echo $modulo_arquivo['nm_rotina']?>',

            /*pager: {
	            total: 10,
	            currentPage: 1
	        },*/

            toolbar : {

                    elements: [MyTableGrid.DEL_BTN,MyTableGrid.SAVE_BTN],

                    onSave: function(){
                            /**
                             * Pega os chaves e valores das novas linhas
                             */
                            var newRowsAdded = tableGrid.getNewRowsAdded();
                            var obj = {};
                            for (var i = 0; i < newRowsAdded.length; i++) {
                                var temp = {};
                                for (var p in newRowsAdded[i]) {
                                    temp[p] = newRowsAdded[i][p];
                                }
                                obj[i] = temp;
                            }
                            var added_rows = Object.toJSON(obj);

                            /**
                             * Pega os chaves e valores das linhas modificadas
                             */
                            var modifiedRows = tableGrid.getModifiedRows();
                            var obj = {};
                            for (var i = 0; i < modifiedRows.length; i++) {
                                var temp = {};
                                for (var p in modifiedRows[i]) {
									temp[p] = modifiedRows[i][p];
                                }
                                obj[i] = temp;
                            }

                            var modified_rows = Object.toJSON(obj);

                            /**
                             * Pega os chaves e valores das linhas deletadas
                             */
                            var deletedRows = tableGrid.getDeletedRows();
                            var obj = {};
                            for (var i = 0; i < deletedRows.length; i++) {
                                var temp = {};
                                for (var p in deletedRows[i]) {
                                    temp[p] = deletedRows[i][p];
                                }
                                obj[i] = temp;
                            }
                            var deleted_rows = Object.toJSON(obj);

                            new Ajax.Request("./modulos/auditoria/inc_items.php", {
                                    method : "POST",
                                    parameters: { "id": "<?php echo $dados['id_empenho']?>" , "dt": "<?php echo $dados['dt_inicio']?>" , "newRowsAdded": [ added_rows ],  "modifiedRows" : [ modified_rows ], "deletedRows" : [ deleted_rows ] },
                                    onComplete: function(transport){
                                        //alert(transport.responseText); exit;
                                        var xmldoc = transport.responseXML;
                                        //alert(xmldoc);
                                        var flg = xmldoc.getElementsByTagName('flg')[0].firstChild.data;
                                        //alert(flg);
                                        if(flg == 1){
                                            alert(xmldoc.getElementsByTagName('mesg')[0].firstChild.data);
                                            loadTabela();
                                        }else{
                                            alert(xmldoc.getElementsByTagName('mesg')[0].firstChild.data);
                                        }

                                    }
                            });

                    }

            }

        },

        onCellBlur : function(element, value, x, y, id) {
	        //alert(value);
	    },

        columnModel : [

            {
                id : 'id_item_contratado',
                title : 'Item',
                width : 50,
                editable: false,
                editor: new MyTableGrid.CellCheckbox({
                    selectable : true
                })
            },
            {
                id : 'id_produto',
                title : 'Produto / Serviço',
                width : 280,
                editable: <?php echo $edicao?>,
	            editor: new MyTableGrid.ComboBox({
	                list: produtoList
	            })
            },
            {
                id : 'id_unidade_medida',
                title : 'Unidade',
                width : 100,
                editable: <?php echo $edicao?>,
	            editor: new MyTableGrid.ComboBox({
	                list: medidaList
	            })
            },
            {
                id : 'qt_item_contratado',
                title : 'Quantidade Contratada',
                width : 180,
                editable: true
            },
            {
                id : 'vl_item_contratado',
                title : 'Valor Unitário Encontrado',
                width : 180,
                editable: <?php echo $edicao?>
            },
            {
                id : 'perm',
                title : 'Alteração',
                width : 70,
                editable: false
            },
            {
                id : 'qt_prod_requisicao',
                title : 'Qt Já Req',
                width : 70,
                editable: false
            }

        ],

        url: './modulos/auditoria/cons_items.php?id=<?php echo $dados['id_empenho']?>&nr=<?php echo $dados['nr_items_contratados']?>'

}


window.onload = function() { loadTabela() }

function loadTabela(){
    tableGrid = new MyTableGrid(tableModel);
    tableGrid.render('mytable1');
}

</script>
<form target="_self" enctype="multipart/form-data" method="post" name="frm_item" id="frm_item" >
<input type="hidden" name="hdn_acao" id="hdn_acao" value="inc">
<input type="hidden" name="id_empenho" id="id_empenho" value="<?php echo $dados['id_empenho']?>">
<table border="0" width="100%" class="orgTableJanela">
<tr><th colspan="2"><?php echo $modulo_arquivo['nm_rotina']?></th></tr>
<tr>
	<td width="20%">NUMERO EMPENHO:</td>
	<td><?php echo $dados['ds_empenho']?></td>
</tr>
<tr>
	<td>NOME FORNECEDOR:</td>
	<td><?php echo $dados['nm_fornecedor']?></td>
</tr>
<tr>
	<td>UNIDADE BENEFICIADA:</td>
	<td><?php echo $dados['nm_unidade']?></td>
</tr>
<tr>
	<td>DATA DO EMPENHO:</td>
	<td><?php echo $dados['data_empenho']?></td>
</tr>
<?php if($dados['ch_bloqueio'] == 'S'){ ?>
<tr>
	<th class="erro just" colspan="2">Atenção! A Edição ou alteração dos dados dos itens estão restringidos por causa de requisições já solicitadas.
	<br>Só será permitida a alteração de quantidade, respeitando os sinais da coluna <i>Alteração</i>:
		<br><br>SIM : <i>Pode alterar/deletar o registro</i>
		<br>NÃO : <i>Nenhum campo pode ser alterado/deletado</i>
		<br>QT+ : <i>Alteração da Quantidade é permitida somente com valor superior a já cadastrada</i>
		<br>QT- : <i>Alteração da Quantidade é permitida desde que o valor minimo seja igual ou mair a Qt Já Requisitada</i>
	</th>
</tr>
<?php } ?>
</table>
<br>
<div id="mytable1" style="position:relative; height: 350px" align="left" class="table_grid"></div>
</form>
