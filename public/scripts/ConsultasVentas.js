$(document).on("ready", init); // Inciamos el jquery
// var objC = new init();
function init() {
  $('#cerrar_formulario').click(function(e){
    console.log('hola')

    $("#VerFormVentaPed").hide();
    $("#VerListado").show();
    //$("#btnNuevoVent").hide();
    // $(".inputs_filtros").show();

    $('#cboFechaDesdeVent').show()
    $('.ventasFechasOcultar').show()
    $('#lblHasta').show()
    $('#cboFechaHastaVent').show()

  })

  $("#tblVentaFechas").dataTable({
    dom: "Bfrtip",
    buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
  });

  $("#tblVentasDetalladas").dataTable({
    dom: "Bfrtip",
    buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
  });
  $("#tblVentaAnuladas").dataTable({
    dom: "Bfrtip",
    buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
  });
  $("#tblVentasPendientes").dataTable({
    dom: "Bfrtip",
    buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
  });
  $("#tblVentasContado").dataTable({
    dom: "Bfrtip",
    buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
  });
  $("#tblVentasCredito").dataTable({
    dom: "Bfrtip",
    buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
  });
  $("#tblVentasCliente").dataTable({
    dom: "Bfrtip",
    buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
  });
  $("#tblVentaEmp").dataTable({
    dom: "Bfrtip",
    buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
  });
  $("#tblVentaEmpDet").dataTable({
    dom: "Bfrtip",
    buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
  });

  $("#cboFechaDesdeVent").change(ListadoVentasFechas);
  $("#cboFechaHastaVent").change(ListadoVentasFechas);
  $("#cboTipo_Cliente").change(ListadoVentasFechas);
  $("#cboAntiguedadCliente").change(ListadoVentasFechas);
  $("#cboEjecutivoComercial").change(ListadoVentasFechas);

  $("#cboFechaDesdeDetVent").change(ListadoVentasDetalladas);
  $("#cboFechaHastaDetVent").change(ListadoVentasDetalladas);

  $("#cboFechaDesdeAnuVent").change(ListadoVentasAnuladas);
  $("#cboFechaHastaAnuVent").change(ListadoVentasAnuladas);

  $("#cboFechaDesdeVentPend").change(ListadoVentasPendientes);
  $("#cboFechaHastaVentPend").change(ListadoVentasPendientes);

  $("#cboFechaDesdeVentCont").change(ListadoVentasContados);
  $("#cboFechaHastaVentCont").change(ListadoVentasContados);

  $("#cboFechaDesdeVentCred").change(ListadoVentasCredito);
  $("#cboFechaHastaVentCred").change(ListadoVentasCredito);

  $("#txtIdCliente").change(ListadoVentasCliente);
  $("#cboFechaDesdeCli").change(ListadoVentasCliente);
  $("#cboFechaHastaCli").change(ListadoVentasCliente);

  $("#cboFechaDesdeVentEmp").change(ListadoVentasEmpleado);
  $("#cboFechaHastaVentEmp").change(ListadoVentasEmpleado);

  $("#cboFechaDesdeVentEmpDet").change(ListadoVentasEmpleadoDet);
  $("#cboFechaHastaVentEmpDet").change(ListadoVentasEmpleadoDet);

  TraerRoles();
}

