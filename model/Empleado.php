<?php
	require "Conexion.php";

	class Empleado{
	
		
		public function __construct(){
		}

		public function Registrar($apellidos,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$fecha_nacimiento,$foto, $login, $clave,$estado
		,$cargo
		,$fecha_ingreso
		,$sexo
		){
			global $conexion;
			$sql = "INSERT INTO empleado(
			apellidos
			,nombre
			,tipo_documento
			,num_documento
			,direccion
			,telefono
			,email
			,fecha_nacimiento
			,foto
			,login
			,clave
			,estado
			,cargo
			,fecha_ingreso
			,sexo
			)
						VALUES(
							'$apellidos'
							,'$nombre'
							,'$tipo_documento'
							,'$num_documento'
							,'$direccion'
							,'$telefono'
							,'$email'
							,'$fecha_nacimiento'
							,'$foto'
							,'$login'
							,'$clave'
							,'$estado'
							,'$cargo'
							,'$fecha_ingreso'
							,'$sexo'
						)";
			// echo $sql;	
			$query = $conexion->query($sql);
			return $query;
		}
		
		public function Modificar($idempleado,$apellidos,$nombre, $tipo_documento,$num_documento,$direccion,$telefono,$email,$fecha_nacimiento,$foto, $login, $clave,$estado
		,$cargo
		,$fecha_ingreso
		,$sexo
		){
			global $conexion;
			$sql = "UPDATE empleado set apellidos = '$apellidos',nombre = '$nombre',tipo_documento='$tipo_documento',num_documento='$num_documento', direccion = '$direccion' ,telefono	='$telefono',email='$email',fecha_nacimiento='$fecha_nacimiento',foto='$foto',cargo='$cargo',fecha_ingreso='$fecha_ingreso',sexo='$sexo',
					login = '$login', clave = '$clave' ,estado='$estado'
						WHERE idempleado = $idempleado";
			$query = $conexion->query($sql);

			// echo $sql;
			return $query;
		}
		
		public function Eliminar($idempleado){
			global $conexion;
			$sql = "DELETE FROM empleado WHERE idempleado = $idempleado";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Listar(){
			global $conexion;
			$sql = "SELECT * FROM empleado order by idempleado desc";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Reporte(){
			global $conexion;
			$sql = "SELECT * FROM empleado order by apellidos asc";
			$query = $conexion->query($sql);
			return $query;
		}

		

	}
