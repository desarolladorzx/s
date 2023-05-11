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

function verDetallesTraslados(val) {
  console.log(val);

  // var traslado = JSON.parse(val.replace(/\+/g, '"'));
  var traslado;
  console.log(traslado);

  $.getJSON(
    "./ajax/TrasladosAjax.php?op=TraerDatos",
    {
      idtraslado: val,
    },
    function (r) {
      traslado = r;
      $("#info_traslado_inicial").val(traslado.almacen_inicial);
      $("#info_traslado_final").val(traslado.almacen_destino);
      $("#info_traslado_motivo").val(traslado.motivo_del_traslado);

      $("#container_descripcion_recepcion").show();
      $("#info_descripcion_recepcion").val(traslado.descripcion_recepcion);
      $("#info_descripcion_recepcion").prop("disabled", true);

      $("#VerFormTrasladosDetalles").show();

      CargarDetalleTraslado(traslado.idtraslado);

      var htmlEstado = `<option value="SALIDA" >EN ESPERA</option>
    <option value="EN TRANSITO">EN TRANSITO</option>
    <option value="ALMACEN OPERADOR" >EN ALMACEN OPERADOR</option>
    <option value="INGRESO" >ENTREGADO</option>`;

      $("#info_fecha_registro").val(traslado.fecha_registro);
      $("#info_fecha_modificado").val(traslado.fecha_modificado);

      $("#info_usuario_registro").val(traslado.empleado_ingreso);
      $("#info_usuario_recepcion").val(traslado.empleado_recepcion);

      $("#estadoTraslado").append(htmlEstado);
      $("#estadoTraslado").val(traslado.estado);

      $("#estadoTraslado").prop("disabled", true);
      $("#body_Traslados").hide();
    }
  );
}

let dataTraslados;
function modificarTraslados(val) {
  $("#containerGuardarCambiarEstado").show();
  var traslado = JSON.parse(val.replace(/\+/g, '"'));

  dataTraslados = traslado;

  $("#info_fecha_registro").val(traslado.fecha_registro);
  $("#info_fecha_modificado").val(traslado.fecha_modificado);

  $("#info_usuario_registro").val(traslado.empleado_ingreso);
  $("#info_usuario_recepcion").val(traslado.empleado_recepcion);

  console.log(traslado.sucursal_destino_id);
  var htmlEstado = "";

  switch (traslado.estado) {
    case "SALIDA":
      htmlEstado = `<option value="SALIDA" >EN ESPERA</option>
                    <option value="EN TRANSITO">EN TRANSITO</option>
                    <option value="ALMACEN OPERADOR" disabled>EN ALMACEN OPERADOR</option>
                    <option value="INGRESO" disabled>ENTREGADO</option>
                    `;
      break;
    case "EN TRANSITO":
      htmlEstado = `<option value="SALIDA" disabled>EN ESPERA</option>
                    <option value="EN TRANSITO">EN TRANSITO</option>
                    <option value="ALMACEN OPERADOR">EN ALMACEN OPERADOR</option>
                    <option value="INGRESO" disabled>ENTREGADO</option>
                    `;
      break;
    case "ALMACEN OPERADOR":
      htmlEstado = `<option value="SALIDA" disabled>EN ESPERA</option>
                      <option value="EN TRANSITO" disabled>EN TRANSITO</option>
                      <option value="ALMACEN OPERADOR" >EN ALMACEN OPERADOR</option>
                      <option value="INGRESO" >ENTREGADO</option>
                      `;
      break;
    case "INGRESO":
      $("#container_descripcion_recepcion").show();
      $("#info_descripcion_recepcion").val(traslado.descripcion_recepcion);

      htmlEstado = `<option value="SALIDA"  disabled>EN ESPERA</option>
                        <option value="EN TRANSITO" disabled>EN TRANSITO</option>
                        <option value="ALMACEN OPERADOR" disabled>EN ALMACEN OPERADOR</option>
                        <option value="INGRESO" disabled>ENTREGADO</option>
                        `;
      break;
    default:
      break;
  }
  // htmlEstado = `<option value="SALIDA" >EN ESPERA</option>
  // <option value="EN TRANSITO">EN TRANSITO</option>
  // <option value="ALMACEN OPERADOR" >EN ALMACEN OPERADOR</option>
  // <option value="INGRESO" >ENTREGADO</option>`;

  $("#estadoTraslado").append(htmlEstado);
  $("#estadoTraslado").val(traslado.estado);

  $("#info_traslado_inicial").val(traslado.almacen_inicial);
  $("#info_traslado_final").val(traslado.almacen_destino);
  $("#info_traslado_motivo").val(traslado.motivo_del_traslado);

  $("#VerFormTrasladosDetalles").show();

  CargarDetalleTraslado(traslado.idtraslado);

  $("#body_Traslados").hide();
}

