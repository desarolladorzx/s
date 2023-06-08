$(document).on("ready", init); // Inciamos el jquery
function modificarHijos(pos) {
  console.log(hijos);
  var txtnombre_hijo = document.getElementsByName("txtnombre_hijo");
  var txtapellido_hijo = document.getElementsByName("txtapellido_hijo");
  var txtDNI_hijo = document.getElementsByName("txtDni_hijo");
  var txtNacimiento_hijo = document.getElementsByName("txtNacimiento_hijo");

  hijos[pos][0] = txtnombre_hijo[pos].value;
  hijos[pos][1] = txtapellido_hijo[pos].value;
  hijos[pos][2] = txtDNI_hijo[pos].value;
  hijos[pos][3] = txtNacimiento_hijo[pos].value;
}
var hijos = [];

function renderizarHijos() {
  let renderHtml = "";
  hijos.map((hijo, index) => { //se encarga de rendirar el html con los input necesarios para poder almacenar los hijos
    renderHtml += `
  <div class="row">
  
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
					  <div class="form-group has-success">
						<label> Nombre hijo/a:</label>
						<input
						  id="txtnombre_hijo[]"
						  type="text"
							onchange='modificarHijos(${index})'
						  value="${hijo[0]}"
						  maxlength="70"
						  name="txtnombre_hijo"
						  class="form-control"
						  placeholder="Nombre del hijo/a"
						  autofocus=""
						  required
						/>
					  </div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
					  <div class="form-group has-success">
						<label>Apellido hijo/a:</label>
						<input
						  id="txtapellido_hijo[]"
						  type="text"
  
						  onchange='modificarHijos(${index})'
  
						  value="${hijo[1]}"
  
						  maxlength="70"
						  name="txtapellido_hijo"
						  class="form-control"
						  placeholder="Apellido del hijo/a"
						  autofocus=""
						  required="true"
						/>
					  </div>
					</div>

          <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
					  <div class="form-group has-success">
						<label>DNI hijo/a:</label>
						<input
						  id="txtDni_hijo[]"
						  type="number"
  
						  onchange='modificarHijos(${index})'
  
						  value="${hijo[2]}"
  
						  maxlength="70"
						  name="txtDni_hijo"
						  class="form-control"
						  placeholder="DNI del hijo/a "
						  autofocus=""
						  required="true"
						/>
					  </div>
					</div>

          <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
					  <div class="form-group has-success">
						<label>f. Nacimiento hijo/a:</label>
						<input
						  id="txtNacimiento_hijo[]"
						  type="date"
  
						  onchange='modificarHijos(${index})'
  
						  value="${hijo[3]}"
  
						  maxlength="70"
						  name="txtNacimiento_hijo"
						  class="form-control"
						  placeholder="fecha de nacimiento hijo/a"
						  autofocus=""
						  required="true"
						/>
					  </div>
					</div>


					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12"
					style="
						display: flex;
						align-items: center;
						justify-content: center;
					  "
  
					>
					<div class="form-group has-success">
  
					<label for="" style="opacity:0;width: 100%;">asda</label>
  
  
					<button class="btn btn-danger" type="button" onclick="eliminarFilaHijo(${index})"><i class="fa fa-trash"></i> </button>
					</div>
					</div>
	</div>
				  
  
  `;
  });

  $("#container_input_hijos").html(renderHtml); // inserta los elemnetos al html
}
function eliminarFilaHijo(index) { //funcion encargada de eliminar un hijo segun la posicion en la que se encuentre con el index 
  if (index !== 0) { // es necesario tener almenos un hijo para poder realizar la operacion
    hijos.splice(index, 1);
    renderizarHijos();  //rendeiza el array de hijos a html
  } else {
    alert("es Necesario un hijo para realizar la operacion");
  }
}

function cargarDataEmpleadoHijo(
  iddetalle_empleado_hijo,
  nombre_hijo,
  apellido_hijo,
  dni_hijo,
  fecha_nacimiento_hijo
) {
  $("#txtNombre_hijoNuevo").val(nombre_hijo);
  $("#txtApellido_hijoNuevo").val(apellido_hijo);

  $("#iddetalle_empleado_hijo").val(iddetalle_empleado_hijo);

  $("#txtDni_hijoNuevo").val(dni_hijo);
  $("#txtFechaNacimiento_hijoNuevo").val(fecha_nacimiento_hijo);

  $("#anadir_hijo").text("Actualizar Hijo");
}

