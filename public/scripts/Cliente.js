$(document).on("ready", init); // Inciamos el jquery

function pulsar(e) {
  if (e.shiftKey) {
    e.preventDefault();
    return false;
  }
  if ([1, 16, 14, 13, 15].includes(e.which) && !e.shiftKey) {
    e.preventDefault();
    console.log("prevented");
    return false;
  }
}

function demostrarTelefono(value, text) {
  $("#container_alerta_telefono").hide(1000);
  $("#container_respuesta_inputs_coincidencia_de_telefonos").hide(1000);

  $(`#${text}`).html("");

  if (value) {
    $.post(
      "./ajax/ClienteAjax.php?op=comprobar_telefono",
      { telefono: value },
      function (r) {
        console.log(r);
        let response = JSON.parse(r);

        let textoListaTelefonoCoincidencias = "";
        if (response.length > 0) {
          // $("#container_alerta_telefono").show("slow");

          response.map((e) => {
            console.log(e);
            textoListaTelefonoCoincidencias += `
            <p>
            usado por -
            ${e.num_documento} -
            ${e.nombre} 
            </p>
            `;
          });

          // $("#containerListaTelefonoCoincidencias").html(
          //   textoListaTelefonoCoincidencias
          // );
          $(`#${text}`).html(textoListaTelefonoCoincidencias);
          // $("#container_respuesta_inputs_coincidencia_de_telefonos").show(
          //   "slow"
          // );
        }
      }
    );
  }
}

function handleClick(checkbox) {
  if (checkbox.checked) {
    console.log((checkbox.value = "True"));

    $("#btn_minimizar_container_localizacion_recepcion").click();
    $("#txt_direccion_envio").val($("#txtDireccion_Calle").val());
    $("#txt_ubicacion_envio").val($("#txt_ubicacion_nuevo").val());

    $("#id_ubicacion_envio_array").val($("#id_ubicacion_array").val());

    $("#txt_direccion_referencia_envio").val(
      $("#txtDireccion_Referencia").val()
    );
  } else {
    console.log((checkbox.value = "False"));
    $("#btn_minimizar_container_localizacion_recepcion").click();
    $("#txt_direccion_envio").val("");
    $("#txt_ubicacion_envio").val("");
    $("#txt_direccion_referencia_envio").val("");
  }
}

function ubicacionAntiguo() {
  console.log();
}




