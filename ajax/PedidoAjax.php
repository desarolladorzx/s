<?php
session_start();
switch ($_GET["op"]) {
    case 'SaveImagesEmpaquetado':
        require_once "../model/Pedido.php";
        $obj = new Pedido();

        $idpedido = $_POST["idpedido"];
        $idSucursal = $_SESSION["idsucursal"];
        $idCliente = $_POST["idcliente"];


        $idUsuario = $_SESSION["idusuario"];

        if (!empty($_FILES["fileupload"])) {
            $file_names = $_FILES['fileupload']['name'];

            for ($i = 0; $i < count($file_names); $i++) {
                $file_name = $file_names[$i];

                $parte = explode(".", $file_name);
                // echo $parte[0]; // nombre del archivo
                // echo $parte[1]; // extension del archivo

                $codigoInterno = strtotime(date('Y-m-d H:i:s'));
                $new_file_name = str_replace(' ', '-', $parte[0] . '-' . $codigoInterno . '.' . $parte[1]);


                $obj->RegistrarDetalleImagenesAlmacen($idpedido, $idCliente, $idUsuario, $idSucursal, $new_file_name);


                move_uploaded_file($_FILES["fileupload"]["tmp_name"][$i], "../Files/Empaquetado/" . $new_file_name);
            }
        }
        break;
    case 'Save':
        require_once "../model/Pedido.php";
        $obj = new Pedido();

        /*
        $files=$_FILES['fileupload'];
        $tmp_name=$files['tmp_name'];
        $name=$files['name'];
        */

        //var_dump($_POST["metodo_pago"],' - ',$_POST["agencia_envio"],' - ',$_POST["tipo_promocion"]);
        //exit;

        $idCliente = $_POST["idCliente"];
        $idUsuario = $_POST["idUsuario"];
        $idSucursal = $_POST["idSucursal"];
        $tipo_pedido = trim($_POST["tipo_pedido"]);

        $metodo_pago = $_POST["metodo_pago"];
        $agencia_envio = $_POST["agencia_envio"];
        $tipo_promocion = $_POST["tipo_promocion"];



        $modo_pago = $_POST["modo_pago"];
        $observaciones = $_POST["observaciones"];

        $tipo_entrega = $_POST["tipo_entrega"];


        /*
        $tipo_promocion = $_POST["tipo_promocion"];
        $metodo_pago = $_POST["metodo_pago"];
        $agencia_envio = $_POST["agencia_envio"];
        */
        //$imagen = $_FILES["imagenVoucher"]["tmp_name"];
        //$ruta = $_FILES["imagenVoucher"]["name"];
        $numero = $_POST["numero"];



        //if(move_uploaded_file($imagen, "../Files/Voucher/".$ruta)){
        if (empty($_POST["idPedido"])) {
            $hosp = $obj->Registrar($idCliente, $idUsuario, $idSucursal, $tipo_pedido, $numero, $_POST["detalle"], $metodo_pago, $agencia_envio, $tipo_promocion, $modo_pago, $observaciones, $tipo_entrega);

            //var_dump($hosp);exit;

            $estadoResult = $hosp[0];
            $idpedido = $hosp[1];

            //var_dump($estadoResult,$idpedido);

            if ($numero == "") {
                $numero = "1";
            } else {

                //$numero = $_POST["numero"];
                //move_uploaded_file($tmp_name, "../Files/Voucher/".$name);


                if (!empty($_FILES["fileupload"])) {

                    $file_names = $_FILES['fileupload']['name'];

                    for ($i = 0; $i < count($file_names); $i++) {

                        $file_name = $file_names[$i];

                        $parte = explode(".", $file_name);
                        // echo $parte[0]; // nombre del archivo
                        // echo $parte[1]; // extension del archivo

                        $codigoInterno = strtotime(date('Y-m-d H:i:s'));
                        $new_file_name = str_replace(' ', '-', $parte[0] . '-' . $codigoInterno . '.' . $parte[1]);

                        // GUARDAR IMAGENES CON EL NUMERO DE COTIZACION
                        $obj->RegistrarDetalleImagenes($idpedido, $idCliente, $idUsuario, $idSucursal, $new_file_name);

                        //$extension = end(explode(".", $file_name));
                        //$original_file_name = pathinfo($file_name, PATHINFO_FILENAME);
                        //$file_url = $original_file_name . "-" . date("YmdHis") . "." . $extension;
                        move_uploaded_file($_FILES["fileupload"]["tmp_name"][$i], "../Files/Voucher/" . $new_file_name);


                        //var_dump($file_name);
                    }
                }
            }


            if ($hosp[0]) {
                echo "Pedido Registrado";
            } else {
                echo "No se ha podido registrar el Pedido";
            }
        } /* else {
                $idPedido = $_POST["idPedido"];
                if($obj->Modificar($idPedido, $idCliente, $idUsuario, $idSucursal, $tipo_pedido, $tipo_promocion,$metodo_pago,$agencia_envio, $numero,"Files/Voucher/".$ruta, $_POST["detalle"])){
                    echo "Pedido Modificado";
                } else {
                    echo "No se ha podido modificar el Pedido";
                }
            }
        }else {
            $ruta_img = $_POST["txtRutaImgVoucher"];
            if(empty($_POST["idPedido"])){
                $hosp = $obj->Registrar($idCliente, $idUsuario, $idSucursal, $tipo_pedido, $tipo_promocion, $metodo_pago, $agencia_envio, $numero,$ruta_img, $_POST["detalle"]);
                    if ($hosp) {
                        echo "Pedido Registradaaaa";
                    } else {
                        echo "No se ha podido registrar el Pedido";
                    }
            } else {
                $idPedido = $_POST["idPedido"];
                if($obj->Modificar($_POST["idPedido"], $idCliente, $idUsuario, $idSucursal, $tipo_pedido, $tipo_promocion,$metodo_pago,$agencia_envio, $numero,$ruta_img, $_POST["detalle"])){
                    echo "Pedido Modificado";
                } else {
                    echo "No se ha podido modificar el Pedido";
                }
            } */
        //}
        break;
    case "imformarCotizacion":
        require_once "../model/Pedido.php";
        $objPedi = new Pedido();
        $query = $objPedi->PedidosEnEspera();
        $nuevo = array();
        while ($reg = $query->fetch_object()) {
            $nuevo[] = $reg;
        }
        echo  json_encode($nuevo);
        break;
    case "informarPedido":
        require_once "../model/Pedido.php";
        $objPedi = new Pedido();
        $query = $objPedi->traerUltimoPedido();
        $reg = $query->fetch_object();

        echo json_encode($reg);
        break;


    case "informarVenta":
        require_once "../model/Pedido.php";
        $objPedi = new Pedido();
        $query = $objPedi->traerUltimaVenta();
        $reg = $query->fetch_object();

        echo json_encode($reg);
        break;
    
        case "informarVentaLima":
            require_once "../model/Pedido.php";
            $objPedi = new Pedido();
            $query = $objPedi->traerUltimaVentaLima();
            $reg = $query->fetch_object();
    
            echo json_encode($reg);
            break;


    case "informarVentaCancelada":
        require_once "../model/Pedido.php";
        $objPedi = new Pedido();
        $query = $objPedi->traerUltimaVentaCancelada();
        $reg = $query->fetch_object();

        echo json_encode($reg);
        break;

    case "listTipoPedidoPedido":
        require_once "../model/Pedido.php";
        $objPed = new Pedido();

        $query_Tipo = $objPed->ListarTipoPedidoPedido($_SESSION["idsucursal"]);


        $query_Usuario = $objPed->DatosUsuario($_SESSION["idempleado"]);


        $res_usuario = $query_Usuario->fetch_object();
        // var_dump($res_usuario);
        $data = array();
        $i = 1;
        while ($reg = $query_Tipo->fetch_object()) {
            $regTotal = $objPed->GetTotal($reg->idpedido);
            $fetch = $regTotal->fetch_object();
            $botonPasarAVenta = '';

            if ($_SESSION["idempleado"] == 11 || $_SESSION["idempleado"] == 6) {

                if ($reg->estadoId == "D") { // APROBADO
                    $botonPasarAVenta = '<button class="btn btn-success" data-toggle="tooltip" title="Generar Venta" onclick="pasarIdPedido(' . $reg->idpedido . ',\'' . $fetch->total . '\',\'' . $reg->email . '\',\'' . $reg->idcliente . '\',\'' . $reg->empleado . '\',\'' . $reg->cliente . '\',\'' . $reg->num_documento . '\',\'' . $reg->celular . '\',\'' . $reg->destino . '\',\'' . $reg->metodo_pago . '\',\'' . $reg->agencia_envio . '\',\'' . $reg->tipo_promocion . '\',\'' . $reg->observaciones . '\',\'' . $reg->modo_pago . '\',\'' . $reg->tipo_entrega . '\')"><i class="fa fa-shopping-cart"></i> </button>&nbsp';
                } else {
                    $botonPasarAVenta = '';
                }
            }
            $botonEliminar = '';

            if ($reg->estadoId == "D") {
                if ($_SESSION["idempleado"] == 11 || $_SESSION["idempleado"] == 6) {
                    // if (true) {
                    $botonEliminar = '<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar Pedido" onclick="eliminarPedido(' . $reg->idpedido . ')" ><i class="fa fa-trash"></i> </button>&nbsp';
                }
            }
            // if (true) {
            if ($reg->estadoId == "A") {
                if ($_SESSION["idempleado"] == 17 || $_SESSION["idempleado"] == 6) { // APROBADO
                    // if (true) {

                    $botonEliminar = '<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar Pedido" onclick="eliminarPedido(' . $reg->idpedido . ')" ><i class="fa fa-trash"></i> </button>&nbsp';
                }
            }
            // if ($_SESSION["idempleado"]==17 ||$_SESSION["idempleado"]==6) { // APROBADO
            //     $botonEliminar = '<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar Pedido" onclick="eliminarPedido(' . $reg->idpedido . ')" ><i class="fa fa-trash"></i> </button>&nbsp';
            // } else {ig
            //     $botonEliminar = '';
            // }
            $botonCambiarEstado = '';
            if ($reg->estadoId !== "D") {
                // if (true) { 
                if ($_SESSION["idempleado"] == 17 || $_SESSION["idempleado"] == 6) {
                    // if (true) { 

                    $botonCambiarEstado = '<button class="btn btn-warning" data-toggle="tooltip" title="Cambiar estado" onclick="cambiarEstadoPedido(' . $reg->idpedido . ')" ><i class="fa fa-refresh"></i> </button>&nbsp';
                } else {
                    $botonCambiarEstado = '';
                }
            }

            $fecha_cotizacion='';
            if(strlen($reg->fecha)>0){
                $fecha_cotizacion="<p>$reg->fecha <b>| $reg->prefijo_pedido</b></p><p>";
            }
            $fecha_aprobacion='';
            if(strlen($reg->fecha_apro_coti)>0){
                $fecha_aprobacion="<p>$reg->fecha_apro_coti <b>| $reg->prefijo_estado</b></p><p>";
            }



            $data[] = array(
                "0" => $i,
                "1" => $reg->idsucursal==1?'Arequipa':'Lima' ,
                "2" =>"$fecha_cotizacion $fecha_aprobacion  "
              
                ,
                "3" => $reg->numero,
                "4" =>  explode("|", $reg->empleado)[0],
                "5" => $reg->cliente,
                "6" => $reg->agencia_envio,
                "7" => $fetch->total, //SE OBTIENE LOS DATOS DE LA TABLA PEDIDO
                "8" => $reg->estado,
                "9" => '<button class="btn btn-success" data-toggle="tooltip" title="Ver Detalle" onclick="cargarDataPedido(' . $reg->idpedido . ',\'' . $fetch->total . '\',\'' . $reg->email . '\',\'' . $reg->idcliente . '\',\'' . $reg->empleado . '\',\'' . $reg->cliente . '\',\'' . $reg->num_documento . '\',\'' . $reg->celular . '\',\'' . $reg->destino . '\',\'' . $reg->metodo_pago . '\',\'' . $reg->agencia_envio . '\',\'' . $reg->tipo_promocion . '\',\'' . $reg->observaciones . '\',\'' . $reg->modo_pago . '\'
                ,\'' . $reg->tipo_entrega . '\'

                
                )" ><i class="fa fa-eye"></i> </button>&nbsp' .
                    $botonPasarAVenta .
                    '<a href="./Reportes/exPedido.php?id=' . $reg->idpedido . '" class="btn btn-primary" data-toggle="tooltip" title="Imprimir" target="blanck" ><i class="fa fa-file-text"></i> </a>&nbsp;' .
                    $botonEliminar .
                    $botonCambiarEstado
                // "9" => $reg->numero,
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

    case "GetVenta":
        require_once "../model/Pedido.php";
        $objPedido = new Pedido();
        $idpedido = $_REQUEST["idPedido"];
        $query = $objPedido->VerVenta($idpedido);
        $reg = $query->fetch_object();
        echo json_encode($reg);
        break;

    case "GetDetalleCantStock":
        require_once "../model/Pedido.php";
        $objPedido = new Pedido();
        $query_Pedido = $objPedido->GetDetalleCantStock($_REQUEST["idPedido"]);
        while ($reg = $query_Pedido->fetch_object()) {
            $data[] = array(
                $reg->iddetalle_ingreso,
                $reg->stock_actual,
                $reg->cantidad
            );
        }
        echo json_encode($data);
        break;

    case "GetNextNumero":
        require_once "../model/Pedido.php";
        $objPedido = new Pedido();
        $query_Pedido = $objPedido->GetNextNumero($_SESSION["idsucursal"]);
        $reg = $query_Pedido->fetch_object();
        echo json_encode($reg);
        break;

    case "GetPrimerCliente":
        require_once "../model/Pedido.php";
        $objPedido = new Pedido();
        $query_Pedido = $objPedido->GetPrimerCliente();
        $reg = $query_Pedido->fetch_object();
        echo json_encode($reg);
        break;

    case "GetDetallePedido":
        require_once "../model/Pedido.php";
        $objPedido = new Pedido();
        $idPedido = $_POST["idPedido"];
        $query_prov = $objPedido->GetDetallePedido($idPedido);
        $i = 1;
        while ($reg = $query_prov->fetch_object()) {
            echo '<tr>
                        <td>' . $reg->articulo . '</td>
                        <td>' . $reg->marca . '</td>
                        <td>' . $reg->codigo . '</td>
                        <td>' . $reg->serie . '</td>
                        <td>' . $reg->cantidad . '</td>
                        <td>' . 'S/ .' . $reg->precio_venta . '</td>
                        <td>' . 'S/ .' . $reg->descuento . '</td>
                        <td>' . 'S/ .' . $reg->total . '</td>
                       </tr>';
            $i++;
        }
        break;

    case "TraerCantidad":
        require_once "../model/Pedido.php";
        $objPedido = new Pedido();
        $query_Pedido = $objPedido->TraerCantidad($_REQUEST["idPedido"]);
        while ($reg = $query_Pedido->fetch_object()) {
            $data[] = array(
                $reg->iddetalle_ingreso,
                $reg->cantidad
            );
        }
        echo json_encode($data);
        break;

    case "CambiarEstado":
        require_once "../model/Pedido.php";
        $obj = new Pedido();
        $idPedido = $_POST["idPedido"];
        foreach ($_POST["detalle"] as $indice => $valor) {
            echo $valor[0] . " - ";
        }
        $hosp = $obj->CambiarEstado($idPedido, $_POST["detalle"]);
        if ($hosp) {
            echo "Venta Anulada";
        } else {
            echo "No se ha podido Anular la Venta";
        }
        break;

    case "EliminarPedido":
        require_once "../model/Pedido.php";
        $obj = new Pedido();
        $idPedido = $_POST["idPedido"];
        $hosp = $obj->EliminarPedido($idPedido);
        if ($hosp) {
            echo "Pedido Eliminado";
        } else {
            echo "No se ha podido eliminar el Pedido";
        }
        break;

    case "listClientes":
        require_once "../model/Pedido.php";
        $objPedido = new Pedido();
        $query_cli = $objPedido->ListarClientes();
        $i = 1;
        while ($reg = $query_cli->fetch_object()) {
            echo '<tr>
                        <td><input type="radio" name="optClienteBusqueda" data-nombre="' . $reg->nombre . ' ' . $reg->apellido . ' | ' . $reg->tipo_persona . '" data-email="' . $reg->email . '" id="' . $reg->idpersona . '" value="' . $reg->idpersona . '" data-cliente="' . $reg->tipo_persona . '" data-telefono="' . $reg->telefono . '-' . $reg->telefono_2 . '" data-direccion="' . $reg->direccion_departamento . '" /></td>
                        <td>' . $i . '</td>
                        <td>' . $reg->tipo_persona . '</td>
                        <td>' . $reg->num_documento . '</td>
                        <td>' . $reg->nombre . ' ' . $reg->apellido . '</td>
                        <td>' . $reg->telefono . '</td>
                        <td>' . $reg->direccion_calle . '</td>
                        <td>' . $reg->email . '</td>
                       </tr>';
            $i++;
        }
        break;

    case "listDetIng":
        require_once "../model/Pedido.php";
        $objPedido = new Pedido();
        $query_cli = $objPedido->ListarDetalleIngresos($_SESSION["idsucursal"]);
        $data = array();
        $i = 1;

        while ($reg = $query_cli->fetch_object()) {


            if ($_SESSION["idsucursal"] == $reg->idsucursal && $reg->estado_detalle_ingreso == 'INGRESO') {
                $disabledButton = '';
            } else {
                $disabledButton = 'disabled';
            }



            $data[] = array(
                "0" => '<button type="button" ' . $disabledButton . '  class="btn btn-warning" name="optDetIngBusqueda[]" data-codigo="' . $reg->codigo . '"
                    data-serie="' . $reg->serie . '" data-nombre="' . $reg->Articulo . '" data-precio-venta="' . $reg->precio_ventapublico . '"
                    data-stock-actual="' . $reg->stock_actual . '" id="' . $reg->iddetalle_ingreso . '" value="' . $reg->iddetalle_ingreso . '"
                    data-toggle="tooltip" title="Agregar al carrito"
                    onclick="AgregarPedCarrito(
                        ' . $reg->iddetalle_ingreso . ',
                        \'' . $reg->stock_actual . '\',
                        \'' . $reg->Articulo . '\',
                        \'' . $reg->codigo . '\',
                        \'' . $reg->serie . '\',
                        \'' . $reg->precio_ventapublico . '\',
                        \'' . $reg->idarticulo . '\',
                        \'' . $reg->marca . '\')" >
                    <i class="fa fa-check" ></i> </button>',
                "1" => $reg->razon_social,
                "2" => $reg->estado_n,
                "3" => $reg->codigo,
                "4" => $reg->Articulo,
                "5" => $reg->marca,
                "6" => $reg->serie,
                //"5"=>$reg->presentacion,
                "7" => $reg->stock_actual,
                "8" => $reg->precio_ventapublico,

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

    case "listTipoDoc":
        require_once "../model/Pedido.php";
        $objPedido = new Pedido();
        $query_Categoria = $objPedido->ListarTipoDocumento($_SESSION["idsucursal"]);
        // echo "<option>--Seleccione Comprobante--</option>";
        while ($reg = $query_Categoria->fetch_object()) {
            echo '<option value=' . $reg->nombre . '>' . $reg->nombre . '</option>';
        }
        break;

    case "GetTipoDocSerieNum":
        require_once "../model/Pedido.php";
        $objPedido = new Pedido();
        $nombre = $_REQUEST["nombre"];
        $query_Categoria = $objPedido->GetTipoDocSerieNum($nombre);
        $reg = $query_Categoria->fetch_object();
        echo json_encode($reg);
        break;

    case "GetIdPedido":
        require_once "../model/Pedido.php";
        $objPedido = new Pedido();
        $query_Categoria = $objPedido->GetIdPedido();
        $reg = $query_Categoria->fetch_object();
        echo json_encode($reg);
        break;

    case "GetTotal":
        require_once "../model/Pedido.php";
        $objPedido = new Pedido();
        $query_total = $objPedido->TotalPedido($_REQUEST["idPedido"]);
        $reg_total = $query_total->fetch_object();
        echo json_encode($reg_total);
        break;
    case "GetImagenesEmpaquetado":
        require_once "../model/Pedido.php";
        $objPedido = new Pedido();
        $query_total = $objPedido->GetImagenesEmpaquetado($_REQUEST["idPedido"]);

        //var_dump($query_total->fetch_object());
        //exit;

        while ($reg = $query_total->fetch_object()) {

            // echo '<li>
            //         <a href="./Files/Voucher/' . $reg->imagen . '" target="_blank">
            //         <span class="mailbox-attachment-icon has-img">
            //         <img src="./Files/Voucher/' . $reg->imagen . '">
            //         </span>
            //         </a>
            //         <div class="mailbox-attachment-info">
            //         <a href="./Files/Voucher/' . $reg->imagen . '" class="mailbox-attachment-name" target="_blank">' . $reg->imagen . '</a>
            //          <span class="mailbox-attachment-size"> -
            //         <a href="#" class="btn btn-default btn-xs pull-right"  onclick="eliminarDetalleImagen(' . $reg->id . ',' . $reg->idpedido . ')"
            //         ><i class="fa fa-trash"></i></a>
            //         </span> */
            //         </div>
            //         </li>';


            echo '<li>
                        <a href="./Files/Empaquetado/' . $reg->imagen . '" target="_blank">
                        <span class="mailbox-attachment-icon has-img">
                        <img src="./Files/Empaquetado/' . $reg->imagen . '">
                        </span>
                        </a>
                        <div class="mailbox-attachment-info">
                        <a href="./Files/Empaquetado/' . $reg->imagen . '" class="mailbox-attachment-name" target="_blank">' . $reg->imagen . '</a>
                         
                        
                        </li>';
        }


        break;

    case "GetImagenes":
        require_once "../model/Pedido.php";
        $objPedido = new Pedido();
        $query_total = $objPedido->GetImagenes($_REQUEST["idPedido"]);

        //var_dump($query_total->fetch_object());
        //exit;

        while ($reg = $query_total->fetch_object()) {

            // echo '<li>
            //         <a href="./Files/Voucher/' . $reg->imagen . '" target="_blank">
            //         <span class="mailbox-attachment-icon has-img">
            //         <img src="./Files/Voucher/' . $reg->imagen . '">
            //         </span>
            //         </a>
            //         <div class="mailbox-attachment-info">
            //         <a href="./Files/Voucher/' . $reg->imagen . '" class="mailbox-attachment-name" target="_blank">' . $reg->imagen . '</a>
            //          <span class="mailbox-attachment-size"> -
            //         <a href="#" class="btn btn-default btn-xs pull-right"  onclick="eliminarDetalleImagen(' . $reg->id . ',' . $reg->idpedido . ')"
            //         ><i class="fa fa-trash"></i></a>
            //         </span> */
            //         </div>
            //         </li>';


            echo '<li>
                    <a href="./Files/Voucher/' . $reg->imagen . '" target="_blank">
                    <span class="mailbox-attachment-icon has-img">
                    <img src="./Files/Voucher/' . $reg->imagen . '">
                    </span>
                    </a>
                    <div class="mailbox-attachment-info">
                    <a href="./Files/Voucher/' . $reg->imagen . '" class="mailbox-attachment-name" target="_blank">' . $reg->imagen . '</a>
                     
                    
                    </li>';
        }


        break;
    case "DeleteImagenes":
        require_once "../model/Pedido.php";
        $objPedido = new Pedido();
        $query = $objPedido->DeleteImagenes($_REQUEST["id"]);
        $result = $query->fetch_object();
        echo json_encode($result);
        break;


    case "cambiarEstadoPedido":

        require_once "../model/Pedido.php";
        $obj = new Pedido();
        $idPedido = $_POST["idPedido"];
        $hosp = $obj->cambiarEstadoPedido($idPedido);
        if ($hosp) {
            echo "Estado de pedido cambiado";
        } else {
            echo "No se ha podido eliminar el Pedido";
        }
        break;

    case "GetCodigoAleatorio":
        require_once "../model/Pedido.php";
        $objPedido = new Pedido();

        $permitted_chars = '123456789';
        // Output: 54esmdr0qf 
        $codigoAletorio = substr(str_shuffle($permitted_chars), 0, 10);
        //$codigoAletorio = 17188;

        $query = $objPedido->GetCodigoAleatorio($codigoAletorio);
        $reg = $query->fetch_object();

        if (is_null($reg)) {
            echo json_encode($codigoAletorio);
        } else {
            echo json_encode($reg);
        }

        break;
}
