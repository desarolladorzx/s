$(document).on("ready", init);

function init(){
    ListadoStockArticuloVencimiento()

    $('#fecha_inicio_vencimiento').on('input',()=>ListadoStockArticuloVencimiento(
    ))
    
    $('#fecha_fin_vencimiento').on('input',()=>ListadoStockArticuloVencimiento(
        ))

    function ListadoStockArticuloVencimiento(){
			
		    var idsucursal = $("#txtIdSucursal").val();
            			
            var tabla = $('#tblStockArticulosVencimiento').dataTable(
		{   "aProcessing": true,
       		"aServerSide": true,
       		dom: 'Bfrtip',
	        buttons: [
	           
	            'excelHtml5',
	            
	        ],
        	"aoColumns":[
				{"mDataProp":"0"},
				{"mDataProp":"6"},
				{"mDataProp":"1"},
				{"mDataProp":"2"},
				{"mDataProp":"3"},
				// {"mDataProp":"4"},
				{"mDataProp":"5"},
				{"mDataProp":"7"},
				{"mDataProp":"8"},
				{"mDataProp":"9"},
				{"mDataProp":"10"},
				{"mDataProp":"11"},
				{"mDataProp":"12"},
				{"mDataProp":"13"},
				{"mDataProp":"14"},
				{"mDataProp":"15"}

        	],"ajax": 
	        	{
	        		url: './ajax/ConsultasComprasAjax.php?op=listStockArticulosVencimiento',
					type : "get",
					data:{fecha_inicio: $('#fecha_inicio_vencimiento').val()
                ,fecha_fin:$('#fecha_fin_vencimiento').val()
                },
					dataType : "json",
					
					error: function(e){
				   		console.log(e.responseText);	
					}
	        	},
	        "bDestroy": true

    	}).DataTable();
	} 
}