function init() {

  $("#insertarClientesACartera").click(function () {
    // $('.close').click()
    $(".loading_window").show();

    if ($("#select_personal_vendedor").val()) {
      $.ajax({
        url: "./ajax/ClienteAjax.php?op=asignarCarteraVendedor",
        dataType: "json",
        data: {
          lista: listadeClientesAsignados,
          idempleado: $("#select_personal_vendedor").val(),
        },
        type: "post",
        success: function (rpta) {
          console.log(rpta);
          swal(
            "Mensaje del Sistema",
            "clientes asignados correctamente",
            "success"
          );
          ListadoCliente();
          $("#asignarUsuario").modal("hide");
        },
        error: function (e) {
          swal(
            "Mensaje del Sistema",
            "clientes asignados correctamente",
            "success"
          );
          ListadoCliente();

          $("#asignarUsuario").modal("hide");
        },
      });
    } else {
      alert("es necesario selecionar un vendedor");
    }
  });

  $("#container_respuesta_inputs_coincidencia_de_telefonos").hide();
  $("#container_alerta_telefono").hide();

  /* 	$('#tblCliente').dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5
            'pdfHtml5'
        ]
    }); */
  $("#txtTelefono_2").change(function () {
    demostrarTelefono($("#txtTelefono_2").val(), "txtTelefono_2_span");
  });
  $("#txtTelefono").change(function () {
    demostrarTelefono($("#txtTelefono").val(), "txtTelefono_span");
  });

  $("#container_ubicacion_antigua").hide();

  $("#txt_ubicacion_nuevo").change(function (e) {
    if ($("#idIgualAdirecionEnvio").val() == "True") {
      $("#txt_ubicacion_envio").val($("#txt_ubicacion_nuevo").val());

      const selectedOption = $(
        `#ubicacion option[value="${$("#txt_ubicacion_nuevo").val()}"`
      );

      // const selectedNumber = selectedOption.data('number');
      $("#id_ubicacion_envio_array").val(selectedOption.data("id"));
    }
  });
  $("#txtDireccion_Calle").change(function (e) {
    if ($("#idIgualAdirecionEnvio").val() == "True") {
      $("#txt_direccion_envio").val($("#txtDireccion_Calle").val());
    }
  });
  $("#txtDireccion_Referencia").change(function (e) {
    if ($("#idIgualAdirecionEnvio").val() == "True") {
      $("#txt_direccion_referencia_envio").val(
        $("#txtDireccion_Referencia").val()
      );
    }
  });

  $("#txt_ubicacion_envio").on("blur", function () {
    var optionValues = $(".ubicacion_containe_options option")
      .map(function () {
        return $(this).val();
      })
      .get();
    if ($.inArray($(this).val(), optionValues) === -1) {
      $(this).val("");
    }
  });

  $("#txt_ubicacion_nuevo").on("blur", function () {
    console.log($(this).val());
    var optionValues = $(".ubicacion_containe_options option")
      .map(function () {
        return $(this).val();
      })
      .get();

    console.log(optionValues);

    if ($.inArray($(this).val(), optionValues) === -1) {
      $(this).val("");
    }
  });

  $("#txt_ubicacion_nuevo").change(function (e) {
    const selectedOption = $(
      `#ubicacions option[value="${$("#txt_ubicacion_nuevo").val()}"`
    );

    // const selectedNumber = selectedOption.data('number');
    $("#id_ubicacion_array").val(selectedOption.data("id"));
  });

  $("#txt_ubicacion_envio").change(function (e) {
    const selectedOption = $(
      `#ubicacion option[value="${$("#txt_ubicacion_envio").val()}"`
    );
    // const selectedNumber = selectedOption.data('number');
    $("#id_ubicacion_envio_array").val(selectedOption.data("id"));
  });

  // traerUbicacion();
  // function traerUbicacion() {
  //   $.ajax({
  //     url: "./ajax/ClienteAjax.php?op=traerUbicacion",
  //     dataType: "json",
  //     type: "get",
  //     success: function (rpta) {
  //       var ubicacion_containe_options_html = "";

  //       rpta.map((e) => {
  //         ubicacion_containe_options_html += `<option data-id='${e.idubicacion}' value='${e.ubicacion}'>${e.idubicacion}</option>`;
  //       });
  //       // console.log(rpta);

  //       $(".ubicacion_containe_options").html(ubicacion_containe_options_html);
  //     },
  //     error: function (e) {},
  //   });
  // }
  $("#btn_minimizar_container_localizacion_recepcion").hide();

  $("#btnNuevo").show();

  $(".loading_window").hide();
  $("#button_registrar_nuevo_cliente").hide();
  if ($("#hdn_rol_usuario").val() == "S") {
    // SUPERADMIN
    $('#cboTipo_Persona option[value="FINAL"]').attr("disabled", false);
    $('#cboTipo_Persona option[value="DISTRIBUIDOR"]').attr("disabled", false);
    $('#cboTipo_Persona option[value="SUPERDISTRIBUIDOR"]').attr(
      "disabled",
      false
    );
    $('#cboTipo_Persona option[value="REPRESENTANTE"]').attr("disabled", false);

    $("#cboTipo_Documento option:not(:selected)").attr("disabled", false);

    //$("#cboTipo_Documento").prop('readonly', false);
    /*$('#cboTipo_Documento option[value="DNI"]').attr("disabled", false);
		$('#cboTipo_Documento option[value="RUC"]').attr("disabled", false);
		$('#cboTipo_Documento option[value="PASAPORTE"]').attr("disabled", false);
		$('#cboTipo_Documento option[value="CE"]').attr("disabled", false);*/
  } else if ($("#hdn_rol_usuario").val() == "A") {
    // USUARIO / TRABAJADOR
    // $('#cboTipo_Persona option[value="FINAL"]').attr("disabled", false);
    // $('#cboTipo_Persona option[value="DISTRIBUIDOR"]').attr("disabled", true);
    // $('#cboTipo_Persona option[value="SUPERDISTRIBUIDOR"]').attr(
    //   "disabled",
    //   true
    // );
    // $('#cboTipo_Persona option[value="REPRESENTANTE"]').attr("disabled", true);

    $("#cboTipo_Persona").prop("disabled", true);

    $("#cboTipo_Documento option:not(:selected)").attr("disabled", true);

    /*$('#cboTipo_Documento option[value="DNI"]').attr("disabled", true);
		$('#cboTipo_Documento option[value="RUC"]').attr("disabled", true);
		$('#cboTipo_Documento option[value="PASAPORTE"]').attr("disabled", true);
		$('#cboTipo_Documento option[value="CE"]').attr("disabled", true);*/
  }

  ListadoCliente(); // Ni bien carga la pagina que cargue el metodo
  ComboTipo_Documento();
  $("#VerForm").hide(); // Ocultamos el formulario
  $("form#frmCliente").submit(SaveOrUpdate); // Evento submit de jquery que llamamos al metodo SaveOrUpdate para poder registrar o modificar datos
  $("#btnNuevo").click(VerForm); // evento click de jquery que llamamos al metodo VerForm
  $("#btnExtraerClientes").click(buscarPorNumeroDocumento); // Evento para buscar documento por Extracioon

  $("#cliente_filtro").change(function () {
    var valor = $(this).val();
    // tabla.column(13).search(valor).draw();

    // console.log(tabla.column(13).data())
    if (valor.length == 0) {
      // verifica si el valor es nulo
      tabla.column(13).search("").draw();
    } else {
      tabla.column(13).search(`^${valor}$`, true, false).draw(); // realiza la búsqueda normal
    }

    llenarCantidades();
  });
  $("#ejecutivo_filtro").change(function () {
    var valor = $(this).val();
    // console.log(valor)
    if (valor.length == 0) {
      // verifica si el valor es nulo
      tabla.column(11).search("").draw();
    } else {
      tabla.column(11).search(`^${valor}$`, true, false).draw(); // realiza la búsqueda normal
    }

    $("#cant_total_cliente").val(tabla.page.info().end);
    llenarCantidades();
  });
  $("#estado_filtro").change(function () {
    var valor = $(this).val();
    tabla.column(3).search(valor).draw();
    llenarCantidades();
  });

  TraerRoles();
  function TraerRoles() {
    $.ajax({
      url: "./ajax/ConsultasVentasAjax.php?op=listaEjecutivoComercial",
      dataType: "json",

      success: function (s) {
        console.log(s);
        var lista = s;

        var html = `<option value="" selected="selected">Todos ...</option>`;
        var htmlEVresponsable = `<option value="" selected="selected"></option>`;
        lista.map((e) => {
          html += `<option value="${e.rol_nombre_usuario}">${e.r_prefijo} ${e.nombre_usuario}</option>`;

          htmlEVresponsable += `<option value="${e.idempleado}">${e.r_prefijo} ${e.nombre_usuario}</option>`;
        });

        $("#txt_empleado_asignado").html(htmlEVresponsable);
        $("#ejecutivo_filtro").html(html);
      },
      error: function (e) {},
    });
  }

  function SaveOrUpdate(e) {
    e.preventDefault(); // para que no se recargue la pagina

    $("#txtTelefono_span").html("");
    $("#txtTelefono_2_span").html("");

    console.log($("#idIgualAdirecionEnvio").val());

    // if ($("#idIgualAdirecionEnvio").val() == "True") {
    //   $("#txt_direccion_referencia_envio").val(
    //     $("#txtDireccion_Referencia").val()
    //   );
    //   $("#txt_direccion_envio").val($("#txtDireccion_Referencia").val());
    //   $("#txt_ubicacion_envio").val($("#txtDireccion_Referencia").val());
    // }

    console.log($("#cboTipo_Persona").val());

    document.getElementById("cboTipo_Persona").disabled = false;

    $.post(
      "./ajax/ClienteAjax.php?op=SaveOrUpdate",
      $(this).serialize(),
      function (r) {
        document.getElementById("cboTipo_Persona").disabled = true;

        // llamamos la url por post. function(r). r-> llamada del callback

        // ListadoCliente();
        // //$.toaster({ priority : 'success', title : 'Mensaje', message : r});
        // //swal("Mensaje del Sistema", r, "success");
        // OcultarForm();

        // $("#btnNuevo").show();

        // $(".container_info_filtro").show();

        // $(".container_info_filtro input").val("");

        // if ([17, 6].includes(Number($("#txtIdEmpleado").val()))) {
        //   $("#btn_asignar_vendedor").show();
        // }

        // Limpiar();

        // swal(
        //   {
        //     title: "Mensaje del Sistema",
        //     text: r,
        //     type: "success",
        //   },
        //   function () {
        //     // location.reload();
        //     // window.location.href = "Cliente.php";
        //   }
        // );

        swal(
          {
            title: "Mensaje del Sistema",
            text: r,
            icon: "success",
          },
          function (confirm) {
            if (confirm) {
              location.reload();
            }
          }
        );
      }
    );
  }

  function Limpiar() {
    // Limpiamos las cajas de texto
    $("#txtIdPersona").val("");
    $("#txtNombre").val("");
    $("#txtApellido").val("");
    $("#txtNum_Documento").val("");
    $("#optionsRadios").val("");
    $("#txtDireccion_Departamento").val("");
    $("#txtDireccion_Provincia").val("");
    $("#txtDireccion_Distrito").val("");
    $("#txtDireccion_Calle").val("");

    $("#cboTipo_Persona").val("FINAL");

    $("#txtDireccion_Referencia").val("");
    $("#txtTelefono").val("");
    $("#txtTelefono_2").val("");
    $("#txtEmail").val("");
    $("#txtNumero_cuenta").val("");
    //$("#txtIdEmpleado").val("");
    $("#txtEmpleado").val("");
    $("#txtIdEmpleado_modificado").val("");
    $("#txtEmpleado_modificado").val("");
    $("#txtFecha_creacion").val("");
    $("#txtFecha_modificacion").val("");

    $("input[name=optionsRadios]").prop("checked", false);
    $("#optionsRadios_edit").val("");
    $("#optionsRadios_id_edit").val("");
    //$("#optionsRadios1").prop("checked", false);
    //$("#optionsRadios2").prop("checked", false);
    //$("#optionsRadios3").prop("checked", false);
  }

  function ComboTipo_Documento() {
    $.get("./ajax/ClienteAjax.php?op=listTipo_DocumentoPersona", function (r) {
      $("#cboTipo_Documento").html(r);
    });
  }

  function VerForm() {
    btnNuevo;
    $(".container_info_filtro").hide();
    $("#VerForm").show(); // Mostramos el formulario
    //$("#btnNuevo").hide();// ocultamos el boton nuevo
    $("#VerListado").hide();

    $("#btn_asignar_vendedor").hide(); // ocultamos el boton nuevo

    $("#optionsRadios1").prop("checked", false);
    $("#optionsRadios2").prop("checked", false);
    $("#optionsRadios3").prop("checked", false);

    $("#panel_rbg_habilitado").show(200);
    $("#panel_rbg_desabilitado").hide(200);
    $("#optionsRadios_edit").val("");
    $("#optionsRadios_id_edit").val("");
  }

  function OcultarForm() {
    $("#VerForm").hide(); // Mostramos el formulario
    //$("#btnNuevo").show();// ocultamos el boton nuevo
    $("#VerListado").show();
    // $("#btn_asignar_vendedor").hide();
  }
}
var tabla;

