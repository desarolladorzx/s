<?php

	require "Conexion.php";

	class TipoDivisa{
		public function listar(){
			global $conexion;
			$sql = "SELECT * from tipo_divisa where activo='1'";
			$query = $conexion->query($sql);

			return $query;
		}

		public function Eliminar($idtipo_divisa){
			global $conexion;
			$sql = "UPDATE tipo_divisa set activo='0' WHERE idtipo_divisa = $idtipo_divisa";
			$query = $conexion->query($sql);

			return $query;
		}
		public function Registrar($descripcion,$numero){
			global $conexion;
			$sql = "INSERT INTO tipo_divisa(descripcion,activo,simbolo)values('$descripcion','1','$numero') ";
			$query = $conexion->query($sql);

			// echo $sql;
			return $query;
		}
		public function Modificar($idtipo_divisa,$descripcion,$numero){
			global $conexion;
			$sql = "UPDATE tipo_divisa set descripcion='$descripcion' ,
            simbolo='$numero'
            WHERE idtipo_divisa = $idtipo_divisa";
			$query = $conexion->query($sql);
			return $query;
		}
    }
