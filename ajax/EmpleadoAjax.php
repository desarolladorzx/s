
<?php

session_start();

require_once "../model/Empleado.php";

$objEmpleado = new Empleado();

switch ($_GET["op"]) {


	case 'AnadirHijooActualizar':


		if (strlen($_POST['iddetalle_empleado_hijo']) > 0) {
			//si existe  un id_detalle_empleado_hijo entonces se actualizara el hijo segun su id  
			$objEmpleado->actualizarHijo($_POST);
		} else {
			//si no existe el id_detalle_empleado_hijo ese agregar un nuevo hijo al emplkeado 
			$objEmpleado->anadirHijo($_POST);
		}


		break;

	case 'TraerEmpleado':


		// trae los datos  del empleado segun el id
		$query = $objEmpleado->TraerDatosEmpleado($_GET['id'])->fetch_object();

		echo  json_encode($query);

		break;
	case 'TraerRol':
		// trae los roles de la tabla roles
		$query_Tipo = $objEmpleado->traerRol();

		$nuevo = array();
		while ($reg = $query_Tipo->fetch_object()) {
			$nuevo[] = $reg;
		}
		echo  json_encode($nuevo);
		break;

	case 'RenovarContrato':
		// print_r($_FILES);



		$contrato_trabajo = $_FILES["contrato_trabajo"]["tmp_name"];
		$contrato_trabajo_name = $_FILES["contrato_trabajo"]["name"];

		$parte = explode(".", $contrato_trabajo_name);
		$codigoInterno = strtotime(date('Y-m-d H:i:s'));
		$new_file_name_contrato = str_replace(' ', '-', 'contrato' . '-' . $codigoInterno . '.' . $parte[1]);

		move_uploaded_file($contrato_trabajo, "../Files/Contrato/" . $new_file_name_contrato);

		// insercion de dni

		if ($_FILES["dniFile"]["size"] !== 0) {


			$dniFile = $_FILES["dniFile"]["tmp_name"];
			$dniFile_name = $_FILES["dniFile"]["name"];

			$parte = explode(".", $dniFile_name);
			$codigoInterno = strtotime(date('Y-m-d H:i:s'));
			$new_file_name_dni = str_replace(' ', '-', 'dni' . '-' . $codigoInterno . '.' . $parte[1]);

			move_uploaded_file($dniFile, "../Files/DNI/" . $new_file_name_dni);
		} else {
			$new_file_name_dni = '';
		}



		//INSERCION DE CV

		if ($_FILES["cv_file"]["size"] !== 0) {

			$cv_file = $_FILES["cv_file"]["tmp_name"];
			$cv_file_name = $_FILES["cv_file"]["name"];


			$parte = explode(".", $cv_file_name);
			$codigoInterno = strtotime(date('Y-m-d H:i:s'));
			$new_file_name_cv = str_replace(' ', '-', 'cv' . '-' . $codigoInterno . '.' . $parte[1]);

			move_uploaded_file($cv_file, "../Files/CV/" . $new_file_name_cv);
		} else {
			$new_file_name_cv = '';
		}



		// INSERCION DE RIT

		if ($_FILES["registro_RIT"]["size"] !== 0) {
			$registro_RIT = $_FILES["registro_RIT"]["tmp_name"];
			$registro_RIT_name = $_FILES["registro_RIT"]["name"];

			$parte = explode(".", $registro_RIT_name);
			$codigoInterno = strtotime(date('Y-m-d H:i:s'));
			$new_file_name_RIT = str_replace(' ', '-', 'rit'. '-' . $codigoInterno . '.' . $parte[1]);

			move_uploaded_file($registro_RIT, "../Files/RIT/" . $new_file_name_RIT);
		} else {
			$new_file_name_RIT = '';
		}



		// INSERCION DE ANTECEDENTES

		if ($_FILES["antecedentes"]["size"] !== 0) {

			$antecedentes = $_FILES["antecedentes"]["tmp_name"];
			$antecedentes_name = $_FILES["antecedentes"]["name"];

			$parte = explode(".", $antecedentes_name);
			$codigoInterno = strtotime(date('Y-m-d H:i:s'));
			$new_file_name_antecedentes = str_replace(' ', '-', 'antecendentes' . '-' . $codigoInterno . '.' . $parte[1]);

			move_uploaded_file($antecedentes, "../Files/Antecedentes/" . $new_file_name_antecedentes);
		} else {
			$new_file_name_antecedentes = '';
		}





		//  INSERCION DE declaracion jurada
		if ($_FILES["declaracion_jurada"]["size"] !== 0) {
			$declaracion_jurada = $_FILES["declaracion_jurada"]["tmp_name"];
			$declaracion_jurada_name = $_FILES["declaracion_jurada"]["name"];


			$parte = explode(".", $declaracion_jurada_name);
			$codigoInterno = strtotime(date('Y-m-d H:i:s'));
			$new_file_name_declaracion_jurada = str_replace(' ', '-', 'declaracion_jurada'. '-' . $codigoInterno . '.' . $parte[1]);

			move_uploaded_file($declaracion_jurada, "../Files/DeclaracionJurada/" . $new_file_name_declaracion_jurada);
		} else {
			$new_file_name_declaracion_jurada = '';
		}



		$objEmpleado->RenovarContrato(
			$_POST['txtIdEmpleado'],
			$new_file_name_contrato,
			$new_file_name_dni,
			$new_file_name_cv,
			$new_file_name_RIT,
			$new_file_name_antecedentes,
			$new_file_name_declaracion_jurada,
			// $_POST['txtfecha_inicio_labores'],
			date('Y-m-d'),
			$_POST['txtfecha_fin_labores'],
			$_POST['txtrazon_social'],
		);



		break;
	case 'SaveOrUpdate':

// si el el elemento txtIdEmpleado no existe entonces registrara  la funcion 
		if (strlen($_POST['txtIdEmpleado']) == 0) {

			$imagenEmp = $_FILES["imagenEmp"]["tmp_name"];
			$imagenEmp_name = $_FILES["imagenEmp"]["name"];

			$parte = explode(".", $imagenEmp_name);
			$codigoInterno = strtotime(date('Y-m-d H:i:s'));
			$new_file_name = str_replace(' ', '-', $parte[0] . '-' . $codigoInterno . '.' . $parte[1]);

			move_uploaded_file($imagenEmp, "../Files/Empleado/" . $new_file_name);


			// registrarmos  el nuevo empleado creado, mas  su imagen  que le  pertece
			$idempleado = $objEmpleado->Registrar($_POST, "../Files/Empleado/" . $new_file_name);

			// insercion de contrato


			$contrato_trabajo = $_FILES["contrato_trabajo"]["tmp_name"];
			$contrato_trabajo_name = $_FILES["contrato_trabajo"]["name"];

			$parte = explode(".", $contrato_trabajo_name);
			$codigoInterno = strtotime(date('Y-m-d H:i:s'));
			$new_file_name_contrato = str_replace(' ', '-', 'contrato' . '-' . $codigoInterno . '.' . $parte[1]);

			move_uploaded_file($contrato_trabajo, "../Files/Contrato/" . $new_file_name_contrato);

			// insercion de dni

			if ($_FILES["dniFile"]["size"] !== 0) {


				$dniFile = $_FILES["dniFile"]["tmp_name"];
				$dniFile_name = $_FILES["dniFile"]["name"];

				$parte = explode(".", $dniFile_name);
				$codigoInterno = strtotime(date('Y-m-d H:i:s'));
				$new_file_name_dni = str_replace(' ', '-', 'dni' . '-' . $codigoInterno . '.' . $parte[1]);

				move_uploaded_file($dniFile, "../Files/DNI/" . $new_file_name_dni);
			} else {
				$new_file_name_dni = '';
			}



			//INSERCION DE CV

			if ($_FILES["cv_file"]["size"] !== 0) {

				$cv_file = $_FILES["cv_file"]["tmp_name"];
				$cv_file_name = $_FILES["cv_file"]["name"];


				$parte = explode(".", $cv_file_name);
				$codigoInterno = strtotime(date('Y-m-d H:i:s'));
				$new_file_name_cv = str_replace(' ', '-', 'cv'. '-' . $codigoInterno . '.' . $parte[1]);

				move_uploaded_file($cv_file, "../Files/CV/" . $new_file_name_cv);
			} else {
				$new_file_name_cv = '';
			}



			// INSERCION DE RIT

			if ($_FILES["registro_RIT"]["size"] !== 0) {
				$registro_RIT = $_FILES["registro_RIT"]["tmp_name"];
				$registro_RIT_name = $_FILES["registro_RIT"]["name"];

				$parte = explode(".", $registro_RIT_name);
				$codigoInterno = strtotime(date('Y-m-d H:i:s'));
				$new_file_name_RIT = str_replace(' ', '-', 'rit' . '-' . $codigoInterno . '.' . $parte[1]);

				move_uploaded_file($registro_RIT, "../Files/RIT/" . $new_file_name_RIT);
			} else {
				$new_file_name_RIT = '';
			}



			// INSERCION DE ANTECEDENTES

			if ($_FILES["antecedentes"]["size"] !== 0) {

				$antecedentes = $_FILES["antecedentes"]["tmp_name"];
				$antecedentes_name = $_FILES["antecedentes"]["name"];

				$parte = explode(".", $antecedentes_name);
				$codigoInterno = strtotime(date('Y-m-d H:i:s'));
				$new_file_name_antecedentes = str_replace(' ', '-', 'antecedentes' . '-' . $codigoInterno . '.' . $parte[1]);

				move_uploaded_file($antecedentes, "../Files/Antecedentes/" . $new_file_name_antecedentes);
			} else {
				$new_file_name_antecedentes = '';
			}





			//  INSERCION DE declaracion jurada
			if ($_FILES["declaracion_jurada"]["size"] !== 0) {
				$declaracion_jurada = $_FILES["declaracion_jurada"]["tmp_name"];
				$declaracion_jurada_name = $_FILES["declaracion_jurada"]["name"];


				$parte = explode(".", $declaracion_jurada_name);
				$codigoInterno = strtotime(date('Y-m-d H:i:s'));
				$new_file_name_declaracion_jurada = str_replace(' ', '-', 'declaracion_jurada' . '-' . $codigoInterno . '.' . $parte[1]);

				move_uploaded_file($declaracion_jurada, "../Files/DeclaracionJurada/" . $new_file_name_declaracion_jurada);
			} else {
				$new_file_name_declaracion_jurada = '';
			}




			// registramos el contrato si el empleado se a generado  correctamente 
			$objEmpleado->RegistrarContrato(
				$idempleado,
				$new_file_name_contrato,
				$new_file_name_dni,
				$new_file_name_cv,
				$new_file_name_RIT,
				$new_file_name_antecedentes,
				$new_file_name_declaracion_jurada,
				date('Y-m-d'),
				$_POST['txtfecha_fin_labores'],
				$_POST['txtrazon_social'],
			);
		} else {

			//si el elemento txtIdEmpleado existe  se procedera  a la actualizacion del empleado 

			if ($_FILES["imagenEmp"]["size"] !== 0) {
				$imagenEmp = $_FILES["imagenEmp"]["tmp_name"];
				$imagenEmp_name = $_FILES["imagenEmp"]["name"];
	
				$parte = explode(".", $imagenEmp_name);
				$codigoInterno = strtotime(date('Y-m-d H:i:s'));
				$new_file_name = str_replace(' ', '-', $parte[0] . '-' . $codigoInterno . '.' . $parte[1]);
	
				move_uploaded_file($imagenEmp, "../Files/Empleado/" . $new_file_name);
			}else{
				$new_file_name='';
			}
			//funcion  que  modifica  el empleado
			$objEmpleado->Modificar($_POST, "../Files/Empleado/" . $new_file_name);
		}

		break;

	case "delete":
		$id = $_POST["id"]; // Llamamos a la variable id del js que mandamos por $.post (Categoria.js (Linea 62))
		$result = $objEmpleado->Eliminar($id);
		if ($result) {
			echo "Eliminado Exitosamente";
		} else {
			echo "No fue Eliminado";
		}
		break;
		case "deleteHijo":
			$id = $_POST["id"]; // Llamamos a la variable id del js que mandamos por $.post (Categoria.js (Linea 62))
			$result = $objEmpleado->EliminarHijo($id);
			if ($result) {
				echo "Eliminado Exitosamente";
			} else {
				echo "No fue Eliminado";
			}
			break;
	case "listContratos":


		$query_Tipo = $objEmpleado->ListarContratos($_GET['id']);
		$data = array();
		$i = 1;
		while ($reg = $query_Tipo->fetch_object()) {

			$contrato = 'No Habido';

			if (strlen($reg->archivo_contrato_trabajo) > 0) {
				$contrato = '
				<a  href="./Files/contrato/' . $reg->archivo_contrato_trabajo . '" target="_blank" 
				>
				<span class="mailbox-attachment-icon has-img">
				<img src="https://static.vecteezy.com/system/resources/previews/010/160/299/non_2x/document-file-icon-paper-doc-sign-free-png.png" 
				
				style="width:100px !important; height:100px !important;"

				/>
				</span>
			   </a>
			
				<div class="mailbox-attachment-info">
				  <a href="./Files/contrato/' . $reg->archivo_contrato_trabajo . '" class="mailbox-attachment-name" target="_blank"
					>
			
					CONTRATO 
				  </a>
			
				  <span class="mailbox-attachment-size">
					1.9 MB
					<a target="_blank"  href="./Files/contrato/' . $reg->archivo_contrato_trabajo . '" class="btn btn-default btn-xs pull-right"
					  ><i class="fa fa-cloud-download"></i
					></a>
				  </span>
				</div>
			  ';
			}

			$dni = 'No Habido';

			if (strlen($reg->archivo_dni) > 0) {
				$dni = '
				<a  href="./Files/DNI/' . $reg->archivo_dni . '" target="_blank" 
				>
				<span class="mailbox-attachment-icon has-img">
				<img src="https://static.vecteezy.com/system/resources/previews/010/160/299/non_2x/document-file-icon-paper-doc-sign-free-png.png" 


				style="width:100px !important; height:100px !important;"

				/>
				</span>
			   </a>
			
				<div class="mailbox-attachment-info">
				  <a href="./Files/DNI/' . $reg->archivo_dni . '" class="mailbox-attachment-name" target="_blank"
					>
			
					DNI 
				  </a>
			
				  <span class="mailbox-attachment-size">
					1.9 MB
					<a target="_blank"  href="./Files/DNI/' . $reg->archivo_dni . '" class="btn btn-default btn-xs pull-right"
					  ><i class="fa fa-cloud-download"></i
					></a>
				  </span>
				</div>
			  ';
			}


			$CV = 'No Habido';

			if (strlen($reg->archivo_cv) > 0) {
				$CV = '
				<a  href="./Files/CV/' . $reg->archivo_cv . '" target="_blank" 
				>
				<span class="mailbox-attachment-icon has-img">
				<img src="https://static.vecteezy.com/system/resources/previews/010/160/299/non_2x/document-file-icon-paper-doc-sign-free-png.png" 
				
				style="width:100px !important; height:100px !important;"

				/>
				</span>
			   </a>
			
				<div class="mailbox-attachment-info">
				  <a href="./Files/CV/' . $reg->archivo_cv . '" class="mailbox-attachment-name" target="_blank"
					>
			
					CV 
				  </a>
			
				  <span class="mailbox-attachment-size">
					1.9 MB
					<a target="_blank"  href="./Files/CV/' . $reg->archivo_cv . '" class="btn btn-default btn-xs pull-right"
					  ><i class="fa fa-cloud-download"></i
					></a>
				  </span>
				</div>
			  ';
			}

			$RIT = 'No Habido';

			if (strlen($reg->archivo_rit) > 0) {
				$RIT = '
				<a  href="./Files/RIT/' . $reg->archivo_rit . '" target="_blank" 
				>
				<span class="mailbox-attachment-icon has-img">
				<img src="https://static.vecteezy.com/system/resources/previews/010/160/299/non_2x/document-file-icon-paper-doc-sign-free-png.png"
				
				style="width:100px !important; height:100px !important;"

				/>
				</span>
			   </a>
			
				<div class="mailbox-attachment-info">
				  <a href="./Files/RIT/' . $reg->archivo_rit . '" class="mailbox-attachment-name" target="_blank"
					>
			
					RIT 
				  </a>
			
				  <span class="mailbox-attachment-size">
					1.9 MB
					<a target="_blank"  href="./Files/RIT/' . $reg->archivo_rit . '" class="btn btn-default btn-xs pull-right"
					  ><i class="fa fa-cloud-download"></i
					></a>
				  </span>
				</div>
			  ';
			}


			$antecedentes = 'No Habido';

			if (strlen($reg->archivo_antecedentes) > 0) {
				$antecedentes = '
				<a  href="./Files/Antecedentes/' . $reg->archivo_antecedentes . '" target="_blank" 
				>
				<span class="mailbox-attachment-icon has-img">
				<img src="https://static.vecteezy.com/system/resources/previews/010/160/299/non_2x/document-file-icon-paper-doc-sign-free-png.png"
				
				style="width:100px !important; height:100px !important;"

				/>
				</span>
			   </a>
			
				<div class="mailbox-attachment-info">
				  <a href="./Files/Antecedentes/' . $reg->archivo_antecedentes . '" class="mailbox-attachment-name" target="_blank"
					>
			
					ANTECEDENTES 
				  </a>
			
				  <span class="mailbox-attachment-size">
					1.9 MB
					<a target="_blank"  href="./Files/Antecedentes/' . $reg->archivo_antecedentes . '" class="btn btn-default btn-xs pull-right"
					  ><i class="fa fa-cloud-download"></i
					></a>
				  </span>
				</div>
			  ';
			}



			$declaracion_jurada = 'No Habido';

			if (strlen($reg->archivo_declaracion_jurada) > 0) {
				$declaracion_jurada = '
				<a  href="./Files/declaracionJurada/' . $reg->archivo_declaracion_jurada . '" target="_blank" 
				>
				<span class="mailbox-attachment-icon has-img">
				<img src="https://static.vecteezy.com/system/resources/previews/010/160/299/non_2x/document-file-icon-paper-doc-sign-free-png.png"

				style="width:100px !important; height:100px !important;"
				
				/>
				</span>
			   </a>
			
				<div class="mailbox-attachment-info">
				  <a href="./Files/declaracionJurada/' . $reg->archivo_declaracion_jurada . '" class="mailbox-attachment-name" target="_blank"
					>
			
					D. JURADA 
				  </a>
			
				  <span class="mailbox-attachment-size">
					1.9 MB
					<a target="_blank"  href="./Files/declaracionJurada/' . $reg->archivo_declaracion_jurada . '" class="btn btn-default btn-xs pull-right"
					  ><i class="fa fa-cloud-download"></i
					></a>
				  </span>
				</div>
			  ';
			}





			$data[] = array(
				"0" => $i,
				"1" => $contrato,
				"2" => $dni,
				"3" => $CV,
				"4" => $RIT,
				"5" => $antecedentes,
				"6" => $declaracion_jurada,
				"7" => $reg->razon_social,
				"8" => $reg->fecha_inicio_labores,
				"9" => $reg->fecha_fin_labores

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


	case "listHijos":
		// lista los hijos segund el empleado
		$query_Tipo = $objEmpleado->ListarHijos($_GET['id']);
		$data = array();
		$i = 1;
		while ($reg = $query_Tipo->fetch_object()) {

			$data[] = array(
				"0" => $i,
				"1" => $reg->nombre_hijo,
				"2" => $reg->apellido_hijo,
				"3" => $reg->dni_hijo,
				"4" => $reg->fecha_nacimiento_hijo,


				"5" => '<button class="btn btn-warning" data-toggle="tooltip"
					
					type="button" 
					title="Editar" onclick="cargarDataEmpleadoHijo(
					 
					' . $reg->iddetalle_empleado_hijo . ',
					\'' . $reg->nombre_hijo . '\',
					\'' . $reg->apellido_hijo . '\',
					\'' . $reg->dni_hijo . '\',
					\'' . $reg->fecha_nacimiento_hijo . '\'

					
					)"><i class="fa fa-pencil"></i> </button>&nbsp;' .
					'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarEmpleadoHijo(' . $reg->iddetalle_empleado_hijo . ')"><i class="fa fa-trash"></i> </button>'
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



	case "list":
		$query_Tipo = $objEmpleado->Listar();
		$data = array();
		$i = 1;
		while ($reg = $query_Tipo->fetch_object()) {



			// muesta los estados de los documentos 


			$estadoDocumentacion = '<div style="display: flex;">
			<ul>
				<li>DNI/CE ' . (strpos($reg->mensaje, 'dni') ? '❌' : '✅') . '</li>
				<li>CV ' . (strpos($reg->mensaje, 'archivo_cv') ? '❌' : '✅') . '</li>
				<li>Antecedentes ' . (strpos($reg->mensaje, 'archivo_antecedentes') ? '❌' : '✅') . '</li>
			</ul>
			<ul>
				<li>D. Jurada ' . (strpos($reg->mensaje, 'archivo_declaracion_jurada') ? '❌' : '✅') . '</li>
				<li>Contrato ' . (strpos($reg->mensaje, 'archivo_contrato_trabajo') ? '❌' : '✅') . '</li>
				<li>RIT ' . (strpos($reg->mensaje, 'archivo_rit') ? '❌' : '✅') . '</li>
			</ul>
		</div>';
			// si no falta nungun documento todos pasaran como true
			if ($reg->mensaje == 'No falta ningún archivo') {
				$estadoDocumentacion = '        <div style="display: flex;">
			<ul >
			  <li>DNI/CE  ✅</li>
			  <li>CV ✅</li>
			  <li>Antecedentes  ✅</li>
			</ul>
			<ul>
			  <li>D. Jurada  ✅</li>
			  <li>Contrato   ✅</li>
			  <li>RIT  ✅</li>
			</ul>
		  </div>';
		//   si no se insertaron ningun documento al empleado estaran como false
			}else if($reg->mensaje ==null){
				$estadoDocumentacion = '        <div style="display: flex;">
				<ul >
				  <li>DNI/CE  ❌</li>
				  <li>CV ❌</li>
				  <li>Antecedentes  ❌</li>
				</ul>
				<ul>
				  <li>D. Jurada  ❌</li>
				  <li>Contrato   ❌</li>
				  <li>RIT  ❌</li>
				</ul>
			  </div>';
			  
			}
			$data[] = array(
				"0" => $i,
				"1" => $reg->razon_social,
				"2" => $reg->nombre_completo,
				"3" => $reg->puesto_ocupado,
				"4" => $reg->area_funcional,
				"5" => $estadoDocumentacion,
				"6" => $reg->dias_restantes.' dias faltantes',
				"7" =>
				
				'<span class="badge bg-green">' .$reg->primera_fecha_contrato.'</span>'
				,
				"8" => '<button class="btn btn-warning" data-toggle="tooltip" title="Editar" onclick="cargarDataEmpleado(' . $reg->idempleado . ')"><i class="fa fa-pencil"></i> </button>&nbsp;' 
					
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
		// lista los documentos de las personas
		require_once "../model/Tipo_Documento.php";
		$objTipo_Documento = new Tipo_Documento();
		$query_tipo_Documento = $objTipo_Documento->VerTipo_Documento_Persona();
		while ($reg = $query_tipo_Documento->fetch_object()) {
			echo '<option value=' . $reg->idtipo_documento . '>' . $reg->nombre . '</option>';
		}
		break;
}
