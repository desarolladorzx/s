<?php
session_start();
switch ($_GET["op"]) {
    case 'TraerDatos':
        require_once "../model/Correccion_stock.php";
        $objPedido = new Correccion_stock();
        $idtraslado = $_GET["idcorreccion_stock"];
        $query_prov = $objPedido->TraerDatos($idtraslado);

        $nuevo = array();
        while ($reg = $query_prov->fetch_object()) {
            $nuevo[] = $reg;
        }
        echo  json_encode($nuevo[0]);
        break;

        break;
    case 'modificarEstadoTraslado':
        require_once "../model/Correccion_stock.php";

        $objCorreccion_stock = new Correccion_stock();
        $estado = $_POST["estado"];
        $arrayDatos = $_POST["arrayDatos"];

        $idtraslado = $_POST['idtraslado'];
        $sucursal_destino_id = $_POST['sucursal_destino_id'];
        $descripcion_recepcion = $_POST['descripcion_recepcion'];


        $query_cli = $objCorreccion_stock->ModificarEstadoTraslado($idtraslado, $estado, json_encode($arrayDatos), $descripcion_recepcion, $sucursal_destino_id);

        break;

    case "listDetIng":
        require_once "../model/Correccion_stock.php";
        $sucursal = $_SESSION["idsucursal"];
        $objCorreccion_stock = new Correccion_stock();
        $query_cli = $objCorreccion_stock->ListarDetalleIngresos($sucursal);
        $data = array();
        $i = 1;
        while ($reg = $query_cli->fetch_object()) {



            if ($reg->estado_detalle_ingreso == 'INGRESO') {
                $disabledButton = '';
            } else {
                $disabledButton = 'disabled';
            }


            $sucursal = $reg->idsucursal == 1 ? 'Arequipa' : 'Lima';
            $data[] = array(
                "0" => '<button type="button" ' . $disabledButton . '  class="btn btn-warning" name="optDetIngBusqueda[]" data-codigo="' . $reg->codigo . '"
                    data-serie="' . $reg->serie . '" data-nombre="' . $reg->Articulo . '" data-precio-venta="' . $reg->precio_ventapublico . '"
                    data-stock-actual="' . $reg->suma_total . '" id="' . $reg->iddetalle_ingreso . '" value="' . $reg->iddetalle_ingreso . '"
                    data-toggle="tooltip" title="Agregar al carrito"
                    onclick="AgregarPedCarritoCorreccion_stock(
                        ' . $reg->idarticulo . ',
                        \'' . $sucursal . '\',
                        \'' . $reg->suma_total . '\',
                        \'' . $reg->Articulo . '\',
                        \'' . $reg->idsucursal . '\',
                        \'' . $reg->precio_ventapublico . '\',
                        \'' . $reg->idarticulo . '\',
                        \'' . $reg->marca . '\')" >
                    <i class="fa fa-check" ></i> </button>',
                "1" => $reg->codigo,
                "2" => $reg->idsucursal == 1 ? 'Arequipa' : 'Lima',
                "3" => $reg->Articulo,
                "4" => $reg->marca,
                "5" => $reg->serie,
                //"5"=>$reg->presentacion,
                "6" => $reg->suma_total,
                "7" => $reg->precio_ventapublico,
                "8" => $reg->estado_n,
                "9" => '<img width=100px height=100px src="./' . $reg->imagen . '" />'
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

    case 'cargarBotones':
        require_once "../model/Correccion_stock.php";
        $objCorreccion_stock = new Correccion_stock();

        $idrol = $objCorreccion_stock->getEmpleado($_SESSION["idempleado"])->fetch_object()->idrol;

        echo json_encode($idrol);

        break;
    case "Save":
        require_once "../model/Correccion_stock.php";

        $objCorreccion_stock = new Correccion_stock();

        $hosp = $objCorreccion_stock->Registrar($_POST);

        if (true) {
            $mensaje="Pedido Registrado";
            echo $mensaje;
            $query_prov = $objCorreccion_stock->EnviarMensaje($mensaje);

        } else {
            echo "No se ha podido registrar el Pedido";
        }
        // }
        break;
    case 'desaprobarCorreccion':
        require_once "../model/Correccion_stock.php";

        $objCorreccion_stock = new Correccion_stock();


        $idcorreccion_stock = $_POST["idcorreccion_stock"];

        $motivo_desaprobado = $_POST["descripcion_desaprobado"];

        $query_prov = $objCorreccion_stock->desaprobarCorreccion($idcorreccion_stock, $motivo_desaprobado);

        $mensaje="se Desaprobo la Correccion de Stock";

        echo  $mensaje;
        $query_prov = $objCorreccion_stock->EnviarMensaje($mensaje);

        break;

    case 'anularCorreccion':
        require_once "../model/Correccion_stock.php";

        $objCorreccion_stock = new Correccion_stock();


        $idcorreccion_stock = $_POST["idcorreccion_stock"];
        $descripcion_anulado= $_POST["descripcion_anulado"];
        $query_prov = $objCorreccion_stock->anularCorreccion($idcorreccion_stock,$descripcion_anulado);


        $mensaje= "Se anulo la Correccion de Stock";
        echo $mensaje;

        $query_prov = $objCorreccion_stock->EnviarMensaje($mensaje);
        break;


    case 'cambiarEstadoConformidad':
        require_once "../model/Correccion_stock.php";

        $objCorreccion_stock = new Correccion_stock();


        $idcorreccion_stock = $_POST["idcorreccion_stock"];
        $query_prov = $objCorreccion_stock->cambiarEstadoConformidad($idcorreccion_stock);

        $mensaje="Se confirmo la Correccion de Stock , pendiente de ser  Aprobado";

        echo $mensaje;

        $query_prov = $objCorreccion_stock->EnviarMensaje($mensaje);


        break;
    case 'cambiarEstadoAprobacion':
        require_once "../model/Correccion_stock.php";

        $objCorreccion_stock = new Correccion_stock();


        $idcorreccion_stock = $_POST["idcorreccion_stock"];
        $query_prov = $objCorreccion_stock->cambiarEstadoAprobacion($idcorreccion_stock);


     


        break;

    case 'cambiarEstadoAprobacion':
        require_once "../model/Correccion_stock.php";

        $objCorreccion_stock = new Correccion_stock();


        $idcorreccion_stock = $_POST["idcorreccion_stock"];
        $query_prov = $objCorreccion_stock->cambiarEstadoAprobacion($idcorreccion_stock);

        break;
    case "ListTipoCorreccion_stock":

        require_once "../model/Correccion_stock.php";
        $objCorreccion_stock = new Correccion_stock();
        $query_Tipo = $objCorreccion_stock->TableCorreccionStock();


        $data = array();
        $i = 1;


        // echo $_SESSION["idempleado"];

        $idrol = $objCorreccion_stock->getEmpleado($_SESSION["idempleado"])->fetch_object()->idrol;

        while ($reg = $query_Tipo->fetch_object()) {
            $htmlButtonConfirmar = '';

            if ($reg->correccion_stock_estado == 'ESPERA') {

                if ($idrol == 5 || $idrol == 2) {

                    $htmlButtonConfirmar = "&nbsp
                <button class='btn btn-warning'  data-toggle='tooltip' onclick='cambiarEstadoConformidad(`$reg->id`)'  title='Confirmar Correccion' >
               
                <i class='glyphicon glyphicon-adjust
                '></i> </button>
                &nbsp
                <button class='btn btn-danger'  data-toggle='tooltip' onclick='anularCorreccion(`$reg->id`)'  title='Anular Correccion' >
               
                <i class='glyphicon glyphicon-trash
                '></i> </button>
                
                ";
                }
            }
            $htmlAprobacion = '';

            if ($reg->correccion_stock_estado == 'CONFIRMADO') {
                if ($idrol == 4 || $idrol == 2) {

                    $htmlAprobacion = "&nbsp
                    <button class='btn btn-info'  data-toggle='tooltip' onclick='cambiarEstadoAprobacion(`$reg->id`)'  title='Aprobar Correccion ' ><i class='glyphicon glyphicon-upload
                    '></i> </button>
                    
                    &nbsp
                    <button class='btn btn-danger'  data-toggle='tooltip' onclick='desaprobarCorreccion(`$reg->id`)'  title='Desaprobar Correccion ' ><i class='glyphicon glyphicon-trash
                    '></i> </button>

                    ";
                }
            }

            $estadoHTML = "<span class='badge bg-green'>$reg->correccion_stock_estado</span>";
            if ($reg->correccion_stock_estado) {
                if ($reg->correccion_stock_estado == 'DESAPROBADO' || $reg->correccion_stock_estado == 'CONFORMIDAD CANCELADA') {
                    $estadoHTML = "<span class='badge bg-red'>$reg->correccion_stock_estado</span>";
                } else if ($reg->correccion_stock_estado == 'ESPERA') {
                    $estadoHTML = "<span class='badge bg-yellow'>$reg->correccion_stock_estado</span>";
                } else if ($reg->correccion_stock_estado == 'CONFIRMADO') {
                    $estadoHTML = "<span class='badge bg-blue'>$reg->correccion_stock_estado</span>";
                }
            }
            $data[] = array(
                "0" => $i,
                "1" => "<div>
                <p><b>creacion</b> - $reg->fecha_ingreso </p>
                <p><b>conformidad</b> - $reg->fecha_conformidad </p>
                <p><b>aprobacion</b> - $reg->fecha_aprobacion </p>
                </div>",
                "2" => "
                <div>
                <p><b>creacion</b> - $reg->empleado_creacion </p>
                <p><b>conformidad</b> - $reg->empleado_conformidad </p>
                <p><b>aprobacion</b> - $reg->empleado_aprobacion </p>
                </div>",
                "3" => $reg->codigo_inventario,

                "4" => $reg->cantidad,
                "5" => $estadoHTML,
                "6" => '<button class="btn btn-success" data-toggle="tooltip" onclick="verDetallesCorreccion_stock(`' . $reg->idcorreccion_stock . '`)"  title="Ver Detalle" ><i class="fav fa-eye"></i> </button>
                    
                '
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

    case "GetDetalleCorreccion_stock":
        require_once "../model/Correccion_stock.php";
        $objPedido = new Correccion_stock();
        $idcorreccion_stock = $_POST["idcorreccion_stock"];
        $query_prov = $objPedido->GetDetalleCorreccionStock($idcorreccion_stock);
        $i = 1;

        // print_r($query_prov);

        $nuevo = array();
        while ($reg = $query_prov->fetch_object()) {
            $nuevo[] = $reg;
        }
        echo  json_encode($nuevo);
        break;
}
