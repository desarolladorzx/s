<?php
require "Conexion.php";

class Empleado
{


	public  function actualizarHijo($Post)
	{

		global $conexion;

		$values = json_decode(json_encode($Post));
		$sql = "UPDATE detalle_empleado_hijo 

		set
		 nombre_hijo='$values->txtNombre_hijoNuevo',
			apellido_hijo='$values->txtApellido_hijoNuevo',
			dni_hijo='$values->txtDni_hijoNuevo',
			fecha_nacimiento_hijo='$values->txtFechaNacimiento_hijoNuevo'
		
		WHERE iddetalle_empleado_hijo=			'$values->iddetalle_empleado_hijo'

		";
		$query = $conexion->query($sql);

		return $query;
	}
	public function anadirHijo($Post)
	{
		global $conexion;

		$values = json_decode(json_encode($Post));

		$sql = "INSERT 
		INTO detalle_empleado_hijo(
			nombre_hijo,
			apellido_hijo,
			dni_hijo,
			fecha_nacimiento_hijo,
			idempleado,
			estado
		)
		values(
			'$values->txtNombre_hijoNuevo',
			'$values->txtApellido_hijoNuevo',
			'$values->txtDni_hijoNuevo',
			'$values->txtFechaNacimiento_hijoNuevo',
			'$values->idempleado',
			'A'
		)";


		$query = $conexion->query($sql);
		return $query;
	}
	public function ListarHijos($id)
	{
		global $conexion;
		$sql = "SELECT * FROM detalle_empleado_hijo 
		
		where idempleado = '$id'
		order by iddetalle_empleado_hijo desc";
		$query = $conexion->query($sql);
		return $query;
	}



	public function ListarContratos($id)
	{
		global $conexion;
		$sql = "SELECT * FROM contrato 
		
		where idempleado = '$id'
		order by Idcontrato desc";
		$query = $conexion->query($sql);
		return $query;
	}

	public  function TraerDatosEmpleado($idempleado)
	{
		global $conexion;
		$sql = "SELECT *,
			CONCAT(empleado.nombre,' ',empleado.apellidos) empleado
			,CONCAT(departamento.iddepartamento,' - ',provincia.idprovincia, ' - ',distrito.iddistrito) idubicacion
			,CONCAT(departamento.descripcion,' - ',provincia.descripcion, ' - ',distrito.descripcion) ubicacion
			
			,empleado.estado
		

		  from  empleado  
		  
		  LEFT JOIN distrito  ON distrito.iddistrito=empleado.iddistrito
			left JOIN provincia  ON provincia.idprovincia=empleado.idprovincia
			left JOIN departamento  ON departamento.iddepartamento=provincia.iddepartamento


		  WHERE idempleado='
		$idempleado
		' ";

		$query = $conexion->query($sql);

		return  $query;
	}
	public function traerRol()
	{
		global $conexion;


		$sql = "SELECT *  from rol";

		$query = $conexion->query($sql);

		// echo $sql;
		return $query;
	}
	public function __construct()
	{
	}
	public function RenovarContrato(
		$idempleado,
		$new_file_name_contrato,
		$new_file_name_dni,
		$new_file_name_cv,
		$new_file_name_RIT,
		$new_file_name_antecedentes,
		$declaracion_jurada,
		$fecha_inicio_labores,
		$fecha_fin_labores,
		$razon_social
	) {

		global $conexion;

		$sql = "UPDATE contrato
			set estado='C'
			where idempleado='$idempleado' ";


		$conexion->query($sql);

		$sql = "INSERT INTO 
				contrato(
					idempleado,
					contrato_trabajo,
					dni,
					cv,
					rit,
					antecedentes,
					declaracion_jurada,
					fecha_creacion,
					idempleado_creacion,
					estado,
					fecha_inicio_labores,
					fecha_fin_labores,
					razon_social
				)
				values(
					'$idempleado',
					'$new_file_name_contrato',
					'$new_file_name_dni',
					'$new_file_name_cv',
					'$new_file_name_RIT',
					'$new_file_name_antecedentes',
					'$declaracion_jurada',
					CURRENT_TIMESTAMP(), 
					" . $_SESSION["idempleado"] . ", 
					'A',
					'$fecha_inicio_labores',
					'$fecha_fin_labores',
					'$razon_social'
					)
					";

		echo $sql;
		$query = $conexion->query($sql);
	}
	public  function RegistrarContrato(

		$idempleado,
		$new_file_name_contrato,
		$new_file_name_dni,
		$new_file_name_cv,
		$new_file_name_RIT,
		$new_file_name_antecedentes,
		$declaracion_jurada,
		$fecha_inicio_labores,
		$fecha_fin_labores,
		$razon_social
	) {

		global $conexion;


		$sql = "INSERT INTO 
			contrato(
				idempleado,
				contrato_trabajo,
				dni,
				cv,
				rit,
				antecedentes,
				declaracion_jurada,
				fecha_creacion,
				idempleado_creacion,
				estado,
				fecha_inicio_labores,
				fecha_fin_labores,
				razon_social
			)
			values(
				'$idempleado',
				'$new_file_name_contrato',
				'$new_file_name_dni',
				'$new_file_name_cv',
				'$new_file_name_RIT',
				'$new_file_name_antecedentes',
				'$declaracion_jurada',
				CURRENT_TIMESTAMP(), 
				" . $_SESSION["idempleado"] . ", 
				'A',
				'$fecha_inicio_labores',
				'$fecha_fin_labores',
				'$razon_social'
				)
				";

		// echo $sql;
		$query = $conexion->query($sql);
	}
	public function AgregarHijos()
	{
	}
	public function Registrar(
		$Post,
		$ruta
	) {

		$values = json_decode(json_encode($Post));
		global $conexion;


		$id_ubicacion_envio_array = isset($Post["id_ubicacion_empleado_array"]) ? explode(' - ', $Post["id_ubicacion_empleado_array"]) : "";


		$sql = "INSERT INTO empleado (
				apellidos,
				nombre,
				tipo_documento,
				num_documento,
				direccion,
				telefono,
				email,
				fecha_nacimiento,
				foto,
				login,
				clave,
				idrol,
				email_personal,
				
				telefono_personal,
				
				sexo,
		
				estado,
				idprovincia,
				iddistrito,
				estado_civil,
				nombre_conyugue,
				hijos,
				nombre_contacto,
				celular_contacto
			  )
			  VALUES (
				'$values->txtApellidos',
				'$values->txtNombre',
				'$values->cboTipo_Documento',
				'$values->txtNum_Documento',
				'$values->txtDireccion',
				'$values->txtTelefono',
				'$values->txtLogin',
				'$values->txtfecha_nacimiento',
				'$ruta',
				'$values->txtLogin',
				'" . md5($values->txtClave) . "',
				'$values->txtRol',
				'$values->txtEmail',
				'$values->txtTelefono',
				
				'$values->optionsRadios',
			
				'$values->txtEstado',
				

				'$id_ubicacion_envio_array[1]',
				'$id_ubicacion_envio_array[2]',
				'$values->txtEstado_civil',
				'$values->txtnombre_conyugue',
				'$values->txtcant_hijos',
				'$values->txtnombre_contacto',
				'$values->txtcelular_contacto'
			  );";
		// echo $sql;	
		$conexion->query($sql);

