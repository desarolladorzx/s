<?php
	require "Conexion.php";

	class Marca{
	
		
		public function __construct(){
		}

		public function Registrar($nombre,$prefijo){
			global $conexion;
			$sql = "INSERT INTO marca(nombre,prefijo, estado)
						VALUES('$nombre','$prefijo', 'A')";
			$query = $conexion->query($sql);
			return $query;
		}
		
		public function Modificar($idmarca, $nombre,$prefijo){
			global $conexion;
			$sql = "UPDATE marca set nombre = '$nombre',prefijo='$prefijo'
						WHERE idmarca = $idmarca";
			$query = $conexion->query($sql);
			return $query;
		}
		
		public function Eliminar($idmarca){
			global $conexion;
			$sql = "DELETE FROM marca WHERE idmarca = $idmarca";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Listar(){
			global $conexion;
			$sql = "SELECT * FROM marca order by idmarca desc";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Reporte(){
			global $conexion;
			$sql = "SELECT * FROM marca order by nombre asc";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarMA(){
			global $conexion;
			$sql = "SELECT * FROM marca";
			$query = $conexion->query($sql);
			return $query;
		}
	}