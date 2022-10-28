<?php
	require "Conexion.php";
	class Persona{

		public function __construct(){
		}
		//Se aumenta la celda apellidos
		public function Registrar($tipo_persona,$nombre,$apellido,$tipo_documento,$num_documento,$direccion_departamento,$direccion_provincia,$direccion_distrito,$direccion_calle,$telefono,$telefono_2,$email,$numero_cuenta,$estado){
			global $conexion;
			$sql = "INSERT INTO persona(tipo_persona,nombre,apellido,tipo_documento,num_documento,direccion_departamento,direccion_provincia,direccion_distrito,direccion_calle,telefono,telefono_2,email,numero_cuenta,estado)
						VALUES('$tipo_persona','$nombre','$apellido','$tipo_documento','$num_documento','$direccion_departamento','$direccion_provincia','$direccion_distrito','$direccion_calle','$telefono','$telefono_2','$email','$numero_cuenta','$estado')";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Modificar($idpersona,$tipo_persona,$nombre,$apellido,$tipo_documento,$num_documento,$direccion_departamento,$direccion_provincia,$direccion_distrito,$direccion_calle,$telefono,$telefono_2,$email,$numero_cuenta,$estado){
			global $conexion;
			$sql = "UPDATE persona set tipo_persona = '$tipo_persona',nombre = '$nombre',apellido='$apellido',tipo_documento='$tipo_documento',num_documento='$num_documento', direccion_departamento = '$direccion_departamento',direccion_provincia='$direccion_provincia',direccion_distrito='$direccion_distrito',direccion_calle='$direccion_calle' ,telefono='$telefono',telefono_2='$telefono_2',email='$email',numero_cuenta='$numero_cuenta',
					estado='$estado'
						WHERE idpersona = $idpersona";
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

		public function ListarCliente(){
			global $conexion;
			$sql = "SELECT * FROM persona order by nombre asc"; /* where tipo_persona='Cliente' & 'Distribuidor' & 'Vip' & 'Tipo 1' & 'Tipo 2' & 'N' order by idpersona desc"; */
			$query = $conexion->query($sql);
			return $query;
		}
	}