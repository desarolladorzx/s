<?php
session_start();
require_once "../model/Establecimiento.php";
$obj = new Establecimiento();

switch ($_GET["op"]) {


    case 'traerDatosRolVendedor':

        $query_Tipo = $obj->traerDatosRolVendedor();

        $nuevo = array();
        while ($reg = $query_Tipo->fetch_object()) {
            $nuevo[] = $reg;
        }
        echo  json_encode($nuevo);

        break;


    case 'TraerDatosCategoria_empresa':

        $query_Tipo = $obj->TraerDatosCategoria_empresa();

        $nuevo = array();
        while ($reg = $query_Tipo->fetch_object()) {
            $nuevo[] = $reg;
        }
        echo  json_encode($nuevo);



        break;


    case 'GetImagenes':

        $query_total = $obj->GetImagenes($_REQUEST["idempresa"]);

        //var_dump($query_total->fetch_object());
        //exit;

        while ($reg = $query_total->fetch_object()) {
            echo '<li>
                    <a href="./Files/Empresa/' . $reg->imagen . '" target="_blank">
                    <span class="mailbox-attachment-icon has-img">
                    <img src="./Files/Empresa/' . $reg->imagen . '">
                    </span>
                    </a>
                    <div class="mailbox-attachment-info">
                    <a href="./Files/Empresa/' . $reg->imagen . '" class="mailbox-attachment-name" target="_blank">' . $reg->imagen . '</a>
                    </li>';
        }
        break;
    case 'listSelect':
        $query = $obj->listar();

        $nuevo = array();
        while ($reg = $query->fetch_object()) {
            $nuevo[] = $reg;
        }
        echo  json_encode($nuevo);
        break;

    case 'cargarDatos':


        $query = $obj->cargarDatos($_REQUEST['id']);

        $nuevo = array();
        while ($reg = $query->fetch_object()) {
            $nuevo[] = $reg;
        }
        echo  json_encode($nuevo[0]);

        break;
    case 'list':
        $query = $obj->listar();

        $data = array();
        $i = 1;





        while ($reg = $query->fetch_object()) {
            $buttonEditar = '';
            if ($_SESSION["idrol"] == 3 || $_SESSION["idrol"] == 7 || $_SESSION["idrol"] == 2) {
                $buttonEditar = '<button class="btn btn-warning" data-toggle="tooltip" title="Editar" onclick="cargarDataEstablecimiento(' . $reg->idempresa . ')"><i class="fa fa-pencil"></i> </button>&nbsp;';
            }


            $buttonEliminar = '';
            if ($_SESSION["idrol"] == 2) {
                $buttonEliminar = '<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarEstablecimiento(' . $reg->idempresa . ')"><i class="fa fa-trash"></i> </button>';
            }

            $data[] = array(
                "0" => $i,
                "1" => $reg->ubicacion,
                "2" => $reg->categoria_empresa_descripcion,

                "3" => $reg->direccion,
                "4" => $reg->razon_comercial,

                "5" => $reg->nombre,

                "6" => $reg->empleado,
                "7" => $reg->verificacion,
                "8" => $buttonEditar .

                    '<button class="btn btn-success" data-toggle="tooltip" title="Detalles" onclick="cargarDataEstablecimiento(' . $reg->idempresa . ',\'' . false . '\')"><i class="fa fa-eye"></i> </button>&nbsp;' .

                    $buttonEliminar,
                '9' => $reg->categoria_empresa
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

        if (empty($_POST["idEstablecimiento"])) {

            // print_r($_POST);
            $idempresa = $obj->Registrar($_POST);



            if (!empty($_FILES["fileupload"])) {

                $file_names = $_FILES['fileupload']['name'];

                for ($i = 0; $i < count($file_names); $i++) {

                    $file_name = $file_names[$i];

                    $parte = explode(".", $file_name);

                    $codigoInterno = strtotime(date('Y-m-d H:i:s'));
                    $new_file_name = str_replace(' ', '-', $parte[0] . '-' . $codigoInterno . '.' . $parte[1]);


                    $obj->RegistrarImagenes($idempresa, $new_file_name);

                    move_uploaded_file($_FILES["fileupload"]["tmp_name"][$i], "../Files/Empresa/" . $new_file_name);
                }
            }


            if (true) {
                echo "Registrado Exitosamente";
            } else {
                echo "Usuario no ha podido ser registado.";
            }
        } else {
            if ($obj->Modificar($_POST["idEstablecimiento"], $_POST)) {

                if (!empty($_FILES["fileupload"])) {

                    $file_names = $_FILES['fileupload']['name'];

                    for ($i = 0; $i < count($file_names); $i++) {

                        $file_name = $file_names[$i];

                        $parte = explode(".", $file_name);

                        $codigoInterno = strtotime(date('Y-m-d H:i:s'));
                        $new_file_name = str_replace(' ', '-', $parte[0] . '-' . $codigoInterno . '.' . $parte[1]);


                        $obj->RegistrarImagenes($_POST["idEstablecimiento"], $new_file_name);

                        move_uploaded_file($_FILES["fileupload"]["tmp_name"][$i], "../Files/Empresa/" . $new_file_name);
                    }
                }




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
