$(document).on("ready", init);
var idgestionActivo = "";
//  $(document).ready(function() {

//         });
var visibleColumns = [
  { value: "0", visible: true ,nombre:'#'},
  { value: "1", visible: false ,nombre:'cogido'},
  { value: "2", visible: false ,nombre:'fecha  de ingreso'},
  { value: "3", visible: false ,nombre:'familia  de activos '},
  { value: "4", visible: true ,nombre:'tipo de Equipo'},

  { value: "5", visible: true ,nombre:'Tipo de Activo'},

  { value: "6", visible: true ,nombre:'ubicacion'},
  { value: "7", visible: false ,nombre:'Cantidad'},
  { value: "8", visible: false ,nombre:'Marca'},
  { value: "9", visible: false ,nombre:'Modelo'},
  { value: "10", visible: false ,nombre:'Nro Serie'},
  { value: "11", visible: false ,nombre:'Color'},
  { value: "12", visible: false ,nombre:'Caracteristicas'},
  { value: "13", visible: false ,nombre:'Estado'},
  { value: "14", visible: false ,nombre:'Tipo/Nro. de Documento'},
  { value: "15", visible: false ,nombre:'Precio de Compra'},
  { value: "16", visible: false ,nombre:'Proveedor'},
  { value: "17", visible: true ,nombre:'Asignado'},
  { value: "18", visible: true ,nombre:'Departamento'},

  { value: "19", visible: true ,nombre:'Gestionado Por'},
  { value: "20", visible: true ,nombre:'Opciones'},
];
var tabla;

var ArrayExport;

