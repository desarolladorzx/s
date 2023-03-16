<?php
session_start();
require_once "../model/Persona.php";
$objCliente = new Persona();
switch ($_GET["op"]) {

	case 'SaveOrUpdate':

		if ($_POST["optionsRadios_edit"] != "") {
			$genero = $_POST["optionsRadios_id_edit"];
		} else {
			$genero = $_POST["optionsRadios"];
		}

		//var_dump($genero);exit;
		
		if ($_POST["cboTipo_Documento_edit"] != "") {
			$tipo_documento = $_POST["cboTipo_Documento_edit"];
		} else {
			$tipo_documento = $_POST["cboTipo_Documento"];
		}
		
		//var_dump($tipo_documento);
		//exit;

		$tipo_persona = $_POST["cboTipo_Persona"];
		$nombre = isset($_POST["txtNombre"]) ? $_POST["txtNombre"]: "";
		$apellido = isset($_POST["txtApellido"]) ? $_POST["txtApellido"]: "";
		//$tipo_documento = $_POST["cboTipo_Documento"];
		$num_documento = $_POST["txtNum_Documento"];
		

		$direccion_referencia = isset($_POST["txtDireccion_Referencia"]) ? $_POST["txtDireccion_Referencia"] : "";

		$direccion_departamento = isset($_POST["txtDireccion_Departamento"]) ? $_POST["txtDireccion_Departamento"] : "";
		$direccion_provincia = isset($_POST["txtDireccion_Provincia"]) ? $_POST["txtDireccion_Provincia"] : "";
		$direccion_distrito = isset($_POST["txtDireccion_Distrito"]) ? $_POST["txtDireccion_Distrito"] : "";
		$direccion_calle = isset($_POST["txtDireccion_Calle"]) ? $_POST["txtDireccion_Calle"] : "";
		$telefono = isset($_POST["txtTelefono"]) ? $_POST["txtTelefono"] : "";
		$telefono_2 = isset($_POST["txtTelefono_2"]) ? $_POST["txtTelefono_2"] : "";
		$email = isset($_POST["txtEmail"]) ? $_POST["txtEmail"] : "";
		$numero_cuenta = isset($_POST["txtNumero_Cuenta"]) ? $_POST["txtNumero_Cuenta"] : "";
		$estado = $_POST["txtEstado"];
		//$idempleado = $_POST["txtIdEmpleado"];
		$idempleado = $_POST["txtIdEmpleado_modificado"] != "" ? $_POST["txtIdEmpleado_modificado"] : $_POST["txtIdEmpleado"];

		//var_dump($idempleado);exit;

		if (empty($_POST["txtIdPersona"])) {
			if ($objCliente->Registrar($tipo_persona, $nombre, $apellido, $tipo_documento, $num_documento, $genero, $direccion_departamento, $direccion_provincia, $direccion_distrito, $direccion_calle, $telefono, $telefono_2, $email, $numero_cuenta, $estado, $idempleado, $idempleado ,$direccion_referencia)) {
				echo "Cliente registrado correctamente";
			} else {
				echo "El Cliente no ha podido ser registrado.";
			}
		} else {
			$idpersona = $_POST["txtIdPersona"];
			if ($objCliente->Modificar($idpersona, $tipo_persona, $nombre, $apellido, $tipo_documento, $num_documento, $genero, $direccion_departamento, $direccion_provincia, $direccion_distrito, $direccion_calle, $telefono, $telefono_2, $email, $numero_cuenta, $estado, $idempleado,$direccion_referencia)) {
				echo "La informacion del Cliente ha sido actualizada";
			} else {
				echo "La informacion del Cliente no ha podido ser actualizada.";
			}
		}
		break;

	case "delete":
		$id = $_POST["id"]; // Llamamos a la variable id del js que mandamos por $.post (Categoria.js (Linea 62))
		$result = $objCliente->Eliminar($id);
		if ($result) {
			echo "Eliminado Exitosamente";
		} else {
			echo "No fue Eliminado";
		}
		break;

	case "list":
		$query_Tipo = $objCliente->ListarCliente();
		$data = array();
		$i = 1;
		while ($reg = $query_Tipo->fetch_object()) {

			$clasificacion = $objCliente->clasificacion_cliente($reg->idpersona)->fetch_object()->clasificacion;


			if ($_SESSION['rol_usuario'] == "S") {
				$boton_editar = '<button class="btn btn-warning" data-toggle="tooltip" title="Editar" onclick="cargarDataCliente(' . $reg->idpersona . ',\'' . $reg->tipo_persona . '\',\'' . $reg->nombre . '\',\'' . $reg->apellido . '\',\'' . $reg->tipo_documento . '\',\'' . $reg->num_documento . '\',\'' . $reg->direccion_departamento . '\',\'' . $reg->direccion_provincia . '\',\'' . $reg->direccion_distrito . '\',\'' . $reg->direccion_calle . '\',\'' . $reg->telefono . '\',\'' . $reg->telefono_2 . '\',\'' . $reg->email . '\',\'' . $reg->numero_cuenta . '\',\'' . $reg->estado . '\',\'' . $reg->idempleado . '\',\'' . $reg->empleado . '\',\'' . $reg->fecha_registro . '\',\'' . $reg->empleado_modificado . '\',\'' . $reg->fecha_modificado . '\',\'' . $reg->genero . '\',\'' . $reg->genero_txt . '\' 
				
				,\'' . $clasificacion . '\',\'' . $reg->direccion_referencia . '\'
				)"><i class="fa fa-pencil"></i> </button>';
			}else{
				$boton_editar = '<button class="btn btn-warning" data-toggle="tooltip" title="Editar" onclick="cargarDataCliente(' . $reg->idpersona . ',\'' . $reg->tipo_persona . '\',\'' . $reg->nombre . '\',\'' . $reg->apellido . '\',\'' . $reg->tipo_documento . '\',\'' . $reg->num_documento . '\',\'' . $reg->direccion_departamento . '\',\'' . $reg->direccion_provincia . '\',\'' . $reg->direccion_distrito . '\',\'' . $reg->direccion_calle . '\',\'' . $reg->telefono . '\',\'' . $reg->telefono_2 . '\',\'' . $reg->email . '\',\'' . $reg->numero_cuenta . '\',\'' . $reg->estado . '\',\'' . $reg->idempleado . '\',\'' . $reg->empleado . '\',\'' . $reg->fecha_registro . '\',\'' . $reg->empleado_modificado . '\',\'' . $reg->fecha_modificado . '\',\'' . $reg->genero . '\',\'' . $reg->genero_txt . '\' 
				,\'' . $clasificacion . '\',\'' . $reg->direccion_referencia . '\'
				)"><i class="fa fa-pencil"></i> </button>';
			}

			$boton_eliminar = '<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarCliente(' . $reg->idpersona . ')"><i class="fa fa-trash"></i> </button>';

			$data[] = array(
				"id" => $i,
				"1" => $reg->tipo_persona . ' - ' . $reg->numero_cuenta,
				"2" => $reg->nombre . '&nbsp;' . $reg->apellido,
				"3" => $reg->tipo_documento . ': ' . $reg->num_documento,
				"4" => $reg->telefono . ' - ' . $reg->telefono_2,
				"5" => $reg->direccion_calle . ': ' . $reg->direccion_distrito . ': ' . $reg->direccion_provincia . ': ' . $reg->direccion_departamento,
				"6" => $reg->email,
				"7" => $reg->empleado,
				"8" => $reg->fecha_registro,
				"9" => $reg->empleado_modificado,
				"10" => $reg->fecha_modificado,
				"11" => $boton_editar . ' ' . $boton_eliminar
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

	case "listTipo_DocumentoPersona":
		require_once "../model/Tipo_Documento.php";
		$objTipo_Documento = new Tipo_Documento();
		$query_tipo_Documento = $objTipo_Documento->VerTipo_Documento_Persona();
		while ($reg = $query_tipo_Documento->fetch_object()) {
			echo '<option value=' . $reg->nombre . '>' . $reg->nombre . '</option>';
		}
		break;

	
	case "buscarClienteSunat":
		//require_once "../public/curl/Curl.php";
		//$server = $_SERVER["HTTP_HOST"];
		$numerodoc = $_REQUEST["numerodoc"];
		$origen = $_REQUEST["origen"];
		// COMPROBAR SI EL CLIENTE YA SE ENCUENTRA REGITRADO EN BASE DE DATOS
		$rptaBuscarClientePorNroDoc = $objCliente->BuscarClientePorNroDoc($numerodoc);
		$reg = $rptaBuscarClientePorNroDoc->fetch_object();

		//var_dump($origen);exit;

		// SI ES NULL NO SE ENCUENTRA EN BASE DE DATOS - SE BUSCA EN API

		if ($reg == NULL || is_null($reg)) {  // $reg->idpersona

			$estadoCuenta = "NUEVO";

			if (strlen($numerodoc) == 8) {

				$headers = get_headers("https://api.apis.net.pe/v1/dni?numero=" . $numerodoc);
				$raptaURL = substr($headers[0], 9, 3);

				if ($raptaURL != '404') {

					$data = file_get_contents("https://api.apis.net.pe/v1/dni?numero=" . $numerodoc);
					$info = json_decode($data, true);

					$tipo_persona = 'Final';
					$nombre = $info['nombres'];
					$apellido = $info['apellidoPaterno'] . ' ' . $info['apellidoMaterno'];
					$tipo_documento = 'DNI'; //El tipo de documento no se actualiza tiene un valor "1" en la API
					$num_documento = $info['numeroDocumento'];
					$direccion_departamento = '';
					$direccion_provincia = '';
					$direccion_distrito = '';
					$direccion_calle = '';
					$telefono = '';
					$telefono_2 = 0;
					$email = '';
					$numero_cuenta = $estadoCuenta;
					$estado = 'A';

					// CONSULTA VARIABLE ORIDEN
					// moduloVenta -> viene desde consulta en venta - insertar y se devuelve datos
					// moduloCliente -> viene desde consulta en nuevo cliente - solo devuelve datos

					//var_dump($origen);
					//exit;
					
					$datos = array(
						'estado' => 'encontrado',
						'idCliente' => "",
						'tipo_persona' => "FINAL",
						'nombre' => $nombre,
						'apellido' => $apellido,
						'tipo_documento' => $tipo_documento,
						'num_documento' => $num_documento,
						'direccion_departamento' => $direccion_departamento,
						'direccion_provincia' => $direccion_provincia,
						'direccion_distrito' => $direccion_distrito,
						'direccion_calle' => $direccion_calle,
						'telefono' => $telefono,
						'telefono_2' => $telefono_2,
						'email' => $email,
						'numero_cuenta' => $numero_cuenta,
						'estado_cliente' => $estado,
						'estadoCuenta' => $estadoCuenta
					);


				} else {
					// SI NO SE ENCUENTRA NUMERO DE DOCUMENTO EN API NI EN BASE DE DATOS
					$datos = array(
						'estado' => 'no_encontrado',
						'idCliente' => "",
						'nombre' => "",
						'apellido' => "",
						'numeroDocumento' => $numerodoc,
						'estadoCuenta' => $estadoCuenta
					);
				}
				//var_dump($data);
				//exit;
			
			} else if (strlen($numerodoc) == 11) {
				$headers = get_headers("https://api.apis.net.pe/v1/dni?numero=" . $numerodoc);
				$raptaURL = substr($headers[0], 9, 3);
				if ($raptaURL != '404') {
					$data = file_get_contents("https://api.apis.net.pe/v1/ruc?numero=" . $numerodoc);
					$info = json_decode($data, true);
	
					$tipo_persona = 'Distribuidor';
					$nombre = $info['nombre'];
					$apellido = '';
					$tipo_documento = 'RUC';
					$num_documento = $info['numeroDocumento'];
					$direccion_departamento = $info['departamento'];
					$direccion_provincia = $info['provincia'];
					$direccion_distrito = $info['distrito'];
					$direccion_calle = $info['direccion'];
					$telefono = '';
					$telefono_2 = 0;
					$email = '';
					$numero_cuenta = '';
					$estado = 'A';
					

					$datos = array(
						'estado' => 'encontrado',
						'idCliente' => "",
						'tipo_persona' => "FINAL",
						'nombre' => $nombre,
						'apellido' => $apellido,
						'tipo_documento' => $tipo_documento,
						'num_documento' => $num_documento,
						'direccion_departamento' => $direccion_departamento,
						'direccion_provincia' => $direccion_provincia,
						'direccion_distrito' => $direccion_distrito,
						'direccion_calle' => $direccion_calle,
						'telefono' => $telefono,
						'telefono_2' => $telefono_2,
						'email' => $email,
						'numero_cuenta' => $numero_cuenta,
						'estado_cliente' => $estado,
						'estadoCuenta' => $estadoCuenta
					);

				} else {
					// SI EL TIPO DE DOCUEMNTO ES DIFERENTE A 8 DIGITOS O A 11
					$datos = array(
						'estado' => 'no_encontrado',
						'idCliente' => "",
						'nombre' => "",
						'apellido' => "",
						'numeroDocumento' => $numerodoc,
						'estadoCuenta' => $estadoCuenta
					);
				}
			}

			//var_dump($info);exit;
			

			/*
			$estadoCuenta = "CLIENTE NUEVO";
			$datos = array(
				'estado' => 'no_encontrado',
				'idCliente' => "",
				'nombre' => "",
				'apellido' => "",
				'numeroDocumento' => $numerodoc,
				//'estadoCuenta' => $estadoCuenta
			);
			echo json_encode($datos, true);
			*/

		}else{

			// SI SE ENCONTRO CLIENTE EN BASE DE DATOS SE MUESTRA DATOS

			// BUSCAR SI TIENEN MAS DE UN PEDIDO
			$rptaBuscarExistePedido = $objCliente->BuscarExistePedido($reg->idpersona);
			$resultExiste = $rptaBuscarExistePedido->fetch_object();

			if ($resultExiste->countidpedido > 1) {
				$estadoCuenta = "ANTIGUO";
			} else {
				$estadoCuenta = "NUEVO";
			}

			$datos = array(
				'estado' => 'encontrado',
				'idCliente' => $reg->idpersona,
				'tipo_persona' => $reg->tipo_persona,
				'nombre' => $reg->nombre,
				'apellido' => $reg->apellido,
				'tipo_documento' => $reg->tipo_documento,
				'num_documento' => $reg->num_documento,
				'direccion_departamento' => $reg->direccion_departamento,
				'direccion_provincia' => $reg->direccion_provincia,
				'direccion_distrito' => $reg->direccion_distrito,
				'direccion_calle' => $reg->direccion_calle,
				'telefono' => $reg->telefono,
				'telefono_2' => $reg->telefono_2,
				'email' => $reg->email,
				'numero_cuenta' => $reg->numero_cuenta,
				'estado_cliente' => $reg->estado,
				'estadoCuenta' => $estadoCuenta,
				'genero' => $reg->genero,
				'idEmpleado_modificado' => $reg->idempleado,
				'genero_txt' => $reg->genero_txt
				//'cuenta' => 'Antiguo'
			);

			//echo json_encode($datos, true);

		}

		echo json_encode($datos, true);

	break;


	case "cambiarEstadoCliente_final":

		require_once "../model/Persona.php";
        $objPersona = new Persona();

		$tipo = $_REQUEST["tipo"];

		$rpta = $objPersona->cambiarEstadoCliente_final($tipo);

		echo json_encode($rpta, true);

	break;

	case "cambiarEstadoCliente_distribuidor":

		require_once "../model/Persona.php";
        $objPersona = new Persona();

		$tipo = $_REQUEST["tipo"];

		$rpta = $objPersona->cambiarEstadoCliente_distribuidor($tipo);

		echo json_encode($rpta, true);

	break;

	case "cambiarEstadoCliente_representante":

		require_once "../model/Persona.php";
        $objPersona = new Persona();

		$tipo = $_REQUEST["tipo"];

		$rpta = $objPersona->cambiarEstadoCliente_representante($tipo);

		echo json_encode($rpta, true);

	break;


}