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


            $idproveedor = 7048;
            $impuesto = 18;

            $total = 0;
            $totalTraslado = 0;
            for ($i = 0; $i < count($detalle); $i++) {
                $array = explode(",", $detalle[$i]);
                $iddetalle_ingreso = $array[0];

                $cantidad_de_traslado = $array[6];

                $sql_select_detalle_ingreso = "SELECT * from  detalle_ingreso where iddetalle_ingreso=$iddetalle_ingreso";

                $detalle_ingreso = $conexion->query($sql_select_detalle_ingreso)->fetch_object();

                $total = $total + $cantidad_de_traslado;
                $totalTraslado = $totalTraslado + 1;
            }


            $sql = "INSERT into ingreso(
                idusuario,
                idsucursal,
                fecha,
                estado,
                idproveedor,
                tipo_comprobante,
                serie_comprobante,
                num_comprobante,
                impuesto,
                total
                
        )values (
            $idUsuario,
            $almacenFinal,
                CURRENT_TIMESTAMP(),
                'A',
                $idproveedor,
                'TRASLADO',
                'NT',
                1,
                $impuesto,
                $total
        )";
            $conexion->query($sql);
            $idingreso = $conexion->insert_id;

            $conexion->autocommit(true);

            for ($i = 0; $i < count($detalle); $i++) {
                $array = explode(",", $detalle[$i]);
                $iddetalle_ingreso = $array[0];

                $stock_total = $array[5];
                $cantidad_de_traslado = $array[6];
                $articulo = $array[8];

                $stock_actual = $stock_total - $cantidad_de_traslado;
                $sql_select_detalle_ingreso = "SELECT * from  detalle_ingreso where iddetalle_ingreso=$iddetalle_ingreso";
                $detalle_ingreso = $conexion->query($sql_select_detalle_ingreso)->fetch_object();


                // var_dump($array) ;
                // echo  "<br>" ;
                $suma_ingreso_anterior_sucursal_final = "SELECT SUM(stock_actual) stock from detalle_ingreso
                join ingreso on ingreso.idingreso=detalle_ingreso.idingreso
                where idarticulo=$detalle_ingreso->idarticulo and idsucursal=$almacenFinal and estado='A'";

                $suma_ingreso_anterior_sucursal_inicial = "SELECT SUM(stock_actual) stock from detalle_ingreso
                    join ingreso on ingreso.idingreso=detalle_ingreso.idingreso
                    where idarticulo=$detalle_ingreso->idarticulo and idsucursal=$almacenInicial and estado='A'";


                $rpta_sql_suma_ingreso_sucursal_final = $conexion->query($suma_ingreso_anterior_sucursal_final)->fetch_object()->stock;



                $rpta_sql_suma_ingreso_sucursal_inicial = $conexion->query($suma_ingreso_anterior_sucursal_inicial)->fetch_object()->stock;

                $suma_stock_actual =  $cantidad_de_traslado;

                $kardexDetalle_ingreso = 0;
                $detallePedido = 0;




                $sql_updateIngreso = "UPDATE detalle_ingreso  set stock_actual=$stock_actual where iddetalle_ingreso=$iddetalle_ingreso";
                $conexion->query($sql_updateIngreso);

                $sql_insert_ingreso =
                    "INSERT into detalle_ingreso(
                    idingreso,
                    idarticulo,
                    stock_ingreso,
                    stock_actual,
                    codigo,
                    precio_compra,
                    precio_ventadistribuidor,
                    precio_ventapublico,
                    serie,
                    descripcion   
                ) values (
                    $idingreso,
                    $detalle_ingreso->idarticulo,
                    $cantidad_de_traslado,
                    $cantidad_de_traslado,
                    '$detalle_ingreso->codigo',
                    '$detalle_ingreso->precio_compra',
                    '$detalle_ingreso->precio_ventadistribuidor',
                    '$detalle_ingreso->precio_ventapublico',
                    '$detalle_ingreso->serie',
                    '$detalle_ingreso->descripcion'
                )";
                $conexion->query($sql_insert_ingreso);

                $id_nuevo_detalle_ingreso = $conexion->insert_id;

                $suma_ingreso_nuevo_almacen_final = "SELECT SUM(stock_actual) stock from detalle_ingreso
                join ingreso on ingreso.idingreso=detalle_ingreso.idingreso
                where idarticulo=$detalle_ingreso->idarticulo and idsucursal=$almacenInicial and estado='A'";

                $rpta_sql_suma_ingreso_nuevo_inicial = $conexion->query($suma_ingreso_nuevo_almacen_final)->fetch_object()->stock;

                $suma_ingreso_nuevo_almacen_inicial = "SELECT SUM(stock_actual) stock from detalle_ingreso
            join ingreso on ingreso.idingreso=detalle_ingreso.idingreso
            where idarticulo=$detalle_ingreso->idarticulo and idsucursal=$almacenFinal and estado='A'";

                $rpta_sql_suma_ingreso_nuevo_final = $conexion->query($suma_ingreso_nuevo_almacen_inicial)->fetch_object()->stock;



                $rpta_sql_suma_ingreso_sucursal_inicial_not_null = ($rpta_sql_suma_ingreso_sucursal_inicial !== null) ? $rpta_sql_suma_ingreso_sucursal_inicial : 0;


                $rpta_sql_suma_ingreso_nuevo_inicial_not_null = ($rpta_sql_suma_ingreso_nuevo_inicial !== null) ? $rpta_sql_suma_ingreso_nuevo_inicial : 0;



                $rpta_sql_suma_ingreso_nuevo_final_not_null = ($rpta_sql_suma_ingreso_nuevo_final !== null) ? $rpta_sql_suma_ingreso_nuevo_final : 0;

                $sqlKardex = "INSERT INTO kardex(
                    id_sucursal,
                    fecha_emision,
                    tipo,
                    id_articulo,
                    id_detalle_ingreso,
                    stock_anterior,
                    cantidad,
                    stock_actual,
                    fecha_creacion,
                    fecha_modificacion,
                    id_detalle_pedido
                         )
                VALUES(
                    '$almacenInicial',
                    CURRENT_TIMESTAMP(),
                     'salida por traslado',
                     '$detalle_ingreso->idarticulo',
                     '" . $kardexDetalle_ingreso . "',
                      '" . $rpta_sql_suma_ingreso_sucursal_inicial_not_null . "',
                    '" . $cantidad_de_traslado . "',
                    '" . $rpta_sql_suma_ingreso_nuevo_inicial_not_null . "',
                    CURRENT_TIMESTAMP(),
                    CURRENT_TIMESTAMP(),
                    '" . $detallePedido . "'
                    )";
                $conexion->query($sqlKardex) or $sw = false;


                $siaca = ($rpta_sql_suma_ingreso_sucursal_final !== null) ? $rpta_sql_suma_ingreso_sucursal_final : 0;
                $sqlKardex = "INSERT INTO kardex(
                    id_sucursal,
                    fecha_emision,
                    tipo,
                    id_articulo,
                    id_detalle_ingreso,
                    stock_anterior,
                    cantidad,
                    stock_actual,
                    fecha_creacion,
                    fecha_modificacion,
                    id_detalle_pedido
                         )
                VALUES(
                    '$almacenFinal',
                    CURRENT_TIMESTAMP(),
                     'ingreso por traslado',
                     '$detalle_ingreso->idarticulo',
                     '" . $id_nuevo_detalle_ingreso . "',
                      '" . $siaca . "',
                    '" . $cantidad_de_traslado . "',
                    '" . $rpta_sql_suma_ingreso_nuevo_final_not_null . "',
                    CURRENT_TIMESTAMP(),
                    CURRENT_TIMESTAMP(),
                    '" . $detallePedido . "'
                    )";
                $conexion->query($sqlKardex) or $sw = false;



                $conexion->autocommit(true);
            }

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
            $totalTraslado,
            CURRENT_TIMESTAMP(),
            CURRENT_TIMESTAMP(),
            $idUsuario
            )";
            $conexion->query($sql);
            $traslado_id = $conexion->insert_id;
            $conexion->autocommit(true); 

            for ($i = 0; $i < count($detalle); $i++) {
         
                $array = explode(",", $detalle[$i]);
                $iddetalle_ingreso = $array[0];
                $cantidad_tras = $array[6];
                // var_dump($array);

                $sql_detall_ingreso="select * from detalle_ingreso where iddetalle_ingreso=$iddetalle_ingreso";
                $detalle_ingreso = $conexion->query($sql_detall_ingreso)->fetch_object();

                // var_dump($array);

                $sql_inventario = "INSERT INTO inventario(
                    descripcion,
                    idarticulo,
                    sucursal_id,
                    sucursal_destino_id,
                    cantidad,
                    serie,
                    idtraslado,
                    fecha_registro,
                    fecha_modificado
                ) VALUES (
                    'traslado',
                    $detalle_ingreso->idarticulo,
                    $almacenInicial,
                    $almacenFinal,
                    '$cantidad_tras',
                    '$detalle_ingreso->serie',
                    $traslado_id,
                    CURRENT_TIMESTAMP(),
                    CURRENT_TIMESTAMP()
                )
                ";
                // var_dump($sql_inventario);
                 $conexion->query($sql_inventario);
                 $conexion->autocommit(true);

            }
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
