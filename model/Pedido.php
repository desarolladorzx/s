<?php

require "Conexion.php";
class Pedido
{
	public function DatosUsuario($id_usuario)
	{
		global $conexion;
		$sql = "SELECT * from empleado where idempleado=$id_usuario";
		$query = $conexion->query($sql);
		return $query;
	}

	public function Registrar($idcliente, $idusuario, $idsucursal, $tipo_pedido, $numero, $detalle, $metodo_pago, $agencia_envio, $tipo_promocion,$modo_pago,$observacion,$tipo_entrega)
	{

		//var_dump($detalle);exit;

		global $conexion;
		$sw = true;
		try {
			//exit;
			$sql = "INSERT INTO pedido(idcliente, idusuario, idsucursal, tipo_pedido, fecha,  numero, estado, metodo_pago, agencia_envio, tipo_promocion,modo_pago,observacion,tipo_entrega)
						VALUES($idcliente, $idusuario, $idsucursal, '$tipo_pedido', CURRENT_TIMESTAMP(),'$numero','A','$metodo_pago','$agencia_envio','$tipo_promocion','$modo_pago','$observacion','$tipo_entrega')";
			//var_dump($sql);
			$conexion->query($sql);
			// echo ($sql);
			$idpedido = $conexion->insert_id;
			$conexion->autocommit(true);



			for ($i = 0; $i < count($detalle); $i++) {

				$array = explode(",", $detalle[$i]);

				// print_r($array);

				$sql_detalle = "INSERT INTO detalle_pedido(idpedido, iddetalle_ingreso, cantidad, precio_venta, descuento, idarticulo)
											VALUES($idpedido, '" . $array[0] . "', '" . $array[3] . "', '" . $array[2] . "', '" . $array[4] . "', '" . $array[8] . "')";

				$conexion->query($sql_detalle) or $sw = false;


				// INSERTA REGISTROS DE KARDEX
				/*
					$fecact = date('Y-m-d H:i:s');

					$sqlKardex = "INSERT INTO kardex(id_sucursal, fecha_emision, tipo, id_articulo, id_detalle_ingreso,id_detalle_pedido, cantidad, fecha_creacion, fecha_modificacion)
					VALUES('".$_SESSION['idsucursal']."', '".$fecact."', 'venta', '0', '".$array[0]."', '".$idpedido."', '".$array[3]."', '".$fecact."','".$fecact."' )";

					$conexion->query($sqlKardex);
					*/
			}

			//var_dump($sql_detalle);
			//exit;

			/*
				foreach($detalle as $indice){
					$sql_detalle = "INSERT INTO detalle_pedido(idpedido, iddetalle_ingreso, cantidad, precio_venta, descuento)
											VALUES($idpedido, ".$indice[0].", ".$indice[3].", ".$indice[2].", ".$indice[4].")";
					$conexion->query($sql_detalle) or $sw = false;
				}
				*/

			//exit;

			/*
				if ($conexion != null) {
                	$conexion->close();
            	}
				*/
		} catch (Exception $e) {
			$conexion->rollback();
		}

		//return $sw;
		return [$sw, $idpedido];
	}

