<?php
session_start();
switch ($_GET["op"]) {

    case 'BuscarArticulos':
        require_once "../model/Kardex.php";
        $obj = new Kardex();

        $q = $_GET["q"];

        $query = $obj->BuscarArticulos($q);

        $reg = $query->fetch_all();

        $data=[];

        foreach ($reg as $indice => $valor) {
            
            $d=(object)[
                "id" => $valor[0],
                "texto" => $valor[1]
            ];
            array_push($data,$d);
        }

      
        $return = array(
            'items' => $data
        );

        echo json_encode($return);


        break;
    case 'TraerDatosTablaKardex':
        require_once "../model/Kardex.php";
        $obj = new Kardex();

        $q = $_GET["q"];

        $fecha_desde = $_GET["fecha_desde"];
        $fecha_hasta = $_GET["fecha_hasta"];
        $sucursal = $_GET["sucursal"];

        $queryTablaKardex = $obj->TraerDatosTablaKardex($q,$fecha_desde,$fecha_hasta,$sucursal);


        $data = array();
        $i = 1;
        while ($reg = $queryTablaKardex->fetch_object()) {

            // echo date("d/m/Y H:i:s", strtotime($reg->Fecha));
         
            $data[] = array(
                "0" => $i,
                "1" => date("d/m/Y H:i:s", strtotime($reg->Fecha)),
                "2" => $reg->Movimiento,
                "3" => $reg->Orden,
                "4" => $reg->Cliente,
                "5" => $reg->Proveedor,
                // "6" => $reg->id_kardex,
                "6" => $reg->stock_anterior,
                "7" => $reg->ingreso,
                "8" => $reg->salida,
                "9" => $reg->stock_actual,
                "10" => $reg->sucursal
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
