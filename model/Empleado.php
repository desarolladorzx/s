<?php
require "Conexion.php";

class Empleado
{


	public  function actualizarHijo($Post)
	{

// actualiza  al hijo del empleado segun el iddetalle_empleado_hijo a la tabla detalle_empleado_hijo
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

		// inserta  un nuevo hijo al empleado seleccionado a la tabla  detalle_empleado_hijo
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
		
		where idempleado = '$id' and estado='A'
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

	public  function TraerDatosEmpleado($idempleado) //funcion encargada de ctraer los datos  del empleado segun el  id empleado
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
		// trae los roles de la tabla roles


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
		// renueva el contrato del empleado y cambia de estado a los anteriores contratos
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

		// cambia de estado estado a los anteriroes contratos
		$sql = "UPDATE contrato
			set estado='C'
			where idempleado='$idempleado' ";


		$conexion->query($sql);
// anade un nuevo contrato
		$sql = "INSERT INTO 
				contrato(
					idempleado,
					archivo_contrato_trabajo,
					archivo_dni,
					archivo_cv,
					archivo_rit,
					archivo_antecedentes,
					archivo_declaracion_jurada,
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
		// inserta el primer contrato  al empleado
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

		// inserta un nuevo contrato a la tabla contrato
		$sql = "INSERT INTO 
			contrato(
				idempleado,
				archivo_contrato_trabajo,
				archivo_dni,
				archivo_cv,
				archivo_rit,
				archivo_antecedentes,
				archivo_declaracion_jurada,
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



			// insercion  de nuevo  empleado  a la  tabla  empleado 
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
				celular_contacto,

				nombre_usuario
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
				'$values->txtcelular_contacto',
				'$values->txtNombreUsuario'
			  );";

		$conexion->query($sql);

		$id = $conexion->insert_id;



		// si el empleado  incerto hijos  se anadiran los hijos  a la  carpeta  detalle_empleado_hijos
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

		// return  del idempleado para la  creacion de  contratos 
		return $id;
	}

	public function Modificar(
		$Post,
		$ruta,
		$actualizar_foto
	) {

		$values = json_decode(json_encode($Post));
		

		
		$id_ubicacion_envio_array = isset($Post["id_ubicacion_empleado_array"]) ? explode(' - ', $Post["id_ubicacion_empleado_array"]) : "";


		// actualizacion del empleado  en la base de datos

	
		global $conexion;
		if($actualizar_foto==true){

		

			$trueruta="foto = '$ruta',";
		}else{
			$trueruta='';
			

		}
		$sql = "UPDATE empleado
		SET apellidos = '$values->txtApellidos',
			nombre ='$values->txtNombre',
			tipo_documento = '$values->cboTipo_Documento',
			num_documento ='$values->txtNum_Documento',
			direccion = '$values->txtDireccion',
			telefono ='$values->txtTelefono',
			
			fecha_nacimiento ='$values->txtfecha_nacimiento',
			
			$trueruta
		
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
			celular_contacto = '$values->txtcelular_contacto',

			nombre_usuario = '$values->txtNombreUsuario'
		WHERE idempleado = '$values->txtIdEmpleado'";
		$query = $conexion->query($sql);

	
		return $query;
	}

	public function Eliminar($idempleado)
	{
		global $conexion;
		$sql = "DELETE FROM empleado WHERE idempleado = $idempleado";
		$query = $conexion->query($sql);
		return $query;
	}
	public function EliminarHijo($id)
	{
		// cambia de estado al hijo "C"
		global $conexion;
		$sql = "UPDATE detalle_empleado_hijo set estado='C' WHERE iddetalle_empleado_hijo = $id";
		$query = $conexion->query($sql);
		return $query;
	}
	public function Listar()
	{
		// lista los empleados en la tabla  empleado
		global $conexion;
		$sql = "SELECT 
		CONCAT(departamento.iddepartamento,' - ',provincia.idprovincia, ' - ',distrito.iddistrito) idubicacion
		
		,CONCAT(departamento.descripcion,' - ',provincia.descripcion, ' - ',distrito.descripcion) ubicacion
		
		,CONCAT(empleado.nombre,' ',empleado.apellidos) nombre_completo
		
		,DATEDIFF(fecha_fin_labores, CURDATE())   dias_restantes
		
		,contrato.razon_social
		
		,fecha_inicio_labores
		,rol.r_descripcion puesto_ocupado
		,AREA.descripcion area_funcional
		
		,contrato.*

		,
		

		CASE
           WHEN DATEDIFF(CURDATE(), (SELECT MIN(fecha_inicio_labores) FROM contrato WHERE idempleado = empleado.idempleado)) > 1095 THEN 'Antiguo'
           ELSE 'Nuevo'
       END 


		
		 AS primera_fecha_contrato
		
		FROM empleado
		LEFT JOIN distrito  ON distrito.iddistrito=empleado.iddistrito
				
		LEFT  JOIN  rol  ON rol.r_id=empleado.idrol
		
		LEFT  JOIN AREA ON AREA.idarea=rol.idarea
		left JOIN provincia  ON provincia.idprovincia=empleado.idprovincia
		
		LEFT JOIN (
			SELECT
		    idcontrato,
		    razon_social,
		    fecha_fin_labores,
		    fecha_inicio_labores,
		    estado,
		    idempleado,
		     CASE
        WHEN (
            LENGTH(archivo_dni) = 0 OR
            LENGTH(archivo_cv) = 0 OR
            LENGTH(archivo_antecedentes) = 0 OR
            LENGTH(archivo_declaracion_jurada) = 0 OR
            LENGTH(archivo_contrato_trabajo) = 0 OR
            LENGTH(archivo_rit) = 0
        ) THEN CONCAT(
            CASE WHEN LENGTH(archivo_dni) = 0 THEN 'archivo_dni, ' ELSE '' END,
            CASE WHEN LENGTH(archivo_cv) = 0 THEN 'archivo_cv, ' ELSE '' END,
            CASE WHEN LENGTH(archivo_antecedentes) = 0 THEN 'archivo_antecedentes, ' ELSE '' END,
            CASE WHEN LENGTH(archivo_declaracion_jurada) = 0 THEN 'archivo_declaracion_jurada, ' ELSE '' END,
            CASE WHEN LENGTH(archivo_contrato_trabajo) = 0 THEN 'archivo_contrato_trabajo, ' ELSE '' END,
            CASE WHEN LENGTH(archivo_rit) = 0 THEN 'archivo_rit' ELSE '' END
        )
        ELSE 'No falta ningún archivo'
    END AS mensaje
FROM contrato
		) contrato ON contrato.idempleado =empleado.idempleado AND contrato.estado='A'
		
		left JOIN departamento  ON departamento.iddepartamento=provincia.iddepartamento
		
		order by empleado.idempleado DESC;
		
		";
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
