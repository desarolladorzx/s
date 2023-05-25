<?php

session_start();

require_once "../model/Usuario.php";

$objusuario = new usuario();

switch ($_GET["op"]) {


	case "Container_cambiarSucursal_global":
		require_once "../model/Sucursal.php";
		$objSucursal = new Sucursal();
		$query_Tipo = $objSucursal->ListarSucursalesEmp($_SESSION["idempleado"]);

		while ($reg = $query_Tipo->fetch_object()) {
			echo '<button type="button" onclick="NuevoAcceder('.$reg->idusuario.',\''.$reg->idsucursal.'\',\''.$reg->idempleado.'\',\''.$reg->empleado.'\',\''.$reg->tipo_documento.'\',\''.$reg->tipo_usuario.'\',\''.$reg->num_documento.'\',\''.$reg->direccion.'\',\''.$reg->telefono.'\',\''.$reg->foto.'\',\''.$reg->logo.'\',\''.$reg->email.'\',\''.$reg->login.'\',\''.$reg->razon_social.'\',\''.$reg->mnu_almacen.'\',\''.$reg->mnu_compras.'\',\''.$reg->mnu_ventas.'\',\''.$reg->mnu_mantenimiento.'\',\''.$reg->mnu_seguridad.'\',\''.$reg->mnu_consulta_compras.'\',\''.$reg->mnu_consulta_ventas.'\',\''.$reg->mnu_admin.'\',\''.$reg->superadmin.'\',\''.$reg->idrol.'\')" class="btn btn-info pull-left">'.explode("-", $reg->razon_social)[0].'</button>
		  ';
		}
		// echo 'hola';
		break;
	case 'SaveOrUpdate':
		$alm = 0;
		$comp = 0;
		$vent = 0;
		$mant = 0;
		$seg = 0;
		$cons_comp = 0;
		$cons_vent = 0;
		$admin = 0;

		$idsucursal = $_POST["cboSucursal"];
		$idempleado = $_POST["txtIdEmpleado"];
		$tipo_usuario = $_POST["cboTipoUsuario"];
		//$mnu_almacen = $_POST["chkMnuAlmacen"];
		if (isset($_POST["chkMnuAlmacen"])) {
			$alm = true;
		} else {
			$alm = 0;
		}

		if (isset($_POST["chkMnuCompras"])) {
			$comp = true;
		} else {
			$comp = 0;
		}

		if (isset($_POST["chkMnuVentas"])) {
			$vent = true;
		} else {
			$vent = 0;
		}
		if (isset($_POST["chkMnuMantenimiento"])) {
			$mant = true;
		} else {
			$mant = 0;
		}

		if (isset($_POST["chkMnuSeguridad"])) {
			$seg = true;
		} else {
			$seg = 0;
		}
		if (isset($_POST["chkMnuConsultaCompras"])) {
			$cons_comp = true;
		} else {
			$cons_comp = 0;
		}
		if (isset($_POST["chkMnuConsultaVentas"])) {
			$cons_vent = true;
		} else {
			$cons_vent = 0;
		}
		if (isset($_POST["chkMnuDocEV"])) {
			$docs_ev = true;
		} else {
			$docs_ev = 0;
		}
		if (isset($_POST["chkMnuDocJV"])) {
			$docs_jv = true;
		} else {
			$docs_jv = 0;
		}
		if (isset($_POST["chkMnuDocJA"])) {
			$docs_ja = true;
		} else {
			$docs_ja = 0;
		}
		if (isset($_POST["chkMnuDocJL"])) {
			$docs_jl = true;
		} else {
			$docs_jl = 0;
		}
		if (isset($_POST["chkMnuAdmin"])) {
			$admin = true;
		} else {
			$admin = 0;
		}

		// print_r($_POST);
// documentacion
		if (isset($_POST["chkMnuDocumentacion"])) {
			$chkMnuDocumentacion = true;
		} else {
			$chkMnuDocumentacion = 0;
		}

		if (isset($_POST["chkMnuDocVentas"])) {
			$chkMnuDocVentas = true;
		} else {
			$chkMnuDocVentas = 0;
		}

		if (isset($_POST["chkMnuDocMarketing"])) {
			$chkMnuDocMarketing = true;
		} else {
			$chkMnuDocMarketing = 0;
		}

		if (isset($_POST["chkMnuDocLogistica"])) {
			$chkMnuDocLogistica = true;
		} else {
			$chkMnuDocLogistica = 0;
		}

		if (isset($_POST["chkMnuDocFinanzas"])) {
			$chkMnuDocFinanzas = true;
		} else {
			$chkMnuDocFinanzas = 0;
		}

		if (isset($_POST["chkMnuDocRRHH"])) {
			$chkMnuDocRRHH = true;
		} else {
			$chkMnuDocRRHH = 0;
		}

		if (isset($_POST["chkMnuDocIT"])) {
			$chkMnuDocIT = true;
		} else {
			$chkMnuDocIT = 0;
		}

		if (isset($_POST["chkMnuDoProduccion"])) {
			$chkMnuDoProduccion = true;
		} else {
			$chkMnuDoProduccion = 0;
		}
// documentacion

		

		if (empty($_POST["txtIdUsuario"])) {

			if ($objusuario->Registrar(
				$idsucursal,
				$idempleado,
				$tipo_usuario,
				$alm,
				$comp,
				$vent,
				$mant,
				$seg,
				$cons_comp,
				$cons_vent,
				$docs_ev,
				$docs_jv,
				$docs_ja,
				$docs_jl,
				$admin,
// documentacion
$chkMnuDocumentacion,
$chkMnuDocVentas,
$chkMnuDocMarketing,
$chkMnuDocLogistica,
$chkMnuDocFinanzas,
$chkMnuDocRRHH,
$chkMnuDocIT,
$chkMnuDoProduccion
// 
			)) {
				echo "Registrado Exitosamente";
			} else {
				echo "Usuario no ha podido ser registado.";
			}
		} else {

			$idusuario = $_POST["txtIdUsuario"];
			if ($objusuario->Modificar(
				$idusuario,
				$idsucursal,
				$idempleado,
				$tipo_usuario,
				$alm,
				$comp,
				$vent,
				$mant,
				$seg,
				$cons_comp,
				$cons_vent,
				$docs_ev,
				$docs_jv,
				$docs_ja,
				$docs_jl,
				$admin
				// documentacion
				,$chkMnuDocumentacion,
				$chkMnuDocVentas,
				$chkMnuDocMarketing,
				$chkMnuDocLogistica,
				$chkMnuDocFinanzas,
				$chkMnuDocRRHH,
				$chkMnuDocIT,
				$chkMnuDoProduccion

				// documentacion 
			)) {
				echo "Informacion del Usuario ha sido actualizada";
			} else {
				echo "Informacion del usuario no ha podido ser actualizada.";
			}
		}

		break;

	case "delete":

		$id = $_POST["id"];
		$result = $objusuario->Eliminar($id);
		if ($result) {
			echo "Eliminado Exitosamente";
		} else {
			echo "No fue Eliminado";
		}
		break;

	case "list":
		$query_Tipo = $objusuario->Listar();
		$data = array();
		$i = 1;
		while ($reg = $query_Tipo->fetch_object()) {
			$data[] = array(
				"0" => $i,
				"1" => $reg->razon_social,
				"2" => $reg->empleado,
				"3" => $reg->tipo_usuario,
				"4" => $reg->fecha_registro,
				"5" => '<button class="btn btn-warning" data-toggle="tooltip" title="Editar" onclick="cargarDataUsuario(' . $reg->idusuario . ',\'' . $reg->idsucursal . '\',\'' . $reg->idempleado . '\',\'' . $reg->empleado . '\',\'' . $reg->tipo_usuario . '\',\'' . $reg->mnu_almacen . '\',\'' . $reg->mnu_compras . '\',\'' . $reg->mnu_ventas . '\',\'' . $reg->mnu_mantenimiento . '\',\'' . $reg->mnu_seguridad . '\',\'' . $reg->mnu_consulta_compras . '\',\'' . $reg->mnu_consulta_ventas . '\',\'' . $reg->mnu_documentacion_ev . '\',\'' . $reg->mnu_documentacion_jv . '\',\'' . $reg->mnu_documentacion_ja . '\',\'' . $reg->mnu_documentacion_jl . '\',\'' . $reg->mnu_admin . '\'
				
				,\'' . $reg->mnu_documentacion . '\'
				,\'' . $reg->mnu_documentacion_marketing . '\'
				,\'' . $reg->mnu_documentacion_finanzas . '\'
				,\'' . $reg->mnu_documentacion_logistica . '\'
				,\'' . $reg->mnu_documentacion_ventas . '\'
				,\'' . $reg->mnu_documentacion_it . '\'
				,\'' . $reg->mnu_documentacion_rrhh . '\'
				,\'' . $reg->mnu_documentacion_produccion . '\'

				)"><i class="fa fa-pencil"></i> </button>&nbsp;' .
					'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarUsuario(' . $reg->idusuario . ')"><i class="fa fa-trash"></i> </button>'
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

	case "listSucursal":
		require_once "../model/Categoria.php";

		$objCategoria = new Categoria();

		$query = $objCategoria->ListarSucursal();

		while ($reg = $query->fetch_object()) {
			echo '<option value=' . $reg->idsucursal . '>' . $reg->razon_social . '</option>';
		}

		break;

	case "listEmpleado":

		require_once "../model/Categoria.php";

		$objCategoria = new Categoria();

		$query_Categoria = $objCategoria->ListarEmpleado();

		$i = 1;

		while ($reg = $query_Categoria->fetch_object()) {
			echo '<tr>		                
		                <td><input type="radio" name="optEmpleadosBusqueda" data-nombre="' . $reg->nombre . '" data-apellidos="' . $reg->apellidos . '" id="' . $reg->idempleado . '" value="' . $reg->idempleado . '" /></td>
		                <td>' . $i . '</td>
		                <td>' . $reg->apellidos . '</td>
		                <td>' . $reg->nombre . '</td>
		                <td>' . $reg->tipo_documento . '</td>
		                <td>' . $reg->num_documento . '</td>
		                <td>' . $reg->email . '</td>
		                <td><img width=100px height=100px src="./' . $reg->foto . '" /></td>		                
	                   </tr>';
		}

		break;

	case "IngresarSistema":

		$user = $_REQUEST["user"];
		$pass = $_REQUEST["pass"];

		$query = $objusuario->Ingresar_Sistema($user, md5($pass));
		$fetch = $query->fetch_object();

		echo json_encode($fetch);


		if (isset($fetch)) {
			$_SESSION["idusuario"] = $fetch->idusuario;
			$_SESSION["idempleado"] = $fetch->idempleado;
			$_SESSION["empleado"] = $fetch->empleado;
			$_SESSION["tipo_documento"] = $fetch->tipo_documento;
			$_SESSION["tipo_usuario"] = $fetch->tipo_usuario;
			$_SESSION["num_documento"] = $fetch->num_documento;
			$_SESSION["direccion"] = $fetch->direccion;
			$_SESSION["telefono"] = $fetch->telefono;
			$_SESSION["foto"] = $fetch->foto;
			$_SESSION["logo"] = $fetch->logo;
			$_SESSION["email"] = $fetch->email;
			$_SESSION["login"] = $fetch->login;
			$_SESSION["razon_social"] = $fetch->razon_social;
			$_SESSION["mnu_almacen"] = $fetch->mnu_almacen;
			$_SESSION["mnu_compras"] = $fetch->mnu_compras;
			$_SESSION["mnu_ventas"] = $fetch->mnu_ventas;
			$_SESSION["mnu_mantenimiento"] = $fetch->mnu_mantenimiento;
			$_SESSION["mnu_seguridad"] = $fetch->mnu_seguridad;
			$_SESSION["mnu_consulta_compras"] = $fetch->mnu_consulta_compras;
			$_SESSION["mnu_consulta_ventas"] = $fetch->mnu_consulta_ventas;
			$_SESSION["mnu_documentacion_ev"] = $fetch->mnu_documentacion_ev;
			$_SESSION["mnu_documentacion_jv"] = $fetch->mnu_documentacion_jv;
			$_SESSION["mnu_documentacion_ja"] = $fetch->mnu_documentacion_ja;
			$_SESSION["mnu_documentacion_jl"] = $fetch->mnu_documentacion_jl;
			$_SESSION["mnu_admin"] = $fetch->mnu_admin;
			$_SESSION["superadmin"] = $fetch->superadmin;
			$_SESSION["rol"] = $fetch->rol_id;

			// documentacion
			$_SESSION["mnu_documentacion_produccion"] = $fetch->mnu_documentacion_produccion;
			$_SESSION["mnu_documentacion_ventas"] = $fetch->mnu_documentacion_ventas;
			$_SESSION["mnu_documentacion_logistica"] = $fetch->mnu_documentacion_logistica;
			$_SESSION["mnu_documentacion_finanzas"] = $fetch->mnu_documentacion_finanzas;
			$_SESSION["mnu_documentacion_marketing"] = $fetch->mnu_documentacion_marketing;
			$_SESSION["mnu_documentacion_it"] = $fetch->mnu_documentacion_it;
			$_SESSION["mnu_documentacion_rrhh"] = $fetch->mnu_documentacion_rrhh;
			$_SESSION["mnu_documentacion"] = $fetch->mnu_documentacion;
			// 
		}
		break;

	case "IngresarPanel":
		$_SESSION["idusuario"] = $_POST["idusuario"];
		$_SESSION["idsucursal"] = $_POST["idsucursal"];
		$_SESSION["idempleado"] = $_POST["idempleado"];
		$_SESSION["superadmin"] = "A";
		$_SESSION["empleado"] = $_POST["empleado"];
		$_SESSION["tipo_documento"] = $_POST["tipo_documento"];
		$_SESSION["tipo_usuario"] = $_POST["tipo_usuario"];
		$_SESSION["num_documento"] = $_POST["num_documento"];
		$_SESSION["direccion"] = $_POST["direccion"];
		$_SESSION["telefono"] = $_POST["telefono"];
		$_SESSION["foto"] = $_POST["foto"];
		$_SESSION["logo"] = $_POST["logo"];
		$_SESSION["email"] = $_POST["email"];
		$_SESSION["login"] = $_POST["login"];
		$_SESSION["sucursal"] = $_POST["razon_social"];
		$_SESSION["mnu_almacen"] = $_POST["mnu_almacen"];
		$_SESSION["mnu_compras"] = $_POST["mnu_compras"];
		$_SESSION["mnu_ventas"] = $_POST["mnu_ventas"];
		$_SESSION["mnu_mantenimiento"] = $_POST["mnu_mantenimiento"];
		$_SESSION["mnu_seguridad"] = $_POST["mnu_seguridad"];
		$_SESSION["mnu_consulta_compras"] = $_POST["mnu_consulta_compras"];
		$_SESSION["mnu_consulta_ventas"] = $_POST["mnu_consulta_ventas"];
		$_SESSION["mnu_documentacion_ev"] = $_POST["mnu_documentacion_ev"];
		$_SESSION["mnu_documentacion_jv"] = $_POST["mnu_documentacion_jv"];
		$_SESSION["mnu_documentacion_ja"] = $_POST["mnu_documentacion_ja"];
		$_SESSION["mnu_documentacion_jl"] = $_POST["mnu_documentacion_jl"];
		$_SESSION["mnu_admin"] = $_POST["mnu_admin"];

		$_SESSION["idrol"] = $_POST["idrol"];


		/*$_SESSION["rol"] = $fetch->rol_id;*/
		$_SESSION["rol_usuario"] = $_POST["superadmin"];
		break;

	case "CambiarSucursal":
		$_SESSION["idsucursal"] = $_POST["idsucursal"];
		$_SESSION["sucursal"] = $_POST["razon_social"];

		break;


	case "IngresarPanelSuperAdmin":
		$_SESSION["idusuario"] = $_POST["idusuario"];
		$_SESSION["idsucursal"] = $_POST["idsucursal"];
		$_SESSION["idempleado"] = $_POST["idempleado"];
		$_SESSION["superadmin"] = $_POST["estadoAdmin"];
		$_SESSION["empleado"] = $_POST["empleado"];
		$_SESSION["tipo_documento"] = $_POST["tipo_documento"];
		$_SESSION["tipo_usuario"] = $_POST["tipo_usuario"];
		$_SESSION["num_documento"] = $_POST["num_documento"];
		$_SESSION["direccion"] = $_POST["direccion"];
		$_SESSION["telefono"] = $_POST["telefono"];
		$_SESSION["foto"] = $_POST["foto"];
		$_SESSION["logo"] = $_POST["logo"];
		$_SESSION["email"] = $_POST["email"];
		$_SESSION["login"] = $_POST["login"];
		$_SESSION["sucursal"] = $_POST["razon_social"];
		$_SESSION["mnu_almacen"] = $_POST["mnu_almacen"];
		$_SESSION["mnu_compras"] = $_POST["mnu_compras"];
		$_SESSION["mnu_ventas"] = $_POST["mnu_ventas"];
		$_SESSION["mnu_mantenimiento"] = $_POST["mnu_mantenimiento"];
		$_SESSION["mnu_seguridad"] = $_POST["mnu_seguridad"];
		$_SESSION["mnu_consulta_compras"] = $_POST["mnu_consulta_compras"];
		$_SESSION["mnu_consulta_ventas"] = $_POST["mnu_consulta_ventas"];
		$_SESSION["mnu_documentacion_ev"] = $_POST["mnu_documentacion_ev"];
		$_SESSION["mnu_documentacion_jv"] = $_POST["mnu_documentacion_jv"];
		$_SESSION["mnu_documentacion_ja"] = $_POST["mnu_documentacion_ja"];
		$_SESSION["mnu_documentacion_jl"] = $_POST["mnu_documentacion_jl"];
		$_SESSION["mnu_admin"] = $_POST["mnu_admin"];
		//$_SESSION["rol"] = $fetch->rol_id;
		$_SESSION["rol_usuario"] = $_POST["superadmin"];
		break;

	case "Salir":
		session_unset();
		session_destroy();
		header("Location:../");
		break;
}
