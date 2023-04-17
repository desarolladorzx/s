<?php
	session_start();
	require_once "../model/Grafico.php";
    $objGlobal = new Grafico(); 
	switch ($_GET["op"]) {
        case "traerVentasAños":
          
            $query = $objGlobal->TraerVentasUltimosAños();
             $reg = $query;

             echo json_encode($reg);
      
        break;
        case "VentasDelMesPorUsuario":
          
            $query = $objGlobal->VentasDelMesPorUsuario();
            $reg = $query;

             echo json_encode($reg);
      
        break;


    }
