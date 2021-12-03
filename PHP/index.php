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
		echo $row['rol'] . ' ' . $row['usuario'] . ' ' . $row['correo'] . '<br>';
	}

?>
