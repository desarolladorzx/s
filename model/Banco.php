<?php

	require "Conexion.php";

	class Banco{
		public function listar(){
			global $conexion;
			$sql = "SELECT * from banco_cuenta where estado='1'";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Eliminar($idbanco){
			global $conexion;
			$sql = "UPDATE banco_cuenta set estado='C' WHERE idbanco = $idbanco";
			$query = $conexion->query($sql);
			return $query;
		}
		public function Registrar($descripcion){
			global $conexion;
			$sql = "INSERT INTO banco_cuenta(descripcion,estado)values('$descripcion','A') ";
			$query = $conexion->query($sql);

			// echo $sql;
			return $query;
		}
		public function Modificar($idbanco,$descripcion){
			global $conexion;
			$sql = "UPDATE banco_cuenta set descripcion='$descripcion' WHERE idbanco = $idbanco";
			$query = $conexion->query($sql);
			return $query;
		}
    }