function init() {
  getTipoActivo()

  function getTipoActivo() {

    $.ajax({
      url: "./ajax/ActivosAjax.php?op=traerTipoActivo",
      dataType: "json",
      type: "get",
      success: function (rpta) {
        console.log(rpta);
        var options_html = '<option value=""></option>';

        rpta.map((e) => {
          options_html += `<option data-id='${e.id}' value='${e.id}'> ${e.descripcion} </option>`;
        });

        $(".tipo_activo_select").html(options_html);
      },
      error: function (e) {
        console.log(e);
      },
    });
  }


  
  function ListadoActivo() {
    let Array = visibleColumns.map((e) => {
      response = {
        mDataProp: e.value,
        visible: e.visible,
      };
      return response;
    });

    ArrayExport = visibleColumns
      .filter((e) => e.visible == true)
      .map((e) =>{
        
        if(e.value!='20'){

          return Number(e.value)}
        }
        );

     
    tabla = $("#tblActivos")
      .dataTable({
        aProcessing: true,
        aServerSide: true,
        dom: "Bfrtip",
        buttons: [
          "copyHtml5",
          {
            extend: "excelHtml5",
            text: "Excel",
            exportOptions: {
              columns: ArrayExport,
            },
          },
          "csvHtml5",
          "pdfHtml5",
        ],
        aoColumns: Array,
        ajax: {
          url: "./ajax/ActivosAjax.php?op=list",
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

  var htmlVisibleColumns = `
  <select
  multiple="multiple"
  name="select_contaimner_select_visible"
  id="select_contaimner_select_visible"
>
    `;
  visibleColumns.map((e) => {
    htmlVisibleColumns += `
    <option   ${e.visible ? "selected" : ""} value="${e.value}">
      ${e.nombre}
    </option>
      `;
  });
  htmlVisibleColumns += `</select>`;
  $("#container_select_visible").html(htmlVisibleColumns);

  if($('#select_contaimner_select_visible').val()){
      $("#select_contaimner_select_visible").multiselect();
  }

  $("#select_contaimner_select_visible").change(function () {
    let valor = $(this).val();

    visibleColumns.map((e) => {
      if (valor.includes(e.value)) {
        e.visible = true;
      } else {
        e.visible = false;
      }
    });

    var data = $("#tblActivos").DataTable();

    visibleColumns.forEach(function (columna) {
      if (columna.visible == true) {
        data.column(columna.value).visible(true);
      } else {
        data.column(columna.value).visible(false);
      }
    });

    ArrayExport = visibleColumns
      .filter((e) => e.visible == true)
      .map((e) =>
      {


        if(e.value!="20"){    
          return Number(e.value)
        }else{


        }
      }
      );

    // Actualizar el botón de Excel con las columnas seleccionadas

    // console.log(tabla.buttons(1))
    let Array = visibleColumns.map((e) => {
      response = {
        mDataProp: e.value,
        visible: e.visible,
      };
      return response;
    });

    tabla = $("#tblActivos")
      .dataTable({
        aProcessing: true,
        aServerSide: true,
        dom: "Bfrtip",
        buttons: [
          "copyHtml5",
          {
            extend: "excelHtml5",
            text: "Excel",
            exportOptions: {
              columns: ArrayExport,
            },
          },
          "csvHtml5",
          "pdfHtml5",
        ],
        aoColumns: Array,
        ajax: {
          url: "./ajax/ActivosAjax.php?op=list",
          type: "get",
          dataType: "json",
          error: function (e) {
            console.log(e.responseText);
          },
        },
        bDestroy: true,
      })
      .DataTable();


  });

  $("#boton_actualizar_ultimo_empleado").hide();
  $("#table_activos_anteriores").hide();
  $("#act_fecha_ingreso ").change(function () {
    var fecha1 = new Date($("#act_fecha_finvida").val());
    var fecha2 = new Date($("#act_fecha_ingreso").val());

    var diferencia = fecha1.getTime() - fecha2.getTime();
    var dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));

    $("#act_dias_restantes").val(dias);
  });

  $("#act_fecha_finvida ").change(function () {
    var fecha1 = new Date($("#act_fecha_finvida").val());
    var fecha2 = new Date($("#act_fecha_ingreso").val());

    var diferencia = fecha1.getTime() - fecha2.getTime();
    var dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));
    $("#act_dias_restantes").val(dias);
  });
  ListadoActivo();
  $("#actualizar_nuevo_articulo_submit").hide();
  // $('.empleadosList').focus(function() {
  //   optionEmpleados();
  // })

  $("#btn_asignar_a_empleado").hide();

  $("#actualizar_boton_asigacion_empleado").click(function () {
    let error = false;

    var idValueObj = {};

    var formData = new FormData();

    var gestion = document.querySelectorAll(".gestion_activo");

    gestion.forEach(function (textarea) {
      if (textarea.value.length == 0) {
        error = true;
      } else {
        idValueObj[textarea.id] = textarea.value;
      }
    });

    for (var key in idValueObj) {
      formData.append(key, idValueObj[key]);
    }

    $.each($("#act_activo_archivo")[0].files, function (i, file) {
      formData.append("fileupload[]", file);
    });

    formData.append("idactivo", $("#act_idactivo").val());

    if (!error) {
      $.ajax({
        url: "./ajax/ActivosAjax.php?op=TrasferirActivo",
        type: "POST",
        data: formData,
        contentType: "application/json",
        contentType: false,
        processData: false,
        success: function (datos) {
          console.log(datos);
          swal("Mensaje del Sistema", datos, "success");

          var tabla = $("#table_activos_anteriores")
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
                url: "./ajax/ActivosAjax.php?op=listaDeEmpleadosPorActivos",
                type: "get",
                dataType: "json",
                data: function (d) {
                  d.id = idActivo;
                },
                error: function (e) {
                  console.log(e.responseText);
                },
              },
              bDestroy: true,
              createdRow: function (row, data, index) {
                if ($(row).find("td:eq(4)").html() == "A") {
                  $(row).find("td").addClass("bg-success");
                }
              },
            })
            .DataTable();
        },

        error: function (datos) {
          console.log(datos);
        },
      });
    } else {
      alert("faltan llenar unos datos");
    }
  });

  $("#actualizar_boton_asigacion_empleado").hide();

  $("#btn_asignar_a_empleado").click(function () {
    $("#actualizar_nuevo_articulo_submit").hide();

    $("#boton_actualizar_ultimo_empleado").hide();

    $(".gestion_activo").each(function () {
      $(this).attr("disabled", false);
      $(this).val("");
    });

    $("#actualizar_boton_asigacion_empleado").show();

    $('input[id^="act_"]').each(function () {
      if (this.className.includes("gestion_activo")) {
      } else {
        $(this).attr("disabled", true);
      }
    });
    $("select, textarea").each(function () {
      if (this.className.includes("gestion_activo")) {
      } else {
        $(this).attr("disabled", true);
      }
    });
  });

  $("#abrir_contanier_insertar_articulo").click(function () {
    // console.log('hola')
    $("#container_insertar_articulo").show();
    $('input[id^="act_"]').each(function () {
      $(this).val("");
    });
    $("select, textarea").each(function () {
      $(this).val("");
    });

    $("#registrar_nuevo_articulo_submit").show();

    $("#actualizar_nuevo_articulo_submit").hide();
  });

  $("#frmActivo").submit(function (e) {
    e.preventDefault();

    var values = [];

    var inputs = document.querySelectorAll("#frmActivo input");
    var textareas = document.querySelectorAll("#frmActivo textarea");
    var select = document.querySelectorAll("#frmActivo select");

    var idValueObj = {};

    inputs.forEach(function (input) {
      idValueObj[input.id] = input.value;
    });
    textareas.forEach(function (textarea) {
      idValueObj[textarea.id] = textarea.value;
    });
    select.forEach(function (textarea) {
      idValueObj[textarea.id] = textarea.value;
    });

    var formData = new FormData();

    $.each($("#act_activo_archivo")[0].files, function (i, file) {
      formData.append("fileupload[]", file);
    });

    for (var key in idValueObj) {
      formData.append(key, idValueObj[key]);
    }
    $("#registrar_nuevo_articulo_submit").prop("disabled", true);
    $("#registrar_nuevo_articulo_submit").html(
      `<i class="fa fa-spinner fa-spin"></i>Registrando`
    );



    $('#registrar_nuevo_articulo_submit').prop('disabled', true);

    if (idValueObj.act_idactivo) {
      console.log("se esta actualizando un articulo");

      $.ajax({
        url: "./ajax/ActivosAjax.php?op=guardarActivo",
        type: "POST",
        data: formData,
        contentType: "application/json",
        contentType: false,
        processData: false,
        success: function (datos) {

          $('#registrar_nuevo_articulo_submit').prop('disabled', false);


          inputs.forEach(function (input) {
            input.value = "";
          });
          textareas.forEach(function (textarea) {
            textarea.value = "";
          });
          select.forEach(function (textarea) {
            textarea.value = "";
          });

          $("#registrar_nuevo_articulo_submit").html(
            `<i class="fa fa-floppy-o"></i>Registrar`
          );
          $("#registrar_nuevo_articulo_submit").prop("disabled", false);
          swal("Mensaje del Sistema", datos, "success");

          $("#VerForm").hide(); // mostramos el formulario
          $("#btnNuevo").show();
          $("#VerListado").show();

          ListadoActivo(); 
        },
      });
    } else {
      $.ajax({
        url: "./ajax/ActivosAjax.php?op=guardarActivo",
        type: "POST",
        data: formData,
        contentType: "application/json",
        contentType: false,
        processData: false,
        success: function (datos) {


          $('#registrar_nuevo_articulo_submit').prop('disabled', false);

          
          console.log("se esta creando un nuevo activo");

          inputs.forEach(function (input) {
            input.value = "";
          });
          textareas.forEach(function (textarea) {
            textarea.value = "";
          });
          select.forEach(function (textarea) {
            textarea.value = "";
          });

          $("#registrar_nuevo_articulo_submit").html(
            `<i class="fa fa-floppy-o"></i>Registrar`
          );
          $("#registrar_nuevo_articulo_submit").prop("disabled", false);

          $("#VerForm").hide(); // mostramos el formulario
          $("#btnNuevo").show();
          $("#VerListado").show();
          ListadoActivo();
          swal("Mensaje del Sistema", datos, "success");
        },
      });
    }
  });

  $("#submit_boton_actualizar_ultimo_empleado").click(function (e) {
    var objet = {
      idempleado: $("#act_idempleado").val(),
      idempleado_uso: $("#act_idempleado_uso").val(),
      area: $("#act_area").val(),
      fecha_asignacion: $("#act_fecha_asignacion").val(),
      idempleado: $("#act_idempleado").val(),
      idubicacion: $("#act_ubicacion").val(),
      idgestionActivo: idgestionActivo,
    };
    var formData = new FormData();

    formData.append("idempleado", $("#act_idempleado").val());
    formData.append("idempleado_uso", $("#act_idempleado_uso").val());
    formData.append("area", $("#act_area").val());
    formData.append("fecha_asignacion", $("#act_fecha_asignacion").val());
    formData.append("idubicacion", $("#act_ubicacion").val());
    formData.append("idgestionActivo", idgestionActivo);

    $.each($("#act_activo_archivo")[0].files, function (i, file) {
      // console.log(file);
      formData.append("fileupload[]", file);
    });

    $.ajax({
      url: "./ajax/ActivosAjax.php?op=actualizar_ultimo_empleado",
      type: "POST",
      data: formData,
      contentType: "application/json",
      contentType: false,
      processData: false,
      success: function (datos) {
        console.log(datos);

        $.ajax({
          url: "./ajax/ActivosAjax.php?op=verArchivosActivos",
          type: "get",
          dataType: "json",
          data: {
            id: idgestionActivo,
          },
          success: function (dataArchivos) {
            var htmldetalleArchivos = "";
            dataArchivos.map(function (dataArchivo) {
              htmldetalleArchivos += `<li>
              <a href="./Files/Activos/${dataArchivo.ruta}" target="_blank">
              <span class="mailbox-attachment-icon has-img">
              <img src="https://img.freepik.com/vector-premium/simbolo-carpeta-icono-carpeta-documentos-ilustracion-vector-plano-aislado-sobre-fondo-blanco_97843-2848.jpg?w=2000">
              </span>
              </a>
              <div class="mailbox-attachment-info">
              <a href="./Files/Activos/${dataArchivo.ruta}" class="mailbox-attachment-name" target="_blank">${dataArchivo.ruta}</a>
              </li>`;
            });

            $("#detalleArchivoActivo").html(htmldetalleArchivos);
          },
          error: function (erro) {
            console.log(erro);
          },
        });

        swal("Mensaje del Sistema", "actulizado correctamente", "success");
        var tabla = $("#table_activos_anteriores")
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
              url: "./ajax/ActivosAjax.php?op=listaDeEmpleadosPorActivos",
              type: "get",
              dataType: "json",
              data: function (d) {
                d.id = idActivo;
              },
              error: function (e) {
                console.log(e.responseText);
              },
            },
            bDestroy: true,
            createdRow: function (row, data, index) {
              if ($(row).find("td:eq(4)").html() == "A") {
                $(row).find("td").addClass("bg-success");
              }
            },
          })
          .DataTable();
      },
      error: function (error) {
        console.log(error);
        swal("Mensaje del Sistema", "actulizado correctamente", "success");

        var tabla = $("#table_activos_anteriores")
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
              url: "./ajax/ActivosAjax.php?op=listaDeEmpleadosPorActivos",
              type: "get",
              dataType: "json",
              data: function (d) {
                d.id = idActivo;
              },
              error: function (e) {
                console.log(e.responseText);
              },
            },
            bDestroy: true,
            createdRow: function (row, data, index) {
              if ($(row).find("td:eq(4)").html() == "A") {
                $(row).find("td").addClass("bg-success");
              }
            },
          })
          .DataTable();
      },
    });
  });
}

