<?php
require "Conexion.php";
class Persona
{

	public function __construct()
	{
	}

	public function ConsultarDni($dni)
	{
		global $conexion;

		$sql = "SELECT * FROM persona WHERE persona.num_documento ='$dni' 
		";

		$query = $conexion->query($sql);
		return $query;
	}

	//Se aumenta la celda apellidos
	public function Registrar(
		$tipo_persona,
		$nombre,
		$apellido,
		$tipo_documento,
		$num_documento,
		$genero,
		$direccion_departamento,
		$direccion_provincia,
		$direccion_distrito,
		$direccion_calle,
		$telefono,
		$telefono_2,
		$email,
		$numero_cuenta,
		$estado,
		$idempleado,
		$idempleado_modificado,
		$direccion_referencia,

		$direccion_calle_factura,
		$direccion_referencia_factura,
		$idprovincia_factura,
		$iddistrito_factura


	) {
		global $conexion;
		$sql = "INSERT INTO persona(tipo_persona,nombre,apellido,tipo_documento,num_documento,genero,direccion_departamento,direccion_provincia,direccion_distrito,direccion_calle,telefono,telefono_2,email,numero_cuenta,estado,idempleado,idempleado_modificado,fecha_registro,fecha_modificado,direccion_referencia
		,direccion_calle_factura
		,direccion_referencia_factura
		,idprovincia_factura
		,iddistrito_factura
		) 
					VALUES('$tipo_persona','$nombre','$apellido','$tipo_documento','$num_documento','$genero','$direccion_departamento','$direccion_provincia','$direccion_distrito','$direccion_calle','$telefono','$telefono_2','$email','$numero_cuenta','$estado','$idempleado','$idempleado_modificado',CURRENT_TIMESTAMP(),CURRENT_TIMESTAMP(),'$direccion_referencia'
					
				,'$direccion_calle_factura',
				'$direccion_referencia_factura',
				'$idprovincia_factura',
				'$iddistrito_factura'
	
					)";
		// direccion_calle_factura,
		// 	 direccion_referencia_factura,
		// 	direccion_distrito_factura

		//  ,'$direccion_calle_factura',
		//  '$direccion_referencia_factura',
		//  '$direccion_distrito_factura'

