$(document).on("ready", init);// Inciamos el jquery
var objC = new init();
function init(){
	var tabla = $('#tblMarcas').dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5' ]
    });
	/*
		{
			"iDisplayLength": 2,
        "aLengthMenu": [10, 15, 20]
		}
	*/
	
	ListadoMarcas();// Ni bien carga la pagina que cargue el metodo
	$("#VerForm").hide();// Ocultamos el formulario
	$("form#frmMarcas").submit(SaveOrUpdate);// Evento submit de jquery que llamamos al metodo SaveOrUpdate para poder registrar o modificar datos

	$("#btnNuevo").click(VerForm);// evento click de jquery que llamamos al metodo VerForm

	//$("#liCatRed").click(function(event) {
      //    $("#Cargar").load('view/Marca.html');
        //  $.getScript("public/js/Marca.js");
    //});

	function SaveOrUpdate(e){
		e.preventDefault();// para que no se recargue la pagina
        $.post("./ajax/MarcaAjax.php?op=SaveOrUpdate", $(this).serialize(), function(r){// llamamos la url por post. function(r). r-> llamada del callback
            
            Limpiar();
            //$.toaster({ priority : 'success', title : 'Mensaje', message : r});
            swal("Mensaje del Sistema", r, "success");
			  ListadoMarcas();
			  OcultarForm();
	        
        });
	};

	function Limpiar(){
		// Limpiamos las cajas de texto
		$("#txtIdMarca").val("");
	    $("#txtNombre").val("");
	}

	function VerForm(){
		$("#VerForm").show();// Mostramos el formulario
		$("#btnNuevo").hide();// ocultamos el boton nuevo
		$("#VerListado").hide();// ocultamos el listado
	}

	function OcultarForm(){
		$("#VerForm").hide();// Mostramos el formulario
		$("#btnNuevo").show();// ocultamos el boton nuevo
		$("#VerListado").show();// ocultamos el listado
	}
}

function ListadoMarcas(){ 
	var tabla = $('#tblMarcas').dataTable(
		{   "aProcessing": true,
       		"aServerSide": true,
       		dom: 'Bfrtip',
	        buttons: [
	            'copyHtml5',
	            'excelHtml5',
	            'csvHtml5',
	            'pdfHtml5'
	        ],
        	"aoColumns":[
        	     	{   "mDataProp": "id"},
                    {   "mDataProp": "1"},
                    {   "mDataProp": "2"}

        	],"ajax": 
	        	{
	        		url: './ajax/MarcaAjax.php?op=list',
					type : "get",
					dataType : "json",
					
					error: function(e){
				   		console.log(e.responseText);	
					}
	        	},
	        "bDestroy": true

    	}).DataTable();

};


function eliminarMarca(id){// funcion que llamamos del archivo ajax/MarcaAjax.php?op=delete linea 53
	bootbox.confirm("¿Esta Seguro de eliminar la Marca?", function(result){ // confirmamos con una pregunta si queremos eliminar
		if(result){// si el result es true
			$.post("./ajax/MarcaAjax.php?op=delete", {id : id}, function(e){// llamamos la url de eliminar por post. y mandamos por parametro el id 
                
				
				swal("Mensaje del Sistema", e, "success");

				ListadoMarcas();
            });
		}
		
	})
}

function cargarDataMarca(id, nombre){// funcion que llamamos del archivo ajax/MarcaAjax.php linea 52
		$("#VerForm").show();// mostramos el formulario
		$("#btnNuevo").hide();// ocultamos el boton nuevo
		$("#VerListado").hide();

		$("#txtIdMarca").val(id);// recibimos la variable id a la caja de texto txtIdMarca
	    $("#txtNombre").val(nombre);// recibimos la variable nombre a la caja de texto txtNombre
}