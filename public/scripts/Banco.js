$(document).on("ready", init);// Inciamos el jquery

function init(){

    $('#tblBancos').dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });
    
	ListadoBancos();// Ni bien carga la pagina que cargue el metodo
	ComboTipo_Documento();
	$("#VerForm").hide();// Ocultamos el formulario
	$("form#frmBanco").submit(SaveOrUpdate);// Evento submit de jquery que llamamos al metodo SaveOrUpdate para poder registrar o modificar datos
	
	$("#btnNuevo").click(VerForm);// evento click de jquery que llamamos al metodo VerForm

	function SaveOrUpdate(e){
		e.preventDefault();

        var formData = new FormData($("#frmBanco")[0]);

        $.ajax({

                url: "./ajax/BancoAjax.php?op=SaveOrUpdate",

                type: "POST",

               data: formData,

                contentType: false,

                processData: false,

                success: function(datos)

                {

                    swal("Mensaje del Sistema", datos, "success");
					ListadoBancos();
					OcultarForm();
                }

            });
	};

	function Limpiar(){
		// Limpiamos las cajas de texto
		$("#txtIdBanco").val("");
	    $("#txtDescripcion").val("");

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

function ListadoBancos(){ 
	var tabla = $("#tblBancos")
    .dataTable({
      aProcessing: true,
      aServerSide: true,
      dom: "Bfrtip",
      buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
      aoColumns: [
        { mDataProp: "0" },
        { mDataProp: "1" },
        { mDataProp: "2" },
      

      ],
      ajax: {
        url: "./ajax/BancoAjax.php?op=list",
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

function eliminarBanco(id){// funcion que llamamos del archivo ajax/CategoriaAjax.php?op=delete linea 53
	bootbox.confirm("¿Esta Seguro de eliminar la Sucursal?", function(result){ // confirmamos con una pregunta si queremos eliminar
		if(result){// si el result es true
			$.post("./ajax/BancoAjax.php?op=delete", {id : id}, function(e){// llamamos la url de eliminar por post. y mandamos por parametro el id 
                swal("Mensaje del Sistema", e, "success");
				ListadoBancos();

            });
		}
		
	})
}

function cargarDataBanco(id, razon_social){// funcion que llamamos del archivo ajax/CategoriaAjax.php linea 52
		$("#VerForm").show();// mostramos el formulario
		$("#btnNuevo").hide();// ocultamos el boton nuevo
		$("#VerListado").hide();// ocultamos el listado

		$("#txtIdbanco").val(id);// recibimos la variable id a la caja de texto txtIdMarca
	    $("#txtDescripcion").val(razon_social);

 	}	


