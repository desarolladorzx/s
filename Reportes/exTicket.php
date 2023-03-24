<?php
error_reporting(0); ?>

<html>

<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <link href="../public/css/ticket.css" rel="stylesheet" type="text/css">
  <script>
    function printPantalla() {
      document.getElementById('cuerpoPagina').style.marginRight = "0";
      document.getElementById('cuerpoPagina').style.marginTop = "1";
      document.getElementById('cuerpoPagina').style.marginLeft = "1";
      document.getElementById('cuerpoPagina').style.marginBottom = "0";
      document.getElementById('botonPrint').style.display = "none";
      window.print();
    }
    window.addEventListener('contextmenu',
      function(
        e
      ) {
        e.
        preventDefault
          ();
      });

    window.addEventListener(
      'afterprint',
      async function(e) {

        var idventa = document.getElementById('idventaTicket').value

        fetch(
          "../ajax/VentaAjax.php?op=SaveImprimir&idventa=" + idventa
        ).
        then
          (function(e) {
            console.log(e)
          })
      });
  </script>
</head>

<body id="cuerpoPagina">
  <?php
  require_once "../model/Pedido.php";

  $objPedido = new Pedido();
  session_start();

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

      <input type="hidden" id='idventaTicket' value="<?php echo $reg_cli->idventa; ?>">
      <tr>
        <td><b>CLIENTE: </b>
          <font size=2> <?php echo mb_strtoupper($reg_cli->nombre . " " . $reg_cli->apellido, 'UTF-8'); ?> </font>
        </td>
      </tr>
      <tr>
        <td><b>
            <font size=2><?php echo $reg_cli->documento_per . " : "; ?>
          </b></font>
          <font size=2> <?php echo $reg_cli->num_documento; ?> </font>
        </td>
      </tr>
      <tr>
        <td><b>CELULAR: </b>
          <font size=2> <?php echo $reg_cli->telefono . " - " . $reg_cli->telefono_2; ?> </font>
        </td>
      </tr>
      <tr>
        <td><b>TRANSPORTE: </b></b>
          <font size=2> <?php echo mb_strtoupper($reg_cli->agencia_envio, 'UTF-8'); ?> </font>
        </td>
      </tr>
      <tr>
        <td><b>TIPO DE ENTREGA: </b>
          <font size=2> <?php echo mb_strtoupper($reg_cli->tipo_entrega, 'UTF-8'); ?> </font>
        </td>
      </tr>
      <tr>
        <td><b>MODO DE PAGO: </b>
          <font size=2> <?php echo mb_strtoupper($reg_cli->modo_pago, 'UTF-8'); ?> </font>
        </td>
      </tr>
      <tr>
        <td><b>DESTINO: </b>
          <font size=2> <?php echo mb_strtoupper($reg_cli->direccion_distrito . " - " . $reg_cli->direccion_provincia . " - " . $reg_cli->direccion_departamento, 'UTF-8'); ?> </font>
        </td>
      </tr>
      <tr>
        <td><b>DIRECCION: </b>
          <font size=2> <?php echo mb_strtoupper($reg_cli->direccion_calle . " - " . $reg_cli->direccion_distrito, 'UTF-8'); ?> </font>
        </td>
      </tr>
      <!-- <tr>
        <td><b>REFERENCIA: </b><font size=2> <?php echo mb_strtoupper($reg_cli->direccion_referencia, 'UTF-8'); ?> </font> </td>
    </tr> -->

      <tr>
        <td align="center"><?php echo "<b>" . "EMPAQUETADO : " . "</b>" . date("Y-m-d") . "<b>" . " Hora: " . "</b>" . date("H:i:s"); ?></td>
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
        <td align="center"><strong>
            <font size="4"><?php echo $reg_cli->serie_comprobante . "-" . $reg_cli->num_comprobante; ?> <?php echo ($reg_cli->estado == 'A') ? '' : '- ANULADO'; ?>
          </strong></td>
      </tr>
      <?php echo ($reg_cli->estado == 'A') ? '' : '<tr>
      <td align="center">ANULADO POR :<b> ' . $reg_cli->empleado_anulado . '</b></td>
    </tr>'; ?>


      <tr>
        <td align="center"> IMPRESO POR : <?php echo $_SESSION["empleado"]; ?></td>
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
        <td><strong>
            <font size=2> <?php echo $reg_cli->documento_per . " : "; ?> </font>
          </strong>
          <font size=2> <?php echo $reg_cli->num_documento; ?> </font>
        </td>
      </tr>
      <tr>
        <td><strong>ATENDIO : </strong><?php echo mb_strtoupper($reg_cli->empleado); ?></td> <!-- Empleado se modifico pedido.php. el script gevVenta -->
      </tr>
      <td>&nbsp;</td>
      <tr>
        <td><b>MEDIO DE PAGO</b></td> <!-- Empleado se modifico pedido.php. el script gevVenta -->
      </tr>
      <tr>
        <td><b>MODO DE PAGO : <?php echo $reg_cli->modo_pago; ?></b></td> <!-- Empleado se modifico pedido.php. el script gevVenta -->
      </tr>
      <tr>
        <td><b>CUENTA ABONADA : <?php echo $reg_cli->metodo_pago; ?></b></td> <!-- Empleado se modifico pedido.php. el script gevVenta -->
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
        echo "<td>" . $reg->articulo . " | <b>" . $reg->marca . "</b> | " . $reg->codigo . " | " . $reg->serie . "</td>";
        echo "<td align='center'>" . $reg->cantidad . "</td>";
        echo "<td align='right'>" . $reg_igv->simbolo_moneda . " " . $precio_venta = $reg->cantidad * ($reg->precio_venta - $reg->descuento) . "</td>";
        echo "</tr>";
        echo "<td>" . "-----------------------------------------" . "</td>";
        $cantidad += $reg->cantidad;
        $precio_venta = $reg->cantidad * ($reg->precio_venta - $reg->descuento);
        $descuentos += $reg->descuento * $reg->cantidad;
        $sub_totales += $reg->sub_total + $reg->descuento;
        $precio_total += $reg->cantidad * $reg->precio_venta;
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
        <td align="right"><b><?php echo $reg_igv->simbolo_moneda; ?> <?php echo $reg_total->Total;  ?></b></td>
      </tr>
      <tr>
        <td colspan="3">Cantidad de productos: <?php echo $cantidad ?></td>
      </tr>
      <tr>
        <td colspan="3">Precio de los productos: <?php echo $reg_igv->simbolo_moneda; ?> <?php echo $precio_total ?></td>
      </tr>
      <tr>
        <td colspan="3">Descuentos por tienda: <?php echo $reg_igv->simbolo_moneda; ?> <?php echo $descuentos ?></td>
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