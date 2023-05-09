$(document).on("ready", init); // Inciamos el jquery

function init() {
  $("#tblTarjeta").dataTable({
    dom: "Bfrtip",
    buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
  });

  ListadoTarjeta(); // Ni bien carga la pagina que cargue el metodo

  $("#VerForm").hide(); // Ocultamos el formulario
  $("form#frmTarjeta").submit(SaveOrUpdate); // Evento submit de jquery que llamamos al metodo SaveOrUpdate para poder registrar o modificar datos

  $("#btnNuevo").click(VerForm); // evento click de jquery que llamamos al metodo VerForm

  function SaveOrUpdate(e) {
    e.preventDefault();
    var formData = new FormData($("#frmTarjeta")[0]);
    $.ajax({
      url: "./ajax/TarjetaAjax.php?op=SaveOrUpdate",

      type: "POST",

      data: formData,

      contentType: false,

      processData: false,

      success: function (datos) {
        swal("Mensaje del Sistema", datos, "success");


        ListadoTarjeta();
        OcultarForm();
        Limpiar();
      },
    });
  }

  function Limpiar() {
    // Limpiamos las cajas de texto
    $("#txtIdtargeta").val("");
    $("#txtDescripcion").val("");
    $("#txtCodigo").val("");
  }

  function VerForm() {
    $("#VerForm").show(); // Mostramos el formulario
    $("#btnNuevo").hide(); // ocultamos el boton nuevo
    $("#VerListado").hide();
  }

  function OcultarForm() {
    console.log('nada')
    $("#VerForm").hide(); // Mostramos el formulario
    $("#btnNuevo").show(); // ocultamos el boton nuevo
    $("#VerListado").show();
  }

  // 
  $("#tblTipoDivisa").dataTable({
    dom: "Bfrtip",
    buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
  });

  ListadoTipoDivisa(); // Ni bien carga la pagina que cargue el metodo

  $("#VerFormTipoDivisa").hide(); // Ocultamos el formulario
  $("form#frmTipoDivisas").submit(SaveOrUpdateTipoDivisa); // Evento submit de jquery que llamamos al metodo SaveOrUpdate para poder registrar o modificar datos

  $("#btnNuevoTipoDivisa").click(VerFormTipoDivisa); // evento click de jquery que llamamos al metodo VerForm

  function SaveOrUpdateTipoDivisa(e) {
    e.preventDefault();

    var formData = new FormData($("#frmTipoDivisas")[0]);

    $.ajax({
      url: "./ajax/TipoDivisaAjax.php?op=SaveOrUpdate",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,

      success: function (datos) {
        swal("Mensaje del Sistema", datos, "success");
        // ListadoTarjeta();
        OcultarFormTipoDivisa();
        ListadoTipoDivisa();

        LimpiarTipoDivisa()
      },
    });
  }

  function LimpiarTipoDivisa() {
    // Limpiamos las cajas de texto
    $("#txtIdtipoDivisa").val("");
    $("#txtDescripcionTipoDivisa").val("");
    $("#txtCodigoTipoDivisa").val("");
  }

  function VerFormTipoDivisa() {
    $("#VerFormTipoDivisa").show(); // Mostramos el formulario
    $("#btnNuevoTipoDivisa").hide(); // ocultamos el boton nuevo
    $("#VerListadoTipoDivisa").hide();
  }

  function OcultarFormTipoDivisa() {
    $("#VerFormTipoDivisa").hide(); // Mostramos el formulario
    $("#btnNuevoTipoDivisa").show(); // ocultamos el boton nuevo
    $("#VerListadoTipoDivisa").show();
  }

  // 


}

function ListadoTarjeta() {
  var tabla = $("#tblTarjeta")
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
      ],
      ajax: {
        url: "./ajax/TarjetaAjax.php?op=list",
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

function eliminarTarjeta(id) {
  // funcion que llamamos del archivo ajax/CategoriaAjax.php?op=delete linea 53
  bootbox.confirm("¿Esta Seguro de eliminar la Sucursal?", function (result) {
    // confirmamos con una pregunta si queremos eliminar
    if (result) {
      // si el result es true
      $.post("./ajax/TarjetaAjax.php?op=delete", { id: id }, function (e) {
        // llamamos la url de eliminar por post. y mandamos por parametro el id
        swal("Mensaje del Sistema", e, "success");
        ListadoTarjeta();
      });
    }
  });
}

function cargarDataTarjeta(id, descripcion, codigo) {
  // funcion que llamamos del archivo ajax/CategoriaAjax.php linea 52
  $("#VerForm").show(); // mostramos el formulario
  $("#btnNuevo").hide(); // ocultamos el boton nuevo
  $("#VerListado").hide(); // ocultamos el listado

  $("#txtIdtargeta").val(id); // recibimos la variable id a la caja de texto txtIdMarca
  $("#txtDescripcion").val(descripcion);
  $("#txtCodigo").val(codigo);
}



// nuevo tipo divisa 


function ListadoTipoDivisa() {
  var tabla = $("#tblTipoDivisa")
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
      ],
      ajax: {
        url: "./ajax/TipoDivisaAjax.php?op=list",
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

function eliminarTipoDivisa(id) {
  // funcion que llamamos del archivo ajax/CategoriaAjax.php?op=delete linea 53
  bootbox.confirm("¿Esta Seguro de eliminar la Sucursal?", function (result) {
    // confirmamos con una pregunta si queremos eliminar
    if (result) {
      // si el result es true
      $.post("./ajax/TipoDivisaAjax.php?op=delete", { id: id }, function (e) {
        // llamamos la url de eliminar por post. y mandamos por parametro el id
        swal("Mensaje del Sistema", e, "success");
        // ListadoTarjeta();
        ListadoTipoDivisa();
      });
    }
  });
}

function cargarDataTipoDivisa(id, descripcion, simbolo) {
  // funcion que llamamos del archivo ajax/CategoriaAjax.php linea 52
  $("#VerFormTipoDivisa").show(); // mostramos el formulario
  $("#btnNuevoTipoDivisa").hide(); // ocultamos el boton nuevo
  $("#VerListadoTipoDivisa").hide(); // ocultamos el listado

  $("#txtIdtipoDivisa").val(id); // recibimos la variable id a la caja de texto txtIdMarca
  $("#txtDescripcionTipoDivisa").val(descripcion);
  $("#txtCodigoTipoDivisa").val(simbolo);
}
