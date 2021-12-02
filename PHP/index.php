<?php

	$pdo = require 'connect.php';
	foreach ($pdo->query('SELECT * FROM usuario') as $row) {
		echo $row['rol'] . ' ' . $row['usuario'] . ' ' . $row['correo'] . '<br>';
	}

?>