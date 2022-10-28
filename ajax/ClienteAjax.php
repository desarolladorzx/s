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
			$direccion_departamento = isset($_POST["txtDireccion_Departamento"])?$_POST["txtDireccion_Departamento"]:"";
			$direccion_provincia = isset($_POST["txtDireccion_Provincia"])?$_POST["txtDireccion_Provincia"]:"";
			$direccion_distrito = isset($_POST["txtDireccion_Distrito"])?$_POST["txtDireccion_Distrito"]:"";
			$direccion_calle = isset($_POST["txtDireccion_Calle"])?$_POST["txtDireccion_Calle"]:"";
			$telefono = isset($_POST["txtTelefono"])?$_POST["txtTelefono"]:"";
			$telefono_2 = isset($_POST["txtTelefono_2"])?$_POST["txtTelefono_2"]:"";
			$email = isset($_POST["txtEmail"])?$_POST["txtEmail"]:"";
			$numero_cuenta = isset($_POST["txtNumero_Cuenta"])?$_POST["txtNumero_Cuenta"]:"";
			$estado = $_POST["txtEstado"];

			if(empty($_POST["txtIdPersona"])){
				if($objCliente->Registrar($tipo_persona,$nombre,$apellido,$tipo_documento,$num_documento,$direccion_departamento,$direccion_provincia,$direccion_distrito,$direccion_calle,$telefono,$telefono_2,$email,$numero_cuenta,$estado)){
					echo "Cliente registrado correctamente";
				}else{
					echo "El Cliente no ha podido ser registrado.";
				}
			}else{
				$idpersona = $_POST["txtIdPersona"];
				if($objCliente->Modificar($idpersona,$tipo_persona,$nombre,$apellido,$tipo_documento,$num_documento,$direccion_departamento,$direccion_provincia,$direccion_distrito,$direccion_calle,$telefono,$telefono_2,$email,$numero_cuenta,$estado)){
					echo "La informacion del Cliente ha sido actualizada";
				}else{
					echo "La informacion del Cliente no ha podido ser actualizada.";
				}
			}
			break;

		case "delete":			
			$id = $_POST["id"];// Llamamos a la variable id del js que mandamos por $.post (Categoria.js (Linea 62))
			$result = $objCliente->Eliminar($id);
			if ($result) {
				echo "Eliminado Exitosamente";
			} else {
				echo "No fue Eliminado";
			}
			break;

		case "list":
			$query_Tipo = $objCliente->ListarCliente();
			$data = Array();
            $i = 1;
     		while ($reg = $query_Tipo->fetch_object()) {
     			$data[] = array(
					"id"=>$i,
					"1"=>$reg->tipo_persona.' - '.$reg->numero_cuenta,
					"2"=>$reg->nombre.'&nbsp;'.$reg->apellido,
					"3"=>$reg->email,
					"4"=>$reg->tipo_documento.': '.$reg->num_documento,
					"5"=>$reg->telefono.' - '.$reg->telefono_2,
					"6"=>$reg->direccion_calle,
					"7"=>'<button class="btn btn-warning" data-toggle="tooltip" title="Editar" onclick="cargarDataCliente('.$reg->idpersona.',\''.$reg->tipo_persona.'\',\''.$reg->nombre.'\',\''.$reg->apellido.'\',\''.$reg->tipo_documento.'\',\''.$reg->num_documento.'\',\''.$reg->direccion_departamento.'\',\''.$reg->direccion_provincia.'\',\''.$reg->direccion_distrito.'\',\''.$reg->direccion_calle.'\',\''.$reg->telefono.'\',\''.$reg->telefono_2.'\',\''.$reg->email.'\',\''.$reg->numero_cuenta.'\',\''.$reg->estado.'\')"><i class="fa fa-pencil"></i> </button>&nbsp;'.
				    '<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarCliente('.$reg->idpersona.')"><i class="fa fa-trash"></i> </button>');
				$i++;
			}
			$results = array(
            "sEcho" => 1,
        	"iTotalRecords" => count($data),
        	"iTotalDisplayRecords" => count($data),
            "aaData"=>$data);
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
	}