		$id = $conexion->insert_id;


		if (isset($values->hijos)) {
			$array = $values->hijos;
			foreach ($array as $element) {
				$elemento = explode(",", $element);

				$sql = "INSERT 
		INTO detalle_empleado_hijo(
			nombre_hijo,
			apellido_hijo,
			dni_hijo,
			fecha_nacimiento_hijo,
			idempleado,
			estado
		)
		values(
			'$elemento[0]',
			'$elemento[1]',
			'$elemento[2]',
			'$elemento[3]',
			'$id',
			'A'
		)
	
		";
				$conexion->query($sql);
			}
		}


		return $id;
	}

	public function Modificar(
		$Post,
		$ruta
	) {

		$values = json_decode(json_encode($Post));
		

		
		$id_ubicacion_envio_array = isset($Post["id_ubicacion_empleado_array"]) ? explode(' - ', $Post["id_ubicacion_empleado_array"]) : "";


		global $conexion;
		$sql = "UPDATE empleado
		SET apellidos = '$values->txtApellidos',
			nombre ='$values->nombre',
			tipo_documento = '$values->cboTipo_Documento',
			num_documento ='$values->txtNum_Documento',
			direccion = '$values->txtDireccion',
			telefono ='$values->txtTelefono',
			email = '$values->txtLogin',
			fecha_nacimiento ='$values->txtfecha_nacimiento',
			foto = '$ruta',
			login = '$values->txtLogin',
			-- clave = 'Nueva clave',
			idrol = '$values->txtRol',
			email_personal = '$values->txtEmail',
			
			telefono_personal = '$values->txtTelefono',
			
			sexo = '$values->optionsRadios',
		
			estado ='$values->txtEstado',
			iddistrito = '$id_ubicacion_envio_array[2]',
				
			idprovincia = '$id_ubicacion_envio_array[1]',
			estado_civil ='$values->txtEstado_civil',
			nombre_conyugue = '$values->txtnombre_conyugue',
			hijos = '$values->txtcant_hijos',
			nombre_contacto = '$values->txtnombre_contacto',
			celular_contacto = '$values->txtcelular_contacto'
		WHERE idempleado = '$values->txtIdEmpleado'";
		$query = $conexion->query($sql);

		echo $sql;
		return $query;
	}

	public function Eliminar($idempleado)
	{
		global $conexion;
		$sql = "DELETE FROM empleado WHERE idempleado = $idempleado";
		$query = $conexion->query($sql);
		return $query;
	}

	public function Listar()
	{
		global $conexion;
		$sql = "SELECT *,

		CONCAT(departamento.iddepartamento,' - ',provincia.idprovincia, ' - ',distrito.iddistrito) idubicacion
		,CONCAT(departamento.descripcion,' - ',provincia.descripcion, ' - ',distrito.descripcion) ubicacion


		
		 FROM empleado
		
		
		LEFT JOIN distrito  ON distrito.iddistrito=empleado.iddistrito
		
		left JOIN provincia  ON provincia.idprovincia=empleado.idprovincia

		left JOIN departamento  ON departamento.iddepartamento=provincia.iddepartamento


		 order by idempleado desc";
		$query = $conexion->query($sql);
		return $query;
	}

	public function Reporte()
	{
		global $conexion;
		$sql = "SELECT * FROM empleado order by apellidos asc";
		$query = $conexion->query($sql);
		return $query;
	}
}
