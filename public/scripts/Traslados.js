$(document).on("ready", init);

var objinit = new init();
var bandera = 1;
var detalleIngresos = new Array();
var detalleTraerCantidad = new Array();
elementos = new Array();
var email = "";





function AgregarPedCarritoTraslado(
  iddet_ing,
  stock_actual,
  art,
  cod,
  serie,
  precio_venta,
  idart,
  marca
) {
  if (stock_actual > 0) {
    //var detalles = new Array(iddet_ing, art, precio_venta, "1", "0.0", stock_actual, cod, serie);
    //elementos.push(detalles);
    //console.log(detalles);

    //let elementosSearch = [];

    var data = JSON.parse(objinit.consultar());
    var detalles = new Array(
      iddet_ing,
      art,
      precio_venta,
      "1",
      "0.0",
      stock_actual,
      1,
      serie,
      idart,
      marca
    );
    // COMPRUBA SI HAY PRODUCTOS AGREGADOS - SI NO, NO BUSCA NADA

    console.log(detalles);
    if (data.length >= 1) {
      let rptaSearch = data.find((element) => element[0] == iddet_ing);

      if (typeof rptaSearch === "undefined") {
        elementos.push(detalles);
      } else {
        alert("El producto elegido ya se encuentra ingresado en la lista...");
      }
    } else {
      elementos.push(detalles);
    }

    ConsultarDetallesTraslado();
  } else {
    bootbox.alert("No se puede agregar al detalle. No tiene stock");
  }
}

function verDetallesTraslados(val){

  var traslado=JSON.parse(val.replace(/\+/g,'"'))

    console.log(traslado)

  $("#info_traslado_inicial").val(traslado.almacen_inicial)
  $("#info_traslado_final").val(traslado.almacen_destino)
  $("#info_traslado_motivo").val(traslado.motivo_del_traslado)

  $("#VerFormTrasladosDetalles").show();


  CargarDetalleTraslado(traslado.idtraslado)



  $('#body_Traslados'
  ).hide()
}

function CargarDetalleTraslado(idtraslado) {
  //$('th:nth-child(2)').hide();
  //$('th:nth-child(3)').hide();
  $("table#tblDetallePedidoVer th:nth-child(4)").hide();
  $("table#tblDetallePedidoVer th:nth-child(8)").hide();

  $("table#tblDetallePedidoTraslado th:nth-child(4)").hide();
  $("table#tblDetallePedidoTraslado th:nth-child(8)").hide();

  $.post(
    "./ajax/TrasladosAjax.php?op=GetDetalleTraslados",
    {
      idtraslado: idtraslado,
    },
    function (r) {
      $("table#tblDetallePedidoTraslado tbody").html(r);
      $("table#tblDetallePedidoTraslado tbody").html(r);
    }
  );
}



function ConsultarDetallesTraslado() {
  $("table#tblDetallePedidoTraslado tbody").html("");

  var data = JSON.parse(JSON.stringify(elementos));

  for (var pos in data) {
    $("table#tblDetallePedidoTraslado").append(
      "<tr><td>" +
        data[pos][1] +
        " <input class='form-control' type='hidden' name='txtIdDetIng' id='txtIdDetIng[]' value='" +
        data[pos][0] +
        "' /></td><td>" +
        data[pos][6] +
        "</td><td> " +
        data[pos][7] +
        "</td><td> " +
        data[pos][9] +
        "</td><td>" +
        data[pos][5] +
        "</td><td><input class='form-control' min="+1+" max=" +
        data[pos][5] +
        " type='number' name='cantidadTrasladar' id='cantidadTrasladar[]' value='" +
        data[pos][6] +
        "'  onchange='guardarCantidadTrasladar(" +
        pos +
        ")' /></td><td><button type='button' onclick='eliminarDetalleTraslado(" +
        pos +
        ")' class='btn btn-danger'><i class='fa fa-remove' ></i> </button></td></tr>"
    );
  }
  //   calcularIgvPed();
  //   calcularSubTotalPed();
  //   calcularTotalPed();
}

