<?php
	require "Conexion.php";

	class RegistroActividad{
	
		
		public function Listar(){
			global $conexion;
			$sql = "SELECT * 
            ,CONCAT(asignado.nombre,' ',asignado.apellidos) usuario  
            
            ,CONCAT(asignador.nombre,' ',asignador.apellidos) evento 
            
            ,CONCAT(persona.nombre,' ',persona.apellido) cliente 
            ,persona.tipo_persona tipo_cliente
            ,cartera_cliente.fecha_registro fecha_registro 
            ,cartera_cliente.estado cartera_estado
            FROM cartera_cliente 
            
            JOIN empleado asignado ON asignado.idempleado =cartera_cliente.idempleado
            
            JOIN empleado asignador ON asignador.idempleado =cartera_cliente.idempleado_asignado
            
            JOIN persona ON persona.idpersona=cartera_cliente.idcliente
            
            order by idcartera_cliente DESC;
            ";
			$query = $conexion->query($sql);
			return $query;
		}
    
    
    }