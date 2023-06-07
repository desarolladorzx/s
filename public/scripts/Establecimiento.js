$(document).on("ready", init); // Inciamos el jquery

function traerDatosTipoEstablecimiento() {



  $.ajax({
    url: "./ajax/EstablecimientoAjax.php?op=TraerDatosCategoria_empresa",
    dataType: "json",
    type: "get",
    success: function (rpta) {
      console.log(rpta);
      var options_html = '<option value=""></option>';

      rpta.map((e) => {
        options_html += `<option data-id='${e.idcategoria_empresa}'value='${e.idcategoria_empresa}'> ${e.descripcion} </option>`;
      });

      $(".tipo_establecimiento_select").html(options_html);
    },
    error: function (e) {
      console.log(e);
    },
  });
}

function traerDatosRolVendedor() {
  $.ajax({
    url: "./ajax/EstablecimientoAjax.php?op=traerDatosRolVendedor",
    dataType: "json",
    type: "get",
    success: function (rpta) {
      console.log(rpta);
      var options_html = '<option value=""></option>';

      rpta.map((e) => {
        if ($("#idempleado_g").val() == e.idempleado) {
          options_html += `<option selected data-id='${e.idempleado}'value='${e.idempleado}'> ${e.apellidos}${e.nombre} </option>`;
        } else {
          options_html += `<option data-id='${e.idempleado}'value='${e.idempleado}'> ${e.apellidos}${e.nombre} </option>`;
        }
      });


      
      if($('#idrol_g').val()==7){
        $('#txtEmpleadoAsignado').prop('disabled',true)
      }
      $(".tipo_empleadoAsignadoEstablecimiento").html(options_html);
    },
    error: function (e) {
      console.log(e);
    },
  });
}

