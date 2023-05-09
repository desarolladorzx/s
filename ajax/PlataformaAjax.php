<?php
session_start();
require_once "../model/Plataforma.php";
$obj = new Plataforma();

switch ($_GET["op"]) {

    case 'list':
        $query = $obj->listar();

        $data = array();
        $i = 1;

        while ($reg = $query->fetch_object()) {
            $data[] = array(
                "0" => $i,
                "1" => $reg->descripcion,
                "2" => '<button class="btn btn-warning" data-toggle="tooltip" title="Editar" onclick="cargarDataPlataforma('.$reg->idplataforma.',\''.$reg->descripcion.'\'
           
                )"><i class="fa fa-pencil"></i> </button>&nbsp;'.
                '<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarPlataforma('.$reg->idplataforma.')"><i class="fa fa-trash"></i> </button>',
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
       
        if (empty($_POST["txtIdplataforma"])) {
            if ($obj->Registrar($_POST["txtDescripcion"]
    
            )) {
                echo "Registrado Exitosamente";
            } else {
                echo "Usuario no ha podido ser registado.";
            }
        } else {
            if ($obj->Modificar($_POST["txtIdplataforma"],$_POST["txtDescripcion"],
       
            )) {
                echo "Registrado Exitosamente";
            } else {
                echo "Usuario no ha podido ser registado.";
            }
        }
        break;

    case "delete":

        $id = $_POST["id"];
        $result = $obj->Eliminar($id);
        if ($result) {
            echo "Eliminado Exitosamente";
        } else {
            echo "No fue Eliminado";
        }
        break;
}
