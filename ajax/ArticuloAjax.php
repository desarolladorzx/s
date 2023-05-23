<?php

	session_start();

	require_once "../model/Articulo.php";

	$objArticulo = new Articulo();

	switch ($_GET["op"]) {

		case 'SaveOrUpdate':

			$stockMinimo = $_POST["txtStockMinimo"];
			$idcategoria = $_POST["cboCategoria"];
			$idmarca = $_POST["cboMarca"];
			$idunidad_medida = $_POST["cboUnidadMedida"];
			$nombre = $_POST["txtNombre"];
			$descripcion = $_POST["txtDescripcion"];


			$precio_compra = $_POST["txtprecio_compra"];
			$precio_final = $_POST["txtprecio_final"];
			$precio_distribuidor = $_POST["txtprecio_distribuidor"];
			$precio_superdistribuidor = $_POST["txtprecio_superdistribuidor"];
			$precio_representante = $_POST["txtprecio_representante"];


			$lote = $_POST["txtLote"];
			$bar_code = $_POST["txtCodigoBarra"];
			$interno_id = $_POST["txtCodigoInterno"];
			// $precio_compra,$precio_final,$precio_distribuidor,$precio_superdistribuidor ,$precio_representante

			$imagen = $_FILES["imagenArt"]["tmp_name"];
			$ruta = $_FILES["imagenArt"]["name"];

			if(move_uploaded_file($imagen, "../Files/Articulo/".$ruta)){

				if(empty($_POST["txtIdArticulo"])){
					
					if($objArticulo->Registrar($idmarca, $idcategoria, $idunidad_medida, $nombre, $descripcion, "Files/Articulo/".$ruta,$stockMinimo,$precio_compra,$precio_final,$precio_distribuidor,$precio_superdistribuidor ,$precio_representante
					
					,$lote
					,$bar_code
					,$interno_id
					)){
						echo "Articulo RegistradoN";
					}else{
						echo "Articulo no ha podido ser registadoON.";
					}
				}else{
					
					$idarticulo = $_POST["txtIdArticulo"];
					if($objArticulo->Modificar($idarticulo, $idmarca, $idcategoria, $idunidad_medida, $nombre, $descripcion, "Files/Articulo/".$ruta,$stockMinimo,$precio_compra,$precio_final,$precio_distribuidor,$precio_superdistribuidor ,$precio_representante
					
					,$lote
					,$bar_code
					,$interno_id

					
					)){
						echo "Informacion del Articulo ha sido actualizados";
					}else{
						echo "Informacion del Articulo no ha podido ser actualizada.";
					}
				}
			} else {
				$ruta_img = $_POST["txtRutaImgArt"];
				if(empty($_POST["txtIdArticulo"])){
					
					if($objArticulo->Registrar($idmarca, $idcategoria, $idunidad_medida, $nombre, $descripcion, $ruta_img,$stockMinimo,
					$precio_compra,$precio_final,$precio_distribuidor,$precio_superdistribuidor ,$precio_representante,

					$lote
					,$bar_code
					,$interno_id

					)){
						echo "Articulo RegistradA";
					}else{
						echo "Articulo no ha podido ser registadAN.";
					}
				}else{
					
					$idarticulo = $_POST["txtIdArticulo"];
					if($objArticulo->Modificar($idarticulo, $idmarca, $idcategoria, $idunidad_medida, $nombre, $descripcion, $ruta_img,$stockMinimo,$precio_compra,$precio_final,$precio_distribuidor,$precio_superdistribuidor ,$precio_representante
					
					
					,$lote
					,$bar_code
					,$interno_id
					)){
						echo "Informacion del Articulo ha sido actualizadas";
					}else{
						echo "Informacion del Articulo no ha podido ser actualizada.";
					}
				}
			}

			break;

		case "delete":
			
			$id = $_POST["id"];
			$result = $objArticulo->Eliminar($id);
			if ($result) {
				echo "Eliminado Exitosamente";
			} else {
				echo "No fue Eliminado";
			}
			break;
		
		case "list":
			$query_Tipo = $objArticulo->Listar();
			$data = Array();
            $i = 1;
     		while ($reg = $query_Tipo->fetch_object()) {

     			$data[] = array(
					"id"=>$i,
					"1"=>$reg->marca,
					"2"=>$reg->categoria,
					"3"=>$reg->unidadMedida,
					"4"=>$reg->nombre,
					"5"=>$reg->descripcion,
					"6"=>$reg->stock_min,
					"7"=>$reg->totalSucursal1,
					"8"=>$reg->totalSucursal2,
					"9"=>'<img width=100px height=100px src="./'.$reg->imagen.'" />',
					"10"=>'<button class="btn btn-warning" data-toggle="tooltip" title="Editar" onclick="cargarDataArticulo('.$reg->idarticulo.',\''.$reg->idcategoria.'\',\''.$reg->idmarca.'\',\''.$reg->idunidad_medida.'\',\''.$reg->nombre.'\',\''.$reg->descripcion.'\',\''.$reg->imagen.'\'
					,\''.$reg->stock_min.'\'
					,\''.$reg->precio_compra.'\'
					,\''.$reg->precio_final.'\'
					,\''.$reg->precio_distribuidor.'\'
					,\''.$reg->precio_superdistribuidor.'\'
					,\''.$reg->precio_representante.'\'


					,\''.$reg->lote.'\'
					,\''.$reg->barcode.'\'
					,\''.$reg->interno_id.'\'

					
					)"><i class="fa fa-pencil"></i> </button>&nbsp;'.
					'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarArticulo('.$reg->idarticulo.')"><i class="fa fa-trash"></i> </button>');
				$i++;
			}
			$results = array(
            "sEcho" => 1,
        	"iTotalRecords" => count($data),
        	"iTotalDisplayRecords" => count($data),
            "aaData"=>$data);
			echo json_encode($results);
            
			break;
		case "listArtElegir":
			$query_Tipo = $objArticulo->Listar();
			$data = Array();
            $i = 1;
     		while ($reg = $query_Tipo->fetch_object()) {

     			$data[] = array(
     				"0"=>'<button type="button" class="btn btn-warning" data-toggle="tooltip" title="Agregar al detalle" onclick="Agregar('.$reg->idarticulo.',\''.$reg->nombre.'\'
					,\''.$reg->precio_compra.'\'
					,\''.$reg->precio_final.'\'
					,\''.$reg->precio_distribuidor.'\'
					,\''.$reg->precio_superdistribuidor.'\'
					,\''.$reg->precio_representante.'\'

					,\''.$reg->lote .'\'
					,\''.$reg->barcode .'\'
					,\''.$reg->descripcion .'\'

					
					)" name="optArtBusqueda[]" data-nombre="'.$reg->nombre.'" id="'.$reg->idarticulo.'" value="'.$reg->idarticulo.'" ><i class="fa fa-check" ></i> </button>',
     				"1"=>$i,
					"2"=>$reg->categoria,
					"3"=>$reg->marca,
					"3"=>$reg->unidadMedida,
					"4"=>$reg->nombre,
					"5"=>$reg->descripcion,
					"6"=>'<img width=100px height=100px src="./'.$reg->imagen.'" />');
				$i++;
            }
            
            $results = array(
            "sEcho" => 1,
        	"iTotalRecords" => count($data),
        	"iTotalDisplayRecords" => count($data),
            "aaData"=>$data);
			echo json_encode($results);
            
			break;

		case "listCategoria":
	        require_once "../model/Categoria.php";
	        $objCategoria = new Categoria();
	        $query_Categoria = $objCategoria->Listar();
	        while ($reg = $query_Categoria->fetch_object()) {
	            echo '<option value=' . $reg->idcategoria . '>' . $reg->nombre . '</option>';
	        }
	        break;

	    case "listUM":

	    	require_once "../model/Categoria.php";
	        $objCategoria = new Categoria();
	        $query_Categoria = $objCategoria->ListarUM();
	        while ($reg = $query_Categoria->fetch_object()) {
	            echo '<option value=' . $reg->idunidad_medida . '>' . $reg->nombre . '</option>';
	        }
	        break;

		case "listMA":

	    	require_once "../model/Marca.php";
	        $objMarca = new Marca();
	        $query_Marca = $objMarca->ListarMA();
	        while ($reg = $query_Marca->fetch_object()) {
	            echo '<option value=' . $reg->idmarca . '>' . $reg->nombre . '</option>';
	        }
	        break;
	}