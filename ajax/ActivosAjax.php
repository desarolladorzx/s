<?php

session_start();

require_once "../model/Activos.php";

$objActivos = new Activos();

switch ($_GET["op"]) {


  case 'actualizar_ultimo_empleado':

    
    $query_Tipo = $objActivos->actualizar_ultimo_empleado($_POST);

    return 'se ha modificado exitosamente';
    break;
  case 'listaDeEmpleadosPorActivos':
    $id = $_GET["id"];
    $query_Tipo = $objActivos->listaDeEmpleadosPorActivos($id);
    $data = array();
    $i = 1;
    while ($reg = $query_Tipo->fetch_object()) {
      $button = '';
      if ($reg->estado_activo == 'A') {
        $button =
          '<button class="btn btn-warning button_modicar_ultimo_usuario" data-toggle="tooltip" 

          
type="button"
				onclick="modicarUltimoUsuarioAsignado(`' . $reg->idgestion_activos . '`)"  title="Ver Detalle" ><i class="glyphicon glyphicon-pencil
				"></i> </button>';
      }
      $data[] = array(
        "0" => $i,

        "1" => $reg->empleado_asignado,
        "2" => $reg->empleado_uso,
        "3" => $reg->fecha_asignacion,
        "4" => $reg->estado_activo,
        "5" => $button
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

  case 'EliminarActivo':

     $query_Tipo = $objActivos->EliminarActivo($_POST['id']);

    break;
  case 'TrasferirActivo':

  

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
				onclick="verDetallesActivoUnidad(`' . $reg->idactivo . '`)"  title="Ver Detalle" ><i class="fa fa-eye"></i> </button>
				&nbsp
				<button class="btn btn-warning" data-toggle="tooltip" 
				type="button" 
				onclick="ModificarDetallesActivosView(`' . $reg->idactivo . '`)"  title="Ver Detalle" ><i class="glyphicon glyphicon-pencil
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
        "1" => $reg->codigo,
        "2" => $reg->fecha_ingreso,
        "3" => $reg->familia_activo,
        "4" => $reg->tipo_equipo,
        "5" => $reg->unidad,
        "6" => '1',
        "7" => $reg->marca,
        "8" => $reg->modelo,
        "9" => $reg->serie,
        "10" => $reg->color,
        "11" => $reg->caracteristica,
        "12" => $reg->estado,
        "13" => $reg->t_documento,
        "14" => $reg->precio_compra,
        "15" => $reg->proveedor,
        "16" => $reg->area,
        "17" => $reg->gestionado_por,

        // "18" => $reg->area,

        
        "30" => '<button class="btn btn-success" data-toggle="tooltip" 
				type="button" 
				onclick="verDetallesActivoUnidad(`' . $reg->idactivo . '`)"  title="Ver Detalle" ><i class="fa fa-eye"></i> </button>
				&nbsp
				<button class="btn btn-warning" data-toggle="tooltip" 
				type="button" 
				onclick="ModificarDetallesActivosView(`' . $reg->idactivo . '`)"  title="Ver Detalle" ><i class="glyphicon glyphicon-pencil
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


  case "verArchivosActivos":
    $id = $_GET["id"];
    $query_Tipo = $objActivos->verArchivosActivos($id);
    $nuevo = array();
    while ($reg = $query_Tipo->fetch_object()) {
      $nuevo[] = $reg;
    }
    echo  json_encode($nuevo);

    break;
  case "guardarActivo":
    // print_r(json_encode($_POST));
    $hosp  = $objActivos->GuardarActivo($_POST);


    $idgestion_activo = $hosp;
    // $idgestion_activo = 23;

    if (!empty($_FILES["fileupload"])) {
      $file_names = $_FILES['fileupload']['name'];

      for ($i = 0; $i < count($file_names); $i++) {
        $file_name = $file_names[$i];

        $parte = explode(".", $file_name);
        // echo $parte[0]; // nombre del archivo
        // echo $parte[1]; // extension del archivo

        $codigoInterno = strtotime(date('Y-m-d H:i:s'));
        $new_file_name = str_replace(' ', '-', $parte[0] . '-' . $codigoInterno . '.' . $parte[1]);


        $objActivos->RegistrarImagenActivo($idgestion_activo, $new_file_name);

        move_uploaded_file($_FILES["fileupload"]["tmp_name"][$i], "../Files/Activos/" . $new_file_name);
      }
    }





    // echo $hosp;

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
