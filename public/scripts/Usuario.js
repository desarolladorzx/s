$(document).on("ready", init);

function init() {
  $("#tblUsuarios").dataTable({
    dom: "Bfrtip",
    buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
  });

  ListadoUsuarios();
  ComboSucursal();
  $("#VerForm").hide();
  //$("#txtRutaImgArt").hide();     IMAGEN DE ARTICULO
  $("form#frmUsuarios").submit(SaveOrUpdate);

  $("#btnNuevo").click(VerForm);
  $("#btnBuscarTrabajador").click(AbrirModalEmpleado);

  $("#btnAgregarEmpleado").click(function (e) {
    e.preventDefault();

    var opt = $("input[type=radio]:checked");
    $("#txtIdEmpleado").val(opt.val());
    $("#txtEmpleado").val(
      opt.attr("data-nombre") + " " + opt.attr("data-apellidos")
    );

    $("#modalListadoEmpleados").modal("hide");
  });

  function SaveOrUpdate(e) {
    e.preventDefault();

	console.log($(this).serialize())
    if ($("#txtIdEmpleado").val() != "") {
      $.post(
        "./ajax/UsuarioAjax.php?op=SaveOrUpdate",
        $(this).serialize(),
        function (r) {
          swal("Mensaje del Sistema", r, "success");
          Limpiar();
          ListadoUsuarios();
          OcultarForm();
        }
      );
    } else {
      bootbox.alert("Debe elegir un trabajador");
    }
  }

  function ComboSucursal() {
    $.post("./ajax/UsuarioAjax.php?op=listSucursal", function (r) {
      $("#cboSucursal").html(r);
    });
  }

  function AbrirModalEmpleado() {
    $("#modalListadoEmpleados").modal("show");

    $.post("./ajax/UsuarioAjax.php?op=listEmpleado", function (r) {
      $("#Trabajador").html(r);
      $("#tblTrabajadores").DataTable();
    });
  }

  function Limpiar() {
    $("#txtIdUsuario").val("");
    $("#txtNombre").val("");
    $("#txtIdEmpleado").val("");
    $("#txtEmpleado").val("");
    $("#chkMnuAlmacen").attr("checked", false);
    $("#chkMnuCompras").attr("checked", false);
    $("#chkMnuVentas").attr("checked", false);
    $("#chkMnuMantenimiento").attr("checked", false);
    $("#chkMnuSeguridad").attr("checked", false);
    $("#chkConsultaCompras").attr("checked", false);
    $("#chkConsultaVentas").attr("checked", false);
    $("#chkMnuDocEV").attr("checked", false);
    $("#chkMnuDocJV").attr("checked", false);
    $("#chkMnuDocJA").attr("checked", false);
    $("#chkMnuDocJL").attr("checked", false);
    $("#chkMnuAdmin").attr("checked", false);
    $("#chkMnuSIGRH").attr("checked", false);
  }

  function VerForm() {
    $("#VerForm").show();
    $("#btnNuevo").hide();
    $("#VerListado").hide();
  }

  function OcultarForm() {
    $("#VerForm").hide();
    $("#btnNuevo").show();
    $("#VerListado").show();
    //Limpiar();
  }
}

function eliminarUsuario(id) {
  bootbox.confirm("¿Esta Seguro de eliminar el Usuario?", function (result) {
    if (result) {
      $.post("./ajax/UsuarioAjax.php?op=delete", { id: id }, function (e) {
        swal("Mensaje del Sistema", e, "success");
        ListadoUsuarios();
        Limpiar();
      });
    }
  });
}

