<?php
	require "Conexion.php";
	class Persona{

		public function __construct(){
		}
		//Se aumenta la celda apellidos
		public function Registrar($tipo_persona,$nombre,$apellido,$tipo_documento,$num_documento,$genero,$direccion_departamento,$direccion_provincia,$direccion_distrito,$direccion_calle,$telefono,$telefono_2,$email,$numero_cuenta,$estado,$idempleado,$idempleado_modificado){
			global $conexion;
			$sql = "INSERT INTO persona(tipo_persona,nombre,apellido,tipo_documento,num_documento,genero,direccion_departamento,direccion_provincia,direccion_distrito,direccion_calle,telefono,telefono_2,email,numero_cuenta,estado,idempleado,idempleado_modificado,fecha_registro,fecha_modificado) 
					VALUES('$tipo_persona','$nombre','$apellido','$tipo_documento','$num_documento','$genero','$direccion_departamento','$direccion_provincia','$direccion_distrito','$direccion_calle','$telefono','$telefono_2','$email','$numero_cuenta','$estado','$idempleado','$idempleado_modificado',CURRENT_TIMESTAMP(),CURRENT_TIMESTAMP())";
			//var_dump($sql);exit;
			$query = $conexion->query($sql);
			return $query;
		}

		public function Modificar($idpersona,$tipo_persona,$nombre,$apellido,$tipo_documento,$num_documento,$genero,$direccion_departamento,$direccion_provincia,$direccion_distrito,$direccion_calle,$telefono,$telefono_2,$email,$numero_cuenta,$estado,$idempleado){
			global $conexion;
			$sql = "UPDATE persona set tipo_persona = '$tipo_persona',nombre = '$nombre',apellido='$apellido',tipo_documento='$tipo_documento',num_documento='$num_documento',genero='$genero', direccion_departamento = '$direccion_departamento',direccion_provincia='$direccion_provincia',direccion_distrito='$direccion_distrito',
			direccion_calle='$direccion_calle' ,telefono='$telefono',telefono_2='$telefono_2',email='$email',numero_cuenta='$numero_cuenta',idempleado_modificado='$idempleado',estado='$estado',fecha_modificado=CURRENT_TIMESTAMP()
						WHERE idpersona = $idpersona";

			//var_dump($sql);exit;

			$query = $conexion->query($sql);
			return $query;
		}
		
		public function Eliminar($idpersona){
			global $conexion;
			$sql = "DELETE FROM persona WHERE idpersona = $idpersona";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Listar(){
			global $conexion;
			$sql = "SELECT * FROM persona order by idpersona desc";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarProveedor(){
			global $conexion;
			$sql = "SELECT * FROM persona where tipo_persona='Proveedor' order by idpersona desc";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ReporteProveedor(){
			global $conexion;
			$sql = "SELECT * FROM persona where tipo_persona='Proveedor' order by nombre asc";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ReporteCliente(){
			global $conexion;
			$sql = "SELECT * FROM persona where tipo_persona='Cliente' order by nombre asc";
			$query = $conexion->query($sql);
			return $query;
		}
		// idUsuario es el identificador por sucursal , idEmpleado es el identificador en Global
		public function ListarCliente(){
			global $conexion;
			$sql = "SELECT
			p.*,
			concat( e.nombre, ' ', e.apellidos ) AS empleado,
			concat( e2.nombre, ' ', e2.apellidos ) AS empleado_modificado
			FROM
			persona p
			INNER JOIN empleado e ON p.idempleado = e.idempleado
			INNER JOIN empleado e2 ON p.idempleado_modificado = e2.idempleado 
			WHERE
			tipo_persona = 'Cliente' & 'Distribuidor' & 'Superdistribuidor ' & 'Representante'
			ORDER BY
			idpersona DESC";
			$query = $conexion->query($sql);
			return $query;
		}

		public function BuscarClientePorNroDoc($numeroDocumento){
			global $conexion;
			$query = $conexion->query("SELECT * FROM persona WHERE num_documento = ".$numeroDocumento);
			return $query;
		}

		public function BuscarExistePedido($idpersona){
			global $conexion;
			$query = $conexion->query("SELECT  COUNT(idpedido) AS countidpedido FROM pedido WHERE idcliente = ".$idpersona);
			//var_dump($query);exit;
			return $query;
		}

		public function ActualizarCuentaCliente($idcliente,$cuenta){
			global $conexion;
			$sql = "UPDATE persona set numero_cuenta='$cuenta' WHERE idpersona = $idcliente";
			$query = $conexion->query($sql);
			return $query;
		}


		public function cambiarEstadoCliente_final($tipo){

			global $conexion;

			switch ($tipo) {
				case '1':
					$consulta = "AND ( DATE_FORMAT(ped.fecha, '%Y-%m') >= DATE_FORMAT(CURRENT_DATE - INTERVAL 2 MONTH, '%Y-%m') ) ";
					$estadoFinal = "A";
				break;
				
				case '2':
					$consulta = "AND (DATE_FORMAT(ped.fecha, '%Y-%m') BETWEEN DATE_FORMAT(CURRENT_DATE - INTERVAL 4 MONTH, '%Y-%m' ) AND  DATE_FORMAT(CURRENT_DATE - INTERVAL 2 MONTH, '%Y-%m') )";
					$estadoFinal = "C";
				break;

				case '3':
					$consulta = "AND ( DATE_FORMAT(ped.fecha, '%Y-%m') <= DATE_FORMAT(CURRENT_DATE - INTERVAL 4 MONTH, '%Y-%m') )";
					$estadoFinal = "P";
				break;
			}

			$sql = "SELECT
					ped.idcliente AS idcliente
					FROM
					pedido ped
					INNER JOIN persona per ON per.idpersona = ped.idcliente
					WHERE per.tipo_persona = 'Cliente' AND ped.estado = 'A' ".$consulta."
					GROUP BY ped.idcliente
					ORDER BY ped.fecha DESC";

			$query = $conexion->query($sql);

			$i = 0;

			// A : Activo
			// C : Inactivo
			// P : Perdido

			while ($reg = $query->fetch_object()) {

				$sql_update = "UPDATE persona SET estado = '".$estadoFinal."' WHERE idpersona = ".$reg->idcliente;

				$rpta_sql_update = $conexion->query($sql_update);

				$i++;

			}

			$results = array(
				"cantidadRegistros" => $i,
				"result" => $rpta_sql_update
			);


			//var_dump($sql_update);

			return $results;

		}







	}