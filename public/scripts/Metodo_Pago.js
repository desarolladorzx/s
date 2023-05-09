$(document).on("ready", init);// Inciamos el jquery

function init(){

    $('#tblMetodo_Pago').dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });
    
	ListadoMetodoPago();// Ni bien carga la pagina que cargue el metodo
	ComboTipo_Documento();
	$("#VerForm").hide();// Ocultamos el formulario
	$("form#frmMetodo_Pago").submit(SaveOrUpdate);// Evento submit de jquery que llamamos al metodo SaveOrUpdate para poder registrar o modificar datos
	
	$("#btnNuevo").click(VerForm);// evento click de jquery que llamamos al metodo VerForm

	function SaveOrUpdate(e){
		e.preventDefault();

        var formData = new FormData($("#frmMetodo_Pago")[0]);

        $.ajax({

                url: "./ajax/Metodo_PagoAjax.php?op=SaveOrUpdate",

                type: "POST",

               data: formData,

                contentType: false,

                processData: false,

                success: function(datos)

                {

                    swal("Mensaje del Sistema", datos, "success");
                    ListadoMetodoPago();
					OcultarForm();
          Limpiar()
                }

            });
	};

	function Limpiar(){
		// Limpiamos las cajas de texto
		$("#txtIdmetodo_pago").val("");
	  $("#txtDescripcion").val("");
	  $("#txtCodigo").val("");

	}

	function VerForm(){
		$("#VerForm").show();// Mostramos el formulario
		$("#btnNuevo").hide();// ocultamos el boton nuevo
		$("#VerListado").hide();
	}


	function OcultarForm(){
		$("#VerForm").hide();// Mostramos el formulario
		$("#btnNuevo").show();// ocultamos el boton nuevo
		$("#VerListado").show();
	}
}

function ListadoMetodoPago(){ 
	var tabla = $("#tblMetodo_Pago")
    .dataTable({
      aProcessing: true,
      aServerSide: true,
      dom: "Bfrtip",
      buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
      aoColumns: [
        { mDataProp: "0" },
        { mDataProp: "1" },
        { mDataProp: "2" },
        { mDataProp: "3" },
        { mDataProp: "4" },
      

      ],
      ajax: {
        url: "./ajax/Metodo_PagoAjax.php?op=list",
        type: "get",
        dataType: "json",

        error: function (e) {
          console.log(e.responseText);
        },
      },
      bDestroy: true,
    })
    .DataTable();
    };

function eliminarMetodoPago(id){// funcion que llamamos del archivo ajax/CategoriaAjax.php?op=delete linea 53
	bootbox.confirm("¿Esta Seguro de eliminar la Sucursal?", function(result){ // confirmamos con una pregunta si queremos eliminar
		if(result){// si el result es true
			$.post("./ajax/Metodo_PagoAjax.php?op=delete", {id : id}, function(e){// llamamos la url de eliminar por post. y mandamos por parametro el id 
                swal("Mensaje del Sistema", e, "success");
                ListadoMetodoPago();

            });
		}
		
	})
}

function cargarDataMetodoPago(id, descripcion,numero){// funcion que llamamos del archivo ajax/CategoriaAjax.php linea 52
		$("#VerForm").show();// mostramos el formulario
		$("#btnNuevo").hide();// ocultamos el boton nuevo
		$("#VerListado").hide();// ocultamos el listado

		$("#txtIdmetodo_pago").val(id);// recibimos la variable id a la caja de texto txtIdMarca
	    $("#txtDescripcion").val(descripcion);
	    $("#txtCodigo").val(numero);

 	}	


