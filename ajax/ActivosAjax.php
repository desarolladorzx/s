<?php

session_start();

require_once "../model/Activos.php";

$objActivos = new Activos();

switch ($_GET["op"]) {

	case 'SaveOrUpdate':

		break;
	case 'verDetallesActivoUnidad':
		$id = $_GET["id"];

		$query_Tipo = $objActivos->verDetallesActivoUnidad($id);
		$nuevo = array();
        // while ($reg = $query_Tipo->fetch_object()) {
        //     $nuevo[] = $reg;
        // }
        echo  json_encode($query_Tipo->fetch_object());
        break;


		
		break;

	case 'TrasferirActivo':

		print_r(json_encode($_POST));

		$query_Tipo = $objActivos->transferirActivo($_POST);
		
		break;
	case 'listPorEmpelado':
		// $id=24;
		$id = $_GET["id"];
		$query_Tipo = $objActivos->ListarActivosPorEmpleados($id);
		$data = array();
		$i = 1;


		

        while ($reg = $query_Tipo->fetch_object()) {

			
			$data[] = array(
				"0" => $i,
				"1" => $reg->codigo,
				"2" => $reg->fecha_ingreso,
				"3" => $reg->fecha_finvida,
				"4" => $reg->precio_compra,
				"5" => $reg->marca,
				"6" => $reg->cantidad, 
				"7" => $reg->tipo_equipo,
				"8" => '<button class="btn btn-success" data-toggle="tooltip" 
				type="button" 
				onclick="verDetallesActivoUnidad(`' .$reg->idactivo. '`)"  title="Ver Detalle" ><i class="fa fa-eye"></i> </button>
				&nbsp
				<button class="btn btn-warning" data-toggle="tooltip" 
				type="button" 
				onclick="ModificarDetallesActivosView(`' .$reg->idactivo. '`)"  title="Ver Detalle" ><i class="glyphicon glyphicon-pencil
				"></i> </button>
				'
			);

			$i++;
		}
		$results = array(
			"sEcho" => 1,
			"iTotalRecords" => count($data),
			"iTotalDisplayRecords" => count($data),
			"aaData" => $data
		);
		echo json_encode($results);
		break;

		
	case "list":
		$query_Tipo = $objActivos->Listar();
		$data = array();
		$i = 1;
		while ($reg = $query_Tipo->fetch_object()) {

			$data[] = array(
				"0" => $i,
				"1" => $reg->tipo_activo,
				"2" => $reg->usado_por,
				"3" => $reg->tipo_activo,
				"4" => $reg->gestionado_por,
				"5" => $reg->estado,
				"6" => $reg->etiqueta,
				"7" => $reg->fecha_finvida,
				"8" => '<button class="btn btn-success" data-toggle="tooltip" 
				type="button" 
				onclick="verDetallesActivoUnidad(`' .$reg->idactivo. '`)"  title="Ver Detalle" ><i class="fa fa-eye"></i> </button>
				&nbsp
				<button class="btn btn-warning" data-toggle="tooltip" 
				type="button" 
				onclick="ModificarDetallesActivosView(`' .$reg->idactivo. '`)"  title="Ver Detalle" ><i class="glyphicon glyphicon-pencil
				"></i> </button>
				'
			);
			$i++;
		}
		$results = array(
			"sEcho" => 1,
			"iTotalRecords" => count($data),
			"iTotalDisplayRecords" => count($data),
			"aaData" => $data
		);
		echo json_encode($results);
		break;

	case "guardarActivo":
		// print_r(json_encode($_POST));
		$query_Tipo = $objActivos->GuardarActivo($_POST);

		echo $query_Tipo;

		break;

	case 'optionEmpleados':

		$query_Tipo = $objActivos->ListarEmpleados();
		$nuevo = array();
        while ($reg = $query_Tipo->fetch_object()) {
            $nuevo[] = $reg;
        }
        echo  json_encode($nuevo);
        break;
}
