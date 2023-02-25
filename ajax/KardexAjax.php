<?php
session_start();
switch ($_GET["op"]) {

    case 'BuscarArticulos':
        require_once "../model/Kardex.php";
        $obj = new Kardex();

        $q = $_GET["q"];

        $query = $obj->BuscarArticulos($q);

        $reg = $query->fetch_object();

        while ($reg = $query->fetch_object()) {
            $data[] = array(
                "id" => $reg->id,
                "texto" => $reg->texto
            );
        }
        // if(@data){

        // }
        $return = array(
            'items' => $data
        );

        echo json_encode($return);

        //var_dump($obj->BuscarArticulos($q));exit;

        break;
    case 'TraerDatosTablaKardex':
        require_once "../model/Kardex.php";
        $obj = new Kardex();

        $q = $_GET["q"];
        $queryTablaKardex = $obj->TraerDatosTablaKardex($q);
        $data = array();
        $i = 1;
        while ($reg = $queryTablaKardex->fetch_object()) {
            $data[] = array(
                "0" => $i,
                "1" => $reg->Fecha,
                "2" => $reg->Movimiento,
                "3" => $reg->Orden,
                "4" => $reg->Cliente,
                "5" => $reg->Proveedor,
                // "6" => $reg->id_kardex,
                "6" => $reg->stock_anterior,
                "7" => $reg->cantidad,
                "8" => $reg->stock_actual
            );
            //$reg->estado,
            // "8"=>($reg->estado=="A")?'<span class="badge bg-green">ACEPTADO</span>':'<span class="badge bg-red">CANCELADO</span>',
            // "9"=>($reg->estado=="A")?'<button class="btn btn-success" data-toggle="tooltip" title="Ver Detalle" onclick="cargarDataIngreso('.$reg->idingreso.',\''.$reg->serie_comprobante.'\',\''.$reg->num_comprobante.'\',\''.$reg->impuesto.'\',\''.$reg->total.'\',\''.$reg->idingreso.'\',\''.$reg->proveedor.'\',\''.$reg->tipo_comprobante.'\')" ><i class="fa fa-eye"></i> </button>&nbsp'.
            // '<button class="btn btn-danger" data-toggle="tooltip" title="Anular Ingreso" onclick="cancelarIngreso('.$reg->idingreso.')" ><i class="fa fa-times-circle"></i> </button>&nbsp'.
            // '<a href="./Reportes/exIngreso.php?id='.$reg->idingreso.'" class="btn btn-primary" data-toggle="tooltip" title="Imprimir" target="blanck" ><i class="fa fa-file-text"></i> </a>':'<button class="btn btn-success" data-toggle="tooltip" title="Ver Detalle" onclick="cargarDataIngreso('.$reg->idingreso.',\''.$reg->serie_comprobante.'\',\''.$reg->num_comprobante.'\',\''.$reg->impuesto.'\',\''.$reg->total.'\',\''.$reg->idingreso.'\',\''.$reg->proveedor.'\',\''.$reg->tipo_comprobante.'\')" ><i class="fa fa-eye"></i> </button>&nbsp'.
            // '<a href="./Reportes/exIngreso.php?id='.$reg->idingreso.'" class="btn btn-primary" data-toggle="tooltip" title="Imprimir" target="blanck" ><i class="fa fa-file-text"></i> </a>');
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
