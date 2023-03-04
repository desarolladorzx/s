<?php
session_start();
switch ($_GET["op"]) {
    case "listDetIng":
        require_once "../model/Traslados.php";
        $sucursal = $_SESSION["idsucursal"];
        $objTraslados = new Traslados();
        $query_cli = $objTraslados->ListarDetalleIngresos($sucursal);
        $data = array();
        $i = 1;
        while ($reg = $query_cli->fetch_object()) {
            $data[] = array(
                "0" => '<button type="button" class="btn btn-warning" name="optDetIngBusqueda[]" data-codigo="' . $reg->codigo . '"
                    data-serie="' . $reg->serie . '" data-nombre="' . $reg->Articulo . '" data-precio-venta="' . $reg->precio_ventapublico . '"
                    data-stock-actual="' . $reg->stock_actual . '" id="' . $reg->iddetalle_ingreso . '" value="' . $reg->iddetalle_ingreso . '"
                    data-toggle="tooltip" title="Agregar al carrito"
                    onclick="AgregarPedCarritoTraslado(
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
                "7" => '<img width=100px height=100px src="./' . $reg->imagen . '" />'
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

    case "Save":
        
        require_once "../model/Traslados.php";
        $almacenInicial =  $_SESSION["idsucursal"];
        $idUsuario = $_SESSION["idusuario"];


        $motivoDeTraslado = $_POST["motivoDeTraslado"];
        $almacenFinal = $_POST["almacenFinal"];

        $objTraslados = new Traslados();

        // $query_cli = $objTraslados->save($sucursal);
        $hosp = $objTraslados->Registrar($almacenInicial, $almacenFinal, $motivoDeTraslado, $idUsuario, $_POST["detalle"]);

        // if ($hosp[0]) {
        if (true) {
            echo "Pedido Registrado";
        } else {
            echo "No se ha podido registrar el Pedido";
        }
        break;


    case "ListTipoTraslados":

        require_once "../model/Traslados.php";
        $objTraslados = new Traslados();
        $query_Tipo = $objTraslados->TableTraslado();
        $data = array();
        $i = 1;

        while ($reg = $query_Tipo->fetch_object()) {
            // $regTotal = $objTraslados->GetTotal($reg->idpedido);
            // $fetch = $regTotal->fetch_object();
            $data[] = array(
                "0" => $i,
                "1" => $reg->fecha,
                "2" => $reg->almacen_inicial,
                "3" => $reg->almacen_destino,
                "4" => $reg->motivo_del_traslado,
                "5" => $reg->cantidad_total_de_productos,
                "6" => $reg->cantidad_total_de_productos, //SE O//SE OBTIENE LOS DATOS DE LA TABLA PEDIDO
                // "6" => $reg->estado,
                // "6" => '<button class="btn btn-success" data-toggle="tooltip" title="Ver Detalle" onclick="cargarDataPedido(' . $reg->idpedido . ',\'' . $fetch->total . '\',\'' . $reg->email . '\',\'' . $reg->idcliente . '\',\'' . $reg->empleado . '\',\'' . $reg->cliente . '\',\'' . $reg->num_documento . '\',\'' . $reg->celular . '\',\'' . $reg->destino . '\',\'' . $reg->metodo_pago . '\',\'' . $reg->agencia_envio . '\',\'' . $reg->tipo_promocion . '\')" ><i class="fa fa-eye"></i> </button>&nbsp' .
                //     $botonPasarAVenta .
                //     '<a href="./Reportes/exPedido.php?id=' . $reg->idpedido . '" class="btn btn-primary" data-toggle="tooltip" title="Imprimir" target="blanck" ><i class="fa fa-file-text"></i> </a>&nbsp;' .
                //     '<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar Pedido" onclick="eliminarPedido(' . $reg->idpedido . ')" ><i class="fa fa-trash"></i> </button>&nbsp' .
                //     '<button class="btn btn-warning" data-toggle="tooltip" title="Cambiar estado" onclick="cambiarEstadoPedido(' . $reg->idpedido . ')" ><i class="fa fa-refresh"></i> </button>&nbsp'
                /*  "6"=>'<button class="btn btn-success" data-toggle="tooltip" title="Ver Detalle" onclick="cargarDataPedido('.$reg->idpedido.',\''.$reg->tipo_pedido.'\',\''.$reg->numero.'\',\''.$reg->Cliente.'\',\''.$fetch->total.'\')" ><i class="fa fa-eye"></i> </button>&nbsp'.
               '<button class="btn btn-success" data-toggle="tooltip" title="Generar Venta" onclick="pasarIdPedido('.$reg->idpedido.',\''.$fetch->total.'\',\''.$reg->email.'\',\''.$reg->idcliente.'\',\''.$reg->metodo_pago.'\',\''.$reg->agencia_envio.'\',\''.$reg->tipo_promocion.'\',\''.$reg->Cliente.'\',\''.$reg->email.'\')"><i class="fa fa-shopping-cart"></i> </button>&nbsp'.
               '<a href="./Reportes/exPedido.php?id='.$reg->idpedido.'" class="btn btn-primary" data-toggle="tooltip" title="Imprimir" target="blanck" ><i class="fa fa-file-text"></i> </a>&nbsp;'.
               '<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar Pedido" onclick="eliminarPedido('.$reg->idpedido.')" ><i class="fa fa-trash"></i> </button>&nbsp'.
               '<button class="btn btn-warning" data-toggle="tooltip" title="Cambiar estado" onclick="cambiarEstadoPedido('.$reg->idpedido.')" ><i class="fa fa-refresh"></i> </button>&nbsp'  */
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
}