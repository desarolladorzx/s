<?php
	require "Conexion.php";

	class Venta{
	
		public function __construct(){
		}

		/* public function Registrar($idpedido,$idusuario,$tipo_venta,$tipo_comprobante,$serie_comprobante,$num_comprobante,$impuesto,$total,$estado, $numero, $iddetalle_documento_sucursal, $detalle){
			global $conexion;
			$sw = true;
			try {
				
				$sql = "INSERT INTO venta(idpedido,idusuario,tipo_venta,tipo_comprobante,serie_comprobante,num_comprobante, fecha ,impuesto,total,estado)
						VALUES('$idpedido','$idusuario','$tipo_venta','$tipo_comprobante','$serie_comprobante','$num_comprobante', curdate(),'$impuesto','$total','$estado')";
				//var_dump($sql);
				$conexion->query($sql);	 */

		public function Registrar($idpedido,$idusuario,$tipo_venta,$tipo_comprobante,$serie_comprobante,$num_comprobante,$impuesto,$total,$estado, $numero, $iddetalle_documento_sucursal, $detalle,$tipo_promocion,$metodo_pago,$agencia_envio){
			global $conexion;
			$sw = true;
			try {

				$sql = "INSERT INTO venta(idpedido,idusuario,tipo_venta,tipo_comprobante,serie_comprobante,num_comprobante,fecha ,impuesto,total,estado,tipo_promocion,metodo_pago,agencia_envio)
						VALUES('$idpedido','$idusuario','$tipo_venta','$tipo_comprobante','$serie_comprobante','$num_comprobante', CURRENT_TIMESTAMP(),'$impuesto','$total','$estado','$tipo_promocion','$metodo_pago','$agencia_envio')";
				
				$conexion->query($sql);

				//var_dump($detalle);
				//exit;

				$sql_detalle_doc = "UPDATE detalle_documento_sucursal set ultimo_numero = '$numero' where iddetalle_documento_sucursal = $iddetalle_documento_sucursal";
				//var_dump($sql);
				$conexion->query($sql_detalle_doc);

				$sql_ped = "UPDATE pedido set tipo_pedido = 'Venta' where idpedido = $idpedido";
				//var_dump($sql);
				$conexion->query($sql_ped);

				$conexion->autocommit(true);
				foreach($detalle as $indice => $valor){
					$stockNuevo = $valor[1] - $valor[2];
					$sql_detalle = "UPDATE detalle_ingreso set stock_actual = ".$stockNuevo." where iddetalle_ingreso = ".$valor[0]."";
					$conexion->query($sql_detalle) or $sw = false;

					//var_dump($sql_detalle);
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
		public function Modificar($idventa,$idpedido, $idusuario,$tipo_venta,$tipo_comprobante,$serie_comprobante,$num_comprobante,$impuesto,$total,$estado){
			global $conexion;
			$sql = "UPDATE venta set idpedido = '$idpedido',idusuario='$idusuario',tipo_venta='$tipo_venta',serie_comprobante='$serie_comprobante',num_comprobante='$num_comprobante', fecha = CURRENT_TIMESTAMP(), impuesto='$impuesto',total='$total',estado='$estado'
						WHERE idventa = $idventa";
			$query = $conexion->query($sql);
			return $query;
		}
		public function Eliminar($idventa){
			global $conexion;
			$sql = "DELETE FROM venta WHERE idventa = $idventa";
			$query = $conexion->query($sql);
			return $query;
		}
		public function Listar(){
			global $conexion;
			$sql = "SELECT * FROM venta order by idventa desc";
			$query = $conexion->query($sql);
			return $query;
		}
		public function GetTipoDocSerieNum($nombre,$idsucursal){
			global $conexion;
			$sql = "select dds.iddetalle_documento_sucursal, dds.ultima_serie, dds.ultimo_numero
			from detalle_documento_sucursal dds inner join tipo_documento td on dds.idtipo_documento = td.idtipo_documento
			where td.operacion = 'Comprobante' and nombre = '$nombre' and dds.idsucursal='$idsucursal'";
			$query = $conexion->query($sql);
			return $query;
		}
		public function CambiarTipoPedido($idpedido){
			global $conexion;
			$sql = "UPDATE pedido set tipo_pedido = 'Venta' where idpedido = 21";
			$query = $conexion->query($sql);
			return $query;
		}
	}