function TraerRoles() {
  $.ajax({
    url: "./ajax/ConsultasVentasAjax.php?op=listaEjecutivoComercial",
    dataType: "json",

    success: function (s) {

      var lista = s;

      var html = `<option value=""></option>`;
      lista.map((e) => {
        html += `
				<option value="${e.idempleado}">${e.nombre}</option>`;
      });

      $("#cboEjecutivoComercial").html(html);
    },
    error: function (e) {
      
    },
  });
}
function ListadoVentasFechas() {
  if (
    $("#cboFechaDesdeVent").val() != "" &&
    $("#cboFechaHastaVent").val() != ""
  ) {
    var fecha_desde = $("#cboFechaDesdeVent").val()
    var fecha_hasta = $("#cboFechaHastaVent").val()
    var idsucursal = $("#txtIdSucursal").val()

    
    var ejecutivo_comercial = $("#cboEjecutivoComercial").val();
    var antiguedad_cliente = $("#cboAntiguedadCliente").val();
    var tipo_cliente = $("#cboTipo_Cliente").val();
    
    var tabla = $("#tblVentaFechas")
      .dataTable({
        aProcessing: true,
        aServerSide: true,
        dom: "Bfrtip",

        "order": [[1, "desc"]],

        buttons: ["copyHtml5",
        {
          extend: "excelHtml5",
          text: "Excel",
          exportOptions: {
                 columns: [0,1,2,3,4,5,6,7,8,9,10,11,12]
          }
          }
          
        , "csvHtml5", "pdfHtml5"],
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
          { mDataProp: "9" },
          { mDataProp: "10" },
          { mDataProp: "11" },
          { mDataProp: "12" },
          { mDataProp: "13" },
        ],
        
        ajax: {
          url: "./ajax/ConsultasVentasAjax.php?op=listVentasFechas",
          type: "get",
          dataType: "json",
          data: {
            fecha_desde: fecha_desde,
            fecha_hasta: fecha_hasta,
            idsucursal: idsucursal,
            ejecutivo_comercial: ejecutivo_comercial,
            antiguedad_cliente: antiguedad_cliente,
            tipo_cliente: tipo_cliente,
          },


          error: function (e) {
            console.log(e.responseText);
          },
        },
        bDestroy: true,
      })
      .DataTable();
  }
}

function ListadoVentasDetalladas() {
  if (
    $("#cboFechaDesdeDetVent").val() != "" &&
    $("#cboFechaHastaDetVent").val() != ""
  ) {
    var fecha_desde = $("#cboFechaDesdeDetVent").val()

  
      fecha_hasta = $("#cboFechaHastaDetVent").val()
      idsucursal = $("#txtIdSucursal").val();
    var tabla = $("#tblVentasDetalladas")
      .dataTable({
        aProcessing: true,
        aServerSide: true,
        "order": [[1, "desc"]],
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
          { mDataProp: "9" },
          { mDataProp: "10" },
          { mDataProp: "11" },
          { mDataProp: "12" },
          { mDataProp: "13" },
          { mDataProp: "14" },
          { mDataProp: "15" },
          { mDataProp: "16" },
          { mDataProp: "17" },
          { mDataProp: "18" },
          { mDataProp: "19" },
          { mDataProp: "20" },
          { mDataProp: "21" },
          { mDataProp: "22" },
          { mDataProp: "23" },
          { mDataProp: "24" },
          { mDataProp: "25" },
          
        ],
        ajax: {
          url: "./ajax/ConsultasVentasAjax.php?op=listVentasDetalladas",
          type: "get",
          dataType: "json",
          data: {
            fecha_desde: fecha_desde,
            fecha_hasta: fecha_hasta,
        
          },
          error: function (e) {
            console.log(e.responseText);
          },
        },
        bDestroy: true,
      })
      .DataTable();
  }
}

function ListadoVentasAnuladas() {

  if (
    $("#cboFechaDesdeAnuVent").val() != "" &&
    $("#cboFechaHastaAnuVent").val() != ""
  ) {
    var fecha_desde = $("#cboFechaDesdeAnuVent").val(),
      fecha_hasta = $("#cboFechaHastaAnuVent").val(),
      idsucursal = $("#txtIdSucursal").val();

    var tabla = $("#tblVentaAnuladas")
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
          { mDataProp: "9" },
          { mDataProp: "10" },
          { mDataProp: "11" },
          { mDataProp: "12" },
          /* {   "mDataProp": "13"} */
        ],
        ajax: {
          url: "./ajax/ConsultasVentasAjax.php?op=listVentasAnuladas",
          type: "get",
          dataType: "json",
          data: {
            fecha_desde: fecha_desde,
            fecha_hasta: fecha_hasta,
            idsucursal: idsucursal,
          },

          error: function (e) {
            console.log(e.responseText);
          },
        },
        bDestroy: true,
      })
      .DataTable();
  }
}

