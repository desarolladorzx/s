<?php

	session_start();

	if(isset($_SESSION["idusuario"]) && $_SESSION["mnu_seguridad"] == 1){
		
		// if ($_SESSION["superadmin"] != "S") {
			include "view/header.html";
			include "view/DocSucursal.html";
		// } else {
		// 	include "view/headeradmin.html";
		// 	include "view/DocSucursal.html";
		// }

		include "view/footer.html";
	} else {
		header("Location:index.html");
	}
		

