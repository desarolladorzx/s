<?php

	session_start();

	if(isset($_SESSION["idusuario"])){

		if ($_SESSION["superadmin"] != "S") {
			include "view/header.html";
			include "view/FOOVTGVT004.html";
		} else {
			include "view/headeradmin.html";
			include "view/FOOVTGVT004.html";
		}

		include "view/footer.html";
	} else {
		header("Location:index.html");
	}