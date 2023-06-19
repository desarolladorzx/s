<?php
require "Conexion.php";

class Venta
{

	public function TraerDataPedido($idpedido){

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

            where p.idpedido=$idpedido
			and c.tipo_persona = 'Final' & 'Distribuidor' & 'Superdistribuidor' & 'Representante' and p.tipo_pedido = 'Venta'
			
			

			 order by idpedido desc
			
			
			-- limit 0,300
			";
		$query = $conexion->query($sql);
		return $query;

		
	}

	public function VerificarStockMinimo($idpedido)
	{
		global $conexion;
		$sql =
			"SELECT stock_min , detalle_ingreso.* ,sum(detalle_ingreso.stock_actual) stock_actual_total,articulo.* ,categoria.nombre marca_nombre  FROM detalle_pedido 
		JOIN detalle_ingreso ON detalle_ingreso.iddetalle_ingreso=detalle_pedido.iddetalle_ingreso
		JOIN articulo ON articulo.idarticulo=detalle_ingreso.idarticulo
		JOIN categoria ON categoria.idcategoria=articulo.idcategoria
		WHERE idpedido =$idpedido 
		GROUP BY detalle_ingreso.idarticulo
		;
		";
		$sql = "SELECT stock_min,detalle_ingreso.*,  a.*, c.nombre AS categoria, um.nombre AS unidadMedida, m.nombre AS marca,
			c.nombre marca_nombre,
			SUM(detalle_ingreso.stock_actual) AS stock_actual_total
	
		FROM articulo a
		left JOIN categoria c ON a.idcategoria = c.idcategoria
		left JOIN detalle_ingreso ON detalle_ingreso.idarticulo = a.idarticulo  AND detalle_ingreso.estado_detalle_ingreso='INGRESO'
		left JOIN marca m ON a.idmarca = m.idmarca
		
		LEFT JOIN detalle_pedido ON detalle_pedido.iddetalle_ingreso=detalle_ingreso.iddetalle_ingreso
		left JOIN unidad_medida um ON a.idunidad_medida = um.idunidad_medida
		left JOIN ingreso ON detalle_ingreso.idingreso = ingreso.idingreso AND ingreso.estado='A'
		WHERE a.estado = 'A'  AND detalle_pedido.idpedido=$idpedido 
		GROUP BY a.idarticulo
		ORDER BY a.idarticulo DESC;
	";
		$query = $conexion->query($sql);
		return $query;
	}
	public function __construct()
	{
	}

	/* public function Registrar($idpedido,$idusuario,$tipo_venta,$tipo_comprobante,$serie_comprobante,$num_comprobante,$impuesto,$total,$estado, $numero, $iddetalle_documento_sucursal, $detalle){
		global $conexion;
		$sw = true;
		try {
			
			$sql = "INSERT INTO venta(idpedido,idusuario,tipo_venta,tipo_comprobante,serie_comprobante,num_comprobante, fecha ,impuesto,total,estado)
			VALUES('$idpedido','$idusuario','$tipo_venta','$tipo_comprobante','$serie_comprobante','$num_comprobante', curdate(),'$impuesto','$total','$estado')";
			//var_dump($sql);
			$conexion->query($sql);	 */

