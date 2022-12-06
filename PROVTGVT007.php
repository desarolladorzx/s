<?php

	session_start();

	if(isset($_SESSION["idusuario"])){

		if ($_SESSION["superadmin"] != "S") {
			include "view/header.html";
			include "view/PROVTGVT007.html";
		} else {
			include "view/headeradmin.html";
			include "view/PROVTGVT007.html";
		}

		include "view/footer.html";
	} else {
		header("Location:index.html");
	}