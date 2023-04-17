<?php

require "Conexion.php";
class Grafico
	
{

	public function __construct()
	{
	}

	public function VentasDelMesPorUsuario() { 
		global $conexion;

		$sql="SELECT idempleado ,concat(nombre,' ',apellidos) nombre FROM empleado WHERE idrol=1;";
		$query = $conexion->query($sql);

		$reg = $query->fetch_all();
	
		$super=array();
		foreach ($reg as $idempleado) {
			$sql="SELECT 
			concat(DATE_FORMAT(DATE_RANGE, '%d'),' ',MONTHNAME(DATE_RANGE))
			 AS fecha, COALESCE(ventas_totales, 0) AS ventas_totales
			FROM (
			   SELECT IFNULL(SUM(venta.total),0) ventas_totales, DATE(venta.fecha) fecha
			   FROM venta 
			   JOIN pedido ON venta.idpedido = pedido.idpedido 
			   JOIN usuario ON usuario.idusuario = pedido.idusuario
			   JOIN empleado ON empleado.idempleado = usuario.idempleado
			   WHERE empleado.idempleado = $idempleado[0] and venta.estado = 'A'
			   GROUP BY DATE(venta.fecha)
			) AS fechas_venta 
			RIGHT JOIN (
			   SELECT DATE(CONCAT(YEAR(CURRENT_DATE),'-',MONTH(CURRENT_DATE),'-','01')) AS date_range 
			   UNION 
			   SELECT LAST_DAY(CONCAT(YEAR(CURRENT_DATE),'-',MONTH(CURRENT_DATE),'-','01')) AS date_range
			   UNION 
			   SELECT DATE_ADD(CONCAT(YEAR(CURRENT_DATE),'-',MONTH(CURRENT_DATE),'-','01'), INTERVAL n.n DAY) AS date_range
			   FROM (
				  SELECT a.N + b.N * 10 + 1 n
				  FROM (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) a
				  CROSS JOIN (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) b
			   ) n
			   WHERE DATE_ADD(CONCAT(YEAR(CURRENT_DATE),'-',MONTH(CURRENT_DATE),'-','01'), INTERVAL n.n DAY) <= LAST_DAY(CONCAT(YEAR(CURRENT_DATE),'-',MONTH(CURRENT_DATE),'-','01'))
			) AS temp_dates ON fechas_venta.fecha = temp_dates.date_range
			ORDER BY fecha ASC;
			";
		
			$query_venta_por_empleado = $conexion->query($sql);
			
			$nuevo=[];
			while($reg = $query_venta_por_empleado->fetch_object()){		
				$nuevo['data'][] = $reg;
				$nuevo['nombre']=$idempleado[1];
			}
			$super[] = $nuevo;
		}
		return $super; 

	}

