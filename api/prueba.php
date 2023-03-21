<?php

require '../model/Conexion.php';

class Prueba{
    function obtenerData(){

        global $conexion;
        $sql ='SELECT * from persona';
        $query=$conexion->query($sql);
        return $query;
    }

}