	public function SaveImprimir($idventa)
	{
		global $conexion;
		$sql = "INSERT INTO impresion(idventa, idusuario,fecha_registro) VALUES ($idventa," . $_SESSION["idusuario"] . ",CURRENT_TIMESTAMP())";

		$query = $conexion->query($sql);
		return $query;
	}
	public function Registrar(
		$idpedido,
		$idusuario,
		$tipo_venta,
		$tipo_comprobante,
		$serie_comprobante,
		$num_comprobante,
		$impuesto,
		$total,
		$estado,
		$numero,
		$iddetalle_documento_sucursal,
		$detalle,
		$tipo_promocion,
		$metodo_pago,
		$agencia_envio,
		$arrayMetodosPago
	) {
		global $conexion;
		$sw = true;
		try {



			$sql = "INSERT INTO venta(idpedido,idusuario,tipo_venta,tipo_comprobante,serie_comprobante,num_comprobante,fecha ,impuesto,total,estado)
						VALUES('$idpedido','$idusuario','$tipo_venta','$tipo_comprobante','$serie_comprobante','$num_comprobante', CURRENT_TIMESTAMP(),'$impuesto','$total','$estado')";
			// echo $sql;
			$conexion->query($sql);


			$idVenta = $conexion->insert_id;


			foreach ($arrayMetodosPago as $subarray) {
				$subarray[1];

				$sql = "INSERT  INTO venta_pago(idventa,fecha_pago,idtipo_metodo_pago,idbanco_cuenta,referencia,pago)
				VALUES(
					$idVenta,
				'$subarray[0]',
				'$subarray[1]'
				,'$subarray[2]',
				'$subarray[3]',
				$subarray[4])";

				// echo $sql;
				$conexion->query($sql);
			}



			$sql_detalle_doc = "UPDATE detalle_documento_sucursal set ultimo_numero = '$numero' where iddetalle_documento_sucursal = $iddetalle_documento_sucursal";
			//var_dump($sql);
			$conexion->query($sql_detalle_doc);

			$sql_ped = "UPDATE pedido set tipo_pedido = 'Venta', estado = 'A' where idpedido = $idpedido";
			//var_dump($sql);
			$conexion->query($sql_ped);

			$conexion->autocommit(true);
			// print_r($detalle);
			foreach ($detalle as $indice => $valor) {

				//1 VERSION
				/* $sql_detalle = "UPDATE detalle_ingreso set stock_actual = ".$valor[1]." - ".$valor[2]." where iddetalle_ingreso = ".$valor[0].""; */

				//2DA VERSION 


				$sql_detalle_ingreso = "SELECT idarticulo from detalle_ingreso WHERE iddetalle_ingreso=" . $valor[0] . " ";

				$idarticulo = $conexion->query($sql_detalle_ingreso)->fetch_object()->idarticulo;

				$suma_anterior = "SELECT sum(stock_actual) as stock
				from detalle_ingreso  
				inner join ingreso on ingreso.idingreso=detalle_ingreso.idingreso
				 where idarticulo=$idarticulo 
				 and ingreso.estado='A'
                    and detalle_ingreso.estado_detalle_ingreso='INGRESO'
					
				  and ingreso.idsucursal=" . $_SESSION["idsucursal"] . "";

				$rpta_sql_suma_anterior = $conexion->query($suma_anterior)->fetch_object();


				$stock_anterior = $rpta_sql_suma_anterior->stock;

				$stockNuevo = $valor[1] - $valor[2];
				$sql_detalle = "UPDATE detalle_ingreso set stock_actual = " . $stockNuevo . " where iddetalle_ingreso = " . $valor[0] . "";

				//ACTUALMENTE
				//$sql_detalle = "UPDATE detalle_ingreso set stock_actual = stock_actual - ".$valor[2]." where iddetalle_ingreso = ".$valor[0]."";

				$conexion->query($sql_detalle) or $sw = false;




				global $conexion;
				$sql = "SELECT * from detalle_ingreso where iddetalle_ingreso =" . $valor[0] . " ";
				$query = $conexion->query($sql)->fetch_object()->idarticulo;


				// $sql = "SELECT iddetalle_pedido from detalle_pedido where idpedido =" . $idpedido." and iddetalle_ingreso=$valor[0] limit 1";

				// print_r($valor);
				// $detalle_pedido = $conexion->query($sql)->fetch_object()->iddetalle_pedido;


				$suma_ingreso = "SELECT sum(stock_actual) as stock
				from detalle_ingreso  
				inner join ingreso on ingreso.idingreso=detalle_ingreso.idingreso
				 where idarticulo=$idarticulo 
				 and ingreso.estado='A'
                    and detalle_ingreso.estado_detalle_ingreso='INGRESO'

				  and ingreso.idsucursal=" . $_SESSION["idsucursal"] . "";

				$rpta_sql_suma_ingreso = $conexion->query($suma_ingreso)->fetch_object();
				$stock_actual = $rpta_sql_suma_ingreso->stock;

				$detale_ingreso = 0;


				$stock_anterior_not_null = ($stock_anterior !== null) ? $stock_anterior : 0;
				$stock_actual_not_null = ($stock_actual !== null) ? $stock_actual : 0;


				$sqlKardex = "INSERT INTO 
					kardex(
					id_sucursal,
					fecha_emision,
					tipo,
					id_articulo,
					id_detalle_ingreso,
					id_detalle_pedido,
					cantidad,
					fecha_creacion,
					fecha_modificacion,
					stock_actual,
					stock_anterior 
				)
					VALUES(
						'" . $_SESSION['idsucursal'] . "',
						CURRENT_TIMESTAMP(),
						'venta',
						'" . $query . "',
						'" . $detale_ingreso . "',
						'" . $valor[3] . "', 
						'" . $valor[2] . "',
						CURRENT_TIMESTAMP(),
						CURRENT_TIMESTAMP(),
						'" . $stock_actual_not_null . "',
						'" . $stock_anterior_not_null . "'
					)";
				$conexion->query($sqlKardex) or $sw = false;
			}

			//exit;


			if ($conexion != null) {
				$conexion->close();
			}
		} catch (Exception $e) {
			$conexion->rollback();
		}
		return $sw;
	}
	public function Modificar($idventa, $idpedido, $idusuario, $tipo_venta, $tipo_comprobante, $serie_comprobante, $num_comprobante, $impuesto, $total, $estado)
	{
		global $conexion;
		$sql = "UPDATE venta set idpedido = '$idpedido',idusuario='$idusuario',tipo_venta='$tipo_venta',serie_comprobante='$serie_comprobante',num_comprobante='$num_comprobante', fecha = CURRENT_TIMESTAMP(), impuesto='$impuesto',total='$total',estado='$estado'
						WHERE idventa = $idventa";
		$query = $conexion->query($sql);
		return $query;
	}
	public function Eliminar($idventa)
	{
		global $conexion;
		$sql = "DELETE FROM venta WHERE idventa = $idventa";
		$query = $conexion->query($sql);
		return $query;
	}
	public function Listar()
	{
		global $conexion;
		$sql = "SELECT * FROM venta order by idventa desc";
		$query = $conexion->query($sql);
		return $query;
	}
	public function GetTipoDocSerieNum($nombre, $idsucursal)
	{
		global $conexion;
		$sql = "select dds.iddetalle_documento_sucursal, dds.ultima_serie, dds.ultimo_numero
			from detalle_documento_sucursal dds inner join tipo_documento td on dds.idtipo_documento = td.idtipo_documento
			where td.operacion = 'Comprobante' and nombre = '$nombre' and dds.idsucursal='$idsucursal'";
		$query = $conexion->query($sql);
		return $query;
	}
	public function CambiarTipoPedido($idpedido)
	{
		global $conexion;
		$sql = "UPDATE pedido set tipo_pedido = 'Venta' where idpedido = 21";
		$query = $conexion->query($sql);
		return $query;
	}

	public function buscarDetalleIngreso($iddetalle_ingreso)
	{
		global $conexion;
		$sql = "SELECT * FROM detalle_ingreso WHERE iddetalle_ingreso = " . $iddetalle_ingreso;
		$query = $conexion->query($sql);
		return $query;
	}
}
