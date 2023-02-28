$(document).on("ready", init);// Inciamos el jquery
var email = "";
/* var clicando= false; */
function init(){
    //Ver();
	$('#tblVentaPedido').dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });

/*     
    $("#btn-only1click").click(function() {
        // Si ha sido clicado
        if (clicando){
          // Mostramos que ya se ha clicado, y no puede clicarse de nuevo
          alert( "Que ya he realizado un click." );
        // Si no ha sido clicado
        } else {
          // Le decimos que ha sido clicado
          clicando= true;
          // Mostramos el mensaje de que ha sido clicado
          alert( "Handler for only1click.click() called." );
        }
      }); */

	ListadoVenta();// Ni bien carga la pagina que cargue el metodo

	ComboTipo_Documento();
    $("#VerFormPed").hide();
	$("#VerForm").hide();// Ocultamos el formulario
	$("form#frmVentas").submit(SaveOrUpdate);//VerificarStockProductos  // Evento submit de jquery que llamamos al metodo SaveOrUpdate para poder registrar o modificar datos
	$("#cboTipoComprobante").change(VerNumSerie);
	$("#btnNuevo").click(VerForm);// evento click de jquery que llamamos al metodo VerForm
    $("#btnNuevoPedido").click(VerFormPedido);
    $("form#frmCreditos").submit(SaveCredito);

    function ComboTipo_Documento() {

        $.get("./ajax/PedidoAjax.php?op=listTipoDoc", function(r) {
                $("#cboTipoComprobante").html(r);
        })

    }

    // VERIFICA STOCK DE PRODUTOS DEL DETALLE
    /*
    function VerificarStockProductos(e) {

        e.preventDefault();// para que no se recargue la pagina
        var detalle =  JSON.parse(consultarDet());

        var data = {
            detalle : detalle
        };

        $.get("./ajax/VentaAjax.php?op=VerificarStockProductos",data, function(r) {
                
            if (r == false || r == 'false') {

                alert("No se puede completar el proceso ya que existen productos sin stock...")
                
            } else {
             

            }
        })

    }
    */


	function SaveOrUpdate(e){

		e.preventDefault();// para que no se recargue la pagina
        if ($("#txtSerieVent").val() != "" && $("#txtNumeroVent").val() != "") {

            //alert($("#txtIdPedido").val())

            var detalle =  JSON.parse(consultarDet());
            var data = {

                idCliente : $("#hdn_idClientePedido").val(),
                idUsuario : $("#txtIdUsuario").val(),
                idPedido : $("#txtIdPedido").val(),
                tipo_venta : $("#cboTipoVenta").val(),
                iddetalle_doc_suc : $("#txtIdTipoDoc").val(),
                tipo_comprobante : $("#cboTipoComprobante").val(),
                serie_vent : $("#txtSerieVent").val(),
                num_vent : $("#txtNumeroVent").val(),

                metodo_pago : $("#hdn_metodo_pago").val(), // Cuenta donde es abonada
                agencia_envio : $("#hdn_agencia_envio").val(), // Transporte
                tipo_promocion : $("#hdn_tipo_promocion").val(), // Promociones de ventas
                //num_operacion : $("#txtNumeroOpe").val(), // Comprobantes de pago 
                //hora_operacion : $("#txtHoraOpe").val(),  // Fecha en que se registra la operacion
                
                impuesto : $("#txtImpuesto").val(),
                total_vent : $("#txtTotalVent").val(),
                //idCliente : $("#hdn_idcliente").val(),
                detalle : detalle
            };

            $.get("./ajax/VentaAjax.php?op=VerificarStockProductos",data, function(r) {

                var obj = jQuery.parseJSON(r);           

                if (obj.estado == true || obj.estado == 'true') {

                    $.post("./ajax/VentaAjax.php?op=SaveOrUpdate", data, function(r){// llamamos la url por post. function(r). r-> llamada del callback

                        if ($("#cboTipoComprobante").val() == "TICKET") {
                                //window.open("/Reportes/exTicket.php?id=" + $("#txtIdPedido").val() , "TICKET" , "width=396,height=430,scrollbars=NO");
                               // window.open("localhostReportes/exTicket.php?id=" + $("#txtIdPedido").val());
                                //location.href = "/Reportes/exTicket.php?id=" + $("#txtIdPedido").val();
                            //EN LA WEB 
                            //window.open("/Reportes/exTicket.php?id=" + $("#txtIdPedido").val(), '_blank');
                            window.open("/Reportes/exTicket.php?id=" + $("#txtIdPedido").val(), '_blank');
                        }
        
                        if ($("#cboTipoVenta").val() == "Contado") {
        
                            
                            swal("Mensaje del Sistema", r, "success");
        
                            $("#btnNuevoPedido").show();
                            OcultarForm();
                            ListadoVenta();
                            ListadoPedidos();
                            LimpiarPedido();
        
                            bootbox.prompt({
                              title: "Solo si el cliente lo solicita,ingrese el correo para enviar el detalle de la compra",
                              value: email,
                              callback: function(result) {
                                if (result !== null) {                                             
                                   $.post("./ajax/VentaAjax.php?op=EnviarCorreo", {result:result, idPedido : $("#txtIdPedido").val()}, function(r){
                                      bootbox.alert(r);
                                   })                     
                                }
                              }
                            });
                            //location.reload();
        
                        } else {
        
                            $("#btnNuevoPedido").show();
        
                            bootbox.prompt({
        
                              title: "Ingrese el correo para enviar el detalle de la compra",
                              value: email,
                              callback: function(result) {
                                if (result !== null) {
                                    $.post("./ajax/VentaAjax.php?op=EnviarCorreo", {result:result, idPedido : $("#txtIdPedido").val()}, function(r){
                                      bootbox.alert(r);
                                    }) 
                                    bootbox.alert(r + ", Pasaremos a Registrar el Credito", function() {
                                      $("#modalCredito").modal("show");
                                      GetIdVenta();
                                    });
                                } else {
                                    bootbox.alert(r + ", Pasaremos a Registrar el Credito", function() {
                                      $("#modalCredito").modal("show");
                                      GetIdVenta();
                                    });
                                }
                              }
                            });
                        }
                    });

                }else{

                    var  arr = obj.detalle;
                    bootbox.alert("No se puede completar el proceso ya que existen productos sin stock:\n"+arr.join('\n'))
                }
            })

        } else {
            bootbox.alert("Debe seleccionar un comprobante");
        }
	};

    function SaveCredito(e){

        e.preventDefault();// para que no se recargue la pagina
        $.post("./ajax/CreditoAjax.php?op=SaveOrUpdate", $(this).serialize(), function(r){// llamamos la url por post. function(r). r-> llamada del callback

                swal("Mensaje del Sistema", r, "success");
                $("#modalCredito").modal("hide");
                OcultarForm();
                ListadoVenta();
                ListadoPedidos();
        });
    }

    function GetIdVenta() {

        $.get("./ajax/CreditoAjax.php?op=GetIdVenta", function(r) {
                $("#txtIdVentaCred").val(r);
        })
    }

	function ComboTipoDocumentoS_N() {

        $.get("./ajax/VentaAjax.php?op=listTipo_DocumentoPersona", function(r) {
                $("#cboTipoDocumentoSN").html(r);
        })
    }

    function VerNumSerie(){
    	var nombre = $("#cboTipoComprobante").val();
        var idsucursal = $("#txtIdSucursal").val();
            $.getJSON("./ajax/VentaAjax.php?op=GetTipoDocSerieNum", {nombre: nombre,idsucursal: idsucursal}, function(r) {
                if (r) {
                    $("#txtIdTipoDoc").val(r.iddetalle_documento_sucursal);
                    $("#txtSerieVent").val(r.ultima_serie);
                    $("#txtNumeroVent").val(r.ultimo_numero);
                } else {
                    $("#txtIdTipoDoc").val("");
                	$("#txtSerieVent").val("");
                    $("#txtNumeroVent").val("");
                }
            });
    }

    function VerFormPedido(){
        $("#VerFormPed").show();// Mostramos el formulario
        $("#btnNuevoPedido").hide();// ocultamos el boton nuevo
        $("#btnGenerarVenta").hide();
        $("#VerListado").hide();// ocultamos el listado
        $("#btnReporte").hide();
    }

	function VerForm(){
		$("#VerForm").show();// Mostramos el formulario
		$("#btnNuevo").hide();// ocultamos el boton nuevo
		$("#VerListado").hide();// ocultamos el listado
		$("#btnReporte").hide();
	}

	function OcultarForm(){
		$("#VerForm").hide();// Mostramos el formulario
		$("#VerListado").show();// ocultamos el listado
		$("#btnReporte").show();
        $("#btnNuevo").show();
        $("#VerFormVentaPed").hide();
        $("#btnNuevoVent").show();
       // $("#lblTitlePed").html("Pedidos");
	}

     function LimpiarPedido(){
        $("#txtIdCliente").val("");
        $("#txtCliente").val("");
        $("#cboTipoPedido").val("Pedido");
        $("#txtNumeroPed").val("");
        elementos.length = 0;
        $("#tblDetallePedido tbody").html("");
        $("#cboTipoComprobante").val("--Seleccione Comprobante--");
        $("#txtSerieVent").val("");
        $("#txtNumeroVent").val("");
        GetNextNumero();
    }

    function GetNextNumero() {
        $.getJSON("./ajax/PedidoAjax.php?op=GetNextNumero", function(r) {
                if (r) {
                    $("#txtNumeroPed").val(r.numero);
                }
        });
    }
}

