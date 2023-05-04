<?php
session_start();
require_once "../model/Banco.php";
$obj = new Banco();

switch ($_GET["op"]) {

    case 'listSelect':
        $query = $obj->listar();

        $nuevo = array();
        while ($reg = $query->fetch_object()) {
            $nuevo[] = $reg;
        }
        echo  json_encode($nuevo);
        break;

        
    case 'list':
        $query = $obj->listar();

        $data = array();
        $i = 1;

        while ($reg = $query->fetch_object()) {
            $data[] = array(
                "0" => $i,
                "1" => $reg->descripcion,
                "2" => '<button class="btn btn-warning" data-toggle="tooltip" title="Editar" onclick="cargarDataBanco('.$reg->idbanco.',\''.$reg->descripcion.'\')"><i class="fa fa-pencil"></i> </button>&nbsp;'.
                '<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarBanco('.$reg->idbanco.')"><i class="fa fa-trash"></i> </button>',
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

        if (empty($_POST["txtIdbanco"])) {
            if ($obj->Registrar($_POST["txtDescripcion"])) {
                echo "Registrado Exitosamente";
            } else {
                echo "Usuario no ha podido ser registado.";
            }
        } else {
            if ($obj->Modificar($_POST["txtIdbanco"],$_POST["txtDescripcion"])) {
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
