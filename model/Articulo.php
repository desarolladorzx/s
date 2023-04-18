<?php
	require "Conexion.php";

	class articulo{
	
		public function __construct(){
		}

		public function Registrar($idmarca, $idcategoria,  $idunidad_medida, $nombre, $descripcion, $imagen,$stockMinimo){
			global $conexion;
			$sql = "INSERT INTO articulo(idmarca, idcategoria,idunidad_medida, nombre, descripcion, imagen, estado,stock_min)
						VALUES($idmarca, $idcategoria, $idunidad_medida, '$nombre','$descripcion', '$imagen', 'A',$stockMinimo)";
			$query = $conexion->query($sql);
			return $query;
		}
		
		public function Modificar($idarticulo, $idmarca, $idcategoria,  $idunidad_medida, $nombre, $descripcion, $imagen,$stockMinimo){
			global $conexion;
			$sql = "UPDATE articulo set idmarca = $idmarca, idcategoria = $idcategoria, idunidad_medida = $idunidad_medida, nombre = '$nombre',
						descripcion = '$descripcion', imagen = '$imagen',stock_min='$stockMinimo'
						WHERE idarticulo = $idarticulo";
			$query = $conexion->query($sql);
			return $query;
		}
		
		public function Eliminar($idarticulo){
			global $conexion;
			$sql = "UPDATE articulo set estado = 'N' WHERE idarticulo = $idarticulo";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Listar(){
			global $conexion;
			$sql = "select a.*, c.nombre as categoria, um.nombre as unidadMedida , m.nombre as marca
			from articulo a inner join categoria c on a.idcategoria = c.idcategoria
			inner join marca m on a.idmarca = m.idmarca 
			inner join unidad_medida um on a.idunidad_medida = um.idunidad_medida where a.estado = 'A' order by idarticulo desc";
			$query = $conexion->query($sql);
			return $query;
		}


		public function Reporte(){
			global $conexion;
			$sql = "select a.*, c.nombre as categoria, um.nombre as unidadMedida 
			from articulo a inner join categoria c on a.idcategoria = c.idcategoria
			inner join unidad_medida um on a.idunidad_medida = um.idunidad_medida where a.estado = 'A' order by a.nombre asc";
			$query = $conexion->query($sql);
			return $query;
		}

	}
