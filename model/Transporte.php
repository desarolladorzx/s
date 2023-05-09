<?php

	require "Conexion.php";

	class Transporte{
		public function listar(){
			global $conexion;
			$sql = "SELECT * from transporte where estado='A'";
			$query = $conexion->query($sql);

			return $query;
		}

		public function Eliminar($idcuentaBancaria){
			global $conexion;
			$sql = "UPDATE transporte set estado='C' WHERE idtransporte = $idcuentaBancaria";
			$query = $conexion->query($sql);

			return $query;
		}
		public function Registrar($descripcion){
			global $conexion;
			$sql = "INSERT INTO transporte(descripcion,estado)values('$descripcion','A') ";
			$query = $conexion->query($sql);

			// echo $sql;
			return $query;
		}
		public function Modificar($idtransporte,$descripcion){
			global $conexion;
			$sql = "UPDATE transporte set descripcion='$descripcion' 
         
            WHERE idtransporte = $idtransporte";
			$query = $conexion->query($sql);
			return $query;
		}
    }
    