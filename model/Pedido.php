<?php

require "Conexion.php";
class Pedido
{

	public function  GetNombreFEFO($idpedido){
		global $conexion;
		
		$sql="SELECT  *  from   detalle_pedido 
		join articulo on articulo.idarticulo =detalle_pedido.idarticulo
		 where idpedido=$idpedido and omision_fefo='true'";
		

		return 	$conexion->query($sql);


	}
	public function  buscarCliente($q)
	{
		global $conexion;
		$exepcion = "";
		$join = "
			 JOIN cartera_cliente ON cartera_cliente.idcliente=persona.idpersona AND cartera_cliente.estado='A'
			 
			JOIN empleado e3 ON e3.idempleado=cartera_cliente.idempleado
			";

		if ($_SESSION["idempleado"] == 17 || $_SESSION["idempleado"] == 6) {
		} else {
			$exepcion = "AND cartera_cliente.idempleado=" . $_SESSION["idempleado"] .  " 
		
				";

			$join = " JOIN cartera_cliente ON cartera_cliente.idcliente=persona.idpersona
				AND cartera_cliente.estado='A'
	
				JOIN empleado e3 ON e3.idempleado=cartera_cliente.idempleado";
		}


		// $sql = "SELECT * from persona where tipo_persona='Cliente' & 'Distribuidor' & 'Superdistribuidor' & 'Representante' and estado = 'A' order by idpersona desc ";

		// se modifico el sql para que los iddocumentos no se repita
		$sql = "SELECT *,CONCAT(persona.num_documento,' - ',persona.nombre,' - ',persona.apellido,' - ', persona.telefono,' - ', persona.telefono_2 )  texto ,
		CONCAT(departamento.descripcion,' - ',provincia.descripcion, ' - ',distrito.descripcion) ubicacion,
		
		persona.tipo_persona,persona.num_documento,persona.nombre,persona.apellido,persona.telefono,persona.direccion_calle ,persona.email 
		,CONCAT(persona.tipo_documento,' - ',persona.num_documento) documento_as
		from persona 
	
		INNER JOIN (SELECT num_documento, MAX(persona.idpersona) AS max_fecha FROM persona  GROUP BY num_documento)	t2 ON t2.num_documento = persona.num_documento AND persona.idpersona = t2.max_fecha
		
		LEFT JOIN distrito ON distrito.iddistrito=persona.direccion_distrito
		left JOIN provincia ON provincia.idprovincia=persona.direccion_provincia
		left JOIN departamento ON departamento.iddepartamento=provincia.iddepartamento
		
		$join
	where   
	 persona.estado = 'A'  
	
	 AND (
			tipo_persona = 'FINAL' or 	tipo_persona =  'DISTRIBUIDOR' or tipo_persona =  'SUPERDISTRIBUIDOR' or tipo_persona = 'REPRESENTANTE' or tipo_persona = 'VIP')
			$exepcion
		 and CONCAT(persona.num_documento,' - ',persona.nombre,' - ',persona.apellido,' - ' )  like '%$q%'



	GROUP BY persona.num_documento
	order by idpersona DESC ;";

		// echo $sql;
		$query = $conexion->query($sql);
		return $query;
	}

	public function TraerMetodoPago($idventa)
	{
		global $conexion;
		$sql = "SELECT * ,tipo_metodo_pago.descripcion tipo_metodo_pago ,banco_cuenta.descripcion banco_cuenta

		FROM venta_pago
		
		left JOIN banco_cuenta ON banco_cuenta.idbanco_cuenta=venta_pago.idbanco_cuenta
		left JOIN tipo_metodo_pago ON tipo_metodo_pago.idtipo_metodo_pago=venta_pago.idtipo_metodo_pago
		where  
		 idventa='$idventa'
		";
		$query = $conexion->query($sql);
		return $query;
	}

	public function traerPersonalTransporte()
	{
		global $conexion;
		$sql = "SELECT * from transporte where estado='A'";
		$query = $conexion->query($sql);
		return $query;
	}


