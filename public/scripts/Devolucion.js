$(document).on("ready", init);
var objinit = new init();
var bandera = 1;
var detalleIngresos = new Array();
var detalleTraerCantidad = new Array();
elementos = new Array();
var email = "";

function init() {
    VistaDevolucion()
  function VistaDevolucion(){
    var tabla = $("#tblDevolucion")
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
        url: "./ajax/DevolucionAjax.php?op=ListTipoDevoluciones",
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


  $("form#frmDevolucion").submit(GuardarDevolucion);
  $("#verdetallesDevoluciones").hide()
  $("#btnBuscarDetDevolucion").click(AbrirModalDetDevolucion);
  function Traer_devolucion_motivo() {
 

    $.ajax({
      url: "./ajax/DevolucionAjax.php?op=Traer_devolucion_motivo",
      // data: formData,
      processData: false,
      contentType: false,
      type: "GET",

      success: function (data) {
        let devolucion_array_motivo = JSON.parse(data);

        let optionArray = `<option value=""></option>`;
        devolucion_array_motivo.map((e) => {
          optionArray += ` 
                        <option value="${e[0]}">${e[1]}</option>
                        `;
        });

        $(".cboDevolucionMotivo").html(optionArray);
        //   swal("Mensaje del Sistema", data, "success");
      },
    });
  }
  Traer_devolucion_motivo();

  function AbrirModalDetDevolucion() {
    $("#modalListadoArticulosDev").modal("show");
    var tabla = $("#tblArticulosPed")
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
          }
        ],
        ajax: {
          url: "./ajax/DevolucionAjax.php?op=listDetIng",
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

  function GuardarDevolucion(e) {
    e.preventDefault();

    var formData = new FormData();

    var detalle = JSON.parse(JSON.stringify(elementos));

    console.log(detalle);

    formData.append("iddevolucion_motivo", $("#cboDevolucionMotivo").val());
    formData.append("observacion", $("#motivo_de_devolucion").val().replace(/[.,\/#!$%\^&\*;:{}=\-_`~()]/g,"."));
    formData.append("fecha", $("#fecha_devolucion").val());

    // formData.append('idUsuario', $('#txtIdUsuario').val());

    for (var i = 0; i < detalle.length; i++) {
      formData.append("detalle[]", detalle[i]);
    }

    console.log(formData.values());

    if ($("#cboDevolucionMotivo").val() != "") {
      if ($("#fecha_devolucion").val() != "") {
        if ($("#iddevolucion_motivo").val() != "") {
          if (elementos.length > 0) {
            if ($("#motivo_de_devolucion").val() != "") {
              $.ajax({
                url: "./ajax/DevolucionAjax.php?op=Save",
                data: formData,
                processData: false,
                contentType: false,
                type: "POST",

                success: function (data) {
                  console.log(data);
                  swal("Mensaje del Sistema", data, "success");
                  // delete this.elementos;
                  // location.href="Traslados.php"
                  //$("#tblDetallePedido tbody").html("");
                  // $("#txtIgvPed").val("");
                  // $("#txtTotalPed").val("");
                  // $("#txtSubTotalPed").val("");
                  // // OcultarForm();
                  //   $("#VerFormPed").hide(); // Mostramos el formulario
                  // $("#btnNuevoPedido").show();
                  Limpiar();
                  VistaDevolucion()
                  $("#VerFormPed").hide(); // Mostramos el formulario
                  $("#VerListado").show();
                  $("#btnNuevoPedido_nuevo").show();


                  // $("#txtCliente").val("");
                  //   ListadoTraslados();
                  // GetPrimerCliente();
                },
              });
            } else {
              bootbox.alert("Debe escribir una observacion");
            }
          } else {
            bootbox.alert("Debe agregar Productos para Realizar el traslado");
          }
        } else {
          bootbox.alert("Debe elejir un motivo de traslado");
        }
      } else {
        bootbox.alert("Debe agregar una fecha de devolucion");
      }
    } else {
      bootbox.alert("Debe seleccionar un motivo de devolucion");
    }
  }

  function Limpiar() {
    $("#cboDevolucionMotivo").val("");
    $("#motivo_de_devolucion").val("");
    $("#fecha_devolucion").val("");
    $("#tblDetallePedidoDevolucion tbody").html("");

    elementos.length = 0;
  }
}

function CargarDetalleDevolucion(iddevolucion) {
    //$('th:nth-child(2)').hide();
    //$('th:nth-child(3)').hide();
    $("table#tblDetallePedidoVer th:nth-child(4)").hide();
    $("table#tblDetallePedidoVer th:nth-child(8)").hide();
  
    $("table#tblDetallePedidoDevolucionVer th:nth-child(4)").hide();
    $("table#tblDetallePedidoDevolucionVer th:nth-child(8)").hide();
  
    $.post(
      "./ajax/DevolucionAjax.php?op=GetDetalleDevolucion",
      {
        idtraslado: iddevolucion,
      },
      function (r) {
        console.log(r)
        $("table#tblDetallePedidoDevolucionVer tbody").html(r);
        $("table#tblDetallePedidoDevolucionVer tbody").html(r);
      }
    );
  }

function verDetallesDevoluciones(val){

    $('#verdetallesDevoluciones').show( )

    var devolucion=JSON.parse(val.replace(/\+/g,'"'))

    
    console.log(devolucion)
    $("#cboDevolucionMotivoDetalle").val(devolucion.iddevolucion_motivo);
    $("#motivo_de_devolucion_detalle").val(devolucion.observacion);
    $("#datepickerDevolucionDetalle").val(devolucion.fecha);

    $("#UsuarioDetalle").val(devolucion.usuario);

    $("#FechaRegistroDetalle").val(devolucion.fecha_registro);
   

    $('#VerListado').hide()
    $('#btnNuevoPedido_nuevo').hide()
    CargarDetalleDevolucion(devolucion.iddevolucion)


}
function ConsultarDetallesDevolucion() {
  $("table#tblDetallePedidoDevolucion tbody").html("");
  var data = JSON.parse(JSON.stringify(elementos));

  for (var pos in data) {
    $("table#tblDetallePedidoDevolucion").append(
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
        "'  onchange='guardarCantidadDevolucion(" +
        pos +
        ")' /></td><td><button type='button' onclick='eliminarDetalleDevolucion(" +
        pos +
        ")' class='btn btn-danger'><i class='fa fa-remove' ></i> </button></td></tr>"
    );
  }
}

function guardarCantidadDevolucion(pos) {
  var cantidadTrasladar = document.getElementsByName("cantidadTrasladar");

  elementos[pos][6] = cantidadTrasladar[pos].value;
}

function eliminarDetalleDevolucion(pos) {
  pos > -1 && elementos.splice(parseInt(pos), 1);

  ConsultarDetalles();
  ConsultarDetallesDevolucion();
}

function AgregarPedCarritoDevolucion(
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

    ConsultarDetallesDevolucion();
  } else {
    bootbox.alert("No se puede agregar al detalle. No tiene stock");
  }
}
