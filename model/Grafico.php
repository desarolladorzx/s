<?php

require "Conexion.php";
class Grafico
{

	public function __construct()
	{
	}

	public function ComprasMesSucursal($idsucursal)
	{

		global $conexion;
		$sql = "SELECT
			monthname(i.Fecha) as mes, sum(i.total) as totalmes
			from ingreso i
			where i.estado='A' and i.idsucursal='$idsucursal'
			/* and date(i.fecha)>='2021-06-01' and date(i.fecha)<='2022-07-01' */
			group by monthname(i.Fecha) order by month(i.Fecha) desc
			limit 12 ";
		$query = $conexion->query($sql);
		return $query;
	}

	public function VentasMesSucursal($idsucursal)
	{

		global $conexion;
		$sql = "SELECT
			monthname(v.Fecha) as mes, sum(v.total) as totalmes
			from venta v
			inner join pedido p on v.idpedido=p.idpedido
			where v.estado='A' and p.idsucursal='$idsucursal'
			/* and date(v.fecha)>='2021-06-01' and date(v.fecha)<='2022-07-01' */
			group by monthname(v.Fecha)
			order by month(v.Fecha) desc
			limit 12";
		$query = $conexion->query($sql);
		return $query;
	}

	public function AsesoraVentasMesSucursal($idsucursal, $idusuario)
	{

		global $conexion;
		$sql = "SELECT monthname(v.Fecha) as mes, sum(v.total) as totalmes
			from venta v
			inner join pedido p on v.idpedido=p.idpedido
			where v.estado='A' and p.idsucursal='$idsucursal' and p.idusuario='$idusuario'
			/* and date(v.fecha)>='2021-01-01' and date(v.fecha)<='2022-07-01' */
			group by monthname(v.Fecha)
			order by month(v.Fecha) desc
			limit 12";
		$query = $conexion->query($sql);
		return $query;
	}

	public function VentasDiasSucursal($idsucursal)
	{

		global $conexion;
		$sql = "SELECT date(v.Fecha) as dia, sum(v.total) as totaldia
			from venta v
			inner join pedido p on v.idpedido=p.idpedido
			where v.estado='A' and p.idsucursal='$idsucursal'
			/* and date(v.fecha)>='2022-02-01' and date(v.fecha)<='2022-03-01' */
			group by date(v.Fecha)
			order by date(v.Fecha) desc
			limit 15";
		$query = $conexion->query($sql);

		return $query;
	}

	public function AsesoraVentasDiasSucursal($idsucursal, $idusuario)
	{

		global $conexion;
		$sql = "SELECT date(v.Fecha) as dia, sum(v.total) as totaldia
			from venta v
			inner join pedido p on v.idpedido=p.idpedido
			where v.estado='A' and p.idsucursal='$idsucursal' and p.idusuario='$idusuario'
			/* and date(v.fecha)>='2022-02-01' and date(v.fecha)<='2022-03-01' */
			group by date(v.Fecha)
			order by date(v.Fecha) desc
			limit 30";
		$query = $conexion->query($sql);

		return $query;
	}

	public function ProductosVendidosAno($idsucursal)
	{
		//Se cambio a productos mas vendidos en el mes
		global $conexion;
		$sql = "SELECT a.nombre as articulo,sum(dp.cantidad) as cantidad
			from articulo a inner join detalle_ingreso di
			on a.idarticulo=di.idarticulo
			inner join detalle_pedido dp on dp.iddetalle_ingreso=di.iddetalle_ingreso
			inner join pedido p on p.idpedido=dp.idpedido
			inner join venta v on p.idpedido=v.idpedido
			where v.estado='A' and year(v.fecha)=year(curdate())
			and p.idsucursal='$idsucursal'
			/* and date(v.fecha)>='2022-02-01' and date(v.fecha)<='2022-03-01' */
			group by a.nombre
			order by sum(dp.cantidad) desc
			limit 10";
		$query = $conexion->query($sql);
		return $query;
	}

	public function AsesoraProductosVendidosAno($idsucursal, $idusuario)
	{
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
			/* and date(v.fecha)>='2022-02-01' and date(v.fecha)<='2022-03-01' */
			group by a.nombre
			order by sum(dp.cantidad) desc
			limit 10";
		$query = $conexion->query($sql);
		return $query;
	}

