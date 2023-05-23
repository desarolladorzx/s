<?php
require "Conexion.php";

class articulo
{

	public function __construct()
	{
	}

	public function Registrar(
		$idmarca,
		$idcategoria,
		$idunidad_medida,
		$nombre,
		$descripcion,
		$imagen,
		$stockMinimo,
		$precio_compra,
		$precio_final,
		$precio_distribuidor,
		$precio_superdistribuidor,
		$precio_representante,
		$lote,
		$bar_code,
		$interno_id
	) {
		global $conexion;
		$sql = "INSERT INTO articulo(idmarca, idcategoria,idunidad_medida, nombre, descripcion, imagen, estado,stock_min,
			
			precio_compra,precio_final,precio_distribuidor,
			precio_superdistribuidor,precio_representante
			
			,lote,barcode ,interno_id
			)
						VALUES($idmarca, $idcategoria, $idunidad_medida, '$nombre','$descripcion', '$imagen', 'A',$stockMinimo,
						'$precio_compra',
						'$precio_final',
						'$precio_distribuidor',
						'$precio_superdistribuidor',
						'$precio_representante'

						,'$lote'
						,'$bar_code'
						,'$interno_id'
					
						)";

		// echo $sql;
		$query = $conexion->query($sql);
		return $query;
	}

	public function Modificar(
		$idarticulo,
		$idmarca,
		$idcategoria,
		$idunidad_medida,
		$nombre,
		$descripcion,
		$imagen,
		$stockMinimo,

		$precio_compra,
		$precio_final,
		$precio_distribuidor,
		$precio_superdistribuidor,
		$precio_representante,
		$lote,
		$bar_code,
		$interno_id

	) {
		global $conexion;
		$sql = "UPDATE articulo set idmarca = $idmarca, idcategoria = $idcategoria, idunidad_medida = $idunidad_medida, nombre = '$nombre',
						descripcion = '$descripcion', imagen = '$imagen',stock_min='$stockMinimo'

						,precio_compra='$precio_compra',
						precio_final='$precio_final',				precio_distribuidor='$precio_distribuidor'					,precio_superdistribuidor='$precio_superdistribuidor',					
						precio_representante='$precio_representante'

						,lote='$lote',
						barcode='$bar_code',
						interno_id='$interno_id'


						WHERE idarticulo = $idarticulo";
		$query = $conexion->query($sql);

		// echo $sql;
		return $query;
	}

	public function Eliminar($idarticulo)
	{
		global $conexion;
		$sql = "UPDATE articulo set estado = 'N' WHERE idarticulo = $idarticulo";
		$query = $conexion->query($sql);
		return $query;
	}

	public function Listar()
	{
		global $conexion;
		$sql = "SELECT a.*, c.nombre AS categoria, um.nombre AS unidadMedida, m.nombre AS marca,
		SUM(detalle_ingreso.stock_actual) AS sumTotal,
		SUM(CASE WHEN ingreso.idsucursal = 1 THEN detalle_ingreso.stock_actual ELSE 0 END) AS totalSucursal1,
		SUM(CASE WHEN ingreso.idsucursal = 2 THEN detalle_ingreso.stock_actual ELSE 0 END) AS totalSucursal2
	FROM articulo a
	INNER JOIN categoria c ON a.idcategoria = c.idcategoria
	INNER JOIN detalle_ingreso ON detalle_ingreso.idarticulo = a.idarticulo
	INNER JOIN marca m ON a.idmarca = m.idmarca
	INNER JOIN unidad_medida um ON a.idunidad_medida = um.idunidad_medida
	INNER JOIN ingreso ON detalle_ingreso.idingreso = ingreso.idingreso
	WHERE a.estado = 'A'
	GROUP BY a.idarticulo
	ORDER BY idarticulo DESC;";
		$query = $conexion->query($sql);
		return $query;
	}


	public function Reporte()
	{
		global $conexion;
		$sql = "select a.*, c.nombre as categoria, um.nombre as unidadMedida 
			from articulo a inner join categoria c on a.idcategoria = c.idcategoria
			inner join unidad_medida um on a.idunidad_medida = um.idunidad_medida where a.estado = 'A' order by a.nombre asc";
		$query = $conexion->query($sql);
		return $query;
	}
}
