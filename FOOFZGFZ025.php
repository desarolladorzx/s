<?php

	session_start();

	if(isset($_SESSION["idusuario"])){

		if ($_SESSION["superadmin"] != "S") {
			include "view/header.html";
			include "view/FOOFZGFZ025.html";
		} else {
			include "view/headeradmin.html";
			include "view/FOOFZGFZ025.html";
		}

		include "view/footer.html";
	} else {
		header("Location:index.html");
	}