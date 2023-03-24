<?php
require "Conexion.php";

class Venta
{

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

	public function Registrar($idpedido, $idusuario, $tipo_venta, $tipo_comprobante, $serie_comprobante, $num_comprobante, $impuesto, $total, $estado, $numero, $iddetalle_documento_sucursal, $detalle, $tipo_promocion, $metodo_pago, $agencia_envio)
	{
		global $conexion;
		$sw = true;
		try {



			$sql = "INSERT INTO venta(idpedido,idusuario,tipo_venta,tipo_comprobante,serie_comprobante,num_comprobante,fecha ,impuesto,total,estado)
						VALUES('$idpedido','$idusuario','$tipo_venta','$tipo_comprobante','$serie_comprobante','$num_comprobante', CURRENT_TIMESTAMP(),'$impuesto','$total','$estado')";
			// echo $sql;
			$conexion->query($sql);



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


				$sql_detalle_ingreso ="SELECT idarticulo from detalle_ingreso WHERE iddetalle_ingreso=" . $valor[0] . " ";

				$idarticulo = $conexion->query($sql_detalle_ingreso)->fetch_object()->idarticulo;

				$suma_anterior = "SELECT sum(stock_actual) as stock
				from detalle_ingreso  
				inner join ingreso on ingreso.idingreso=detalle_ingreso.idingreso
				 where idarticulo=$idarticulo 
				 and ingreso.estado='A'
                    and detalle_ingreso.estado_detalle_ingreso='INGRESO'
					
				  and ingreso.idsucursal=".$_SESSION["idsucursal"]."";

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


				$suma_ingreso="SELECT sum(stock_actual) as stock
				from detalle_ingreso  
				inner join ingreso on ingreso.idingreso=detalle_ingreso.idingreso
				 where idarticulo=$idarticulo 
				 and ingreso.estado='A'
                    and detalle_ingreso.estado_detalle_ingreso='INGRESO'

				  and ingreso.idsucursal=".$_SESSION["idsucursal"]."";

				$rpta_sql_suma_ingreso = $conexion->query($suma_ingreso)->fetch_object();
				$stock_actual = $rpta_sql_suma_ingreso->stock;

				$detale_ingreso = 0;


				$stock_anterior_not_null=($stock_anterior !== null) ? $stock_anterior: 0 ; 
				$stock_actual_not_null=($stock_actual !== null) ? $stock_actual: 0 ;


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
						'" . $query. "',
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