function ListadoVentasPendientes() {
  if (
    $("#cboFechaDesdeVentPend").val() != "" &&
    $("#cboFechaHastaVentPend").val() != ""
  ) {
    var fecha_desde = $("#cboFechaDesdeVentPend").val(),
      fecha_hasta = $("#cboFechaHastaVentPend").val(),
      idsucursal = $("#txtIdSucursal").val();
    var tabla = $("#tblVentasPendientes")
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
          { mDataProp: "9" },
          { mDataProp: "10" },
          { mDataProp: "11" },
          { mDataProp: "12" },
        ],
        ajax: {
          url: "./ajax/ConsultasVentasAjax.php?op=listVentasPendientes",
          type: "get",
          dataType: "json",
          data: {
            fecha_desde: fecha_desde,
            fecha_hasta: fecha_hasta,
            idsucursal: idsucursal,
          },
          error: function (e) {
            console.log(e.responseText);
          },
        },
        bDestroy: true,
      })
      .DataTable();
  }
}

function ListadoVentasContados() {
  if (
    $("#cboFechaDesdeVentCont").val() != "" &&
    $("#cboFechaHastaVentCont").val() != ""
  ) {
    var fecha_desde = $("#cboFechaDesdeVentCont").val(),
      fecha_hasta = $("#cboFechaHastaVentCont").val(),
      idsucursal = $("#txtIdSucursal").val();
    var tabla = $("#tblVentasContado")
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
          { mDataProp: "9" },
          { mDataProp: "10" },
        ],
        ajax: {
          url: "./ajax/ConsultasVentasAjax.php?op=listVentasContado",
          type: "get",
          dataType: "json",
          data: {
            fecha_desde: fecha_desde,
            fecha_hasta: fecha_hasta,
            idsucursal: idsucursal,
          },
          error: function (e) {
            console.log(e.responseText);
          },
        },
        bDestroy: true,
      })
      .DataTable();
  }
}

function ListadoVentasCredito() {
  if (
    $("#cboFechaDesdeVentCred").val() != "" &&
    $("#cboFechaHastaVentCred").val() != ""
  ) {
    var fecha_desde = $("#cboFechaDesdeVentCred").val(),
      fecha_hasta = $("#cboFechaHastaVentCred").val(),
      idsucursal = $("#txtIdSucursal").val();
    var tabla = $("#tblVentasCredito")
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
          { mDataProp: "9" },
          { mDataProp: "10" },
          { mDataProp: "11" },
          { mDataProp: "12" },
        ],
        ajax: {
          url: "./ajax/ConsultasVentasAjax.php?op=listVentasCredito",
          type: "get",
          dataType: "json",
          data: {
            fecha_desde: fecha_desde,
            fecha_hasta: fecha_hasta,
            idsucursal: idsucursal,
          },
          error: function (e) {
            console.log(e.responseText);
          },
        },
        bDestroy: true,
      })
      .DataTable();
  }
}

function ListadoVentasCliente() {
  if (
    $("#cboFechaDesdeCli").val() != "" &&
    $("#cboFechaHastaCli").val() != "" &&
    $("#txtIdCliente").val() != ""
  ) {
    var idCliente = $("#txtIdCliente").val(),
      fecha_desde = $("#cboFechaDesdeCli").val(),
      fecha_hasta = $("#cboFechaHastaCli").val(),
      idsucursal = $("#txtIdSucursal").val();
    var tabla = $("#tblVentasCliente")
      .dataTable({
        aProcessing: true,
        aServerSide: true,
        dom: "Bfrtip",
        "order": [[ 0, "desc" ]],
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
          { mDataProp: "9" },
          { mDataProp: "10" },
          { mDataProp: "11" },
          /* {   "mDataProp": "12"},
						{   "mDataProp": "13"} */
        ],
        ajax: {
          url: "./ajax/ConsultasVentasAjax.php?op=listVentasCliente",
          type: "get",
          dataType: "json",
          data: {
            idCliente: idCliente,
            fecha_desde: fecha_desde,
            fecha_hasta: fecha_hasta,
            idsucursal: idsucursal,
          },
          error: function (e) {
            console.log(e.responseText);
          },
        },
        bDestroy: true,
      })
      .DataTable();
  }
}
function ListadoComprasDetProveedor() {
  if (
    $("#cboFechaDesdeDetProv").val() != "" &&
    $("#cboFechaHastaDetProv").val() != "" &&
    $("#txtIdProveedor").val() != ""
  ) {
    var idProveedor = $("#txtIdProveedor").val(),
      fecha_desde = $("#cboFechaDesdeDetProv").val(),
      fecha_hasta = $("#cboFechaHastaDetProv").val(),
      idsucursal = $("#txtIdSucursal").val();
    var tabla = $("#tblComprasDetProveedor")
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
          { mDataProp: "9" },
          { mDataProp: "10" },
          { mDataProp: "11" },
          { mDataProp: "12" },
          { mDataProp: "13" },
          { mDataProp: "14" },
          { mDataProp: "15" },
          { mDataProp: "16" },
        ],
        ajax: {
          url: "./ajax/ConsultasComprasAjax.php?op=listComprasDetProveedor",
          type: "get",
          dataType: "json",
          data: {
            idProveedor: idProveedor,
            fecha_desde: fecha_desde,
            fecha_hasta: fecha_hasta,
            idsucursal: idsucursal,
          },
          error: function (e) {
            console.log(e.responseText);
          },
        },
        bDestroy: true,
      })
      .DataTable();
  }
}

