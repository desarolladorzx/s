<?php 
error_reporting (0); ?>

<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link href="../public/css/ticket.css" rel="stylesheet" type="text/css">
<script>
    function printPantalla()
{
   document.getElementById('cuerpoPagina').style.marginRight  = "0";
   document.getElementById('cuerpoPagina').style.marginTop = "1";
   document.getElementById('cuerpoPagina').style.marginLeft = "1";
   document.getElementById('cuerpoPagina').style.marginBottom = "0";
   document.getElementById('botonPrint').style.display = "none";
   window.print();
}
</script>
</head>
<body id="cuerpoPagina">
<?php
require_once "../model/Pedido.php";

$objPedido = new Pedido();
$query_cli = $objPedido->GetVenta($_GET["id"]);
$reg_cli = $query_cli->fetch_object();
//$reg = $query_Pedido->fetch_object();

date_default_timezone_set('America/Lima');

require_once "../model/Configuracion.php";
$objConfiguracion = new Configuracion();
$query_global = $objConfiguracion->Listar();
$reg_igv = $query_global->fetch_object();

?>

<div class="zona_impresion">
        <!-- codigo imprimir -->
<table border="0" align="center" width="300px">
    <tr>
      <td align="center"></td>
    </tr>
    <tr>
        <td>CLIENTE: <font size=3> <?php echo mb_strtoupper($reg_cli->nombre." ".$reg_cli->apellido,'UTF-8');?> </font> </td>
    </tr>
    <tr>
        <td><font size=2> <?php echo $reg_cli->documento_per." : "; ?> </font> <font size=3> <?php echo $reg_cli->num_documento; ?> </font> </td>
    </tr>
    <tr>
        <td>CELULAR: <font size=3> <?php echo $reg_cli->telefono. " - " .$reg_cli->telefono_2; ?> </font> </td>
    </tr>
    <tr>
        <td>DESTINO:<font size=3> <?php echo mb_strtoupper($reg_cli->direccion_distrito." - ".$reg_cli->direccion_provincia." - ".$reg_cli->direccion_departamento,'UTF-8'); ?> </font> </td>
    </tr>
    <tr>
        <td>DIRECCION:<font size=3> <?php echo mb_strtoupper($reg_cli->direccion_calle." - ".$reg_cli->direccion_distrito,'UTF-8'); ?> </font> </td>
    </tr>
    <tr>
        <td>TRANSPORTE:<font size=3> <?php echo mb_strtoupper($reg_cli->agencia_envio,'UTF-8'); ?> </font> </td>
    </tr>
    <tr>
        <td align="center"><?php echo "EMPAQUETADO : ".date("Y-m-d")." Hora: ".date("H:i:s"); ?></td>    
    </tr>
</table>

</div>

<div class="zona_impresion">
        <!-- codigo imprimir   -->
<table border="0" align="center" width="300px">
    <tr>
      <td align="center">
        .::<strong><?php echo "MEDICFITCEN S.A.C"; ?></strong>::.<br><?php echo $reg_cli->razon_social; ?><br></td>
    </tr>
    <!-- <tr>
      <td align="center"><?php echo $reg_cli->direccion; ?><br></td>
    </tr> -->
    <tr>
      <td align="center">TICKET DE VENTA </td>
    </tr>
    <tr>
      <td align="center"><strong><font size="4"><?php echo $reg_cli->serie_comprobante."-".$reg_cli->num_comprobante;?> <?php echo($reg_cli->estado=='A')?'':'- ANULADO'; ?></strong></td>
    </tr>
    <tr>
      <td align="center">FECHA EMISIÓN : <?php echo $reg_cli->fecha; ?></td>
    </tr>
      <td>&nbsp;</td>
    <tr>
        <td><strong>TIPO DE CLIENTE : </strong><?php echo $reg_cli->tipo_cliente; ?></td>
    </tr>
    <tr>
        <td><strong>CLIENTE : </strong><?php echo $reg_cli->nombre; ?> <?php echo $reg_cli->apellido; ?></td>
    </tr>
    <tr>
        <td><strong> <font size=2> <?php echo $reg_cli->documento_per." : "; ?> </font></strong> <font size=2> <?php echo $reg_cli->num_documento; ?> </font> </td>
    </tr>
    <tr>
        <td><strong>ATENDIO : </strong><?php echo mb_strtoupper($reg_cli->empleado) ; ?></td> <!-- Empleado se modifico pedido.php. el script gevVenta -->
    </tr>
    <td>&nbsp;</td>
    <tr>
        <td><b>MEDIO DE PAGO</b></td> <!-- Empleado se modifico pedido.php. el script gevVenta -->
    </tr>
    <tr>
        <td><b>CUENTA ABONADA : <?php echo $reg_cli->metodo_pago ; ?></b></td> <!-- Empleado se modifico pedido.php. el script gevVenta -->
    </tr>
