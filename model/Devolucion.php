<?php

require "Conexion.php";

class Devolucion
{	
	public function GetDetalleDevolucion($iddevolucion)
	{
		global $conexion;
		$sql = "SELECT articulo.nombre producto , devolucion_articulo.cantidad ,detalle_ingreso.serie,detalle_ingreso.codigo
		FROM devolucion_articulo 
		JOIN articulo ON articulo.idarticulo=devolucion_articulo.idarticulo
		JOIN detalle_ingreso ON detalle_ingreso.iddetalle_ingreso=devolucion_articulo.iddetalle_ingreso
		
		WHERE iddevolucion=$iddevolucion";

		//var_dump($sql);
		//exit;

		$query = $conexion->query($sql);
		return $query;
	}


	public function TraerListaDevolucion(){
		global $conexion;
		$sql = "SELECT dev.iddevolucion, dev.iddevolucion_motivo, dev.fecha fecha, CONCAT(emp.nombre,' ',emp.apellidos) usuario ,filename devolucion ,dev_mot.descripcion motivo,dev.observacion FROM devolucion dev
		JOIN empleado emp ON  emp.idempleado=dev.idempleado
		JOIN devolucion_motivo dev_mot ON  dev_mot.iddevolucion_motivo=dev.iddevolucion_motivo
		where dev.idsucursal=".$_SESSION["idsucursal"]. "
		ORDER BY iddevolucion desc 
		";
		$query = $conexion->query($sql);
		return $query;
	}
	public function TraerDatosDevoluciones()
	{
		global $conexion;
		$sql = "SELECT * FROM devolucion_motivo";
		$query = $conexion->query($sql);
		return $query;
	}
	public function ListarDetalleIngresos($sucursal)
	{
		global $conexion;
		$sql = "SELECT distinct di.iddetalle_ingreso, di.stock_actual, a.nombre as Articulo, di.codigo, di.serie, di.precio_ventapublico, a.imagen, i.fecha,c.nombre as marca, um.nombre as presentacion,di.idarticulo AS idarticulo 
			from ingreso i inner join detalle_ingreso di on di.idingreso = i.idingreso
			inner join articulo a on di.idarticulo = a.idarticulo
			inner join categoria c on a.idcategoria = c.idcategoria
            inner join unidad_medida um on a.idunidad_medida = um.idunidad_medida
			where i.estado = 'A' and i.idsucursal =$sucursal and di.stock_actual > 0 order by fecha asc";
		$query = $conexion->query($sql);
		return $query;
	}
	public function Save($iddevolucion_motivo, $observacion, $fecha, $idsucursal, $idUsuario, $detalle)
	{

		// print_r($detalle);

		global $conexion;
		$sql = "INSERT INTO devolucion
	(
	idempleado,
	idsucursal,
	prefijo,
	fecha,
	hora,
	iddevolucion_motivo,
	observacion,
	filename,
	fecha_registro,
	fecha_modificado
	)
	VALUES 
	(
	$idUsuario,
	$idsucursal,
	'DV',
	'$fecha',
	TIME(NOW()),
	'$iddevolucion_motivo',
	'$observacion',
	'DV-01-13032023',
	CURRENT_TIMESTAMP(),
	CURRENT_TIMESTAMP()
	)
	";
		$query = $conexion->query($sql);
		$iddevolucion = $conexion->insert_id;
		$conexion->autocommit(true);

		foreach ($detalle as $val) {
			$valor = explode(',', $val);
			$iddetalle_ingreso = $valor[0];
			$cantidad_devolucion = $valor[6];

			$sql = "SELECT * from detalle_ingreso where iddetalle_ingreso=$iddetalle_ingreso";
			$detalle_ingreso = $conexion->query($sql)->fetch_object();

			$stock_actual = $detalle_ingreso->stock_actual - $cantidad_devolucion;


			$sql = "SELECT SUM(stock_actual) stock from detalle_ingreso
		join ingreso on ingreso.idingreso=detalle_ingreso.idingreso
		where idarticulo=$detalle_ingreso->idarticulo and idsucursal=$idsucursal and estado='A'";

			$suma_total_anterior = $conexion->query($sql)->fetch_object()->stock;

			$sql = "UPDATE detalle_ingreso  set stock_actual=$stock_actual where iddetalle_ingreso=$iddetalle_ingreso";
			$conexion->query($sql);


			$sql = "SELECT SUM(stock_actual) stock from detalle_ingreso
		join ingreso on ingreso.idingreso=detalle_ingreso.idingreso
		where idarticulo=$detalle_ingreso->idarticulo and idsucursal=$idsucursal and estado='A'";


			$suma_total_posterior = $conexion->query($sql)->fetch_object()->stock;
			$sql = "INSERT INTO devolucion_articulo
		(
		iddevolucion,
		idarticulo,
		cantidad,
		iddetalle_ingreso
		)VALUES 
		(
		$iddevolucion,
		$detalle_ingreso->idarticulo,
		$cantidad_devolucion,
		$detalle_ingreso->iddetalle_ingreso
		)
		";
			$query = $conexion->query($sql);
			$detallePedido = 0;

			$sql = "INSERT INTO kardex(
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
			'$idsucursal',
			CURRENT_TIMESTAMP(),
			 'salida por devolucion',
			 '$detalle_ingreso->idarticulo',
			 '" . $detalle_ingreso->iddetalle_ingreso . "',
			  '" . $suma_total_anterior . "',
			'" . $cantidad_devolucion . "',
			'" . $suma_total_posterior . "',
			CURRENT_TIMESTAMP(),
			CURRENT_TIMESTAMP(),
			'" . $detallePedido . "'
			)";
			// echo $sql;
			$query = $conexion->query($sql);
		}
		return $query;
	}
}
