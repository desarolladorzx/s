$(document).on("ready", init);

// var objinit = new init();
var bandera = 1;
var detalleIngresos = new Array();
var detalleTraerCantidad = new Array();
elementos = new Array();
var email = "";
function ListadoCorreccionStock() {
  var tabla = $("#tblCorreccion_stock")
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
        url: "./ajax/Correccion_stockAjax.php?op=ListTipoCorreccion_stock",
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
function AgregarPedCarritoCorreccion_stock(
  idarticulo,
  sucursal,
  stock_actual,
  Articulo,
  idsucursal
) {
  if (stock_actual > 0) {
    var data = JSON.parse(JSON.stringify(elementos));
    var detalles = new Array(
      idarticulo,
      sucursal,
      stock_actual,
      Articulo,
      "añadir",
      "",
      "",
      new Date().toISOString().slice(0, 10),
      idsucursal
    );
    // COMPRUBA SI HAY PRODUCTOS AGREGADOS - SI NO, NO BUSCA NADA

    if (data.length >= 1) {
      let rptaSearch = data.find(
        (element) => element[0] == idarticulo && element[1] == idsucursal
      );

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
function cargarBotones(estado, id) {
  $.getJSON(
    "./ajax/Correccion_stockAjax.php?op=cargarBotones",
    {
      idcorreccion_stock: estado,
    },
    function (r) {
      let idrol = r;

      let htmlButtonConfirmar = "";

      console.log(estado, idrol);
      if (estado == "ESPERA") {
        if (idrol == 5 || idrol == 2) {
          htmlButtonConfirmar = `
      &nbsp
      <button class='btn btn-warning' data-toggle='tooltip' 
      type='button'
      onclick='cambiarEstadoConformidad(${id})' title='Confirmar Correccion'>
        <i class='glyphicon glyphicon-adjust'></i>
        Confirmar Correccion

      </button>
      &nbsp
      <button class='btn btn-danger' data-toggle='tooltip' onclick='anularCorreccion(${id})'
      type='button'
      title='Anular Correccion'>
        <i class='glyphicon glyphicon-trash'></i>
        Anular Correccion
      </button>
    `;
        }
      }

      let htmlAprobacion = "";

      if (estado == "CONFIRMADO") {
        if (idrol == 4 || idrol == 2) {
          htmlAprobacion = `
      &nbsp
      <button class='btn btn-info' data-toggle='tooltip'
      type='button'
       onclick='cambiarEstadoAprobacion(${id})' title='Aprobar Correccion'>
        <i class='glyphicon glyphicon-upload'></i>
        AprobarCorreccion
      </button>
      &nbsp
      <button class='btn btn-danger' 
      type='button'
      data-toggle='tooltip' onclick='desaprobarCorreccion(${id})' title='Desaprobar Correccion'>
        <i class='glyphicon glyphicon-trash'></i>
        Desaprobar Correccion
      </button>
    `;
        }
      }

      $(".container_butones_estado").append(htmlAprobacion);
      $(".container_butones_estado").append(htmlButtonConfirmar);

      console.log(htmlButtonConfirmar);
    }
  );
}


function CargarImagenesCorreccionStock(idcorreccion_stock){
  $.post(
    "./ajax/Correccion_stockAjax.php?op=CargarImagenesCorreccionStock",
    {
      idcorreccion_stock: idcorreccion_stock,
    },
    function (r) {
      console.log(r);
      if (r != "") {
        $("#detalleImagenesCorreccionStock").html(r);
      } else {
        $("#detalleImagenesCorreccionStock").html("Sin datos que mostrar...");
      }
    }
    )
}
function verDetallesCorreccion_stock(val) {
  $.getJSON(
    "./ajax/Correccion_stockAjax.php?op=TraerDatos",
    {
      idcorreccion_stock: val,
    },
    function (r) {
      correccion_stock = r;

      console.log(correccion_stock);

      $("#info_fecha_registro").val(correccion_stock.fecha_ingreso);
      $("#info_fecha_conformidad").val(correccion_stock.fecha_conformidad);
      $("#info_fecha_aprobacion").val(correccion_stock.fecha_aprobacion);

      $("#info_empleado_registro").val(correccion_stock.empleado_creacion);
      $("#info_empleado_confirmacion").val(
        correccion_stock.empleado_conformidad
      );
      $("#info_empleado_aprobacion").val(correccion_stock.empleado_aprobacion);

      $("#container_descripcion_recepcion").show();
      $("#info_descripcion_correccion").val(correccion_stock.descripcion);

      $("#VerFormCorreccion_stockDetalles").show();

      $("#info_estado_correccion").val(
        correccion_stock.correccion_stock_estado
      );

      $("#info_codigo").val(
        correccion_stock.codigo_serie
      );


      $("#container_descripcion_desapruebo").hide();

      if (correccion_stock.correccion_stock_estado == "DESAPROBADO") {
        $("#info_descripcion_desaprobado").val(
          correccion_stock.motivo_desaprobado
        );

        $("#container_descripcion_desapruebo").show();
      }

      if (correccion_stock.correccion_stock_estado == "CONFORMIDAD CANCELADA") {
        $("#info_descripcion_rechaso").val(
          correccion_stock.motivo_cancelado_conformidad
        );

        $("#container_descripcion_correccion").show();
      }

CargarImagenesCorreccionStock(correccion_stock.idcorreccion_stock)
      
      $(".btn_guardar_correccion_stock").hide();
      cargarBotones(
        correccion_stock.correccion_stock_estado,
        correccion_stock.idcorreccion_stock
      );
      CargarDetalleCorreccion_stock(correccion_stock.idcorreccion_stock);

      $("#body_Correccion_stock").hide();
    }
  );
}
let dataCorreccion_stock;

function desaprobarCorreccion(idcorreccion_stock) {
  var result = window.confirm("¿Deseas confirmar la correccion de stock?");
  if (result) {
    var descripcion_desaprobado = prompt(
      "Ingrese el motivo por el cual Desea Desaprobar la Correccion"
    );

    if (descripcion_desaprobado.length > 20) {
      $.post(
        "./ajax/Correccion_stockAjax.php?op=desaprobarCorreccion",
        {
          idcorreccion_stock: idcorreccion_stock,
          descripcion_desaprobado: descripcion_desaprobado,
        },
        function (r) {
          $("#body_Correccion_stock").show();
          swal("Mensaje del Sistema", r, "success");

          $(".container_butones_estado").html("");

          $("#VerFormCorreccion_stockDetalles").hide();
          ListadoCorreccionStock();
          $("#tblDetallePedidoStock_Elementos td").html("");
        }
      );
    } else {
      alert("el motivo debe tener mas caracteres ");
    }
  }
}

function anularCorreccion(idcorreccion_stock) {
  var result = window.confirm("¿Deseas confirmar la correccion de stock?");
  if (result) {
    var descripcion_anulado = prompt(
      "Ingrese el motivo por el cual Desea Anular la Correccion"
    );

    if (descripcion_anulado.length > 20) {
      $.post(
        "./ajax/Correccion_stockAjax.php?op=anularCorreccion",
        {
          idcorreccion_stock: idcorreccion_stock,
          descripcion_anulado: descripcion_anulado,
        },
        function (r) {
          $("#body_Correccion_stock").show();
          $(".container_butones_estado").html("");

          $("#VerFormCorreccion_stockDetalles").hide();
          ListadoCorreccionStock();
          $("#tblDetallePedidoStock_Elementos td").html("");
          swal("Mensaje del Sistema", r, "success");
        }
      );
    } else {
      alert("el motivo debe tener mas caracteres");
    }
  } else {
  }
}

function cambiarEstadoConformidad(idcorreccion_stock) {
  var result = window.confirm("¿Deseas confirmar la correccion de stock?");
  if (result) {
    $.post(
      "./ajax/Correccion_stockAjax.php?op=cambiarEstadoConformidad",
      {
        idcorreccion_stock: idcorreccion_stock,
      },
      function (r) {
        $(".container_butones_estado").html("");

        $("#body_Correccion_stock").show();

        $("#VerFormCorreccion_stockDetalles").hide();
        ListadoCorreccionStock();

        $("#tblDetallePedidoStock_Elementos td").html("");

        swal("Mensaje del Sistema", r, "success");
      }
    );
  } else {
  }
}
function cambiarEstadoAprobacion(idcorreccion_stock) {
  var result = window.confirm("¿Deseas Aprobar  la correccion de stock?");
  if (result) {
    $.post(
      "./ajax/Correccion_stockAjax.php?op=cambiarEstadoAprobacion",
      {
        idcorreccion_stock: idcorreccion_stock,
      },
      function (r) {
        swal("Mensaje del Sistema", r, "success");
        $("#tblDetallePedidoStock_Elementos td").html("");

        $(".container_butones_estado").html("");

        $("#body_Correccion_stock").show();

        $("#VerFormCorreccion_stockDetalles").hide();
        ListadoCorreccionStock();
      }
    );
  }
}

var arrayDatosRecibidos = [];

var idtrasladojs;
function CargarDetalleCorreccion_stock(idcorreccion_stock) {
  //$('th:nth-child(2)').hide();
  //$('th:nth-child(3)').hide();

  console.log(idcorreccion_stock);

  idtrasladojs = idcorreccion_stock;

  $("table#tblDetallePedidoVer th:nth-child(4)").hide();
  $("table#tblDetallePedidoVer th:nth-child(8)").hide();

  $("table#tblDetallePedidoCorrreccion_stockRecibido th:nth-child(4)").hide();
  $("table#tblDetallePedidoCorrreccion_stockRecibido th:nth-child(8)").hide();

  $.post(
    "./ajax/Correccion_stockAjax.php?op=GetDetalleCorreccion_stock",
    {
      idcorreccion_stock: idcorreccion_stock,
    },
    function (r) {
      // console.log(r);
      arrayDatosRecibidos = JSON.parse(r);

      console.log(arrayDatosRecibidos);

      for (var pos in arrayDatosRecibidos) {
        var sucursal =
          arrayDatosRecibidos[pos].idsucursal == "1" ? "AREQUIPA" : "LIMA";

        var clasetD = "";

        if (arrayDatosRecibidos[pos].tipo == "reducir") {
          clasetD = "style='opacity:0'";
        }
        $("table#tblDetallePedidoStock_Elementos").append(
          "<tr><td>" +
            arrayDatosRecibidos[pos].Articulo +
            "</td><td>" +
            sucursal +
            "</td><td>" +
            arrayDatosRecibidos[pos].tipo +
            "</td><td> " +
            arrayDatosRecibidos[pos].Cantidad +
            "</td ><td " +
            clasetD +
            " > " +
            arrayDatosRecibidos[pos].Precio_compra +
            "</td><td " +
            clasetD +
            " >" +
            arrayDatosRecibidos[pos].Fecha_vencimiento +
            "</td>" +
            "</tr>"
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
  $("table#tblDetallePedidoCorrreccion_stockRecibido tbody").html("");

  var data = JSON.parse(JSON.stringify(elementos));

  for (var pos in data) {
    $("table#tblDetallePedidoCorrreccion_stockRecibido").append(
      "<tr><td>" +
        data[pos][3] +
        " <input class='form-control' type='hidden' name='txtIdArticulo' id='txtIdArticulo[]' value='" +
        data[pos][0] +
        "' /></td><td>" +
        data[pos][1] +
        "</td><td> " +
        data[pos][2] +
        "</td><td> " +
        `<select name="tipoCorreccion_stock" id="tipoCorreccion_stock[]"
        onchange='guardartipoCorreccion_stock(${pos},"${data[pos][4]}")'
        class='form-control' value='${data[pos][4]}'>
        <option value="añadir">Añadir</option>
        <option value="reducir">Reducir</option>
    </select>` +
        "</td><td><input class='form-control' type='number' name='cantidadCorreccionStock' required id='cantidadCorreccionStock[]' min='1' max='" +
        data[pos][2] +
        "' value='" +
        data[pos][5] +
        "'  onchange='guardarcantidadCorreccionStock(" +
        pos +
        ")' /></td>  " +
        "<td style='display:flex;justify-content:center;align-items:center'> <span class='label label-primary pull-right bg-green' style='margin:0 5px'> S/ </span>  <input class='form-control' type='number' name='cantidadPrecioCompra' required id='cantidadPrecioCompra[]' value='" +
        data[pos][6] +
        "'  onchange='guardarcantidadPrecioCompra(" +
        pos +
        ")' /></td>" +
        "<td>  <input class='form-control' type='date' required name='fechaVencimientoProducto' id='fechaVencimientoProducto[]' value='" +
        data[pos][7] +
        "'  onchange='guardarfechaVencimientoProducto(" +
        pos +
        ")' /></td>" +
        "<td><button type='button' onclick='eliminarDetalleTraslado(" +
        pos +
        ")' class='btn btn-danger'><i class='fa fa-remove' ></i> </button></td></tr>"
    );
  }
  //   calcularIgvPed();
  //   calcularSubTotalPed();
  //   calcularTotalPed();
}

function guardarfechaVencimientoProducto(pos, valor) {
  var fechaVencimientoProducto = document.getElementsByName(
    "fechaVencimientoProducto"
  );

  elementos[pos][7] = fechaVencimientoProducto[pos].value;
}

function eliminarDetalleTraslado(pos) {
  pos > -1 && elementos.splice(parseInt(pos), 1);
  ConsultarDetalles();
  ConsultarDetallesTraslado();
}

function guardartipoCorreccion_stock(pos, valor) {
  var cantidadCorreccionStock = document.getElementsByName(
    "tipoCorreccion_stock"
  );

  elementos[pos][4] = cantidadCorreccionStock[pos].value;

  var cantidadPrecioCompra = document.getElementsByName("cantidadPrecioCompra");
  var fechaVencimientoProducto = document.getElementsByName(
    "fechaVencimientoProducto"
  );

  if (elementos[pos][4] == "reducir") {
    cantidadPrecioCompra[pos].disabled = true;
    fechaVencimientoProducto[pos].disabled = true;

    cantidadPrecioCompra[pos].value = 0;
    elementos[pos][6] = 0;
  } else {
    cantidadPrecioCompra[pos].disabled = false;
    fechaVencimientoProducto[pos].disabled = false;

    cantidadPrecioCompra[pos].value = elementos[pos][6];
  }
}

function guardarcantidadCorreccionStock(pos, valor) {
  var cantidadCorreccionStock = document.getElementsByName(
    "cantidadCorreccionStock"
  );

  elementos[pos][5] = cantidadCorreccionStock[pos].value;
}

function guardarcantidadPrecioCompra(pos, valor) {
  var cantidadCorreccionStock = document.getElementsByName(
    "cantidadPrecioCompra"
  );

  elementos[pos][6] = cantidadCorreccionStock[pos].value;
}


function getCodigoCorreccion_stock(){

  $.getJSON(
    "./ajax/Correccion_stockAjax.php?op=TraerUltimoCodigo",
    function (r) {
      
      $('#codigo_correccion').val(r)
    })
}

function init() {

  $("#btnNuevoCorrecion_stock").click(VerFormPedido_Nuevo);
  function VerFormPedido_Nuevo() {
    $("#VerFormPed").show(); // Mostramos el formulario
    $("#btnNuevoCorrecion_stock").hide(); // ocultamos el boton nuevo
    $("#btnGenerarVenta").hide();
    $("#VerListado").hide(); // ocultamos el listado
    getCodigoCorreccion_stock();
  }



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

    dataCorreccion_stock.sucursal_destino_id
      ? formData.append(
          "sucursal_destino_id",
          dataCorreccion_stock.sucursal_destino_id
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
          url: "./ajax/Correccion_stockAjax.php?op=modificarEstadoTraslado",
          data: formData,
          processData: false,
          contentType: false,
          type: "POST",

          success: function (data) {
            console.log(data);
            swal("Mensaje del Sistema", data, "success");
            location.href = "Correccion_stock.php";
          },
        });
      }
    } else {
      if (estado !== "SALIDA") {
        $.ajax({
          url: "./ajax/Correccion_stockAjax.php?op=modificarEstadoTraslado",
          data: formData,
          processData: false,
          contentType: false,
          type: "POST",

          success: function (data) {
            console.log(data);

            // delete this.elementos;
            location.href = "Correccion_stock.php";
            //$("#tblDetallePedido tbody").html("");
            // $("#txtIgvPed").val("");
            // $("#txtTotalPed").val("");
            // $("#txtSubTotalPed").val("");
            // // OcultarForm();
            // $("#VerFormPed").hide(); // Mostramos el formulario
            // $("#btnNuevoPedido").show();
            // Limpiar();

            // $("#txtCliente").val("");
            // ListadoCorreccionStock();
            // GetPrimerCliente();
          },
        });
      }
    }
  });

  $("#VerFormCorreccion_stockDetalles").hide();

  $("#btnBuscarDetCorreccion_stock").click(AbrirModalDeCorreccion_stock);

  $("form#frmCorreccion_stock").submit(GuardarCorreccion_stock);

  ListadoCorreccionStock();

  function Limpiar() {
    $("#motivo_de_Correccion_stock").val("");
    $("#tblDetallePedidoCorrreccion_stockRecibido tbody").html("");

    elementos.length = 0;
  }

  function GuardarCorreccion_stock(e) {
    e.preventDefault();

    var formData = new FormData();

    var detalle = JSON.parse(JSON.stringify(elementos));

    for (var i = 0; i < detalle.length; i++) {
      formData.append("detalle[]", detalle[i]);
    }

    $.each($("input[type='file']#archivos_correccion_stock")[0].files, function (i, file) {
      //alert(file)

      formData.append("fileupload[]", file);
    });

    formData.append(
      "motivo_de_Correccion_stock",
      $("#motivo_de_Correccion_stock")
        .val()
        .replace(/[.,\/#!$%\^&\*;:{}=\-_`~()]/g, ".")
        .replace(/\n/g, " ")
        .replace(/(\r\n|\n|\r)/gm, " ")
    );

    if (elementos.length > 0) {
      if ($("#motivo_de_Correccion_stock").val() != "") {
        $(".btn_guardar_correccion_stock").prop("disabled", false);

        $.ajax({
          url: "./ajax/Correccion_stockAjax.php?op=Save",
          data: formData,
          processData: false,
          contentType: false,
          type: "POST",

          success: function (data) {
            swal("Mensaje del Sistema", data, "success");

            location.href = "Correccion_stock.php";

            $(".btn_guardar_correccion_stock").prop("disabled", true);
          },

          error: function (data) {
            console.log(data);
          },
        });
      } else {
        bootbox.alert("Debe describir el motivo de la correcion de stock");
      }
    } else {
      bootbox.alert(
        "Debe agregar Productos para realizar la correcion  de  stock"
      );
    }
  }

  function AbrirModalDeCorreccion_stock() {
    $("#modalListadoArticulosCorreccion_stock").modal("show");
    var tabla = $("#tblArticulosPedCorreccion_stock")
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
          {
            mDataProp: "9",
          },
        ],
        ajax: {
          url: "./ajax/Correccion_stockAjax.php?op=listDetIng",
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