function llenarCantidades() {
  $("#cant_total_cliente").val(tabla.page.info().recordsDisplay);

  let valoresFiltrados = tabla.rows({ search: "applied" }).data().toArray();

  console.log(valoresFiltrados);

  let clientes_activos = valoresFiltrados.filter((e) =>
    e[13].includes("-ACTIVO")
  ).length;

  let clientes_inactivos = valoresFiltrados.filter((e) =>
    e[13].includes("INACTIVO")
  ).length;

  let clientes_perdidos = valoresFiltrados.filter((e) =>
    e[13].includes("PERDIDO")
  ).length;

  let clientes_FINAL = valoresFiltrados.filter((e) =>
    e[1].includes("FINAL")
  ).length;

  let clientes_final = valoresFiltrados.filter((e) =>
    e[1].includes("Final")
  ).length;

  let clientes_distribuidores = valoresFiltrados.filter((e) =>
    e[1].includes("Distribuidor")
  ).length;

  let clientes_Superdistribuidores = valoresFiltrados.filter((e) =>
    e[1].includes("Superdistribuidores")
  ).length;

  let clientes_representantes = valoresFiltrados.filter((e) =>
    e[1].includes("Representante")
  ).length;

  $("#cant_clientes_activos").val(clientes_activos);
  $("#cant_clientes_inactivos").val(clientes_inactivos);
  $("#cant_clientes_perdidos").val(clientes_perdidos);

  $("#cant_clientes_finales").val(clientes_final + clientes_FINAL);
  $("#cant_clientes_distribuidor").val(clientes_distribuidores);
  $("#cant_clientes_Superdistribuidores").val(clientes_Superdistribuidores);
  $("#cant_clientes_Representantes").val(clientes_representantes);
}
var listadeClientesAsignados = [];

