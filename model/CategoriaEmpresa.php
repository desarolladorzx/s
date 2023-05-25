<?php

	require "Conexion.php";

	class categoria_empresa{
		public function listar(){
			global $conexion;
			$sql = "SELECT * from categoria_empresa where estado='1'";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Eliminar($idcategoria_empresa){
			global $conexion;
			$sql = "UPDATE categoria_empresa set estado='0' WHERE idcategoria_empresa = $idcategoria_empresa";
			$query = $conexion->query($sql);
			return $query;
		}
		public function Registrar($descripcion){
			global $conexion;
			$sql = "INSERT INTO categoria_empresa(descripcion,estado)values('$descripcion','1') ";
			$query = $conexion->query($sql);

			// echo $sql;
			return $query;
		}
		public function Modificar($idcategoria_empresa,$descripcion){
			global $conexion;
			$sql = "UPDATE categoria_empresa set descripcion='$descripcion' WHERE idcategoria_empresa = $idcategoria_empresa";
			$query = $conexion->query($sql);
			return $query;
		}
    }