	public function TraerVentasSemanalesUltimosAños(){
		global $conexion;
		$sql="SELECT DISTINCT YEAR(fecha) year  FROM venta;";

		$query = $conexion->query($sql);


		$reg = $query->fetch_all();

		$super=array();
		foreach($reg as $row){
			$sql="SELECT DAYNAME(dias.fecha) AS nombre_dia, dias.fecha, IFNULL(SUM(venta.total), 0) AS total_venta
			FROM (
			  SELECT '$row[0]-01-01' + INTERVAL ROW_NUMBER() OVER (ORDER BY (SELECT NULL)) - 1 DAY AS fecha
			  FROM information_schema.columns
			  LIMIT 366 -- número máximo de días en un año
			) AS dias
			LEFT JOIN venta ON date(venta.fecha) = dias.fecha AND venta.estado = 'A' AND YEAR(venta.fecha) = $row[0] 
			  AND (WEEK(venta.fecha, 1) = week(current_date) OR venta.fecha IS NULL)
			
			GROUP BY nombre_dia
			ORDER BY FIELD(nombre_dia, 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
			";

			
			$queryMeses = $conexion->query($sql);

			$nuevo = array();
			
			while($reg = $queryMeses->fetch_object()){
				$nuevo['año']=$row[0];
				$nuevo['data'][] = $reg;

			}
			$super[] = $nuevo;
			// echo  json_encode($nuevo);

		}
		return $super; 

	}
	public function TraerVentasUltimosAños()
	{
		global $conexion;

		$sql="SELECT DISTINCT YEAR(fecha) year  FROM venta;";

		$query = $conexion->query($sql);


		$reg = $query->fetch_all();
		

		$super=array();
		foreach($reg as $row){
			$sql="SELECT meses.mes, $row[0] AS año, IFNULL(SUM(venta.total), 0) AS total_venta
			FROM (
				SELECT 1 AS mes UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6
				UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12
			) AS meses
			LEFT JOIN venta ON MONTH(venta.fecha) = meses.mes AND venta.estado = 'A' AND YEAR(venta.fecha) = $row[0]
			GROUP BY meses.mes
			ORDER BY meses.mes
			";

			
			$queryMeses = $conexion->query($sql);

			$nuevo = array();
			
			while($reg = $queryMeses->fetch_object()){
				
				$nuevo[] = $reg;

			}
			$super[] = $nuevo;
			// echo  json_encode($nuevo);

		}

		return $super; 
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
		(SELECT IFNULL(SUM(venta.total),0)  FROM venta join pedido on venta.idpedido=pedido.idpedido  WHERE venta.estado='A' AND date(venta.fecha)= CURRENT_DATE AND year(venta.fecha)=year(CURRENT_DATE) )   ventas_diarias,
		
		(SELECT IFNULL(SUM(venta.total),0)  FROM venta join pedido on venta.idpedido=pedido.idpedido  WHERE venta.estado='A' AND WEEK(venta.fecha)=WEEK(CURRENT_DATE) AND year(venta.fecha)=year(CURRENT_DATE) ) ventas_semanales,
		
		(SELECT IFNULL(SUM(venta.total),0)  FROM venta join pedido on venta.idpedido=pedido.idpedido  WHERE venta.estado='A' AND MONTH(venta.fecha)= MONTH(CURRENT_DATE) AND year(venta.fecha)=year(CURRENT_DATE) ) ventas_mensuales,
		
		(SELECT IFNULL(SUM(venta.total),0)  FROM venta join pedido on venta.idpedido=pedido.idpedido  WHERE venta.estado='C' AND MONTH(venta.fecha)= MONTH(CURRENT_DATE) AND year(venta.fecha)=year(CURRENT_DATE) ) ventas_anuladas
			";
		if ($idsucursal != 0) {
		$sql = "SELECT 
		(SELECT IFNULL(SUM(venta.total),0)  FROM venta join pedido on venta.idpedido=pedido.idpedido  WHERE venta.estado='A' AND date(venta.fecha)= CURRENT_DATE
		AND year(venta.fecha)=year(CURRENT_DATE) 
		  and pedido.idsucursal='$idsucursal' )   ventas_diarias,
		
		(SELECT IFNULL(SUM(venta.total),0)  FROM venta join pedido on venta.idpedido=pedido.idpedido  WHERE venta.estado='A' AND WEEK(venta.fecha)=WEEK(CURRENT_DATE) AND year(venta.fecha)=year(CURRENT_DATE)  and pedido.idsucursal='$idsucursal' ) ventas_semanales,
		
		(SELECT IFNULL(SUM(venta.total),0) FROM venta join pedido on venta.idpedido=pedido.idpedido  WHERE venta.estado='A' AND MONTH(venta.fecha)= MONTH(CURRENT_DATE) AND year(venta.fecha)=year(CURRENT_DATE)  and pedido.idsucursal='$idsucursal') ventas_mensuales,
		
		(SELECT SUM(venta.total) FROM venta join pedido on venta.idpedido=pedido.idpedido  WHERE venta.estado='C' AND MONTH(venta.fecha)= MONTH(CURRENT_DATE)  
		AND year(venta.fecha)=year(CURRENT_DATE) and pedido.idsucursal='$idsucursal') ventas_anuladas
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
			AND year(venta.fecha)=year(CURRENT_DATE) 

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
			AND year(venta.fecha)=year(CURRENT_DATE) 

			AND empleado.idempleado=$idempleado and pedido.idsucursal='$idsucursal'
			) ventas_semanales,
			(
			SELECT IFNULL(SUM(venta.total),0) 
			FROM venta 
			join pedido on venta.idpedido=pedido.idpedido 
			JOIN usuario ON usuario.idusuario=pedido.idusuario
			join empleado ON empleado.idempleado=usuario.idempleado
			WHERE venta.estado='A' AND MONTH(venta.fecha)= MONTH(CURRENT_DATE)
			AND year(venta.fecha)=year(CURRENT_DATE) 

			AND empleado.idempleado=$idempleado and pedido.idsucursal='$idsucursal'
			) ventas_mensuales,
			(
			SELECT IFNULL(SUM(venta.total),0) 
			FROM venta 
			join pedido on venta.idpedido=pedido.idpedido 
			JOIN usuario ON usuario.idusuario=pedido.idusuario
			join empleado ON empleado.idempleado=usuario.idempleado 
			WHERE venta.estado='C' AND MONTH(venta.fecha)= MONTH(CURRENT_DATE) 
			AND year(venta.fecha)=year(CURRENT_DATE) 

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
			AND year(venta.fecha)=year(CURRENT_DATE) 

			AND empleado.idempleado=$idempleado)   ventas_diarias,
			
			(SELECT IFNULL(SUM(venta.total),0) 
			FROM venta 
			join pedido on venta.idpedido=pedido.idpedido 
			JOIN usuario ON usuario.idusuario=pedido.idusuario
			join empleado ON empleado.idempleado=usuario.idempleado
			WHERE venta.estado='A' AND WEEK(venta.fecha)=WEEK(CURRENT_DATE)
			AND year(venta.fecha)=year(CURRENT_DATE) 

			AND empleado.idempleado=$idempleado) ventas_semanales,
			
			(SELECT IFNULL(SUM(venta.total),0) 
			FROM venta 
			join pedido on venta.idpedido=pedido.idpedido 
			JOIN usuario ON usuario.idusuario=pedido.idusuario
			join empleado ON empleado.idempleado=usuario.idempleado
			WHERE venta.estado='A' AND MONTH(venta.fecha)= MONTH(CURRENT_DATE)
			AND year(venta.fecha)=year(CURRENT_DATE) 

			AND empleado.idempleado=$idempleado) ventas_mensuales,
			
			(SELECT IFNULL(SUM(venta.total),0) 
			FROM venta 
			join pedido on venta.idpedido=pedido.idpedido 
			JOIN usuario ON usuario.idusuario=pedido.idusuario
			join empleado ON empleado.idempleado=usuario.idempleado 
			WHERE venta.estado='C' AND MONTH(venta.fecha)= MONTH(CURRENT_DATE) 
			AND year(venta.fecha)=year(CURRENT_DATE) 

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
			
			AND year(venta.fecha)=year(CURRENT_DATE) 

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
			AND year(venta.fecha)=year(CURRENT_DATE) 

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
			AND year(venta.fecha)=year(CURRENT_DATE) 

			AND empleado.idempleado=$idempleado
			) ventas_semanales,
			(
			SELECT IFNULL(count(venta.total),0) 
			FROM venta 
			join pedido on venta.idpedido=pedido.idpedido 
			JOIN usuario ON usuario.idusuario=pedido.idusuario
			join empleado ON empleado.idempleado=usuario.idempleado
			WHERE venta.estado='A' AND WEEK(venta.fecha)=WEEK(CURRENT_DATE) 
			AND year(venta.fecha)=year(CURRENT_DATE) 

			AND empleado.idempleado=$idempleado
			) ventas_semanales_cantidad,

			(
			SELECT IFNULL(SUM(venta.total),0) 
			FROM venta 
			join pedido on venta.idpedido=pedido.idpedido 
			JOIN usuario ON usuario.idusuario=pedido.idusuario
			join empleado ON empleado.idempleado=usuario.idempleado
			WHERE venta.estado='A' AND MONTH(venta.fecha)= MONTH(CURRENT_DATE)
			AND year(venta.fecha)=year(CURRENT_DATE) 

			AND empleado.idempleado=$idempleado
			) ventas_mensuales,
			(
			SELECT IFNULL(count(venta.total),0) 
			FROM venta 
			join pedido on venta.idpedido=pedido.idpedido 
			JOIN usuario ON usuario.idusuario=pedido.idusuario
			join empleado ON empleado.idempleado=usuario.idempleado
			WHERE venta.estado='A' AND MONTH(venta.fecha)= MONTH(CURRENT_DATE)
			AND year(venta.fecha)=year(CURRENT_DATE) 

			AND empleado.idempleado=$idempleado
			) ventas_mensuales_cantidad,

			(
			SELECT IFNULL(SUM(venta.total),0) 
			FROM venta 
			join pedido on venta.idpedido=pedido.idpedido 
			JOIN usuario ON usuario.idusuario=pedido.idusuario
			join empleado ON empleado.idempleado=usuario.idempleado 
			WHERE venta.estado='C' AND MONTH(venta.fecha)= MONTH(CURRENT_DATE) 
			AND year(venta.fecha)=year(CURRENT_DATE) 

			AND empleado.idempleado=$idempleado
			) ventas_anuladas,
			(
			SELECT IFNULL(count(venta.total),0) 
			FROM venta 
			join pedido on venta.idpedido=pedido.idpedido 
			JOIN usuario ON usuario.idusuario=pedido.idusuario
			join empleado ON empleado.idempleado=usuario.idempleado 
			WHERE venta.estado='C' AND MONTH(venta.fecha)= MONTH(CURRENT_DATE) 
			AND year(venta.fecha)=year(CURRENT_DATE) 
			
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
		and month(v.fecha)>=MONTH(current_date)
		group by a.nombre
		order by sum(dp.cantidad) desc
		limit 10";
		$query = $conexion->query($sql);
		return $query;
	}
	public function ProductosVendidosAnoTotalPorDinero()
	{

		global $conexion;
		$sql = "	SELECT a.nombre as articulo,SUM(v.total) as cantidad
		from articulo a inner join detalle_ingreso di
		on a.idarticulo=di.idarticulo
		inner join detalle_pedido dp on dp.iddetalle_ingreso=di.iddetalle_ingreso
		inner join pedido p on p.idpedido=dp.idpedido
		inner join venta v on p.idpedido=v.idpedido
		where v.estado='A' and year(v.fecha)=year(curdate())
		and month(v.fecha)>=MONTH(current_date)
		group by a.nombre
		order by SUM(v.total) desc
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