function motivo_reasignacionModal(){
  $("#motivo_reasignacionModal").modal("show");

}
function guardarSelects() {
  var selectedRows = $("#tblCliente")
    .DataTable()
    .rows({ selected: true })
    .data()
    .toArray();
  console.log(selectedRows);
  listadeClientesAsignados = selectedRows;

  if (selectedRows.length > 0) {
    $("#asignarUsuario").modal("show");
    var textli = "";
    selectedRows.map((e) => {
      textli += ` <li>
           ${e[3]} ${e[2]}   
      </li>`;
    });

    $("#numero_clientes_asignados").html(
      `${selectedRows.length} cliente(s) por asignar`
    );

    $("#lista_clientes_por_asignar").html(textli);
  } else {
    alert(
      "Necesita selecionar uno o mas clientes para poder asignar a un vendedor"
    );
  }
}
function ListadoCliente() {
  tabla = $("#tblCliente").DataTable({
    pagingType: "full_numbers",
    lengthMenu: [
      [25, 50, 100, 150, 300],
      [25, 50, 100, 150, 300],
    ],
    aProcessing: true,
    // aServerSide: true,
    // dom: "Bfrtip",
    buttons: [
      //'copyHtml5',
      //'excelHtml5',
      //'csvHtml5',
      //'pdfHtml5'
    ],

    aoColumns: [
      {
        orderable: false,
        className: "select-checkbox", // Agregar la opción
        mDataProp: "0",
        targets: 0,
        visible: [6, 17].includes(Number($("#txtIdEmpleado").val()))
          ? true
          : false,
        checkboxes: {
          selectAll: true,
        },
      },
      { mDataProp: "id" },
      { mDataProp: "1" },
      { mDataProp: "13" },
      { mDataProp: "14" },

      { mDataProp: "2" },
      { mDataProp: "3" },
      { mDataProp: "4" },
      { mDataProp: "5" },
      { mDataProp: "6" },
      // { mDataProp: "7" },
      { mDataProp: "8" },

      // { mDataProp: "10" },
      { mDataProp: "vendedor_asignado" },

      { mDataProp: "12", visible: false },
      { mDataProp: "30", visible: false },
      { mDataProp: "11" },
    ],

    select: {
      style: "os",
      selector: ".select-checkbox",
    },
    order: [[1, "asc"]],

    ajax: {
      url: "./ajax/ClienteAjax.php?op=list",
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      },
    },
    initComplete: function (settings, json) {
      llenarCantidades();

      // console.log(json)

      // $("#cant_clientes_finales").val(tabla.page.info())
    },
    bDestroy: true,
  });

  tabla.on("click", "th.select-checkbox", function () {
    var pageNum = tabla.page.info().page;
    var rows = tabla.rows({ page: "current" }).nodes();

    // var nodes = tabla.rows({ search: "applied" }).nodes();

    // console.log(nodes)
    // if ($("th.select-checkbox").hasClass("selected")) {
    //   tabla.rows().deselect();
    //   $("th.select-checkbox").removeClass("selected");
    // } else {
    //   tabla.rows({ search: "applied" }).select();
    //   $(nodes).addClass("selected");
    //   $("th.select-checkbox").addClass("selected");
    // }
    if ($("th.select-checkbox").hasClass("selected")) {
      tabla.rows().deselect();
      $("th.select-checkbox").removeClass("selected");
    } else {
      tabla.rows({ page: "current" }).select();
      $(rows).addClass("selected");
      $("th.select-checkbox").addClass("selected");
    }

    var numRows = rows.length;
    console.log(
      "Número de filas en la página " + (pageNum + 1) + ": " + numRows
    );
  });

  tabla.on("select deselect", function () {
    var nodes = tabla.rows({ search: "applied" }).nodes();
    if (
      tabla.rows({ selected: true, search: "applied" }).count() !== nodes.length
    ) {
      $("th.select-checkbox").removeClass("selected");
    } else {
      $("th.select-checkbox").addClass("selected");
    }
  });
  tabla.on("search.dt", function () {
    tabla.rows().deselect();
    $("th.select-checkbox").removeClass("selected");
  });
}

