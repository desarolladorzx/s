<?php
require "Conexion.php";

class Ingreso
{

	public function Registrar($idusuario, $idsucursal, $idproveedor, $tipo_comprobante, $serie_comprobante, $num_comprobante, $impuesto, $total, $detalle)
	{
		global $conexion;
		$sw = true;
		try {


			$sql = "INSERT INTO ingreso(idusuario, idsucursal, idproveedor, tipo_comprobante, serie_comprobante, num_comprobante, fecha, impuesto,
                        total, estado) 
                        VALUES($idusuario, $idsucursal, $idproveedor, '$tipo_comprobante', '$serie_comprobante', '$num_comprobante', CURRENT_TIMESTAMP(), $impuesto, $total, 'A')";
			//var_dump($sql);
			$conexion->query($sql);
			$idingreso = $conexion->insert_id;
			$conexion->autocommit(true);




			foreach ($detalle as $indice => $valor) {

				// detalle ingreso anterior
				// $suma_anterior = "SELECT SUM(stock_actual) stock from detalle_ingreso where idarticulo=" . $valor[0] . "";
				// $rpta_sql_suma_anterior = $conexion->query($suma_anterior)->fetch_object();

				// $stock_anterior = $rpta_sql_suma_anterior->stock;



				$sql_detalle = "INSERT INTO detalle_ingreso(
					idingreso, idarticulo, codigo, serie, descripcion, stock_ingreso, stock_actual, precio_compra, precio_ventadistribuidor, precio_ventapublico,
					estado_detalle_ingreso,
					
					precio_ventasuperdistribuidor
					,precio_ventarepresentante,

					lote
					)
                                            VALUES($idingreso, " . $valor[0] . ", '" . $valor[1] . "', '" . $valor[2] . "', '" . $valor[3] . "', " . $valor[4] . ", " . $valor[4] . ", " . $valor[6] . ", " . $valor[7] . ", " . $valor[8] . ",'INGRESO',
											" . $valor[9] . ", " . $valor[10] . ", " . $valor[11] . ")";
				$conexion->query($sql_detalle) or $sw = false;


				// $sql_stock_producto = "SELECT
				// i.fecha,di.stock_actual AS stockActual,di.idarticulo,di.iddetalle_ingreso AS iddetalle_ingreso
				// FROM detalle_ingreso di
				// INNER JOIN ingreso i ON i.idingreso = di.idingreso
				// WHERE di.idarticulo = " . $valor[0] . " AND ingreso.estado = 'A'
				// ORDER BY i.fecha DESC LIMIT 1,1";

				// $rpta_sql_stock_producto = $conexion->query($sql_stock_producto);

				// $regStockAnterior = $rpta_sql_stock_producto->fetch_object();




				$cantidad = $valor[4];
				// detalle actual 
				// $suma_ingreso = "SELECT SUM(stock_actual) stock from detalle_ingreso where idarticulo=" . $valor[0] . "";
                
                $suma_ingreso="SELECT SUM(stock_actual) stock from detalle_ingreso
                    join ingreso on ingreso.idingreso=detalle_ingreso.idingreso
                    where idarticulo=$valor[0] and idsucursal=" . $_SESSION['idsucursal'] . " AND ingreso.estado='A'
                and detalle_ingreso.estado_detalle_ingreso='INGRESO'";

				$rpta_sql_suma_ingreso = $conexion->query($suma_ingreso)->fetch_object();

				$stock_actual = $rpta_sql_suma_ingreso->stock;


				$sql = "SELECT iddetalle_ingreso from detalle_ingreso where idingreso =" . $idingreso . " and idarticulo=$valor[0] ";

				$detalle_ingreso = $conexion->query($sql)->fetch_object()->iddetalle_ingreso;


				$stock_anterior=$stock_actual-$cantidad;
				$detallePedido = 0;
				$fecact = date('Y-m-d ');
				$sqlKardex = "INSERT INTO kardex(
						id_sucursal,
						fecha_emision,
						tipo,
						id_articulo,
						id_detalle_ingreso,
						stock_anterior,
						cantidad,
						stock_actual,
						fecha_creacion,
						fecha_modificacion,
						id_detalle_pedido
							 )
					VALUES(
						'" . $_SESSION['idsucursal'] . "',
						CURRENT_TIMESTAMP(),
						 'ingreso',
						 '" . $valor[0] . "',
						 '" . $detalle_ingreso . "',
						  '" . $stock_anterior . "',
						'" . $cantidad . "',
						'" . $stock_actual . "',
						CURRENT_TIMESTAMP(),
						CURRENT_TIMESTAMP(),
						'" . $detallePedido . "'
						)";
						 
