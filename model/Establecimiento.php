<?php

	require "Conexion.php";

	class Establecimiento{

	public function TraerDatosCategoria_empresa(){
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

		public function cargarDatos($id){
			global $conexion;
			
			$sql = "SELECT * 
			,CONCAT(empleado.nombre,' ',empleado.apellidos) empleado
			,CONCAT(departamento.iddepartamento,' - ',provincia.idprovincia, ' - ',distrito.iddistrito) idubicacion
			,CONCAT(departamento.descripcion,' - ',provincia.descripcion, ' - ',distrito.descripcion) ubicacion
			,empresa.nombre,empresa.direccion	,empresa.telefono
			
			 from empresa 
			
			left JOIN empleado ON empleado.idempleado =empresa.idempleado
			
			
			LEFT JOIN distrito  ON distrito.iddistrito=empresa.distrito
			left JOIN provincia  ON provincia.idprovincia=empresa.provincia
			left JOIN departamento  ON departamento.iddepartamento=provincia.iddepartamento
					
			where empresa.estado='1' and 
			idempresa=$id
			";

			
			$query = $conexion->query($sql);
			return $query;
		}

		public function listar(){
			global $conexion;
			$sql = "SELECT * 
			,CONCAT(empleado.nombre,' ',empleado.apellidos) empleado
			,CONCAT(departamento.iddepartamento,' - ',provincia.idprovincia, ' - ',distrito.iddistrito) idubicacion
			,CONCAT(departamento.descripcion,' - ',provincia.descripcion, ' - ',distrito.descripcion) ubicacion
			,empresa.nombre,empresa.direccion	,empresa.telefono
			
			, categoria_empresa.descripcion categoria_empresa_descripcion
			 from empresa 
			
			left JOIN empleado ON empleado.idempleado =empresa.idempleado
			
			
			LEFT JOIN distrito  ON distrito.iddistrito=empresa.distrito
			left JOIN provincia  ON provincia.idprovincia=empresa.provincia

			left join categoria_empresa on categoria_empresa.idcategoria_empresa=empresa.categoria_empresa
			left JOIN departamento  ON departamento.iddepartamento=provincia.iddepartamento
					
			where empresa.estado='1'
			";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Eliminar($idempresa){
			global $conexion;
			$sql = "UPDATE empresa set estado='0' WHERE idempresa = $idempresa";
			$query = $conexion->query($sql);
			return $query;
		}
		public function Registrar($POST){


			$id_ubicacion_envio_array = isset($_POST["id_ubicacion_envio_array"]) ? explode(' - ', $_POST["id_ubicacion_envio_array"]) : "";

			$json = json_decode(json_encode($POST));
			// print_r($json);
			global $conexion;
			$sql = "INSERT INTO 
			empresa(
				horario,
				estado,
				telefono,
				categoria_empresa,
				razon_comercial,
				direccion,
				provincia,
				distrito,
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
				'".$_SESSION['idempleado']."',
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
				'$json->hor_fin_domingo'

				) ";
			
			$query = $conexion->query($sql);
			$idpedido = $conexion->insert_id;

			return $idpedido;
		}
		public function Modificar($idempresa,$POST){
			global $conexion;

			$id_ubicacion_envio_array = isset($_POST["id_ubicacion_envio_array"]) ? explode(' - ', $_POST["id_ubicacion_envio_array"]) : "";

			$json = json_decode(json_encode($POST));


			$sql = "UPDATE empresa set 
			horario='$json->txtHorario',
			estado='1',
			telefono='$json->txtTelefono',
			categoria_empresa='$json->txtTipoEstablecimiento',
			razon_comercial='$json->txtNombreEstablecimiento',
			direccion='$json->txtDireccionEstablecimiento',
			provincia='$id_ubicacion_envio_array[1]',
			distrito='$id_ubicacion_envio_array[2]',
			idempleado='".$_SESSION['idempleado']."',
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