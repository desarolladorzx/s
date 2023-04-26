<?php
	require "Conexion.php";
	class ConsultasVentas{



		public function __construct(){
		}

		public function listaDeRoles(){
			global $conexion;
			$sql="SELECT idempleado ,rol.r_prefijo, CONCAT(nombre,' ',apellidos) nombre FROM empleado
			JOIN rol ON rol.r_id=empleado.idrol
			
			 WHERE idrol=1";
			$query=$conexion->query($sql);
			return $query;
		}
		public function ListarVentasFechas($fecha_desde, $fecha_hasta,$ejecutivo_comercial ,$antiguedad_cliente ,$tipo_cliente){
			global $conexion;

			$sql = "select 
			pe.*,
			concat(pe.direccion_departamento,' - ',pe.direccion_provincia,' - ',pe.direccion_distrito,'  |  ',pe.direccion_calle, '|',IFNULL(pe.direccion_referencia,'')) as destino,

			CONCAT( IFNULL(departamento.descripcion,'') ,' - ',IFNULL(provincia.descripcion,''), ' - ',IFNULL(distrito.descripcion,''),' - ',pe.direccion_calle ,' - ',IFNULL(pe.direccion_referencia,'')) destino 
,
			concat(e.nombre,' ',e.apellidos,' |  ',v.fecha) as aproba_venta,

			concat(e.nombre,' ',e.apellidos,' |  ',p.fecha_apro_coti) as aproba_pedido ,

			p.*, p.idpedido, p.tipo_pedido, v.fecha,s.razon_social as sucursal,pe.tipo_persona as tipo_cliente,pe.numero_cuenta as nuevo_antiguo,
							concat(e.apellidos,' ',e.nombre) as empleado,
							concat(pe.nombre,' ',pe.apellido) as cliente,
							pe.num_documento as dni,pe.telefono as celular,pe.telefono_2,
							
							if(pe.direccion_distrito>0,departamento.descripcion,pe.direccion_distrito)departamento 
							,
							concat(v.serie_comprobante,'-',v.num_comprobante) as ticket,
							p.metodo_pago as cuenta_abonada,
							v.tipo_comprobante as comprobante,p.agencia_envio as transporte,
							v.serie_comprobante as serie,v.num_comprobante as numero,
							v.impuesto,
							format((v.total-(v.impuesto*v.total/(100+v.impuesto))),2) as subtotal,
							format((v.impuesto*v.total/(100+v.impuesto)),2) as totalimpuesto,
							v.total
							from venta v inner join pedido p on v.idpedido=p.idpedido
							inner join sucursal s on p.idsucursal=s.idsucursal
							inner join usuario u on p.idusuario=u.idusuario
							inner join empleado e on u.idempleado=e.idempleado
							inner join persona pe on p.idcliente=pe.idpersona


							LEFT JOIN distrito ON distrito.iddistrito=pe.direccion_distrito
							left JOIN provincia ON provincia.idprovincia=pe.direccion_provincia
							left JOIN departamento ON departamento.iddepartamento=provincia.iddepartamento
				where v.fecha>='$fecha_desde' and v.fecha<='$fecha_hasta' and v.estado='A'
				AND 
				CASE
				WHEN LENGTH('$ejecutivo_comercial')>0 THEN  e.idempleado='$ejecutivo_comercial'
				else v.idventa
				end

				AND 
				CASE
				WHEN LENGTH('$tipo_cliente')>0 THEN  pe.tipo_persona='$tipo_cliente'
				else v.idventa
				end

				AND 
				CASE
				WHEN LENGTH('$antiguedad_cliente')>0 THEN  pe.numero_cuenta='$antiguedad_cliente'
				else v.idventa
				end
				order by v.idventa asc";
				// echo $sql;
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarVentasDetalladas($fecha_desde, $fecha_hasta){
			
			global $conexion;
				$sql="select s.razon_social as sucursal, v.fecha,pe.numero_cuenta as antiguedad,pe.tipo_persona as tipo_cliente,
				concat(e.apellidos,' ',e.nombre) as empleado,
				concat(pe.nombre,' ',pe.apellido) as cliente,
				v.tipo_comprobante as comprobante,
				v.serie_comprobante as serie,v.num_comprobante as numero,
				v.impuesto,c.nombre as marca,m.nombre as categoria,
				a.nombre as articulo,di.codigo as codigo,di.serie as serie_art,
				dp.cantidad,dp.precio_venta,dp.descuento,
				(dp.precio_venta-dp.descuento) as venta_unitario,
				(dp.cantidad*(dp.precio_venta-dp.descuento))as total,
				di.precio_compra as costo,
				(dp.cantidad*di.precio_compra) as costo_total,
				((dp.cantidad*(dp.precio_venta-dp.descuento))-(di.precio_compra*dp.cantidad)) as ganancia,
				p.tipo_promocion as promocion, 
				if(pe.direccion_distrito>0,departamento.descripcion,pe.direccion_distrito)departamento 
				,
				pe.direccion_distrito as distrito,p.metodo_pago as banco_abono
				from detalle_pedido dp inner join detalle_ingreso di on dp.iddetalle_ingreso=di.iddetalle_ingreso
				inner join articulo a on di.idarticulo=a.idarticulo
				inner join categoria c on a.idcategoria=c.idcategoria
				inner join marca m on a.idmarca=m.idmarca
				inner join pedido p on dp.idpedido=p.idpedido
				inner join venta v on v.idpedido=p.idpedido
				inner join sucursal s on p.idsucursal=s.idsucursal
				inner join usuario u on p.idusuario=u.idusuario
				inner join empleado e on u.idempleado=e.idempleado
				inner join persona pe on p.idcliente=pe.idpersona

				LEFT JOIN distrito ON distrito.iddistrito=pe.direccion_distrito
				left JOIN provincia ON provincia.idprovincia=pe.direccion_provincia
				left JOIN departamento ON departamento.iddepartamento=provincia.iddepartamento

				where v.fecha>='$fecha_desde' and v.fecha<='$fecha_hasta'
				 and v.estado='A'
				order by v.fecha asc
				";
				// echo $sql;
			
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarVentasAnuladas($fecha_desde, $fecha_hasta){
			global $conexion;
			$sql = "select p.idpedido, p.tipo_pedido, v.fecha,s.razon_social as sucursal,
				concat(e.apellidos,' ',e.nombre) as empleado,
				concat(pe.nombre,' ',pe.apellido) as cliente,
				pe.num_documento as dni,pe.telefono as celular, pe.direccion_departamento as departamento,
				concat(v.serie_comprobante,'-',v.num_comprobante) as ticket,
				p.metodo_pago as cuenta_abonada,
				v.tipo_comprobante as comprobante,p.agencia_envio as transporte,
				v.serie_comprobante as serie,v.num_comprobante as numero,
				v.impuesto,
				format((v.total-(v.impuesto*v.total/(100+v.impuesto))),2) as subtotal,
				format((v.impuesto*v.total/(100+v.impuesto)),2) as totalimpuesto,
				v.total,p.estado
				from venta v inner join pedido p on v.idpedido=p.idpedido
				inner join sucursal s on p.idsucursal=s.idsucursal
				inner join usuario u on p.idusuario=u.idusuario
				inner join empleado e on u.idempleado=e.idempleado
				inner join persona pe on p.idcliente=pe.idpersona
				where v.fecha>='$fecha_desde' and v.fecha<='$fecha_hasta' and v.estado='C'
				order by v.fecha desc ";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarVentasPendientes($fecha_desde, $fecha_hasta){
			global $conexion;
			$sql = "select v.fecha,s.razon_social as sucursal,
				concat(e.apellidos,' ',e.nombre) as empleado,
				concat(pe.nombre,' ',pe.apellido) as cliente,
				v.tipo_comprobante as comprobante,
				v.serie_comprobante as serie,v.num_comprobante as numero,
				v.impuesto,
				a.nombre as articulo,di.codigo as codigo,di.serie as serie_art,
				dp.cantidad,dp.precio_venta,dp.descuento,
				(dp.cantidad*(dp.precio_venta-dp.descuento))as total
				from detalle_pedido dp inner join detalle_ingreso di on dp.iddetalle_ingreso=di.iddetalle_ingreso
				inner join articulo a on di.idarticulo=a.idarticulo
				inner join pedido p on dp.idpedido=p.idpedido 
				inner join venta v on v.idpedido=p.idpedido
				inner join sucursal s on p.idsucursal=s.idsucursal
				inner join usuario u on p.idusuario=u.idusuario
				inner join empleado e on u.idempleado=e.idempleado
				inner join persona pe on p.idcliente=pe.idpersona
				where v.fecha>='$fecha_desde' and v.fecha<='$fecha_hasta' 
				and v.estado='A'
				order by v.fecha desc
				";
			$query = $conexion->query($sql);
			return $query;
		}

		// Ventas al CREDITO , cambiadas por pedidos

		// public function ListarVentasPendientes($idsucursal, $fecha_desde, $fecha_hasta){
		// 	global $conexion;
		// 	$sql = "select v.fecha,s.razon_social as sucursal,
		// 		concat(e.apellidos,' ',e.nombre) as empleado,
		// 		pe.nombre as cliente,v.tipo_comprobante as comprobante,
		// 		v.serie_comprobante as serie,v.num_comprobante as numero,
		// 		v.impuesto,
		// 		format((v.total-(v.impuesto*v.total/(100+v.impuesto))),2) as subtotal,
		// 		format((v.impuesto*v.total/(100+v.impuesto)),2) as totalimpuesto,
		// 		v.total as totalpagar,(select sum(total_pago) from credito where idventa=v.idventa)as totalpagado,
		// 		(v.total-(select sum(total_pago) from credito where idventa=v.idventa))as totaldeuda
		// 		from venta v inner join pedido p on v.idpedido=p.idpedido
		// 		inner join sucursal s on p.idsucursal=s.idsucursal
		// 		inner join usuario u on p.idusuario=u.idusuario
		// 		inner join empleado e on u.idempleado=e.idempleado
		// 		inner join persona pe on p.idcliente=pe.idpersona
		// 		where v.fecha>='$fecha_desde' and v.fecha<='$fecha_hasta' 
		// 		and (v.total-(select sum(total_pago) from credito where idventa=v.idventa))> 0
		// 		and s.idsucursal= $idsucursal and v.tipo_venta='Credito' and v.estado='A'
		// 		order by v.fecha desc
		// 		";
		// 	$query = $conexion->query($sql);
		// 	return $query;
		// }

		public function ListarVentasContado($fecha_desde, $fecha_hasta){
			global $conexion;
			$sql = "select v.fecha,s.razon_social as sucursal,
				concat(e.apellidos,' ',e.nombre) as empleado,
				concat(pe.nombre,' ',pe.apellido) as cliente,v.tipo_comprobante as comprobante,
				v.serie_comprobante as serie,v.num_comprobante as numero,
				v.impuesto,
				format((v.total-(v.impuesto*v.total/(100+v.impuesto))),2) as subtotal,
				format((v.impuesto*v.total/(100+v.impuesto)),2) as totalimpuesto,
				v.total
				from venta v inner join pedido p on v.idpedido=p.idpedido
				inner join sucursal s on p.idsucursal=s.idsucursal
				inner join usuario u on p.idusuario=u.idusuario
				inner join empleado e on u.idempleado=e.idempleado
				inner join persona pe on p.idcliente=pe.idpersona
				where v.fecha>='$fecha_desde' and v.fecha<='$fecha_hasta' and v.tipo_venta='Contado' and v.estado='A'
				order by v.fecha desc
				";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarVentasCredito($fecha_desde, $fecha_hasta){
			global $conexion;
			$sql = "select v.fecha,s.razon_social as sucursal,
				concat(e.apellidos,' ',e.nombre) as empleado,
				concat(pe.nombre,' ',pe.apellido) as cliente,v.tipo_comprobante as comprobante,
				v.serie_comprobante as serie,v.num_comprobante as numero,
				v.impuesto,
				format((v.total-(v.impuesto*v.total/(100+v.impuesto))),2) as subtotal,
				format((v.impuesto*v.total/(100+v.impuesto)),2) as totalimpuesto,
				v.total as totalpagar,(select sum(total_pago) from credito where idventa=v.idventa)as totalpagado,
				(v.total-(select sum(total_pago) from credito where idventa=v.idventa))as totaldeuda
				from venta v inner join pedido p on v.idpedido=p.idpedido
				inner join sucursal s on p.idsucursal=s.idsucursal
				inner join usuario u on p.idusuario=u.idusuario
				inner join empleado e on u.idempleado=e.idempleado
				inner join persona pe on p.idcliente=pe.idpersona
				where v.fecha>='$fecha_desde' and v.fecha<='$fecha_hasta' 
				and v.tipo_venta='Credito' and v.estado='A'
				order by v.fecha desc
				";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarVentasCliente($idcliente, $fecha_desde, $fecha_hasta){
			global $conexion;
			$sql = "select p.idpedido, p.tipo_pedido, v.fecha,s.razon_social as sucursal,
				concat(e.apellidos,' ',e.nombre) as empleado,
				concat(pe.nombre,' ',pe.apellido) as cliente,
				pe.num_documento as dni,pe.telefono as celular,pe.telefono_2, pe.direccion_departamento as departamento,
				concat(v.serie_comprobante,'-',v.num_comprobante) as ticket,
				v.metodo_pago as cuenta_abonada,
				v.tipo_comprobante as comprobante,v.agencia_envio as transporte,
				v.serie_comprobante as serie,v.num_comprobante as numero,
				v.impuesto,
				format((v.total-(v.impuesto*v.total/(100+v.impuesto))),2) as subtotal,
				format((v.impuesto*v.total/(100+v.impuesto)),2) as totalimpuesto,
				v.total
				from venta v inner join pedido p on v.idpedido=p.idpedido
				inner join sucursal s on p.idsucursal=s.idsucursal
				inner join usuario u on p.idusuario=u.idusuario
				inner join empleado e on u.idempleado=e.idempleado
				inner join persona pe on p.idcliente=pe.idpersona
				where v.fecha>='$fecha_desde' and v.fecha<='$fecha_hasta'
				and pe.idpersona= $idcliente and v.estado='A'
				order by v.fecha desc
				";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarVentasEmpleado($idempleado, $fecha_desde, $fecha_hasta){
			global $conexion;
			$sql = "select v.fecha,s.razon_social as sucursal,
				concat(e.apellidos,' ',e.nombre) as empleado,
				concat(pe.nombre,' ',pe.apellido) as cliente,v.tipo_comprobante as comprobante,
				v.serie_comprobante as serie,v.num_comprobante as numero,
				v.impuesto,
				format((v.total-(v.impuesto*v.total/(100+v.impuesto))),2) as subtotal,
				format((v.impuesto*v.total/(100+v.impuesto)),2) as totalimpuesto,
				v.total
				from venta v inner join pedido p on v.idpedido=p.idpedido
				inner join sucursal s on p.idsucursal=s.idsucursal
				inner join usuario u on p.idusuario=u.idusuario
				inner join empleado e on u.idempleado=e.idempleado
				inner join persona pe on p.idcliente=pe.idpersona
				where v.fecha>='$fecha_desde' and v.fecha<='$fecha_hasta'
				and e.idempleado= $idempleado and v.estado='A'
				order by v.fecha desc;
				";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarVentasEmpleadoDet($idempleado, $fecha_desde, $fecha_hasta){
			global $conexion;
			$sql = "select v.fecha,s.razon_social as sucursal,
				concat(e.apellidos,' ',e.nombre) as empleado,
				concat(pe.nombre,' ',pe.apellido) as cliente,v.tipo_comprobante as comprobante,
				v.serie_comprobante as serie,v.num_comprobante as numero,
				v.impuesto,
				a.nombre as articulo,di.codigo as codigo,di.serie as serie_art,
				dp.cantidad,dp.precio_venta,dp.descuento,
				(dp.cantidad*(dp.precio_venta-dp.descuento))as total
				from detalle_pedido dp inner join detalle_ingreso di on dp.iddetalle_ingreso=di.iddetalle_ingreso
				inner join articulo a on di.idarticulo=a.idarticulo
				inner join pedido p on dp.idpedido=p.idpedido 
				inner join venta v on v.idpedido=p.idpedido
				inner join sucursal s on p.idsucursal=s.idsucursal
				inner join usuario u on p.idusuario=u.idusuario
				inner join empleado e on u.idempleado=e.idempleado
				inner join persona pe on p.idcliente=pe.idpersona
				where v.fecha>='$fecha_desde' and v.fecha<='$fecha_hasta'
				and v.estado='A'
				and e.idempleado= $idempleado
				order by v.fecha desc
				";
			$query = $conexion->query($sql);
			return $query;
		}
	}