</table>
<br>
<table border="0" align="center" width="300px">
    <tr>
        <td>PRODUCTOS</td>
        <td>CANT.</td>      
        <td align="right">IMPORTE</td>
    </tr>
    <tr>
      <td colspan="3">==========================================</td>
    </tr>
    <?php
    $query_ped = $objPedido->ImprimirDetallePedido($_GET["id"]);

        while ($reg = $query_ped->fetch_object()) {
        echo "<tr>";
        echo "<td>".$reg->articulo. " | ".$reg->marca." | ".$reg->codigo." | ".$reg->serie."</td>";
        echo "<td align='center'>".$reg->cantidad."</td>";
        echo "<td align='right'>". $reg_igv->simbolo_moneda." ".$precio_venta=$reg->cantidad*($reg->precio_venta-$reg->descuento)."</td>";
        echo "</tr>";
        $cantidad+=$reg->cantidad;
        $precio_venta=$reg->cantidad*($reg->precio_venta-$reg->descuento);
        $descuentos+=$reg->descuento*$reg->cantidad;
        $sub_totales+=$reg->sub_total+$reg->descuento;
        $precio_total+=$reg->cantidad*$reg->precio_venta;
    }
    $query_total = $objPedido->TotalPedido($_GET["id"]);

    $reg_total = $query_total->fetch_object();
    ?>
    <tr>
      <td colspan="3">==========================================</td>
    </tr>
    <tr>
    <td>&nbsp;</td>
    <td align="center"><b>Total pagado:</b></td>
    <td align="right"><b><?php echo $reg_igv->simbolo_moneda;?>  <?php echo $reg_total->Total;  ?></b></td>
    </tr>
    <tr>
      <td colspan="3">Cantidad de productos:  <?php echo $cantidad ?></td>
    </tr>
    <tr>
      <td colspan="3">Precio de los productos: <?php echo $reg_igv->simbolo_moneda;?> <?php echo $precio_total ?></td>
    </tr>
    <tr>
      <td colspan="3">Descuentos por tienda: <?php echo $reg_igv->simbolo_moneda;?> <?php echo $descuentos ?></td>
    </tr>
    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>
      <td colspan="3" align="center">¡Gracias por usar productos Medicfitcen para lograr sus objetivos!</td>
    </tr>
    <tr>
      <td colspan="3" align="center">Para mayor información:</td>
    </tr>
    <tr>
      <td colspan="3" align="center">+51 962 723 138</td>
    </tr>
    <tr>
      <td colspan="3" align="center"><b>Recuerde visitar : www.medicfitcen.com</b></td>
    </tr>
    <tr>
    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>
      <td colspan="3" align="center">CONFORMIDAD DE PRODUCTO</td>
    </tr>
    <tr>
      <td colspan="3" align="center">RECIBI CONFORME EL PEDIDO FIRMA Y DNI</td>
    </tr>
    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>  
    <tr>
      <td colspan="3" align="center">--------------------------------------</td>
    </tr>
    
</table>
<br>
</div>
<p>

<div style="margin-left:245px;"><a href="#" id="botonPrint" onClick="printPantalla();"><img src="../img/printer.png" border="0" style="cursor:pointer" title="Imprimir"></a></div>
</body>
</html>

