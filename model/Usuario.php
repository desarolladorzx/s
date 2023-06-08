<?php
	require "Conexion.php";

	class usuario{
	
		
		public function __construct(){
		}

		public function Registrar($idsucursal, $idempleado, $tipo_usuario, $mnu_almacen, $mnu_compras, $mnu_ventas, $mnu_mantenimiento, $mnu_seguridad, $mnu_consulta_compras, $mnu_consultas_ventas,$mnu_documentacion_ev,$mnu_documentacion_jv,$mnu_documentacion_ja,$mnu_documentacion_jl, $mnu_admin
		// documentacion
			,$chkMnuDocumentacion,
			$chkMnuDocVentas,
			$chkMnuDocMarketing,
			$chkMnuDocLogistica,
			$chkMnuDocFinanzas,
			$chkMnuDocRRHH,
			$chkMnuDocIT,
			$chkMnuDoProduccion
			,$ventas_campo
			,$mnu_sigrh
	// documentacion
		
		){
			global $conexion;
			$sql = "INSERT INTO usuario(idsucursal, idempleado, tipo_usuario, fecha_registro, mnu_almacen, mnu_compras, mnu_ventas, mnu_mantenimiento, mnu_seguridad, mnu_consulta_compras, mnu_consulta_ventas,mnu_documentacion_ev,mnu_documentacion_jv,mnu_documentacion_ja,mnu_documentacion_jl, mnu_admin, estado
			,mnu_documentacion
			,mnu_documentacion_ventas
			,mnu_documentacion_marketing
			,mnu_documentacion_logistica
			,mnu_documentacion_finanzas
			,mnu_documentacion_rrhh
			,mnu_documentacion_it
			,mnu_documentacion_produccion
			,ventas_campo,
			mnu_sigrh
			)
						VALUES($idsucursal, $idempleado, '$tipo_usuario', curdate(), $mnu_almacen, $mnu_compras, $mnu_ventas, $mnu_mantenimiento, $mnu_seguridad, $mnu_consulta_compras, $mnu_consultas_ventas,$mnu_documentacion_ev,$mnu_documentacion_jv,$mnu_documentacion_ja,$mnu_documentacion_jl, $mnu_admin, 'A'
						,$chkMnuDocumentacion,
						$chkMnuDocVentas,
						$chkMnuDocMarketing,
						$chkMnuDocLogistica,
						$chkMnuDocFinanzas,
						$chkMnuDocRRHH,
						$chkMnuDocIT,
						$chkMnuDoProduccion,
						$ventas_campo,
						$mnu_sigrh
						)";
			$query = $conexion->query($sql);
			return $query;
		}
		
		public function Modificar($idusuario, $idsucursal, $idempleado, $tipo_usuario, $mnu_almacen, $mnu_compras, $mnu_ventas, $mnu_mantenimiento, $mnu_seguridad, $mnu_consulta_compras, $mnu_consultas_ventas,$mnu_documentacion_ev,$mnu_documentacion_jv,$mnu_documentacion_ja,$mnu_documentacion_jl, $mnu_admin
		// documentacion
		,$chkMnuDocumentacion,
				$chkMnuDocVentas,
				$chkMnuDocMarketing,
				$chkMnuDocLogistica,
				$chkMnuDocFinanzas,
				$chkMnuDocRRHH,
				$chkMnuDocIT,
				$chkMnuDoProduccion,
				// documentacion
				
				$ventas_campo
				,$mnu_sigrh

				){
			global $conexion;
			$sql = "UPDATE usuario set idsucursal = $idsucursal, idempleado = $idempleado, tipo_usuario = '$tipo_usuario', mnu_almacen = $mnu_almacen, mnu_compras = $mnu_compras, mnu_ventas = $mnu_ventas, mnu_mantenimiento = $mnu_mantenimiento, mnu_seguridad = $mnu_seguridad, mnu_consulta_compras = $mnu_consulta_compras, mnu_consulta_ventas = $mnu_consultas_ventas, mnu_documentacion_ev = $mnu_documentacion_ev, mnu_documentacion_jv = $mnu_documentacion_jv, mnu_documentacion_ja = $mnu_documentacion_ja, mnu_documentacion_jl = $mnu_documentacion_jl, mnu_admin = $mnu_admin

			,mnu_documentacion=$chkMnuDocumentacion
			,mnu_documentacion_ventas=$chkMnuDocVentas
			,mnu_documentacion_marketing=$chkMnuDocMarketing
			,mnu_documentacion_logistica=$chkMnuDocLogistica
			,mnu_documentacion_finanzas=$chkMnuDocFinanzas
			,mnu_documentacion_rrhh=$chkMnuDocRRHH
			,mnu_documentacion_it=$chkMnuDocIT
			,mnu_documentacion_produccion=$chkMnuDoProduccion
					,mnu_ventas_campo=$ventas_campo
					,mnu_sigrh=$mnu_sigrh


						WHERE idusuario = $idusuario";


					// echo $sql;
			$query = $conexion->query($sql);
			return $query;
		}
		
		public function Eliminar($idusuario){
			global $conexion;
			$sql = "DELETE from usuario WHERE idusuario = $idusuario";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Listar(){
			global $conexion;
			$sql = "select u.*,CONCAT (IFNULL(r_prefijo,' '), ' - ',IFNULL(nombre_usuario,' ')) nombre_usuario_rol, s.razon_social, concat(e.nombre, ' ', e.apellidos) as empleado
			from usuario u inner join sucursal s on u.idsucursal = s.idsucursal
			
			
			inner join empleado e on u.idempleado = e.idempleado
			left JOIN rol ON rol.r_id=e.idrol
			where u.estado <> 'C'";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Ingresar_Sistema($user, $pass){
			global $conexion;
			$sql = "select u.*, s.razon_social, s.logo as logo, concat(e.nombre, ' ', e.apellidos) as empleado, e.*, e.estado as superadmin
	from usuario u inner join sucursal s on u.idsucursal = s.idsucursal
	inner join empleado e on u.idempleado = e.idempleado
	where e.login = '$user' and e.clave = '$pass' and u.estado <> 'C'";
			$query = $conexion->query($sql);
			return $query;
		}

	}
