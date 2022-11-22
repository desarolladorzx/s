$(document).on("ready", init);// Inciamos el jquery
function init(){
/* 	$('#tblCliente').dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    }); */

	ListadoCliente();// Ni bien carga la pagina que cargue el metodo
	ComboTipo_Documento();
	$("#VerForm").hide();// Ocultamos el formulario
	$("form#frmCliente").submit(SaveOrUpdate);// Evento submit de jquery que llamamos al metodo SaveOrUpdate para poder registrar o modificar datos
	$("#btnNuevo").click(VerForm);// evento click de jquery que llamamos al metodo VerForm

	function SaveOrUpdate(e){
		e.preventDefault();// para que no se recargue la pagina
        $.post("./ajax/ClienteAjax.php?op=SaveOrUpdate", $(this).serialize(), function(r){// llamamos la url por post. function(r). r-> llamada del callback
            Limpiar();
            ListadoCliente();
            //$.toaster({ priority : 'success', title : 'Mensaje', message : r});
            swal("Mensaje del Sistema", r, "success");
            OcultarForm();
        });
	};

	function Limpiar(){
		// Limpiamos las cajas de texto
		$("#txtIdPersona").val("");
		$("#txtNombre").val("");
		$("#txtApellido").val("");
	    $("#txtNum_Documento").val("");
	    $("#txtDireccion_Departamento").val("");
	    $("#txtDireccion_Provincia").val("");
	    $("#txtDireccion_Distrito").val("");
	    $("#txtDireccion_Calle").val("");
	    $("#txtTelefono").val("");
		$("#txtTelefono_2").val("");
	    $("#txtEmail").val("");
	    $("#txtNumero_cuenta").val("");
		$("#txtIdEmpleado").val("");
		$("#txtEmpleado").val("");
		$("#txtIdEmpleado_modificado").val("");
		$("#txtEmpleado_modificado").val("");
		$("#txtFecha_creacion").val("");
		$("#txtFecha_modificacion").val("");
	}

	function ComboTipo_Documento() {
        $.get("./ajax/ClienteAjax.php?op=listTipo_DocumentoPersona", function(r) {
                $("#cboTipo_Documento").html(r);
        })
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

function ListadoCliente(){ 		
		var tabla = $('#tblCliente').dataTable(
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
				{   "mDataProp": "2"},
				{   "mDataProp": "3"},
				{   "mDataProp": "4"},
				{   "mDataProp": "5"},
				{   "mDataProp": "6"},
				{   "mDataProp": "7"}
        	],"ajax":
	        	{
	        		url: './ajax/ClienteAjax.php?op=list',
					type : "get",
					dataType : "json",
					error: function(e){
				   		console.log(e.responseText);
					}
	        	},
	        "bDestroy": true
    	}).DataTable();
    };

function eliminarCliente(id){// funcion que llamamos del archivo ajax/CategoriaAjax.php?op=delete linea 53
	bootbox.confirm("¿Esta Seguro de eliminar el cliente seleccionado?", function(result){ // confirmamos con una pregunta si queremos eliminar
		if(result){// si el result es true
			$.post("./ajax/ClienteAjax.php?op=delete", {id : id}, function(e){// llamamos la url de eliminar por post. y mandamos por parametro el id 
				ListadoCliente();
				swal("Mensaje del Sistema", e, "success");
            });
		}
	})
}
//Datos que se muestran en el ticket
 function cargarDataCliente(id,tipo_persona,nombre,apellido,tipo_documento,num_documento,direccion_departamento,direccion_provincia,direccion_distrito,direccion_calle,telefono,telefono_2,email,numero_cuenta,estado,idempleado,empleado,fecha_registro,idempleado_modificado,empleado_modificado,fecha_modificado){// funcion que llamamos del archivo ajax/CategoriaAjax.php linea 52
		$("#VerForm").show();// mostramos el formulario
		$("#btnNuevo").hide();// ocultamos el boton nuevo
		$("#VerListado").hide();
 
		$("#txtIdPersona").val(id);// recibimos la variable id a la caja de texto
		$("#cboTipoPersona").val(tipo_persona);
	    $("#txtNombre").val(nombre);// recibimos la variable nombre a la caja de texto txtNombre
		$("#txtApellido").val(apellido);// recibimos la variable apellido a la caja de texto txtApellido
		$("#cboTipo_Documento").val(tipo_documento);// recibimos la variale tipo_documento de sucursal
 		$("#txtNum_Documento").val(num_documento);
	    $("#txtDireccion_Departamento").val(direccion_departamento);
	    $("#txtDireccion_Provincia").val(direccion_provincia);
	    $("#txtDireccion_Distrito").val(direccion_distrito);
	    $("#txtDireccion_Calle").val(direccion_calle);
	    $("#txtTelefono").val(telefono);
		$("#txtTelefono_2").val(telefono_2);
 		$("#txtEmail").val(email);
 		$("#txtNumero_Cuenta").val(numero_cuenta);
 		$("#cboEstado").val(estado);
		$('#txtIdEmpleado').val(idempleado);//Campo empleado ID
		$('#txtEmpleado').val(empleado);//Campo empleado ID
		$('#txtFecha_registro').val(fecha_registro);//Campo empleado
		$('#txtIdEmpleado_modificado').val(idempleado);//Campo empleado
		$('#txtEmpleado_modificado').val(empleado_modificado);//Campo empleado ID
		$('#txtFecha_modificado').val(fecha_modificado);//Campo empleado
 	}