function ListadoPedidos(){ 
    var tabla = $('#tblVentas').dataTable(
    {   "aProcessing": true,
    "aServerSide": true,
    dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ],
    "aoColumns":[
            {   "mDataProp": "0"},
            {   "mDataProp": "1"},
            {   "mDataProp": "2"},
            {   "mDataProp": "3"},
            {   "mDataProp": "4"},
            {   "mDataProp": "5"},
            {   "mDataProp": "6"},
            {   "mDataProp": "7"}
    ],"ajax": 
        {
            url: './ajax/VentaAjax.php?op=list',
            type : "get",
            dataType : "json",
            
            error: function(e){
                console.log(e.responseText);    
            }
        },
    "bDestroy": true

}).DataTable();
};
function ListadoPedidos2(){ 
    
var tabla = $('#tblVentas2').dataTable(
{   "aProcessing": true,
"aServerSide": true,
dom: 'Bfrtip',
    buttons: [
        'copyHtml5',
        'excelHtml5',
        'csvHtml5',
        'pdfHtml5'
    ],
"aoColumns":[
        {   "mDataProp": "0"},
        {   "mDataProp": "1"},
        {   "mDataProp": "2"},
        {   "mDataProp": "3"},
        {   "mDataProp": "4"},
        {   "mDataProp": "5"},
        {   "mDataProp": "6"},
        {   "mDataProp": "7"}

],"ajax": 
    {
        url: './ajax/VentaAjax.php?op=listAdmin',
        type : "get",
        dataType : "json",
        
        error: function(e){
            console.log(e.responseText);    
        }
    },
"bDestroy": true
}).DataTable();
};