	public function Totales($idsucursal)
	{

		$sql = "SELECT (select simbolo_moneda from global order by idglobal desc limit 1 ) as moneda,(select ifnull(sum(total),0) from ingreso
			where date(fecha)=curdate() and estado='A') as totalingreso,
			(select ifnull(sum(v.total),0) from venta v inner join pedido p
			on v.idpedido=p.idpedido 
			where date(v.fecha)=curdate() and v.tipo_venta='Contado' and v.estado='A'
			) as totalcontado,
			(select ifnull(sum(v.total),0) from venta v inner join pedido p
			on v.idpedido=p.idpedido 
			where MONTH(v.fecha)=MONTH(now()) and  year(v.fecha)=year(now()) and v.estado='A'
			) as totalcontadomes,
			(select ifnull(sum(c.total_pago),0) from credito c
			inner join venta v on c.idventa=v.idventa
			inner join pedido p on v.idpedido=p.idpedido
			where date(c.fecha_pago)= curdate() and v.estado='A'
			) as totalcredito";


		if ($idsucursal != 0) {
			$sql = "SELECT (select simbolo_moneda from global order by idglobal desc limit 1 ) as moneda,(select ifnull(sum(total),0) from ingreso
				where date(fecha)=curdate() and estado='A' and idsucursal='$idsucursal') as totalingreso,
				(select ifnull(sum(v.total),0) from venta v inner join pedido p
				on v.idpedido=p.idpedido 
				where date(v.fecha)=curdate() and v.tipo_venta='Contado' and v.estado='A'
				and p.idsucursal='$idsucursal') as totalcontado,
				(select ifnull(sum(v.total),0) from venta v inner join pedido p
				on v.idpedido=p.idpedido 
				where MONTH(v.fecha)=MONTH(now()) and  year(v.fecha)=year(now()) and v.estado='A'
				and p.idsucursal='$idsucursal') as totalcontadomes,
				(select ifnull(sum(c.total_pago),0) from credito c
				inner join venta v on c.idventa=v.idventa
				inner join pedido p on v.idpedido=p.idpedido
				where date(c.fecha_pago)= curdate() and v.estado='A'
				and p.idsucursal='$idsucursal') as totalcredito";
		}
		global $conexion;


		$query = $conexion->query($sql);

		// echo $sql;
		return $query;
	}
	public function VentasTotales($idsucursal,$idempleado)
	{
		$sql = "SELECT 
		(SELECT IFNULL(SUM(venta.total),0)  FROM venta join pedido on venta.idpedido=pedido.idpedido  WHERE venta.estado='A' AND date(venta.fecha)= CURRENT_DATE)   ventas_diarias,
		
		(SELECT IFNULL(SUM(venta.total),0)  FROM venta join pedido on venta.idpedido=pedido.idpedido  WHERE venta.estado='A' AND WEEK(venta.fecha)=WEEK(CURRENT_DATE)) ventas_semanales,
		
		(SELECT IFNULL(SUM(venta.total),0)  FROM venta join pedido on venta.idpedido=pedido.idpedido  WHERE venta.estado='A' AND MONTH(venta.fecha)= MONTH(CURRENT_DATE) ) ventas_mensuales,
		
		(SELECT IFNULL(SUM(venta.total),0)  FROM venta join pedido on venta.idpedido=pedido.idpedido  WHERE venta.estado='C' AND MONTH(venta.fecha)= MONTH(CURRENT_DATE)) ventas_anuladas
			";
		if ($idsucursal != 0) {
		$sql = "SELECT 
		(SELECT IFNULL(SUM(venta.total),0)  FROM venta join pedido on venta.idpedido=pedido.idpedido  WHERE venta.estado='A' AND date(venta.fecha)= CURRENT_DATE  and pedido.idsucursal='$idsucursal'  )   ventas_diarias,
		
		(SELECT IFNULL(SUM(venta.total),0)  FROM venta join pedido on venta.idpedido=pedido.idpedido  WHERE venta.estado='A' AND WEEK(venta.fecha)=WEEK(CURRENT_DATE) and pedido.idsucursal='$idsucursal' ) ventas_semanales,
		
		(SELECT IFNULL(SUM(venta.total),0) FROM venta join pedido on venta.idpedido=pedido.idpedido  WHERE venta.estado='A' AND MONTH(venta.fecha)= MONTH(CURRENT_DATE) and pedido.idsucursal='$idsucursal') ventas_mensuales,
		
		(SELECT SUM(venta.total) FROM venta join pedido on venta.idpedido=pedido.idpedido  WHERE venta.estado='C' AND MONTH(venta.fecha)= MONTH(CURRENT_DATE) and pedido.idsucursal='$idsucursal') ventas_anuladas
		";

		if($idempleado==12 || $idempleado==14 ||$idempleado==15 ||$idempleado==16 ||$idempleado==18 ||$idempleado==20  ||$idempleado==19 ){
			$sql="SELECT 
			(
			SELECT  IFNULL(SUM(venta.total),0) 
			FROM venta
			join pedido on venta.idpedido=pedido.idpedido 
			JOIN usuario ON usuario.idusuario=pedido.idusuario
			join empleado ON empleado.idempleado=usuario.idempleado
			WHERE venta.estado='A' AND date(venta.fecha)= CURRENT_DATE  
			AND empleado.idempleado=$idempleado and pedido.idsucursal='$idsucursal'
			)
			 ventas_diarias,
			(
			SELECT IFNULL(SUM(venta.total),0) 
			FROM venta 
			join pedido on venta.idpedido=pedido.idpedido 
			JOIN usuario ON usuario.idusuario=pedido.idusuario
			join empleado ON empleado.idempleado=usuario.idempleado
			WHERE venta.estado='A' AND WEEK(venta.fecha)=WEEK(CURRENT_DATE) 
			AND empleado.idempleado=$idempleado and pedido.idsucursal='$idsucursal'
			) ventas_semanales,
			(
			SELECT IFNULL(SUM(venta.total),0) 
			FROM venta 
			join pedido on venta.idpedido=pedido.idpedido 
			JOIN usuario ON usuario.idusuario=pedido.idusuario
			join empleado ON empleado.idempleado=usuario.idempleado
			WHERE venta.estado='A' AND MONTH(venta.fecha)= MONTH(CURRENT_DATE)
			AND empleado.idempleado=$idempleado and pedido.idsucursal='$idsucursal'
			) ventas_mensuales,
			(
			SELECT IFNULL(SUM(venta.total),0) 
			FROM venta 
			join pedido on venta.idpedido=pedido.idpedido 
			JOIN usuario ON usuario.idusuario=pedido.idusuario
			join empleado ON empleado.idempleado=usuario.idempleado 
			WHERE venta.estado='C' AND MONTH(venta.fecha)= MONTH(CURRENT_DATE) 
			AND empleado.idempleado=$idempleado and pedido.idsucursal='$idsucursal'
			) ventas_anuladas,
			(SELECT CONCAT(nombre,' ',apellidos) FROM empleado WHERE idempleado=$idempleado) vendedor
			";

		}

	

		}
		if($idsucursal==0 && in_array($idempleado,[12, 14, 15, 16, 18, 20 , 19])){
			$sql = "SELECT 
			(SELECT  IFNULL(SUM(venta.total),0) 
			FROM venta
			join pedido on venta.idpedido=pedido.idpedido 
			JOIN usuario ON usuario.idusuario=pedido.idusuario
			join empleado ON empleado.idempleado=usuario.idempleado
			WHERE venta.estado='A' AND date(venta.fecha)= CURRENT_DATE  
			AND empleado.idempleado=$idempleado)   ventas_diarias,
			
			(SELECT IFNULL(SUM(venta.total),0) 
			FROM venta 
			join pedido on venta.idpedido=pedido.idpedido 
			JOIN usuario ON usuario.idusuario=pedido.idusuario
			join empleado ON empleado.idempleado=usuario.idempleado
			WHERE venta.estado='A' AND WEEK(venta.fecha)=WEEK(CURRENT_DATE) 
			AND empleado.idempleado=$idempleado) ventas_semanales,
			
			(SELECT IFNULL(SUM(venta.total),0) 
			FROM venta 
			join pedido on venta.idpedido=pedido.idpedido 
			JOIN usuario ON usuario.idusuario=pedido.idusuario
			join empleado ON empleado.idempleado=usuario.idempleado
			WHERE venta.estado='A' AND MONTH(venta.fecha)= MONTH(CURRENT_DATE)
			AND empleado.idempleado=$idempleado) ventas_mensuales,
			
			(SELECT IFNULL(SUM(venta.total),0) 
			FROM venta 
			join pedido on venta.idpedido=pedido.idpedido 
			JOIN usuario ON usuario.idusuario=pedido.idusuario
			join empleado ON empleado.idempleado=usuario.idempleado 
			WHERE venta.estado='C' AND MONTH(venta.fecha)= MONTH(CURRENT_DATE) 
			AND empleado.idempleado=$idempleado) ventas_anuladas
				";

				
				
		}

		global $conexion;


		$query = $conexion->query($sql);

		// echo $sql;
		return $query;
	}

	public function AsesoraTotales($idsucursal, $idusuario)
	{

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
	public function VentasPorUsuario($empleados){



		global $conexion;

		
		foreach ($empleados as &$idempleado) {
			$sql="SELECT 
			(
			SELECT  IFNULL(SUM(venta.total),0) 
			FROM venta
			join pedido on venta.idpedido=pedido.idpedido 
			JOIN usuario ON usuario.idusuario=pedido.idusuario
			join empleado ON empleado.idempleado=usuario.idempleado
			WHERE venta.estado='A' AND date(venta.fecha)= CURRENT_DATE  
			AND empleado.idempleado=$idempleado
			)
			 ventas_diarias,
			 (
			SELECT  IFNULL(count(venta.total),0) 
			FROM venta
			join pedido on venta.idpedido=pedido.idpedido 
			JOIN usuario ON usuario.idusuario=pedido.idusuario
			join empleado ON empleado.idempleado=usuario.idempleado
			WHERE venta.estado='A' AND date(venta.fecha)= CURRENT_DATE  
			AND empleado.idempleado=$idempleado
			)
			 ventas_diarias_cantidad,

			(
			SELECT IFNULL(SUM(venta.total),0) 
			FROM venta 
			join pedido on venta.idpedido=pedido.idpedido 
			JOIN usuario ON usuario.idusuario=pedido.idusuario
			join empleado ON empleado.idempleado=usuario.idempleado
			WHERE venta.estado='A' AND WEEK(venta.fecha)=WEEK(CURRENT_DATE) 
			AND empleado.idempleado=$idempleado
			) ventas_semanales,
			(
			SELECT IFNULL(count(venta.total),0) 
			FROM venta 
			join pedido on venta.idpedido=pedido.idpedido 
			JOIN usuario ON usuario.idusuario=pedido.idusuario
			join empleado ON empleado.idempleado=usuario.idempleado
			WHERE venta.estado='A' AND WEEK(venta.fecha)=WEEK(CURRENT_DATE) 
			AND empleado.idempleado=$idempleado
			) ventas_semanales_cantidad,

			(
			SELECT IFNULL(SUM(venta.total),0) 
			FROM venta 
			join pedido on venta.idpedido=pedido.idpedido 
			JOIN usuario ON usuario.idusuario=pedido.idusuario
			join empleado ON empleado.idempleado=usuario.idempleado
			WHERE venta.estado='A' AND MONTH(venta.fecha)= MONTH(CURRENT_DATE)
			AND empleado.idempleado=$idempleado
			) ventas_mensuales,
			(
			SELECT IFNULL(count(venta.total),0) 
			FROM venta 
			join pedido on venta.idpedido=pedido.idpedido 
			JOIN usuario ON usuario.idusuario=pedido.idusuario
			join empleado ON empleado.idempleado=usuario.idempleado
			WHERE venta.estado='A' AND MONTH(venta.fecha)= MONTH(CURRENT_DATE)
			AND empleado.idempleado=$idempleado
			) ventas_mensuales_cantidad,

			(
			SELECT IFNULL(SUM(venta.total),0) 
			FROM venta 
			join pedido on venta.idpedido=pedido.idpedido 
			JOIN usuario ON usuario.idusuario=pedido.idusuario
			join empleado ON empleado.idempleado=usuario.idempleado 
			WHERE venta.estado='C' AND MONTH(venta.fecha)= MONTH(CURRENT_DATE) 
			AND empleado.idempleado=$idempleado
			) ventas_anuladas,
			(
			SELECT IFNULL(count(venta.total),0) 
			FROM venta 
			join pedido on venta.idpedido=pedido.idpedido 
			JOIN usuario ON usuario.idusuario=pedido.idusuario
			join empleado ON empleado.idempleado=usuario.idempleado 
			WHERE venta.estado='C' AND MONTH(venta.fecha)= MONTH(CURRENT_DATE) 
			AND empleado.idempleado=$idempleado
			) ventas_anuladas_cantidad,

			(SELECT CONCAT(nombre,' ',apellidos) FROM empleado WHERE idempleado=$idempleado) vendedor,

			(SELECT foto FROM empleado WHERE idempleado=$idempleado) foto
			;";
			$query = $conexion->query($sql);

			// echo $sql;
			$array[] = $query->fetch_object();

		}
		// echo json_encode($array);

		return json_encode($array);

	}

	public function TotalesVentas($idsucursal)
	{

		global $conexion;
		$sql = "SELECT (select simbolo_moneda from global order by idglobal desc limit 1 ) as moneda,(select ifnull(sum(v.total),0) from venta v inner join pedido p on v.idpedido=p.idpedido
			where date(v.fecha)=curdate() and v.tipo_venta='Contado' and v.estado='A'
			and p.idsucursal='$idsucursal' and p.idusuario='40') as ventas1,
            (select ifnull(sum(v.total),0) from venta v inner join pedido p
			on v.idpedido=p.idpedido
			where date(v.fecha)=curdate() and v.tipo_venta='Contado' and v.estado='A'
			and p.idsucursal='$idsucursal' and p.idusuario='36') as ventas2,
            (select ifnull(sum(v.total),0) from venta v inner join pedido p
			on v.idpedido=p.idpedido 
			where date(v.fecha)=curdate() and v.tipo_venta='Contado' and v.estado='A'
			and p.idsucursal='$idsucursal' and p.idusuario='56') as ventas3,
            (select ifnull(sum(v.total),0) from venta v inner join pedido p
			on v.idpedido=p.idpedido 
			where date(v.fecha)=curdate() and v.tipo_venta='Contado' and v.estado='A'
			and p.idsucursal='$idsucursal' and p.idusuario='34') as ventas4,
            (select ifnull(sum(v.total),0) from venta v inner join pedido p
			on v.idpedido=p.idpedido
			where date(v.fecha)=curdate() and v.tipo_venta='Contado' and v.estado='A'
			and p.idsucursal='$idsucursal' and p.idusuario='38') as ventas5,
            (select ifnull(sum(v.total),0) from venta v inner join pedido p
			on v.idpedido=p.idpedido
			where date(v.fecha)=curdate() and v.tipo_venta='Contado' and v.estado='A'
			and p.idsucursal='$idsucursal' and p.idusuario='39') as ventas6,
            (select ifnull(sum(v.total),0) from venta v inner join pedido p
			on v.idpedido=p.idpedido 
			where date(v.fecha)=curdate() and v.tipo_venta='Contado' and v.estado='A'
			and p.idsucursal='$idsucursal' and p.idusuario='37') as ventas7 ,
            (select ifnull(sum(v.total),0) from venta v inner join pedido p
			on v.idpedido=p.idpedido 
			where date(v.fecha)=curdate() and v.tipo_venta='Contado' and v.estado='A'
			and p.idsucursal='$idsucursal' and p.idusuario='22') as ventas8";
		$query = $conexion->query($sql);
		return $query;
	}

	public function CantidadVentas($idsucursal)
	{
		global $conexion;
		$sql = "SELECT(select count(p.idpedido) from pedido p inner join venta v on v.idpedido=p.idpedido where date(v.fecha)=curdate() and v.estado='A'
			and p.idsucursal='$idsucursal' and p.idusuario='40') as cantidad1,
			(select count(p.idpedido) from pedido p inner join venta v on v.idpedido=p.idpedido where date(v.fecha)=curdate() and v.estado='A'
			and p.idsucursal='$idsucursal' and p.idusuario='36') as cantidad2,
			(select count(p.idpedido) from pedido p inner join venta v on v.idpedido=p.idpedido where date(v.fecha)=curdate() and v.estado='A'
			and p.idsucursal='$idsucursal' and p.idusuario='56') as cantidad3,
			(select count(p.idpedido) from pedido p inner join venta v on v.idpedido=p.idpedido where date(v.fecha)=curdate() and v.estado='A'
			and p.idsucursal='$idsucursal' and p.idusuario='34') as cantidad4,
			(select count(p.idpedido) from pedido p inner join venta v on v.idpedido=p.idpedido where date(v.fecha)=curdate() and v.estado='A'
			and p.idsucursal='$idsucursal' and p.idusuario='38') as cantidad5,
			(select count(p.idpedido) from pedido p inner join venta v on v.idpedido=p.idpedido where date(v.fecha)=curdate() and v.estado='A'
			and p.idsucursal='$idsucursal' and p.idusuario='39') as cantidad6,
			(select count(p.idpedido) from pedido p inner join venta v on v.idpedido=p.idpedido where date(v.fecha)=curdate() and v.estado='A'
			and p.idsucursal='$idsucursal' and p.idusuario='37') as cantidad7,
			(select count(p.idpedido) from pedido p inner join venta v on v.idpedido=p.idpedido where date(v.fecha)=curdate() and v.estado='A'
			and p.idsucursal='$idsucursal' and p.idusuario='22') as cantidad8";
		$query = $conexion->query($sql);
		return $query;
	}

	public function ComprasMes()
	{

		global $conexion;
		$sql = "SELECT
			monthname(i.Fecha) as mes, sum(i.total) as totalmes
			from ingreso i
			where i.estado='A'
			and date(i.fecha)>='2021-06-01' and date(i.fecha)<='2022-07-01'
			group by monthname(i.Fecha) order by month(i.Fecha) asc
			limit 12 ";
		$query = $conexion->query($sql);
		return $query;
	}

	public function VentasMes()
	{

		global $conexion;
		$sql = "SELECT
			monthname(v.Fecha) as mes, sum(v.total) as totalmes
			from venta v
			inner join pedido p on v.idpedido=p.idpedido
			where v.estado='A'
			and date(v.fecha)>='2021-06-01' and date(v.fecha)<='2022-07-01'
			group by monthname(v.Fecha) order by month(v.Fecha) asc
			limit 12";
		$query = $conexion->query($sql);
		return $query;
	}

	public function VentasDias()
	{

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

	public function ProductosVendidosAnoTotal()
	{

		global $conexion;
		$sql = "SELECT a.nombre as articulo,sum(dp.cantidad) as cantidad
			from articulo a inner join detalle_ingreso di
			on a.idarticulo=di.idarticulo
			inner join detalle_pedido dp on dp.iddetalle_ingreso=di.iddetalle_ingreso
			inner join pedido p on p.idpedido=dp.idpedido
			inner join venta v on p.idpedido=v.idpedido
			where v.estado='A' and year(v.fecha)=year(curdate())
			and date(v.fecha)>='2021-06-01' and date(v.fecha)<='2022-07-01'
			group by a.nombre
			order by sum(dp.cantidad) desc
			limit 10";
		$query = $conexion->query($sql);
		return $query;
	}

	public function CantPedidosVendidos()
	{

		global $conexion;
		$sql = "SELECT p.idpedido, p.tipo_pedido, v.fecha,s.razon_social as sucursal,
			concat(e.apellidos,' ',e.nombre) as empleado,
			concat(pe.nombre,' ',pe.apellido) as cliente,
			pe.num_documento as dni,pe.telefono as celular, pe.direccion_departamento as departamento,
			concat(v.serie_comprobante,'-',v.num_comprobante) as ticket,
			from venta v inner join pedido p on v.idpedido=p.idpedido
			inner join sucursal s on p.idsucursal=s.idsucursal
			inner join usuario u on p.idusuario=u.idusuario
			inner join empleado e on u.idempleado=e.idempleado
			inner join persona pe on p.idcliente=pe.idpersona
			where MONTH(v.fecha)=MONTH(now())
			order by v.fecha desc";
		$query = $conexion->query($sql);
		return $query;
	}

	public function TotalesTotal()
	{
		global $conexion;
		$sql = "SELECT (select simbolo_moneda from global order by idglobal desc limit 1 ) as moneda,
			(select ifnull(sum(total),0) from ingreso
			where date(fecha)=curdate() and estado='A') as totalingreso,
			(select ifnull(sum(total),0) from ingreso
			where MONTH(fecha)=MONTH(now()) and  year(fecha)=year(now()) and estado='A') as totalingresomes,
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