function ListadoUsuarios() {
  var tabla = $("#tblUsuarios")
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
      ],
      ajax: {
        url: "./ajax/UsuarioAjax.php?op=list",
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

function cargarDataUsuario(
  idUsuario,
  idSucursal,
  idempleado,
  empleado,
  tipo_usuario,
  mnu_almacen,
  mnu_compras,
  mnu_ventas,
  mnu_mantenimiento,
  mnu_seguridad,
  mnu_consulta_compras,
  mnu_consulta_ventas,
  mnu_documentacion_ev,
  mnu_documentacion_jv,
  mnu_documentacion_ja,
  mnu_documentacion_jl,
  mnu_admin,

  mnu_documentacion,
  mnu_documentacion_marketing,
  mnu_documentacion_finanzas,
  mnu_documentacion_logistica,
  mnu_documentacion_ventas,
  mnu_documentacion_it,
  mnu_documentacion_rrhh,
  mnu_documentacion_produccion,
  mnu_sigrh
) {
  $("#VerForm").show();
  $("#btnNuevo").hide();
  $("#VerListado").hide();

  $("#txtIdUsuario").val(idUsuario);
  $("#cboSucursal").val(idSucursal);
  $("#txtIdEmpleado").val(idempleado);
  $("#txtEmpleado").val(empleado);
  $("#cboTipoUsuario").val(tipo_usuario);

  // DOCUMENTATION
  if (mnu_documentacion == 1) {
    $("#chkMnuDocumentacion").attr("checked", true);
  } else {
    $("#chkMnuDocumentacion").attr("checked", false);
  }


  

  if (mnu_sigrh == 1) {
    $("#chkMnuSIGRH").attr("checked", true);
  } else {
    $("#chkMnuSIGRH").attr("checked", false);
  }



  if (mnu_documentacion_ventas == 1) {
    $("#chkMnuDocVentas").attr("checked", true);
  } else {
    $("#chkMnuDocVentas").attr("checked", false);
  }
  if (mnu_documentacion_marketing == 1) {
    $("#chkMnuDocMarketing").attr("checked", true);
  } else {
    $("#chkMnuDocMarketing").attr("checked", false);
  }
  if (mnu_documentacion_logistica == 1) {
    $("#chkMnuDocLogistica").attr("checked", true);
  } else {
    $("#chkMnuDocLogistica").attr("checked", false);
  }
  if (mnu_documentacion_finanzas == 1) {
    $("#chkMnuDocFinanzas").attr("checked", true);
  } else {
    $("#chkMnuDocFinanzas").attr("checked", false);
  }
  if (mnu_documentacion_rrhh == 1) {
    $("#chkMnuDocRRHH").attr("checked", true);
  } else {
    $("#chkMnuDocRRHH").attr("checked", false);
  }
  if (mnu_documentacion_it == 1) {
    $("#chkMnuDocIT").attr("checked", true);
  } else {
    $("#chkMnuDocIT").attr("checked", false);
  }
  if (mnu_documentacion_produccion == 1) {
    $("#chkMnuDoProduccion").attr("checked", true);
  } else {
    $("#chkMnuDoProduccion").attr("checked", false);
  }
  // DOCUMENTATION

  if (mnu_almacen == 1) {
    $("#chkMnuAlmacen").attr("checked", true);
  } else {
    $("#chkMnuAlmacen").attr("checked", false);
  }
  if (mnu_compras == 1) {
    $("#chkMnuCompras").attr("checked", true);
  } else {
    $("#chkMnuCompras").attr("checked", false);
  }
  if (mnu_ventas == 1) {
    $("#chkMnuVentas").attr("checked", true);
  } else {
    $("#chkMnuVentas").attr("checked", false);
  }
  if (mnu_mantenimiento == 1) {
    $("#chkMnuMantenimiento").attr("checked", true);
  } else {
    $("#chkMnuMantenimiento").attr("checked", false);
  }
  if (mnu_seguridad == 1) {
    $("#chkMnuSeguridad").attr("checked", true);
  } else {
    $("#chkMnuSeguridad").attr("checked", false);
  }
  if (mnu_consulta_compras == 1) {
    $("#chkConsultaCompras").attr("checked", true);
  } else {
    $("#chkConsultaCompras").attr("checked", false);
  }
  if (mnu_consulta_ventas == 1) {
    $("#chkConsultaVentas").attr("checked", true);
  } else {
    $("#chkConsultaVentas").attr("checked", false);
  }
  if (mnu_documentacion_ev == 1) {
    $("#chkMnuDocEV").attr("checked", true);
  } else {
    $("#chkMnuDocEV").attr("checked", false);
  }
  if (mnu_documentacion_jv == 1) {
    $("#chkMnuDocJV").attr("checked", true);
  } else {
    $("#chkMnuDocJV").attr("checked", false);
  }
  if (mnu_documentacion_ja == 1) {
    $("#chkMnuDocJA").attr("checked", true);
  } else {
    $("#chkMnuDocJA").attr("checked", false);
  }
  if (mnu_documentacion_jl == 1) {
    $("#chkMnuDocJL").attr("checked", true);
  } else {
    $("#chkMnuDocJL").attr("checked", false);
  }
  if (mnu_admin == 1) {
    $("#chkMnuAdmin").attr("checked", true);
  } else {
    $("#chkMnuAdmin").attr("checked", false);
  }
}
