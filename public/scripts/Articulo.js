$(document).on("ready", init);

function init(){

	var tabla = $('#tblArticulos').dataTable({
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5',
        ]
    });

	ListadoArticulos();
	ComboCategoria();
	ComboMarca();//Permite cargar la lista desplegable de Categorias.
	ComboUM();
	$("#VerForm").hide();
	$("#txtRutaImgArt").hide();
	$("form#frmArticulos").submit(SaveOrUpdate);
	
	$("#btnNuevo").click(VerForm);

	function SaveOrUpdate(e){
			e.preventDefault();

	        var formData = new FormData($("#frmArticulos")[0]);
			console.log($("#frmArticulos")[0])
	        $.ajax({

	                url: "./ajax/ArticuloAjax.php?op=SaveOrUpdate",
	                type: "POST",
	                data: formData,
	                contentType: false,
	                processData: false,
	                success: function(datos)

	                {
							
	                    // swal("Mensaje del Sistema", datos, "success");
						//   ListadoArticulos();
						//   OcultarForm();
						//   $('#frmArticulos').trigger("reset");
						  swal(
							{
							  title: "Mensaje del Sistema",
							  text: datos,
							  icon: "success",
							},
							function (confirm) {
							  if (confirm) {
								location.reload();
							  }
							}
						  );

	                }

	            });
	};

	function ComboCategoria(){
			$.post("./ajax/ArticuloAjax.php?op=listCategoria", function(r){
	            $("#cboCategoria").html(r);
	        });
	}

	function ComboMarca(){
		$.post("./ajax/ArticuloAjax.php?op=listMA", function(r){
			$("#cboMarca").html(r);
		});
}

	function ComboUM(){
			$.post("./ajax/ArticuloAjax.php?op=listUM", function(r){
	            $("#cboUnidadMedida").html(r);
	        });
	}

	function Limpiar(){
			$("#txtIdArticulo").val("");
		    $("#txtNombre").val("");
	}

	function VerForm(){
			$("#VerForm").show();
			$("#btnNuevo").hide();
			$("#VerListado").hide();
	}

	function OcultarForm(){
			$("#VerForm").hide();// Mostramos el formulario
			$("#btnNuevo").show();// ocultamos el boton nuevo
			$("#VerListado").show();
	}
}
function ListadoArticulos(){ 
	var tabla = $('#tblArticulos').dataTable(
		{   "aProcessing": true,
       		"aServerSide": true,
       		dom: 'Bfrtip',
	        buttons: [
	          
	            'excelHtml5',
	          
	        ],
        	"aoColumns":[
        	     	{   "mDataProp": "id"},
                    {   "mDataProp": "1"},
                    {   "mDataProp": "2"},
                    {   "mDataProp": "3"},
                    {   "mDataProp": "4"},
                    {   "mDataProp": "5"},
                    {   "mDataProp": "6"},
					{   "mDataProp": "7"},
					{   "mDataProp": "8"},
					{   "mDataProp": "9"},
					{   "mDataProp": "10"},
					{   "mDataProp": "11"},
					

        	],"ajax": 
	        	{
	        		url: './ajax/ArticuloAjax.php?op=list',
					type : "get",
					dataType : "json",
					
					error: function(e){
				   		console.log(e.responseText);	
					}
	        	},
	        "bDestroy": true

    	}).DataTable();

    };
function eliminarArticulo(id){
	bootbox.confirm("¿Esta Seguro de eliminar la Articulo?", function(result){
		if(result){
			$.post("./ajax/ArticuloAjax.php?op=delete", {id : id}, function(e){
                
				swal("Mensaje del Sistema", e, "success");
				ListadoArticulos();
            });
		}	
	})
}
function cargarDataArticulo(idarticulo, idcategoria, idmarca, idunidad_medida, nombre, descripcion, imagen,stock_min
	,precio_compra
	,precio_final
	,precio_distribuidor
	,precio_superdistribuidor
	,precio_representante


	,lote
	,barcode
	,interno_id

	
	){
		$("#VerForm").show();
		$("#btnNuevo").hide();
		$("#VerListado").hide();

		$("#txtIdArticulo").val(idarticulo);
	    $("#cboCategoria").val(idcategoria);
		$("#cboMarca").val(idmarca);
	    $("#cboUnidadMedida").val(idunidad_medida);
	    $("#txtNombre").val(nombre);
	    $("#txtDescripcion").val(descripcion);
	   // $("#imagenArt").val(imagen);
	    $("#txtRutaImgArt").val(imagen);

		$("#txtStockMinimo").val(stock_min);

		$("#txtprecio_compra").val(precio_compra);
		$("#txtprecio_final").val(precio_final);
		$("#txtprecio_distribuidor").val(precio_distribuidor);
		$("#txtprecio_superdistribuidor").val(precio_superdistribuidor);
		$("#txtprecio_representante").val(precio_representante);



		$("#txtLote").val(lote);
		// $("#imagenArt").val(imagen);
		 $("#txtCodigoBarra").val(barcode);
 
		 $("#txtCodigoInterno").val(interno_id);



	    // $("#txtRutaImgArt").show();
	    // $("#txtRutaImgArt").show();

	    //$("#txtRutaImgArt").prop("disabled", true);
}