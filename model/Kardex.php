<?php

require "Conexion.php";
class Kardex
{

	public function BuscarArticulos($q)
	{

		//var_dump($q);exit;

		global $conexion;

		$sql = "SELECT 
		idarticulo AS id, 
		CONCAT(articulo.nombre,' ',descripcion,' ',categoria.nombre)AS texto 
		FROM articulo 
		join categoria on categoria.idcategoria=articulo.idcategoria
		
		WHERE CONCAT(articulo.nombre,' ',descripcion,' ',categoria.nombre) LIKE '%" . $q . "%'";
		$query = $conexion->query($sql);
		//var_dump($reg = $query->fetch_object());exit;

		return $query;
	}
	public function TraerDatosTablaKardex($q)
	{
		global $conexion;


		$id_articulo= isset($q)?$q:0;  
		// $ejemplo=4942;

		$id_articulo=$id_articulo;
		// exit;
		$idsucursal=1;
		
		// var_dump($id_articulo);

		$sql = "SELECT 
		id_kardex,
		fecha_emision
		 as Fecha,
		
		 tipo
		 AS Movimiento,
		  CASE
			WHEN 0!=id_detalle_pedido THEN CONCAT( venta.tipo_comprobante ,' ', venta.serie_comprobante , '-', venta.num_comprobante) 
			WHEN 0!=id_detalle_ingreso THEN CONCAT(ingreso.tipo_comprobante ,' ', ingreso.serie_comprobante , '-', ingreso.num_comprobante ) 
			ELSE 0
			END
		 AS Orden,
		--  
		 CASE
			WHEN 0!=id_detalle_ingreso THEN persona.nombre 
			ELSE '-'
			END
		 AS Proveedor,
		 CASE
			WHEN 0!=id_detalle_pedido THEN CONCAT (persona.nombre ,' ',persona.apellido)
			ELSE '-'
			END
		 AS Cliente,
		stock_anterior,
		kardex.cantidad,
		kardex.stock_actual	
		  from kardex
			
		LEFT  JOIN detalle_ingreso on detalle_ingreso.iddetalle_ingreso=kardex.id_detalle_ingreso
		
		LEFT  JOIN ingreso on ingreso.idingreso=detalle_ingreso.idingreso
		left join detalle_pedido on detalle_pedido.iddetalle_pedido=kardex.id_detalle_pedido
		left join pedido on pedido.idpedido =detalle_pedido.idpedido
		left JOIN persona
		on case 
		when  0!=id_detalle_ingreso  then persona.idpersona = ingreso.idproveedor 
		else  persona.idpersona = pedido.idcliente
		end
		LEFT join venta on pedido.idpedido=venta.idpedido
		where kardex.id_articulo=" . $id_articulo . " 
		ORDER BY id_kardex
		";
		$query = $conexion->query($sql);
		
		// var_dump($query->fetch_object());

		return $query;


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
