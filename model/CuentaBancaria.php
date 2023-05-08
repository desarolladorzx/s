<?php

	require "Conexion.php";

	class CuentaBancaria{
		public function traerTipoDivisa()
		{
			global $conexion;
			$sql = "SELECT * from tipo_divisa ";
			$query = $conexion->query($sql);
			return $query;
		}
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
			$sql = "SELECT * ,tipo_cuenta.descripcion tipo_cuenta,banco.descripcion banco ,banco_cuenta.estado estado,banco_cuenta.descripcion banco_cuenta ,
			banco_cuenta.numero ,banco_cuenta.cci,banco_cuenta.balance_inicial  ,tipo_divisa.simbolo
			FROM banco_cuenta 
						
						JOIN tipo_divisa ON tipo_divisa.idtipo_divisa=banco_cuenta.tipo_moneda
						JOIN tipo_cuenta ON tipo_cuenta.idtipo_cuenta=banco_cuenta.idtipo_cuenta
						JOIN banco ON banco.idbanco=banco_cuenta.idbanco 

						where estado='1'
			;";
			$query = $conexion->query($sql);

			return $query;
		}

		public function Eliminar($idbanco_cuenta){
			global $conexion;
			$sql = "UPDATE banco_cuenta set estado=0 WHERE idbanco_cuenta = $idbanco_cuenta";
			$query = $conexion->query($sql);
		
			return $query;
		}
		public function Registrar(
			$nombre_banco_cuenta ,
			$idtipo_cuenta ,
			$idbanco ,
			$numero ,
			$cci ,
			$balance_inicial ,
			$tipo_moneda ,
			$descripcion ,
			$nombre_titular 

			){
			global $conexion;
			$sql = "INSERT INTO 
			banco_cuenta
			(
			nombre_banco_cuenta ,
			idtipo_cuenta ,
			idbanco ,
			numero ,
			cci ,
			balance_inicial ,
			tipo_moneda ,
			descripcion ,
			nombre_titular,
			estado 
			)
			values(
			'$nombre_banco_cuenta' ,
			'$idtipo_cuenta' ,
			'$idbanco' ,
			'$numero' ,
			'$cci' ,
			'$balance_inicial' ,
			'$tipo_moneda' ,
			'$descripcion' ,
			'$nombre_titular',
			'1'
			) ";
			$query = $conexion->query($sql);

			// echo $sql;
			return $query;
		}
		public function Modificar(
			$idbanco_cuenta,
			$nombre_banco_cuenta ,
			$idtipo_cuenta ,
			$idbanco ,
			$numero ,
			$cci ,
			$balance_inicial ,
			$tipo_moneda ,
			$descripcion ,
			$nombre_titular ){
			global $conexion;
			$sql = "UPDATE banco_cuenta set nombre_banco_cuenta='$nombre_banco_cuenta' ,
			idtipo_cuenta='$idtipo_cuenta',
			idbanco= '$idbanco',
			numero='$numero',
			cci ='$cci',
			balance_inicial='$balance_inicial' ,
			tipo_moneda='$tipo_moneda',
			descripcion='$descripcion',
			nombre_titular='$nombre_titular'
            WHERE idbanco_cuenta = $idbanco_cuenta";
			
			$query = $conexion->query($sql);
			return $query;
		}
    }
    