				$conexion->query($sqlKardex) or $sw = false;
			}
			if ($conexion != null) {
				$conexion->close();
			}
		} catch (Exception $e) {
			$conexion->rollback();
		}
		return $sw;
	}
	public function Listar($idsucursal)
	{
		global $conexion;
		$sql = "select i.*, p.nombre as proveedor from ingreso i inner join persona p on i.idproveedor = p.idpersona 
            where i.idsucursal = $idsucursal order by idingreso desc limit 0,2999";
		$query = $conexion->query($sql);
		return $query;
	}
	public function CambiarEstado($idingreso)
	{
		// se agrego la consulta para adjuntantar al kardex la cancelacion del ingreso
		global $conexion;

		$sqlIngresoDatos="select * from  detalle_ingreso where idingreso=$idingreso";

		$response_ingreso_datos=$conexion->query($sqlIngresoDatos)->fetch_all();
		$fecact = date('Y-m-d ');
		foreach($response_ingreso_datos as $indice=>$valor){
			
			$iddetalle_ingreso=$valor[0];
			$id_articulo=$valor[2];
			$id_cantidad=$valor[6];
			$id_ingreso=$valor[0];
			


			$suma_ingreso_inicial="SELECT SUM(stock_actual) stock from detalle_ingreso
			join ingreso on ingreso.idingreso=detalle_ingreso.idingreso
			where idarticulo=$id_articulo and idsucursal=" . $_SESSION['idsucursal'] . " AND ingreso.estado = 'A'
		
                and detalle_ingreso.estado_detalle_ingreso='INGRESO'
			";			
			
			$rpta_sql_suma_ingreso_inicial = $conexion->query($suma_ingreso_inicial)->fetch_object();

			// var_dump($rpta_sql_suma_ingreso_inicial->stock);
			$stock_anterior = ($rpta_sql_suma_ingreso_inicial->stock !== null)?$rpta_sql_suma_ingreso_inicial->stock:0;




			$stock_actual=$stock_anterior-$id_cantidad;

			$stock_porid = "SELECT stock_actual stock from detalle_ingreso where iddetalle_ingreso=$iddetalle_ingreso ";
			$res = $conexion->query($stock_porid)->fetch_object()->stock;
			
			$ulu=$res-$id_cantidad;

			$sql = "UPDATE detalle_ingreso set  stock_actual=$ulu 
                        WHERE iddetalle_ingreso = $iddetalle_ingreso";
			$query = $conexion->query($sql);



			$suma_ingreso_final="SELECT SUM(stock_actual) stock from detalle_ingreso
			join ingreso on ingreso.idingreso=detalle_ingreso.idingreso
			where idarticulo=$id_articulo and idsucursal=" . $_SESSION['idsucursal'] . " AND ingreso.estado = 'A'
		
		and detalle_ingreso.estado_detalle_ingreso='INGRESO'";			
			$rpta_sql_suma_ingreso_final = $conexion->query($suma_ingreso_final)->fetch_object()->stock;

			$res_stock=($rpta_sql_suma_ingreso_final !== null)?$rpta_sql_suma_ingreso_final:0;
			$detallePedido = 0;
			// $stock_anterior=$valor[6];
			
			$anterior=$res_stock+$id_cantidad;

		
			$sqlKardex = "INSERT INTO kardex(
				id_sucursal,
				fecha_emision,
				tipo,
				id_articulo,
				id_detalle_ingreso,
				stock_anterior,
				cantidad,
				stock_actual,
				fecha_creacion,
				fecha_modificacion,
				id_detalle_pedido
					 )
			VALUES(
				'" . $_SESSION['idsucursal'] . "',
				 CURRENT_TIMESTAMP(),
				 'ingreso anulado',
				 '" . $id_articulo. "',
				 '" . $iddetalle_ingreso . "',
				 $stock_anterior,
				 $id_cantidad,
				$res_stock,
				CURRENT_TIMESTAMP(),
				CURRENT_TIMESTAMP(),
				'" . $detallePedido . "'
				)";
		$conexion->query($sqlKardex) or $sw = false;

		}
		// se agrego la consulta para adjuntantar al kardex la cancelacion del ingreso\\
		$sql = "UPDATE ingreso set estado = 'C'
                        WHERE idingreso = $idingreso";
		$query = $conexion->query($sql);
		return $query;
	}
	public function GetDetalleArticulo($idingreso)
	{
		global $conexion;
		$sql = "select a.nombre as articulo, di.*, (di.stock_ingreso * di.precio_compra) as sub_total
    from detalle_ingreso di
    inner join articulo a on di.idarticulo = a.idarticulo where di.idingreso = $idingreso";
		$query = $conexion->query($sql);
		return $query;
	}
	public function GetProveedorSucursalIngreso($idingreso)
	{
		global $conexion;
		$sql = "select p.*, ped.fecha,ped.tipo_comprobante, ped.num_comprobante, ped.serie_comprobante, s.razon_social, s.tipo_documento as documento_sucursal, s.num_documento as num_sucursal, s.direccion, s.telefono as telefono_suc, 
    s.email as email_suc, s.representante, s.logo, sum(di.stock_ingreso * di.precio_compra) as total,ped.impuesto
    from persona p inner join ingreso ped on ped.idproveedor = p.idpersona 
    inner join detalle_ingreso di on ped.idingreso = di.idingreso
    inner join sucursal s on ped.idsucursal = s.idsucursal
    where ped.idingreso = $idingreso";
		$query = $conexion->query($sql);
		return $query;
	}
	public function ListarProveedor()
	{
		global $conexion;
		$sql = "select * from persona where tipo_persona = 'Proveedor' and estado = 'A'";
		$query = $conexion->query($sql);
		return $query;
	}
	public function ListarTipoDocumento()
	{
		global $conexion;
		$sql = "select * from tipo_documento where operacion = 'Comprobante'";
		$query = $conexion->query($sql);
		return $query;
	}
	public function GetTipoDocSerieNum($nombre)
	{
		global $conexion;
		$sql = "select ultima_serie, ultimo_numero from tipo_documento where operacion = 'Comprobante' and nombre = '$nombre'";
		$query = $conexion->query($sql);
		return $query;
	}
	public function ListarProveedores()
	{
		global $conexion;
		$sql = "select * from persona where tipo_perssona = 'Proveedor'";
		$query = $conexion->query($sql);
		return $query;
	}
}
