<?php

	require "Conexion.php";
	class GraficoEmp{

		public function __construct(){
		}

		public function ComprasMesSucursal($idsucursal,$idusuario){

			global $conexion;
			$sql = "SELECT
			monthname(i.Fecha) as mes, sum(i.total) as totalmes
			from ingreso i
			where i.estado='A' and i.idsucursal='$idsucursal' and i.idusuario='$idusuario'
			/* and date(i.fecha)>='2022-01-01' and date(i.fecha)<='2022-12-31' */
			group by monthname(i.Fecha) order by month(i.Fecha) desc
			limit 12 ";
			$query = $conexion->query($sql);
			return $query;
		} 

		public function VentasMesSucursal($idsucursal,$idusuario){

			global $conexion;
			$sql = "SELECT monthname(v.Fecha) as mes, sum(v.total) as totalmes
			from venta v
			inner join pedido p on v.idpedido=p.idpedido
			where v.estado='A' and p.idsucursal='$idsucursal' and p.idusuario='$idusuario'
			/* and date(v.fecha)>='2022-01-01' and date(v.fecha)<='2022-12-31' */
			group by monthname(v.Fecha)
			order by month(v.Fecha) desc
			limit 12";
			$query = $conexion->query($sql);
			return $query;
		}

		public function VentasDiasSucursal($idsucursal,$idusuario){

			global $conexion;
			$sql = "SELECT date(v.Fecha) as dia, sum(v.total) as totaldia
			from venta v
			inner join pedido p on v.idpedido=p.idpedido
			where v.estado='A' and p.idsucursal='$idsucursal' and p.idusuario='$idusuario'
			/* and date(v.fecha)>='2022-01-01' and date(v.fecha)<='2022-01-31'*/
			group by date(v.Fecha)
			order by date(v.Fecha) desc
			limit 15";
			$query = $conexion->query($sql); 
			return $query;
		}

		public function ProductosVendidosAno($idsucursal,$idusuario){
			//Se cambio a productos mas vendidos en el mes
			global $conexion;
			$sql = "SELECT a.nombre as articulo,sum(dp.cantidad) as cantidad
			from articulo a inner join detalle_ingreso di
			on a.idarticulo=di.idarticulo
			inner join detalle_pedido dp on dp.iddetalle_ingreso=di.iddetalle_ingreso
			inner join pedido p on p.idpedido=dp.idpedido
			inner join venta v on p.idpedido=v.idpedido
			where v.estado='A' and year(v.fecha)=year(curdate())
			and p.idsucursal='$idsucursal' and p.idusuario='$idusuario'
			and date(v.fecha)>='2022-01-01' and date(v.fecha)<='2022-01-31'
			group by a.nombre
			order by sum(dp.cantidad) desc
			limit 10";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Totales($idsucursal,$idusuario){

			global $conexion;
			$sql = "SELECT (select simbolo_moneda from global order by idglobal desc limit 1 ) as moneda,(select ifnull(sum(total),0) from ingreso
			where date(fecha)=curdate() and estado='A' and idsucursal='$idsucursal' and idusuario='$idusuario') as totalingreso,
			(select ifnull(sum(v.total),0) from venta v inner join pedido p
			on v.idpedido=p.idpedido
			where date(v.fecha)=curdate() and v.tipo_venta='Contado' and v.estado='A'
			and p.idsucursal='$idsucursal' and p.idusuario='$idusuario') as totalcontado,
			(select ifnull(sum(v.total),0) from venta v inner join pedido p
			on v.idpedido=p.idpedido
			where MONTH(v.fecha)=MONTH(now()) and  year(v.fecha)=year(now()) and v.estado='A'
			and p.idsucursal='$idsucursal' and p.idusuario='$idusuario') as totalcontadomes,
			(select ifnull(sum(c.total_pago),0) from credito c
			inner join venta v on c.idventa=v.idventa
			inner join pedido p on v.idpedido=p.idpedido
			where date(c.fecha_pago)= curdate() and v.estado='A'
			and p.idsucursal='$idsucursal' and p.idusuario='$idusuario') as totalcredito";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ComprasMes(){

			global $conexion;
			$sql = "SELECT
			monthname(i.Fecha) as mes, sum(i.total) as totalmes
			from ingreso i
			where i.estado='A'
			and date(i.fecha)>='2022-01-01' and date(i.fecha)<='2022-12-31'
			group by monthname(i.Fecha) order by month(i.Fecha) asc
			limit 12 ";
			$query = $conexion->query($sql);
			return $query;
		}

		public function VentasMes(){

			global $conexion;
			$sql = "SELECT
			monthname(v.Fecha) as mes, sum(v.total) as totalmes
			from venta v
			inner join pedido p on v.idpedido=p.idpedido
			where v.estado='A'
			and date(v.fecha)>='2022-01-01' and date(v.fecha)<='2022-12-31'
			group by monthname(v.Fecha) order by month(v.Fecha) asc
			limit 12";
			$query = $conexion->query($sql);
			return $query;
		}

		public function VentasDias(){

			global $conexion;
			$sql = "SELECT
			date(v.Fecha) as dia, sum(v.total) as totaldia
			from venta v
			inner join pedido p on v.idpedido=p.idpedido
			where v.estado='A'
			and date(v.fecha)>='2022-02-01' and date(v.fecha)<='2022-03-01'
			group by date(v.Fecha) order by date(v.Fecha) asc
			limit 31";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ProductosVendidosAnoTotal(){

			global $conexion;
			$sql = "SELECT a.nombre as articulo,sum(dp.cantidad) as cantidad
			from articulo a inner join detalle_ingreso di
			on a.idarticulo=di.idarticulo
			inner join detalle_pedido dp on dp.iddetalle_ingreso=di.iddetalle_ingreso
			inner join pedido p on p.idpedido=dp.idpedido
			inner join venta v on p.idpedido=v.idpedido
			where v.estado='A' and year(v.fecha)=year(curdate())
			and date(v.fecha)>='2022-02-01' and date(v.fecha)<='2022-03-01'
			group by a.nombre
			order by sum(dp.cantidad) desc
			limit 10";
			$query = $conexion->query($sql);
			return $query;
		}

		public function CantPedidosVendidos(){

			global $conexion;
			$sql = "SELECT p.idpedido, p.tipo_pedido, v.fecha,s.razon_social as sucursal,
			concat(e.apellidos,' ',e.nombre) as empleado,
			concat(pe.nombre,' ',pe.apellido) as cliente,
			pe.num_documento as dni,pe.telefono as celular, pe.direccion_departamento as departamento,
			concat(v.serie_comprobante,'-',v.num_comprobante) as ticket,
			from venta v inner join pedido p on v.idpedido=p.idpedido
			inner join sucursal s on p.idsucursal=s.idsucursal
			inner join usuario u on p.idusuario=u.idusuario
			inner join empleado e on u.idusuario=e.idusuario
			inner join persona pe on p.idcliente=pe.idpersona
			where MONTH(v.fecha)=MONTH(now())
			order by v.fecha desc";
			$query = $conexion->query($sql);
			return $query;
		}

		public function TotalesTotal(){
			global $conexion;
			$sql = "SELECT (select simbolo_moneda from global order by idglobal desc limit 1 ) as moneda,
			(select ifnull(sum(total),0) from ingreso
			where date(fecha)=curdate() and estado='A') as totalingreso,
			(select ifnull(sum(total),0) from ingreso
			where MONTH(fecha)=MONTH(now()) and  year(fecha)=year(now()) and estado='A' ) as totalingresomes,
			(select ifnull(sum(v.total),0) from venta v inner join pedido p
			on v.idpedido=p.idpedido 
			where date(v.fecha)=curdate() and v.tipo_venta='Contado' and v.estado='A') as totalcontado,
			(select ifnull(sum(v.total),0) from venta v inner join pedido p
			on v.idpedido=p.idpedido 
			where MONTH(v.fecha)=MONTH(now()) and  year(v.fecha)=year(now()) and v.estado='A') as totalcontadomes,
			(select ifnull(sum(c.total_pago),0) from credito c
			inner join venta v on c.idventa=v.idventa
			inner join pedido p on v.idpedido=p.idpedido
			where c.fecha_pago= curdate() and v.estado='A') as totalcredito";
			$query = $conexion->query($sql);
			return $query;
		}
	}