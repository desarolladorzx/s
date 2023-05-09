<?php
session_start();
require_once "../model/ConsultasVentas.php";
$objCategoria = new ConsultasVentas();
switch ($_GET["op"]) {

     case 'listaEjecutivoComercial':
          $query_Tipo = $objCategoria->listaDePersonasVendedor();

          $nuevo = array();
          while ($reg = $query_Tipo->fetch_object()) {
               $nuevo[] = $reg;
          }
          echo  json_encode($nuevo);
          break;
     case "listVentasFechas":
          $ejecutivo_comercial = $_REQUEST["ejecutivo_comercial"];

          $antiguedad_cliente = $_REQUEST["antiguedad_cliente"];

          $tipo_cliente = $_REQUEST["tipo_cliente"];

          $fecha_desde = $_REQUEST["fecha_desde"];
          $fecha_hasta = $_REQUEST["fecha_hasta"];
          $idsucursal = $_REQUEST["idsucursal"];
          $data = array();
          $query_Tipo = $objCategoria->ListarVentasFechas( $fecha_desde, $fecha_hasta, $ejecutivo_comercial, $antiguedad_cliente, $tipo_cliente);
          while ($reg = $query_Tipo->fetch_object()) {
               require_once "../model/Pedido.php";
               $objPedido = new Pedido();
               $query_total = $objPedido->TotalPedido($reg->idpedido);
               $reg_total = $query_total->fetch_object();

               $data[] = array(
                    "0" => $reg->sucursal,
                    "1" => $reg->fecha,
                    "2" => $reg->empleado,
                    "3" => $reg->nuevo_antiguo,
                    "4" => $reg->tipo_cliente,
                    "5" => $reg->cliente,
                    "6" => $reg->dni,
                    "7" => $reg->celular . ' - ' . $reg->telefono_2,
                    "8" => $reg->ticket,
                    "9" => $reg->departamento,
                    "10" => $reg->transporte,
                    "11" => $reg->cuenta_abonada,
                    /*                          "10"=>$reg->num_ope,
                         "11"=>$reg->fecha_operacion, */
                    "12" => $reg->total,
                    "13" => '<button class="btn btn-success" data-toggle="tooltip" title="Ver Detalle" onclick="cargarDataPedido(' . $reg->idpedido . ',\'' . $reg->tipo_pedido . '\',\'' . $reg->numero . '\',\'' . $reg->cliente . '\',\'' . $reg_total->Total . '\',\'' . $reg->email . '\',\'' . $reg->num_documento . '\',\'' . $reg->celular . '\',\'' . $reg->tipo_cliente . '\',\'' . $reg->destino . '\',\'' . $reg->ticket . '\',\'' . $reg->aproba_venta . '\',\'' . $reg->aproba_pedido . '\',\'' . $reg->empleado . '\',\'' . $reg->metodo_pago . '\',\'' . $reg->agencia_envio . '\',\'' . $reg->tipo_promocion . '\',\'' . $reg->tipo_entrega . '\',\'' . $reg->observacion . '\',\'' . $reg->modo_pago . '\')" 
                    
                    
                    ><i class="fa fa-eye"></i> </button>&nbsp' .
                         '<a href="./Reportes/exVenta.php?id=' . $reg->idpedido . '" class="btn btn-primary" data-toggle="tooltip" title="Imprimir" target="blanck" ><i class="fa fa-file-text"></i> </a>&nbsp;'
               );
          }

          
          $results = array(
               "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData" => $data
          );
          echo json_encode($results);
          break;

     case "listVentasDetalladas":
          $fecha_desde = $_REQUEST["fecha_desde"];
          $fecha_hasta = $_REQUEST["fecha_hasta"];
        
          $data = array();
          $query_Tipo = $objCategoria->ListarVentasDetalladas( $fecha_desde, $fecha_hasta);
           
          while ($reg = $query_Tipo->fetch_object()) {

               $data[] = array(
                    "0" => $reg->sucursal,
                    "1" => $reg->fecha,
                    "2" => $reg->empleado,
                    "3" => $reg->tipo_cliente,
                    "4" => $reg->antiguedad,
                    "5" => $reg->cliente,
                    //"3"=>$reg->serie,                         
                    "6" => $reg->serie . '-' . $reg->numero,
                    //"5"=>$reg->impuesto,
                    "7" => $reg->categoria,
                    "8" => $reg->articulo,
                    "9" => $reg->marca,
                    "10" => $reg->codigo,
                    "11" => $reg->serie_art,
                    "12" => $reg->cantidad,
                    "13" => $reg->precio_venta,
                    "14" => $reg->descuento,
                    "15" => $reg->venta_unitario,
                    "16" => $reg->total,
                    "17" => $reg->costo,
                    "18" => $reg->costo_total,
                    "19" => $reg->ganancia,
                    "20" => $reg->promocion,
                    "21" => $reg->departamento,
                    "22" => $reg->distrito,
                    "23" => $reg->banco_abono,
               );
          }

          // print_r($data);
          $results = array(
               "sEcho" => 0,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData" => $data
          );
          echo json_encode($results);
          break;

     case "listVentasAnuladas":
          $fecha_desde = $_REQUEST["fecha_desde"];
          $fecha_hasta = $_REQUEST["fecha_hasta"];
          $idsucursal = $_REQUEST["idsucursal"];



          $data = array();
          $query_Tipo = $objCategoria->ListarVentasAnuladas( $fecha_desde, $fecha_hasta);
          while ($reg = $query_Tipo->fetch_object()) {

               $data[] = array(
                    "0" => $reg->fecha,
                    "1" => $reg->sucursal,
                    "2" => $reg->empleado,
                    "3" => $reg->cliente,
                    "4" => $reg->dni,
                    "5" => $reg->celular,
                    "6" => $reg->ticket,
                    "7" => $reg->departamento,
                    "8" => $reg->transporte,
                    "9" => $reg->cuenta_abonada,
                    /* "11"=>$reg->fecha_operacion, */
                    "10" => $reg->total,
                    "11" => ($reg->estado == "A") ? '<span class="badge bg-green">ACEPTADO</span>' : '<span class="badge bg-red">CANCELADO</span>',
                    "12" => '<button class="btn btn-success" data-toggle="tooltip" title="Ver Detalle" onclick="cargarDataPedido(' . $reg->idpedido . ',\'' . $reg->tipo_pedido . '\',\'' . $reg->numero . '\',\'' . $reg->cliente . '\',\'' . $reg->total . '\')" ><i class="fa fa-eye"></i> </button>&nbsp' .
                         '<a href="./Reportes/exVenta.php?id=' . $reg->idpedido . '" class="btn btn-primary" data-toggle="tooltip" title="Imprimir" target="blanck" ><i class="fa fa-file-text"></i> </a>&nbsp;'
               );
          }

          $results = array(
               "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData" => $data
          );
          echo json_encode($results);
          break;

     case "listVentasPendientes":
          $fecha_desde = $_REQUEST["fecha_desde"];
          $fecha_hasta = $_REQUEST["fecha_hasta"];
          $idsucursal = $_REQUEST["idsucursal"];
          $data = array();
          $query_Tipo = $objCategoria->ListarVentasPendientes( $fecha_desde, $fecha_hasta);
          while ($reg = $query_Tipo->fetch_object()) {
               $data[] = array(
                    "0" => $reg->fecha,
                    "1" => $reg->sucursal,
                    "2" => $reg->empleado,
                    "3" => $reg->cliente,
                    "4" => $reg->comprobante,
                    "5" => $reg->serie,
                    "6" => $reg->numero,
                    "7" => $reg->impuesto,
                    "8" => $reg->subtotal,
                    "9" => $reg->totalimpuesto,
                    "10" => $reg->totalpagar,
                    "11" => $reg->totalpagado,
                    "12" => $reg->totaldeuda
               );
          }
          $results = array(
               "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData" => $data
          );
          echo json_encode($results);
          break;

     case "listVentasContado":
          $fecha_desde = $_REQUEST["fecha_desde"];
          $fecha_hasta = $_REQUEST["fecha_hasta"];
          $idsucursal = $_REQUEST["idsucursal"];
          $data = array();
          $query_Tipo = $objCategoria->ListarVentasContado( $fecha_desde, $fecha_hasta);
          while ($reg = $query_Tipo->fetch_object()) {
               $data[] = array(
                    "0" => $reg->fecha,
                    "1" => $reg->sucursal,
                    "2" => $reg->empleado,
                    "3" => $reg->cliente,
                    "4" => $reg->comprobante,
                    "5" => $reg->serie,
                    "6" => $reg->numero,
                    "7" => $reg->impuesto,
                    "8" => $reg->subtotal,
                    "9" => $reg->totalimpuesto,
                    "10" => $reg->total
               );
          }
          $results = array(
               "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData" => $data
          );
          echo json_encode($results);
          break;

     case "listVentasCredito":

          $fecha_desde = $_REQUEST["fecha_desde"];
          $fecha_hasta = $_REQUEST["fecha_hasta"];
          $idsucursal = $_REQUEST["idsucursal"];
          $data = array();
          $query_Tipo = $objCategoria->ListarVentasCredito( $fecha_desde, $fecha_hasta);

          while ($reg = $query_Tipo->fetch_object()) {

               $data[] = array(
                    "0" => $reg->fecha,
                    "1" => $reg->sucursal,
                    "2" => $reg->empleado,
                    "3" => $reg->cliente,
                    "4" => $reg->comprobante,
                    "5" => $reg->serie,
                    "6" => $reg->numero,
                    "7" => $reg->impuesto,
                    "8" => $reg->subtotal,
                    "9" => $reg->totalimpuesto,
                    "10" => $reg->totalpagar,
                    "11" => $reg->totalpagado,
                    "12" => $reg->totaldeuda
               );
          }
          $results = array(
               "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData" => $data
          );
          echo json_encode($results);
          break;

     case "listVentasCliente":
          $idCliente = $_REQUEST["idCliente"];
          $fecha_desde = $_REQUEST["fecha_desde"];
          $fecha_hasta = $_REQUEST["fecha_hasta"];
          $idsucursal = $_REQUEST["idsucursal"];
          $data = array();
          $query_Tipo = $objCategoria->ListarVentasCliente( $idCliente, $fecha_desde, $fecha_hasta);
          while ($reg = $query_Tipo->fetch_object()) {
               $data[] = array(
                    "0" => $reg->fecha,
                    "1" => $reg->sucursal,
                    "2" => $reg->empleado,
                    "3" => $reg->cliente,
                    "4" => $reg->dni,
                    "5" => $reg->celular . ' - ' . $reg->telefono_2,
                    "6" => $reg->ticket,
                    "7" => $reg->departamento,
                    "8" => $reg->transporte,
                    "9" => $reg->cuenta_abonada,
                    /*                          "10"=>$reg->num_ope,
                         "11"=>$reg->fecha_operacion, */
                    "10" => $reg->total,
                    "11" => '<button class="btn btn-success" data-toggle="tooltip" title="Ver Detalle" onclick="cargarDataPedido(' . $reg->idpedido . ',\'' . $reg->tipo_pedido . '\',\'' . $reg->numero . '\',\'' . $reg->cliente . '\',\'' . $reg->total . '\')" ><i class="fa fa-eye"></i> </button>&nbsp' .
                         '<a href="./Reportes/exVenta.php?id=' . $reg->idpedido . '" class="btn btn-primary" data-toggle="tooltip" title="Imprimir" target="blanck" ><i class="fa fa-file-text"></i> </a>&nbsp;'
               );
          }
          $results = array(
               "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData" => $data
          );
          echo json_encode($results);
          break;

     case "listComprasDetProveedor":
          $idProveedor = $_REQUEST["idProveedor"];
          $fecha_desde = $_REQUEST["fecha_desde"];
          $fecha_hasta = $_REQUEST["fecha_hasta"];
          $idsucursal = $_REQUEST["idsucursal"];
          $data = array();
          $query_Tipo = $objCategoria->ListarComprasDetProveedor($idsucursal, $idProveedor, $fecha_desde, $fecha_hasta);

          while ($reg = $query_Tipo->fetch_object()) {
               $data[] = array(
                    "0" => $reg->fecha,
                    "1" => $reg->sucursal,
                    "2" => $reg->empleado,
                    "3" => $reg->proveedor,
                    "4" => $reg->comprobante,
                    "5" => $reg->serie,
                    "6" => $reg->numero,
                    "7" => $reg->impuesto,
                    "8" => $reg->articulo,
                    "9" => $reg->codigo,
                    "10" => $reg->serie,
                    "11" => $reg->stock_ingreso,
                    "12" => $reg->stock_actual,
                    "13" => $reg->stock_vendido,
                    "14" => $reg->precio_compra,
                    "15" => $reg->precio_ventapublico,
                    "16" => $reg->precio_ventadistribuidor
               );
          }
          $results = array(
               "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData" => $data
          );
          echo json_encode($results);
          break;

     case "listVentasEmpleado":
          $fecha_desde = $_REQUEST["fecha_desde"];
          $fecha_hasta = $_REQUEST["fecha_hasta"];
          $idsucursal = $_REQUEST["idsucursal"];
          $data = array();
          $query_Tipo = $objCategoria->ListarVentasEmpleado( $_SESSION["idempleado"], $fecha_desde, $fecha_hasta);

          while ($reg = $query_Tipo->fetch_object()) {
               $data[] = array(
                    "1" => $reg->fecha,
                    "2" => $reg->sucursal,
                    "3" => $reg->empleado,
                    "4" => $reg->cliente,
                    "5" => $reg->comprobante,
                    "6" => $reg->serie,
                    "7" => $reg->numero,
                    "8" => $reg->impuesto,
                    "9" => $reg->subtotal,
                    "10" => $reg->totalimpuesto,
                    "11" => $reg->total
               );
          }
          $results = array(
               "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData" => $data
          );
          echo json_encode($results);
          break;

     case "listVentasEmpleadoDet":
          $fecha_desde = $_REQUEST["fecha_desde"];
          $fecha_hasta = $_REQUEST["fecha_hasta"];
          $idsucursal = $_REQUEST["idsucursal"];
          $data = array();
          $query_Tipo = $objCategoria->ListarVentasEmpleadoDet( $_SESSION["idempleado"], $fecha_desde, $fecha_hasta);

          while ($reg = $query_Tipo->fetch_object()) {
               $data[] = array(
                    "1" => $reg->fecha,
                    "2" => $reg->sucursal,
                    "3" => $reg->empleado,
                    "4" => $reg->cliente,
                    "5" => $reg->comprobante,
                    "6" => $reg->serie,
                    "7" => $reg->numero,
                    "8" => $reg->impuesto,
                    "9" => $reg->articulo,
                    "10" => $reg->codigo,
                    "11" => $reg->serie_art,
                    "12" => $reg->cantidad,
                    "13" => $reg->precio_venta,
                    "14" => $reg->descuento,
                    "15" => $reg->total
               );
          }
          $results = array(
               "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData" => $data
          );
          echo json_encode($results);
          break;
}
