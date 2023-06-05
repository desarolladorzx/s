<?php

require "Conexion.php";

class Establecimiento
{
	public function traerDatosRolVendedor()
	{
		global $conexion;
		$sql = "SELECT *  FROM  empleado WHERE  idrol=1 or idrol=2 or idrol=7;";
		$query = $conexion->query($sql);
		return $query;
	}


	public function TraerDatosCategoria_empresa()
	{
		global $conexion;
		$sql = "SELECT * from categoria_empresa where estado='1'";
		$query = $conexion->query($sql);
		return $query;
	}
	public function GetImagenes($idempresa)
	{
		global $conexion;

		$sql = "SELECT
		*
		from empresa_img 
		
		where empresa_img.idempresa = $idempresa and  empresa_img.estado = 1 AND tipo_imagen='EMPRESA'";
		//var_dump($sql);exit;
		$query = $conexion->query($sql);
		return $query;
	}

	public function RegistrarImagenes($idempresa, $imagen)
	{

		global $conexion;
		$sql = "INSERT INTO empresa_img(
				idempresa,
				imagen,
				estado,
				tipo_imagen,
				fecha
				)
			VALUES(
				$idempresa, 
				'$imagen',
				 1,
				 'EMPRESA',
				 CURRENT_TIMESTAMP()
				 )";


		$query = $conexion->query($sql);

