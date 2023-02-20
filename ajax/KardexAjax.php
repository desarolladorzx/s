<?php
session_start();
switch ($_GET["op"]) {

    case 'BuscarArticulos':
    require_once "../model/Kardex.php";
    $obj= new Kardex();

        $q = $_GET["q"];

        $query = $obj->BuscarArticulos($q);

        $reg = $query->fetch_object();

        while ($reg = $query->fetch_object()) {
            $data[] = array(
                "id"=>$reg->id,
                "texto"=>$reg->texto);
        }

        $return = array(
		    'items' => $data
		);

		echo json_encode($return);

        //var_dump($obj->BuscarArticulos($q));exit;

        break;
        

}