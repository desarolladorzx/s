<?php

	session_start();

	if(isset($_SESSION["idusuario"]) && $_SESSION["mnu_consulta_compras"] == 1){
	
		if ($_SESSION["superadmin"] != "S") {
			include "view/header.html";
			include "view/Kardex.html";
		} else {
			include "view/headeradmin.html";
			include "view/Kardex.html";
		}

		include "view/footer.html";
	} else {
		header("Location:index.html");
	}
		

