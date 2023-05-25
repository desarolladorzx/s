<?php

session_start();

require_once "../model/Catalogo.php";

$obj = new Catalogo();


switch ($_GET["op"]) {
    case 'Listar':
        $nuevo = array();
    
        $query_prov = $obj->Listar();

        $i = 1;

        while ($reg = $query_prov->fetch_object()) {
            $nuevo[] = $reg;
        }
        echo  json_encode($nuevo);

        break;
}
