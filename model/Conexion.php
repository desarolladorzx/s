<?php
	$conexion = new mysqli("localhost", "sismedic_medicfit", "IRcOjz9B.U*E", "sismedic_medicfit");

	if (mysqli_connect_errno()) {
	    printf("Connect failed: %s\n", mysqli_connect_error());
	    exit();
	}