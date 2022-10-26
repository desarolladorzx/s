<?php

	session_start();

	require_once "../model/ConsultasCompras.php";

	$objCategoria = new ConsultasCompras();

	switch ($_GET["op"]) {

		case "listKardexValorizado":
               if ( !isset($_REQUEST['idsucursal'])) $_REQUEST['idsucursal'] = 1;
               $idsucursal = $_REQUEST["idsucursal"];

			$query_Tipo = $objCategoria->ListarKardexValorizado($idsucursal);
               $data = Array();
			while ($reg = $query_Tipo->fetch_object()) {
				$data[] = array(
                         "0"=>$reg->sucursal,
                         "1"=>$reg->articulo,
                         "2"=>$reg->categoria,
                         "3"=>'<img width=100px height=100px src="./'.$reg->imagen.'" />',
                         "4"=>$reg->unidad,
                         "5"=>$reg->totalingreso,
                         "6"=>$reg->valorizadoingreso,
                         "7"=>$reg->totalstock,
                         "9"=>$reg->valorizadostock,
                         "8"=>$reg->totalventa,
                         "10"=>$reg->valorizadoventa,
                         "11"=>$reg->utilidadvalorizada
                         );
			}
               $results = array(
               "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);

			break;

		case "listStockArticulos":
           if ( !isset($_REQUEST['idsucursal'])) $_REQUEST['idsucursal'] = 1;
          $idsucursal = $_REQUEST["idsucursal"];
          $data =Array();

			$query_Tipo = $objCategoria->ListarStockArticulos($idsucursal);

			while ($reg = $query_Tipo->fetch_object()) {

				$data[] = array(
                         "0"=>$reg->sucursal,
                         "1"=>$reg->articulo,
                         "2"=>$reg->marca,
                         "3"=>$reg->categoria,
                         "4"=>'<img width=100px height=100px src="./'.$reg->imagen.'" />',
                         "5"=>$reg->codigo,
                         "6"=>$reg->serie,
                         "7"=>$reg->totalingreso,
                         "8"=>$reg->valorizadoingreso,
                         "9"=>$reg->totalstock,
                         "10"=>$reg->preciocompra,
                         "11"=>$reg->valorizadostock,
                         "12"=>$reg->totalventa,
                         "13"=>$reg->valorizadoventa,
                         "14"=>$reg->utilidadvalorizada
                         );
			}
               $results = array(
               "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);
            
			break;

          case "listStockArticulos":
           if ( !isset($_REQUEST['idsucursal'])) $_REQUEST['idsucursal'] = 1;
          $idsucursal = $_REQUEST["idsucursal"];
          $data =Array();

			$query_Tipo = $objCategoria->ListarStockArticulos($idsucursal);

			while ($reg = $query_Tipo->fetch_object()) {

				$data[] = array(
                         "0"=>$reg->sucursal,
                         "1"=>$reg->articulo,
                         "2"=>$reg->marca,
                         "3"=>$reg->categoria,
                         "4"=>'<img width=100px height=100px src="./'.$reg->imagen.'" />',
                         "5"=>$reg->codigo,
                         "6"=>$reg->serie,
                         "7"=>$reg->totalingreso,
                         "8"=>$reg->valorizadoingreso,
                         "9"=>$reg->totalstock,
                         "10"=>$reg->preciocompra,
                         "11"=>$reg->valorizadostock,
                         "12"=>$reg->totalventa,
                         "13"=>$reg->valorizadoventa,
                         "14"=>$reg->utilidadvalorizada
                         );
			}
               $results = array(
               "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);
            
			break;

          case "listSalidaArticulos":
           if ( !isset($_REQUEST['idsucursal'])) $_REQUEST['idsucursal'] = 1;
          $idsucursal = $_REQUEST["idsucursal"];
          $data =Array();

			$query_Tipo = $objCategoria->ListarSalidasProductos($idsucursal);

			while ($reg = $query_Tipo->fetch_object()) {

				$data[] = array(
                         "0"=>$reg->sucursal,
                         "1"=>$reg->marca,
                         "2"=>$reg->codigo,
                         "3"=>$reg->articulo,
                         "4"=>$reg->categoria,
                         "5"=>$reg->unidad,
                         "6"=>$reg->serie_art,
                         "7"=>$reg->salida,
                         "8"=>$reg->costo,
                         "9"=>$reg->costo_total,
                         "10"=>$reg->totalstock,
                         "11"=>$reg->valorizadostock,
                         );
			}
               $results = array(
               "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);
            
			break;

          case "listComprasFechas":

               $fecha_desde = $_REQUEST["fecha_desde"];
               $fecha_hasta = $_REQUEST["fecha_hasta"];
               $idsucursal = $_REQUEST["idsucursal"];
               $data = Array();
               $query_Tipo = $objCategoria->ListarComprasFechas($idsucursal, $fecha_desde, $fecha_hasta);

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
                         "8"=>$reg->subtotal,
                         "9"=>$reg->totalimpuesto,
                         "10"=>$reg->total,
                         "11"=>'<button class="btn btn-success" data-toggle="tooltip" title="Ver Detalle" onclick="cargarDataIngreso('.$reg->idingreso.',\''.$reg->serie.'\',\''.$reg->numero.'\',\''.$reg->impuesto.'\',\''.$reg->total.'\',\''.$reg->idingreso.'\',\''.$reg->proveedor.'\',\''.$reg->comprobante.'\')" ><i class="fa fa-eye"></i> </button>&nbsp'.
                    '<a href="./Reportes/exIngreso.php?id='.$reg->idingreso.'" class="btn btn-primary" data-toggle="tooltip" title="Imprimir" target="blanck" ><i class="fa fa-file-text"></i> </a>'
                    );
               }
            
               $results = array(
               "sEcho" => 1,
               "iTotalRecords" => count($data),
               "iTotalDisplayRecords" => count($data),
               "aaData"=>$data);
               echo json_encode($results);
            
               break;

          case "listComprasDetalladas":

               $fecha_desde = $_REQUEST["fecha_desde"];
               $fecha_hasta = $_REQUEST["fecha_hasta"];
               //if ( !isset($_REQUEST['idsucursal'])) $_REQUEST['idsucursal'] = 1;
               $idsucursal = $_REQUEST["idsucursal"];
               $data = Array();
               $query_Tipo = $objCategoria->ListarComprasDetalladas($idsucursal, $fecha_desde, $fecha_hasta);

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
                         "10"=>$reg->serie_art,
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

          case "listComprasProveedor":

               $idProveedor = $_REQUEST["idProveedor"];
               $fecha_desde = $_REQUEST["fecha_desde"];
               $fecha_hasta = $_REQUEST["fecha_hasta"];
               // if ( !isset($_REQUEST['idsucursal'])) $_REQUEST['idsucursal'] = 1;
               $idsucursal = $_REQUEST["idsucursal"];
               $data = Array();

               $query_Tipo = $objCategoria->ListarComprasProveedor($idsucursal, $idProveedor, $fecha_desde, $fecha_hasta);

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

          case "listComprasDetProveedor":

               $idProveedor = $_REQUEST["idProveedor"];
               $fecha_desde = $_REQUEST["fecha_desde"];
               $fecha_hasta = $_REQUEST["fecha_hasta"];
               // if ( !isset($_REQUEST['idsucursal'])) $_REQUEST['idsucursal'] = 1;
               $idsucursal = $_REQUEST["idsucursal"];
               $data = Array();
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
	}