<?php
	$conexion = new mysqli("localhost", "root", "tH!4Fpf9WsSMMqH7", "medicfitcen_dev");
	$conexion->set_charset("UTF8");

	if (mysqli_connect_errno()) {
	    printf("Connect failed: %s\n", mysqli_connect_error());
	    exit();
	}