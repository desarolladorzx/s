<?php

require "Conexion.php";
class Correccion_stock
{   
    public function GetImagenes($id){
        global $conexion;
        $sql="SELECT * from correccion_stock_img where idcorreccion_stock=$id ";


        $query = $conexion->query($sql);
		return $query;

    }
    public function  RegistrarDetalleImagenesCorreccion_stock($idcorreccion_stock, $new_file_name)
	{
		global $conexion;
		$sql = "INSERT INTO correccion_stock_img(
			idcorreccion_stock,
			imagen,
			fecha,
			estado,
			tipo_imagen
			)
		VALUES(
			$idcorreccion_stock, 
			'$new_file_name', 
			current_date(), 
			1,
			'CORRECCION')";
        
		$query = $conexion->query($sql);
		return $query;
	}


    
    public function TraerUltimoCodigo(){
        global $conexion;

        $sql="SELECT *,CONCAT(codigo,'-',registro_solicitud,'-','00',cast(correlativo as INT)+1,'-',YEAR(CURRENT_DATE())) correlativo,
        
        correlativo idcorrelativo from correccion_stock ORDER BY idcorreccion_stock desc LIMIT 1;";
        
        return  $conexion->query($sql);


    }
    public function EnviarMensaje($mensaje){
        
        require_once "../PHPMailer/class.phpmailer.php";
        $server = $_SERVER["HTTP_HOST"];
        // $idPedido = $_POST["idPedido"];

        $result1 = 'almacen@grupopuma.pe';
        $result2 = 'logistica@grupopuma.pe2';
        $result3 = 'administracion@grupopuma.pe2';

        $sucursal = $_SESSION["sucursal"];
        $email = $_SESSION["email"];
        $mail = new PHPMailer;

        
        $mail->Host = "$server";
        $mail->From = "$email";
        $mail->FromName = "$sucursal - Área Logistica";
        $mail->Subject = "$sucursal - Notificacion de Correccion de Stock";
        $mail->addAddress("$result1", "Almacen");
        $mail->addAddress("$result2", "Jefe de Logistica");
        $mail->addAddress("$result3", "Jefe de Administracion");
        $mail->MsgHTML($mensaje);
        if ($mail->Send()) {
           
        } else {
            
        }
    }
    public function  cargarBotones($idcorreccion_stock){
        $sql='SELECT *  FROM correccion_stock';
    } 
    public function desaprobarCorreccion($idcorreccion_stock,$motivo_desaprobado)
    {
        global $conexion;

        $sql = "UPDATE correccion_stock  SET  estado='DESAPROBADO',
        idempleado_desaprobado='" . $_SESSION["idempleado"] . "',
        fecha_modificacion=CURRENT_TIMESTAMP(),
        fecha_desaprobado=CURRENT_TIMESTAMP(),

        motivo_desaprobado='$motivo_desaprobado'
        WHERE idcorreccion_stock=$idcorreccion_stock";


        $conexion->query($sql);
    }

    public function anularCorreccion($idcorreccion_stock,$motivo_cancelado_conformidad)
    {
        global $conexion;

        $sql = "UPDATE correccion_stock  SET  estado='CONFORMIDAD CANCELADA',
        idempleado_cancelado_conformidad='" . $_SESSION["idempleado"] . "',
        motivo_cancelado_conformidad='$motivo_cancelado_conformidad',
        fecha_modificacion=CURRENT_TIMESTAMP(),
        fecha_cancelado_conformidad=CURRENT_TIMESTAMP()

        WHERE idcorreccion_stock=$idcorreccion_stock";


        $conexion->query($sql);
    }


    public function cambiarEstadoConformidad($idcorreccion_stock)
    {
        global $conexion;

        $sql = "UPDATE correccion_stock  SET  estado='CONFIRMADO',
        idempleado_conformidad='" . $_SESSION["idempleado"] . "',
        fecha_modificacion=CURRENT_TIMESTAMP(),
        fecha_conformidad=CURRENT_TIMESTAMP()

        WHERE idcorreccion_stock=$idcorreccion_stock";


        $conexion->query($sql);
    }

    public function cambiarEstadoAprobacion($idcorreccion_stock)
    {
        global $conexion;

        
        $error = false;
        $textError = array();

        $sql = "SELECT *  from correccion_stock_detalle where idcorreccion_stock=$idcorreccion_stock";

        $query_prov = $conexion->query($sql);

        $nuevo = array();
        while ($reg = $query_prov->fetch_object()) {
            $nuevo[] = $reg;
        }

        foreach ($nuevo as  $comprobacion_reducir) {
            if ($comprobacion_reducir->tipo == 'reducir') {

                $sql = "SELECT SUM(stock_actual) stock from detalle_ingreso
            join ingreso on ingreso.idingreso=detalle_ingreso.idingreso
            where idarticulo=$comprobacion_reducir->idproducto and idsucursal=$comprobacion_reducir->idsucursal 
            and ingreso.estado='A'
            and detalle_ingreso.estado_detalle_ingreso='INGRESO'
            ";


                $stock = $conexion->query($sql)->fetch_object()->stock;


                if ($comprobacion_reducir->cantidad > $stock) {
                    $error = true;
                    $textError[] = "la solicitud del producto  $comprobacion_reducir->idproducto con la cantidad a reducir $comprobacion_reducir->cantidad sobrepasa el stock actual $stock ";
                }
            }
        }

        if ($error == false) {
           
            echo "Se aprobo la correccion de Stock";

            $sql = "UPDATE correccion_stock  SET  estado='APROBADO',
        idempleado_aprobacion='" . $_SESSION["idempleado"] . "',
        fecha_aprobacion=CURRENT_TIMESTAMP(),
        fecha_modificacion=CURRENT_TIMESTAMP()
        WHERE idcorreccion_stock=$idcorreccion_stock";

            $conexion->query($sql);

            $sql = "SELECT *  from correccion_stock where idcorreccion_stock=$idcorreccion_stock";


            $correccion_stock = $conexion->query($sql)->fetch_object();


            $arrayPorSucursalTipo = array();

            foreach ($nuevo  as $elemento) {

                $sucursal = $elemento->idsucursal;

                $tipo = $elemento->tipo;

                if (!isset($arrayPorSucursalTipo[$sucursal])) {
                    $arrayPorSucursalTipo[$sucursal] = array();
                }

                if (!isset($arrayPorSucursalTipo[$sucursal][$tipo])) {
                    $arrayPorSucursalTipo[$sucursal][$tipo] = array();
                }
                $arrayPorSucursalTipo[$sucursal][$tipo][] = $elemento;
            }

            $idproveedor = 7048;
            $impuesto = 18;

            foreach ($arrayPorSucursalTipo as $nombreSucursal => $sucursal) {

                if (isset($sucursal['añadir'])) {
                    if (count($sucursal['añadir']) > 0) {

                        $total = 0;

                        foreach ($sucursal['añadir'] as $tipo => $datosAnadir) {

                            $total += $datosAnadir->precio_compra * $datosAnadir->cantidad;
                        }


                        $idsucursal = $nombreSucursal;


                        $serie = $idsucursal == 1 ? 'CS-AQP' : 'CS-LIMA';

                        $sql = 'SELECT CAST(MAX(CAST(numero AS INT)) + 1 AS VARCHAR(50)) AS numero
                        FROM traslados';
                        $numero = $conexion->query($sql)->fetch_object()->numero;


                        $sql = "SELECT *  FROM usuario WHERE idempleado=$correccion_stock->idempleado_creacion LIMIT 1";

                        $idusuario = $conexion->query($sql)->fetch_object()->idusuario;


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
                        $idusuario ,
                            $idsucursal,
                            CURRENT_TIMESTAMP(),
                            'A',
                            $idproveedor,
                            'CORRECCION STOCK',
                            '$serie ',
                            YEAR(NOW()),
                            $impuesto,
                            $total
                    )";
                        $conexion->query($sql);
                        $idingreso = $conexion->insert_id;


                        foreach ($sucursal['añadir'] as $tipo => $datosAnadir) {

                            $sql = "SELECT *  from articulo WHERE idarticulo =$datosAnadir->idproducto";

                            $articulo = $conexion->query($sql)->fetch_object();

                            $sql = "SELECT SUM(stock_actual) stock from detalle_ingreso
                            join ingreso on ingreso.idingreso=detalle_ingreso.idingreso
                            where idarticulo=$datosAnadir->idproducto and idsucursal=$idsucursal 
                            and ingreso.estado='A'
                            and detalle_ingreso.estado_detalle_ingreso='INGRESO'
                            ";
                            $rpta_sql_suma_ingreso_sucursal_inicial = $conexion->query($sql)->fetch_object()->stock;

                            $rpta_sql_suma_ingreso_sucursal_inicial_not_null = ($rpta_sql_suma_ingreso_sucursal_inicial !== null) ? $rpta_sql_suma_ingreso_sucursal_inicial : 0;

                            $sql =
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
                            descripcion,
                            estado_detalle_ingreso
                        ) values (
                            $idingreso,
                            $datosAnadir->idproducto,
                            $datosAnadir->cantidad,
                            $datosAnadir->cantidad,
                            '$articulo->barcode',
                            '$datosAnadir->precio_compra',
                            '$articulo->precio_distribuidor',
                            '$articulo->precio_final',
                            '$datosAnadir->fecha_vencimiento',
                            '$articulo->descripcion',
                            'INGRESO'
                        )";

                            $conexion->query($sql);
                            $iddetalle_ingreso = $conexion->insert_id;


                            $sql = "SELECT SUM(stock_actual) stock from detalle_ingreso
                            join ingreso on ingreso.idingreso=detalle_ingreso.idingreso
                            where idarticulo=$datosAnadir->idproducto and idsucursal=$idsucursal 
                            and ingreso.estado='A'
                            and detalle_ingreso.estado_detalle_ingreso='INGRESO'
                            ";
                            $rpta_sql_suma_ingreso_nuevo_inicial = $conexion->query($sql)->fetch_object()->stock;

                            $rpta_sql_suma_ingreso_nuevo_inicial_not_null = ($rpta_sql_suma_ingreso_nuevo_inicial !== null) ? $rpta_sql_suma_ingreso_nuevo_inicial : 0;


                            $detallePedido = 0;

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
                                '$idsucursal',
                                CURRENT_TIMESTAMP(),
                                 'ingreso por correccion_stock',
                                 '$articulo->idarticulo',
                                 '$iddetalle_ingreso',
                                '" . $rpta_sql_suma_ingreso_sucursal_inicial_not_null . "',
                                '$datosAnadir->cantidad',
                                '" . $rpta_sql_suma_ingreso_nuevo_inicial_not_null . "',
                                CURRENT_TIMESTAMP(),
                                CURRENT_TIMESTAMP(),
                                '" . $detallePedido . "'
                                )";
                            $conexion->query($sqlKardex) or $sw = false;
                        }
                    }
                }
                if (isset($sucursal['reducir'])) {
                    if (count($sucursal['reducir']) > 0) {

                        foreach ($sucursal['reducir'] as $tipo => $datosReducir) {

                            $idsucursal = $nombreSucursal;

                            $sql = "SELECT SUM(stock_actual) stock from detalle_ingreso
                        join ingreso on ingreso.idingreso=detalle_ingreso.idingreso
                        where idarticulo=$datosReducir->idproducto and idsucursal=$idsucursal 
                        and ingreso.estado='A'
                        and detalle_ingreso.estado_detalle_ingreso='INGRESO'
                        ";

                            $stock = $conexion->query($sql)->fetch_object()->stock;

                            if ($stock >= $datosReducir->cantidad) {

                                $valorTotal = $datosReducir->cantidad;
                                $index = 0;

                                $sql = "SELECT *  from articulo WHERE idarticulo =$datosReducir->idproducto";
                                $articulo = $conexion->query($sql)->fetch_object();

                                while ($valorTotal > 0) {

                                    $sql = "SELECT *  FROM detalle_ingreso 
                                    JOIN ingreso ON ingreso.idingreso=detalle_ingreso.idingreso
                                    
                                    WHERE idarticulo=$datosReducir->idproducto AND ingreso.estado='A' AND estado_detalle_ingreso='INGRESO' and idsucursal=$idsucursal
                                    ORDER BY iddetalle_ingreso desc limit 1

                                    OFFSET $index
                                    ;
                                     ";

                                    $UDetalle_ingreso = $conexion->query($sql)->fetch_object();

                                    $stock_actual = $UDetalle_ingreso->stock_actual;

                                    $nuevoValor = $stock_actual - $valorTotal >= 0 ?
                                        $stock_actual - $valorTotal : 0;

                                    $sql = "SELECT SUM(stock_actual) stock from detalle_ingreso
                                        join ingreso on ingreso.idingreso=detalle_ingreso.idingreso
                                        where idarticulo=$datosReducir->idproducto and idsucursal=$idsucursal 
                                        and ingreso.estado='A'
                                        and detalle_ingreso.estado_detalle_ingreso='INGRESO'
                                        ";

                                    $rpta_sql_suma_ingreso_sucursal_inicial = $conexion->query($sql)->fetch_object()->stock;
                                    $rpta_sql_suma_ingreso_sucursal_inicial_not_null = ($rpta_sql_suma_ingreso_sucursal_inicial !== null) ? $rpta_sql_suma_ingreso_sucursal_inicial : 0;

                                    $sql = "UPDATE detalle_ingreso SET stock_actual=$nuevoValor  WHERE iddetalle_ingreso=$UDetalle_ingreso->iddetalle_ingreso";

                                    $conexion->query($sql);

                                    $sql = "SELECT SUM(stock_actual) stock from detalle_ingreso
                                    join ingreso on ingreso.idingreso=detalle_ingreso.idingreso
                                    where idarticulo=$datosReducir->idproducto and idsucursal=$idsucursal 
                                    and ingreso.estado='A'
                                    and detalle_ingreso.estado_detalle_ingreso='INGRESO'
                                    ";
                                    $rpta_sql_suma_ingreso_nuevo_inicial = $conexion->query($sql)->fetch_object()->stock;
                                    $rpta_sql_suma_ingreso_nuevo_inicial_not_null = ($rpta_sql_suma_ingreso_nuevo_inicial !== null) ? $rpta_sql_suma_ingreso_nuevo_inicial : 0;

                                    $detallePedido = 0;

                                    $kardexValor = $stock_actual - $valorTotal > 0 ? $valorTotal : $stock_actual;

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
                                        '$idsucursal',
                                        CURRENT_TIMESTAMP(),
                                         'salida por correccion_stock',
                                         '$articulo->idarticulo',
                                         '$UDetalle_ingreso->iddetalle_ingreso',
                                        '" . $rpta_sql_suma_ingreso_sucursal_inicial_not_null . "',
                                        '$kardexValor',
                                        '" . $rpta_sql_suma_ingreso_nuevo_inicial_not_null . "',
                                        CURRENT_TIMESTAMP(),
                                        CURRENT_TIMESTAMP(),
                                        '" . $detallePedido . "'
                                        )";


                                    $conexion->query($sqlKardex) or $sw = false;

                                    $valorTotal -= $stock_actual;

                                    $index++;
                                }
                            }
                        }
                    }
                }
            };
        } else {


            echo json_encode($textError);
        }
    }

    public function TraerDatos($idcorreccion_stock)
    {
        // CONCAT(codigo,'-',registro_solicitud,'-','00',cast(correlativo as INT)+1,'-',YEAR(CURRENT_DATE())) correlativo
        global $conexion;
        $sql = "SELECT * ,
correccion_stock.estado correccion_stock_estado
,
        CONCAT(e1.nombre,' ',e1.apellidos) empleado_creacion , 
        CONCAT(e2.nombre,' ',e2.apellidos) empleado_conformidad ,
        CONCAT(e3.nombre,' ',e3.apellidos) empleado_aprobacion ,
        COUNT(correccion_stock_detalle.idcorreccion_stock) cantidad
        ,
        CONCAT(codigo,'-',registro_solicitud,'-','00',cast(correlativo as INT),'-',YEAR(CURRENT_DATE())) codigo_serie
        FROM correccion_stock
        left JOIN empleado e1 ON e1.idempleado=correccion_stock.idempleado_creacion
        left JOIN empleado e2 ON e2.idempleado=correccion_stock.idempleado_conformidad
        left JOIN empleado e3 ON e3.idempleado=correccion_stock.idempleado_aprobacion
        
        left JOIN correccion_stock_detalle ON correccion_stock_detalle.idcorreccion_stock=correccion_stock.idcorreccion_stock
        
       
        WHERE correccion_stock_detalle.idcorreccion_stock=$idcorreccion_stock

        GROUP BY correccion_stock.idcorreccion_stock

        ";
        $query = $conexion->query($sql);
        return $query;
    }


    public function getEmpleado($idEmpleado)
    {
        global $conexion;

        $sql = "SELECT * from empleado
        where idempleado=$idEmpleado
       ";
        
        $query = $conexion->query($sql);
        return $query;
    }
    public function ModificarEstadoTraslado($idtraslado, $estado, $arrayDatos, $descripcion_recepcion, $sucursal_destino_id)
    {
        global $conexion;

        $array = json_decode(json_decode($arrayDatos, true));

        if ($estado != 'INGRESO') {
            $sql = "UPDATE traslados  SET  estado='$estado',fecha_modificado=CURRENT_TIMESTAMP()
            WHERE idtraslado=$idtraslado";
            $conexion->query($sql);

            foreach ($array as $clave => $valor) {
                $sql = "UPDATE detalle_ingreso  SET  estado_detalle_ingreso='$estado' WHERE iddetalle_ingreso=$valor->iddetalle_ingreso";
                $conexion->query($sql);
            }
        } else {
            $sql = "UPDATE traslados  
            SET  estado='$estado',
                descripcion_recepcion ='$descripcion_recepcion',
            fecha_modificado=CURRENT_TIMESTAMP(),id_empleado_recepcion=" . $_SESSION["idusuario"] . "
            WHERE idtraslado=$idtraslado";
            $conexion->query($sql);

            foreach ($array as $clave => $valor) {


                $sql_stock_anterior = "SELECT SUM(stock_actual) stock from detalle_ingreso
                join ingreso on ingreso.idingreso=detalle_ingreso.idingreso
                where idarticulo=$valor->idarticulo and idsucursal=$sucursal_destino_id
                and ingreso.estado='A' 
                and detalle_ingreso.estado_detalle_ingreso='INGRESO'
                ";

                // echo $sql_stock_anterior;

                $stock_anterior = $conexion->query($sql_stock_anterior)->fetch_object()->stock;

                $stock_anterior_not_null = ($stock_anterior !== null) ? $stock_anterior : 0;

                $sql = "UPDATE detalle_ingreso  SET stock_ingreso='$valor->cantidadRecibida' ,
                stock_actual='$valor->cantidadRecibida' ,
                estado_detalle_ingreso='$estado' WHERE iddetalle_ingreso=$valor->iddetalle_ingreso";
                $conexion->query($sql);

                $sql_stock_actual = "SELECT SUM(stock_actual) stock from detalle_ingreso
                join ingreso on ingreso.idingreso=detalle_ingreso.idingreso
                where idarticulo=$valor->idarticulo and idsucursal=$sucursal_destino_id 
                and ingreso.estado='A'
                and detalle_ingreso.estado_detalle_ingreso='INGRESO'
                ";
                $stock_actual = $conexion->query($sql_stock_actual)->fetch_object()->stock;

                $stock_actual_not_null = ($stock_actual !== null) ? $stock_actual : 0;

                // SE ACTUALIZA EL KARDEX 

                $detallePedido = 0;
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
                    '$sucursal_destino_id ',
                    CURRENT_TIMESTAMP(),
                     'ingreso por traslado',
                     '$valor->idarticulo',
                     '$valor->iddetalle_ingreso',
                      '$stock_anterior_not_null',
                    '$valor->cantidadRecibida',
                    '$stock_actual_not_null',
                    CURRENT_TIMESTAMP(),
                    CURRENT_TIMESTAMP(),
                    '$detallePedido'
                    )";
                $conexion->query($sqlKardex);
            }
        }
    }

    public function ListarDetalleIngresos($sucursal)
    {
        global $conexion;
        $sql = "SELECT distinct di.iddetalle_ingreso,
        case 
		WHEN  di.estado_detalle_ingreso='SALIDA' THEN 'En transito'
		WHEN  di.estado_detalle_ingreso='EN TRANSITO' THEN 'En transito'
		WHEN  di.estado_detalle_ingreso='ALMACEN OPERADOR' THEN 'Almacen Transportista'
		WHEN  di.estado_detalle_ingreso='INGRESO' THEN 'Disponible'		
	END	
		AS estado_n
			,SUM(stock_actual) suma_total
			,i.idsucursal
		
	,
        
        di.estado_detalle_ingreso, di.stock_actual, a.nombre as Articulo, di.codigo,
		   di.serie, di.precio_ventapublico, a.imagen, i.fecha,c.nombre as marca, um.nombre as presentacion,di.idarticulo AS idarticulo 
			from ingreso i inner join detalle_ingreso di on di.idingreso = i.idingreso
			inner join articulo a on di.idarticulo = a.idarticulo
			inner join categoria c on a.idcategoria = c.idcategoria
         inner join unidad_medida um on a.idunidad_medida = um.idunidad_medida
			where i.estado = 'A'  and di.stock_actual > 0 
			
			GROUP BY idarticulo ,i.idsucursal
			
			order by fecha asc";
        $sql="SELECT *,

case 
		WHEN  detalle_ingreso.estado_detalle_ingreso='SALIDA' THEN 'En transito'
		WHEN  detalle_ingreso.estado_detalle_ingreso='EN TRANSITO' THEN 'En transito'
		WHEN  detalle_ingreso.estado_detalle_ingreso='ALMACEN OPERADOR' THEN 'Almacen Transportista'
		WHEN  detalle_ingreso.estado_detalle_ingreso='INGRESO' THEN 'Disponible'		
	END	
		AS estado_n,
        SUM(detalle_ingreso.stock_actual) suma_total
                , unidad_medida.nombre as presentacion
                , categoria.nombre as marca
                , articulo.nombre as Articulo
                ,ingreso.idsucursal 
        FROM detalle_ingreso 
        
        
        
        inner join ingreso  on ingreso.idingreso = detalle_ingreso.idingreso
        join articulo on detalle_ingreso.idarticulo = articulo.idarticulo 
        join categoria  on articulo.idcategoria = categoria.idcategoria
        join unidad_medida  on articulo.idunidad_medida = unidad_medida.idunidad_medida
        
        WHERE detalle_ingreso.stock_actual>0 AND estado_detalle_ingreso='INGRESO'
        AND ingreso.estado='A'
        
        GROUP BY articulo.idarticulo ,ingreso.idsucursal
        
        ;";

        // echo $sql;
        $query = $conexion->query($sql);
        return $query;
    }


    public function Registrar($val)
    {
        global $conexion;
        $fechaActual = date("Ymd");
        $num1 = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

        $codigo = $fechaActual . $num1;

        $Vvalor = json_decode(json_encode($val));
        $sw = true;


        $correlativo='';
        $response=$this->TraerUltimoCodigo()->fetch_object();

        if(!$response){
            $correlativo='001';
        }else{
            $correlativo="00$response->idcorrelativo";
        };
        $sql =
            "INSERT into correccion_stock(
                    descripcion,
                    fecha_ingreso,
                    fecha_modificacion,
                    idempleado_creacion,
                    estado,
                    codigo,
                    registro_solicitud,
                    correlativo
                ) values( 
                    '$Vvalor->motivo_de_Correccion_stock',
                    CURRENT_TIMESTAMP(),
                    CURRENT_TIMESTAMP(),
                    '" . $_SESSION["idempleado"] . "',
                    'ESPERA',
                    'FO-OLG-INV',
                    '16',
                    '$correlativo'
                )";
 
         
        $conexion->query($sql);

        $idcorreccion_stock = $conexion->insert_id;

        $value=$idcorreccion_stock;
        $conexion->autocommit(true);

   

        $array =  $Vvalor->detalle;
      
        foreach ($array as $clave => $string) {

            $elemento = explode(",", $string);

            
            $sql = "INSERT INTO correccion_stock_detalle(
                    idcorreccion_stock,
                    idproducto,
                    idsucursal,
                    fecha_ingreso,
                    precio_compra,
                    cantidad,
                    tipo,
                    fecha_vencimiento
            ) values(
                '$idcorreccion_stock',
                '$elemento[0]',
                '$elemento[8]',
                CURRENT_TIMESTAMP(),
                '$elemento[6]',
                '$elemento[5]',
                '$elemento[4]',
                '$elemento[7]'
            )";
            $conexion->query($sql);
        }

        return $value;
    }


    public function TableCorreccionStock()
    {
        global $conexion;
        $sql = "SELECT * ,correccion_stock.estado correccion_stock_estado,
        CONCAT(e1.nombre,' ',e1.apellidos) empleado_creacion , 
        CONCAT(e2.nombre,' ',e2.apellidos) empleado_conformidad ,
        CONCAT(e3.nombre,' ',e3.apellidos) empleado_aprobacion ,
        COUNT(correccion_stock_detalle.idcorreccion_stock) cantidad

        ,correccion_stock.idcorreccion_stock id
        ,
        CONCAT(codigo,'-',registro_solicitud,'-','00',cast(correlativo as INT)+1,'-',YEAR(CURRENT_DATE())) codigo_inventario

         FROM correccion_stock
        left JOIN empleado e1 ON e1.idempleado=correccion_stock.idempleado_creacion
        left JOIN empleado e2 ON e2.idempleado=correccion_stock.idempleado_conformidad
        left JOIN empleado e3 ON e3.idempleado=correccion_stock.idempleado_aprobacion
        
        left JOIN correccion_stock_detalle ON correccion_stock_detalle.idcorreccion_stock=correccion_stock.idcorreccion_stock
        
        GROUP BY correccion_stock.idcorreccion_stock
        
        order by correccion_stock.idcorreccion_stock desc
        ;

             ";

        $query = $conexion->query($sql);
        return $query;
    }
    public function infoTraslados($idtraslado)
    {
        global $conexion;
        $sql = "SELECT descripcion,suc_ini.razon_social AS inicial,suc_des.razon_social AS final FROM traslados 
        JOIN sucursal suc_ini ON suc_ini.idsucursal = traslados.sucursal_id
        JOIN sucursal suc_des ON suc_des.idsucursal = traslados.sucursal_destino_id
        where idtraslado=$idtraslado
        ";
        $query = $conexion->query($sql);
        return $query;
    }

    public function GetDetalleCorreccionStock($idcorreccion_stock)
    {
        global $conexion;
        $sql = "SELECT * ,articulo.nombre Articulo
        ,correccion_stock_detalle.cantidad Cantidad
        ,correccion_stock_detalle.precio_compra Precio_compra
        ,correccion_stock_detalle.fecha_vencimiento Fecha_vencimiento
        FROM  correccion_stock_detalle
        JOIN articulo ON articulo.idarticulo=correccion_stock_detalle.idproducto
        where idcorreccion_stock=$idcorreccion_stock
       ";

        // echo $sql;

        //exit;

        $query = $conexion->query($sql);
        return $query;
    }
}