function init() {
  $("#txtEstado_civil").change(function () {
    console.log(this.value);


    // si el estado civil pasa a casado el input que almacena  el nombre de conyugue pasara a ser requerido para la insercion de datos
    if (this.value == "CASADO") {
      $("#txtnombre_conyugue").prop("required", true);
    } else {
      // en caso no este el estado civil el nombre del conyugue pasara a ser no requerido 
      $("#txtnombre_conyugue").prop("required", false);
    }
  });

  // funcion encargada  de que al momneto de almacenar una ubicacion que no cuadra con las opciones dadas , este se limpiara 
  $("#txt_ubicacion_empleado").on("blur", function () {
    var optionValues = $(".ubicacion_containe_options option")
      .map(function () {
        return $(this).val();
      })
      .get();
    if ($.inArray($(this).val(), optionValues) === -1) {
      $(this).val("");
    }
  });
// 

// funcion encargada  de almacenar los id de la ubicacion para posterirormente guardarlos en la base  de datos 
  $("#txt_ubicacion_empleado").change(function (e) {
    const selectedOption = $(
      `#ubicacion option[value="${$("#txt_ubicacion_empleado").val()}"`
    );
    // const selectedNumber = selectedOption.data('number');

    $("#id_ubicacion_empleado_array").val(selectedOption.data("id"));
  });
// 



  $("#container_tbl_contratos").hide(); // el contenedor de contratos  estara oculto hasta que se pase  al modo de edicion
  $("#container_tbl_hijos").hide();// el contendedor de  hijos estara oculta hasta que se pase al modo de edicion

  $("#anadir_hijo").click(function () {
    // se encarga  de agragar un nuevo hijo dentro del funcionamiento de edicion del empleado
    if ($("#txtNombre_hijoNuevo").val()) {
      //si el input existe se puede anadir

      if ($("#txtApellido_hijoNuevo").val()) {
         //si el input existe se puede anadir

        if ($("#txtDni_hijoNuevo").val()) {
                //si el input existe se puede anadir


          if ($("#txtFechaNacimiento_hijoNuevo").val()) {
            //si el input existe se puede anadir

            var formData = new FormData();


            // se anaden al formdata  los input necesarios para  poder  anadir a un hijo
            formData.append(
              "iddetalle_empleado_hijo",
              $("#iddetalle_empleado_hijo").val()
            );

            formData.append(
              "txtNombre_hijoNuevo",
              $("#txtNombre_hijoNuevo").val()
            );
            formData.append(
              "txtApellido_hijoNuevo",
              $("#txtApellido_hijoNuevo").val()
            );
            formData.append("txtDni_hijoNuevo", $("#txtDni_hijoNuevo").val());
            formData.append(
              "txtFechaNacimiento_hijoNuevo",
              $("#txtFechaNacimiento_hijoNuevo").val()
            );

            formData.append("idempleado", $("#txtIdEmpleado").val());

            $.ajax({
              url: "./ajax/EmpleadoAjax.php?op=AnadirHijooActualizar", //actualiza o anade  un nuevo hijo al empleado

              type: "POST",

              data: formData,

              contentType: false,

              processData: false,

              success: function (datos) {


                // si el Post funciona correctamente , se liminaran los datos de los input insertados anteriormente

                $("#anadir_hijo").text("Anadir Hijo");
                $("#txtNombre_hijoNuevo").val("");
                $("#txtApellido_hijoNuevo").val("");
                $("#iddetalle_empleado_hijo").val("");
                $("#txtDni_hijoNuevo").val("");
                $("#txtFechaNacimiento_hijoNuevo").val("");

                // 
                TraerHijosEmpleado($("#txtIdEmpleado").val()); //trae la tabla de hijos  del empleado
              },
            });
          } else {
            bootbox.alert("Es necesario la fecha de nacimiento del hijo");
          }
        } else {
          bootbox.alert("Es necesario el DNI del hijo");
        }
      } else {
        bootbox.alert("Es necesario el apellido del hijo");
      }
    } else {
      bootbox.alert("Es necesario el nombre del hijo");
    }

    console.log();
  });
  $("#button_renovar_contrato").click(function () {
 
//  funcion encargada  de renovar un nuevo contrato y cambiar los contratos  anteriores  a invalidos 
    var inputFile = $("#contrato_trabajo")[0].files;

    if ($("#txtfecha_fin_labores").val()) {
      // si existe la fecha fin de laboleres se puede renovar el contrato
      if (inputFile.length !== 0) {
        // si existe el contrato se puede renovar el contrato
        var formData = new FormData($("#frmEmpleado")[0]);

        $.ajax({
          url: "./ajax/EmpleadoAjax.php?op=RenovarContrato",
// consulta encargada de renovar el contrato
          type: "POST",

          data: formData,

          contentType: false,

          processData: false,

          success: function (datos) {
            // si la consulta  funciona correctamente entonces los inputs para renovar el contrato se limpiaran
            $("#txtfecha_fin_labores").val("");
            $("#declaracion_jurada").val("");
            $("#antecedentes").val("");
            $("#registro_RIT").val("");
            $("#dniFile").val("");
            $("#contrato_trabajo").val("");
            $("#cv_file").val("");
            // 
            ListarContratos($("#txtIdEmpleado").val()); //trae la lista  de contratos
          },
        });
      } else {
        bootbox.alert("Debes agregar el contrato renovado");
      }
    } else {
      bootbox.alert("Debes agregar la fecha de finalizacion de contrato");
    }

    // var inputFile = $('#mi-input-file')[0].files;

    // if (inputFile.length === 0) {
    //   console.log('El campo está vacío');
    // } else {
    //   console.log('El campo no está vacío');
    // }
  });
  $("#button_anadir_hijos").hide(); // el boton de anadir hijos estara oculto hasta que el campo de hijos este con el valor si 


  
  $("#button_anadir_hijos").click(function () {//button encargado  de anadir hijos 
    anadir_hijos();  // inserta hijos a un array de hijos 
  });

  function anadir_hijos() { //anade un elemneto al array de hijos 
    hijos.push(["", "", "", ""]);

    renderizarHijos(); //renderiza los inputs segun la cantidad  de hijos   que  se  requiera  
  }
  $("#txtcant_hijos").change(function () {


    if (this.value == "SI") {

      // si el select  txtcant_hijos  da a entender que se cuenta con un hijo 

      $("#button_anadir_hijos").show(); //nuestra el boton de anadir mas hijos si es requerido
      hijos = [["", "", "", ""]]; // anade el primer elemento al array de hijos

      renderizarHijos(); //renderiza el array de hijos a  html
    } else {
      // en caso no tener hijos, se limpiara el array , asi como el html del contenedor de los input de hijos
      hijos = [];
      $("#container_input_hijos").html(""); 
      //
      $("#button_anadir_hijos").hide(); // se ocultara el boton
    }
  });

  function getFormattedDate(date) { //funcion encargada  de establecer el dia de hoy y retornar un valor que pueda ser insertado en un input date 
    var year = date.getFullYear();
    var month = ("0" + (date.getMonth() + 1)).slice(-2);
    var day = ("0" + date.getDate()).slice(-2);
    return year + "-" + month + "-" + day;
  }

  $("#txtfecha_inicio_labores").val(getFormattedDate(new Date())); // el input txtfecha_inicio_labores esta siempre con la fecha de hoy

  $("#txtfecha_fin_labores").attr("min", getFormattedDate(new Date())); // encargado de poner como fecha de fin de labores un dia despues del dia enconttrado 

  $("#tblEmpleado").dataTable({
    dom: "Bfrtip",
    buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
  });

  ListadoEmpleado(); // Ni bien carga la pagina que cargue el metodo
  ComboTipo_Documento(); //trae los tipos de documentos 

  $("#VerForm").hide(); // Ocultamos el formulario
  $("#txtClaveOtro").hide(); 
  $("form#frmEmpleado").submit(SaveOrUpdate); // Evento submit de jquery que llamamos al metodo SaveOrUpdate para poder registrar o modificar datos

  $("#btnNuevoEmpleado").click(VerFormEmpleado); // evento click de jquery que llamamos al metodo VerForm

  function SaveOrUpdate(e) { //funcion encargada de actualizar o anadir empleados
    e.preventDefault();

    var formData = new FormData($("#frmEmpleado")[0]);


    hijos.map((hijos) => { //agrega el array de hijos a la consulta 
      formData.append("hijos[]", hijos);
    });

    $.ajax({
      url: "./ajax/EmpleadoAjax.php?op=SaveOrUpdate",
//guarda o actualiza al empleado
      type: "POST",

      data: formData,

      contentType: false,

      processData: false,

      success: function (datos) {
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
      },
    });
  }

  function Limpiar() {
    // Limpiamos las cajas de texto
    $("#txtIdEmpleado").val("");
    $("#txtNombre").val("");
    $("#txtApellidos").val("");
    $("#txtNum_Documento").val("");
    $("#txtDireccion").val("");
    $("#txtTelefono").val("");
    $("#txtEmail").val("");
    $("#txtRepresentante").val("");
    $("#txtLogin").val("");
    $("#txtClave").val("");
    $("#txtClaveOtro").val("");
  }

  function VerFormEmpleado() {
    $("#VerForm").show(); // Mostramos el formulario
    $("#btnNuevoEmpleado").hide();
    $("#VerListado").hide(); // ocultamos el listado
  }

  function OcultarForm() {
    $("#VerForm").hide(); // Mostramos el formulario
    $("#btnNuevoEmpleado").show(); // ocultamos el boton nuevo
    $("#VerListado").show();
  }
}

