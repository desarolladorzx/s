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
			// $sql = "SELECT
			// p.*,
			// concat( e.nombre, ' ', e.apellidos ) AS empleado,
			// concat( e2.nombre, ' ', e2.apellidos ) AS empleado_modificado,
			// (CASE
			// 	WHEN p.genero = 1 THEN 'MUJER'
			// 	WHEN p.genero = 2 THEN 'HOMBRE'
			// 	WHEN p.genero = 3 THEN 'PREFIERO NO DECIRLO'
			// END) AS genero_txt
			// FROM
			// persona p
			// INNER JOIN empleado e ON p.idempleado = e.idempleado
			// INNER JOIN empleado e2 ON p.idempleado_modificado = e2.idempleado 
			// WHERE
			// tipo_persona = 'Cliente' & 'Distribuidor' & 'Superdistribuidor ' & 'Representante'
			// ORDER BY
			// idpersona DESC";
			$sql="SELECT
			p.*,
			concat( e.nombre, ' ', e.apellidos ) AS empleado,
			concat( e2.nombre, ' ', e2.apellidos ) AS empleado_modificado,
			(CASE
				WHEN p.genero = 1 THEN 'MUJER'
				WHEN p.genero = 2 THEN 'HOMBRE'
				WHEN p.genero = 3 THEN 'PREFIERO NO DECIRLO'
			END) AS genero_txt
			FROM
			persona p
			INNER JOIN empleado e ON p.idempleado = e.idempleado
			INNER JOIN empleado e2 ON p.idempleado_modificado = e2.idempleado 
			WHERE
			tipo_persona = 'FINAL' or 	tipo_persona =  'DISTRIBUIDOR' or tipo_persona =  'SUPERDISTRIBUIDOR' or tipo_persona = 'REPRESENTANTE'
			ORDER BY idpersona DESC";

			//var_dump($sql);exit;

			$query = $conexion->query($sql);
			return $query;
		}

		public function BuscarClientePorNroDoc($numeroDocumento){
			global $conexion;
			$query = $conexion->query("SELECT *,
			(CASE
				WHEN genero = 1 THEN 'MUJER'
				WHEN genero = 2 THEN 'HOMBRE'
				WHEN genero = 3 THEN 'PREFIERO NO DECIRLO'
			END) AS genero_txt
			FROM persona WHERE num_documento = ".$numeroDocumento);
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

			$reg = $query->fetch_object();

			if (!is_null($reg)) {

				while ($reg = $query->fetch_object()) {

					$sql_update = "UPDATE persona SET estado = '".$estadoFinal."' WHERE idpersona = ".$reg->idcliente;
					$rpta_sql_update = $conexion->query($sql_update);
					$i++;

				}

			}else{
				$rpta_sql_update = "null";
			}

			$results = array(
				"cantidadRegistros" => $i,
				"result" => $rpta_sql_update
			);

			//var_dump($sql_update);

			return $results;

		}

		public function cambiarEstadoCliente_distribuidor($tipo){

			global $conexion;

			switch ($tipo) {
				case '1':
					$consulta = "AND ( DATE_FORMAT(ped.fecha, '%Y-%m') >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, '%Y-%m') ) ";
					$estadoFinal = "A";
				break;
				
				case '2':
					$consulta = "AND (DATE_FORMAT(ped.fecha, '%Y-%m') BETWEEN DATE_FORMAT(CURRENT_DATE - INTERVAL 2 MONTH, '%Y-%m' ) AND  DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, '%Y-%m') )";
					$estadoFinal = "C";
				break;

				case '3':
					$consulta = "AND ( DATE_FORMAT(ped.fecha, '%Y-%m') <= DATE_FORMAT(CURRENT_DATE - INTERVAL 2 MONTH, '%Y-%m') )";
					$estadoFinal = "P";
				break;
			}

			$sql = "SELECT
					ped.idcliente AS idcliente
					FROM
					pedido ped
					INNER JOIN persona per ON per.idpersona = ped.idcliente
					WHERE (per.tipo_persona = 'Distribuidor' OR per.tipo_persona = 'Superdistribuidor') AND ped.estado = 'A' ".$consulta."
					GROUP BY ped.idcliente
					ORDER BY ped.fecha DESC";

			$query = $conexion->query($sql);

			$i = 0;

			// A : Activo
			// C : Inactivo
			// P : Perdido

			$reg = $query->fetch_object();

			if (!is_null($reg)) {

				while ($reg = $query->fetch_object()) {

				$sql_update = "UPDATE persona SET estado = '".$estadoFinal."' WHERE idpersona = ".$reg->idcliente;

				$rpta_sql_update = $conexion->query($sql_update);

				$i++;

				}

			}else{
				$rpta_sql_update = "null";
			}

			$results = array(
				"cantidadRegistros" => $i,
				"result" => $rpta_sql_update
			);

			//var_dump($sql_update);

			return $results;

		}
		public function  clasificacion_cliente($id_cliente){
			global $conexion;

			$sql="	SELECT 
				idpersona ,
			
				tipo_persona,
				-- TIMESTAMPDIFF(month,venta.fecha,CURDATE()) AS meses_transcurridos,
				
			CASE 
				  WHEN tipo_persona='FINAL' and TIMESTAMPDIFF(month,venta.fecha ,CURDATE())<2 THEN 'ACTIVO' 
					 WHEN tipo_persona='FINAL' and TIMESTAMPDIFF(month,venta.fecha ,CURDATE())>=2 
					 and TIMESTAMPDIFF(month,venta.fecha ,CURDATE())<4   THEN 'INACTIVO' 
			   WHEN tipo_persona='FINAL' and TIMESTAMPDIFF(month,venta.fecha ,CURDATE())>=4 THEN 'PERDIDO' 

			   WHEN tipo_persona='distribuidor' and TIMESTAMPDIFF(month,venta.fecha ,CURDATE())<1 THEN 'ACTIVO' 
					 WHEN tipo_persona='distribuidor' and TIMESTAMPDIFF(month,venta.fecha ,CURDATE())>=1 
					 and TIMESTAMPDIFF(month,venta.fecha ,CURDATE())<2   THEN 'INACTIVO' 
			   WHEN tipo_persona='distribuidor' and TIMESTAMPDIFF(month,venta.fecha ,CURDATE())>=2 THEN 'PERDIDO' 
			   
			    WHEN tipo_persona='superdistribuidor' and TIMESTAMPDIFF(month,venta.fecha ,CURDATE())<1 THEN 'ACTIVO' 
					 WHEN tipo_persona='superdistribuidor' and TIMESTAMPDIFF(month,venta.fecha ,CURDATE())>=1 
					 and TIMESTAMPDIFF(month,venta.fecha ,CURDATE())<2   THEN 'INACTIVO' 
			   WHEN tipo_persona='superdistribuidor' and TIMESTAMPDIFF(month,venta.fecha ,CURDATE())>=2 THEN 'PERDIDO' 
			   
					 
					 WHEN tipo_persona='representante' and TIMESTAMPDIFF(month,venta.fecha ,CURDATE())<3 THEN 'ACTIVO' 
					 WHEN tipo_persona='representante' and TIMESTAMPDIFF(month,venta.fecha ,CURDATE())>=3 
					 and TIMESTAMPDIFF(month,venta.fecha ,CURDATE())<=5   THEN 'INACTIVO' 
			   WHEN tipo_persona='representante' and TIMESTAMPDIFF(month,venta.fecha ,CURDATE())>=5 THEN 'PERDIDO' 
				   
						 WHEN venta.fecha IS NULL then 'PERDIDO'
				   
				   END  as clasificacion,
			 persona.nombre
			
			FROM persona 
			left join pedido on pedido.idcliente=persona.idpersona
			left JOIN venta ON venta.idpedido=pedido.idpedido
			 WHERE persona.idpersona=$id_cliente
			GROUP by (idpersona)
			  
			";
			$query = $conexion->query($sql);
			return $query;
		}

		public function cambiarEstadoCliente_representante($tipo){

			global $conexion;

			switch ($tipo) {
				case '1':
					$consulta = "AND ( DATE_FORMAT(ped.fecha, '%Y-%m') >= DATE_FORMAT(CURRENT_DATE - INTERVAL 3 MONTH, '%Y-%m') ) ";
					$estadoFinal = "A";
				break;
				
				case '2':
					$consulta = "AND (DATE_FORMAT(ped.fecha, '%Y-%m') BETWEEN DATE_FORMAT(CURRENT_DATE - INTERVAL 5 MONTH, '%Y-%m' ) AND  DATE_FORMAT(CURRENT_DATE - INTERVAL 3 MONTH, '%Y-%m') )";
					$estadoFinal = "C";
				break;

				case '3':
					$consulta = "AND ( DATE_FORMAT(ped.fecha, '%Y-%m') <= DATE_FORMAT(CURRENT_DATE - INTERVAL 5 MONTH, '%Y-%m') )";
					$estadoFinal = "P";
				break;
			}

			$sql = "SELECT
					ped.idcliente AS idcliente
					FROM
					pedido ped
					INNER JOIN persona per ON per.idpersona = ped.idcliente
					WHERE per.tipo_persona = 'Representante' AND ped.estado = 'A' ".$consulta."
					GROUP BY ped.idcliente
					ORDER BY ped.fecha DESC";

			$query = $conexion->query($sql);

			$i = 0;

			// A : Activo
			// C : Inactivo
			// P : Perdido

			//var_dump($query->fetch_object());

			$reg = $query->fetch_object();

			if (!is_null($reg)) {

				while ($reg = $query->fetch_object()) {
					$sql_update = "UPDATE persona SET estado = '".$estadoFinal."' WHERE idpersona = ".$reg->idcliente;
					$rpta_sql_update = $conexion->query($sql_update);
					$i++;
				}

			}else{
				$rpta_sql_update = "null";
			}

			$results = array(
				"cantidadRegistros" => $i,
				"result" => $rpta_sql_update
			);

			//var_dump($results);

			return $results;

		}


	}