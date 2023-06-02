<?php

	session_start();

	if(isset($_SESSION["idusuario"])){

		// if ($_SESSION["superadmin"] != "S") {
			// include "view/header.html";
			// include "view/Escritorio.html";

			include "view/header.html";
			include "view/Escritorio.html";

		// } else {
			// include "view/headeradmin.html";
			// include "view/EscritorioAdmin.html";

		// }
	} else {
		header("Location:index.html");
	}