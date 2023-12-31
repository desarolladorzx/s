$(document).on("ready", init); // Inciamos el jquery



function init() {

  // console.log('hola tardo demacido')


  $('#idListCategoria').focus(function() {
    TraerCategoria()
  })

  $('#idListProveedor').focus(function() {
    TraerProveedor()
  })


	function TraerCategoria(){
		$.ajax({
			url: "./ajax/ConsultasComprasAjax.php?op=TraerCategoria",
			processData: false,
			contentType: false,
			type: "GET",
			success: function (data) {
			let response=JSON.parse(data)
			
			let textValueCategoria='<option value=""></option>';
			  response.map(e=>{
				textValueCategoria+=`                       <option ${e.idpersona}>${e.nombre} </option>`
			  })

			  $('#idListCategoria').html(textValueCategoria)

			}
		  });
	}

	function TraerProveedor(){
		$.ajax({
			url: "./ajax/ConsultasComprasAjax.php?op=TraerProveedor",
			processData: false,
			contentType: false,
			type: "GET",
			success: function (data) {
				let response=JSON.parse(data)
			
				let textValueCategoria='<option value=""></option>';
				  response.map(e=>{
					textValueCategoria+=`<option ${e.idcategoria}>${e.nombre} ${e.apellido}</option>`
				  })

				$('#idListProveedor').html(textValueCategoria)
			},
		  });
	}

  $("#tblCompraFechas").dataTable({
    dom: "Bfrtip",
    buttons: ["excelHtml5"],
  });
  $("#tblComprasDetalladas").dataTable({
    dom: "Bfrtip",
    buttons: ["excelHtml5"],
  });
  $("#tblComprasProveedor").dataTable({
    dom: "Bfrtip",
    buttons: ["excelHtml5"],
  });
  $("#tblComprasDetProveedor").dataTable({
    dom: "Bfrtip",
    buttons: ["excelHtml5"],
  });

  var tabla = $("#tblStockArticulos").dataTable({
    dom: "Bfrtip",
    buttons: ["excelHtml5"],
  });

  var tablaKV = $("#tblKardexValorizado").dataTable({
    dom: "Bfrtip",
    buttons: ["excelHtml5"],
  });

  var tabla = $("#tblSalidaArticulos").dataTable({
    dom: "Bfrtip",
    buttons: ["excelHtml5"],
  });

  ListadoKardexValorizado();
  ListadoStockArticulos();
  ListadoSalidaArticulos();

  $("#btnFiltrarSA").click(ListadoStockArticulos);
  $("#btnFiltrarKV").click(ListadoKardexValorizado);

  $("#cboFechaDesde").change(ListadoComprasFechas);
  $("#cboFechaHasta").change(ListadoComprasFechas);

  $("#idListCategoria").change(ListadoComprasFechas);

  $("#idListProveedor").change(ListadoComprasFechas);



  $("#cboFechaDesdeDet").change(ListadoComprasDetalladas);
  $("#cboFechaHastaDet").change(ListadoComprasDetalladas);


  $("#idListCategoria").change(ListadoComprasDetalladas);

  $("#idListProveedor").change(ListadoComprasDetalladas);

  $("#txtIdProveedor").change(ListadoComprasProveedor);
  $("#cboFechaDesdeProv").change(ListadoComprasProveedor);
  $("#cboFechaHastaProv").change(ListadoComprasProveedor);

  $("#txtIdProveedor").change(ListadoComprasDetProveedor);
  $("#cboFechaDesdeDetProv").change(ListadoComprasDetProveedor);
  $("#cboFechaHastaDetProv").change(ListadoComprasDetProveedor);
}
function ListadoKardexValorizado() {
  var idsucursal = $("#txtIdSucursal").val();
  var tabla = $("#tblKardexValorizado")
    .dataTable({
      aProcessing: true,
      aServerSide: true,
      dom: "Bfrtip",
      buttons: ["excelHtml5"],
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
        url: "./ajax/ConsultasComprasAjax.php?op=listKardexValorizado",
        type: "get",
        data: { idsucursal: idsucursal },
        dataType: "json",

        error: function (e) {
          console.log(e.responseText);
        },
      },
      bDestroy: true,
    })
    .DataTable();
}

