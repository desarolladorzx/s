<?php

	session_start();

	if(isset($_SESSION["idusuario"]) && $_SESSION["mnu_consulta_ventas"] == 1){
		
		// if ($_SESSION["superadmin"] != "S") {
			include "view/header.html";
			include "view/VentasContado.html";
		// } else {
		// 	include "view/headeradmin.html";
		// 	include "view/VentasContado.html";
		// }

		include "view/footer.html";
	} else {
		header("Location:index.html");
	}
		

