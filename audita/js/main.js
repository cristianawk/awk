$(function(){

	function formatDate(value){
        return value ? value.dateFormat('d M, Y') : '';
    };
	
    function formatBoolean(value){
        return value == 1 ? 'Sim' : 'Não';  
    };
	
	var cm = new Ext.grid.ColumnModel(
		[
			{
				header: "Nome",
				dataIndex: 'nome',
				width: 220,
				sortable: true
			},			

			{
				header: "Email",
				dataIndex: 'email',
				width: 220,
				sortable: true	
			},		

			{
				header: "Data Cadastro",
				dataIndex: 'data_cadastro',
				width: 220,
				sortable: true,
				renderer: formatDate
			},

			{
				header: "Receber Noticias",
				dataIndex: 'news',
				width: 220,
				renderer: formatBoolean		
			}				
        ]
	);
    cm.defaultSortable = true;	
	
	ds = new Ext.data.Store({
		proxy:  new Ext.data.ScriptTagProxy({
			url:'listar_usuarios.php'
		}),
		reader:  new Ext.data.JsonReader({
			root: 'resultado', 
			id: 'id_usuario'
		},
			[
				{name: 'nome', mapping: 'nome', type: 'string'},
				{name: 'email', mapping: 'email', type: 'string'}, 
				{name: 'data_cadastro', mapping: 'data_cadastro', type:'date', dateFormat:'Y-m-d'},
				{name: 'news', mapping: 'news', type:'boolean'}
			]
		)
	});

	var grid = new Ext.grid.EditorGrid('grid', {
        ds: ds,
        cm: cm,
        enableColLock:false		
    });	
	grid.render();
	ds.load();		
});