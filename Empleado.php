<?php

	session_start();

	if(isset($_SESSION["idusuario"]) && $_SESSION["mnu_sigrh"] == 1){
		
		// if ($_SESSION["superadmin"] != "S") {
			include "view/header.html";
			include "view/Empleado.html";
		// } else {
			// include "view/headeradmin.html";
			// include "view/Empleado.html";
		// }

		include "view/footer.html";
	} else {
		header("Location:index.html");
	}
		

