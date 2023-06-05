$(document).on("ready", init); // Inciamos el jquery
function modificarHijos(pos) {
  console.log(hijos);
  var txtnombre_hijo = document.getElementsByName("txtnombre_hijo");
  var txtapellido_hijo = document.getElementsByName("txtapellido_hijo");

  hijos[pos][0] = txtnombre_hijo[pos].value;
  hijos[pos][1] = txtapellido_hijo[pos].value;
}
var hijos = [];

function renderizarHijos() {
  let renderHtml = "";
  hijos.map((hijo, index) => {
    renderHtml += `
  <div class="row">
					<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
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
						  placeholder="Nombre del conyugue"
						  autofocus=""
						  required
						/>
					  </div>
					</div>
					<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
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
						  placeholder="Nombre del conyugue"
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

  $("#container_input_hijos").html(renderHtml);
}
function eliminarFilaHijo(index) {
  if (index !== 0) {
    hijos.splice(index, 1);
    renderizarHijos();
  } else {
    alert("es Necesario un hijo para realizar la operacion");
  }
}

function init() {
  $("#button_anadir_hijos").hide();

  $("#button_anadir_hijos").click(function () {
    anadir_hijos();
  });

  function anadir_hijos() {
    hijos.push([" ", " "]);

    renderizarHijos();
  }
  $("#txtcant_hijos").change(function () {
    if (this.value == "SI") {
      // console.log(this.value)
      $("#button_anadir_hijos").show();
      // $('#button_anadir_hijos').show()

      hijos = [[" ", " "]];

      renderizarHijos();
    } else {
      hijos = [];
      $("#container_input_hijos").html("");
      $("#button_anadir_hijos").hide();
    }
  });

  function getFormattedDate(date) {
    var year = date.getFullYear();
    var month = ("0" + (date.getMonth() + 1)).slice(-2);
    var day = ("0" + date.getDate()).slice(-2);
    return year + "-" + month + "-" + day;
  }

  $("#txtfecha_inicio_labores").val(getFormattedDate(new Date()));

  $("#txtfecha_fin_labores").attr("min", getFormattedDate(new Date()));

  $("#tblEmpleado").dataTable({
    dom: "Bfrtip",
    buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
  });

  ListadoEmpleado(); // Ni bien carga la pagina que cargue el metodo
  ComboTipo_Documento();
  $("#VerForm").hide(); // Ocultamos el formulario
  $("#txtClaveOtro").hide();
  $("form#frmEmpleado").submit(SaveOrUpdate); // Evento submit de jquery que llamamos al metodo SaveOrUpdate para poder registrar o modificar datos

  $("#btnNuevoEmpleado").click(VerFormEmpleado); // evento click de jquery que llamamos al metodo VerForm

  function SaveOrUpdate(e) {
    e.preventDefault();

    var formData = new FormData($("#frmEmpleado")[0]);

    $.ajax({
      url: "./ajax/EmpleadoAjax.php?op=SaveOrUpdate",

      type: "POST",

      data: formData,

      contentType: false,

      processData: false,

      success: function (datos) {
        swal("Mensaje del Sistema", datos, "success");
        ListadoEmpleado();
        OcultarForm();
        Limpiar();
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

function cargarDataEmpleado(
  id,
  apellidos,
  nombre,
  tipo_documento,
  num_documento,
  direccion,
  telefono,
  email,
  fecha_nacimiento,
  foto,
  login,
  clave,
  estado,
  cargo,
  fecha_ingreso,
  sexo
) {
  // funcion que llamamos del archivo ajax/CategoriaAjax.php linea 52
  // console.log(fecha_ingreso.split(' ')[0])
  $("#txtCargo").val(cargo);
  $("#txtfecha_ingreso").val(
    fecha_ingreso ? fecha_ingreso.split(" ")[0] : Date
  );
  $("#txtsexo").val(sexo);

  $("#VerForm").show(); // mostramos el formulario
  $("#btnNuevoEmpleado").hide();
  $("#VerListado").hide(); // ocultamos el listado

  $("#txtIdEmpleado").val(id); // recibimos la variable id a la caja de texto txtIdMarca
  $("#txtApellidos").val(apellidos);
  $("#txtNombre").val(nombre);
  $("#cboTipo_Documento").val(tipo_documento);
  $("#txtNum_Documento").val(num_documento);
  $("#txtDireccion").val(direccion);
  $("#txtTelefono").val(telefono);
  $("#txtEmail").val(email);
  $("#txtFecha_Nacimiento").val(fecha_nacimiento);
  //$("#txtLogo").val(logo);
  $("#txtRutaImgEmp").val(foto);
  $("#txtLogin").val(login);
  //$("#txtClave").val(clave);
  $("#txtRutaImgEmp").show();
  $("#txtEstado").val(estado);
  $("#txtClaveOtro").val(clave);
  //$("#txtClaveOtro").show();
}

function ComboTipo_Documento() {
  $.get("./ajax/EmpleadoAjax.php?op=listTipo_DocumentoPersona", function (r) {
    $("#cboTipo_Documento").html(r);
  });
}
