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
        case "TraerVentasSemanalesUltimosAños":
          
            $query = $objGlobal->TraerVentasSemanalesUltimosAños();
             $reg = $query;

             echo json_encode($reg);
      
        break;
        case "VentasDelMesPorUsuario":
          
            $query = $objGlobal->VentasDelMesPorUsuario();
            $reg = $query;

             echo json_encode($reg);
      
        break;
        case "ProductosVendidosMesTotal":
          
            $query = $objGlobal->ProductosVendidosAnoTotal();
            $reg = $query;
            $nuevo = array();
            while ($reg = $query->fetch_object()) {
                $nuevo[] = $reg;
            }
            echo  json_encode($nuevo);

      
        break;
        case "ProductosVendidosMesTotalDinero":
          
            $query = $objGlobal->ProductosVendidosAnoTotalPorDinero();
            $reg = $query;
            $nuevo = array();
            while ($reg = $query->fetch_object()) {
                $nuevo[] = $reg;
            }
            echo  json_encode($nuevo);

      
        break;


    }
    