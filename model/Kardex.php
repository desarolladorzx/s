<?php

	require "Conexion.php";
	class Kardex{

		public function BuscarArticulos($q){

            //var_dump($q);exit;

			global $conexion;

			$sql = "SELECT idarticulo AS id,nombre AS texto FROM articulo WHERE CONCAT(nombre,' ',descripcion) LIKE '%".$q."%'";
			$query = $conexion->query($sql);
            //var_dump($reg = $query->fetch_object());exit;

			return $query;
		}


	}