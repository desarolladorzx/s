$(document).on("ready", init);
var objinit = new init();
var bandera = 1;
var detalleIngresos = new Array();
var detalleTraerCantidad = new Array();
elementos = new Array();
var email = "";

function init() {
  function notificaciones() {
    let idsucursal = $("#txtIdSucursalGlobal").val();
    let tipo_usuario = $("#txtIdTipoUsuarioGlobal").val();

    Notification.requestPermission().then((resultado) => {
      var ultimoPedido;
      $.ajax({
        url: "./ajax/PedidoAjax.php?op=imformarCotizacion",
        // data: formData,
        processData: false,
        contentType: false,
        type: "GET",

        success: function (data) {
          ultimoPedido = data;
          if (localStorage.getItem("ultimoPedido")) {
            let local = JSON.parse(
              localStorage.getItem("ultimoPedido")
            ).idpedido;
            let baseD = JSON.parse(data).idpedido;
            let nuevoPedido = JSON.parse(data);

            if (baseD != local) {
              if (tipo_usuario == "Cliente" && nuevoPedido?.idsucursal == 2) {
                if ((Notification.permission = "grantFd")) {
                  const notification = new Notification(
                    "Hay una nueva cotizacion ",
                    {
                      icon: "/medicfitcen/Files/Global/logo_medicfitcen2.jpg",
                      body: "Nueva cotizacion -" + JSON.parse(data).numero,
                    }
                  );
                  notification.onclick = function () {
                    window.open("https://medicfit.grupopuma.pe/Pedido.php");
                  };
                  localStorage.setItem("ultimoPedido", ultimoPedido);
                }
              } else {
                if ((Notification.permission = "grantFd")) {
                  const notification = new Notification(
                    "Hay una nueva cotizacion ",
                    {
                      icon: "/medicfitcen/Files/Global/logo_medicfitcen2.jpg",
                      body: "Nueva cotizacion -" + JSON.parse(data).numero,
                    }
                  );
                  notification.onclick = function () {
                    window.open("https://medicfit.grupopuma.pe/Pedido.php");
                  };
                  localStorage.setItem("ultimoPedido", ultimoPedido);
                }
              }
            }
          } else {
            localStorage.setItem("ultimoPedido", ultimoPedido);
          }
        },
      }).then(() => {
        $.ajax({
          url: "./ajax/PedidoAjax.php?op=informarVenta",
          // data: formData,
          processData: false,
          contentType: false,
          type: "GET",

          success: function (data) {
            ultimoVenta = data;
            if (localStorage.getItem("ultimoVenta")) {
              let local = JSON.parse(
                localStorage.getItem("ultimoVenta")
              ).idventa;
              let baseD = JSON.parse(data).idventa;
              if (baseD != local) {
                if (tipo_usuario == "Cliente" && nuevoPedido?.idsucursal == 2) {
                  if ((Notification.permission = "grantFd")) {
                    const notification = new Notification(
                      "Se ha registrado una nueva Venta ",
                      {
                        icon: "/medicfitcen/Files/Global/logo_medicfitcen2.jpg",
                        body:
                          "Nueva Venta -" +
                          JSON.parse(data).serie_comprobante +
                          "-" +
                          JSON.parse(data).num_comprobante,
                      }
                    );
                    notification.onclick = function () {
                      window.open("https://medicfit.grupopuma.pe/Venta.php");
                    };
                    localStorage.setItem("ultimoVenta", ultimoVenta);
                  } else {
                    localStorage.setItem("ultimoVenta", ultimoVenta);
                  }
                } else {
                  if ((Notification.permission = "grantFd")) {
                    const notification = new Notification(
                      "Se ha registrado una nueva Venta ",
                      {
                        icon: "/medicfitcen/Files/Global/logo_medicfitcen2.jpg",
                        body:
                          "Nueva Venta -" +
                          JSON.parse(data).serie_comprobante +
                          "-" +
                          JSON.parse(data).num_comprobante,
                      }
                    );
                    notification.onclick = function () {
                      window.open("https://medicfit.grupopuma.pe/Venta.php");
                    };
                    localStorage.setItem("ultimoVenta", ultimoVenta);
                  } else {
                    localStorage.setItem("ultimoVenta", ultimoVenta);
                  }
                }
              }
            } else {
              localStorage.setItem("ultimoVenta", ultimoVenta);
            }
          },
        }).then(() => {
          $.ajax({
            url: "./ajax/PedidoAjax.php?op=informarVentaCancelada",
            // data: formData,
            processData: false,
            contentType: false,
            type: "GET",

            success: function (data) {
              setTimeout(() => {
                notificaciones();
                // console.log('3 segundso')
              }, 3000);
              ultimoVentaCancelada = data;
              if (localStorage.getItem("ultimoVentaCancelada")) {
                let local = JSON.parse(
                  localStorage.getItem("ultimoVentaCancelada")
                ).idpedido;
                let baseD = JSON.parse(data).idpedido;

                if (baseD != local) {
                  if (
                    tipo_usuario == "Cliente" &&
                    nuevoPedido?.idsucursal == 2
                  ) {
                    if ((Notification.permission = "grantFd")) {
                      const notification = new Notification(
                        "Se ha anulador un pedido ",
                        {
                          icon: "/medicfitcen/Files/Global/logo_medicfitcen2.jpg",
                          body:
                            "Venta Anulada -" +
                            JSON.parse(data).serie_comprobante +
                            "-" +
                            JSON.parse(data).num_comprobante,
                        }
                      );
                      notification.onclick = function () {
                        window.open(
                          "https://medicfit.grupopuma.pe/AnulacionVenta.php"
                        );
                      };
                      localStorage.setItem(
                        "ultimoVentaCancelada",
                        ultimoVentaCancelada
                      );
                    } else {
                      localStorage.setItem(
                        "ultimoVentaCancelada",
                        ultimoVentaCancelada
                      );
                    }
                  } else {
                    if ((Notification.permission = "grantFd")) {
                      const notification = new Notification(
                        "Se ha anulador un pedido ",
                        {
                          icon: "/medicfitcen/Files/Global/logo_medicfitcen2.jpg",
                          body:
                            "Venta Anulada -" +
                            JSON.parse(data).serie_comprobante +
                            "-" +
                            JSON.parse(data).num_comprobante,
                        }
                      );
                      notification.onclick = function () {
                        window.open(
                          "https://medicfit.grupopuma.pe/AnulacionVenta.php"
                        );
                      };
                      localStorage.setItem(
                        "ultimoVentaCancelada",
                        ultimoVentaCancelada
                      );
                    } else {
                      localStorage.setItem(
                        "ultimoVentaCancelada",
                        ultimoVentaCancelada
                      );
                    }
                  }
                }
              } else {
                localStorage.setItem(
                  "ultimoVentaCancelada",
                  ultimoVentaCancelada
                );
              }
            },
          });
        });
      });
    });
  }
  notificaciones();
  var total = 0.0;
  //GetNextNumero();
  //GetTotal(19);

  $("#tblVentas").dataTable({
    dom: "Bfrtip",
    buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
  });

  var tablaArtPed = $("#tblArticulosPed").dataTable({
    pageLength: 30,
    //"aLengthMenu": [2, 4]
  });

  ListadoPedidos();
  ListadoPedidos2();
  GetImpuesto();
  GetPrimerCliente();

  $("#txtRutaImgVoucher").hide();
  $("#VerForm").hide();
  $("#VerFormVentaPed").hide();

  $("#ContainerbuttonAgregarImagenAlmacen").hide();

  $('#cancelado_por_container').hide();
  // $("#btnAgregar").click(AgregarDetallePedPedido)
  // $("#cboTipoComprobante").change(VerNumSerie);
  $("#btnBuscarCliente").click(AbrirModalCliente);
  $("#btnBuscarDetIng").click(AbrirModalDetPed);
  $("#btnEnviarCorreo").click(EnviarCorreo);
  //$("#btnNuevoVent").click(VerForm);

  $("#btn_todos_eliminar_imagen").hide();

  $("#btn_todos_eliminar_imagen").click(function () {
    $("#image-preview-container").html("");
    $("#image-preview-container-almacen").html("");
    $("input[type='file']").val("");
  });
  $("#btnNuevoPedido_nuevo").click(VerFormPedido_Nuevo);
  $("form#frmPedidos").submit(GuardarPedido);

  $("#btnGenerarVenta").click(GenerarVenta);

  $("#btnAgregarCliente").click(function (e) {
    e.preventDefault();

    var opt = $("input[type=radio]:checked");
    $("#txtIdCliente").val(opt.val());
    $("#txtCliente").val(opt.attr("data-nombre"));
    email = opt.attr("data-email");

    $("#modalListadoCliente").modal("hide");

    /* DESTRUIR TABLA DE CLIENTES AL MOMENTO DE ELEGIR CLIENTE*/
    if ($.fn.DataTable.isDataTable("#tblClientees")) {
      $("#tblClientees").DataTable().destroy();
    }
    $("#tblClientees tbody").empty();
    $("#modalListadoCliente").modal("hide");
  });

  $("#btnAgregarArtPed").click(function (e) {
    e.preventDefault();

    var opt = tablaArtPed.$("input[name='optDetIngBusqueda[]']:checked", {
      page: "all",
    });

    opt.each(function () {
      AgregarDetallePed(
        $(this).val(),
        $(this).attr("data-nombre"),
        $(this).attr("data-precio-venta"),
        "1",
        "0.0",
        $(this).attr("data-stock-actual"),
        $(this).attr("data-codigo"),
        $(this).attr("data-serie")
      );
    });

    $("#modalListadoArticulosPed").modal("hide");
  });

  function FormVenta(total, idpedido) {
    $("#VerFormVentaPed").show();
    $("#btnNuevo").hide();
    $("#VerForm").hide();
    $("#VerListado").hide();
    $("#txtTotalVent").val(total);
    $("#txtIdPedido").val(idpedido);
    $("#lblTitlePed").html("Venta");
    //Ver();
  }
  $("#save-guardar-almacen").click(function () {

    var formData = new FormData();

    formData.append('idpedido', $("#txtIdPedido").val());
    formData.append('idcliente',$("#txtIdCliente").val());


    $.each($("#inputFileAlmacen")[0].files, function (i, file) {
      formData.append("fileupload[]", file);
    });

    $.ajax({
      url: "./ajax/PedidoAjax.php?op=SaveImagesEmpaquetado",
      data: formData,
      processData: false,
      contentType: false,
      type: "POST",

      success: function (data) {
        swal("Mensaje del Sistema", data, "success");
        // delete this.elementos;
        window.location.href = "Venta.php"
        //$("#tblDetallePedido tbody").html("");
        // $("#txtIgvPed").val("");
        // $("#txtTotalPed").val("");
        // $("#txtSubTotalPed").val("");
        // OcultarForm();
        // $("#VerFormPed").hide(); // Mostramos el formulario
        // $("#btnNuevoPedido_nuevo").show();
        // Limpiar();
        // $("#txtCliente").val("");
        // ListadoVenta();
        // GetPrimerCliente();
      },
    });
  });
  $("#inputFileAlmacen").change(function () {
    $("#btn_todos_eliminar_imagen").show();
    $("#image-preview-container-almacen").html("");
    const files = this.files;

    for (let i = 0; i < files.length; i++) {
      const reader = new FileReader();

      reader.addEventListener("load", function () {
        const image = new Image();
        image.src = reader.result;

        const imagePreview = document.createElement("div");
        // imagePreview.style.add('width:100px');

        image.classList.add("my-images_preview");
        image.style.width = "30px";
        // image.style.minWidth = '300px';
        // image.style.height = '300px';
        image.style.objectFit = "fixed";
        imagePreview.appendChild(image);

        $("#image-preview-container-almacen").append(imagePreview);
      });

      reader.readAsDataURL(files[i]);
    }
  });


  $("#imagenVoucher").on("change", function (e) {
    $("#btn_todos_eliminar_imagen").show();
    $("#image-preview-container").html("");
    const files = this.files;

    for (let i = 0; i < files.length; i++) {
      const reader = new FileReader();

      reader.addEventListener("load", function () {
        const image = new Image();
        image.src = reader.result;

        const imagePreview = document.createElement("div");
        // imagePreview.style.add('width:100px');

        image.classList.add("my-images_preview");
        image.style.width = "30px";
        // image.style.minWidth = '300px';
        // image.style.height = '300px';
        image.style.objectFit = "fixed";
        imagePreview.appendChild(image);

        $("#image-preview-container").append(imagePreview);
      });

      reader.readAsDataURL(files[i]);
    }
  });

  function GuardarPedido(e) {
    e.preventDefault();

    if ($("#txtIdCliente").val() != "") {
      if (elementos.length > 0) {
        /*
                var file_name=$('#imagenVoucher').val();
                var index_dot=file_name.lastIndexOf(".")+1;
                var ext=file_name.substr(index_dot);

                if (file_name == "") {
                    var archivo = "";
                    var fileName = "";
                }else{
                    var archivo = $('#imagenVoucher')[0].files[0];
                    var fileName = file_name;
                }
                */

        var formData = new FormData();

        var detalle = JSON.parse(consultar());

        // alert(detalle);

        $.each($("input[type='file']")[0].files, function (i, file) {
          //alert(file)

          formData.append("fileupload[]", file);
        });

        formData.append("idUsuario", $("#txtIdUsuario").val());
        formData.append("idCliente", $("#txtIdCliente").val());
        formData.append("idSucursal", $("#txtIdSucursal").val());
        formData.append("tipo_pedido", $("#cboTipoPedido").val());

        formData.append("metodo_pago", $("#cboMetodoPago").val());
        formData.append("agencia_envio", $("#cboAgenEnvio").val());
        formData.append("tipo_promocion", $("#cboTipoPromocion").val());

        formData.append("numero", $("#txtNumeroPed").val());
        formData.append("modo_pago", $("#cboModPago").val());

        formData.append("tipo_entrega", $("#cboModTipo_Entrega").val());
        formData.append(
          "observaciones",
          $("#textObservaciones")
            .val()
            .replace(/[.,\/#!$%\^&\*;:{}=\-_`~()]/g, ".")
        );

        for (var i = 0; i < detalle.length; i++) {
          formData.append("detalle[]", detalle[i]);
        }
        //formData.append('detalle', detalle);
        //formData.append('ext', ext);

        $.ajax({
          url: "./ajax/PedidoAjax.php?op=Save",
          data: formData,
          processData: false,
          contentType: false,
          type: "POST",

          success: function (data) {
            swal("Mensaje del Sistema", data, "success");
            // delete this.elementos;

            //$("#tblDetallePedido tbody").html("");
            $("#txtIgvPed").val("");
            $("#txtTotalPed").val("");
            $("#txtSubTotalPed").val("");
            OcultarForm();
            $("#VerFormPed").hide(); // Mostramos el formulario
            $("#btnNuevoPedido_nuevo").show();
            Limpiar();
            $("#txtCliente").val("");
            ListadoVenta();
            GetPrimerCliente();
          },
        });

        // alert(fileName);
        /*
                var detalle =  JSON.parse(consultar());

                var data = { 
                    idUsuario : $("#txtIdUsuario").val(),
                    idCliente : $("#txtIdCliente").val(),
                    idSucursal : $("#txtIdSucursal").val(),
                    tipo_pedido : $("#cboTipoPedido").val(),
                    
                    tipo_promocion : $("#cboTipoPromocion").val(), // Promociones de ventas
                    metodo_pago : $("#cboMetodoPago").val(), // Cuenta donde es abonada
                    agencia_envio : $("#cboAgenEnvio").val(), // Transporte
    
                    numero : $("#txtNumeroPed").val(),

                    //fileupload : archivo,
                    //ext : ext,

                    detalle : detalle
                };
                
                $.post("./ajax/PedidoAjax.php?op=Save", data, function(r){
                           swal("Mensaje del Sistema", r, "success");
                           // delete this.elementos;
   
                            //$("#tblDetallePedido tbody").html("");
                            $("#txtIgvPed").val("");
                            $("#txtTotalPed").val("");
                            $("#txtSubTotalPed").val("");
                            OcultarForm();
                            $("#VerFormPed").hide();// Mostramos el formulario
                            $("#btnNuevoPedido").show();
                            Limpiar();
                            $("#txtCliente").val("");
                            ListadoVenta();
                            GetPrimerCliente();

                });

                */
      } else {
        bootbox.alert(
          "Debe agregar Productos al detalle para registrar el Pedido"
        );
      }
    } else {
      bootbox.alert("Debe elegir un Cliente para registrar el Pedido");
    }
  }

  function EnviarCorreo() {
    bootbox.prompt({
      title: "Ingrese el correo para enviar el detalle de la compra",
      value: email,
      callback: function (result) {
        if (result !== null) {
          $.post(
            "./ajax/VentaAjax.php?op=EnviarCorreo",
            {
              result: result,
              idPedido: $("#txtIdPedido").val(),
            },
            function (r) {
              bootbox.alert(r);
            }
          );
        }
      },
    });
  }

  function EnviarCorreo() {
    bootbox.prompt({
      title: "Ingrese el correo para enviar el detalle de la compra",
      value: email,
      callback: function (result) {
        if (result !== null) {
          $.post(
            "./ajax/VentaAjax.php?op=EnviarCorreo",
            {
              result: result,
              idPedido: $("#txtIdPedido").val(),
            },
            function (r) {
              bootbox.alert(r);
            }
          );
        }
      },
    });
  }

  function GenerarVenta(e) {
    e.preventDefault();

    if ($("#txtIdCliente").val() != "") {
      if (elementos.length > 0) {
        var detalle = JSON.parse(consultar());

        var data = {
          idUsuario: $("#txtIdUsuario").val(),
          idCliente: $("#txtIdCliente").val(),
          idSucursal: $("#txtIdSucursal").val(),
          tipo_pedido: "Pedido",
          //numero : $("#txtNumeroPed").val(),
          metodo_pago: $("#cboMetodoPago").val(), // Cuenta donde es abonada
          agencia_envio: $("#cboAgenEnvio").val(), // Transporte
          tipo_promocion: $("#cboTipoPromocion").val(), // Promociones de ventas
          numero: $("#txtNumeroPed").val(),
          imagen: $("#txtRutaImgVoucher").val(),
          detalle: detalle,
        };

        $.post("./ajax/PedidoAjax.php?op=Save", data, function (r) {
          /*
                    swal("Mensaje del Sistema", r, "success");
                    //delete this.elementos;
                    //$("#tblDetallePedido tbody").html("");
                    $("#txtIgvPed").val("");
                    $("#txtTotalPed").val("");
                    $("#txtSubTotalPed").val("");
                    //Limpiar(); //Se añadio limpiar
                    ListadoPedidos();
                    ListadoPedidos2();
                    $.getJSON("./ajax/PedidoAjax.php?op=GetIdPedido", function (r) {
                        if (r) {
                            GetTotal(r.idpedido);
                            AgregatStockCant(r.idpedido);
                            $("#VerFormVentaPed").show();
                            $("#btnNuevo").hide();
                            $("#VerForm").hide();
                            $("#VerListado").hide();
                            $("#lblTitlePed").html("Venta");
                            $("#txtTotalVent").val(total);
                            var cli = $("#txtCliente").val();
                            $("#txtClienteVent").val(cli);
                            $("#txtClienteDni").val(r.num_documento);
                            $("#hdn_idcliente").val($("#txtIdCliente").val());
                            $("#txtIdPedido").val(r.idpedido);
                            //$("#VerVentaDetallePedido").hide();
                            $("#btnEnviarCorreo").hide();
                            ComboTipoDoc();

                            $('table#tblDetallePedidoVer th:nth-child(4)').hide();
                            $('table#tblDetallePedidoVer th:nth-child(8)').hide();

                            $('table#tblDetallePedido th:nth-child(4)').hide();
                            $('table#tblDetallePedido th:nth-child(8)').hide();

                            $.post("./ajax/PedidoAjax.php?op=GetDetallePedido", {
                                idPedido: r.idpedido
                            }, function (r) {
                                $("table#tblDetallePedidoVer tbody").html(r);
                                $("table#tblDetallePedido tbody").html(r);
                            })
                        }

                        Limpiar();

                    });
                    */
        });
      } else {
        bootbox.alert("Debe agregar articulos al detalle js GenerarVenta ");
      }
    } else {
      bootbox.alert("Debe elegir un cliente js GenerarVenta ");
    }
  }
  // Limpia los campos de nueva cotizacion
  function Limpiar() {
    $("#cboModPago").val("");
    $("#textObservaciones").val("");

    $("#txtIdCliente").val("");
    $("#cboTipoPedido").val("Pedido");
    $("#txtNumeroPed").val("");
    $("#cboTipoComprobante").val("--Seleccione Comprobante--");
    $("#cboMetodoPago").val("");
    $("#cboAgenEnvio").val("");
    $("#cboTipoPromocion").val("");
    $("#imagenVoucher").val("");
    //$("#txtRutaImgVoucher").val("");
    elementos.length = 0;
    $("#tblDetallePedido tbody").html("");
    //GetNextNumero();
    //getCodigoAleatorio();
  }

  function GetTotal(idPedido) {
    $.getJSON(
      "./ajax/PedidoAjax.php?op=GetTotal",
      {
        idPedido: idPedido,
      },
      function (r) {
        if (r) {
          total = r.Total;
          $("#txtTotalVent").val(total);

          var igvPed =
            (total * parseInt($("#txtImpuesto").val())) /
            (100 + parseInt($("#txtImpuesto").val()));
          $("#txtIgvPedVer").val(Math.round(igvPed * 100) / 100);

          var subTotalPed =
            total -
            (total * parseInt($("#txtImpuesto").val())) /
              (100 + parseInt($("#txtImpuesto").val()));
          $("#txtSubTotalPedVer").val(Math.round(subTotalPed * 100) / 100);

          $("#txtTotalPedVer").val(Math.round(total * 100) / 100);
        }
      }
    );
  }

  /*
    function GetNextNumero() {
        $.getJSON("./ajax/PedidoAjax.php?op=GetNextNumero", function (r) {
            if (r) {
                $("#txtNumeroPed").val(r.numero);
            }
        });
    }
    */

  function VerFormPedido_Nuevo() {
    $("#VerFormPed").show(); // Mostramos el formulario
    $("#btnNuevoPedido_nuevo").hide(); // ocultamos el boton nuevo
    $("#btnGenerarVenta").hide();
    $("#VerListado").hide(); // ocultamos el listado
    $("#btnReporte").hide();
    getCodigoAleatorio();
  }

  function getCodigoAleatorio() {
    $.getJSON("./ajax/PedidoAjax.php?op=GetCodigoAleatorio", function (r) {
      if (r == true) {
        getCodigoAleatorio();
      } else {
        $("#txtNumeroPed").val(r);
      }
    });
  }

  function ComboTipoDoc() {
    $.get("./ajax/PedidoAjax.php?op=listTipoDoc", function (r) {
      $("#cboTipoComprobante").html(r);
    });
  }

  function GetImpuesto() {
    $.getJSON("./ajax/GlobalAjax.php?op=GetImpuesto", function (r) {
      $("#txtImpuestoPed").val(r.porcentaje_impuesto);
      $("#SubTotal").html(r.simbolo_moneda + " Sub Total:");
      $("#IGV").html(
        r.simbolo_moneda +
          " " +
          r.nombre_impuesto +
          " " +
          r.porcentaje_impuesto +
          "I%:"
      );
      $("#Total").html(r.simbolo_moneda + " Total:");

      $("#txtImpuesto").val(r.porcentaje_impuesto);
      $("#SubTotal_Ver").html(r.simbolo_moneda + " Sub Total:");
      $("#IGV_Ver").html(
        r.simbolo_moneda +
          " " +
          r.nombre_impuesto +
          " " +
          r.porcentaje_impuesto +
          "V%:"
      );
      $("#Total_Ver").html(r.simbolo_moneda + " Total:");
    });
  }

  function VerNumSerie() {
    var nombre = $("#cboTipoComprobante").val();

    $.getJSON(
      "./ajax/PedidoAjax.php?op=GetTipoDocSerieNum",
      {
        nombre: nombre,
      },
      function (r) {
        if (r) {
          $("#txtSerie").val(r.ultima_serie);
          $("#txtNumeroPed").val(r.ultimo_numero);
        }
      }
    );
  }

  function VerForm() {
    $("#VerForm").show();
    //$("#btnNuevoVent").hide();
    $("#cboTipoPedido").hide();
    $("#txtNumeroPed").hide();
    $("#inputTipoPed").hide();
    $("#inputNumero").hide();
    $("#btnRegPedido").hide();
    $("#VerListado").hide();
  }

  function OcultarForm() {
    $("#VerForm").hide();
    //$("#btnNuevoVent").show();
    $("#VerListado").show();
  }

  //Destruccion de tabla busqueda de Clientes
  $("#btnCerrarBusqueda").click(function () {
    if ($.fn.DataTable.isDataTable("#tblClientees")) {
      $("#tblClientees").DataTable().destroy();
    }
    $("#tblClientees tbody").empty();
    $("#modalListadoCliente").modal("hide");
  });

  /*
    $("#btnAgregarCliente").click(function(){
        if ( $.fn.DataTable.isDataTable('#tblClientees') ) {
            $('#tblClientees').DataTable().destroy();
        }
        $('#tblClientees tbody').empty();
        $("#modalListadoCliente").modal("hide");
    });
    */

  function AbrirModalCliente() {
    $("#modalListadoCliente").modal("show");
    $.post("./ajax/PedidoAjax.php?op=listClientes", function (r) {
      $("#Cliente").html(r);
      $("#tblClientees").dataTable({
        //"aProcessing": true,
        //"aServerSide": true,
        pageLength: 7,
        //"iDisplayLength": 7,
        //"aLengthMenu": [0,3],
        //"bDestroy": true ,
        retrieve: true,
        paging: true,
        searching: true,
        destroy: true,
      });
    });
  }

  function AbrirModalDetPed() {
    $("#modalListadoArticulosPed").modal("show");
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
          },
        ],
        ajax: {
          url: "./ajax/PedidoAjax.php?op=listDetIng",
          type: "get",
          dataType: "json",

          error: function (e) {
            console.log(e.responseText);
          },
        },
        bDestroy: true, //funcion que causa problemas en el rendimiento
      })
      .DataTable();
  }

  function AgregatStockCant(idPedido) {
    $.ajax({
      url: "./ajax/PedidoAjax.php?op=GetDetalleCantStock",
      dataType: "json",
      data: {
        idPedido: idPedido,
      },
      success: function (s) {
        for (var i = 0; i < s.length; i++) {
          AgregarDetalleCantStock(s[i][0], s[i][1], s[i][2]);
        }
        //      Ver();
      },
      error: function (e) {
        console.log(e.responseText);
      },
    });
  }

  function AgregarDetallePed(
    iddet_ing,
    nombre,
    precio_venta,
    cant,
    desc,
    stock_actual,
    codigo,
    serie
  ) {
    var detalles = new Array(
      iddet_ing,
      nombre,
      precio_venta,
      cant,
      desc,
      stock_actual,
      codigo,
      serie
    );
    elementos.push(detalles);
    ConsultarDetallesPed();
  }

  function consultar() {
    return JSON.stringify(elementos);
  }

  this.eliminar = function (pos) {
    //var pos = elementos[].indexOf( 'c' );
    console.log(pos);
    pos > -1 && elementos.splice(parseInt(pos), 1);
    console.log(elementos);
    //this.elementos.splice(pos, 1);
    //console.log(this.elementos);
  };

  this.consultar = function () {
    /*
        for(i=0;i<this.elementos.length;i++){
            for(j=0;j<this.this.elementos[i].length;j++){
                console.log("Elemento: "+this.elementos[i][j]);
            }
        }
        */
    return JSON.stringify(elementos);
  };
}

