<?php

	session_start();

	if(isset($_SESSION["idusuario"])){

		if ($_SESSION["superadmin"] != "S") {
			include "view/header.html";
			include "view/FOOLINVM016.html";
		} else {
			include "view/headeradmin.html";
			include "view/FOOLINVM016.html";
		}

		include "view/footer.html";
	} else {
		header("Location:index.html");
	}