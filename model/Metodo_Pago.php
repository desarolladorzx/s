<?php

	require "Conexion.php";

	class Metodo_Pago{
		public function listar(){
			global $conexion;
			$sql = "SELECT * from tipo_metodo_pago ";
			$query = $conexion->query($sql);

			return $query;
		}

		public function Eliminar($idcuentaBancaria){
			global $conexion;
			$sql = "UPDATE metodo_pago set estado='C' WHERE idmetodo_pago = $idcuentaBancaria";
			$query = $conexion->query($sql);

			return $query;
		}
		public function Registrar($descripcion,$codigo){
			global $conexion;
			$sql = "INSERT INTO metodo_pago(descripcion,estado,codigo)values('$descripcion','A','$codigo') ";
			$query = $conexion->query($sql);

			// echo $sql;
			return $query;
		}
		public function Modificar($idmetodo_pago,$descripcion,$codigo){
			global $conexion;
			$sql = "UPDATE metodo_pago set descripcion='$descripcion' ,
            codigo='$codigo'
            WHERE idmetodo_pago = $idmetodo_pago";
			$query = $conexion->query($sql);
			return $query;
		}
    }
    