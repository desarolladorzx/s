<?php
session_start();
require_once "../model/MotivoTraslado.php";
$obj = new Motivo_traslado();

switch ($_GET["op"]) {

    case 'list':
        $query = $obj->listar();

        $data = array();
        $i = 1;

        while ($reg = $query->fetch_object()) {
            $data[] = array(
                "0" => $i,
                "1" => $reg->codigo,
                "2" => $reg->descripcion,
                "3" => $reg->descuento_stock,
                "4" => '<button class="btn btn-warning" data-toggle="tooltip" title="Editar" onclick="cargarDataMotivoTraslado('.$reg->idmotivo_traslado.',\''.$reg->descripcion.'\'
                ,\''.$reg->codigo.'\'
                ,\''.$reg->descuento_stock.'\'
           
                )"><i class="fa fa-pencil"></i> </button>&nbsp;'.
                '<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarMotivoTraslado('.$reg->idmotivo_traslado.')"><i class="fa fa-trash"></i> </button>',
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
       
        if (empty($_POST["txtIdmotivo_traslado"])) {
            if ($obj->Registrar($_POST["txtDescripcion"],
            $_POST["txtCodigo"],
            $_POST["txtDescuentoStock"],
          
    
            )) {
                echo "Registrado Exitosamente";
            } else {
                echo "Usuario no ha podido ser registado.";
            }
        } else {
            if ($obj->Modificar(
            $_POST["txtIdmotivo_traslado"],
            $_POST["txtDescripcion"],
            $_POST["txtCodigo"],
            $_POST["txtDescuentoStock"],
   
       
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