function eliminarCliente(id) {
  // funcion que llamamos del archivo ajax/CategoriaAjax.php?op=delete linea 53
  bootbox.confirm(
    "¿Esta Seguro de eliminar el cliente seleccionado?",
    function (result) {
      // confirmamos con una pregunta si queremos eliminar
      if (result) {
        // si el result es true
        $.post("./ajax/ClienteAjax.php?op=delete", { id: id }, function (e) {
          // llamamos la url de eliminar por post. y mandamos por parametro el id
          ListadoCliente();
          swal("Mensaje del Sistema", e, "success");
        });
      }
    }
  );
}
//Datos que se muestran en el ticket
function cargarDataCliente(
  id,
  tipo_persona,
  nombre,
  apellido,
  tipo_documento,
  num_documento,
  direccion_departamento,
  direccion_provincia,
  direccion_distrito,
  direccion_calle,
  telefono,
  telefono_2,
  email,
  numero_cuenta,
  estado,
  idempleado,
  empleado,
  fecha_registro,
  empleado_modificado,
  fecha_modificado,
  genero,
  genero_txt,
  newClasifiacion,
  direccion_referencia,
  ubicacion_factura,
  ubicacion,
  idubicacion_factura,
  idubicacion,
  direccion_referencia_factura,
  direccion_calle_factura,
  direccion_antigua,
  idempleado_asignado,
  empleado_asignado,
  disabled
) {
  $("#txt_empleado_asignado").val(idempleado_asignado);
  $("#txt_idempleado_asignado").val(idempleado_asignado);

  // si tiene la direccion antigua es true , se muestra la direccion antigua
  if (direccion_antigua.length > 0) {
    $("#container_ubicacion_antigua").show();

    if (direccion_antigua == "  ") {
      $("#ubicacion_antigua").html("sin ubicacion");
    } else {
      $("#ubicacion_antigua").html(direccion_antigua);
    }
  }
  //

  $("#btnExtraerClientes").hide();
  $("#button_registrar_nuevo_cliente").show();
  // funcion que llamamos del archivo ajax/CategoriaAjax.php linea 52
  $("#VerForm").show(); // mostramos el formulario
  $("#btnNuevo").hide(); // ocultamos el boton nuevo
  $("#btn_asignar_vendedor").hide(); // ocultamos el boton nuevo
  $("#VerListado").hide();

  $("#txtIdPersona").val(id); // recibimos la variable id a la caja de texto
  $("#cboTipo_Persona").val(tipo_persona);
  $("#txtNombre").val(nombre); // recibimos la variable nombre a la caja de texto txtNombre
  $("#txtApellido").val(apellido); // recibimos la variable apellido a la caja de texto txtApellido
  $("#cboTipo_Documento").val(tipo_documento); // recibimos la variale tipo_documento de sucursal
  $("#cboTipo_Documento_edit").val(tipo_documento);

  $("#txtNum_Documento").val(num_documento);
  $("#txtDireccion_Departamento").val(direccion_departamento);
  $("#txtDireccion_Provincia").val(direccion_provincia);
  $("#txtDireccion_Distrito").val(direccion_distrito);
  $("#txtDireccion_Calle").val(direccion_calle);
  $("#txtTelefono").val(telefono);
  $("#txtTelefono_2").val(telefono_2);
  $("#txtEmail").val(email);
  $("#txtNumero_Cuenta").val(numero_cuenta);
  $("#cboEstado").val(estado);
  $("#txtIdEmpleado").val(idempleado); //Campo empleado ID
  $("#txtEmpleado").val(empleado); //Campo nombre empleado
  $("#txtFecha_registro").val(fecha_registro); //Campo empleado
  //$('#txtIdEmpleado_modificado').val(idempleado_modificado);//Campo  empleado ID
  $("#txtEmpleado_modificado").val(empleado_modificado); //Campo nombre del empleado
  $("#txtFecha_modificado").val(fecha_modificado); //Campo fecha de modificacion empleado

  $("#txtDireccion_Referencia").val(direccion_referencia);

  $("#txtClasificacion").val(newClasifiacion);

  // console.log(tipo_persona)

  // ,ubicacion_factura
  // ,ubicacion
  // ,idubicacion_factura
  // ,idubicacion
  // ,direccion_referencia_factura
  // ,direccion_calle_factura

  if (
    idubicacion == idubicacion_factura &&
    direccion_referencia == direccion_referencia_factura &&
    direccion_calle_factura == direccion_calle
  ) {
    console.log("los datos son los mismos , mantener cerrado el modal");
  } else {
    $("#idIgualAdirecionEnvio").val("False");
    $("#idIgualAdirecionEnvio").prop("checked", false);
    handleClick($("#idIgualAdirecionEnvio"));
  }

  $("#id_ubicacion_array").val(idubicacion);

  $("#id_ubicacion_envio_array").val(idubicacion_factura);

  $("#txt_ubicacion_nuevo").val(ubicacion);

  $("#txt_ubicacion_envio").val(ubicacion_factura);

  $("#txt_direccion_envio").val(direccion_calle_factura);
  $("#txt_direccion_referencia_envio").val(direccion_referencia_factura);

  // if(['FINAL','DISTRIBUIDOR','SUPERDISTRIBUIDOR','REPRESENTANTE'].includes(tipo_persona)){

  // }

  // $("#txtNum_Documento").prop("readonly", true);

  if ($("#hdn_rol_usuario").val() == "S") {
    // SUPERADMIN

    $("#panel_rbg_habilitado").show(200);
    $("#panel_rbg_desabilitado").hide(200);
    if (genero) {
      $("input[name=optionsRadios][value=" + genero + "]").prop(
        "checked",
        true
      );
    }
  } else if ($("#hdn_rol_usuario").val() == "A") {
    // USUARIO / TRABAJADOR

    // $('#cboTipo_Documento').prop('disabled',true)
    // $('#cboTipo_Persona').prop('disabled',true)

    // $('#txt_empleado_asignado').prop("disabled", true);

    $("#panel_rbg_habilitado").hide(200);
    $("#panel_rbg_desabilitado").show(200);

    $("#optionsRadios_edit").val(genero_txt);
    $("#optionsRadios_id_edit").val(genero);

    $("input[name=optionsRadios]").prop("disabled", true);

    if (tipo_documento == "DNI" || tipo_documento == "RUC") {
      $("#txtNombre").prop("readonly", true);
      $("#txtApellido").prop("readonly", true);
      $("#txtNum_Documento").prop("readonly", true);
    } else if (tipo_documento == "PASAPORTE" || tipo_documento == "CE") {
      $("#txtNombre").prop("readonly", false);
      $("#txtApellido").prop("readonly", false);
      $("#txtNum_Documento").prop("readonly", true);
    }
  }

  $(".container_info_filtro").hide();

  if (disabled == "disabled") {
    $("#button_registrar_nuevo_cliente").hide();
    $("input").prop("disabled", true);
    $("select").prop("disabled", true);
  }

  //PROBLEMA CUANDO SE EDITA UN CLIENTE

  // $("#cboTipo_Documento").change(function () {
  //   if ($(this).val() == "DNI" || $(this).val() == "RUC") {
  //     $("#txtNombre").prop("readonly", true);
  //     $("#txtApellido").prop("readonly", true);
  //     $("#txtNum_Documento").prop("readonly", true);
  //   } else if ($(this).val() == "PASAPORTE" || $(this).val() == "CE") {
  //     $("#txtNombre").prop("readonly", false);
  //     $("#txtApellido").prop("readonly", false);
  //     $("#txtNum_Documento").prop("readonly", true);
  //   }
  // });

  if ($("#hdn_rol_usuario").val() == "S") {
    // SUPERADMIN
    //$("#cboTipo_Documento").prop('readonly', false);
    $("#cboTipo_Documento option:not(:selected)").attr("disabled", false);
    /*$('#cboTipo_Documento option[value="DNI"]').attr("disabled", false);
			$('#cboTipo_Documento option[value="RUC"]').attr("disabled", false);
			$('#cboTipo_Documento option[value="PASAPORTE"]').attr("disabled", false);
			$('#cboTipo_Documento option[value="CE"]').attr("disabled", false);*/
  } else if ($("#hdn_rol_usuario").val() == "A") {
    // USUARIO / TRABAJADOR
    //$("#cboTipo_Documento").prop('readonly', true);
    $("#cboTipo_Documento option:not(:selected)").attr("disabled", true);
    //$("#cboTipo_Documento").val(tipo_documento);
    /*$('#cboTipo_Documento option[value="DNI"]').attr("disabled", true);
			$('#cboTipo_Documento option[value="RUC"]').attr("disabled", true);
			$('#cboTipo_Documento option[value="PASAPORTE"]').attr("disabled", true);
			$('#cboTipo_Documento option[value="CE"]').attr("disabled", true);*/
  }
}

