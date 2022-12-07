<?php
session_start();
require_once "../model/Persona.php";
$objCliente = new Persona();
switch ($_GET["op"]) {

	case 'SaveOrUpdate':
		$tipo_persona = $_POST["cboTipo_Persona"];
		$nombre = mb_strtoupper($_POST["txtNombre"]);
		$apellido = mb_strtoupper($_POST["txtApellido"]);
		$tipo_documento = $_POST["cboTipo_Documento"];
		$num_documento = $_POST["txtNum_Documento"];
		$direccion_departamento = isset($_POST["txtDireccion_Departamento"]) ? $_POST["txtDireccion_Departamento"] : "";
		$direccion_provincia = isset($_POST["txtDireccion_Provincia"]) ? $_POST["txtDireccion_Provincia"] : "";
		$direccion_distrito = isset($_POST["txtDireccion_Distrito"]) ? $_POST["txtDireccion_Distrito"] : "";
		$direccion_calle = isset($_POST["txtDireccion_Calle"]) ? $_POST["txtDireccion_Calle"] : "";
		$telefono = isset($_POST["txtTelefono"]) ? $_POST["txtTelefono"] : "";
		$telefono_2 = isset($_POST["txtTelefono_2"]) ? $_POST["txtTelefono_2"] : "";
		$email = isset($_POST["txtEmail"]) ? $_POST["txtEmail"] : "";
		$numero_cuenta = isset($_POST["txtNumero_Cuenta"]) ? $_POST["txtNumero_Cuenta"] : "";
		$estado = $_POST["txtEstado"];
		$idempleado = $_POST["txtIdEmpleado"];
		$idempleado = isset($_POST["txtIdEmpleado_modificado"]) ? $_POST["txtIdEmpleado_modificado"] : "";

		if (empty($_POST["txtIdPersona"])) {
			if ($objCliente->Registrar($tipo_persona, $nombre, $apellido, $tipo_documento, $num_documento, $direccion_departamento, $direccion_provincia, $direccion_distrito, $direccion_calle, $telefono, $telefono_2, $email, $numero_cuenta, $estado, $idempleado, $idempleado)) {
				echo "Cliente registrado correctamente";
			} else {
				echo "El Cliente no ha podido ser registrado.";
			}
		} else {
			$idpersona = $_POST["txtIdPersona"];
			if ($objCliente->Modificar($idpersona, $tipo_persona, $nombre, $apellido, $tipo_documento, $num_documento, $direccion_departamento, $direccion_provincia, $direccion_distrito, $direccion_calle, $telefono, $telefono_2, $email, $numero_cuenta, $estado, $idempleado)) {
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
				"11" => '<button class="btn btn-warning" data-toggle="tooltip" title="Editar" onclick="cargarDataCliente(' . $reg->idpersona . ',\'' . $reg->tipo_persona . '\',\'' . $reg->nombre . '\',\'' . $reg->apellido . '\',\'' . $reg->tipo_documento . '\',\'' . $reg->num_documento . '\',\'' . $reg->direccion_departamento . '\',\'' . $reg->direccion_provincia . '\',\'' . $reg->direccion_distrito . '\',\'' . $reg->direccion_calle . '\',\'' . $reg->telefono . '\',\'' . $reg->telefono_2 . '\',\'' . $reg->email . '\',\'' . $reg->numero_cuenta . '\',\'' . $reg->estado . '\',\'' . $reg->idempleado . '\',\'' . $reg->empleado . '\',\'' . $reg->fecha_registro . '\',\'' . $reg->empleado_modificado . '\',\'' . $reg->fecha_modificado . '\')"><i class="fa fa-pencil"></i> </button>&nbsp;' .
					'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarCliente(' . $reg->idpersona . ')"><i class="fa fa-trash"></i> </button>'
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

	/*
	case "buscarClienteSunat":
		//require_once "../public/curl/Curl.php";
		//$server = $_SERVER["HTTP_HOST"];
		$numerodoc = $_REQUEST["numerodoc"];
		$origen = $_REQUEST["origen"];
		// COMPROBAR SI EL CLIENTE YA SE ENCUENTRA REGITRADO EN BASE DE DATOS
		$rptaBuscarClientePorNroDoc = $objCliente->BuscarClientePorNroDoc($numerodoc);
		$reg = $rptaBuscarClientePorNroDoc->fetch_object();

		if (is_null($reg)) {

			if (strlen($numerodoc) == 8) {

				$headers = get_headers("https://api.apis.net.pe/v1/dni?numero=" . $numerodoc);
				$raptaURL = substr($headers[0], 9, 3);

				if ($raptaURL != '404') {

					$data = file_get_contents("https://api.apis.net.pe/v1/dni?numero=" . $numerodoc);
					$info = json_decode($data, true);

					$tipo_persona = 'Cliente';
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
					$numero_cuenta = '';
					$estado = 'A';

					// CONSULTA VARIABLE ORIDEN
					// moduloVenta -> viene desde consulta en venta - insertar y se devuelve datos
					// moduloCliente -> viene desde consulta en nuevo cliente - solo devuelve datos

					//var_dump($origen);
					//exit;

	
					if ($origen == "moduloCliente") {
						$datos = array(
							'estado' => 'encontrado',
							'nombre' => $nombre,
							'apellido' => $apellido,
							'numeroDocumento' => $num_documento,
							'tipoDocumento' => $tipo_documento,
						);
						//echo  "moduloCliente";
					}
					//var_dump($datos);
					//exit;
				} else {
					// SI NO SE ENCUENTRA NUMERO DE DOCUMENTO EN API NI EN BASE DE DATOS
					$datos = array(
						'estado' => 'no_encontrado',
						'idCliente' => "",
						'nombre' => "",
						'apellido' => "",
						'numeroDocumento' => $numerodoc,
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

				
					if ($origen == "moduloCliente") {

						// SI NO SE ENCUENTRA NUMERO DE DOCUMENTO EN API NI EN BASE DE DATOS
						$datos = array(
							'estado' => 'no_encontrado',
							'idCliente' => "",
							'nombre' => "",
							'apellido' => "",
							'numeroDocumento' => $numerodoc,
						);
					}
					}
				} else {
					// SI EL TIPO DE DOCUEMNTO ES DIFERENTE A 8 DIGITOS O A 11
					$datos = array(
						'estado' => 'no_encontrado',
						'idCliente' => "",
						'nombre' => "",
						'apellido' => "",
						'numeroDocumento' => $numerodoc,
						//'cuenta' => 'Nuevo'
					);
				}
			} else {
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
					//'cuenta' => 'Antiguo'
				);
			}
			//var_dump($info);exit;
			echo json_encode($datos, true);
			break;
		}
		*/
		/* case "buscarDatosCliente":
					//require_once "../public/curl/Curl.php";
					//$server = $_SERVER["HTTP_HOST"];
					$idCliente = $_REQUEST["idCliente"];
					//var_dump($idCliente);exit;
					// COMPROBAR SI EL CLIENTEYA SE ENCUENTRA REGITRADO EN BASE DE DATOS
					$rptaBuscarClientePorNroDoc = $objVenta->BuscarClientePorNroDoc($numerodoc);
					$reg = $rptaBuscarClientePorNroDoc->fetch_object();
					//var_dump($reg);exit;
					if (is_null($reg)) {
						if (strlen($numerodoc) == 8) {
							$data = file_get_contents("https://api.apis.net.pe/v1/dni?numero=".$numerodoc);
							$info = json_decode($data,true);
							/*$datos = array(
								'estado' => 'encontrado', 
								'numeroDocumento' => $info['numeroDocumento'],
								'apellidoPaterno' => $info['apellidoPaterno'],
								'apellidoMaterno' => $info['apellidoMaterno'],
								'nombres' => $info['nombres']);//

							$tipo_persona = 'CLIENTE';
							$nombre = $info['nombres'];
							$apellido = $info['apellidoPaterno'].' '.$info['apellidoMaterno'];
							$tipo_documento = 'DNI';
							$num_documento = $info['numeroDocumento'];
							$direccion_departamento = '';
							$direccion_provincia = '';
							$direccion_distrito = '';
							$direccion_calle = '';
							$telefono = '';
							$telefono_2 = 0;
							$email = '';
							$numero_cuenta = '';
							$estado = 'A';
	
							// REGISTRA CLIENTE CON DATOS ENCONTRADOS DE SUNAT
							if($objVenta->RegistrarCliente($tipo_persona,$nombre,$apellido,$tipo_documento,$num_documento,$direccion_departamento,$direccion_provincia,$direccion_distrito,$direccion_calle,$telefono,$telefono_2,$email,$numero_cuenta,$estado)){
								// BUSCA ID DE CLIENTE REGISTRADO
								$rptaBuscarClientePorNroDoc = $objVenta->BuscarClientePorNroDoc($num_documento);
								$reg = $rptaBuscarClientePorNroDoc->fetch_object();
								$datos = array(
									'estado' => 'encontrado', 
									'idCliente' => $reg->idpersona,
									'nombre' => $nombre.' '.$apellido, 
									'numeroDocumento' => $num_documento
								);
							}else{
								$datos = array(
									'estado' => 'error'
								);
							}
						}else if(strlen($numerodoc) == 11){
							$data = file_get_contents("https://api.apis.net.pe/v1/ruc?numero=".$numerodoc);
							$info = json_decode($data,true);
							/*
							$datos = array(
								'estado' => 'encontrado', 
								'nombre' => $info['nombre'], 
								'numeroDocumento' => $info['numeroDocumento'],
								'direccion' => $info['direccion'],
								'zonaCodigo' => $info['zonaCodigo'],
								'zonaTipo' => $info['zonaTipo'],
								'distrito' => $info['distrito'],
								'provincia' => $info['provincia'],
								'departamento' => $info['departamento']
							);
							////////////
							$tipo_persona = 'CLIENTE';
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
							// REGISTRA CLIENTE CON DATOS ENCONTRADOS DE SUNAT
							if($objVenta->RegistrarCliente($tipo_persona,$nombre,$apellido,$tipo_documento,$num_documento,$direccion_departamento,$direccion_provincia,$direccion_distrito,$direccion_calle,$telefono,$telefono_2,$email,$numero_cuenta,$estado)){
								// BUSCA ID DE CLIENTE REGISTRADO
								$rptaBuscarClientePorNroDoc = $objVenta->BuscarClientePorNroDoc($num_documento);
								$reg = $rptaBuscarClientePorNroDoc->fetch_object();
								$datos = array(
									'estado' => 'encontrado',
									'idCliente' => $reg->idpersona,
									'nombre' => $nombre.' '.$apellido,
									'numeroDocumento' => $num_documento
								);
							}else{
								$datos = array(
									'estado' => 'error'
								);
							}
						} else{
							$datos = array(
								'estado' => 'error'
							);
						}
					}else{
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
							'estado' => $reg->estado
						);
					}
					//var_dump($info);exit;
					echo json_encode($datos,true);	
					break; */
}