		echo $sql;
		$query = $conexion->query($sql);
		return $query;
	}

	public function Modificar(
		$idpersona,
		$tipo_persona,
		$nombre,
		$apellido,
		$tipo_documento,
		$num_documento,
		$genero,
		$direccion_departamento,
		$direccion_provincia,
		$direccion_distrito,
		$direccion_calle,
		$telefono,
		$telefono_2,
		$email,
		$numero_cuenta,
		$estado,
		$idempleado,
		$direccion_referencia,


		$direccion_calle_factura,
		$direccion_referencia_factura,
		$idprovincia_factura,
		$iddistrito_factura

	) {
		global $conexion;
		$sql = "UPDATE persona set tipo_persona = '$tipo_persona',nombre = '$nombre',apellido='$apellido',tipo_documento='$tipo_documento',num_documento='$num_documento',genero='$genero', direccion_departamento = '$direccion_departamento',direccion_provincia='$direccion_provincia',direccion_distrito='$direccion_distrito',
			direccion_calle='$direccion_calle' ,telefono='$telefono',telefono_2='$telefono_2',email='$email',numero_cuenta='$numero_cuenta',idempleado_modificado='$idempleado',estado='$estado',fecha_modificado=CURRENT_TIMESTAMP(),direccion_referencia='$direccion_referencia',


			direccion_calle_factura='$direccion_calle_factura',
			direccion_referencia_factura='$direccion_referencia_factura',
			idprovincia_factura='$idprovincia_factura',
			iddistrito_factura='$iddistrito_factura'


						WHERE idpersona = $idpersona";

$sql = "UPDATE persona set tipo_persona = '$tipo_persona',nombre = '$nombre',apellido='$apellido',tipo_documento='$tipo_documento',num_documento='$num_documento',genero='$genero', direccion_departamento = '$direccion_departamento',direccion_provincia='$direccion_provincia',direccion_distrito='$direccion_distrito',
direccion_calle='$direccion_calle' ,telefono='$telefono',telefono_2='$telefono_2',email='$email',numero_cuenta='$numero_cuenta',idempleado_modificado='$idempleado',estado='$estado',fecha_modificado=CURRENT_TIMESTAMP(),direccion_referencia='$direccion_referencia',


direccion_calle_factura='$direccion_calle_factura',
direccion_referencia_factura='$direccion_referencia_factura',
idprovincia_factura='$idprovincia_factura',
iddistrito_factura='$iddistrito_factura'

			WHERE num_documento ='$num_documento' and 
			idpersona=$idpersona";
			
		echo($sql);

		$query = $conexion->query($sql);
		return $query;
	}

	public function Eliminar($idpersona)
	{
		global $conexion;
		$sql = "DELETE FROM persona WHERE idpersona = $idpersona";
		$query = $conexion->query($sql);
		return $query;
	}

	public function comprobar_telefono($telefono)
	{
		global $conexion;
		$sql = "SELECT *  FROM persona WHERE telefono=$telefono AND estado ='A'";
		$query = $conexion->query($sql);
		return $query;
	}



	public function Listar()
	{
		global $conexion;
		$sql = "SELECT * FROM persona order by idpersona desc";
		$query = $conexion->query($sql);
		return $query;
	}

	public function ListarProveedor()
	{
		global $conexion;
		$sql = "SELECT * FROM persona where tipo_persona='Proveedor' order by idpersona desc";
		$query = $conexion->query($sql);
		return $query;
	}

	public function ReporteProveedor()
	{
		global $conexion;
		$sql = "SELECT * FROM persona where tipo_persona='Proveedor' order by nombre asc";
		$query = $conexion->query($sql);
		return $query;
	}

	public function ReporteCliente()
	{
		global $conexion;
		$sql = "SELECT * FROM persona where tipo_persona='Cliente' order by nombre asc";
		$query = $conexion->query($sql);
		return $query;
	}
	// idUsuario es el identificador por sucursal , idEmpleado es el identificador en Global
	public function ListarCliente()
	{
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


		// antiguo sql solo un num_documento
		$sql = "SELECT
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

		// nuevo sql solo un num_documento
		$sql = "SELECT
		p.idpersona,
		p.num_documento,
		COUNT(p.num_documento),
		concat( e.nombre, ' ', e.apellidos ) AS empleado,
		concat( e2.nombre, ' ', e2.apellidos ) AS empleado_modificado,
		(CASE
			WHEN p.genero = 1 THEN 'MUJER'
			WHEN p.genero = 2 THEN 'HOMBRE'
			WHEN p.genero = 3 THEN 'PREFIERO NO DECIRLO'
		END) AS genero_txt
		,p.*
		,CONCAT(departamento.iddepartamento,' - ',provincia.idprovincia, ' - ',distrito.iddistrito) idubicacion
		,  CONCAT(departamento.descripcion,' - ',provincia.descripcion, ' - ',distrito.descripcion) ubicacion
		,CONCAT(dep_n.iddepartamento,' - ',pro_n.idprovincia, ' - ',dis_n.iddistrito) idubicacion_factura
		,  CONCAT(dep_n.descripcion,' - ',pro_n.descripcion, ' - ',dis_n.descripcion) ubicacion_factura
		,if(p.direccion_distrito>0 AND p.direccion_provincia>0,'',CONCAT(p.direccion_departamento ,' ', p.direccion_distrito,' ',p.direccion_provincia)) direccion_antigua


	


		FROM
		persona p
		INNER JOIN empleado e ON p.idempleado = e.idempleado
		INNER JOIN empleado e2 ON p.idempleado_modificado = e2.idempleado 
		INNER JOIN (SELECT num_documento, MAX(persona.idpersona) AS max_fecha FROM persona  GROUP BY num_documento)
		t2 ON t2.num_documento = p.num_documento AND p.idpersona = t2.max_fecha
		
		
			
		LEFT JOIN distrito ON distrito.iddistrito=p.direccion_distrito
		left JOIN provincia ON provincia.idprovincia=p.direccion_provincia
		left JOIN departamento ON departamento.iddepartamento=provincia.iddepartamento
		
		LEFT JOIN distrito dis_n ON dis_n.iddistrito=p.iddistrito_factura
		left JOIN provincia pro_n ON pro_n.idprovincia=p.idprovincia_factura
		left JOIN departamento dep_n ON dep_n.iddepartamento=pro_n.iddepartamento
		
		WHERE p.estado='A' AND (
		tipo_persona = 'FINAL' or 	tipo_persona =  'DISTRIBUIDOR' or tipo_persona =  'SUPERDISTRIBUIDOR' or tipo_persona = 'REPRESENTANTE' )
		GROUP BY p.num_documento
		ORDER BY p.idpersona DESC
		
;
		
		";
		//var_dump($sql);exit;

		$query = $conexion->query($sql);
		return $query;
	}

	public function BuscarClientePorNroDoc($numeroDocumento)
	{
		global $conexion;

		$sql = "SELECT *,
		(
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
			   
		END  

		)as clasificacion ,
		(CASE
			WHEN genero = 1 THEN 'MUJER'
			WHEN genero = 2 THEN 'HOMBRE'
			WHEN genero = 3 THEN 'PREFIERO NO DECIRLO'
		END) AS genero_txt
		FROM persona 
		left join pedido on pedido.idcliente=persona.idpersona
		left JOIN venta ON venta.idpedido=pedido.idpedido
		WHERE num_documento = $numeroDocumento";
		// nuevo sql que solo trae el dato el dato mas reciente
		$sql = "SELECT *,
		(
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
			   
		END  

		)as clasificacion ,
		(CASE
			WHEN genero = 1 THEN 'MUJER'
			WHEN genero = 2 THEN 'HOMBRE'
			WHEN genero = 3 THEN 'PREFIERO NO DECIRLO'
		END) AS genero_txt
		, CONCAT(departamento.iddepartamento,' - ',provincia.idprovincia, ' - ',distrito.iddistrito) idubicacion,  CONCAT(departamento.descripcion,' - ',provincia.descripcion, ' - ',distrito.descripcion) ubicacion
		

		, CONCAT(dep_n.iddepartamento,' - ',pro_n.idprovincia, ' - ',dis_n.iddistrito) idubicacion_factura,  CONCAT(dep_n.descripcion,' - ',pro_n.descripcion, ' - ',dis_n.descripcion) ubicacion_factura
		
		,if(persona.direccion_distrito>0 AND persona.direccion_provincia>0,'',CONCAT(persona.direccion_departamento ,' ', persona.direccion_distrito,' ',persona.direccion_provincia)) direccion_antigua

		FROM persona 

		left join pedido on pedido.idcliente=persona.idpersona
		left JOIN venta ON venta.idpedido=pedido.idpedido
		
		INNER JOIN (SELECT num_documento, MAX(persona.idpersona) AS max_fecha FROM persona  GROUP BY num_documento)
		t2 ON t2.num_documento = persona.num_documento AND persona.idpersona = t2.max_fecha
		 
		LEFT JOIN distrito ON distrito.iddistrito=persona.direccion_distrito
		left JOIN provincia ON provincia.idprovincia=persona.direccion_provincia
		left JOIN departamento ON departamento.iddepartamento=provincia.iddepartamento
	
		
			
		LEFT JOIN distrito dis_n ON dis_n.iddistrito=persona.iddistrito_factura
		left JOIN provincia pro_n ON pro_n.idprovincia=persona.idprovincia_factura
		left JOIN departamento dep_n ON dep_n.iddepartamento=pro_n.iddepartamento

		
		WHERE persona.num_documento ='$numeroDocumento'
			GROUP BY persona.num_documento
		ORDER BY persona.idpersona DESC;";




		$query = $conexion->query($sql);
		return $query;
	}

	public function BuscarExistePedido($idpersona)
	{
		global $conexion;
		$query = $conexion->query("SELECT  COUNT(idpedido) AS countidpedido FROM pedido WHERE idcliente = " . $idpersona);
		//var_dump($query);exit;
		return $query;
	}

	public function ActualizarCuentaCliente($idcliente, $cuenta)
	{
		global $conexion;
		$sql = "UPDATE persona set numero_cuenta='$cuenta' WHERE idpersona = $idcliente";
		$query = $conexion->query($sql);
		return $query;
	}


	public function cambiarEstadoCliente_final($tipo)
	{

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
					WHERE per.tipo_persona = 'Cliente' AND ped.estado = 'A' " . $consulta . "
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

				$sql_update = "UPDATE persona SET estado = '" . $estadoFinal . "' WHERE idpersona = " . $reg->idcliente;
				$rpta_sql_update = $conexion->query($sql_update);
				$i++;
			}
		} else {
			$rpta_sql_update = "null";
		}

		$results = array(
			"cantidadRegistros" => $i,
			"result" => $rpta_sql_update
		);

		//var_dump($sql_update);

		return $results;
	}

	public function cambiarEstadoCliente_distribuidor($tipo)
	{

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
					WHERE (per.tipo_persona = 'Distribuidor' OR per.tipo_persona = 'Superdistribuidor') AND ped.estado = 'A' " . $consulta . "
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

				$sql_update = "UPDATE persona SET estado = '" . $estadoFinal . "' WHERE idpersona = " . $reg->idcliente;

				$rpta_sql_update = $conexion->query($sql_update);

				$i++;
			}
		} else {
			$rpta_sql_update = "null";
		}

		$results = array(
			"cantidadRegistros" => $i,
			"result" => $rpta_sql_update
		);

		//var_dump($sql_update);

		return $results;
	}
	public function  clasificacion_cliente($id_cliente)
	{
		global $conexion;

		$sql = "	SELECT 
				idpersona ,
				tipo_persona,
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
	public function traerUbicacion()
	{
		global $conexion;
		$sql = "SELECT CONCAT(departamento.iddepartamento,' - ',provincia.idprovincia, ' - ',distrito.iddistrito) idubicacion,  CONCAT(departamento.descripcion,' - ',provincia.descripcion, ' - ',distrito.descripcion) ubicacion FROM distrito


		JOIN provincia ON provincia.idprovincia=distrito.idprovincia
		
		JOIN departamento ON departamento.iddepartamento=provincia.iddepartamento
		
		
		-- WHERE CONCAT(departamento.descripcion,' ',provincia.descripcion, ' ',distrito.descripcion) LIKE '%aNCASH%'
		";


		$query = $conexion->query($sql);

		return $query;
	}
	public function cambiarEstadoCliente_representante($tipo)
	{

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
					WHERE per.tipo_persona = 'Representante' AND ped.estado = 'A' " . $consulta . "
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
				$sql_update = "UPDATE persona SET estado = '" . $estadoFinal . "' WHERE idpersona = " . $reg->idcliente;
				$rpta_sql_update = $conexion->query($sql_update);
				$i++;
			}
		} else {
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