function buscarPorNumeroDocumento() {
  $("#cboTipo_Persona").val("");
  $("#txtNumero_Cuenta").val("");
  $("#txtNombre").val("");
  $("#txtApellido").val("");
  $("#cboTipo_Documento").val("");
  $("#cboTipo_Documento_edit").val("");

  $("#optionsRadios").val("");
  $("#txtDireccion_Departamento").val("");
  $("#txtDireccion_Provincia").val("");
  $("#txtDireccion_Distrito").val("");
  $("#txtDireccion_Calle").val("");

  $("#txtDireccion_Referencia").val("");

  $("#txtTelefono").val("");
  $("#txtTelefono_2").val("");
  $("#txtEmail").val("");
  $("#txtEstado").val("");

  $("#txtIdPersona").val("");
  //$("#txtIdEmpleado_modificado").val("");

  //$("#txtEmpleado").val("");
  //$("#txtIdEmpleado_modificado").val("");
  //$("#txtEmpleado_modificado").val("");
  //$("#txtFecha_creacion").val("");
  //$("#txtFecha_modificacion").val("");

  if ($("#txtNum_Documento").val() != "") {
    $(".loading_window").show();
    $.ajax({
      url: "./ajax/ClienteAjax.php?op=buscarClienteSunat",
      dataType: "json",
      data: {
        numerodoc: $("#txtNum_Documento").val(),
        origen: "moduloCliente",
      },
      success: function (rpta) {
        $(".loading_window").hide();
        $("#button_registrar_nuevo_cliente").show();

        switch (rpta["estado"]) {
          case "encontrado":
            //$("input[name=optionsRadios][value=" + genero + "]").prop("checked", true);

            if ($("#txtIdEmpleado").val() == 17) {
              $("#txt_empleado_asignado").prop("disabled", false);
            }

            if (rpta["direccion_antigua"]) {
              if (rpta["direccion_antigua"].length > 0) {
                $("#container_ubicacion_antigua").show();

                if (rpta["direccion_antigua"] == "  ") {
                  $("#ubicacion_antigua").html("sin ubicacion");
                } else {
                  $("#ubicacion_antigua").html(rpta["direccion_antigua"]);
                }
              }
            }

            $("#cboTipo_Documento_edit").val(rpta["tipo_documento"]);

            if ($("#hdn_rol_usuario").val() == "S") {
              // SUPERADMIN

              $("#panel_rbg_habilitado").show(200);
              $("#panel_rbg_desabilitado").hide(200);

              if (rpta["genero"]) {
                $(
                  "input[name=optionsRadios][value=" + rpta["genero"] + "]"
                ).prop("checked", true);
              }

              $("#txtNombre").prop("readonly", false);
              $("#txtApellido").prop("readonly", false);
              $("#txtNum_Documento").prop("readonly", false);

              $("#cboTipo_Documento option:not(:selected)").attr(
                "disabled",
                false
              );
            } else if ($("#hdn_rol_usuario").val() == "A") {
              // USUARIO / TRABAJADOR

              if (rpta["genero"]) {
                $("#panel_rbg_habilitado").hide(200);
                $("#panel_rbg_desabilitado").show(200);
                $("input[name=optionsRadios]").prop("disabled", true);
              }

              // $('#cboTipo_Documento').prop('disabled',true)
              // $('#cboTipo_Persona').prop('disabled',true)

              $("#optionsRadios_edit").val(rpta["genero_txt"]);
              $("#optionsRadios_id_edit").val(rpta["genero"]);
              // $("input[name=optionsRadios]").prop("disabled", true);

              $("#txtNombre").prop("readonly", true);
              $("#txtApellido").prop("readonly", true);
              $("#txtNum_Documento").prop("readonly", true);

              $("#cboTipo_Documento option:not(:selected)").attr(
                "disabled",
                true
              );
            }

            if (rpta["genero"] == 1) {
              $("#optionsRadios1").prop("checked", true);
            } else if (rpta["genero"] == 2) {
              $("#optionsRadios2").prop("checked", true);
            } else if (rpta["genero"] == 3) {
              $("#optionsRadios3").prop("checked", true);
            }

            //alert(rpta["genero"]);

            if (
              rpta["idubicacion"] == rpta["idubicacion_factura"] &&
              rpta["direccion_referencia"] ==
                rpta["direccion_referencia_factura"] &&
              rpta["direccion_calle_factura"] == rpta["direccion_calle"]
            ) {
              console.log(
                "los datos son los mismos , mantener cerrado el modal"
              );
            } else {
              if (
                rpta["response_text"].includes(
                  "se encuentra registrado en el sistema"
                )
              ) {
                $("#idIgualAdirecionEnvio").val("False");
                $("#idIgualAdirecionEnvio").prop("checked", false);
                handleClick($("#idIgualAdirecionEnvio"));
              }
            }

            $("#id_ubicacion_array").val(rpta["idubicacion"]);

            $("#id_ubicacion_envio_array").val(rpta["idubicacion_factura"]);

            $("#txt_ubicacion_nuevo").val(rpta["ubicacion"]);

            $("#txt_ubicacion_envio").val(rpta["ubicacion_factura"]);

            console.log(rpta["idempleado_asignado"]);
            $("#txt_empleado_asignado").val(rpta["idempleado_asignado"]);

            $("#txt_idempleado_asignado").val(rpta["idempleado_asignado"]);

            if ($("#txtIdEmpleado").val() == rpta["idempleado_asignado"]) {
            } else {
              console.log(
                $("#txtIdEmpleado").val(),
                rpta["idempleado_asignado"]
              );
              if (
                $("#txtIdEmpleado").val() == 6 ||
                $("#txtIdEmpleado").val() == 17
              ) {
              } else {
                if (
                  !rpta["response_text"].includes(
                    "encuentra registrado en el sistema"
                  )
                ) {
                  $("#txt_empleado_asignado").val($("#txtEmpleadoNuevo").val());
                } else {
                  $("input").prop("disabled", true);
                  $("#button_registrar_nuevo_cliente").hide();
                }
              }
            }

            $("#txt_direccion_envio").val(rpta["direccion_calle_factura"]);

            $("#txt_direccion_referencia_envio").val(
              rpta["direccion_referencia_factura"]
            );

            //

            //
            //$("#txtIdCliente").val(rpta['idCliente']);
            //alert(rpta['tipo_persona'])
            $("#cboTipo_Persona").val(rpta["tipo_persona"]);
            $("#txtNumero_Cuenta").val(rpta["estadoCuenta"]);
            $("#txtNombre").val(rpta["nombre"]);
            $("#txtApellido").val(rpta["apellido"]);
            $("#cboTipo_Documento").val(rpta["tipo_documento"]);
            $("#optionsRadios").val(rpta["genero"]);
            $("#txtNum_Documento").val($("#txtNum_Documento").val());
            $("#txtDireccion_Departamento").val(rpta["direccion_departamento"]);
            $("#txtDireccion_Provincia").val(rpta["direccion_provincia"]);
            $("#txtDireccion_Distrito").val(rpta["direccion_distrito"]);
            $("#txtDireccion_Calle").val(rpta["direccion_calle"]);
            $("#txtTelefono").val(rpta["telefono"]);
            $("#txtTelefono_2").val(rpta["telefono_2"]);
            $("#txtEmail").val(rpta["email"]);
            $("#txtEstado").val(rpta["estado_cliente"]);

            $("#txtIdPersona").val(rpta["idCliente"]);

            $("#txtDireccion_Referencia").val(rpta["direccion_referencia"]);
            $("#txtClasificacion").val(rpta["clasificacion"]);
            $("#txtIdPersona").val(rpta["idCliente"]);
            //$("#txtIdEmpleado_modificado").val(rpta["idEmpleado_modificado"]);

            // txtIdEmpleado

            /*
										$("#txtCliente").val(rpta['nombre']);
										$("#txtClienteNroDocumento").val("");
										$("#modalBuscarClientes").modal("hide");
										$("#btnEditarCliente").show(200);
										*/
            // alert(rpta["response_text"]);

            swal("Mensaje del Sistema", rpta["response_text"], "success");
            break;
          case "error":
            alert("Ocurrio un error al registrar cliente...");

            $("#txtTelefono_2").val(0);
            swal("Mensaje del Sistema", "error del sistema", "error");
            break;
          case "no_encontrado":
            console.log(434534534534);
            $("#txt_empleado_asignado").val($("#txtEmpleadoNuevo").val());

            // $('#cboTipo_Persona').val('FINAL')

            $("#cboTipo_Persona").val("FINAL");
            $("#txtNumero_Cuenta").val(rpta["estadoCuenta"]);
            $("#txtNombre").val("");
            $("#txtApellido").val("");
            $("#cboTipo_Documento").val("DNI");
            $("#txtNum_Documento").val($("#txtNum_Documento").val());
            $("#optionsRadios").val("");
            $("#txtDireccion_Departamento").val();
            $("#txtDireccion_Provincia").val();
            $("#txtDireccion_Distrito").val();
            $("#txtDireccion_Calle").val();
            $("#txtTelefono").val();
            $("#txtTelefono_2").val();
            $("#txtEmail").val();
            $("#txtEstado").val();
            $("#txtIdPersona").val();

            $("#txtTelefono_2").val(0);
            swal(
              "Mensaje del Sistema",
              `	No se encontraron resultados con el numero de documento ${rpta["numeroDocumento"]}. `,
              "error"
            );

            break;
        }
      },
      error: function (e) {
        console.log(e.responseText);
        $(".loading_window").hide();
      },
    });
  } else {
    alert("Ingresar número de documento válido...");
  }
}