function ListadoEmpleado() {
  // lisa los empleado en la tabla tblempleado
  var tabla = $("#tblEmpleado")
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
        { mDataProp: "5" },
        { mDataProp: "6" },
        { mDataProp: "7" },
        { mDataProp: "8" },
      ],
      ajax: {
        url: "./ajax/EmpleadoAjax.php?op=list",
        type: "get",
        dataType: "json",

        error: function (e) {
          console.log(e.responseText);
        },
      },
      bDestroy: true,
    })
    .DataTable();
}

function eliminarEmpleado(id) {
  // funcion que llamamos del archivo ajax/CategoriaAjax.php?op=delete linea 53
  bootbox.confirm("¿Esta Seguro de eliminar el Empleado?", function (result) {
    // confirmamos con una pregunta si queremos eliminar
    if (result) {
      // si el result es true
      $.post("./ajax/EmpleadoAjax.php?op=delete", { id: id }, function (e) {
        // llamamos la url de eliminar por post. y mandamos por parametro el id
        swal("Mensaje del Sistema", e, "success");
        ListadoEmpleado();
      });
    }
  });
}

function eliminarEmpleadoHijo(id) { //elimina el hijo del empleado seleccionado
  bootbox.confirm("¿Esta Seguro de eliminar el Hijo?", function (result) {
    // confirmamos con una pregunta si queremos eliminar
    if (result) {
      // si el result es true
      $.post("./ajax/EmpleadoAjax.php?op=deleteHijo", { id: id }, function (e) {
        // llamamos la url de eliminar por post. y mandamos por parametro el id

        swal("Mensaje del Sistema", e, "success");
      });
    }
  });
}

