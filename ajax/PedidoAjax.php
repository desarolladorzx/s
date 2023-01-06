<?php
session_start();
switch ($_GET["op"]) {

    case 'Save':
    require_once "../model/Pedido.php";
    $obj= new Pedido();

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


        /*
        $tipo_promocion = $_POST["tipo_promocion"];
        $metodo_pago = $_POST["metodo_pago"];
        $agencia_envio = $_POST["agencia_envio"];
        */
        //$imagen = $_FILES["imagenVoucher"]["tmp_name"];
		//$ruta = $_FILES["imagenVoucher"]["name"];
        $numero = $_POST["numero"];

        

        //if(move_uploaded_file($imagen, "../Files/Voucher/".$ruta)){
            if(empty($_POST["idPedido"])){
                $hosp = $obj->Registrar($idCliente, $idUsuario, $idSucursal, $tipo_pedido, $numero, $_POST["detalle"], $metodo_pago, $agencia_envio, $tipo_promocion);

                //var_dump($hosp);exit;

                $estadoResult = $hosp[0];
                $idpedido = $hosp[1];

                //var_dump($estadoResult,$idpedido);

                if ($numero==""){
                    $numero="1";
                }else{
        
                    //$numero = $_POST["numero"];
                    //move_uploaded_file($tmp_name, "../Files/Voucher/".$name);
                    
                    
                    if(!empty($_FILES["fileupload"])){

                        $file_names = $_FILES['fileupload']['name'];
        
                        for ($i = 0; $i < count($file_names); $i++) {
                            
                            $file_name=$file_names[$i];

                            $parte = explode(".", $file_name);
                            // echo $parte[0]; // nombre del archivo
                            // echo $parte[1]; // extension del archivo

                            $codigoInterno = strtotime(date('Y-m-d H:i:s'));
                            $new_file_name = str_replace(' ', '-',$parte[0].'-'.$codigoInterno.'.'.$parte[1]);
        
                            // GUARDAR IMAGENES CON EL NUMERO DE COTIZACION
                            $obj->RegistrarDetalleImagenes($idpedido,$idCliente,$idUsuario,$idSucursal,$new_file_name);
        
                            //$extension = end(explode(".", $file_name));
                            //$original_file_name = pathinfo($file_name, PATHINFO_FILENAME);
                            //$file_url = $original_file_name . "-" . date("YmdHis") . "." . $extension;
                            move_uploaded_file($_FILES["fileupload"]["tmp_name"][$i], "../Files/Voucher/".$new_file_name);
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
        
        
        
        
	case "listTipoPedidoPedido":	
			require_once "../model/Pedido.php";
			$objPed = new Pedido();

			$query_Tipo = $objPed->ListarTipoPedidoPedido($_SESSION["idsucursal"]);
			$data = Array();
            $i = 1;
     		while ($reg = $query_Tipo->fetch_object()) {
     			$regTotal = $objPed->GetTotal($reg->idpedido);
     			$fetch = $regTotal->fetch_object();
     			$data[] = array(
     				"0"=>$i,
                    "1"=>$reg->fecha,
                    "2"=>$reg->Cliente.'&nbsp;'.$reg->APCliente,
                    "3"=>$reg->tipo_pedido,
                    "4"=>$fetch->total,//SE OBTIENE LOS DATOS DE LA TABLA PEDIDO
                    "5"=>$reg->estado,
                    "6"=>'<button class="btn btn-success" data-toggle="tooltip" title="Ver Detalle" onclick="cargarDataPedido('.$reg->idpedido.',\''.$reg->tipo_pedido.'\',\''.$reg->numero.'\',\''.$reg->Cliente.'\',\''.$fetch->total.'\')" ><i class="fa fa-eye"></i> </button>&nbsp'.
                    '<button class="btn btn-success" data-toggle="tooltip" title="Generar Venta" onclick="pasarIdPedido('.$reg->idpedido.',\''.$fetch->total.'\',\''.$reg->email.'\',\''.$reg->idcliente.'\',\''.$reg->metodo_pago.'\',\''.$reg->agencia_envio.'\',\''.$reg->tipo_promocion.'\',\''.$reg->tipo_promocion.'\',\''.$reg->tipo_promocion.'\')"><i class="fa fa-shopping-cart"></i> </button>&nbsp'.
                    '<a href="./Reportes/exPedido.php?id='.$reg->idpedido.'" class="btn btn-primary" data-toggle="tooltip" title="Imprimir" target="blanck" ><i class="fa fa-file-text"></i> </a>&nbsp;'.
                    '<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar Pedido" onclick="eliminarPedido('.$reg->idpedido.')" ><i class="fa fa-trash"></i> </button>&nbsp'.
                    '<button class="btn btn-warning" data-toggle="tooltip" title="Cambiar estado" onclick="cambiarEstadoPedido('.$reg->idpedido.')" ><i class="fa fa-refresh"></i> </button>&nbsp'
                    );
                $i++;
            }
            $results = array(
            "sEcho" => 1,
        	"iTotalRecords" => count($data),
        	"iTotalDisplayRecords" => count($data),
            "aaData"=>$data);
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
                $data[] = array($reg->iddetalle_ingreso,
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
                        <td>'.$reg->articulo.'</td>
                        <td>'.$reg->codigo.'</td>
                        <td>'.$reg->serie.'</td>
                        <td>'.$reg->precio_venta.'</td>
                        <td>'.$reg->cantidad.'</td>
                        <td>'.$reg->descuento.'</td>
                       </tr>';
                 $i++; 
            }
        break;

    case "TraerCantidad" :
        require_once "../model/Pedido.php";
        $objPedido = new Pedido();
        $query_Pedido = $objPedido->TraerCantidad($_REQUEST["idPedido"]);
            while ($reg = $query_Pedido->fetch_object()) {
                $data[] = array($reg->iddetalle_ingreso,
                    $reg->cantidad
                    );
            }
        echo json_encode($data);
        break;

    case "CambiarEstado" :
        require_once "../model/Pedido.php";
        $obj= new Pedido();
        $idPedido = $_POST["idPedido"];
        foreach($_POST["detalle"] as $indice => $valor){
            echo $valor[0]. " - ";
        }
        $hosp = $obj->CambiarEstado($idPedido, $_POST["detalle"]);
                if ($hosp) {
                    echo "Venta Anulada";
                } else {
                    echo "No se ha podido Anular la Venta";
                }
        break;

    case "EliminarPedido" :
        require_once "../model/Pedido.php";
        $obj= new Pedido();
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
                        <td><input type="radio" name="optClienteBusqueda" data-nombre="'.$reg->nombre.' '.$reg->apellido.' | '.$reg->tipo_persona.'" data-email="'.$reg->email.'" id="'.$reg->idpersona.'" value="'.$reg->idpersona.'" data-cliente="'.$reg->tipo_persona.'" data-telefono="'.$reg->telefono.'-'.$reg->telefono_2.'" data-direccion="'.$reg->direccion_departamento.'" /></td>
                        <td>'.$i.'</td>
                        <td>'.$reg->tipo_persona.'</td>
                        <td>'.$reg->num_documento.'</td>
                        <td>'.$reg->nombre.' '.$reg->apellido.'</td>
                        <td>'.$reg->telefono.'</td>
                        <td>'.$reg->direccion_calle.'</td>
                        <td>'.$reg->email.'</td>
                       </tr>';
                 $i++; 
            }
        break;

    case "listDetIng":
        require_once "../model/Pedido.php";
        $objPedido = new Pedido();
        $query_cli = $objPedido->ListarDetalleIngresos($_SESSION["idsucursal"]);
        $data= Array();
        $i = 1;
            while ($reg = $query_cli->fetch_object()) {
                $data[] = array(
                    "0"=>'<button type="button" class="btn btn-warning" name="optDetIngBusqueda[]" data-codigo="'.$reg->codigo.'"
                    data-serie="'.$reg->serie.'" data-nombre="'.$reg->Articulo.'" data-precio-venta="'.$reg->precio_ventapublico.'"
                    data-stock-actual="'.$reg->stock_actual.'" id="'.$reg->iddetalle_ingreso.'" value="'.$reg->iddetalle_ingreso.'"
                    data-toggle="tooltip" title="Agregar al carrito"
                    onclick="AgregarPedCarrito('.$reg->iddetalle_ingreso.',\''.$reg->stock_actual.'\',\''.$reg->Articulo.'\',\''.$reg->codigo.'\',\''.$reg->serie.'\',\''.$reg->precio_ventapublico.'\')" >
                    <i class="fa fa-check" ></i> </button>',
                    "1"=>$reg->codigo,
                    "2"=>$reg->Articulo,
                    "3"=>$reg->marca,
                    "4"=>$reg->serie,
                    //"5"=>$reg->presentacion,
                    "5"=>$reg->stock_actual,
                    "6"=>$reg->precio_ventapublico,
                    "7"=>'<img width=100px height=100px src="./'.$reg->imagen.'" />'
                    );
                $i++;
            }

            $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData"=>$data);
            echo json_encode($results);
            break;

     case "listTipoDoc":
            require_once "../model/Pedido.php";
            $objPedido = new Pedido();
            $query_Categoria = $objPedido->ListarTipoDocumento($_SESSION["idsucursal"]);
            echo "<option>--Seleccione Comprobante--</option>";
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
    case "GetImagenes":
            require_once "../model/Pedido.php";
            $objPedido = new Pedido();
            $query_total = $objPedido->GetImagenes($_REQUEST["idPedido"]);

            //var_dump($query_total->fetch_object());
            //exit;

            while ($reg = $query_total->fetch_object()) {

                echo '<li>
                    <a href="./Files/Voucher/'.$reg->imagen.'" target="_blank">
                    <span class="mailbox-attachment-icon has-img">
                    <img src="./Files/Voucher/'.$reg->imagen.'">
                    </span>
                    </a>
                    <div class="mailbox-attachment-info">
                    <a href="./Files/Voucher/'.$reg->imagen.'" class="mailbox-attachment-name" target="_blank">'.$reg->imagen.'</a>
                    <span class="mailbox-attachment-size"> -
                    <a href="#" class="btn btn-default btn-xs pull-right"  onclick="eliminarDetalleImagen('.$reg->id.','.$reg->idpedido.')"><i class="fa fa-trash"></i></a>
                    </span>
                    </div>
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

                
    case "cambiarEstadoPedido" :
            require_once "../model/Pedido.php";
            $obj= new Pedido();
            $idPedido = $_POST["idPedido"];
            $hosp = $obj->cambiarEstadoPedido($idPedido);
                    if ($hosp) {
                        echo "Estado de pedido cambiado";
                    } else {
                        echo "No se ha podido eliminar el Pedido";
                    }
            break;


}