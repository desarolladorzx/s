<?php
	session_start();
	require_once "../model/Venta.php";
	require_once "../model/Persona.php";

	$objVenta = new Venta();
	$objCliente = new Persona();
	switch ($_GET["op"]) {
		case 'SaveOrUpdate':

			$idCliente = $_POST["idCliente"];
			$idpedido = $_POST["idPedido"];
			$idusuario = $_POST["idUsuario"];
			$tipo_venta = $_POST["tipo_venta"];
			$iddetalle_doc_suc = $_POST["iddetalle_doc_suc"];
			$tipo_comprobante = $_POST["tipo_comprobante"];
			$serie_comprobante = $_POST["serie_vent"];
			$num_comprobante = $_POST["num_vent"];

			$tipo_promocion = $_POST["tipo_promocion"];
			$metodo_pago = $_POST["metodo_pago"];
			$agencia_envio = $_POST["agencia_envio"];


			/* $tipo_promocion = $_POST["tipo_promocion"];
			$metodo_pago = $_POST["metodo_pago"];
			$num_operacion = $_POST["num_operacion"];
			$hora_operacion = $_POST["hora_operacion"];
			$agencia_envio = $_POST["agencia_envio"]; */
			$impuesto = $_POST["impuesto"];
			$total = $_POST["total_vent"];
			$estado = "A";
			$entero = intval($num_comprobante);
			$cant_letra = strlen($entero);
			$parte_izquierda = substr($num_comprobante, 0, -$cant_letra);
			$suma = $entero + 1;
			$numero = $parte_izquierda."".$suma;

			/* CONSULTAR NUMERO DE PEDIDOS POR ID CLIENTE */
			$rptaBuscarExistePedido = $objCliente->BuscarExistePedido($idCliente);
			$resultExiste = $rptaBuscarExistePedido->fetch_object();

			if ($resultExiste->countidpedido >= 2) {
				$estadoCuenta = "ANTIGUO";
			} else {
				$estadoCuenta = "NUEVO";
			}

			/* ACTUALIZA SEGUN NUMERO DE PEDIDOS - LA CUENTE DE CLIENTE */
			$objCliente->ActualizarCuentaCliente($idCliente,$estadoCuenta);
			
 
			if(empty($_POST["txtIdVenta"])){
				if($objVenta->Registrar($idpedido,$idusuario,$tipo_venta,$tipo_comprobante,$serie_comprobante,$num_comprobante,$impuesto,$total,$estado, $numero, $iddetalle_doc_suc, $_POST["detalle"],$tipo_promocion,$metodo_pago,$agencia_envio)){
						echo "Venta Registrada correctamente.";
				}else{
						echo "Venta no ha podido ser registado.";
				}
			}else{
				$idVenta = $_POST["txtIdVenta"];
				if($objVenta->Modificar($idventa,$idpedido, $idusuario,$tipo_venta,$tipo_comprobante,$serie_comprobante,$num_comprobante,$impuesto,$total,$estado)){
					echo "La información del Venta ha sido actualizada.";
				}else{
					echo "La información del Venta no ha podido ser actualizada.";
				}
			}

			break;

		case "delete":
			
			$id = $_POST["id"];// Llamamos a la variable id del js que mandamos por $.post (Categoria.js (Linea 62))
			$result = $objVenta->Eliminar($id);
			if ($result) {
				echo "Eliminado Exitosamente";
			} else {
				echo "No fue Eliminado";
			}
			break;
		
/* 		case "list":
			$query_Tipo = $objVenta->Listar();
            $data = Array();
        	$i = 1;
     		while ($reg = $query_Tipo->fetch_object()) {
	             echo '<tr>
		                <td>'.$i.'</td>
		                <td>'.$reg->apellidos.'&nbsp;'.$reg->nombre.'</td>
		                <td>'.$reg->tipo_documento.'&nbsp;'.$reg->num_documento.'</td>
		                <td>'.$reg->email.'</td>
		                <td>'.$reg->telefono.'</td>
		                <td>'.$reg->login.'</td>
		                <td><img width=100px height=100px src="./'.$reg->foto.'" /></td>
		                <td><button class="btn btn-warning" onclick="cargarDataVenta('.$reg->idVenta.',\''.$reg->apellidos.'\',\''.$reg->nombre.'\',\''.$reg->tipo_documento.'\',\''.$reg->num_documento.'\',\''.$reg->direccion.'\',\''.$reg->telefono.'\',\''.$reg->email.'\',\''.$reg->fecha_nacimiento.'\',\''.$reg->foto.'\',\''.$reg->login.'\',\''.$reg->clave.'\',\''.$reg->estado.'\')"><i class="fa fa-pencil"></i> </button></td>
                        <td><button class="btn btn-danger" onclick="eliminarVenta('.$reg->idVenta.')"><i class="fa fa-trash"></i> </button></td>
	                   </tr>';
	             $i++; 
            } 
			break; */

		// case "listTipoPedidoPedido":	
		// 	require_once "../model/Pedido.php";
		// 	$objPed = new Pedido();

		// 	$query_Tipo = $objPed->ListarTipoPedidoPedido($_SESSION["idsucursal"]);
		// 	$data = Array();
        //     $i = 1;
		// 	while ($reg = $query_Tipo->fetch_object()) {
		// 		$regTotal = $objPed->GetTotal($reg->idpedido);
		// 		$fetch = $regTotal->fetch_object();
		// 		$data[] = array(
		// 		   "0"=>$i,
		// 		   "1"=>$reg->Cliente,
		// 		   "2"=>$reg->tipo_pedido,
		// 		   "3"=>$reg->fecha,
		// 		   "4"=>'<button class="btn btn-success" data-toggle="tooltip" title="Ver Detalle" onclick="cargarDataPedido('.$reg->idpedido.',\''.$reg->tipo_pedido.'\',\''.$reg->numero.'\',\''.$reg->Cliente.'\',\''.$reg_total->Total.'\',\''.$reg->email.'\',\''.$reg->direccion_calle.'\',\''.$reg->num_documento.'\',\''.$reg->telefono.'\',\''.$reg->fecha.'\')" ><i class="fa fa-eye"></i> </button>&nbsp'.
		// 		   '<button class="btn btn-success" onclick="pasarIdPedido('.$reg->idpedido.',\''.$reg->tipo_pedido.'\',\''.$reg->numero.'\',\''.$reg->Cliente.'\',\''.$reg_total->Total.'\',\''.$reg->email.'\',\''.$reg->direccion_calle.'\',\''.$reg->num_documento.'\',\''.$reg->telefono.'\',\''.$reg->fecha.'\')"><i class="fa fa-shopping-cart"></i> </button>&nbsp'.
		// 		   '<a href="./Reportes/exPedido.php?id='.$reg->idpedido.'" class="btn btn-primary" data-toggle="tooltip" title="Imprimir" target="blanck" ><i class="fa fa-file-text"></i> </a>&nbsp;'.
		// 		   '<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar Pedido" onclick="eliminarPedido('.$reg->idpedido.')" ><i class="fa fa-trash"></i> </button>&nbsp'
		// 		   );
		// 	   $i++;
		//    }
        //     $results = array(
        //     "sEcho" => 1,
        // 	"iTotalRecords" => count($data),
        // 	"iTotalDisplayRecords" => count($data),
        //     "aaData"=>$data);
		// 	echo json_encode($results);            
		// 	break;

		case "list":
			require_once "../model/Pedido.php";
			$data= Array();
			$objPedido = new Pedido();
			if ( !isset($_SESSION['idsucursal']))
			{
				$_SESSION['idsucursal'] = 1;
			}
			$query_Pedido = $objPedido->Listar($_SESSION["idsucursal"]);
			$i = 1;
			while ($reg = $query_Pedido->fetch_object()) {
				$query_total = $objPedido->TotalPedido($reg->idpedido);
				$reg_total = $query_total->fetch_object();
				$data[] = array(
					"0"=>$i,
					"1"=>$reg->fecha,
					"2"=>$reg->ticket,
					"3"=>$reg->cliente,
					"4"=>($reg->tipo_pedido=="Pedido")?'<span class="badge bg-blue">Pedido</span>':(($reg->tipo_pedido=="Venta")?'<span class="badge bg-aqua">Venta</span>':'<span class="badge bg-green">Proforma</span>'),
					//"4"=>$reg_direc->direccion_calle, --- MUESTRA LA VENTANA DE VENTAS
					"5"=>$reg_total->Total,//SE OBTIENE LOS DATOS DE LA TABLA PEDIDO
					"6"=>($reg->estado=="A")?'<span class="badge bg-green">ACTIVO</span>':'<span class="badge bg-red">CANCELADO</span>',
					"7"=>($reg->estado=="A")?'<button class="btn btn-success" data-toggle="tooltip" title="Ver Detalle" onclick="cargarDataPedido('.$reg->idpedido.',\''.$reg->tipo_pedido.'\',\''.$reg->numero.'\',\''.$reg->cliente.'\',\''.$reg_total->Total.'\',\''.$reg->email.'\',\''.$reg->num_documento.'\',\''.$reg->celular.'\',\''.$reg->tipo_cliente.'\',\''.$reg->destino.'\',\''.$reg->ticket.'\',\''.$reg->aproba_venta.'\',\''.$reg->aproba_pedido.'\',\''.$reg->empleado.'\',\''.$reg->metodo_pago.'\',\''.$reg->agencia_envio.'\',\''.$reg->tipo_promocion.'\')" ><i class="fa fa-eye"></i> </button>&nbsp'.

					/* '<button class="btn btn-warning" data-toggle="tooltip" title="Anular VENTASASSS" onclick="cancelarPedido('.$reg->idpedido.')" ><i class="fa fa-times-circle"></i> </button>&nbsp'. */
					'<a href="./Reportes/exTicket.php?id='.$reg->idpedido.'" class="btn btn-primary" data-toggle="tooltip" title="Imprimir" target="blanck" ><i class="fa fa-file-text"></i> </a>':
					'<button class="btn btn-success" data-toggle="tooltip" title="Ver Detalle" onclick="cargarDataPedido('.$reg->idpedido.',\''.$reg->tipo_pedido.'\',\''.$reg->numero.'\',\''.$reg->cliente.'\',\''.$reg_total->Total.'\',\''.$reg->num_documento.'\',\''.$reg->celular.'\',\''.$reg->tipo_cliente.'\',\''.$reg->destino.'\',\''.$reg->ticket.'\',\''.$reg->aproba_venta.'\',\''.$reg->aproba_pedido.'\',\''.$reg->empleado.'\',\''.$reg->metodo_pago.'\',\''.$reg->agencia_envio.'\',\''.$reg->tipo_promocion.'\')" ><i class="fa fa-eye"></i> </button>&nbsp'.
					'<a href="./Reportes/exTicket.php?id='.$reg->idpedido.'" class="btn btn-primary" data-toggle="tooltip" title="Imprimir" target="blanck" ><i class="fa fa-file-text"></i> </a>&nbsp;');
				$i++;
			}
				$results = array(
				"sEcho" => 1,
				"iTotalRecords" => count($data),
				"iTotalDisplayRecords" => count($data),
				"aaData"=>$data);
				echo json_encode($results);
			break;
	
		case "listAdmin":
				require_once "../model/Pedido.php";
				$data= Array();
				$objPedido = new Pedido();
				if ( !isset($_SESSION['idsucursal']))
				{
					$_SESSION['idsucursal'] = 1;
				}
				$query_Pedido = $objPedido->Listar($_SESSION["idsucursal"]);
		
				$i = 1;
				while ($reg = $query_Pedido->fetch_object()) {
					$query_total = $objPedido->TotalPedido($reg->idpedido);
					$reg_total = $query_total->fetch_object();
					/* $data[] = array("0"=>$i,
						"1"=>$reg->Cliente.'&nbsp;'.$reg->APCliente,
						"2"=>($reg->tipo_pedido=="Pedido")?'<span class="badge bg-blue">Pedido</span>':(($reg->tipo_pedido=="Venta")?'<span class="badge bg-aqua">Venta</span>':'<span class="badge bg-green">Proforma</span>'),
						"3"=>$reg->fecha,
						//"4"=>$reg_direc->direccion_calle, --- MUESTRA LA VENTANA DE VENTAS
						"4"=>$reg_total->Total,//SE OBTIENE LOS DATOS DE LA TABLA PEDIDO
						"5"=>($reg->estado=="A")?'<span class="badge bg-green">ACEPTADO</span>':'<span class="badge bg-red">CANCELADO</span>',
						"6"=>($reg->estado=="A")?'<button class="btn btn-success" data-toggle="tooltip" title="Ver Detalle" onclick="cargarDataPedido('.$reg->idpedido.',\''.$reg->tipo_pedido.'\',\''.$reg->numero.'\',\''.$reg->Cliente.'\',\''.$reg_total->Total.'\',\''.$reg->email.'\',\''.$reg->direccion_calle.'\',\''.$reg->num_documento.'\',\''.$reg->telefono.'\',\''.$reg->fecha.'\')" ><i class="fa fa-eye"></i> </button>&nbsp'.
						'<button class="btn btn-warning" data-toggle="tooltip" title="ANULAR VENTA" onclick="cancelarPedido('.$reg->idpedido.')" ><i class="fa fa-times-circle"></i> </button>&nbsp'.
						'<a href="./Reportes/exTicket.php?id='.$reg->idpedido.'" class="btn btn-primary" data-toggle="tooltip" title="Imprimir" target="blanck" ><i class="fa fa-file-text"></i> </a>':
						'<button class="btn btn-success" data-toggle="tooltip" title="Ver Detalle" onclick="cargarDataPedido('.$reg->idpedido.',\''.$reg->tipo_pedido.'\',\''.$reg->numero.'\',\''.$reg->Cliente.'\',\''.$reg_total->Total.'\',\''.$reg->direccion_calle.'\',\''.$reg->num_documento.'\',\''.$reg->telefono.'\',\''.$reg->fecha.'\')" ><i class="fa fa-eye"></i> </button>&nbsp'.
						'<a href="./Reportes/exTicket.php?id='.$reg->idpedido.'" class="btn btn-primary" data-toggle="tooltip" title="Imprimir" target="blanck" ><i class="fa fa-file-text"></i> </a>&nbsp;');
					$i++; */
					$data[] = array("0"=>$i,
					"1"=>$reg->fecha,
					"2"=>$reg->ticket,
					"3"=>$reg->cliente,
					"4"=>($reg->tipo_pedido=="Pedido")?'<span class="badge bg-blue">Pedido</span>':(($reg->tipo_pedido=="Venta")?'<span class="badge bg-aqua">Venta</span>':'<span class="badge bg-green">Proforma</span>'),
					//"4"=>$reg_direc->direccion_calle, --- MUESTRA LA VENTANA DE VENTAS
					"5"=>$reg_total->Total,//SE OBTIENE LOS DATOS DE LA TABLA PEDIDO
					"6"=>($reg->estado=="A")?'<span class="badge bg-green">ACEPTADO</span>':'<span class="badge bg-red">CANCELADO</span>',
					"7"=>($reg->estado=="A")?'<button class="btn btn-success" data-toggle="tooltip" title="Ver Detalle" onclick="cargarDataPedido('.$reg->idpedido.',\''.$reg->tipo_pedido.'\',\''.$reg->numero.'\',\''.$reg->cliente.'\',\''.$reg_total->Total.'\',\''.$reg->email.'\',\''.$reg->num_documento.'\',\''.$reg->celular.'\',\''.$reg->tipo_cliente.'\',\''.$reg->destino.'\',\''.$reg->ticket.'\',\''.$reg->aproba_venta.'\',\''.$reg->aproba_pedido.'\',\''.$reg->empleado.'\',\''.$reg->metodo_pago.'\',\''.$reg->agencia_envio.'\',\''.$reg->tipo_promocion.'\')" ><i class="fa fa-eye"></i> </button>&nbsp'.

					'<button class="btn btn-warning" data-toggle="tooltip" title="Anular VENTASASSS" onclick="cancelarPedido('.$reg->idpedido.')" ><i class="fa fa-times-circle"></i> </button>&nbsp'. 
					'<a href="./Reportes/exTicket.php?id='.$reg->idpedido.'" class="btn btn-primary" data-toggle="tooltip" title="Imprimir" target="blanck" ><i class="fa fa-file-text"></i> </a>':
					'<button class="btn btn-success" data-toggle="tooltip" title="Ver Detalle" onclick="cargarDataPedido('.$reg->idpedido.',\''.$reg->tipo_pedido.'\',\''.$reg->numero.'\',\''.$reg->cliente.'\',\''.$reg_total->Total.'\',\''.$reg->num_documento.'\',\''.$reg->celular.'\',\''.$reg->tipo_cliente.'\',\''.$reg->destino.'\',\''.$reg->ticket.'\',\''.$reg->aproba_venta.'\',\''.$reg->aproba_pedido.'\',\''.$reg->empleado.'\',\''.$reg->metodo_pago.'\',\''.$reg->agencia_envio.'\',\''.$reg->tipo_promocion.'\')" ><i class="fa fa-eye"></i> </button>&nbsp'.
					'<a href="./Reportes/exTicket.php?id='.$reg->idpedido.'" class="btn btn-primary" data-toggle="tooltip" title="Imprimir" target="blanck" ><i class="fa fa-file-text"></i> </a>&nbsp;');
				$i++;
				}
					$results = array(
					"sEcho" => 1,
					"iTotalRecords" => count($data),
					"iTotalDisplayRecords" => count($data),
					"aaData"=>$data);
					echo json_encode($results);
				break;
			
		case "listTipo_DocumentoPersona":
		        require_once "../model/Tipo_Documento.php";

		        $objTipo_Documento = new Tipo_Documento();

		        $query_tipo_Documento = $objTipo_Documento->VerTipo_Documento_Persona();

		        while ($reg = $query_tipo_Documento->fetch_object()) {
		            echo '<option value=' . $reg->nombre . '>' . $reg->nombre . '</option>';
		        }
		    break;

		case "GetTipoDocSerieNum":

            $nombre = $_REQUEST["nombre"];
            $idsucursal = $_REQUEST["idsucursal"];
            $query_Categoria = $objVenta->GetTipoDocSerieNum($nombre,$idsucursal);
            $reg = $query_Categoria->fetch_object();
            echo json_encode($reg);
            break;

        case "EnviarCorreo":
        	require_once "../PHPMailer/class.phpmailer.php";
			$server = $_SERVER["HTTP_HOST"];
			$idPedido = $_POST["idPedido"];
			$result = $_POST["result"];
			$sucursal = $_SESSION["sucursal"];
			$email = $_SESSION["email"];
			$mail = new PHPMailer;
			$mail->Host = "$server";
			$mail->From = "$email";
			$mail->FromName = "$sucursal - Administracion";
			$mail->Subject = "$sucursal - Detalle de compra";
			$mail->addAddress("$result", "Alex");
			$mail->MsgHTML("Puede ver el detalle de su compra haciendo click <a href='$server/Reportes/exVenta.php?id=".$idPedido."'> Aqui</a>");

			if ($mail->Send()) {
			 	echo "Enviado con éxito";
			} else {
				echo "Venta Registrada correctamente. No se pudo realizar el envio";
			}
			break;

		case 'VerificarStockProductos':
			require_once "../model/Venta.php";
			$objDetalleIngreso = new Venta();

			$detalle = $_GET["detalle"];

			//var_dump($detalle);
			//exit;

			foreach($detalle as $indice => $valor){

				$cantidadProducto = $valor[2];
				
				// BUSCA EN TABLA DETALLE INGRESO, EL STOCK ACTUAL DE LOS PRODUCTOS
				$query_DetalleIngreso = $objDetalleIngreso->buscarDetalleIngreso($valor[0]);
				$reg = $query_DetalleIngreso->fetch_object();
				
				

				$stockActual = $reg->stock_actual;
				$descripcionProducto = $reg->descripcion;

				// SI EL STOCK ACTUAL ES MENOR A LA CANTIDA A DESCONTAR, REGISTRA UN FALSE PARA INDICA QUE NO SE PUEDE REALIZAR LA ACCION
				if ($stockActual>=$cantidadProducto) {
					$dataProd = "";
					$result = true;
					$dataDet = "";
				}else{
					$dataProd = $descripcionProducto;
					//$result = array('estado'=>false,'detalle'=>$dataProd);
					$result = false;
					$dataDet = '- '.$dataProd.' [x'.$stockActual.']';
				}

				$data[] = $result;

			}

			//var_dump($dataDet);
			//exit;

			// SE ANALIZA ARRAY DATA; SI SE ENCUENTRA ALGUN FALSE, DEVUELVE FALSE Y NO PROCEDE A CAMBIAR COTIZACION A VENTA
			if (in_array(false, $data)) {
				//echo json_encode(false,$dataDet);
				$estado = false;
			}else{
				//echo json_encode(true,$dataDet);
				$estado = true;
			}

			$results = array(
				'estado' => $estado,
				'detalle' => $dataDet);

			echo json_encode($results,true);

		break;

		case 'VerificarStockProductos_CambiarEstado':

			require_once "../model/Pedido.php";
			$objPedido = new Pedido();

			$idPedido = $_GET["idPedido"];

			$query_prov = $objPedido->GetDetallePedido($idPedido);
			$i = 1;
				while ($reg = $query_prov->fetch_object()) {

					$resultsDetalle[] = array(
						$reg->articulo,
						$reg->codigo,
						$reg->serie,
						$reg->marca,
						$reg->iddetalle_pedido,
						$reg->idpedido,
						$reg->iddetalle_ingreso,
						$reg->cantidad,
						$reg->precio_venta,
						$reg->descuento,
						$reg->total
					);

				}

			require_once "../model/Venta.php";
			$objDetalleIngreso = new Venta();

			//var_dump($resultsDetalle);

			foreach($resultsDetalle as $valor){
				

				//exit;
				$cantidadProducto = $valor[7];
				
				// BUSCA EN TABLA DETALLE INGRESO, EL STOCK ACTUAL DE LOS PRODUCTOS
				$query_DetalleIngreso = $objDetalleIngreso->buscarDetalleIngreso($valor[6]);
				$reg = $query_DetalleIngreso->fetch_object();
				
				
				$stockActual = $reg->stock_actual;
				$descripcionProducto = $reg->descripcion;

				// SI EL STOCK ACTUAL ES MENOR A LA CANTIDA A DESCONTAR, REGISTRA UN FALSE PARA INDICA QUE NO SE PUEDE REALIZAR LA ACCION

				// var_dump($stockActual);
				// var_dump($cantidadProducto);
				if ($stockActual>=$cantidadProducto) {
					$dataProd = "";
					$result = true;
					$dataDet = "";
				}else{
					$dataProd = $descripcionProducto;
					//$result = array('estado'=>false,'detalle'=>$dataProd);
					$result = false;
					$dataDet[] = '- '.$dataProd.' [x'.$stockActual.']';
				}

				$data[] = $result;
				

			}


			// SE ANALIZA ARRAY DATA; SI SE ENCUENTRA ALGUN FALSE, DEVUELVE FALSE Y NO PROCEDE A CAMBIAR COTIZACION A VENTA
	
			if (in_array(false, $data)) {
				//echo json_encode(false,$dataDet);
				$estado = false;
			}else{
				//echo json_encode(true,$dataDet);
				$estado = true;
			}

			$results = array(
				'estado' => $estado,
				'detalle' => $dataDet);

			echo json_encode($results,true);

		break;





	}