<?php
require "Conexion.php";

class Activos
{
	public function __construct()
	{
	}

	public function actualizar_ultimo_empleado($valor){
		global $conexion;
		$data = (object) $valor;

		$sql="UPDATE gestion_activo
				 SET 
				area = '$data->area',
				fecha_asignacion =  '$data->fecha_asignacion',
				idempleado = '$data->idempleado',
				idempleado_uso =  '$data->idempleado_uso',
				ubicacion = '$data->idubicacion'
				 WHERE idgestion_activos='$data->idgestionActivo'
		";
		
		$query = $conexion->query($sql);
		return  $query;
		

	}
	public function verArchivosActivos($idgestion_activo)
	{
		global $conexion;

		$sql = " SELECT * FROM gestion_activo_archivo where idgestion_activo=$idgestion_activo";
		$query = $conexion->query($sql);
		return  $query;
	}

	public function  RegistrarImagenActivo(
		$idgestion_activo,
		$new_file_name
	) {
		global $conexion;
		$sql = "INSERT INTO gestion_activo_archivo(
			idgestion_activo,
			ruta,
			fecha,
			estado
			)
		VALUES(
			$idgestion_activo, 
			'$new_file_name', 
			CURRENT_TIMESTAMP(), 
			'A'			
			)";
		// echo $sql;
		$query = $conexion->query($sql);
		return $query;
	}
	public function EliminarActivo($id){
		global $conexion;

		$sql = "UPDATE activo
				 SET estado_activo = 'C'
				 where idactivo='$id'
			 ";
		$conexion->query($sql); 

	}
	public function transferirActivo($valor)
	{

		global $conexion;

		$data = array_combine(
			array_map(function ($key) {
				return str_replace('act_', '', $key);
			}, array_keys($valor)),
			$valor
		);

		$data = (object) $data;


		$sql = "UPDATE gestion_activo
				 SET estado_activo = 'C'
				 where idactivo='$data->idactivo'
			 ";
		$conexion->query($sql);

		$sql = "INSERT INTO gestion_activo (
			idactivo,
			idempleado,
			area,
			fecha_asignacion,
		 	idempleado_uso,
			idempleado_registro,
		 	idempleado_modificado,
		 	fecha_registro,
		 	fecha_modificado,
			estado_activo,
			ubicacion
		) VALUES (
			'$data->idactivo',
			'$data->idempleado',
			'$data->area',
			'$data->fecha_asignacion',
			'$data->idempleado_uso',
			'" . $_SESSION["idempleado"] . "', 
		 	'" . $_SESSION["idempleado"] . "', 
			CURRENT_TIMESTAMP(), 
			CURRENT_TIMESTAMP(),
			'A',
			'$data->ubicacion'
		)";
			
		// print_r($valor); 

		$query = $conexion->query($sql);
		$idgestion_activo=$conexion->insert_id;

		// $sql = "INSERT INTO gestion_activo_archivo(
		// 	idgestion_activo,
		// 	ruta,
		// 	fecha,
		// 	estado
		// 	)
		// VALUES(
		// 	$idgestion_activo, 
		// 	'$new_file_name', 
		// 	CURRENT_TIMESTAMP(), 
		// 	'A'			
		// 	)";

		// $query = $conexion->query($sql);
		return $idgestion_activo;
	}
	public function verDetallesActivoUnidad($id)
	{


		global $conexion;
		$sql = " SELECT 
			
		gestion_activo.* 
		,activo.*
		,CAST(fecha_ingreso AS DATE) fecha_ingreso,CAST(fecha_finvida  AS DATE) fecha_finvida,CAST(fecha_asignacion  AS DATE) fecha_asignacion 
		
		 FROM activo
		 
		 left join gestion_activo on gestion_activo.idactivo = activo.idactivo
		  where activo.idactivo=$id
		  AND gestion_activo.estado_activo='A'
			 ";


		$query = $conexion->query($sql);
		return $query;
	}
	public function listaDeEmpleadosPorActivos($id)
	{
		global $conexion;
		$sql = "SELECT *,CONCAT(empleado.nombre ,' ',empleado.apellidos) empleado_asignado, CONCAT(emp_uso.nombre ,' ',emp_uso.apellidos) empleado_uso FROM gestion_activo

		left JOIN empleado ON empleado.idempleado=gestion_activo.idempleado
		left JOIN empleado emp_uso ON emp_uso.idempleado=gestion_activo.idempleado_uso
		
		 WHERE idactivo=$id 
		 order by idgestion_activos desc
		 ";
		$query = $conexion->query($sql);
		return $query;
	}

	public function ListarActivosPorEmpleados($id)
	{

		global $conexion;
		$sql = "SELECT gestion_activo.* 
			
			 FROM gestion_activo where idempleado=$id ";
		$query = $conexion->query($sql);
		return $query;
	}

	public function ListarEmpleados()
	{
		global $conexion;
		$sql = "SELECT * FROM empleado";
		$query = $conexion->query($sql);
		return $query;
	}

	public function Listar()
	{
		global $conexion;
		$sql = "SELECT area,gestion_activo.idgestion_activos, activo.tipo_activo,CONCAT(emp_uso.nombre,' ',emp_uso.apellidos) usado_por , CONCAT(emp_jes.nombre,' ',emp_jes.apellidos) gestionado_por,activo.estado,activo.codigo etiqueta,activo.fecha_finvida,  activo.* 
		,CAST(fecha_finvida  AS DATE) fecha_finvida
		,ubicacion
		FROM activo
		left join gestion_activo on gestion_activo.idactivo = activo.idactivo
		
		LEFT JOIN empleado emp_uso ON emp_uso.idempleado =gestion_activo.idempleado_uso
		
		
			LEFT JOIN empleado emp_jes ON emp_jes.idempleado=gestion_activo.idempleado_registro
		where estado_activo='A'
		 order by activo.idactivo desc";
		$query = $conexion->query($sql);
		return $query;
	}
	public function GuardarActivo($valor)
	{

		$data = array_combine(
			array_map(function ($key) {
				return str_replace('act_', '', $key);
			}, array_keys($valor)),
			$valor
		);

		$data = (object) $data;




		if ($data->idactivo) {
			$sql = "UPDATE activo
		 SET codigo = '$data->codigo',
		
			 familia_activo = '$data->familia_activo',
			 tipo_equipo = '$data->tipo_equipo',
			 unidad = '$data->unidad',
			 marca = '$data->marca',
			 modelo = '$data->modelo',
			 serie = '$data->serie',
			 color = '$data->color',
			 caracteristica = '$data->caracteristica',
			 estado = '$data->estado',
			 tipo_activo = '$data->tipo_activo',
			 t_documento = '$data->t_documento',
			 precio_compra = '$data->precio_compra',
			 proveedor =  '$data->proveedor',
			 fecha_finvida =  '$data->fecha_finvida'
		 
		 WHERE idactivo = $data->idactivo;";

			global $conexion;
			$conexion->query($sql);
		} else {
			$i = 1;

			$sql = "INSERT INTO activo (
				codigo,
				fecha_ingreso,
				familia_activo,
				tipo_equipo,
				unidad,
				marca,
				modelo,
				serie,
				color,
				caracteristica,
				estado,
				tipo_activo,
				t_documento,
				precio_compra,
				proveedor,
				fecha_finvida
				
			  ) VALUES (
				'$data->codigo',
				'$data->fecha_ingreso',
				'$data->familia_activo',
				'$data->tipo_equipo',
				'$data->unidad',
				'$data->marca',
				'$data->modelo',
				'$data->serie',
				'$data->color',
				'$data->caracteristica',
				'$data->estado',
				'$data->tipo_activo',
				'$data->t_documento',
				'$data->precio_compra',
				'$data->proveedor',
				'$data->fecha_finvida'
			
			  );";

			global $conexion;
			$conexion->query($sql);

			$idActivo = $conexion->insert_id;



			$sql = "INSERT INTO gestion_activo (
			idactivo,
			idempleado,
			area,
			fecha_asignacion,
		 	idempleado_uso,
			idempleado_registro,
		 	idempleado_modificado,
		 	fecha_registro,
		 	fecha_modificado,
			estado_activo,
			ubicacion
		) VALUES (
			'$idActivo',
			'$data->idempleado',
			'$data->area',
			'$data->fecha_asignacion',
			'$data->idempleado_uso',
			'" . $_SESSION["idempleado"] . "', 
		 	'" . $_SESSION["idempleado"] . "', 
			CURRENT_TIMESTAMP(), 
			CURRENT_TIMESTAMP(),
			'A',
			'$data->ubicacion'
		)";


			global $conexion;
			$conexion->query($sql);

			$idgestion_activo = $conexion->insert_id;
			return  $idgestion_activo;
		}


		// $sql="INSERT INTO gestion_activo (
		// 	idempleado,
		// 	codigo,
		// 	fecha_ingreso,
		// 	familia_activos,
		// 	tipo_equipo,
		// 	unidades,
		// 	cantidad,
		// 	marca,
		// 	modelos,
		// 	serie,
		// 	color,
		// 	caracteristicas,
		// 	estado,
		// 	tipo_activo,
		// 	t_documento,
		// 	precio_compra,
		// 	proveedor,
		// 	fecha_finvida,
		// 	ubicacion,
		// 	area,
		// 	fecha_asignacion,
		// 	idempleado_uso,
		// 	idempleado_registro,
		// 	idempleado_modificado,
		// 	fecha_registro,
		// 	fecha_modificado
		// ) VALUES (
		// 	'$data->idempleado',
		// 	'$data->codigo',
		// 	'$data->fecha_ingreso',
		// 	'$data->familia_activos',
		// 	'$data->tipo_equipo',
		// 	'$data->unidades', 
		// 	'$data->cantidad', 
		// 	'$data->marca', 
		// 	'$data->modelos', 
		// 	'$data->serie', 
		// 	'$data->color', 
		// 	'$data->caracteristicas', 
		// 	'$data->estado', 
		// 	'$data->tipo_activo', 
		// 	'$data->t_documento', 
		// 	'$data->precio_compra', 
		// 	'$data->proveedor', 
		// 	'$data->fecha_finvida', 
		// 	'$data->ubicacion', 
		// 	'$data->area', 
		// 	'$data->fecha_asignacion', 
		// 	'$data->idempleado_uso', 
		// 	'".$_SESSION["idempleado"]."', 
		// 	'".$_SESSION["idempleado"]."', 
		// 	CURRENT_TIMESTAMP(), 
		// 	CURRENT_TIMESTAMP()
		// );
		// ";



	}
}
