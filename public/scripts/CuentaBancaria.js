$(document).on("ready", init); // Inciamos el jquery
function ListadoCuentaBancaria() {
  var tabla = $("#tblCuentaBancario")
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
        url: "./ajax/CuentaBancariaAjax.php?op=list",
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
function traerTipoDivisa() {

  $.ajax({
    url: "./ajax/CuentaBancariaAjax.php?op=traerTipoDivisa",
    dataType: "json",
    type: "get",
    success: function (rpta) {
      console.log(rpta);
      var options_html = '<option value=""></option>';

      rpta.map((e) => {
        options_html += `<option data-id='${e.descripcion}' value='${e.idtipo_divisa}'> ${e.simbolo} </option>`;
      });

      $(".tipo_divisa_select_all").html(options_html);
    },
    error: function (e) {
      console.log(e);
    },
  });
}

function traerTipoCuenta() {
  $.ajax({
    url: "./ajax/CuentaBancariaAjax.php?op=traerTipoCuenta",
    dataType: "json",
    type: "get",
    success: function (rpta) {
      console.log(rpta);
      var options_html = '<option value=""></option>';

      rpta.map((e) => {
        options_html += `<option data-id='${e.descripcion}' value='${e.idtipo_cuenta}'> ${e.descripcion} </option>`;
      });

      $(".tipo_cuenta_select_all").html(options_html);
    },
    error: function (e) {
      console.log(e);
    },
  });
}

function traerBanco() {
  $.ajax({
    url: "./ajax/CuentaBancariaAjax.php?op=traerBanco",
    dataType: "json",
    type: "get",
    success: function (rpta) {
      console.log(rpta);
      var options_html = '<option value=""></option>';

      rpta.map((e) => {
        options_html += `<option data-id='${e.descripcion}' value='${e.idbanco}'> ${e.descripcion} </option>`;
      });

      $(".banco_select_all").html(options_html);
    },
    error: function (e) {
      console.log(e);
    },
  });
}
  
function init() {
  traerBanco();
  traerTipoCuenta();
  traerTipoDivisa()


  $("#tblCuentaBancario").dataTable({
    dom: "Bfrtip",
    buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
  });

  ListadoCuentaBancaria(); // Ni bien carga la pagina que cargue el metodo
  ComboTipo_Documento();
  $("#VerForm").hide(); // Ocultamos el formulario
  $("form#frmCuentaBancaria").submit(SaveOrUpdate); // Evento submit de jquery que llamamos al metodo SaveOrUpdate para poder registrar o modificar datos

  $("#btnNuevo").click(VerForm); // evento click de jquery que llamamos al metodo VerForm

  function SaveOrUpdate(e) {
    e.preventDefault();

    var formData = new FormData($("#frmCuentaBancaria")[0]);

    $.ajax({
      url: "./ajax/CuentaBancariaAjax.php?op=SaveOrUpdate",

      type: "POST",

      data: formData,

      contentType: false,

      processData: false,

      success: function (datos) {
        swal("Mensaje del Sistema", datos, "success");
        ListadoCuentaBancaria();
        OcultarForm();
        Limpiar();
      },
    });
  }

  function Limpiar() {
    // Limpiamos las cajas de texto
    $("#txtIdcuenta_bancaria").val("");
    $("#txtDescripcion").val("");
    $("#txtNumero").val("");


    $("#txtIdcuenta_bancaria").val("");
    $("#cboModTipo_Moneda").val("");
    $("#cboModTipo_de_Cuenta").val("");
  
    $("#cboModIdBanco").val("");
    $("#txtModNombreTitular").val("");
    $("#txtNombreCuentaBancaria").val("");
    $("#txtNumero").val("");
    $("#txtNumeroCCI").val("");
    $("#txtDescripcion").val("");
    $("#txtSaldoInicial").val("");
  }

  function VerForm() {
    $("#VerForm").show(); // Mostramos el formulario
    $("#btnNuevo").hide(); // ocultamos el boton nuevo
    $("#VerListado").hide();
  }

  function OcultarForm() {
    $("#VerForm").hide(); // Mostramos el formulario
    $("#btnNuevo").show(); // ocultamos el boton nuevo
    $("#VerListado").show();
  }
}

function eliminarCuentaBancaria(id) {
  // funcion que llamamos del archivo ajax/CategoriaAjax.php?op=delete linea 53
  bootbox.confirm("¿Esta Seguro de eliminar la Sucursal?", function (result) {
    // confirmamos con una pregunta si queremos eliminar
    if (result) {
      // si el result es true
      $.post(
        "./ajax/CuentaBancariaAjax.php?op=delete",
        { id: id },
        function (e) {
          // llamamos la url de eliminar por post. y mandamos por parametro el id
          swal("Mensaje del Sistema", e, "success");
          ListadoCuentaBancaria();
        }
      );
    }
  });
}

function cargarDataCuentaBancaria(
  id,
  tipo_moneda, 
  idtipo_cuenta,
  idbanco,
  nombre_titular,
  nombre_banco_cuenta,
  numero,
  cci,
  descripcion,
  balance_inicial,
  ) {
  // funcion que llamamos del archivo ajax/CategoriaAjax.php linea 52
  $("#VerForm").show(); // mostramos el formulario
  $("#btnNuevo").hide(); // ocultamos el boton nuevo
  $("#VerListado").hide(); // ocultamos el listado

  $("#txtIdcuenta_bancaria").val(id);
  $("#cboModTipo_Moneda").val(tipo_moneda);
  $("#cboModTipo_de_Cuenta").val(idtipo_cuenta);

  $("#cboModIdBanco").val(idbanco);
  $("#txtModNombreTitular").val(nombre_titular);
  $("#txtNombreCuentaBancaria").val(nombre_banco_cuenta);
  $("#txtNumero").val(numero);
  $("#txtNumeroCCI").val(cci);
  $("#txtDescripcion").val(descripcion);
  $("#txtSaldoInicial").val(balance_inicial);
  
}
