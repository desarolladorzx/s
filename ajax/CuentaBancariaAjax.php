<?php
session_start();
require_once "../model/CuentaBancaria.php";
$obj = new CuentaBancaria();

switch ($_GET["op"]) {


    case 'traerTipoDivisa':


        $query_Tipo = $obj->traerTipoDivisa();

        $nuevo = array();
        while ($reg = $query_Tipo->fetch_object()) {
            $nuevo[] = $reg;
        }
        echo  json_encode($nuevo);


        break;

    case 'traerTipoCuenta':


        $query_Tipo = $obj->traerTipoCuenta();

        $nuevo = array();
        while ($reg = $query_Tipo->fetch_object()) {
            $nuevo[] = $reg;
        }
        echo  json_encode($nuevo);


        break;

    case 'traerBanco':


        $query_Tipo = $obj->traerBanco();

        $nuevo = array();
        while ($reg = $query_Tipo->fetch_object()) {
            $nuevo[] = $reg;
        }
        echo  json_encode($nuevo);


        break;

    case 'list':
        $query = $obj->listar();

        $data = array();
        $i = 1;

        while ($reg = $query->fetch_object()) {
            $data[] = array(
                "0" => $i,
                "1" => $reg->tipo_cuenta,
                "2" => $reg->estado==1?'activo':'inactivo',
                "3" => $reg->banco,
                "4" => $reg->banco_cuenta,
                "5" => $reg->numero,
                "6" => $reg->cci,
                "7" => $reg->balance_inicial,

                "8" => '<button class="btn btn-warning" data-toggle="tooltip" title="Editar" onclick="cargarDataCuentaBancaria(
                    `' . $reg->idbanco_cuenta . '`,
                    `' . $reg->tipo_moneda . '`,
                    `' . $reg->idtipo_cuenta . '`,
                    `' . $reg->idbanco . '`,
                    `' . $reg->nombre_titular . '`,
                    `' . $reg->nombre_banco_cuenta . '`,
                    `' . $reg->numero . '`,
                    `' . $reg->cci . '`,
                    `' . $reg->descripcion . '`,
                    `' . $reg->balance_inicial . '`
              
                )"><i class="fa fa-pencil"></i> </button>&nbsp;' .
                    '<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarCuentaBancaria(' . $reg->idbanco_cuenta . ')"><i class="fa fa-trash"></i> </button>',
            );
            $i++;
        }
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);

        break;
    case 'SaveOrUpdate':

        if (empty($_POST["txtIdcuenta_bancaria"])) {
            if ($obj->Registrar(
                $_POST["txtNombreCuentaBancaria"],
                $_POST["cboModTipo_de_Cuenta"],
                $_POST["cboModIdBanco"],
                $_POST["txtNumero"],
                $_POST["txtNumeroCCI"],
                $_POST["txtSaldoInicial"],
                $_POST["cboModTipo_Moneda"],
                $_POST["txtDescripcion"],
                $_POST["txtNombreCuentaBancaria"],

            )) {
                echo "Registrado Exitosamente";
            } else {
                echo "Usuario no ha podido ser registado.";
            }
        } else {
            if ($obj->Modificar(
                $_POST["txtIdcuenta_bancaria"],
                $_POST["txtNombreCuentaBancaria"],
                $_POST["cboModTipo_de_Cuenta"],
                $_POST["cboModIdBanco"],
                $_POST["txtNumero"],
                $_POST["txtNumeroCCI"],
                $_POST["txtSaldoInicial"],
                $_POST["cboModTipo_Moneda"],
                $_POST["txtDescripcion"],
                $_POST["txtNombreCuentaBancaria"],
            )) {
                echo "Registrado Exitosamente";
            } else {
                echo "Usuario no ha podido ser registado.";
            }
        }
        break;

    case "delete":

        $id = $_POST["id"];
        $result = $obj->Eliminar($id);
        if ($result) {
            echo "Eliminado Exitosamente";
        } else {
            echo "No fue Eliminado";
        }
        break;
}
