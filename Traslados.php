<?php

	session_start();

	if(isset($_SESSION["idusuario"]) && $_SESSION["mnu_consulta_compras"] == 1){
		include "view/header.html";

		include "view/Traslados.html";

		include "view/footer.html";
	} else {
		header("Location:index.html");
	}


	
