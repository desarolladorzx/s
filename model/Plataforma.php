<?php

	require "Conexion.php";

	class Plataforma{
		public function listar(){
			global $conexion;
			$sql = "SELECT * from plataforma where estado='A'";
			$query = $conexion->query($sql);

			return $query;
		}

		public function Eliminar($idplataforma){
			global $conexion;
			$sql = "UPDATE plataforma set estado='C' WHERE idplataforma = $idplataforma";
			$query = $conexion->query($sql);

			return $query;
		}
		public function Registrar($descripcion){
			global $conexion;
			$sql = "INSERT INTO plataforma(descripcion,estado)values('$descripcion','A') ";
			$query = $conexion->query($sql);

			// echo $sql;
			return $query;
		}
		public function Modificar($idplataforma,$descripcion){
			global $conexion;
			$sql = "UPDATE plataforma set descripcion='$descripcion' 
         
            WHERE idplataforma = $idplataforma";
			$query = $conexion->query($sql);
			return $query;
		}
    }
    