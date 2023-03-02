<?php

require "Conexion.php";


class Traslados
{

    public function ListarDetalleIngresos($sucursal)
    {
        global $conexion;
        $sql = "SELECT distinct di.iddetalle_ingreso, di.stock_actual, a.nombre as Articulo, di.codigo, di.serie, di.precio_ventapublico, a.imagen, i.fecha,c.nombre as marca, um.nombre as presentacion,di.idarticulo AS idarticulo 
			from ingreso i inner join detalle_ingreso di on di.idingreso = i.idingreso
			inner join articulo a on di.idarticulo = a.idarticulo
			inner join categoria c on a.idcategoria = c.idcategoria
            inner join unidad_medida um on a.idunidad_medida = um.idunidad_medida
			where i.estado = 'A' and i.idsucursal =$sucursal and di.stock_actual > 0 order by fecha asc";
        $query = $conexion->query($sql);
        return $query;
    }


    public function Registrar($almacenInicial, $almacenFinal, $motivoDeTraslado, $idUsuario, $detalle)
    {

        global $conexion;
        $sw = true;
        try {
            $sql = "INSERT into traslados
                (
                descripcion,
                sucursal_id,
                sucursal_destino_id,
                cantidad,
                fecha_registro,
                fecha_modificado,
                id_empleado
                )
                VALUES (
                '$motivoDeTraslado',
                $almacenInicial,
                $almacenFinal,
                10,
                CURRENT_TIMESTAMP(),
                CURRENT_TIMESTAMP(),
                $idUsuario
                )";
            $conexion->query($sql);
            $idpedido = $conexion->insert_id;
            $conexion->autocommit(true);
      
            $idproveedor=7048;

            $sql = "INSERT into ingreso(
                    idusuario,
                    idsucursal,
                    fecha,
                    estado,
                    idproveedor
                     )values (
                        $idUsuario,
                        " . $_SESSION["idusuario"] . ",
                        CURRENT_TIMESTAMP(),
                        'A' ,
                        $idproveedor
                     )
                ";
            $conexion->query($sql);
            $idingreso = $conexion->insert_id;

            $conexion->autocommit(true);
            echo $idingreso;





            // for ($i = 0; $i < count($detalle); $i++) {
            //     $array = explode(",", $detalle[$i]);
            //     $iddetalle_ingreso = $array[0];
            //     $stock_total = $array[5];
            //     $cantidad_de_traslado = $array[6];
            //     $articulo = $array[8];


            //     $stock_actual = $stock_total - $cantidad_de_traslado;

            //     $sql_updateIngreso = "UPDATE detalle_ingreso  set stock_actual=$stock_actual where iddetalle_ingreso=$iddetalle_ingreso";

            //     $conexion->query($sql_updateIngreso);
            // }
            // if ($hosp[0]) {
            //     echo "Pedido Registrado";
            // } else {
            //     echo "No se ha podido registrar el Pedido";
            // }

        } catch (Exception $e) {
            $conexion->rollback();
        }
    }


    public function TableTraslado()
    {
        global $conexion;
        $sql = "SELECT 
            fecha_registro fecha,
            sucursal.razon_social almacen_inicial ,
            sucu.razon_social almacen_destino,
            descripcion motivo_del_traslado,
            cantidad cantidad_total_de_productos,
            cantidad cantidad_total_de_productos
             from traslados
            left join sucursal on sucursal.idsucursal=traslados.sucursal_id 
            left join sucursal  sucu on sucu.idsucursal=traslados.sucursal_destino_id";

        $query = $conexion->query($sql);
        return $query;
    }
}
