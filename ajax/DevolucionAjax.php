<?php
session_start();
switch ($_GET["op"]) {

    case 'ListTipoDevoluciones':
        require_once "../model/Devolucion.php";
        $obj = new Devolucion();
        $query = $obj->TraerListaDevolucion();
        $data = array();
        $i = 1;
        while ($reg = $query->fetch_object()) {
            // $regTotal = $objTraslados->GetTotal($reg->idpedido);
            // $fetch = $regTotal->fetch_object();
            $data[] = array(
                "0" => $i,
                "1" => $reg->fecha,
                "2" => $reg->usuario,
                "3" => $reg->devolucion,
                "4" => $reg->motivo,
                "5" => $reg->observacion,
                "6" => '<button class="btn btn-success" data-toggle="tooltip" onclick="verDetallesDevoluciones(`' .str_replace('"',"+",json_encode($reg))  . '`)"  title="Ver Detalle" ><i class="fa fa-eye"></i> </button>&nbsp' 
            
            );
             $i++;
        }
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);

        break;
    case  'Traer_devolucion_motivo':
        require_once "../model/Devolucion.php";
        $obj = new Devolucion();
        $query = $obj->TraerDatosDevoluciones();
        $reg = $query->fetch_all();
        echo json_encode($reg);
        break;
    case 'Save':
        require_once "../model/Devolucion.php";
        $obj = new Devolucion();
        $iddevolucion_motivo = $_POST["iddevolucion_motivo"];
        $observacion = $_POST["observacion"];
        $fecha = $_POST["fecha"];

        $idsucursal = $_SESSION["idsucursal"];
        $idUsuario = $_SESSION["idempleado"];

        $detalle = $_POST["detalle"];
        $query = $obj->Save($iddevolucion_motivo, $observacion, $fecha, $idsucursal, $idUsuario, $detalle);

        // if ($hosp[0]) {
        if ($query) {
            echo "Pedido Registrado";
        } else {
            echo "No se ha podido registrar el Pedido";
        }
        // }

        break;
    case "listDetIng":
        require_once "../model/Devolucion.php";
        $sucursal = $_SESSION["idsucursal"];
        $objTraslados = new Devolucion();
        $query_cli = $objTraslados->ListarDetalleIngresos($sucursal);
        $data = array();
        $i = 1;
        while ($reg = $query_cli->fetch_object()) {

            if ($reg->estado_detalle_ingreso=='INGRESO') {
                $disabledButton = '';
            } else {
                $disabledButton = 'disabled';
            }


            $data[] = array(
                "0" => '<button type="button" ' . $disabledButton . ' class="btn btn-warning" name="optDetIngBusqueda[]" data-codigo="' . $reg->codigo . '"
                    data-serie="' . $reg->serie . '" data-nombre="' . $reg->Articulo . '" data-precio-venta="' . $reg->precio_ventapublico . '"
                    data-stock-actual="' . $reg->stock_actual . '" id="' . $reg->iddetalle_ingreso . '" value="' . $reg->iddetalle_ingreso . '"
                    data-toggle="tooltip" title="Agregar al carrito"
                    onclick="AgregarPedCarritoDevolucion(
                        ' . $reg->iddetalle_ingreso . ',
                        \'' . $reg->stock_actual . '\',
                        \'' . $reg->Articulo . '\',
                        \'' . $reg->codigo . '\',
                        \'' . $reg->serie . '\',
                        \'' . $reg->precio_ventapublico . '\',
                        \'' . $reg->idarticulo . '\',
                        \'' . $reg->marca . '\')" >
                    <i class="fa fa-check" ></i> </button>',
                "1" => $reg->codigo,
                "2" => $reg->Articulo,
                "3" => $reg->marca,
                "4" => $reg->serie,
                //"5"=>$reg->presentacion,
                "5" => $reg->stock_actual,
                "6" => $reg->precio_ventapublico,
                "7" => $reg->estado_detalle_ingreso,
                "8" => '<img width=100px height=100px src="./' . $reg->imagen . '" />'
            );
            $i++;
        }

        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);

        break;

        
        case "GetDetalleDevolucion":
            require_once "../model/Devolucion.php";
            $objPedido = new Devolucion();
            $idtraslado = $_POST["idtraslado"];
            $query_prov = $objPedido->GetDetalleDevolucion($idtraslado);
            $i = 1;
            while ($reg = $query_prov->fetch_object()) {
                echo '<tr>
                            <td>' . $reg->producto . '</td>
                            <td>' . $reg->marca . '</td>
                            <td>' . $reg->lote . '</td>
                            <td>' . $reg->fecha_vencimiento . '</td>
                            <td>' . $reg->cantidad . '</td>
                        
                           </tr>';
                $i++;
            }
            break;

}
