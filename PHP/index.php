<?php
	require_once 'header.php';
	require 'connect.php';
	$pdo =connect($dbHost, $db, $dbPort, $dbUser, $dbPass);
?>


	<div class="top_vendedores">
		<h1>TOP 5 VENDEDORES</h1>
		<?php
			{
			$counter = 0;
			foreach($pdo->query('SELECT * FROM top_vendedor') as $row) {
				echo '<div class=top_vendores_item>';
				echo '<a href="">#'.++$counter.'. '.$row['usuario'].'</a>';
				echo '<p>Ventas: '.$row['cantidad_vendida'].'</p>';
				echo '</div>';
			}
			}
		?>
	</div>

	<div class="top_productos_ventas">
		<h1>TOP 5 VENDEDORES</h1>
		<?php
			{
			$counter = 0;
			foreach($pdo->query('SELECT * FROM top_mas_vendidos') as $row) {
				echo '<div class=top_productos_ventas_item>';
				echo '<a href="">#'.++$counter.'. '.$row['nombre'].'</a>';
				echo '<p>Ventas: '.$row['cantidad_vendida'].'</p><p>Precio: '.$row['precio'].'</p>';
				echo '</div>';
			}
			}
		?>
	</div>

	<script type="module" src="../JS/busqueda.js"></script>

<?php
	require 'footer.php';
	 
	
	foreach ($pdo->query('SELECT * FROM usuario') as $row) {
		$rol = $row['rol'];
		$user = $row['usuario'];
		$password = $row['contrasena'];
		$email = $row['correo'];
		$birthday = $row['nacimiento'];
		$password = password_hash($password,PASSWORD_BCRYPT);
		$query= "INSERT INTO usuario(rol, usuario, contrasena, correo, nacimiento) VALUES ('$rol', '$user', '$password', '$email', TO_DATE('$birthday', 'YYYY-MM-DD'))";
		echo $query . ' -- '.$row['contrasena'].'<br>';
	}

?>
