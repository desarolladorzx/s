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



	$("#btnNuevo").show();



	if ($("#hdn_rol_usuario").val() == 'S') { // SUPERADMIN
		$('#cboTipo_Persona option[value="FINAL"]').attr("disabled", false);
		$('#cboTipo_Persona option[value="DISTRIBUIDOR"]').attr("disabled", false);
		$('#cboTipo_Persona option[value="SUPERDISTRIBUIDOR"]').attr("disabled", false);
		$('#cboTipo_Persona option[value="REPRESENTANTE"]').attr("disabled", false);

		$('#cboTipo_Documento option:not(:selected)').attr('disabled',false);

		//$("#cboTipo_Documento").prop('readonly', false);
		/*$('#cboTipo_Documento option[value="DNI"]').attr("disabled", false);
		$('#cboTipo_Documento option[value="RUC"]').attr("disabled", false);
		$('#cboTipo_Documento option[value="PASAPORTE"]').attr("disabled", false);
		$('#cboTipo_Documento option[value="CE"]').attr("disabled", false);*/

	} else if ($("#hdn_rol_usuario").val() == 'A'){ // USUARIO / TRABAJADOR
		$('#cboTipo_Persona option[value="FINAL"]').attr("disabled", false);
		$('#cboTipo_Persona option[value="DISTRIBUIDOR"]').attr("disabled", true);
		$('#cboTipo_Persona option[value="SUPERDISTRIBUIDOR"]').attr("disabled", true);
		$('#cboTipo_Persona option[value="REPRESENTANTE"]').attr("disabled", true);

		$('#cboTipo_Documento option:not(:selected)').attr('disabled',true);

		/*$('#cboTipo_Documento option[value="DNI"]').attr("disabled", true);
		$('#cboTipo_Documento option[value="RUC"]').attr("disabled", true);
		$('#cboTipo_Documento option[value="PASAPORTE"]').attr("disabled", true);
		$('#cboTipo_Documento option[value="CE"]').attr("disabled", true);*/
	}

	ListadoCliente();// Ni bien carga la pagina que cargue el metodo
	ComboTipo_Documento();
	$("#VerForm").hide();// Ocultamos el formulario
	$("form#frmCliente").submit(SaveOrUpdate);// Evento submit de jquery que llamamos al metodo SaveOrUpdate para poder registrar o modificar datos
	$("#btnNuevo").click(VerForm);// evento click de jquery que llamamos al metodo VerForm
	$("#btnExtraerClientes").click(buscarPorNumeroDocumento); // Evento para buscar documento por Extracioon 

	function SaveOrUpdate(e){
		e.preventDefault();// para que no se recargue la pagina

		console.log($(this).serialize())
        $.post("./ajax/ClienteAjax.php?op=SaveOrUpdate", $(this).serialize(), function(r){// llamamos la url por post. function(r). r-> llamada del callback
            
			//ListadoCliente();
            //$.toaster({ priority : 'success', title : 'Mensaje', message : r});
            //swal("Mensaje del Sistema", r, "success");
            //OcultarForm();
			//Limpiar();

			swal({
				title: "Mensaje del Sistema", 
				text: r, 
				type: "success"
			  },
			function(){ 
				location.reload();
			}
		 );

        });
		
	};

	function Limpiar(){
		// Limpiamos las cajas de texto
		$("#txtIdPersona").val("");
		$("#txtNombre").val("");
		$("#txtApellido").val("");
	    $("#txtNum_Documento").val("");
		$("#optionsRadios").val("");
	    $("#txtDireccion_Departamento").val("");
	    $("#txtDireccion_Provincia").val("");
	    $("#txtDireccion_Distrito").val("");
	    $("#txtDireccion_Calle").val("");
	    $("#txtTelefono").val("");
		$("#txtTelefono_2").val("");
	    $("#txtEmail").val("");
	    $("#txtNumero_cuenta").val("");
		//$("#txtIdEmpleado").val("");
		$("#txtEmpleado").val("");
		$("#txtIdEmpleado_modificado").val("");
		$("#txtEmpleado_modificado").val("");
		$("#txtFecha_creacion").val("");
		$("#txtFecha_modificacion").val("");

		$("input[name=optionsRadios]").prop('checked', false);
		$('#optionsRadios_edit').val("");
		$('#optionsRadios_id_edit').val("");
		//$("#optionsRadios1").prop("checked", false);
		//$("#optionsRadios2").prop("checked", false);
		//$("#optionsRadios3").prop("checked", false);

	}

	function ComboTipo_Documento() {
        $.get("./ajax/ClienteAjax.php?op=listTipo_DocumentoPersona", function(r) {
                $("#cboTipo_Documento").html(r);

        })
    }

	function VerForm(){
		$("#VerForm").show();// Mostramos el formulario
		//$("#btnNuevo").hide();// ocultamos el boton nuevo
		$("#VerListado").hide();

		$("#optionsRadios1").prop("checked", false);
		$("#optionsRadios2").prop("checked", false);
		$("#optionsRadios3").prop("checked", false);

		$("#panel_rbg_habilitado").show(200);
		$("#panel_rbg_desabilitado").hide(200);
		$('#optionsRadios_edit').val("");
		$('#optionsRadios_id_edit').val("");
		

	}

	function OcultarForm(){
		$("#VerForm").hide();// Mostramos el formulario
		//$("#btnNuevo").show();// ocultamos el boton nuevo
		$("#VerListado").show();
	}
}