function eliminarActivo(id) {
  $.ajax({
    url: "./ajax/ActivosAjax.php?op=EliminarActivo",
    type: "post",
    dataType: "json",
    data: {
      id,
    },
    success: function (data) {
      console.log(data);
    },
  });
}
function cargarDataEmpleadoActivos(
  id,
  apellidos,
  nombre,
  tipo_documento,
  num_documento,
  direccion,
  telefono,
  email,
  fecha_nacimiento,
  foto,
  login,
  clave,
  estado
) {
  var tabla = $("#tblActivosPorEmpleado")
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
        url: "./ajax/ActivosAjax.php?op=listPorEmpelado",
        type: "get",
        dataType: "json",
        data: function (d) {
          d.id = id;
        },
        error: function (e) {
          console.log(e.responseText);
        },
      },
      bDestroy: true,
    })
    .DataTable();

  // funcion que llamamos del archivo ajax/CategoriaAjax.php linea 52
  $("#VerForm").show(); // mostramos el formulario
  $("#btnNuevo").hide();
  $("#VerListado").hide(); // ocultamos el listado

  $("#txtIdEmpleado").val(id); // recibimos la variable id a la caja de texto txtIdMarca

  $("#act_idempleado").val(id);

  $("#txtApellidos").val(`${apellidos}  ${nombre}`);
  $("#txtNombre").val(nombre);
  $("#cboTipo_Documento").val(tipo_documento + " - " + num_documento);
  $("#txtNum_Documento").val(num_documento);
  $("#txtDireccion").val(direccion);
  $("#txtTelefono").val(telefono);
  $("#txtEmail").val(email);
  $("#txtFecha_Nacimiento").val(fecha_nacimiento);
  //$("#txtLogo").val(logo);
  $("#txtRutaImgEmp").val(foto);
  $("#txtLogin").val(login);
  //$("#txtClave").val(clave);
  $("#txtRutaImgEmp").show();
  $("#txtEstado").val(estado);
  $("#txtClaveOtro").val(clave);
  //$("#txtClaveOtro").show();
}
function modicarUltimoUsuarioAsignado(id) {
  $("#actualizar_boton_asigacion_empleado").hide();

  $("#act_idempleado").prop("disabled", false);
  $("#act_idempleado_uso").prop("disabled", false);
  $("#act_area").prop("disabled", false);
  $("#act_fecha_asignacion").prop("disabled", false);
  $("#act_ubicacion").prop("disabled", false);

  $("#act_activo_archivo").prop("disabled", false);

  idgestionActivo = id;
  console.log(id);
  $("#boton_actualizar_ultimo_empleado").show();
}