function eliminarVenta(id){// funcion que llamamos del archivo ajax/CategoriaAjax.php?op=delete linea 53
	bootbox.confirm("¿Esta Seguro de eliminar el Venta seleccionado?", function(result){ // confirmamos con una pregunta si queremos eliminar
		if(result){// si el result es true
			$.post("./ajax/VentaAjax.php?op=delete", {id : id}, function(e){// llamamos la url de eliminar por post. y mandamos por parametro el id 
				swal("Mensaje del Sistema", e, "success");
				location.reload();
            });
		}
	})
}

function pasarIdPedido(idPedido,total,correo,idcliente,empleado,cliente,num_documento,celular,destino,metodo_pago,agencia_envio,tipo_promocion){// funcion que llamamos del archivo ajax/PedidoAjax.php linea 149

		$("#VerForm").show();// mostramos el formulario
		$("#VerListado").hide();// ocultamos el listado
        $("#btnNuevoPedido").hide();
        $("#VerTotalesDetPedido").hide();
		$("#txtIdPedido").val(idPedido);

        /* $("#txtEjecutivo").val(telefono);
        $("#txtClienteFech").val(APCliente)//.val(APCliente);
        $("#txtNuevo_Antiguo").val(direccion_calle);// MUESTRA DETALLE DE VENTA
        $("#txtTipoCliente").val(num_documento);// MUESTRA DETALLE DE VENTA */

        $("#txtEmpleadoVent").val(empleado)//.Empleado que registro el pedido;
        $("#txtClienteVent").val(cliente)//.falta concatenar nombre y apellido desde js;
        $("#txtClienteDni").val(num_documento);// MUESTRA DETALLE DE VENTA
        $("#txtClienteCel").val(celular);        
        $("#txtClienteEmail").val(correo);// MUESTRA DETALLE DE VENTA
        $("#txtClienteDir").val(destino);// MUESTRA DETALLE DE VENTA

        $("#hdn_idClientePedido").val(idcliente);
        $("#hdn_metodo_pago").val(metodo_pago);
        $("#hdn_agencia_envio").val(agencia_envio);
        $("#hdn_tipo_promocion").val(tipo_promocion);
        $("#txtClientePed").val(metodo_pago);
        
       /*  $("#txtRutaImgVoucher").val(imagen);
	    $("#txtRutaImgVoucher").show(); */

		$("#txtTotalVent").val(total);
        email = correo;
        AgregatStockCant(idPedido);
        CargarDetallePedido(idPedido);
        var igvPed=total * parseInt($("#txtImpuesto").val())/(100+parseInt($("#txtImpuesto").val()));
        $("#txtIgvPed").val(Math.round(igvPed*100)/100);
        var subTotalPed=total - (total * parseInt($("#txtImpuesto").val())/(100+parseInt($("#txtImpuesto").val())));
        $("#txtSubTotalPed").val(Math.round(subTotalPed*100)/100);
        $("#txtTotalPed").val(Math.round(total*100)/100);

        // CARGA DETALLE DE IMAGENES 
        mostrarDetalleImagenes(idPedido);
 	}

function mostrarDetalleImagenes(idPedido) {
        $("#detalleImagenes").html("");
        $.post("./ajax/PedidoAjax.php?op=GetImagenes",{idPedido: idPedido}, function(r){
            if (r != "") {
                $("#detalleImagenes").html(r);
            } else {
                $("#detalleImagenes").html("Sin datos que mostrar...");
            }

        });
    }