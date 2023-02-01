<?php
	session_start();
	require_once "../model/ConsultasVentas.php";
	$objCategoria = new ConsultasVentas();
	switch ($_GET["op"]) {

          case "listVentasFechas":
               $fecha_desde = $_REQUEST["fecha_desde"];
               $fecha_hasta = $_REQUEST["fecha_hasta"];
               $idsucursal = $_REQUEST["idsucursal"];
               $data = Array();
               $query_Tipo = $objCategoria->ListarVentasFechas($idsucursal, $fecha_desde, $fecha_hasta);
               while ($reg = $query_Tipo->fetch_object()) {

                    $data[] = array(
                         "0"=>$reg->fecha,
                         "1"=>$reg->empleado,
                         "2"=>$reg->nuevo_antiguo,
                         "3"=>$reg->tipo_cliente,
                         "4"=>$reg->cliente,
                         "5"=>$reg->dni,
                         "6"=>$reg->celular.' - '.$reg->telefono_2,
                         "7"=>$reg->ticket,
                         "8"=>$reg->departamento,
                         "9"=>$reg->transporte,
                         "10"=>$reg->cuenta_abonada,
/*                          "10"=>$reg->num_ope,
                         "11"=>$reg->fecha_operacion, */
                         "11"=>$reg->total,
                         "12"=>'<button class="btn btn-success" data-toggle="tooltip" title="Ver Detalle" onclick="cargarDataPedido('.$reg->idpedido.',\''.$reg->tipo_pedido.'\',\''.$reg->numero.'\',\''.$reg->cliente.'\',\''.$reg->total.'\')" ><i class="fa fa-eye"></i> </button>&nbsp'.
                    '<a href="./Reportes/exVenta.php?id='.$reg->idpedido.'" class="btn btn-primary" data-toggle="tooltip" title="Imprimir" target="blanck" ><i class="fa fa-file-text"></i> </a>&nbsp;'
                    );
               }

               $results = array(
               "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);
               break;

          case "listVentasDetalladas":
               $fecha_desde = $_REQUEST["fecha_desde"];
               $fecha_hasta = $_REQUEST["fecha_hasta"];
               $idsucursal = $_REQUEST["idsucursal"];
               $data = Array();
               $query_Tipo = $objCategoria->ListarVentasDetalladas($idsucursal, $fecha_desde, $fecha_hasta);

               while ($reg = $query_Tipo->fetch_object()) {

                    $data[] = array(
                         "0"=>$reg->sucursal,
                         "1"=>$reg->fecha,
                         "2"=>$reg->empleado,
                         "3"=>$reg->antiguedad,
                         "4"=>$reg->cliente,
                         //"3"=>$reg->serie,                         
                         "5"=>$reg->serie.'-'.$reg->numero,
                         //"5"=>$reg->impuesto,
                         "6"=>$reg->categoria,
                         "7"=>$reg->articulo,
                         "8"=>$reg->marca,
                         "9"=>$reg->codigo,
                         "10"=>$reg->serie_art,
                         "11"=>$reg->cantidad,
                         "12"=>$reg->precio_venta,
                         "13"=>$reg->descuento,
                         "14"=>$reg->venta_unitario,
                         "15"=>$reg->total,
                         "16"=>$reg->costo,
                         "17"=>$reg->costo_total,
                         "18"=>$reg->ganancia,
                         "19"=>$reg->tipo_cliente,
                         "20"=>$reg->promocion,
                         "21"=>$reg->departamento,
                         "22"=>$reg->distrito,
                         "23"=>$reg->banco_abono,
                    );
               }
               $results = array(
               "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);
               break;

          case "listVentasAnuladas":
               $fecha_desde = $_REQUEST["fecha_desde"];
               $fecha_hasta = $_REQUEST["fecha_hasta"];
               $idsucursal = $_REQUEST["idsucursal"];
               $data = Array();
               $query_Tipo = $objCategoria->ListarVentasAnuladas($idsucursal, $fecha_desde, $fecha_hasta);
               while ($reg = $query_Tipo->fetch_object()) {

                    $data[] = array(
                         "0"=>$reg->fecha,
                         "1"=>$reg->sucursal,
                         "2"=>$reg->empleado,
                         "3"=>$reg->cliente,
                         "4"=>$reg->dni,
                         "5"=>$reg->celular,
                         "6"=>$reg->ticket,
                         "7"=>$reg->departamento,
                         "8"=>$reg->transporte,
                         "9"=>$reg->cuenta_abonada,
                        /* "11"=>$reg->fecha_operacion, */
                         "10"=>$reg->total,
                         "11"=>($reg->estado=="A")?'<span class="badge bg-green">ACEPTADO</span>':'<span class="badge bg-red">CANCELADO</span>',
                         "12"=>'<button class="btn btn-success" data-toggle="tooltip" title="Ver Detalle" onclick="cargarDataPedido('.$reg->idpedido.',\''.$reg->tipo_pedido.'\',\''.$reg->numero.'\',\''.$reg->cliente.'\',\''.$reg->total.'\')" ><i class="fa fa-eye"></i> </button>&nbsp'.
                    '<a href="./Reportes/exVenta.php?id='.$reg->idpedido.'" class="btn btn-primary" data-toggle="tooltip" title="Imprimir" target="blanck" ><i class="fa fa-file-text"></i> </a>&nbsp;'
                    );
               }

               $results = array(
               "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);
               break;
          
          case "listVentasPendientes":
               $fecha_desde = $_REQUEST["fecha_desde"];
               $fecha_hasta = $_REQUEST["fecha_hasta"];
               $idsucursal = $_REQUEST["idsucursal"];
               $data = Array();
               $query_Tipo = $objCategoria->ListarVentasPendientes($idsucursal, $fecha_desde, $fecha_hasta);
               while ($reg = $query_Tipo->fetch_object()) {
                    $data[] = array(
                         "0"=>$reg->fecha,
                         "1"=>$reg->sucursal,
                         "2"=>$reg->empleado,
                         "3"=>$reg->cliente,
                         "4"=>$reg->comprobante,
                         "5"=>$reg->serie,
                         "6"=>$reg->numero,
                         "7"=>$reg->impuesto,
                         "8"=>$reg->subtotal,
                         "9"=>$reg->totalimpuesto,
                         "10"=>$reg->totalpagar,
                         "11"=>$reg->totalpagado,
                         "12"=>$reg->totaldeuda
                    );
               }
               $results = array(
               "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);
               break;

          case "listVentasContado":
               $fecha_desde = $_REQUEST["fecha_desde"];
               $fecha_hasta = $_REQUEST["fecha_hasta"];
               $idsucursal = $_REQUEST["idsucursal"];
               $data = Array();
               $query_Tipo = $objCategoria->ListarVentasContado($idsucursal, $fecha_desde, $fecha_hasta);
               while ($reg = $query_Tipo->fetch_object()) {
                    $data[] = array(
                         "0"=>$reg->fecha,
                         "1"=>$reg->sucursal,
                         "2"=>$reg->empleado,
                         "3"=>$reg->cliente,
                         "4"=>$reg->comprobante,
                         "5"=>$reg->serie,
                         "6"=>$reg->numero,
                         "7"=>$reg->impuesto,
                         "8"=>$reg->subtotal,
                         "9"=>$reg->totalimpuesto,
                         "10"=>$reg->total
                    );
               }
               $results = array(
               "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);
               break;

          case "listVentasCredito":

               $fecha_desde = $_REQUEST["fecha_desde"];
               $fecha_hasta = $_REQUEST["fecha_hasta"];
               $idsucursal = $_REQUEST["idsucursal"];
               $data = Array();
               $query_Tipo = $objCategoria->ListarVentasCredito($idsucursal, $fecha_desde, $fecha_hasta);

               while ($reg = $query_Tipo->fetch_object()) {

                    $data[] = array(
                         "0"=>$reg->fecha,
                         "1"=>$reg->sucursal,
                         "2"=>$reg->empleado,
                         "3"=>$reg->cliente,
                         "4"=>$reg->comprobante,
                         "5"=>$reg->serie,
                         "6"=>$reg->numero,
                         "7"=>$reg->impuesto,
                         "8"=>$reg->subtotal,
                         "9"=>$reg->totalimpuesto,
                         "10"=>$reg->totalpagar,
                         "11"=>$reg->totalpagado,
                         "12"=>$reg->totaldeuda
                    );
               }
                $results = array(
               "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);
               break;

          case "listVentasCliente":
               $idCliente = $_REQUEST["idCliente"];
               $fecha_desde = $_REQUEST["fecha_desde"];
               $fecha_hasta = $_REQUEST["fecha_hasta"];
               $idsucursal = $_REQUEST["idsucursal"];
               $data= Array();
               $query_Tipo = $objCategoria->ListarVentasCliente($idsucursal, $idCliente, $fecha_desde, $fecha_hasta);
               while ($reg = $query_Tipo->fetch_object()) {
                    $data[] = array(
                         "0"=>$reg->fecha,
                         "1"=>$reg->sucursal,
                         "2"=>$reg->empleado,
                         "3"=>$reg->cliente,
                         "4"=>$reg->dni,
                         "5"=>$reg->celular.' - '.$reg->telefono_2,
                         "6"=>$reg->ticket,
                         "7"=>$reg->departamento,
                         "8"=>$reg->transporte,
                         "9"=>$reg->cuenta_abonada,
/*                          "10"=>$reg->num_ope,
                         "11"=>$reg->fecha_operacion, */
                         "10"=>$reg->total,
                         "11"=>'<button class="btn btn-success" data-toggle="tooltip" title="Ver Detalle" onclick="cargarDataPedido('.$reg->idpedido.',\''.$reg->tipo_pedido.'\',\''.$reg->numero.'\',\''.$reg->cliente.'\',\''.$reg->total.'\')" ><i class="fa fa-eye"></i> </button>&nbsp'.
                    '<a href="./Reportes/exVenta.php?id='.$reg->idpedido.'" class="btn btn-primary" data-toggle="tooltip" title="Imprimir" target="blanck" ><i class="fa fa-file-text"></i> </a>&nbsp;'
                    );
               }
               $results = array(
               "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);
               break;

          case "listComprasDetProveedor":
               $idProveedor = $_REQUEST["idProveedor"];
               $fecha_desde = $_REQUEST["fecha_desde"];
               $fecha_hasta = $_REQUEST["fecha_hasta"];
               $idsucursal = $_REQUEST["idsucursal"];
               $data= Array();
               $query_Tipo = $objCategoria->ListarComprasDetProveedor($idsucursal, $idProveedor, $fecha_desde, $fecha_hasta);
              
               while ($reg = $query_Tipo->fetch_object()) {
                    $data[] = array(
                         "0"=>$reg->fecha,
                         "1"=>$reg->sucursal,
                         "2"=>$reg->empleado,
                         "3"=>$reg->proveedor,
                         "4"=>$reg->comprobante,
                         "5"=>$reg->serie,
                         "6"=>$reg->numero,
                         "7"=>$reg->impuesto,
                         "8"=>$reg->articulo,
                         "9"=>$reg->codigo,
                         "10"=>$reg->serie,
                         "11"=>$reg->stock_ingreso,
                         "12"=>$reg->stock_actual,
                         "13"=>$reg->stock_vendido,
                         "14"=>$reg->precio_compra,
                         "15"=>$reg->precio_ventapublico,
                         "16"=>$reg->precio_ventadistribuidor
                    );
               }
               $results = array(
               "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);
               break;

          case "listVentasEmpleado":
               $fecha_desde = $_REQUEST["fecha_desde"];
               $fecha_hasta = $_REQUEST["fecha_hasta"];
               $idsucursal = $_REQUEST["idsucursal"];
               $data= Array();
               $query_Tipo = $objCategoria->ListarVentasEmpleado($idsucursal, $_SESSION["idempleado"], $fecha_desde, $fecha_hasta);
               
               while ($reg = $query_Tipo->fetch_object()) {
                    $data[] = array(
                         "1"=>$reg->fecha,
                         "2"=>$reg->sucursal,
                         "3"=>$reg->empleado,
                         "4"=>$reg->cliente,
                         "5"=>$reg->comprobante,
                         "6"=>$reg->serie,
                         "7"=>$reg->numero,
                         "8"=>$reg->impuesto,
                         "9"=>$reg->subtotal,
                         "10"=>$reg->totalimpuesto,
                         "11"=>$reg->total
                   );
               }
               $results = array(
                "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);
               break;

          case "listVentasEmpleadoDet":
               $fecha_desde = $_REQUEST["fecha_desde"];
               $fecha_hasta = $_REQUEST["fecha_hasta"];
               $idsucursal = $_REQUEST["idsucursal"];
               $data= Array();
               $query_Tipo = $objCategoria->ListarVentasEmpleadoDet($idsucursal, $_SESSION["idempleado"], $fecha_desde, $fecha_hasta);

               while ($reg = $query_Tipo->fetch_object()) {
                    $data[] = array(
                         "1"=>$reg->fecha,
                         "2"=>$reg->sucursal,
                         "3"=>$reg->empleado,
                         "4"=>$reg->cliente,
                         "5"=>$reg->comprobante,
                         "6"=>$reg->serie,
                         "7"=>$reg->numero,
                         "8"=>$reg->impuesto,
                         "9"=>$reg->articulo,
                         "10"=>$reg->codigo,
                         "11"=>$reg->serie_art,
                         "12"=>$reg->cantidad,
                         "13"=>$reg->precio_venta,
                         "14"=>$reg->descuento,
                         "15"=>$reg->total
                    );
               }
               $results = array(
               "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);
               break;
	}