function verDetallesActivoUnidad(id) {
  $("#btnNuevo").hide();

  idActivo = id;

  // ocultamos el contendedor asignar_activos para insertar una  tabla
  $("#container_asignar_activos").hide();

  $("#table_activos_anteriores").show();

  var tabla = $("#table_activos_anteriores")
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
        { mDataProp: "5", visible: false },
      ],
      ajax: {
        url: "./ajax/ActivosAjax.php?op=listaDeEmpleadosPorActivos",
        type: "get",
        dataType: "json",
        data: function (d) {
          d.id = id;
        },
        error: function (e) {
          console.log(e.responseText);
        },
      },
      bDestroy: true,
      createdRow: function (row, data, index) {
        if ($(row).find("td:eq(4)").html() == "A") {
          $(row).find("td").addClass("bg-success");
        }
      },
    })
    .DataTable();

  //

  $('input[id^="act_"]').each(function () {
    $(this).val("");
  });
  $("select, textarea").each(function () {
    $(this).val("");
  });

  $("#VerForm").show();
  $("#VerListado").hide();

  $.ajax({
    url: "./ajax/ActivosAjax.php?op=verDetallesActivoUnidad",
    type: "get",
    dataType: "json",
    data: {
      id,
    },

    success: function (datos) {
      // var htmldetalleArchivos;
      $.ajax({
        url: "./ajax/ActivosAjax.php?op=verArchivosActivos",
        type: "get",
        dataType: "json",
        data: {
          id: datos.idgestion_activos,
        },
        success: function (dataArchivos) {
          var htmldetalleArchivos = "";
          dataArchivos.map(function (dataArchivo) {
            htmldetalleArchivos += `<li>
            <a href="./Files/Activos/${dataArchivo.ruta}" target="_blank">
            <span class="mailbox-attachment-icon has-img">
            <img src="https://img.freepik.com/vector-premium/simbolo-carpeta-icono-carpeta-documentos-ilustracion-vector-plano-aislado-sobre-fondo-blanco_97843-2848.jpg?w=2000">
            </span>
            </a>
            <div class="mailbox-attachment-info">
            <a href="./Files/Activos/${dataArchivo.ruta}" class="mailbox-attachment-name" target="_blank">${dataArchivo.ruta}</a>
            </li>`;
          });

          $("#detalleArchivoActivo").html(htmldetalleArchivos);
        },
        error: function (erro) {
          console.log(erro);
        },
      });

      $('input[id^="act_"]').each(function () {
        $(this).attr("disabled", true);
      });
      $("select, textarea").each(function () {
        $(this).attr("disabled", true);
      });

      $("#container_insertar_articulo").show();

      $.each(datos, function (key, value) {
        $("#act_" + key).val(value);
      });

      $("#actualizar_nuevo_articulo_submit").hide();

      $("#registrar_nuevo_articulo_submit").hide();
    },
    error: function (e) {
      console.log(e);
    },
  });
}
var idActivo = "";
function ModificarDetallesActivosView(id) {
  idActivo = id;

  $("#btnNuevo").hide();
  $("#table_activos_anteriores").show();

  $('input[id^="act_"]').each(function () {
    $(this).val("");
  });
  $("select, textarea").each(function () {
    $(this).val("");
  });
  $("#VerForm").show();
  $("#VerListado").hide();
  // insertammos  la  tabla  modificar  detalles

  var tabla = $("#table_activos_anteriores")
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
        url: "./ajax/ActivosAjax.php?op=listaDeEmpleadosPorActivos",
        type: "get",
        dataType: "json",
        data: function (d) {
          d.id = id;
        },
        error: function (e) {
          console.log(e.responseText);
        },
      },
      bDestroy: true,
      createdRow: function (row, data, index) {
        if ($(row).find("td:eq(4)").html() == "A") {
          $(row).find("td").addClass("bg-success");
        }
      },
    })
    .DataTable();

  //

  $("#btn_asignar_a_empleado").show();

  $.ajax({
    url: "./ajax/ActivosAjax.php?op=verDetallesActivoUnidad",
    type: "get",
    dataType: "json",

    data: {
      id,
    },

    success: function (datos) {
      console.log(datos);

      $.ajax({
        url: "./ajax/ActivosAjax.php?op=verArchivosActivos",
        type: "get",
        dataType: "json",
        data: {
          id: datos.idgestion_activos,
        },
        success: function (dataArchivos) {
          var htmldetalleArchivos = "";
          dataArchivos.map(function (dataArchivo) {
            htmldetalleArchivos += `<li>
            <a href="./Files/Activos/${dataArchivo.ruta}" target="_blank">
            <span class="mailbox-attachment-icon has-img">
            <img src="https://img.freepik.com/vector-premium/simbolo-carpeta-icono-carpeta-documentos-ilustracion-vector-plano-aislado-sobre-fondo-blanco_97843-2848.jpg?w=2000">
            </span>
            </a>
            <div class="mailbox-attachment-info">
            <a href="./Files/Activos/${dataArchivo.ruta}" class="mailbox-attachment-name" target="_blank">${dataArchivo.ruta}</a>
            </li>`;
          });

          $("#detalleArchivoActivo").html(htmldetalleArchivos);
        },
        error: function (erro) {
          console.log(erro);
        },
      });

      $("#container_insertar_articulo").show();
      $('input[id^="act_"]').each(function () {
        $(this).attr("disabled", false);
      });

      $("select, textarea").each(function () {
        $(this).attr("disabled", false);
      });

      $(".gestion_activo").each(function () {
        $(this).attr("disabled", true);
      });

      $("#act_cantidad").attr("disabled", true);

      $("#registrar_nuevo_articulo_submit").hide();

      $("#actualizar_nuevo_articulo_submit").show();

      $.each(datos, function (key, value) {
        $("#act_" + key).val(value);
      });
    },
  });
}