function clasificacion_final(tipo) {
  $.ajax({
    url: "./ajax/ClienteAjax.php?op=cambiarEstadoCliente_final",
    dataType: "json",
    data: {
      tipo: tipo,
    },
    success: function (rpta) {
      if (rpta["result"] == true) {
        alert("Se actualizaron " + rpta["cantidadRegistros"] + " registros.");
      } else if (rpta["result"] == null || rpta["result"] == "null") {
        alert("Sin datos que actualizar...");
      } else {
        alert("Error al actualizar registros...");
      }
    },
    error: function (e) {
      console.log(e.responseText);
    },
  });
}

function clasificacion_distribuidor(tipo) {
  $.ajax({
    url: "./ajax/ClienteAjax.php?op=cambiarEstadoCliente_distribuidor",
    dataType: "json",
    data: {
      tipo: tipo,
    },
    success: function (rpta) {
      if (rpta["result"] == true) {
        alert("Se actualizaron " + rpta["cantidadRegistros"] + " registros.");
      } else if (rpta["result"] == null || rpta["result"] == "null") {
        alert("Sin datos que actualizar...");
      } else {
        alert("Error al actualizar registros...");
      }
    },
    error: function (e) {
      console.log(e.responseText);
    },
  });
}

function clasificacion_representante(tipo) {
  $.ajax({
    url: "./ajax/ClienteAjax.php?op=cambiarEstadoCliente_representante",
    dataType: "json",
    data: {
      tipo: tipo,
    },
    success: function (rpta) {
      if (rpta["result"] == true) {
        alert("Se actualizaron " + rpta["cantidadRegistros"] + " registros.");
      } else if (rpta["result"] == null || rpta["result"] == "null") {
        alert("Sin datos que actualizar...");
      } else {
        alert("Error al actualizar registros...");
      }
    },
    error: function (e) {
      console.log(e.responseText);
    },
  });
}