	// Se cambio la cantidad del orden a 10K corregir AP
	public function Listar($idsucursal)
	{
		global $conexion;
		$sql = "SELECT p.*, concat(e.nombre,' ',e.apellidos,' |  ',p.fecha) as empleado,concat(c.nombre,' ',c.apellido) as cliente, c.email, concat(c.direccion_departamento,' - ',c.direccion_provincia,' - ',c.direccion_distrito,'  |  ',c.direccion_calle, '|',
		IFNULL(c.direccion_referencia,'')) as destino , c.num_documento, concat(c.telefono,' - ',c.telefono_2) as celular,concat(v.serie_comprobante,' - ',v.num_comprobante) as ticket,v.fecha as fecha_venta,v.idusuario as aprobacion2v,v.tipo_venta,concat(ev.nombre,' ',ev.apellidos,' |  ',v.fecha) as aproba_venta,concat(eva.nombre,' ',eva.apellidos,' |  ',p.fecha_apro_coti) as aproba_pedido,concat(c.tipo_persona,' - ',c.numero_cuenta) as tipo_cliente
			from pedido p
						inner join persona c on p.idcliente = c.idpersona
            inner join venta v on p.idpedido = v.idpedido
						inner join usuario u on p.idusuario=u.idusuario
						inner join empleado e on u.idempleado=e.idempleado
						inner join usuario uv on v.idusuario=uv.idusuario
						inner join empleado ev on uv.idempleado=ev.idempleado
						inner join usuario uva on p.idusuario_est=uva.idusuario
						inner join empleado eva on uva.idempleado=eva.idempleado
            where p.idsucursal = $idsucursal
			and c.tipo_persona = 'Final' & 'Distribuidor' & 'Superdistribuidor' & 'Representante' and p.tipo_pedido = 'Venta' order by idpedido desc limit 0,300";
		$query = $conexion->query($sql);
		return $query;
	}

	// public function Listar($idsucursal){
	// 	global $conexion;
	// 	$sql = "SELECT p.*, c.nombre as Cliente, c.email 
	// 	from pedido p inner join persona c on p.idcliente = c.idpersona where p.idsucursal = $idsucursal 
	// 	and c.tipo_persona = 'Cliente' and p.tipo_pedido = 'Venta' order by idpedido desc limit 0,2999";
	// 	$query = $conexion->query($sql);
	// 	return $query;
	// }

	public function VerVenta($idpedido)
	{
		global $conexion;
		$sql = "SELECT * from venta where idpedido = $idpedido";
		$query = $conexion->query($sql);
		return $query;
	}

	public function TotalPedido($idpedido)
	{
		global $conexion;
		$sql = "SELECT sum((precio_venta * cantidad) - (descuento * cantidad)) as Total
	from detalle_pedido where idpedido = $idpedido";
		$query = $conexion->query($sql);
		return $query;
	}

	public function CambiarEstado($idpedido, $detalle)
	{
		global $conexion;
		$sw = true;
		try {

			$sql_data_pedido = "select * from pedido where  idpedido=$idpedido";
			$response_data_pedido = $conexion->query($sql_data_pedido)->fetch_object();

			$sql = "UPDATE pedido set estado = 'C'
						WHERE idpedido = $idpedido";
			//var_dump($sql);
			$conexion->query($sql);

			$sql2 = "UPDATE venta set impuesto = '0.00',total='0.00',estado = 'C',fecha_anu=CURRENT_TIMESTAMP() ,idusaurio_anu=" . $_SESSION["idempleado"] . "
						WHERE idpedido = $idpedido";
			//var_dump($sql);
			$conexion->query($sql2);

			$sql3 = "UPDATE credito set total_pago = '0.00'
						WHERE idventa = (SELECT idventa from venta where idpedido=$idpedido)";
			//var_dump($sql);
			$conexion->query($sql3);

			$sql4 = "UPDATE venta set estado = 'C',idusuario_anu = " . $_SESSION["idusuario"] . "
						WHERE idpedido = $idpedido";
			//var_dump($sql);
			$conexion->query($sql4);


			$conexion->autocommit(true);
			foreach ($detalle as $indice => $valor) {

				$sqlDetallePedido = "SELECT * from detalle_pedido where  iddetalle_ingreso=" . $valor[0] . " and idpedido = $idpedido";


				$response_detalle_pedido = $conexion->query($sqlDetallePedido)->fetch_object();


				// $suma_anterior = "SELECT SUM(stock_actual) stock from detalle_ingreso where idarticulo=" . $response_detalle_pedido->idarticulo . "";

				$suma_anterior = "SELECT sum(stock_actual) stock from detalle_ingreso  
				inner join ingreso on ingreso.idingreso=detalle_ingreso.idingreso
				 where idarticulo=$response_detalle_pedido->idarticulo and estado ='A' and stock_actual>'0' and ingreso.idsucursal=" . $_SESSION["idsucursal"] . "";

				$rpta_sql_suma_anterior = $conexion->query($suma_anterior)->fetch_object();
				$stock_anterior = $rpta_sql_suma_anterior->stock;
				$stock_actual = $stock_anterior + $response_detalle_pedido->cantidad;


				// codigo anterior
				$sql_detalle = "UPDATE detalle_ingreso SET stock_actual = stock_actual + " . $valor[1] . " WHERE iddetalle_ingreso = " . $valor[0] . "";
				$conexion->query($sql_detalle) or $sw = false;
				// codigo anterior



				#ingreso sql de kardex 
				$suma_ingreso = "SELECT sum(stock_actual) stock
				from detalle_ingreso  
				inner join ingreso on ingreso.idingreso=detalle_ingreso.idingreso
				 where idarticulo=$response_detalle_pedido->idarticulo and estado ='A' and stock_actual>'0' and ingreso.idsucursal=" . $_SESSION["idsucursal"] . "";



				$rpta_sql_suma_ingreso = $conexion->query($suma_ingreso)->fetch_object();
				$stock_actual = $rpta_sql_suma_ingreso->stock;


				$fecact = date('Y-m-d H:i:s');

				$detale_ingreso = 0;


				

				$stock_anterior_not_null=($stock_anterior !== null) ? $stock_anterior: 0 ; 
				$stock_actual_not_null=($stock_actual !== null) ? $stock_actual: 0 ;
				
				
				// var_dump($response_detalle_pedido->idarticulo);
				// var_dump($detale_ingreso);
				// var_dump($stock_anterior);
				// var_dump($response_detalle_pedido->cantidad);
				// var_dump($stock_actual);
				// var_dump($response_detalle_pedido->iddetalle_pedido);
				// echo "articulo:;";

				$sqlKardex = "INSERT INTO kardex(
						id_sucursal,
						fecha_emision,
						tipo,
						id_articulo,
						id_detalle_ingreso,
						stock_anterior,
						cantidad,
						stock_actual,
						fecha_creacion,
						fecha_modificacion,
						id_detalle_pedido
						)
					VALUES(
						'" . $_SESSION['idsucursal'] . "',
						CURRENT_TIMESTAMP(),
						'venta anulada',
						'" . $response_detalle_pedido->idarticulo . "',
						 '" . $detale_ingreso . "',
						  '" . $stock_anterior_not_null . "',
						'" . $response_detalle_pedido->cantidad . "',
						'" . $stock_actual_not_null . "',
						CURRENT_TIMESTAMP(),
						CURRENT_TIMESTAMP(),
						'" . $response_detalle_pedido->iddetalle_pedido . "'
						)";
				$conexion->query($sqlKardex) or $sw = false;
			}
			if ($conexion != null) {
				$conexion->close();
			}
		} catch (Exception $e) {
			$conexion->rollback();
		}
		return $sw;
	}

	public function EliminarPedido($idpedido)
	{
		global $conexion;
		$sql = "DELETE FROM detalle_pedido
						WHERE idpedido = $idpedido";
		$query = $conexion->query($sql);

		$sql = "DELETE FROM pedido
						WHERE idpedido = $idpedido";
		$query = $conexion->query($sql);
		return $query;
	}

	public function GetPrimerCliente()
	{
		global $conexion;
		$sql = "SELECT idpersona,nombre from persona where tipo_persona='Cliente' order by idpersona limit 0,1";
		$query = $conexion->query($sql);
		return $query;
	}

	public function TraerCantidad($idpedido)
	{
		global $conexion;
		$sql = "SELECT iddetalle_ingreso, cantidad from detalle_pedido where idpedido = $idpedido";
		$query = $conexion->query($sql);
		return $query;
	}

	public function GetDetallePedido($idpedido)
	{
		global $conexion;
		$sql = "SELECT a.nombre as articulo, dg.codigo, dg.serie, c.nombre as marca, dp.*,((dp.precio_venta * dp.cantidad) - (dp.descuento * dp.cantidad)) as total
			from pedido p inner join detalle_pedido dp on p.idpedido = dp.idpedido
			inner join detalle_ingreso dg on dp.iddetalle_ingreso = dg.iddetalle_ingreso
			inner join articulo a on dg.idarticulo = a.idarticulo
			inner join categoria c on a.idcategoria = c.idcategoria
			where dp.idpedido = $idpedido";

		//var_dump($sql);
		//exit;
 
		$query = $conexion->query($sql);
		return $query;
	}

	public function GetDetalleCantStock($idpedido)
	{
		global $conexion;
		$sql = "SELECT di.iddetalle_ingreso, di.stock_actual, dp.cantidad 
			from detalle_pedido dp inner join detalle_ingreso di on dp.iddetalle_ingreso = di.iddetalle_ingreso
			where dp.idpedido = $idpedido";
		$query = $conexion->query($sql);
		return $query;
	}

	public function ListarTipoPedidoPedido($idsucursal)
	{
		global $conexion;
		$sql = "SELECT p.*,concat(e.nombre,' ',e.apellidos) as empleado,concat(c.nombre,' ',c.apellido) as cliente,c.email,concat(c.direccion_departamento,' - ',c.direccion_provincia,' - ',c.direccion_distrito,' - ',c.direccion_calle ,' - ',IFNULL(c.direccion_referencia,'')) as destino, c.num_documento,concat(c.telefono,' - ',c.telefono_2) as celular,
	

			(CASE
				WHEN p.estado = 'A' THEN '<span class=\'badge bg-blue\'>Activo</span>'
				WHEN p.estado = 'C' THEN '<span class=\'badge bg-red\'>Cancelado</span>'
				WHEN p.estado = 'D' THEN '<span class=\'badge bg-green\'>Aprobado</span>'
			END ) AS estado,
			p.metodo_pago AS metodo_pago,
			p.agencia_envio AS agencia_envio,
			p.tipo_promocion AS tipo_promocion,
			p.estado AS estadoId
			,p.observacion as observaciones,p.modo_pago,
			p.tipo_entrega
			from pedido p inner join persona c on p.idcliente = c.idpersona
			inner join usuario u on p.idusuario=u.idusuario
			inner join empleado e on u.idempleado=e.idempleado
			
			where p.idsucursal =  $idsucursal
			and c.tipo_persona = 'Cliente' & 'Distribuidor' & 'Superdistribuidor' & 'Representante' and p.tipo_pedido <> 'Venta' order by idpedido limit 0,300";

		//var_dump($sql);exit;

		$query = $conexion->query($sql);
		return $query;
	}

	// public function ListarTipoPedidoPedido($idsucursal){
	// 	global $conexion;
	// 	$sql = "SELECT p.*, c.nombre as Cliente,c.email,
	// 	from pedido p inner join persona c 
	// 	on p.idcliente = c.idpersona where p.estado = 'A' and p.idsucursal = $idsucursal 
	// 	and p.tipo_pedido <> 'Venta' 
	// 	order by idpedido desc";
	// 	$query = $conexion->query($sql);
	// 	return $query;
	// }

	public function GetTotal($idpedido)
	{
		global $conexion;
		$sql = "SELECT sum((cantidad * precio_venta) - (cantidad * descuento)) as total from detalle_pedido where idpedido = $idpedido";
		$query = $conexion->query($sql);
		return $query;
	}

	public function GetIdPedido()
	{
		global $conexion;
		$sql = "SELECT max(idpedido) as idpedido from pedido";
		$query = $conexion->query($sql);
		return $query;
	}

	public function GetNextNumero($idsucursal)
	{
		global $conexion;
		$sql = "SELECT max(numero) + 1 as numero from pedido where idsucursal = $idsucursal";
		$query = $conexion->query($sql);
		return $query;
	}

	// lista modal clientes en la ventana de ventas
	public function ListarClientes()
	{
		global $conexion;
		$sql = "SELECT * from persona where tipo_persona='Cliente' & 'Distribuidor' & 'Superdistribuidor' & 'Representante' and estado = 'A' order by idpersona desc ";
		$query = $conexion->query($sql);
		return $query;
	}

	// lista modal productos en la ventana de ventas
	public function ListarDetalleIngresos($idsucursal)
	{
		global $conexion;
		$sql = "SELECT distinct di.iddetalle_ingreso, di.stock_actual, a.nombre as Articulo, di.codigo, di.serie, di.precio_ventapublico, a.imagen, i.fecha,c.nombre as marca, um.nombre as presentacion,di.idarticulo AS idarticulo,
		i.idsucursal,razon_social  
					from ingreso i inner join detalle_ingreso di on di.idingreso = i.idingreso
					inner join articulo a on di.idarticulo = a.idarticulo
					inner join categoria c on a.idcategoria = c.idcategoria
					inner JOIN sucursal ON sucursal.idsucursal=i.idsucursal
					inner join unidad_medida um on a.idunidad_medida = um.idunidad_medida
					where i.estado = 'A' 
					-- and i.idsucursal =$idsucursal 
					and di.stock_actual > 0 order by fecha asc";
		$query = $conexion->query($sql);
		return $query;
	}

	public function ListarProveedor()
	{
		global $conexion;
		$sql = "SELECT * from persona where tipo_persona = 'Proveedor' and estado = 'A'";
		$query = $conexion->query($sql);
		return $query;
	}

	public function ListarTipoDocumento($idsucursal)
	{
		global $conexion;
		$sql = "SELECT dds.*, td.nombre
			from detalle_documento_sucursal dds inner join tipo_documento td on dds.idtipo_documento = td.idtipo_documento
			where dds.idsucursal = $idsucursal and operacion = 'Comprobante'";
		$query = $conexion->query($sql);
		return $query;
	}

	public function GetTipoDocSerieNum($nombre)
	{
		global $conexion;
		$sql = "SELECT ultima_serie, ultimo_numero from tipo_documento where operacion = 'Comprobante' and nombre = '$nombre'";
		$query = $conexion->query($sql);
		return $query;
	}

	public function ListarProveedores()
	{
		global $conexion;
		$sql = "SELECT * from persona where tipo_perssona = 'Proveedor'";
		$query = $conexion->query($sql);
		return $query;
	}

	public function GetClienteSucursalPedido($idpedido)
	{
		global $conexion;
		$sql = "SELECT p.*, ped.fecha, s.razon_social, ped.numero, s.tipo_documento, s.num_documento as num_sucursal, s.direccion, s.telefono as telefono_suc, s.email as email_suc, s.representante, s.logo, ped.tipo_pedido,p.tipo_documento as doc
			from persona p inner join pedido ped on ped.idcliente = p.idpersona
			inner join sucursal s on ped.idsucursal = s.idsucursal
			where ped.idpedido = $idpedido";
		$query = $conexion->query($sql);
		return $query;
	}

	public function GetVenta($idpedido)
	{
		global $conexion;
		$sql = "SELECT p.*,concat(e.apellidos,' ',e.nombre) as empleado, p.tipo_documento as documento_per,p.tipo_persona as tipo_cliente, ped.fecha, s.razon_social, v.num_comprobante, v.serie_comprobante, v.metodo_pago, v.agencia_envio, s.tipo_documento, s.num_documento as num_sucursal, s.direccion, s.telefono as telefono_suc, s.email as email_suc, s.representante, s.logo, ped.tipo_pedido,v.impuesto,p.tipo_documento as doc,ped.estado,ped.modo_pago,ped.tipo_entrega
			from persona p inner join pedido ped on ped.idcliente = p.idpersona
			inner join detalle_pedido dp on dp.idpedido = ped.idpedido
			inner join sucursal s on ped.idsucursal = s.idsucursal
			inner join venta v on v.idpedido = ped.idpedido
			inner join usuario u on ped.idusuario=u.idusuario
			inner join empleado e on u.idempleado=e.idempleado
			where ped.idpedido = $idpedido";
		$query = $conexion->query($sql);
		return $query;
	}

	public function GetComprobanteTipo($idpedido)
	{
		global $conexion;
		$sql = "SELECT v.tipo_comprobante from venta v inner join pedido p on p.idpedido=v.idpedido
			where p.idpedido = $idpedido";
		$query = $conexion->query($sql);
		return $query;
	}

	public function ImprimirDetallePedido($idpedido)
	{
		global $conexion;
		$sql = "SELECT di.codigo,concat(e.apellidos,' ',e.nombre) as empleado,di.serie, c.nombre as marca,di.codigo as codigo, a.nombre as articulo, dp.*, (dp.cantidad * dp.precio_venta) - (dp.cantidad * dp.descuento) as sub_total
			from detalle_pedido dp inner join pedido p on dp.idpedido = p.idpedido
			inner join detalle_ingreso di on dp.iddetalle_ingreso = di.iddetalle_ingreso
			inner join articulo a on di.idarticulo = a.idarticulo
			inner join categoria c on a.idcategoria=c.idcategoria
			inner join usuario u on p.idusuario=u.idusuario
			inner join empleado e on u.idempleado=e.idempleado
			where p.idpedido = $idpedido";
		$query = $conexion->query($sql);
		return $query;
	}

	public function RegistrarDetalleImagenes($idpedido, $idcliente, $idusuario, $idsucursal, $imagen)
	{

		global $conexion;
		$sql = "INSERT INTO detalle_pedido_img(idpedido, idcliente, idusuario, idsucursal, imagen, estado)
						VALUES($idpedido, $idcliente, $idusuario, '$idsucursal', '$imagen', 1)";
		//var_dump($sql);
		$query = $conexion->query($sql);

		/*
			if ($conexion != null) {
				$conexion->close();
			}
			*/
		return $query;
	}

	public function GetImagenes($idpedido)
	{
		global $conexion;
		$sql = "SELECT
					iddetalle_img AS id,
					idpedido AS idpedido,
					idcliente AS idcliente,
					idusuario AS idusuario,
					idsucursal AS idsucursal,
					imagen AS imagen
			 		from detalle_pedido_img where idpedido = $idpedido AND estado = 1";
		//var_dump($sql);exit;
		$query = $conexion->query($sql);
		return $query;
	}

	public function DeleteImagenes($iddetalleimg)
	{
		global $conexion;
		$sql = "UPDATE detalle_pedido_img set estado = '0' WHERE iddetalle_img = $iddetalleimg";
		$query = $conexion->query($sql);
		return $query;
	}

	public function cambiarEstadoPedido($idpedido)
	{
		global $conexion;
		$sql = "UPDATE pedido set estado = 'D',fecha_apro_coti=CURRENT_TIMESTAMP() ,idusuario_est = " . $_SESSION["idusuario"] . " WHERE idpedido = $idpedido";
		//var_dump($sql);exit;
		$query = $conexion->query($sql);
		return $query;
	}

	public function GetCodigoAleatorio($codigoAleatorio)
	{
		global $conexion;
		$sql = "SELECT
					idpedido AS idpedido
			 		from pedido where numero = $codigoAleatorio";
		//var_dump($sql);exit;
		$query = $conexion->query($sql);
		return $query;
	}
	public function traerUltimoPedido()
	{
		global $conexion;
		$sql = "SELECT * FROM pedido ORDER	BY  fecha desc LIMIT 1 ";
		$query = $conexion->query($sql);
		return $query;
	}
	public function traerUltimaVenta()
	{
		global $conexion;
		$sql = "SELECT * FROM venta ORDER	BY  fecha DESC LIMIT 1";
		$query = $conexion->query($sql);
		return $query;
	}

	public function traerUltimaVentaCancelada()
	{
		global $conexion;
		$sql = "SELECT * FROM venta 
		left join empleado on empleado.idempleado = venta.idusuario_anu
		 WHERE venta.estado='C'	
		  ORDER	BY  fecha_anu  DESC  LIMIT 1";
		$query = $conexion->query($sql);

		// echo json_decode($query);
		return $query;
	}
	//$sql = "SELECT vencab_id FROM tbl_vencab WHERE vencab_codigo = $rptaAleatorio";

}