function ListadoVenta() {
  var tabla = $("#tblVentapedido")
    .dataTable({
      aProcessing: true,
      aServerSide: true,
      dom: "Bfrtip",
      buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
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
          sClass: "text-center",
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
        // {
        //   mDataProp: "9",
        // },
      ],
      ajax: {
        url: "./ajax/PedidoAjax.php?op=listTipoPedidoPedido",
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

function eliminarDetallePed(ele) {
  console.log(ele);
  objinit.eliminar(ele);
  ConsultarDetallesPed();
}

function ConsultarDetallesPed() {
  $("table#tblDetallePedido tbody").html("");
  var data = JSON.parse(objinit.consultar());

  for (var pos in data) {
    $("table#tblDetallePedido").append(
      "<tr><td>" +
        data[pos][1] +
        " <input class='form-control' type='hidden' name='txtIdDetIng' id='txtIdDetIng[]' value='" +
        data[pos][0] +
        "' /></td><td> " +
        data[pos][6] +
        "</td><td> " +
        data[pos][7] +
        "</td><td> " +
        data[pos][9] +
        "</td><td>" +
        data[pos][5] +
        "</td><td><input class='form-control' type='text' name='txtPrecioVentPed' id='txtPrecioVentPed[]' value='" +
        data[pos][2] +
        "' onchange='calcularTotalPed(" +
        pos +
        ")' /></td><td><input class='form-control' type='text' name='txtCantidaPed' id='txtCantidaPed[]'  value='" +
        data[pos][3] +
        "' onchange='calcularTotalPed(" +
        pos +
        ")' /></td><td><input class='form-control' type='text' name='txtDescuentoPed' id='txtDescuentoPed[]' value='" +
        data[pos][4] +
        "' onchange='calcularTotalPed(" +
        pos +
        ")' /></td><td><button type='button' onclick='eliminarDetallePed(" +
        pos +
        ")' class='btn btn-danger'><i class='fa fa-remove' ></i> </button></td></tr>"
    );
  }
  calcularIgvPed();
  calcularSubTotalPed();
  calcularTotalPed();
}

function calcularIgvPed() {
  var suma = 0;
  var data = JSON.parse(objinit.consultar());

  for (var pos in data) {
    suma += parseFloat(data[pos][3] * (data[pos][2] - data[pos][4]));
  }
  var igvPed =
    (suma * parseInt($("#txtImpuesto").val())) /
    (100 + parseInt($("#txtImpuesto").val()));
  $("#txtIgvPed").val(Math.round(igvPed * 100) / 100);
}

function calcularSubTotalPed() {
  var suma = 0;
  var data = JSON.parse(objinit.consultar());
  for (var pos in data) {
    suma += parseFloat(data[pos][3] * (data[pos][2] - data[pos][4]));
  }
  var subTotalPed =
    suma -
    (suma * parseInt($("#txtImpuesto").val())) /
      (100 + parseInt($("#txtImpuesto").val()));
  $("#txtSubTotalPed").val(Math.round(subTotalPed * 100) / 100);
}

function calcularTotalPed(posi) {
  if (posi != null) {
    ModificarPed(posi);
    //Modificar(posi);
  }
  var suma = 0;
  var data = JSON.parse(objinit.consultar());
  for (var pos in data) {
    suma += parseFloat(data[pos][3] * (data[pos][2] - data[pos][4]));
  }
  calcularIgvPed();
  calcularSubTotalPed();
  $("#txtTotalPed").val(Math.round(suma * 100) / 100);
}

function cargarDataPedido(
  idPedido,
  tipo_pedido,
  numero,
  cliente,
  total,
  correo,
  num_documento,
  celular,
  tipo_cliente,
  destino,
  ticket,
  aproba_venta,
  aproba_pedido,
  empleado,
  metodo_pago,
  agencia_envio,
  tipo_promocion,
  observaciones,
  modo_pago,
  ultimo,
  modificar_detalle,
  idcliente,
  estado
) {
  console.log(
    idPedido,
  tipo_pedido,
  numero,
  cliente,
  total,
  correo,
  num_documento,
  celular,
  tipo_cliente,
  destino,
  ticket,
  aproba_venta,
  aproba_pedido,
  empleado,
  metodo_pago,
  agencia_envio,
  tipo_promocion,
  observaciones,
  modo_pago,
  ultimo,
  modificar_detalle,
  idcliente,
  estado
  );


  $('.ventasFechasOcultar').hide()
  
  if(modificar_detalle==='C'){

    // console.log(idcliente);
    $('#cancelado_por_container').show();
    $('#empleado_anulador').val(idcliente)
  }
  // el numero crea el espacio en la celda - , celular,num_documento, celular, destino, date, agencia_envio
  bandera = 2;
  
  $("#txtIdCliente").val(idcliente)
  $("#VerForm").show();
  //$("#btnNuevoVent").hide();
  $("#VerListado").hide();
  $("#txtIdPedido").val(idPedido);
  $("#txtCliente").hide();
  $("#cboTipoPedido").hide();

  $("#txtEmpleadoVent").val(total); //.Empleado que registro el pedido;
  $("#txtClienteVent").val(correo); //.falta concatenar nombre y apellido desde js;
  $("#txtClienteDni").val(num_documento); // MUESTRA DETALLE DE VENTA
  $("#txtClienteCel").val(celular);
  $("#txtClienteEmail").val(correo); // MUESTRA DETALLE DE VENTA
  $("#txtClienteDir").val(tipo_cliente); // MUESTRA DETALLE DE VENTA

  // $("#hdn_idClientePedido").val(idcliente);
  $("#hdn_metodo_pago").val(destino);
  $("#hdn_agencia_envio").val(ticket);
  $("#hdn_tipo_promocion").val(aproba_venta);
  $("#txtClientePed").val(metodo_pago);

  $("#cboModPagoDetalles").val(empleado);
  $("#textObservacionesDetalles").val(aproba_pedido);

  $("#hdn_tipo_entrega").val(observaciones);
  $("#hdn_modo_pago").val(ultimo);
  $("#hdn_observacion").val(modo_pago);

  $("#cboModTipo_EntregaDetalles").val(metodo_pago);

  $("#txtTotalVent").val(tipo_pedido);

 
  //$("#hdn_agencia_envio").val(agencia_envio);
  //$("#txtClienteDir").val(destino); // MUESTRA DETALLE DE VENTA
  /* $("#txtRutaImgVoucher").val(imagen);
    $("#txtRutaImgVoucher").show(); */

  //$("#txtRutaImgArt").prop("disabled", true);
  email = correo;
  //destino = direccion;
  //num_documento = dni;
  //celular = celular;
  //fecha = date;
  //hora_operacion = hora_operacion;
  
  var igvPed =
    (total * parseInt($("#txtImpuesto").val())) /
    (100 + parseInt($("#txtImpuesto").val()));
  $("#txtIgvPed").val(Math.round(igvPed * 100) / 100);

  var subTotalPed =
    total -
    (total * parseInt($("#txtImpuesto").val())) /
      (100 + parseInt($("#txtImpuesto").val()));
  $("#txtSubTotalPed").val(Math.round(subTotalPed * 100) / 100);

  $("#txtTotalPed").val(Math.round(total * 100) / 100);

  if (modificar_detalle == "modificarDetalles") {
    $("#ContainerbuttonAgregarImagenAlmacen").show();
  }
  mostrarDetalleImagenesEmpaquetado(idPedido)


  if (tipo_pedido == "Venta") {
    $.getJSON(
      "./ajax/PedidoAjax.php?op=GetVenta",
      {
        idPedido: idPedido,
      },
      function (r) {
        if (r) {
          $("#VerFormVentaPed").show();
          $("#VerDetallePedido").hide();
          $("#VerTotalesDetPedido").hide();
          $("#inputTotal").hide();
          $("#txtTotalVent").hide();
          $("#VerRegPedido").hide();
          $("#txtClienteVent").val(cliente); //.val(cliente);
          $("#txtSerieVent").val(r.serie_comprobante);
          $("#txtNumeroVent").val(r.num_comprobante);
          $("#cboTipoVenta").val(r.tipo_venta); //$("#txtClienteFech").val(date);
          $("#cboTipoComprobante").html(
            "<option>" + r.tipo_comprobante + "</option>"
          );
          $("#txtClienteDni").val(num_documento); // MUESTRA DETALLE DE VENTA
          $("#txtClienteCel").val(celular);
          $("#txtTipoCliente").val(tipo_cliente);
          $("#txtClienteDir").val(destino); // MUESTRA DETALLE DE VENTA
          $("#txtNotaVenta").val(ticket);
          $("#txtAprobaCuenAbo").val(aproba_venta);
          $("#txtAprobaVenta").val(aproba_pedido);
          $("#txtEmpleadoVent").val(empleado);
          $("#hdn_metodo_pago").val(metodo_pago);
          $("#hdn_agencia_envio").val(agencia_envio);
          $("#hdn_tipo_promocion").val(tipo_promocion);

          /* $("#txtNumeroOpe").val(r.num_operacion);

                //$("#cboTipo_documento").val(documento_per);
                
                //$("#cboTipoPromocion").val(r.tipo_promocion);
                //$("#cboMetodoPago").val(r.metodo_pago);
                //$("#hdn_agencia_envio").val(agencia_envio);
                //$("#hdn_idcliente").val(r.metodo_pago);
                /* $("#txtNumeroOpe").val(r.num_operacion);
                $("#txtHoraOpe").val(r.hora_operacion); */
          //$("#cboAgenEnvio").val(r.agencia_envio);

          var igvPed =
            (r.total * parseInt($("#txtImpuesto").val())) /
            (100 + parseInt($("#txtImpuesto").val()));
          $("#txtIgvPedVer").val(Math.round(igvPed * 100) / 100);

          var subTotalPed =
            r.total -
            (r.total * parseInt($("#txtImpuesto").val())) /
              (100 + parseInt($("#txtImpuesto").val()));
          $("#txtSubTotalPedVer").val(Math.round(subTotalPed * 100) / 100);

          $("#txtTotalPedVer").val(Math.round(r.total * 100) / 100);
          $("#txtVenta").html("Datos de la Venta");
          $("#OcultaBR1").hide();
          $("#OcultaBR2").hide();
          $('button[type="submit"]').hide();
          $("#btnGenerarVenta").hide();
          $("#btnEnviarCorreo").show();
        }
      }
    );
  }
  $("#txtNumeroPed").hide();

  $("#txtImpuestoPed").hide();
  $("#Porcentaje").hide();
  $("#btnBuscarCliente").hide();
  $("#btnBuscarDetIng").hide();

  $("#inputCliente").hide();
  $("#inputImpuesto").hide();
  $("#inputTipoPed").hide();
  $("#inputNumero").hide();

  CargarDetallePedido(idPedido);
  $("#cboTipoPedido").prop("disabled", true);
  $("#txtNumeroPed").prop("disabled", true);
  $("#txtCliente").prop("disabled", true);
  $("#labelFecha").hide(); //Se oculta la hora de registro de operacion
  //$("#txtHoraOpe").hide(); //Se oculta la hora de registro de operacion

  $('button[type="submit"]').hide();
  $("#btnGenerarVenta").hide();
  //$('button[type="submit"]').attr('disabled','disabled');
  $("#btnBuscarDetIng").prop("disabled", true);
  $("#btnBuscarCliente").prop("disabled", true);

  $("#cboFechaDesdeVent").hide();
  $("#cboFechaHastaVent").hide();
  $("#lblDesde").hide();
  $("#lblHasta").hide();
  $("#btnNuevoPedido_nuevo").hide();

  // CARGA DETALLE DE IMAGENES

  mostrarDetalleImagenes(idPedido);
}

function mostrarDetalleImagenes(idPedido) {
  $("#detalleImagenes").html("");

  $.post(
    "./ajax/PedidoAjax.php?op=GetImagenes",
    {
      idPedido: idPedido,
    },
    function (r) {
      if (r != "") {
        $("#detalleImagenes").html(r);
      } else {
        $("#detalleImagenes").html("Sin datos que mostrar...");
      }
    }
  );
}

function mostrarDetalleImagenesEmpaquetado(idPedido) {
  $("#detalleImagenesEmpaquetado").html("");

  $.post(
    "./ajax/PedidoAjax.php?op=GetImagenesEmpaquetado",
    {
      idPedido: idPedido,
    },
    function (r) {
      if (r != "") {
        $("#detalleImagenesEmpaquetado").html(r);
      } else {
        $("#detalleImagenesEmpaquetado").html("Sin datos que mostrar...");
      }
    }
  );
}



/*  function eliminarDetalleImagen(id,idpedido) {

     $.post("./ajax/PedidoAjax.php?op=DeleteImagenes",{id: id}, function(r){

         mostrarDetalleImagenes(idpedido);

     });

     /*
     $("#modal-eliminar-imagen").modal("show");
     $("#hdn_iddetalleimagen").val(id);
     $("#hdn_numerdetalleimagen").val(numero);
     

 } */

$("#btn_cancelar_imagen").click(function (e) {
  e.preventDefault();

  $("#modal-eliminar-imagen").modal("hide");
  $("#hdn_iddetalleimagen").val("");
  $("#hdn_numerdetalleimagen").val("");
});

$("#btn_eliminar_imagen").click(function (e) {
  e.preventDefault();

  $.post(
    "./ajax/PedidoAjax.php?op=DeleteImagenes",
    {
      id: $("#hdn_iddetalleimagen").val(),
    },
    function (r) {
      mostrarDetalleImagenes($("#hdn_numerdetalleimagen").val());
    }
  );
});

function CargarDetallePedido(idPedido) {
  //$('th:nth-child(2)').hide();
  //$('th:nth-child(3)').hide();
  $("table#tblDetallePedidoVer th:nth-child(4)").hide();
  $("table#tblDetallePedidoVer th:nth-child(8)").hide();

  $("table#tblDetallePedido th:nth-child(4)").hide();
  $("table#tblDetallePedido th:nth-child(8)").hide();

  $.post(
    "./ajax/PedidoAjax.php?op=GetDetallePedido",
    {
      idPedido: idPedido,
    },
    function (r) {
      $("table#tblDetallePedidoVer tbody").html(r);
      $("table#tblDetallePedido tbody").html(r);
    }
  );
}

function cancelarPedido(idPedido) {
  // alert(idPedido);

  //alert(detalleTraerCantidad[0]);
  bootbox.confirm(
    "Solo el ADMINISTRADOR debe anular las ventas ¿Esta seguro de Anular la Venta?",
    function (result) {
      if (result) {
        $.ajax({
          url: "./ajax/PedidoAjax.php?op=TraerCantidad",
          dataType: "json",
          data: {
            idPedido: idPedido,
          },
          success: function (s) {
            for (var i = 0; i < s.length; i++) {
              //alert(s[i][0] + " - " + s[i][1]);
              TraerCantidad(s[i][0], s[i][1]);
            }
            var detalle = JSON.parse(consultarCantidad());
            var data = {
              idPedido: idPedido,
              detalle: detalle,
            };

            $.post(
              "./ajax/PedidoAjax.php?op=CambiarEstado",
              data,
              function (e) {
                swal("Mensaje del Sistema", e, "success");
                //alert(e);
                ListadoPedidos();
              }
            );
          },
          error: function (e) {
            console.log(e.responseText);
          },
        });
        //Ver();
        detalleTraerCantidad.length = 0;
      }
    }
  );
}

function TraerCantidad(iddet_ing, cantidad) {
  var detalle = new Array(iddet_ing, cantidad);
  detalleTraerCantidad.push(detalle);
}

function eliminarPedido(idPedido) {
  bootbox.confirm("¿Esta seguro de eliminar el pedido?", function (result) {
    if (result) {
      $.post(
        "./ajax/PedidoAjax.php?op=EliminarPedido",
        {
          idPedido: idPedido,
        },
        function (e) {
          swal("Mensaje del Sistema", e, "success");
          ListadoPedidos();
          ListadoVenta();
        }
      );
    }
  });
}

function cambiarEstadoPedido(idPedido) {
  // COMPRUEBA PRIMERO STOCK DE PRODUCTOS

  $.get(
    "./ajax/VentaAjax.php?op=VerificarStockProductos_CambiarEstado",
    "idPedido=" + idPedido,
    function (r) {
      var obj = jQuery.parseJSON(r);
      console.log(obj.length == 0);

      // if (obj.estado == true || obj.estado == "true") {
      if (obj.length == 0) {
        bootbox.confirm(
          "¿Esta seguro de cambiar el estado del pedido?",
          function (result) {
            if (result) {
              $.post(
                "./ajax/PedidoAjax.php?op=cambiarEstadoPedido",
                {
                  idPedido: idPedido,
                },
                function (e) {
                  swal("Mensaje del Sistema", e, "success");
                  ListadoPedidos();
                  ListadoVenta();
                }
              );
            }
          }
        );
      } else {
        var arr = obj;
        alert(
          "No se puede completar el proceso ya que existen productos sin stock:\n" +
            arr.join("\n")
        );
      }
    }
  );
}

function VerMsj() {
  bootbox.alert("No se puede generar la venta, este pedido esta cancelado");
}

function ModificarPed(pos) {
  var idDetIng = document.getElementsByName("txtIdDetIng");
  var pvd = document.getElementsByName("txtPrecioVentPed");
  var cantPed = document.getElementsByName("txtCantidaPed");
  var descPed = document.getElementsByName("txtDescuentoPed");
  // alert(pos);
  //elementos[pos][2] = $("input[name=txtPrecioVentPed]:eq(" + pos + ")").val();

  elementos[pos][0] = idDetIng[pos].value;
  elementos[pos][2] = pvd[pos].value;
  if (parseInt(cantPed[pos].value) <= elementos[pos][5]) {
    elementos[pos][3] = cantPed[pos].value;
    if (parseInt(cantPed[pos].value) <= 0) {
      bootbox.alert(
        "<center>El Articulo " +
          elementos[pos][1] +
          " no puede estar vacio, menor o igual que 0</center>",
        function () {
          elementos[pos][3] = "1";
          cantPed[pos].value = "1";
          calcularIgvPed();
          calcularSubTotalPed();
          calcularTotalPed();
        }
      );
    }
  } else {
    bootbox.alert(
      "<center>El Articulo " +
        elementos[pos][1] +
        " no tiene suficiente stock para tal cantidad</center>",
      function () {
        elementos[pos][3] = "1";
        cantPed[pos].value = "1";
        calcularIgvPed();
        calcularSubTotalPed();
        calcularTotalPed();
      }
    );
  }

  elementos[pos][4] = descPed[pos].value;
  //alert(elementos[pos][3]);
  //alert(elementos[pos][0] + " - " + elementos[pos][2] + " - " + elementos[pos][3] + " - " + elementos[pos][4] + " - ");
  ConsultarDetalles();
}

function FormVenta(total, idpedido, total, Cliente, num_documento, correo) {
  $("#VerFormVentaPed").show();
  $("#btnNuevo").hide();
  $("#btnEnviarCorreo").hide();
  $("#VerListado").hide();
  $("#txtTotalVent").val(total);
  $("#txtClienteVent").val(Cliente);
  $("#txtClienteDni").val(num_documento);
  $("#txtIdPedido").val(idpedido);
  email = correo;
  $("#lblTitlePed").html("Venta");
  ComboTipoDoc();
  CargarDetallePedido(idpedido);
  var igvPed =
    (total * parseInt($("#txtImpuesto").val())) /
    (100 + parseInt($("#txtImpuesto").val()));
  $("#txtIgvPedVer").val(Math.round(igvPed * 100) / 100);

  var subTotalPed =
    total -
    (total * parseInt($("#txtImpuesto").val())) /
      (100 + parseInt($("#txtImpuesto").val()));
  $("#txtSubTotalPedVer").val(Math.round(subTotalPed * 100) / 100);

  $("#txtTotalPedVer").val(Math.round(total * 100) / 100);
  AgregatStockCant(idpedido);

  function newFunction() {
    return "#txtClienteVent", "#txtClienteDni";
  }
}

function AgregatStockCant(idPedido) {
  $.ajax({
    url: "./ajax/PedidoAjax.php?op=GetDetalleCantStock",
    dataType: "json",
    data: {
      idPedido: idPedido,
    },
    success: function (s) {
      for (var i = 0; i < s.length; i++) {
        AgregarDetalleCantStock(s[i][0], s[i][1], s[i][2]);
      }
      //      Ver();
    },
    error: function (e) {
      console.log(e.responseText);
    },
  });
}

function Ver() {
  var data = JSON.parse(consultarCantidad());
  for (var pos in data) {
    alert(data[pos][1]);
  }
}

function AgregarDetalleCantStock(iddet_ing, stock, cant) {
  var detalles = new Array(iddet_ing, stock, cant);
  detalleIngresos.push(detalles);
}

function consultarCantidad() {
  return JSON.stringify(detalleTraerCantidad);
}

this.consultarCantidad = function () {
  return JSON.stringify(detalleTraerCantidad);
};

this.consultarDet = function () {
  return JSON.stringify(detalleIngresos);
};

function ComboTipoDoc() {
  $.get("./ajax/PedidoAjax.php?op=listTipoDoc", function (r) {
    $("#cboTipoComprobante").html(r);
  });
}

//Consulta de stock menor a 0 Unidades
function AgregarPedCarrito(
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
      cod,
      serie,
      idart,
      marca
    );
    // COMPRUBA SI HAY PRODUCTOS AGREGADOS - SI NO, NO BUSCA NADA
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

    //console.log(data);

    ConsultarDetallesPed();
  } else {
    bootbox.alert("No se puede agregar al detalle. No tiene stock");
  }
}

function GetPrimerCliente() {
  $.getJSON("./ajax/PedidoAjax.php?op=GetPrimerCliente", function (r) {
    if (r) {
      $("#txtIdCliente").val("");
      $("#txtCliente").val("");
    }
  });
}