	public function DatosUsuario($id_usuario)
	{
		global $conexion;
		$sql = "SELECT * from empleado where idempleado=$id_usuario";
		$query = $conexion->query($sql);
		return $query;
	}

	public function Registrar($idcliente, $idusuario, $idsucursal, $tipo_pedido, $numero, $detalle, $metodo_pago, $agencia_envio, $tipo_promocion, $modo_pago, $observacion, $tipo_entrega)
	{

		//var_dump($detalle);exit;

		global $conexion;
		$sw = true;
		try {
			//exit;
			$sql = "INSERT INTO pedido(idcliente, idusuario, idsucursal, tipo_pedido, fecha,  numero, estado, metodo_pago, agencia_envio, tipo_promocion,modo_pago,observacion,tipo_entrega)
						VALUES($idcliente, $idusuario, $idsucursal, '$tipo_pedido', CURRENT_TIMESTAMP(),'$numero','A','$metodo_pago','$agencia_envio','$tipo_promocion','$modo_pago','$observacion','$tipo_entrega')";
			$sql = "INSERT INTO pedido(idcliente, idusuario, idsucursal, tipo_pedido, fecha,  numero, estado, metodo_pago, agencia_envio, tipo_promocion,modo_pago,observacion,tipo_entrega)
	

			SELECT $idcliente, $idusuario, $idsucursal, '$tipo_pedido', CURRENT_TIMESTAMP(), concat(10,max(idpedido)+1),'A','$metodo_pago','$agencia_envio','$tipo_promocion','$modo_pago','$observacion','$tipo_entrega'
			 FROM pedido;";
			//var_dump($sql);
			$conexion->query($sql);
			// echo ($sql);
			$idpedido = $conexion->insert_id;
			$conexion->autocommit(true);



			for ($i = 0; $i < count($detalle); $i++) {

				$array = explode(",", $detalle[$i]);

				// print_r($array);

				$sql_detalle = "INSERT INTO detalle_pedido(idpedido, iddetalle_ingreso, cantidad, precio_venta, descuento, idarticulo ,omision_fefo)
											VALUES($idpedido, '" . $array[0] . "', '" . $array[3] . "', '" . $array[2] . "', '" . $array[4] . "', '" . $array[8] . "', '" . $array[13] . "')";

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
	public function Listar($idsucursal,$listar)
	{
		if($listar=='14diasRadio'){
			$limit='
						AND v.fecha >= DATE_SUB(CURDATE(), INTERVAL 14 DAY)
			';

		}else{
			$limit='
			
			AND v.fecha >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
			';
		}
		global $conexion;
		$sql = "SELECT  concat(r_anu.r_prefijo ,' ',em_anu.nombre_usuario) empleado_anulado_txt,p.*, concat(r_e.r_prefijo,' ',e.nombre_usuario,' | ',p.fecha) as empleado,concat(c.nombre,' ',c.apellido) as cliente, c.email, concat(c.direccion_departamento,' - ',c.direccion_provincia,' - ',c.direccion_distrito,'  |  ',c.direccion_calle, '|',
		
		IFNULL(c.direccion_referencia,'')) as destino , c.num_documento, concat(c.telefono,' - ',c.telefono_2) as celular,concat(v.serie_comprobante,' - ',v.num_comprobante) as ticket,v.fecha as fecha_venta,v.idusuario as aprobacion2v,v.tipo_venta,concat(r_ev.r_prefijo,' ',ev.nombre_usuario,' |  ',v.fecha) as aproba_venta,concat(r_eva.r_prefijo,' ',eva.nombre_usuario,' |  ',p.fecha_apro_coti) as aproba_pedido,concat(c.tipo_persona,' - ',c.numero_cuenta) as tipo_cliente
	

	

		,CONCAT( IFNULL(departamento.descripcion,'') ,' - ',IFNULL(provincia.descripcion,''), ' - ',IFNULL(distrito.descripcion,''),' - ',c.direccion_calle ,' - ',IFNULL(c.direccion_referencia,'')) destino


		,r_e.r_prefijo prefijo_pedido,r_eva.r_prefijo prefijo_estado,r_ev.r_prefijo prefijo_venta
		,

		CONCAT (IFNULL(r_e.r_prefijo,' '), ' - ',IFNULL(e.nombre_usuario,' ')) nombre_usuario_rol


			from pedido p
			LEFT join persona c on p.idcliente = c.idpersona
						LEFT join venta v on p.idpedido = v.idpedido
			LEFT join usuario u on p.idusuario=u.idusuario
						LEFT join empleado e on u.idempleado=e.idempleado
						LEFT join usuario uv on v.idusuario=uv.idusuario
						LEFT join empleado ev on uv.idempleado=ev.idempleado
						LEFT join usuario uva on p.idusuario_est=uva.idusuario
						LEFT join empleado eva on uva.idempleado=eva.idempleado

						LEFT JOIN rol r_e ON r_e.r_id=e.idrol
						LEFT JOIN rol r_eva ON r_eva.r_id=eva.idrol
						LEFT JOIN rol r_ev ON r_ev.r_id=ev.idrol
						
						LEFT JOIN usuario anu ON anu.idusuario=v.idusuario_anu
						LEFT JOIN empleado em_anu ON em_anu.idempleado=anu.idempleado


						LEFT JOIN rol r_anu ON r_anu.r_id=em_anu.idrol

		left JOIN distrito ON distrito.iddistrito=c.direccion_distrito
		LEFT  JOIN provincia ON provincia.idprovincia=c.direccion_provincia
		left 	JOIN departamento ON departamento.iddepartamento=provincia.iddepartamento

            where p.idsucursal = $idsucursal
			and c.tipo_persona = 'Final' & 'Distribuidor' & 'Superdistribuidor' & 'Representante' and p.tipo_pedido = 'Venta'
			
			$limit

			 order by idpedido desc
			
			
			-- limit 0,300
			";
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
				 where idarticulo=$response_detalle_pedido->idarticulo and ingreso.estado='A'
                    and detalle_ingreso.estado_detalle_ingreso='INGRESO'  and stock_actual>'0' and ingreso.idsucursal=" . $_SESSION["idsucursal"] . "";

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
				 where idarticulo=$response_detalle_pedido->idarticulo and ingreso.estado='A'
                    and detalle_ingreso.estado_detalle_ingreso='INGRESO' and stock_actual>'0' and ingreso.idsucursal=" . $_SESSION["idsucursal"] . "";



				$rpta_sql_suma_ingreso = $conexion->query($suma_ingreso)->fetch_object();
				$stock_actual = $rpta_sql_suma_ingreso->stock;


				$fecact = date('Y-m-d H:i:s');

				$detale_ingreso = 0;




				$stock_anterior_not_null = ($stock_anterior !== null) ? $stock_anterior : 0;
				$stock_actual_not_null = ($stock_actual !== null) ? $stock_actual : 0;


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

	public function GetDetallePedidoSolo($idpedido)
	{
		global $conexion;
		$sql = "			SELECT detalle_ingreso.iddetalle_ingreso,stock_actual,cantidad,iddetalle_pedido FROM detalle_pedido JOIN detalle_ingreso on detalle_ingreso.iddetalle_ingreso=detalle_pedido.iddetalle_ingreso where idpedido = $idpedido";
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
		$sql = "SELECT p.*,concat(e.nombre,' ',e.apellidos) as empleado 
		,e.nombre_usuario
,
		CONCAT (IFNULL(r_e.r_prefijo,' '), ' - ',IFNULL(e.nombre_usuario,' ')) nombre_usario_rol

		,concat(c.nombre,' ',c.apellido) as cliente,c.email,concat(c.direccion_departamento,' - ',c.direccion_provincia,' - ',c.direccion_distrito,' - ',c.direccion_calle ,' - ',IFNULL(c.direccion_referencia,'')) as destino, c.num_documento,concat(c.telefono,' - ',c.telefono_2) as celular,
	

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
		
		,CONCAT( IFNULL(departamento.descripcion,'') ,' - ',IFNULL(provincia.descripcion,''), ' - ',IFNULL(distrito.descripcion,''),' - ',c.direccion_calle ,' - ',IFNULL(c.direccion_referencia,'')) destino
		
	,if(c.direccion_distrito>0 AND c.direccion_provincia>0,'',CONCAT(c.direccion_departamento ,' ', c.direccion_distrito,' ',c.direccion_provincia)) direccion_antigua

		,r_e.r_prefijo prefijo_pedido,r_eva.r_prefijo prefijo_estado 
		,c.tipo_persona
		from pedido p inner join persona c on p.idcliente = c.idpersona
		
		
		left join usuario uva on p.idusuario_est=uva.idusuario
		left join empleado eva on uva.idempleado=eva.idempleado			
		left join usuario u on p.idusuario=u.idusuario
		left join empleado e on u.idempleado=e.idempleado
		

		left JOIN rol r_e ON r_e.r_id=e.idrol
		left JOIN rol r_eva ON r_eva.r_id=eva.idrol

		left JOIN distrito ON distrito.iddistrito=c.direccion_distrito
		LEFT  JOIN provincia ON provincia.idprovincia=c.direccion_provincia
		left 	JOIN departamento ON departamento.iddepartamento=provincia.iddepartamento
						 
		where p.idsucursal =  $idsucursal
		and c.tipo_persona = 'Cliente' & 'Distribuidor' & 'Superdistribuidor' & 'Representante' and p.tipo_pedido <> 'Venta' order by idpedido limit 0,300";

		// echo $sql;
		// exit;

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

		$exepcion = "";
		$join = "
		 JOIN cartera_cliente ON cartera_cliente.idcliente=persona.idpersona AND cartera_cliente.estado='A'
		 
		JOIN empleado e3 ON e3.idempleado=cartera_cliente.idempleado
		";

		if ($_SESSION["idempleado"] == 17 || $_SESSION["idempleado"] == 6) {
		} else {
			$exepcion = "AND cartera_cliente.idempleado=" . $_SESSION["idempleado"] .  " 
	
			";

			$join = " JOIN cartera_cliente ON cartera_cliente.idcliente=persona.idpersona
			AND cartera_cliente.estado='A'

			JOIN empleado e3 ON e3.idempleado=cartera_cliente.idempleado";
		}


		// $sql = "SELECT * from persona where tipo_persona='Cliente' & 'Distribuidor' & 'Superdistribuidor' & 'Representante' and estado = 'A' order by idpersona desc ";

		// se modifico el sql para que los iddocumentos no se repita
		$sql = "SELECT *,persona.tipo_persona,persona.num_documento,persona.nombre,persona.apellido,persona.telefono,persona.direccion_calle ,persona.email from persona 

	INNER JOIN (SELECT num_documento, MAX(persona.idpersona) AS max_fecha FROM persona  GROUP BY num_documento)	t2 ON t2.num_documento = persona.num_documento AND persona.idpersona = t2.max_fecha
	$join
where   
 persona.estado = 'A'  

 AND (
		tipo_persona = 'FINAL' or 	tipo_persona =  'DISTRIBUIDOR' or tipo_persona =  'SUPERDISTRIBUIDOR' or tipo_persona = 'REPRESENTANTE' )
		$exepcion

GROUP BY persona.num_documento
order by idpersona DESC ;";

		echo $sql;
		$query = $conexion->query($sql);
		return $query;
	}

	// lista modal productos en la ventana de ventas
	public function ListarDetalleIngresos($idsucursal)
	{
		global $conexion;
		$sql = "SELECT distinct di.iddetalle_ingreso,
		case 
		WHEN  di.estado_detalle_ingreso='SALIDA' THEN 'En transito'
		WHEN  di.estado_detalle_ingreso='EN TRANSITO' THEN 'En transito'
		WHEN  di.estado_detalle_ingreso='ALMACEN OPERADOR' THEN 'Almacen Transportista'
		WHEN  di.estado_detalle_ingreso='INGRESO' THEN 'Disponible'		
	END	
		AS estado_n
		
	,di.estado_detalle_ingreso, di.stock_actual, a.nombre as Articulo, di.codigo, di.serie, di.precio_ventapublico
	,a.imagen, i.fecha,c.nombre as marca, um.nombre as presentacion,di.idarticulo AS idarticulo
	,di.precio_ventadistribuidor,di.precio_ventarepresentante,di.precio_ventasuperdistribuidor,
			i.idsucursal,razon_social  

			,di.lote,
			serie,
			TIMESTAMPDIFF(MONTH,CURDATE(),serie ),
			CASE 
		 	WHEN TIMESTAMPDIFF(MONTH,CURDATE(),serie )<1 THEN 'VENCIDO' 
			WHEN TIMESTAMPDIFF(MONTH,CURDATE(),serie)>=1 and TIMESTAMPDIFF(month,CURDATE(),serie)<=3   THEN 'POR VENCER' 
	   	WHEN TIMESTAMPDIFF(month,CURDATE(),serie)>=4 THEN 'VIGENTE' 


		   WHEN TIMESTAMPDIFF(MONTH,CURDATE(),serie) is null then 'SIN CADUCIDAD'
	   	END  AS vigencia
			
						from ingreso i 
						inner join detalle_ingreso di on di.idingreso = i.idingreso
						inner join articulo a on di.idarticulo = a.idarticulo
						inner join categoria c on a.idcategoria = c.idcategoria
						inner JOIN sucursal ON sucursal.idsucursal=i.idsucursal
						inner join unidad_medida um on a.idunidad_medida = um.idunidad_medida
						where i.estado = 'A' 

					
						-- and i.idsucursal =$idsucursal 
						and di.stock_actual > 0 order by fecha asc;";


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
			where dds.idsucursal = $idsucursal and operacion = 'Comprobante'
			AND( iddetalle_documento_sucursal=5 or iddetalle_documento_sucursal=9 )
			";
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
	public function TraerEmpleadoUsuarioRol($id){
		global $conexion;
		$sql="SELECT * FROM empleado
		join rol  on  rol.r_id=empleado.idrol
		 where idempleado=$id";
		$query = $conexion->query($sql);
		return $query;

	}
	public function GetVenta($idpedido)
	{
		global $conexion;
		$sql = "SELECT p.*,concat(e.nombre,' ',e.apellidos) as empleado,concat(rol.r_prefijo,' ',e.nombre_usuario) nombre_usuario, concat(emp_anu.nombre,' ',emp_anu.apellidos) as empleado_anulado,
		concat(rol_anu.r_prefijo,' ',emp_anu.nombre_usuario) as usuario_empleado_anulado,
		 p.tipo_documento as documento_per,p.tipo_persona as tipo_cliente, ped.fecha, s.razon_social, v.num_comprobante,v.idventa, v.serie_comprobante, ped.metodo_pago, ped.agencia_envio, s.tipo_documento, s.num_documento as num_sucursal, s.direccion, s.telefono as telefono_suc, s.email as email_suc, s.representante, s.logo, ped.tipo_pedido,v.impuesto,p.tipo_documento as doc,ped.estado,ped.modo_pago,ped.tipo_entrega
		
		,distrito.descripcion distrito,provincia.descripcion provincia ,departamento.descripcion departamento
		
		from persona p inner join pedido ped on ped.idcliente = p.idpersona
		inner join detalle_pedido dp on dp.idpedido = ped.idpedido
		inner join sucursal s on ped.idsucursal = s.idsucursal
		inner join venta v on v.idpedido = ped.idpedido
		inner join usuario u on ped.idusuario=u.idusuario
		inner join empleado e on u.idempleado=e.idempleado
		inner join rol on rol.r_id=e.idrol
		left JOIN  usuario usu_anu on usu_anu.idusuario= v.idusuario_anu
		LEFT  JOIN  empleado emp_anu on emp_anu.idempleado=usu_anu.idempleado

		left  join rol rol_anu on rol_anu.r_id=emp_anu.idrol

		left JOIN distrito ON distrito.iddistrito=p.direccion_distrito
	LEFT  JOIN provincia ON provincia.idprovincia=p.direccion_provincia
		left 	JOIN departamento ON departamento.iddepartamento=provincia.iddepartamento


		where ped.idpedido =$idpedido";
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


	public function  RegistrarDetalleImagenesAlmacen($idpedido, $idcliente, $idusuario, $idsucursal, $imagen)
	{
		global $conexion;
		$sql = "INSERT INTO detalle_pedido_img(
			idpedido,
			idusuario,
			imagen,
			estado,
			tipo_imagen
			)
		VALUES(
			$idpedido, 
			$idusuario, 
			'$imagen', 
			1,
			'EMPAQUETADO')";
		// echo $sql;
		$query = $conexion->query($sql);
		return $query;
	}


	public function  RegistrarDetalleImagenesFEFO($idpedido, $idcliente, $idusuario, $idsucursal, $imagen)
	{
		global $conexion;
		$sql = "INSERT INTO detalle_pedido_img(
			idpedido,
			idusuario,
			imagen,
			estado,
			tipo_imagen
			)
		VALUES(
			$idpedido, 
			$idusuario, 
			'$imagen', 
			1,
			'FEFO')";
			
		$query = $conexion->query($sql);
		return $query;
	}


	public function  RegistrarDetalleImagenesChat($idpedido, $idcliente, $idusuario, $idsucursal, $imagen)
	{
		global $conexion;
		$sql = "INSERT INTO detalle_pedido_img(
			idpedido,
			idusuario,
			imagen,
			estado,
			tipo_imagen
			)
		VALUES(
			$idpedido, 
			$idusuario, 
			'$imagen', 
			1,
			'CHAT')";
		// echo $sql;
		$query = $conexion->query($sql);
		return $query;
	}

	public function RegistrarDetalleImagenes($idpedido, $idcliente, $idusuario, $idsucursal, $imagen)
	{

		global $conexion;
		$sql = "INSERT INTO detalle_pedido_img(idpedido, idusuario, imagen, estado,tipo_imagen)
		VALUES($idpedido, $idusuario, '$imagen', 1,'VOUCHER')";


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
			 		from detalle_pedido_img where idpedido = $idpedido AND estado = 1 AND tipo_imagen='VOUCHER'
					";

		$sql = "SELECT
		iddetalle_img AS id,
		pedido.idpedido AS idpedido,
		pedido.idcliente AS idcliente,
		detalle_pedido_img.idusuario AS idusuario,
		pedido.idsucursal AS idsucursal,
		imagen AS imagen
		from detalle_pedido_img 
		JOIN pedido ON pedido.idpedido=detalle_pedido_img.idpedido
		where detalle_pedido_img.idpedido = $idpedido and  detalle_pedido_img.estado = 1 AND tipo_imagen='VOUCHER'";
		//var_dump($sql);exit;
		$query = $conexion->query($sql);
		return $query;
	}

	public function GetImagenesEmpaquetado($idpedido)
	{
		global $conexion;
		$sql = "SELECT
		iddetalle_img AS id,
		pedido.idpedido AS idpedido,
		pedido.idcliente AS idcliente,
		detalle_pedido_img.idusuario AS idusuario,
		pedido.idsucursal AS idsucursal,
		imagen AS imagen
		from detalle_pedido_img 
		JOIN pedido ON pedido.idpedido=detalle_pedido_img.idpedido
		where detalle_pedido_img.idpedido = $idpedido AND detalle_pedido_img.estado = 1 and tipo_imagen='EMPAQUETADO'
		";
		$query = $conexion->query($sql);
		return $query;
	}


	public function GetImageneFEFO($idpedido)
	{
		global $conexion;
		$sql = "SELECT
		iddetalle_img AS id,
		pedido.idpedido AS idpedido,
		pedido.idcliente AS idcliente,
		detalle_pedido_img.idusuario AS idusuario,
		pedido.idsucursal AS idsucursal,
		imagen AS imagen
		from detalle_pedido_img 
		JOIN pedido ON pedido.idpedido=detalle_pedido_img.idpedido
		where detalle_pedido_img.idpedido = $idpedido AND detalle_pedido_img.estado = 1 and tipo_imagen='FEFO'
		";
		$query = $conexion->query($sql);
		
		return $query;
	}

	public function GetImagenesChat($idpedido)
	{
		global $conexion;
		$sql = "SELECT
		iddetalle_img AS id,
		pedido.idpedido AS idpedido,
		pedido.idcliente AS idcliente,
		detalle_pedido_img.idusuario AS idusuario,
		pedido.idsucursal AS idsucursal,
		imagen AS imagen
		from detalle_pedido_img 
		JOIN pedido ON pedido.idpedido=detalle_pedido_img.idpedido
		where detalle_pedido_img.idpedido = $idpedido AND detalle_pedido_img.estado = 1 and tipo_imagen='CHAT'
		";
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
		$sql = "SELECT MAX(idpedido)+1 pedidoSiguiente FROM pedido";
		//var_dump($sql);exit;
		$query = $conexion->query($sql);
		return $query;
	}
	public function PedidosEnEspera()
	{
		global $conexion;
		// $sql = "SELECT * FROM pedido ORDER	BY  fecha desc LIMIT 1 ";
		$sql = "SELECT * FROM pedido WHERE tipo_pedido='Pedido'";
		$query = $conexion->query($sql);
		return $query;
	}

	public function traerUltimoPedido()
	{
		global $conexion;
		// $sql = "SELECT * FROM pedido ORDER	BY  fecha desc LIMIT 1 ";
		$sql = "SELECT pedido.*,empleado.nombre,empleado.apellidos ,emp_est.nombre nombre_estado,emp_est.apellidos apellidos_estado FROM pedido 

JOIN usuario ON usuario.idusuario=pedido.idusuario
JOIN empleado ON empleado.idempleado=usuario.idempleado
left JOIN usuario usu_est ON usu_est.idusuario=pedido.idusuario_est
left JOIN empleado emp_est ON emp_est.idempleado=usu_est.idempleado

ORDER	BY  fecha desc LIMIT 1 ;";
		$query = $conexion->query($sql);
		return $query;
	}

	public function traerUltimaVenta()
	{
		global $conexion;
		$sql = "SELECT * FROM venta ORDER	BY  fecha DESC LIMIT 1";
		$sql = "SELECT venta.*,pedido.idsucursal,empleado.nombre,empleado.apellidos FROM venta 
		JOIN pedido ON pedido.idpedido=venta.idpedido
		JOIN usuario ON usuario.idusuario=venta.idusuario
		JOIN empleado ON empleado.idempleado=usuario.idempleado
		
		ORDER	BY  venta.fecha DESC LIMIT 1";
		$query = $conexion->query($sql);
		return $query;
	}

	public function traerUltimaVentaLima()
	{
		global $conexion;
		$sql = "SELECT venta.*,empleado.nombre,empleado.apellidos FROM venta 
		JOIN pedido ON pedido.idpedido=venta.idpedido
		JOIN usuario ON usuario.idusuario=venta.idusuario
		JOIN empleado ON empleado.idempleado=usuario.idempleado
		WHERE pedido.idsucursal=2
		ORDER	BY  venta.fecha DESC LIMIT 1";
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
