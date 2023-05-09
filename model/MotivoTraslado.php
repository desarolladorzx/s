<?php

	require "Conexion.php";

	class Motivo_traslado{
		public function listar(){
			global $conexion;
			$sql = "SELECT * from motivo_traslado where estado='A'";
			$query = $conexion->query($sql);

			return $query;
		}

		public function Eliminar($idcuentaBancaria){
			global $conexion;
			$sql = "UPDATE motivo_traslado set estado='C' WHERE idmotivo_traslado = $idcuentaBancaria";
			$query = $conexion->query($sql);

			return $query;
		}
		public function Registrar($descripcion,$codigo,$descuento_stock){
			global $conexion;
			$sql = "INSERT INTO motivo_traslado(descripcion,estado,codigo,descuento_stock)values('$descripcion','A','$codigo','$descuento_stock') ";
			$query = $conexion->query($sql);

			// echo $sql;
			return $query;
		}
		public function Modificar($idmotivo_traslado,$descripcion,$codigo,$descuento_stock){
			global $conexion;
			$sql = "UPDATE motivo_traslado set descripcion='$descripcion' ,
			codigo='$codigo' ,
			descuento_stock='$descuento_stock' 

         
            WHERE idmotivo_traslado = $idmotivo_traslado";
			$query = $conexion->query($sql);
			return $query;
		}
    }
    