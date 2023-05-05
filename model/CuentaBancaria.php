<?php

	require "Conexion.php";

	class CuentaBancaria{

		public function traerTipoCuenta()
		{
			global $conexion;
			$sql = "SELECT * from tipo_cuenta ";
			$query = $conexion->query($sql);
			return $query;
		}

		public function traerBanco()
		{
			global $conexion;
			$sql = "SELECT * from banco where activo='1'";
			$query = $conexion->query($sql);
			return $query;
		}

		public function listar(){
			global $conexion;
			$sql = "SELECT * ,tipo_cuenta.descripcion tipo_cuenta,banco.descripcion banco ,banco_cuenta.estado estado,banco_cuenta.descripcion banco_cuenta ,banco_cuenta.numero ,banco_cuenta.cci,banco_cuenta.balance_inicial  FROM banco_cuenta 
			JOIN tipo_cuenta ON tipo_cuenta.idtipo_cuenta=banco_cuenta.idtipo_cuenta
			JOIN banco ON banco.idbanco=banco_cuenta.idbanco ";
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
    