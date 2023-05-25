<?php
require "Conexion.php";


class Catalogo
{
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
	WHERE a.estado = 'A' AND ingreso.estado='A' AND detalle_ingreso.estado_detalle_ingreso='INGRESO'
	GROUP BY a.idarticulo
	ORDER BY idarticulo DESC;
	";
        $query = $conexion->query($sql);
        return $query;
    }
}
