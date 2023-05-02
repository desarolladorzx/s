<?php

	require "Conexion.php";

	class CuentaBancaria{
		public function listar(){
			global $conexion;
			$sql = "SELECT * from cuenta_bancaria where estado='A'";
			$query = $conexion->query($sql);

			return $query;
		}

		public function Eliminar($idcuentaBancaria){
			global $conexion;
			$sql = "UPDATE cuenta_bancaria set estado='C' WHERE idcuenta_bancaria = $idcuentaBancaria";
			$query = $conexion->query($sql);

			return $query;
		}
		public function Registrar($descripcion,$numero){
			global $conexion;
			$sql = "INSERT INTO cuenta_bancaria(descripcion,estado,numero)values('$descripcion','A','$numero') ";
			$query = $conexion->query($sql);

			// echo $sql;
			return $query;
		}
		public function Modificar($idbanco,$descripcion,$numero){
			global $conexion;
			$sql = "UPDATE cuenta_bancaria set descripcion='$descripcion' ,
            numero='$numero'
            WHERE idcuenta_bancaria = $idbanco";
			$query = $conexion->query($sql);
			return $query;
		}
    }
    