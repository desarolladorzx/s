<?php
	$conexion = new mysqli("localhost", "root", "tH!4Fpf9WsSMMqH7", "medicfitcen_gp");

	if (mysqli_connect_errno()) {
	    printf("Connect failed: %s\n", mysqli_connect_error());
	    exit();
	}