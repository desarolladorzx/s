<?php
	require "Conexion.php";

	class articulo{
	
		public function __construct(){
		}

		public function Registrar($idmarca, $idcategoria,  $idunidad_medida, $nombre, $descripcion, $imagen){
			global $conexion;
			$sql = "INSERT INTO articulo(idmarca, idcategoria,idunidad_medida, nombre, descripcion, imagen, estado)
						VALUES($idmarca, $idcategoria, $idunidad_medida, '$nombre','$descripcion', '$imagen', 'A')";
			$query = $conexion->query($sql);
			return $query;
		}