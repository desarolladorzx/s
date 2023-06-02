<?php

session_start();

require_once "../model/RegistroActividad.php";

$obj = new RegistroActividad();


switch ($_GET["op"]) {


    case 'list':
        $query_Tipo = $obj->Listar();
        $data = array();
        $i = 1;
        while ($reg = $query_Tipo->fetch_object()) {

            $data[] = array(
                "0" => $i,
                "1" => $reg->fecha_registro,
                "2" => $reg->usuario,
                "3" => $reg->tipo_cliente,
                "4" => $reg->cliente,
                "5" => $reg->evento,
                '6' => $reg->cartera_estado
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
}
