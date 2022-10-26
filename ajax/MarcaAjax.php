<?php
	session_start();
	require_once "../model/Marca.php";
	$objMarca = new Marca();
	switch ($_GET["op"]) {

		case 'SaveOrUpdate':			
			$nombre = $_POST["txtNombre"]; // Llamamos al input txtNombre
			if(empty($_POST["txtIdMarca"])){
				
				if($objMarca->Registrar($nombre)){
					echo "Marca Registrada";
				}else{
					echo "Marca no ha podido ser registado.";
				}
			}else{
				$idMarca = $_POST["txtIdMarca"];
				if($objMarca->Modificar($idMarca, $nombre)){
					echo "Marca actualizada";
				}else{
					echo "Informacion de la Marca no ha podido ser actualizada.";
				}
			}
			break;

		case "delete":			
			$id = $_POST["id"];// Llamamos a la variable id del js que mandamos por $.post (Marca.js (Linea 62))
			$result = $objMarca->Eliminar($id);
			if ($result) {
				echo "Eliminado Exitosamente";
			} else {
				echo "No fue Eliminado";
			}
			break;
		
		case "list":
			$query_Tipo = $objMarca->Listar();
			$data = Array();
			$i = 1;
			while ($reg = $query_Tipo->fetch_object()) {
				$data[] = array(
					"id"=>$i,
					"1"=>$reg->nombre,
					"2"=>'<button class="btn btn-warning" data-toggle="tooltip" title="Editar" onclick="cargarDataMarca('.$reg->idmarca.',\''.$reg->nombre.'\')"><i class="fa fa-pencil"></i> </button>&nbsp;'.
					'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarMarca('.$reg->idmarca.')"><i class="fa fa-trash"></i> </button>');
				$i++;
			}
			$results = array(
            "sEcho" => 1,
        	"iTotalRecords" => count($data),
        	"iTotalDisplayRecords" => count($data),
            "aaData"=>$data);
			echo json_encode($results);
			break;
	}