		return $query;
	}

	public function cargarDatos($id)
	{
		global $conexion;





		$sql = "SELECT * 
			,CONCAT(empleado.nombre,' ',empleado.apellidos) empleado
			,CONCAT(departamento.iddepartamento,' - ',provincia.idprovincia, ' - ',distrito.iddistrito) idubicacion
			,CONCAT(departamento.descripcion,' - ',provincia.descripcion, ' - ',distrito.descripcion) ubicacion
			,empresa.nombre,empresa.direccion	,empresa.telefono
			
			 from empresa 
			
			left JOIN empleado ON empleado.idempleado =empresa.idempleado
			
			
			LEFT JOIN distrito  ON distrito.iddistrito=empresa.iddistrito
			left JOIN provincia  ON provincia.idprovincia=empresa.idprovincia
			left JOIN departamento  ON departamento.iddepartamento=provincia.iddepartamento
					
			where empresa.estado='1' and 
			idempresa=$id
			";


		$query = $conexion->query($sql);
		return $query;
	}

	public function listar()
	{
		global $conexion;

		$exepcion='';
		if($_SESSION['idrol']==7){
			$exepcion="and cartera_empresa.idempleado=".$_SESSION['idempleado'].  "";
		}
		$sql = "SELECT * 
		,CONCAT(empleado.nombre,' ',empleado.apellidos) empleado
		,CONCAT(departamento.iddepartamento,' - ',provincia.idprovincia, ' - ',distrito.iddistrito) idubicacion
		,CONCAT(departamento.descripcion,' - ',provincia.descripcion, ' - ',distrito.descripcion) ubicacion
		,empresa.nombre,empresa.direccion	,empresa.telefono
		
		, categoria_empresa.descripcion categoria_empresa_descripcion
		 from empresa 
		

		LEFT JOIN distrito  ON distrito.iddistrito=empresa.iddistrito
		
		left JOIN provincia  ON provincia.idprovincia=empresa.idprovincia

		left JOIN departamento  ON departamento.iddepartamento=provincia.iddepartamento
		
		left join categoria_empresa on categoria_empresa.idcategoria_empresa=empresa.idcategoria_empresa
		
		JOIN cartera_empresa ON empresa.idempresa=cartera_empresa.idempresa
		
		left JOIN empleado ON empleado.idempleado =cartera_empresa.idempleado
		
		WHERE cartera_empresa.estado='A'
		
		".$exepcion."
		and empresa.estado='1'

		order by empresa.idempresa desc
			";

			
		$query = $conexion->query($sql);
		return $query;
	}

	public function Eliminar($idempresa)
	{
		global $conexion;
		$sql = "UPDATE empresa set estado='0' WHERE idempresa = $idempresa";
		$query = $conexion->query($sql);
		return $query;
	}
	public function Registrar($POST)
	{

		if(isset($_POST["id_ubicacion_envio_array"]) and $_POST["id_ubicacion_envio_array"]!=""){
			$id_ubicacion_envio_array =explode(' - ', $_POST["id_ubicacion_envio_array"]);

		}else{
			$id_ubicacion_envio_array =[" " ," "," "];
		}
		$json = json_decode(json_encode($POST));

		$verificado= strlen($json->txt_verificacion)==0?'SIN VERIFICAR':$json->txt_verificacion;

		global $conexion;
		$sql = "INSERT INTO 
			empresa(
				horario,
				estado,
				telefono,
				idcategoria_empresa,
				razon_comercial,
				direccion,
				idprovincia,
				iddistrito,
				idempleado,
				nombre,
				hor_ini_lunes,
				hor_ini_martes,
				hor_ini_miercoles,
				hor_ini_jueves,
				hor_ini_viernes,
				hor_ini_sabado,
				hor_ini_domingo,

				hor_fin_lunes,
				hor_fin_martes,
				hor_fin_miercoles,
				hor_fin_jueves,
				hor_fin_viernes,
				hor_fin_sabado,
				hor_fin_domingo
				
				,fecha_registro
				,fecha_modificado
				,verificacion
				)
			values(
				'$json->txtHorario',
				'1',
				'$json->txtTelefono',
				'$json->txtTipoEstablecimiento',
				'$json->txtNombreEstablecimiento',
				'$json->txtDireccionEstablecimiento',
				'$id_ubicacion_envio_array[1]',
				'$id_ubicacion_envio_array[2]',
				'" . $_SESSION['idempleado'] . "',
				'$json->txtNombre',

				'$json->hor_ini_lunes',
				'$json->hor_ini_martes',
				'$json->hor_ini_miercoles',
				'$json->hor_ini_jueves',
				'$json->hor_ini_viernes',
				'$json->hor_ini_sabado',
				'$json->hor_ini_domingo',

				'$json->hor_fin_lunes',
				'$json->hor_fin_martes',
				'$json->hor_fin_miercoles',
				'$json->hor_fin_jueves',
				'$json->hor_fin_viernes',
				'$json->hor_fin_sabado',
				'$json->hor_fin_domingo',

				CURRENT_TIMESTAMP(),
				CURRENT_TIMESTAMP()
				,'$verificado'


				) ";
				// echo $sql;
		$query = $conexion->query($sql);


		$idempresa = $conexion->insert_id;

		$sql ="INSERT INTO  
		cartera_empresa(idempleado,idempresa,fecha_modificado,fecha_registro,estado) 
		values(
			'$json->txtEmpleadoAsignado',
			'$idempresa',
			CURRENT_TIMESTAMP(),
			CURRENT_TIMESTAMP(),
			'A'
		)";

		$query = $conexion->query($sql);


		return $idempresa;
	}
	public function Modificar($idempresa, $POST)
	{
		global $conexion;

		$id_ubicacion_envio_array = isset($_POST["id_ubicacion_envio_array"]) ? explode(' - ', $_POST["id_ubicacion_envio_array"]) : "";

		$json = json_decode(json_encode($POST));


		$sql = "UPDATE empresa set 
			horario='$json->txtHorario',
			estado='1',
			telefono='$json->txtTelefono',
			idcategoria_empresa='$json->txtTipoEstablecimiento',
			razon_comercial='$json->txtNombreEstablecimiento',
			direccion='$json->txtDireccionEstablecimiento',
			idprovincia='$id_ubicacion_envio_array[1]',
			iddistrito='$id_ubicacion_envio_array[2]',
			idempleado='" . $_SESSION['idempleado'] . "',
			nombre='$json->txtNombre'
			,
			hor_ini_lunes='$json->hor_ini_lunes',
			hor_ini_martes='$json->hor_ini_martes',
			hor_ini_miercoles='$json->hor_ini_miercoles',
			hor_ini_jueves='$json->hor_ini_jueves',
			hor_ini_viernes='$json->hor_ini_viernes',
			hor_ini_sabado='$json->hor_ini_sabado',
			hor_ini_domingo='$json->hor_ini_domingo',

			hor_fin_lunes='$json->hor_fin_lunes',
			hor_fin_martes='$json->hor_fin_martes',
			hor_fin_miercoles='$json->hor_fin_miercoles',
			hor_fin_jueves='$json->hor_fin_jueves',
			hor_fin_viernes='$json->hor_fin_viernes',
			hor_fin_sabado='$json->hor_fin_sabado',
			hor_fin_domingo='$json->hor_fin_domingo'

			WHERE idempresa = $idempresa";

		// echo $sql;
		$query = $conexion->query($sql);
		return $query;
	}
}
