<?php

session_start();

require_once "../model/Activos.php";

$objActivos = new Activos();

switch ($_GET["op"]) {



  case 'traerTipoActivo':
    require_once "../model/Pedido.php";

    $objActivos = new Activos();

    $query_Tipo = $objActivos->traerTipoActivo();

    $nuevo = array();
    while ($reg = $query_Tipo->fetch_object()) {
      $nuevo[] = $reg;
    }

    echo json_encode($nuevo);
    
    break;


  case 'actualizar_ultimo_empleado':

    // print_r($_POST);
    $idgestion_activo = $_POST["idgestionActivo"];

    // echo $idgestionActivo;
    $query_Tipo = $objActivos->actualizar_ultimo_empleado($_POST);

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



    echo  json_encode($query_Tipo);

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
				onclick="modicarUltimoUsuarioAsignado(`' . $reg->idgestion_activos . '`)"  title="Editar Empleado Asignado" ><i class="glyphicon glyphicon-pencil
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

    $idgestion_activo = $objActivos->transferirActivo($_POST);

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
    echo  json_encode($idgestion_activo);

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
				onclick="ModificarDetallesActivosView(`' . $reg->idactivo . '`)"  title="Editar Activo" ><i class="glyphicon glyphicon-pencil
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

        "5" => $reg->tipo_activo,
        "6" => $reg->ubicacion == 1 ? 'Arequipa' : 'Lima',
        "7" => $reg->unidad,
        "8" => $reg->marca,
        "9" => $reg->modelo,
        "10" => $reg->serie,
        "11" => $reg->color,
        "12" => $reg->caracteristica,
        "13" => $reg->estado,
        "14" => $reg->t_documento,
        "15" => $reg->precio_compra,
        "16" => $reg->proveedor,
        "17" => $reg->usado_por,
        "18" => $reg->area,



        "19" => $reg->gestionado_por,
        // "18" => $reg->area,


        "20" => '<button class="btn btn-success" data-toggle="tooltip" 
				type="button" 
				onclick="verDetallesActivoUnidad(`' . $reg->idactivo . '`)"  title="Ver Detalle" ><i class="fa fa-eye"></i> </button>
				&nbsp
				<button class="btn btn-warning" data-toggle="tooltip" 
				type="button" 
				onclick="ModificarDetallesActivosView(`' . $reg->idactivo . '`)"  title="Editar Activo" ><i class="glyphicon glyphicon-pencil
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
