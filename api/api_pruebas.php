<?php

include_once 'prueba.php';


class ApiPrueba
{

    function getAll()
    {
        $prueba = new Prueba();
        $pruebas = array();
        $pruebas['items'] = array();

        $result = $prueba->obtenerData();


     

        // Verificar si se encontraron registros
        if ($result->num_rows > 0) {
            // Crear un array con los resultados
            $usuarios = array();
            while ($row = $result->fetch_assoc()) {
                $usuarios[] = $row;
            }

            // Formatear la respuesta como JSON
            $json =json_encode(["items" =>  $usuarios]);
            
           

            // Configurar las cabeceras de respuesta
            header('Access-Control-Allow-Origin: *');
            header('Content-Type: application/json');

            // Enviar la respuesta al usuario 
            echo $json;
        } else {
            // Si no se encontraron registros, enviar una respuesta vacía
            header('Access-Control-Allow-Origin: *');
            header('Content-Type: application/json');
            echo json_encode(array());
        }


        // if($res->rowCount()){
        //     while(){

        //     }
        // }
    }
}
$pru=new ApiPrueba ;
$pru->getAll();