var arrayDatosRecibidos = [];

var idtrasladojs;
function CargarDetalleTraslado(idtraslado) {
  //$('th:nth-child(2)').hide();
  //$('th:nth-child(3)').hide();

  console.log(idtraslado);

  idtrasladojs = idtraslado;

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
      // console.log(r);
      arrayDatosRecibidos = JSON.parse(r);

      for (var pos in arrayDatosRecibidos) {
        arrayDatosRecibidos[pos].cantidadRecibida = null;

        var cantidad;
        if (arrayDatosRecibidos[pos].estado_detalle_ingreso == "INGRESO") {
          cantidad = arrayDatosRecibidos[pos].stock_ingreso;
        } else {
          cantidad = arrayDatosRecibidos[pos].cantidadRecibida;
        }
        $("table#tblDetallePedidoTrasladoRecibido").append(
          "<tr><td>" +
            arrayDatosRecibidos[pos].Articulo +
            "</td><td>" +
            arrayDatosRecibidos[pos].Codigo +
            "</td><td>" +
            arrayDatosRecibidos[pos].Serie +
            "</td><td> " +
            arrayDatosRecibidos[pos].marca +
            "</td><td> " +
            Math.trunc(arrayDatosRecibidos[pos].Cantidad) +
            "</td><td><input class='form-control' disabled  max=" +
            Math.trunc(arrayDatosRecibidos[pos].Cantidad) +
            " type='number' name='cantidadRecibida' id='cantidadRecibida[]'  value=" +
            cantidad +
            "  onchange='guardarCantidadRecibida(" +
            pos +
            ")' /></td>"
        );
      }
    }
  );
}
function guardarCantidadRecibida(pos) {
  var cantidadRecibida = document.getElementsByName("cantidadRecibida");
  arrayDatosRecibidos[pos].cantidadRecibida = cantidadRecibida[pos].value;
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
        "</td><td><input class='form-control' min=" +
        1 +
        " max=" +
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
  $("#containerGuardarCambiarEstado").hide();
  $("#container_descripcion_recepcion").hide();
  $("#estadoTraslado").change(function (e) {
    var valorEstado = e.target.value;

    if (valorEstado == "INGRESO") {
      var cantidadRecibida = document.getElementsByName("cantidadRecibida");

      bootbox.alert("Por favor rellene la candidad que ingreso al almacen ");
      cantidadRecibida.forEach((element) => {
        element.disabled = false;
      });
      $("#container_descripcion_recepcion").show();
    }
  });

  $("#guardarCambiarEstado").click(function (e) {
    var estado = $("#estadoTraslado").val();

    var formData = new FormData();

    var descripcion_recepcion = $("#info_descripcion_recepcion")
      .val()
      .replace(/[.,\/#!$%\^&\*;:{}=\-_`~()]/g, ".")
      .replace(/\n/g, " ")
      .replace(/(\r\n|\n|\r)/gm, ". - ");
    formData.append("idtraslado", idtrasladojs);

    formData.append("estado", estado);
    formData.append("arrayDatos", JSON.stringify(arrayDatosRecibidos));

    formData.append("descripcion_recepcion", descripcion_recepcion);

    dataTraslados.sucursal_destino_id
      ? formData.append(
          "sucursal_destino_id",
          dataTraslados.sucursal_destino_id
        )
      : formData.append("sucursal_destino_id", "");

    console.log(descripcion_recepcion);
    if (estado == "INGRESO") {
      let errores = false;

      if (!descripcion_recepcion) {
        bootbox.alert(
          `Es necesario la descripcion de la entrega para guardar el cambio`
        );

        errores = true;
      } else {
        console.log(descripcion_recepcion);
      }
      arrayDatosRecibidos.map((dato) => {
        if (!dato.cantidadRecibida) {
          bootbox.alert(
            `Es necesario la cantidad recibida en el producto ${dato.Articulo} `
          );
          errores = true;
        } else {
          if (dato.cantidadRecibida > Number(dato.Cantidad)) {
            bootbox.alert(
              `La cantidad trasladad no puede ser mayor a la cantidad recibida en el producto ${dato.Articulo} `
            );
            errores = true;
          }
        }
      });
      if (errores == false) {
        $.ajax({
          url: "./ajax/TrasladosAjax.php?op=modificarEstadoTraslado",
          data: formData,
          processData: false,
          contentType: false,
          type: "POST",

          success: function (data) {
            console.log(data);
            swal("Mensaje del Sistema", data, "success");
            location.href = "Traslados.php";
          },
        });
      }
    } else {
      if (estado !== "SALIDA") {
        $.ajax({
          url: "./ajax/TrasladosAjax.php?op=modificarEstadoTraslado",
          data: formData,
          processData: false,
          contentType: false,
          type: "POST",

          success: function (data) {
            console.log(data);
            swal("Mensaje del Sistema", data, "success");
            // delete this.elementos;
            location.href = "Traslados.php";
            //$("#tblDetallePedido tbody").html("");
            // $("#txtIgvPed").val("");
            // $("#txtTotalPed").val("");
            // $("#txtSubTotalPed").val("");
            // // OcultarForm();
            // $("#VerFormPed").hide(); // Mostramos el formulario
            // $("#btnNuevoPedido").show();
            // Limpiar();

            // $("#txtCliente").val("");
            // ListadoTraslados();
            // GetPrimerCliente();
          },
        });
      }
    }
  });

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
          { mDataProp: "7" },

          { mDataProp: "8" },
          { mDataProp: "9" },
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
  function Limpiar() {
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

    console.log(
      $("#motivo_de_traslado")
        .val()
        .replace(/[.,\/#!$%\^&\*;:{}=\-_`~()]/g, ".")
        .replace(/\n/g, " ")
        .replace(/(\r\n|\n|\r)/gm, ".- ")
    );

    formData.append(
      "motivoDeTraslado",
      $("#motivo_de_traslado")
        .val()
        .replace(/[.,\/#!$%\^&\*;:{}=\-_`~()]/g, ".")
        .replace(/\n/g, " ")
        .replace(/(\r\n|\n|\r)/gm, " ")
    );

    // formData.append('idUsuario', $('#txtIdUsuario').val());

    for (var i = 0; i < detalle.length; i++) {
      formData.append("detalle[]", detalle[i]);
    }

    console.log($("#idtxtSucursalTraslado").val());
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
              location.href = "Traslados.php";
              //$("#tblDetallePedido tbody").html("");
              // $("#txtIgvPed").val("");
              // $("#txtTotalPed").val("");
              // $("#txtSubTotalPed").val("");
              // // OcultarForm();
              // $("#VerFormPed").hide(); // Mostramos el formulario
              // $("#btnNuevoPedido").show();
              // Limpiar();

              // $("#txtCliente").val("");
              // ListadoTraslados();
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
          {
            mDataProp: "8",
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