function init() {


  $('.horaInicio ').val('09:00');
  $('.horaFin').val('20:30');


  traerDatosRolVendedor();
  traerDatosTipoEstablecimiento();

  if($('#idrol_g').val()==7){
    $('#txt_verificacion').prop('disabled',true)
    $('#txt_verificacion').val('SIN VERIFICAR')
  }


  $("#tipo_de_establecimiento_select").on("change", function () {
    var valorSeleccionado = this.value;
    tablaEstablecimiento.column(8).search(valorSeleccionado).draw();

    let valoresFiltrados = tablaEstablecimiento
      .rows({ search: "applied" })
      .data()
      .toArray().length;

    $("#txt_resultados_busquedas").val(valoresFiltrados);
  });

  $("#txt_ubicacionSelect").on("change", function () {
    var valorSeleccionado = this.value;

    tablaEstablecimiento.column(1).search("^" + valorSeleccionado + "$", true, false).draw();
  
    let valoresFiltrados = tablaEstablecimiento
      .rows({ search: "applied" })
      .data()
      .toArray().length;
  
    $("#txt_resultados_busquedas").val(valoresFiltrados);
  });

  $("#tblEstablecimientos").dataTable({
    dom: "Bfrtip",
    buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
  });

  ListadoEstablecimientos(); // Ni bien carga la pagina que cargue el metodo

  ComboTipo_Documento();
  $("#VerForm").hide(); // Ocultamos el formulario
  $("form#frmEstablecimiento").submit(SaveOrUpdate); // Evento submit de jquery que llamamos al metodo SaveOrUpdate para poder registrar o modificar datos

  $("#btn_todos_eliminar_imagen").hide();

  $("#btn_todos_eliminar_imagen").click(function () {
    $("#image-preview-container").html("");
    // $("input[type='file']").val("");
  });

  $("#txtFileFotografia").change(function () {
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

  $("#txt_ubicacion_establecimiento").on("blur", function () {
    var optionValues = $(".ubicacion_containe_options option")
      .map(function () {
        return $(this).val();
      })
      .get();
    if ($.inArray($(this).val(), optionValues) === -1) {
      $(this).val("");
    }
  });

  $("#txt_ubicacion_establecimiento").change(function (e) {
    const selectedOption = $(
      `#ubicacion option[value="${$("#txt_ubicacion_establecimiento").val()}"`
    );
    // const selectedNumber = selectedOption.data('number');
    $("#id_ubicacion_envio_array").val(selectedOption.data("id"));
  });

  $("#btnNuevoEstablecimiento").click(VerFormEstablecimiento); // evento click de jquery que llamamos al metodo VerForm

  function SaveOrUpdate(e) {
    e.preventDefault();


    $('#txt_verificacion').prop('disabled',false)

    $('#txtEmpleadoAsignado').prop('disabled',false)
    var formData = new FormData($("#frmEstablecimiento")[0]);
    $('#txtEmpleadoAsignado').prop('disabled',true)

    $('#txt_verificacion').prop('disabled',true)


    $.each($("#txtFileFotografia")[0].files, function (i, file) {
      formData.append("fileupload[]", file);
    });

    $.ajax({
      url: "./ajax/EstablecimientoAjax.php?op=SaveOrUpdate",

      type: "POST",

      data: formData,

      contentType: false,

      processData: false,

      success: function (datos) {
        swal("Mensaje del Sistema", datos, "success");
        ListadoEstablecimientos();
        OcultarForm();
        $("input").val("");
        $("select").val("");
        $("#image-preview-container").html("");

        $("#container_select_button").show();
      },
    });
  }

  function Limpiar() {
    // Limpiamos las cajas de texto
    $("#txtIdEstablecimiento").val("");
    $("#txtDescripcion").val("");
  }

  function VerFormEstablecimiento() {
    $("#VerForm").show(); // Mostramos el formulario
    $("#btnNuevoEstablecimiento").hide(); // ocultamos el boton nuevo
    $("#VerListado").hide();

    $("input").prop("required", false);
    $("select").prop("required", false);

    // $('#txtFileFotografia').prop('required',true)
    $("#container_select_button").hide();
  }

  function OcultarForm() {
    $("#VerForm").hide(); // Mostramos el formulario
    $("#btnNuevoEstablecimiento").show(); // ocultamos el boton nuevo
    $("#VerListado").show();
  }
}
var tablaEstablecimiento;
function ListadoEstablecimientos() {
  tablaEstablecimiento = $("#tblEstablecimientos")
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
        { mDataProp: "9", visible: false },
      ],
      ajax: {
        url: "./ajax/EstablecimientoAjax.php?op=list",
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

function eliminarEstablecimiento(id) {
  // funcion que llamamos del archivo ajax/CategoriaAjax.php?op=delete linea 53
  bootbox.confirm("¿Esta Seguro de eliminar la Sucursal?", function (result) {
    // confirmamos con una pregunta si queremos eliminar
    if (result) {
      // si el result es true
      $.post(
        "./ajax/EstablecimientoAjax.php?op=delete",
        { id: id },
        function (e) {
          // llamamos la url de eliminar por post. y mandamos por parametro el id
          swal("Mensaje del Sistema", e, "success");
          ListadoEstablecimientos();
        }
      );
    }
  });
}

function mostrarImagenes(idempresa) {
  $("#container_img_empresa").html("");

  $.post(
    "./ajax/EstablecimientoAjax.php?op=GetImagenes",
    {
      idempresa: idempresa,
    },
    function (r) {
      // console.log(r);
      if (r != "") {
        $("#container_img_empresa").html(r);
      } else {
        $("#container_img_empresa").html("Sin datos que mostrar...");
      }
    }
  );
}

function cargarDataEstablecimiento(id, editable) {
  $.ajax({
    url: "./ajax/EstablecimientoAjax.php?op=cargarDatos",
    data: {
      id,
    },
    dataType: "json",

    type: "GET",
    success: function (empresa) {
      $("input").prop("required", true);
      $("select").prop("required", true);

      $("#hor_ini_lunes").val(empresa.hor_ini_lunes);
      $("#hor_ini_martes").val(empresa.hor_ini_martes);
      $("#hor_ini_miercoles").val(empresa.hor_ini_miercoles);
      $("#hor_ini_jueves").val(empresa.hor_ini_jueves);
      $("#hor_ini_viernes").val(empresa.hor_ini_viernes);
      $("#hor_ini_sabado").val(empresa.hor_ini_sabado);
      $("#hor_ini_domingo").val(empresa.hor_ini_domingo);
      $("#hor_fin_lunes").val(empresa.hor_fin_lunes);
      $("#hor_fin_martes").val(empresa.hor_fin_martes);
      $("#hor_fin_miercoles").val(empresa.hor_fin_miercoles);
      $("#hor_fin_jueves").val(empresa.hor_fin_jueves);
      $("#hor_fin_viernes").val(empresa.hor_fin_viernes);
      $("#hor_fin_sabado").val(empresa.hor_fin_sabado);
      $("#hor_fin_domingo").val(empresa.hor_fin_domingo);

      $("#txtFileFotografia").prop("required", false);

      $("#container_select_button").hide();

      $("#idEstablecimiento").val(empresa.idempresa);

      $("#id_ubicacion_envio_array").val(empresa.idubicacion);

      $("#txt_ubicacion_establecimiento").val(empresa.ubicacion);

      $("#txtTipoEstablecimiento").val(empresa.idcategoria_empresa);

      $("#txtNombreEstablecimiento").val(empresa.razon_comercial);

      $("#txtDireccionEstablecimiento").val(empresa.direccion);
      $("#txtHorario").val(empresa.horario);

      $("#txtNombre").val(empresa.nombre);
      $("#txtTelefono").val(empresa.telefono);

      mostrarImagenes(empresa.idempresa);

      if (editable == 0) {
        $("input").prop("disabled", true);
        $("select").prop("disabled", true);
        $("#btnRegistrar_Establecimiento").hide();
      }
    },
  });

  // funcion que llamamos del archivo ajax/CategoriaAjax.php linea 52
  $("#VerForm").show(); // mostramos el formulario
  $("#btnNuevoEstablecimiento").hide(); // ocultamos el boton nuevo
  $("#VerListado").hide(); // ocultamos el listado
}