function ListarContratos(id) {
  //funcion encargada  de traer la lista  de contratos que tiene el empleado segun su id
  var tabla = $("#tblEmpleadoContrato")
    .dataTable({
      aProcessing: true,
      aServerSide: true,
      dom: "Bfrtip",
      pageLength: 2,
      buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
      aoColumns: [
        { mDataProp: "0" },
        { width: "150px", mDataProp: "1" },
        { width: "150px", mDataProp: "2" },
        { width: "150px", mDataProp: "3" },
        { width: "150px", mDataProp: "4" },
        { width: "150px", mDataProp: "5" },
        { width: "150px", mDataProp: "6" },
        { mDataProp: "7" },
        { mDataProp: "8" },
        { mDataProp: "9" },
      ],
      ajax: {
        url: "./ajax/EmpleadoAjax.php?op=listContratos", //ajax engargado de listar los contratos para la tabla
        type: "get",
        dataType: "json",
        data: {
          id,
        },
        error: function (e) {
          console.log(e.responseText);
        },
      },
      bDestroy: true,
    })
    .DataTable();
}

function TraerHijosEmpleado(id) {
  //funcion encargada  de traer los hijos pertenecientes segun el id
  console.log("asd");

  var tabla = $("#tblEmpleadoHijos")
    .dataTable({
      aProcessing: true,
      aServerSide: true,
      dom: "Bfrtip",
      pageLength: 5,
      buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
      aoColumns: [
        { mDataProp: "0" },
        { mDataProp: "1" },
        { mDataProp: "2" },
        { mDataProp: "3" },
        { mDataProp: "4" },
        { mDataProp: "5" },
      ],
      ajax: {
        url: "./ajax/EmpleadoAjax.php?op=listHijos",
        type: "get",
        dataType: "json",
        data: {
          id,
        },
        error: function (e) {
          console.log(e.responseText);
        },
      },
      bDestroy: true,
    })
    .DataTable();
}