function ListadoStockArticulos() {
  var idsucursal = $("#txtIdSucursal").val();

  var tabla = $("#tblStockArticulos")
    .dataTable({
      aProcessing: true,
      aServerSide: true,
      dom: "Bfrtip",
      buttons: ["excelHtml5"],
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
      ],
      ajax: {
        url: "./ajax/ConsultasComprasAjax.php?op=listStockArticulos",
        type: "get",
        data: { idsucursal: idsucursal },
        dataType: "json",

        error: function (e) {
          console.log(e.responseText);
        },
      },
      bDestroy: true,
    })
    .DataTable();
}
function ListadoSalidaArticulos() {
  var idsucursal = $("#txtIdSucursal").val();
  var tabla = $("#tblSalidaArticulos")
    .dataTable({
      aProcessing: true,
      aServerSide: true,
      pageLength: 30,
      iDisplayLength: 30, //Cantidad de filas a mostrar en las salidas
      dom: "Bfrtip",
      buttons: ["excelHtml5"],
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
      ],
      ajax: {
        url: "./ajax/ConsultasComprasAjax.php?op=listSalidaArticulos",
        type: "get",
        data: { idsucursal: idsucursal },
        dataType: "json",

        error: function (e) {
          console.log(e.responseText);
        },
      },
      bDestroy: true,
    })
    .DataTable();
}

function ListadoComprasFechas() {
  if ($("#cboFechaDesde").val() != "" && $("#cboFechaHasta").val() != "") {
    var fecha_desde = $("#cboFechaDesde").val(),
      fecha_hasta = $("#cboFechaHasta").val(),
      idsucursal = $("#txtIdSucursal").val();

    var tabla = $("#tblCompraFechas")
      .dataTable({
        aProcessing: true,
        aServerSide: true,
        dom: "Bfrtip",
        buttons: ["excelHtml5"],
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
        ],
        ajax: {
          url: "./ajax/ConsultasComprasAjax.php?op=listComprasFechas",
          type: "get",
          data: {
            fecha_desde: fecha_desde,
            fecha_hasta: fecha_hasta,
			categoria:$('#idListCategoria').val(),
			proveedor:$('#idListProveedor').val(),
            idsucursal: idsucursal,
          },
          dataType: "json",

          error: function (e) {
            console.log(e.responseText);
          },
        },
        bDestroy: true,
      })
      .DataTable();
  }
}

function ListadoComprasDetalladas() {
  if (
    $("#cboFechaDesdeDet").val() != "" &&
    $("#cboFechaHastaDet").val() != ""
  ) {
    var fecha_desde = $("#cboFechaDesdeDet").val(),
      fecha_hasta = $("#cboFechaHastaDet").val(),
      idsucursal = $("#txtIdSucursal").val();

    var tabla = $("#tblComprasDetalladas")
      .dataTable({
        aProcessing: true,
        aServerSide: true,
        dom: "Bfrtip",
        buttons: ["excelHtml5"],
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
          // {   "mDataProp": "15"}
        ],
        ajax: {
          url: "./ajax/ConsultasComprasAjax.php?op=listComprasDetalladas",
          type: "get",
          data: {
            fecha_desde: fecha_desde,
            fecha_hasta: fecha_hasta,
            idsucursal: idsucursal,
			categoria:$('#idListCategoria').val(),
			proveedor:$('#idListProveedor').val(),

          },
          dataType: "json",

          error: function (e) {
            console.log(e.responseText);
          },
        },
        bDestroy: true,
      })
      .DataTable();
  }
}

function ListadoComprasProveedor() {
  if (
    $("#cboFechaDesdeProv").val() != "" &&
    $("#cboFechaHastaProv").val() != "" &&
    $("#txtIdProveedor").val() != ""
  ) {
    var idProveedor = $("#txtIdProveedor").val(),
      fecha_desde = $("#cboFechaDesdeProv").val(),
      fecha_hasta = $("#cboFechaHastaProv").val(),
      idsucursal = $("#txtIdSucursal").val();

    var tabla = $("#tblComprasProveedor")
      .dataTable({
        aProcessing: true,
        aServerSide: true,
        dom: "Bfrtip",
        buttons: ["excelHtml5"],
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
          url: "./ajax/ConsultasComprasAjax.php?op=listComprasProveedor",
          type: "get",
          data: {
            idProveedor: idProveedor,
            fecha_desde: fecha_desde,
            fecha_hasta: fecha_hasta,
            idsucursal: idsucursal,
          },
          dataType: "json",

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
        buttons: ["excelHtml5"],
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
          data: {
            idProveedor: idProveedor,
            fecha_desde: fecha_desde,
            fecha_hasta: fecha_hasta,
            idsucursal: idsucursal,
          },
          dataType: "json",
          error: function (e) {
            console.log(e.responseText);
          },
        },
        bDestroy: true,
      })
      .DataTable();
  }
}
