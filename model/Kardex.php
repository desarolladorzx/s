<?php

require "Conexion.php";
class Kardex
{

	public function BuscarArticulos($q)
	{

		//var_dump($q);exit;

		global $conexion;

		$sql = "SELECT idarticulo AS id,nombre AS texto FROM articulo WHERE CONCAT(nombre,' ',descripcion) LIKE '%" . $q . "%'";
		$query = $conexion->query($sql);
		//var_dump($reg = $query->fetch_object());exit;

		return $query;
	}
	public function TraerDatosTablaKardex($q)
	{
		global $conexion;

		var_dump($q);exit;

		// $sql = "select p.*, c.nombre as Cliente,c.apellido as APCliente, c.email, c.direccion_calle , c.num_documento, c.telefono
		// 	from pedido p inner join persona c on p.idcliente = c.idpersona where p.idsucursal = $idsucursal 
		// 	and c.tipo_persona = 'Cliente' & 'Distribuidor' & 'Vip' & 'Tipo 1' & 'Tipo 2' & 'N' and p.tipo_pedido <> 'Venta' order by idpedido limit 0,10000";
		// $query = $conexion->query($sql);
		// return $query;


		// global $conexion;

		// $sql = "SELECT idarticulo AS id,nombre AS texto FROM articulo WHERE CONCAT(nombre,' ',descripcion) LIKE '%".$q."%'";
		// $query = $conexion->query($sql);
		// //var_dump($reg = $query->fetch_object());exit;

		// return $query;
	}
}