function cargarDataEmpleado(id) {
  $.getJSON(
    "ajax/EmpleadoAjax.php?op=TraerEmpleado", //funcion encargaeda  de traer  los datos del empleado por el id
    { id },
    function (values) {
      $("#VerForm").show();

      TraerHijosEmpleado(id); // funcion que trae la tabla de hijos  del empleado

      $("#container_tbl_hijos").show(); //se mostrara la tabla  de hijos que tenga  el empleado

      $("#container_insert_hijos").hide(); //quita el insert de hijos que sera reemplazado por la tabla

      $("#container_tbl_contratos").show(); //se mostrara la tabla  de contratos  que tenga el empleado

      ListarContratos(id); //funcion que trae los contratos del empleado

      // se insertaran los valones del empleado a los imputs
      $("#txtNombre").val(values.nombre);
      $("#txtIdEmpleado").val(values.idempleado);
      $("#txtApellidos").val(values.apellidos);
      $("#cboTipo_Documento").val(values.tipo_documento);
      $("#txtNum_Documento").val(values.num_documento);

      $(`input[name="optionsRadios"][value="${values.sexo}"]`).prop(
        "checked",
        true
      );
      $("#txtDireccion").val(values.direccion);
      $("#id_ubicacion_empleado_array").val(values.idubicacion);

      $("#txt_ubicacion_empleado").val(values.ubicacion);

      $("#txtfecha_nacimiento").val(values.fecha_nacimiento);

      $("#txtRol").val(values.idrol);

      $("#txtTelefono").val(values.telefono);

      $("#txtNombreUsuario").val(values.nombre_usuario);

      $("#txtEmail").val(values.email_personal);

      $("#txtEstado_civil").val(values.estado_civil);

      $("#txtnombre_conyugue").val(values.nombre_conyugue);

      $("#txtcant_hijos").val(values.hijos);

      $("#txtEstado").val(values.estado);

      $("#txtLogin").val(values.login);
      $("#txtClave").val(values.clave);
      $("#txtnombre_contacto").val(values.nombre_contacto);
      $("#txtcelular_contacto").val(values.celular_contacto);
      //

      $("#txtLogin").prop("disabled", true);
      $("#txtClave").prop("disabled", true);
      $("#VerListado").hide(); // ocultamos  el listado

      $("#btnNuevoEmpleado").hide(); //ocultamos  el  boton empleado

      //se quitaran los requeridos de los input que no son necesarios  para  la actualizacion del empleado
      $("#imagenEmp").prop("required", false);
      $("#txtfecha_fin_labores").prop("required", false);
      $("#txtrazon_social").prop("required", false);

      $("#contrato_trabajo").prop("required", false);

      //
    }
  );
}

function ComboTipo_Documento() {
  $.get("./ajax/EmpleadoAjax.php?op=listTipo_DocumentoPersona", function (r) {
    $("#cboTipo_Documento").html(r);
  });
}
