<?php

	require "Conexion.php";

	class Tarjeta{
		public function listar(){
			global $conexion;
			$sql = "SELECT * from tarjeta where estado='1'";
			$query = $conexion->query($sql);

			return $query;
		}

		public function Eliminar($idtarjeta){
			global $conexion;
			$sql = "UPDATE tarjeta set estado='0' WHERE idtarjeta = $idtarjeta";
			$query = $conexion->query($sql);

			return $query;
		}
		public function Registrar($descripcion,$numero){
			global $conexion;
			$sql = "INSERT INTO tarjeta(descripcion,estado,codigo)values('$descripcion','1','$numero') ";
			$query = $conexion->query($sql);

			// echo $sql;
			return $query;
		}
		public function Modificar($idtarjeta,$descripcion,$numero){
			global $conexion;
			$sql = "UPDATE tarjeta set descripcion='$descripcion' ,
            codigo='$numero'
            WHERE idtarjeta = $idtarjeta";
			$query = $conexion->query($sql);
			return $query;
		}
    }
    