function ListadoVentasEmpleado() {
  if (
    $("#cboFechaDesdeVentEmp").val() != "" &&
    $("#cboFechaHastaVentEmp").val() != ""
  ) {
    var fecha_desde = $("#cboFechaDesdeVentEmp").val(),
      fecha_hasta = $("#cboFechaHastaVentEmp").val(),
      idsucursal = $("#txtIdSucursal").val();
    var tabla = $("#tblVentaEmp")
      .dataTable({
        aProcessing: true,
        aServerSide: true,
        dom: "Bfrtip",
        buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
        aoColumns: [
          { mDataProp: "1" },
          { mDataProp: "2" },
          { mDataProp: "3" },
          { mDataProp: "4" },
          { mDataProp: "5" },
          { mDataProp: "6" },
          { mDataProp: "7" },
          { mDataProp: "8" },
          { mDataProp: "9" },
          { mDataProp: "10" },
          { mDataProp: "11" },
        ],
        ajax: {
          url: "./ajax/ConsultasVentasAjax.php?op=listVentasEmpleado",
          type: "get",
          dataType: "json",
          data: {
            fecha_desde: fecha_desde,
            fecha_hasta: fecha_hasta,
            idsucursal: idsucursal,
          },
          error: function (e) {
            console.log(e.responseText);
          },
        },
        bDestroy: true,
      })
      .DataTable();
  }
}

function ListadoVentasEmpleadoDet() {
  if (
    $("#cboFechaDesdeVentEmpDet").val() != "" &&
    $("#cboFechaHastaVentEmpDet").val() != ""
  ) {
    var fecha_desde = $("#cboFechaDesdeVentEmpDet").val(),
      fecha_hasta = $("#cboFechaHastaVentEmpDet").val(),
      idsucursal = $("#txtIdSucursal").val();
    var tabla = $("#tblVentaEmpDet")
      .dataTable({
        aProcessing: true,
        aServerSide: true,
        dom: "Bfrtip",
        buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
        aoColumns: [
          { mDataProp: "1" },
          { mDataProp: "2" },
          { mDataProp: "3" },
          { mDataProp: "4" },
          { mDataProp: "5" },
          { mDataProp: "6" },
          { mDataProp: "7" },
          { mDataProp: "8" },
          { mDataProp: "9" },
          { mDataProp: "10" },
          { mDataProp: "11" },
          { mDataProp: "12" },
          { mDataProp: "13" },
          { mDataProp: "14" },
          { mDataProp: "15" },
        ],
        ajax: {
          url: "./ajax/ConsultasVentasAjax.php?op=listVentasEmpleadoDet",
          type: "get",
          dataType: "json",
          data: {
            fecha_desde: fecha_desde,
            fecha_hasta: fecha_hasta,
            idsucursal: idsucursal,

            idsucursal: idsucursal,
            idsucursal: idsucursal,
            idsucursal: idsucursal,
          },
          error: function (e) {
            console.log(e.responseText);
          },
        },
        bDestroy: true,
      })
      .DataTable();
  }
}