function ListadoCliente(){ 		
		var tabla = $('#tblCliente').dataTable(
		{   "aProcessing": true,
       		"aServerSide": true,
       		dom: 'Bfrtip',
	        buttons: [
	        //'copyHtml5',
            //'excelHtml5',
            //'csvHtml5',
            //'pdfHtml5'
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
				{   "mDataProp": "11"}
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
function cargarDataCliente(id,tipo_persona,nombre,apellido,tipo_documento,num_documento,direccion_departamento,direccion_provincia,direccion_distrito,direccion_calle,telefono,telefono_2,email,numero_cuenta,estado,idempleado,empleado,fecha_registro,empleado_modificado,fecha_modificado,genero,genero_txt,newClasifiacion){// funcion que llamamos del archivo ajax/CategoriaAjax.php linea 52
		$("#VerForm").show();// mostramos el formulario
		//$("#btnNuevo").hide();// ocultamos el boton nuevo
		$("#VerListado").hide();

	
		$("#txtIdPersona").val(id);// recibimos la variable id a la caja de texto
		$("#cboTipoPersona").val(tipo_persona);
	    $("#txtNombre").val(nombre);// recibimos la variable nombre a la caja de texto txtNombre
		$("#txtApellido").val(apellido);// recibimos la variable apellido a la caja de texto txtApellido
		$("#cboTipo_Documento").val(tipo_documento);// recibimos la variale tipo_documento de sucursal
		$("#cboTipo_Documento_edit").val(tipo_documento);
		
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
		$('#txtEmpleado').val(empleado);//Campo nombre empleado 
		$('#txtFecha_registro').val(fecha_registro);//Campo empleado
		//$('#txtIdEmpleado_modificado').val(idempleado_modificado);//Campo  empleado ID
		$('#txtEmpleado_modificado').val(empleado_modificado);//Campo nombre del empleado
		$('#txtFecha_modificado').val(fecha_modificado);//Campo fecha de modificacion empleado 


		console.log(newClasifiacion);
		$("#txtClasificacion").val(newClasifiacion);

		// console.log(tipo_persona)
		// if(['FINAL','DISTRIBUIDOR','SUPERDISTRIBUIDOR','REPRESENTANTE'].includes(tipo_persona)){
			
		// }
		

		if ($("#hdn_rol_usuario").val() == 'S') { // SUPERADMIN


			$("#panel_rbg_habilitado").show(200);
			$("#panel_rbg_desabilitado").hide(200);

			$("input[name=optionsRadios][value=" + genero + "]").prop("checked", true);

		}else if ($("#hdn_rol_usuario").val() == 'A'){ // USUARIO / TRABAJADOR


			$("#panel_rbg_habilitado").hide(200);
			$("#panel_rbg_desabilitado").show(200);
			
			$('#optionsRadios_edit').val(genero_txt);
			$('#optionsRadios_id_edit').val(genero);

			$('input[name=optionsRadios]').prop('disabled',true); 

		}

		//PROBLEMA CUANDO SE EDITA UN CLIENTE
		if (tipo_documento == "DNI" || tipo_documento == "RUC") {
	
			$('#txtNombre').prop('readonly', true);
			$('#txtApellido').prop('readonly', true);
			$('#txtNum_Documento').prop('readonly', true);
			
		} else if (tipo_documento == "PASAPORTE" || tipo_documento == "CE") {
			
			$('#txtNombre').prop('readonly', false);
			$('#txtApellido').prop('readonly', false);
			$('#txtNum_Documento').prop('readonly', true);

		}

		$("#cboTipo_Documento").change(function(){

			if ($(this).val() == "DNI" || $(this).val() == "RUC") {
	
				$('#txtNombre').prop('readonly', true);
				$('#txtApellido').prop('readonly', true);
				$('#txtNum_Documento').prop('readonly', true);
				
			} else if ($(this).val() == "PASAPORTE" || $(this).val() == "CE") {
				
				$('#txtNombre').prop('readonly', false);
				$('#txtApellido').prop('readonly', false);
				$('#txtNum_Documento').prop('readonly', true);
	
			}
	
		});

		if ($("#hdn_rol_usuario").val() == 'S') { // SUPERADMIN
			//$("#cboTipo_Documento").prop('readonly', false);
			$('#cboTipo_Documento option:not(:selected)').attr('disabled',false);
			/*$('#cboTipo_Documento option[value="DNI"]').attr("disabled", false);
			$('#cboTipo_Documento option[value="RUC"]').attr("disabled", false);
			$('#cboTipo_Documento option[value="PASAPORTE"]').attr("disabled", false);
			$('#cboTipo_Documento option[value="CE"]').attr("disabled", false);*/
	
		} else if ($("#hdn_rol_usuario").val() == 'A'){ // USUARIO / TRABAJADOR
			//$("#cboTipo_Documento").prop('readonly', true);
			$('#cboTipo_Documento option:not(:selected)').attr('disabled',true);
			//$("#cboTipo_Documento").val(tipo_documento);
			/*$('#cboTipo_Documento option[value="DNI"]').attr("disabled", true);
			$('#cboTipo_Documento option[value="RUC"]').attr("disabled", true);
			$('#cboTipo_Documento option[value="PASAPORTE"]').attr("disabled", true);
			$('#cboTipo_Documento option[value="CE"]').attr("disabled", true);*/
		}


 	}

function buscarPorNumeroDocumento() {
	$("#cboTipo_Persona").val("");
	$("#txtNumero_Cuenta").val("");
	$("#txtNombre").val("");
	$("#txtApellido").val("");
	$("#cboTipo_Documento").val("");
	$("#cboTipo_Documento_edit").val("");
	
	$("#optionsRadios").val("");
	$("#txtDireccion_Departamento").val("");
	$("#txtDireccion_Provincia").val("");
	$("#txtDireccion_Distrito").val("");
	$("#txtDireccion_Calle").val("");
	$("#txtTelefono").val("");
	$("#txtTelefono_2").val("");
	$("#txtEmail").val("");
	$("#txtEstado").val("");

	$("#txtIdPersona").val("");
	//$("#txtIdEmpleado_modificado").val("");

	//$("#txtEmpleado").val("");
	//$("#txtIdEmpleado_modificado").val("");
	//$("#txtEmpleado_modificado").val("");
	//$("#txtFecha_creacion").val("");
	//$("#txtFecha_modificacion").val("");

	if ($("#txtNum_Documento").val() != "") {
		$.ajax({
			url: "./ajax/ClienteAjax.php?op=buscarClienteSunat",
			dataType: "json",
			data: {
				numerodoc: $("#txtNum_Documento").val(),
				origen: "moduloCliente",
			},
			success: function (rpta) {
				//alert(rpta['estado'])
				switch (rpta["estado"]) {
					case "encontrado":



						//$("input[name=optionsRadios][value=" + genero + "]").prop("checked", true);
						
						$("#cboTipo_Documento_edit").val(rpta["tipo_documento"]);

						if ($("#hdn_rol_usuario").val() == 'S') { // SUPERADMIN

							$("#panel_rbg_habilitado").show(200);
							$("#panel_rbg_desabilitado").hide(200);
							$("input[name=optionsRadios][value=" + rpta["genero"] + "]").prop("checked", true);

							$('#txtNombre').prop('readonly', false);
							$('#txtApellido').prop('readonly', false);
							$('#txtNum_Documento').prop('readonly', false);

							$('#cboTipo_Documento option:not(:selected)').attr('disabled',false);
				
						}else if ($("#hdn_rol_usuario").val() == 'A'){ // USUARIO / TRABAJADOR
				
				
							$("#panel_rbg_habilitado").hide(200);
							$("#panel_rbg_desabilitado").show(200);
							$('#optionsRadios_edit').val(rpta["genero_txt"]);
							$('#optionsRadios_id_edit').val(rpta["genero"]);
							$('input[name=optionsRadios]').prop('disabled',true);

							$('#txtNombre').prop('readonly', true);
							$('#txtApellido').prop('readonly', true);
							$('#txtNum_Documento').prop('readonly', true);

							$('#cboTipo_Documento option:not(:selected)').attr('disabled',true);
				
						}


						if (rpta["genero"] == 1) {
							$("#optionsRadios1").prop("checked", true);
						} else if(rpta["genero"] == 2){
							$("#optionsRadios2").prop("checked", true);
						} else if(rpta["genero"] == 3){
							$("#optionsRadios3").prop("checked", true);
						}

						//alert(rpta["genero"]);

						//$("#txtIdCliente").val(rpta['idCliente']);
						//alert(rpta['tipo_persona'])
						$("#cboTipo_Persona").val(rpta["tipo_persona"]);
						$("#txtNumero_Cuenta").val(rpta["estadoCuenta"]);
						$("#txtNombre").val(rpta["nombre"]);
						$("#txtApellido").val(rpta["apellido"]);
						$("#cboTipo_Documento").val(rpta["tipo_documento"]);
						$("#optionsRadios").val(rpta["genero"]);
						$("#txtNum_Documento").val($("#txtNum_Documento").val());
						$("#txtDireccion_Departamento").val(rpta["direccion_departamento"]);
						$("#txtDireccion_Provincia").val(rpta["direccion_provincia"]);
						$("#txtDireccion_Distrito").val(rpta["direccion_distrito"]);
						$("#txtDireccion_Calle").val(rpta["direccion_calle"]);
						$("#txtTelefono").val(rpta["telefono"]);
						$("#txtTelefono_2").val(rpta["telefono_2"]);
						$("#txtEmail").val(rpta["email"]);
						$("#txtEstado").val(rpta["estado_cliente"]);
						
						$("#txtIdPersona").val(rpta["idCliente"]);
						//$("#txtIdEmpleado_modificado").val(rpta["idEmpleado_modificado"]);
						
						// txtIdEmpleado

						/*
										$("#txtCliente").val(rpta['nombre']);
										$("#txtClienteNroDocumento").val("");
										$("#modalBuscarClientes").modal("hide");
										$("#btnEditarCliente").show(200);
										*/
						alert("El cliente ya se encuentra registrado, solo está permitido editar los campos habilitados...");

									
						break;
					case "error":
						alert("Ocurrio un error al registrar cliente...");
						break;
					case "no_encontrado":
						$("#cboTipo_Persona").val("");
						$("#txtNumero_Cuenta").val(rpta["estadoCuenta"]);
						$("#txtNombre").val("");
						$("#txtApellido").val("");
						$("#cboTipo_Documento").val("");
						$("#txtNum_Documento").val($("#txtNum_Documento").val());
						$("#optionsRadios").val("");
						$("#txtDireccion_Departamento").val();
						$("#txtDireccion_Provincia").val();
						$("#txtDireccion_Distrito").val();
						$("#txtDireccion_Calle").val();
						$("#txtTelefono").val();
						$("#txtTelefono_2").val();
						$("#txtEmail").val();
						$("#txtEstado").val();
						$("#txtIdPersona").val();
						break;
				}
			},
			error: function (e) {
				console.log(e.responseText);
			},
		});
	} else {
		alert("Ingresar número de documento válido...");
	}
}





function clasificacion_final(tipo) {
	
	$.ajax({
		url: "./ajax/ClienteAjax.php?op=cambiarEstadoCliente_final",
		dataType: "json",
		data: {
			tipo: tipo
		},
		success: function (rpta) {
			if (rpta['result'] == true) {
				alert("Se actualizaron "+rpta['cantidadRegistros']+" registros.")
			}else if(rpta['result'] == null || rpta['result'] == "null"){
				alert("Sin datos que actualizar...")
			}else{
				alert("Error al actualizar registros...")
			}
		},
		error: function (e) {
			console.log(e.responseText);
		},
	});

}

function clasificacion_distribuidor(tipo) {
	
	$.ajax({
		url: "./ajax/ClienteAjax.php?op=cambiarEstadoCliente_distribuidor",
		dataType: "json",
		data: {
			tipo: tipo
		},
		success: function (rpta) {
			if (rpta['result'] == true) {
				alert("Se actualizaron "+rpta['cantidadRegistros']+" registros.")
			}else if(rpta['result'] == null || rpta['result'] == "null"){
				alert("Sin datos que actualizar...")
			}else{
				alert("Error al actualizar registros...")
			}
		},
		error: function (e) {
			console.log(e.responseText);
		},
	});
	
}

function clasificacion_representante(tipo) {
	
	$.ajax({
		url: "./ajax/ClienteAjax.php?op=cambiarEstadoCliente_representante",
		dataType: "json",
		data: {
			tipo: tipo
		},
		success: function (rpta) {
			if (rpta['result'] == true) {
				alert("Se actualizaron "+rpta['cantidadRegistros']+" registros.")
			}else if(rpta['result'] == null || rpta['result'] == "null"){
				alert("Sin datos que actualizar...")
			}else{
				alert("Error al actualizar registros...")
			}
		},
		error: function (e) {
			console.log(e.responseText);
		},
	});
	
}