function eliminarDetalleTraslado(pos) {
  pos > -1 && elementos.splice(parseInt(pos), 1);

  ConsultarDetalles();
  ConsultarDetallesTraslado();
}
function guardarCantidadTrasladar(pos) {
  var cantidadTrasladar = document.getElementsByName("cantidadTrasladar");

  elementos[pos][6] = cantidadTrasladar[pos].value;
}

function init() {

  $("#VerFormTrasladosDetalles").hide();

  $("#btnBuscarDetTraslados").click(AbrirModalDeTraslados);

  $("form#frmTraslados").submit(GuardarTraslado);

  ListadoTraslados();

  function ListadoTraslados() {
    var tabla = $("#tblTraslados")
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
        ],
        ajax: {
          url: "./ajax/TrasladosAjax.php?op=ListTipoTraslados",
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
  function Limpiar(){
    $("#motivo_de_traslado").val("");
    $("#tblDetallePedidoTraslado tbody").html("");

    elementos.length = 0;
  }

  function GuardarTraslado(e) {
    e.preventDefault();

    var formData = new FormData();

    var detalle = JSON.parse(JSON.stringify(elementos));

    formData.append("almacenInicial", $("#almacenFinal").val());

    formData.append("almacenFinal", $("#almacenFinal").val());

    formData.append("motivoDeTraslado", $("#motivo_de_traslado").val());

    // formData.append('idUsuario', $('#txtIdUsuario').val());

    for (var i = 0; i < detalle.length; i++) {
      formData.append("detalle[]", detalle[i]);
    }

    if ($("#almacenFinal").val() != $("#idtxtSucursalTraslado").val()) {
      if (elementos.length > 0) {
        if ($("#motivo_de_traslado").val() != "") {
          $.ajax({
            url: "./ajax/TrasladosAjax.php?op=Save",
            data: formData,
            processData: false,
            contentType: false,
            type: "POST",

            success: function (data) {
              swal("Mensaje del Sistema", data, "success");
              // delete this.elementos;
              // location.href="Traslados.php"
              //$("#tblDetallePedido tbody").html("");
              // $("#txtIgvPed").val("");
              // $("#txtTotalPed").val("");
              // $("#txtSubTotalPed").val("");
              // // OcultarForm();
              $("#VerFormPed").hide(); // Mostramos el formulario
              // $("#btnNuevoPedido").show();
              Limpiar();

              // $("#txtCliente").val("");
              ListadoTraslados();
              // GetPrimerCliente();
            },
          });
        } else {
          bootbox.alert("Debe describir el motivo del traslado");
        }
      } else {
        bootbox.alert("Debe agregar Productos para Realizar el traslado");
      }
    } else {
      bootbox.alert("La sucursal final no puede ser la sucursal final");
    }
  }

  function AbrirModalDeTraslados() {
    $("#modalListadoArticulosTraslados").modal("show");
    var tabla = $("#tblArticulosPedTraslados")
      .dataTable({
        aProcessing: true,
        aServerSide: true,
        pageLength: 7,
        //"search": false ,
        // "iDisplayLength": 7,
        //"aLengthMenu": [0, 4],
        aoColumns: [
          {
            mDataProp: "0",
          },
          {
            mDataProp: "1",
          },
          {
            mDataProp: "2",
          },
          {
            mDataProp: "3",
          },
          {
            mDataProp: "4",
          },
          {
            mDataProp: "5",
          },
          {
            mDataProp: "6",
          },
          {
            mDataProp: "7",
          },
        ],
        ajax: {
          url: "./ajax/TrasladosAjax.php?op=listDetIng",
          type: "get",
          dataType: "json",
          data: {
            // idSucursal: $("#txtSucursalTraslado").val(),
          },
          error: function (e) {
            console.log(e.responseText);
          },
        },
        bDestroy: true, //funcion que causa problemas en el rendimiento
      })
      .DataTable();
  }
}
