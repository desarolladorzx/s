<?php
//Este archivo permite el control de acceso a anulacion de ventas de administrador 
	session_start();

	if(isset($_SESSION["idusuario"]) && $_SESSION["mnu_ventas"] == 1){

		// if ($_SESSION["superadmin"] != "S") {
				include "view/header.html";
				include "view/AnulacionAdmin.html";
			// } else {
				// include "view/headeradmin.html";
				// include "view/AnulacionAdmin.html";
			// }

			include "view/footer.html";
		} else {
			header("Location:index.html");
		}