<?php
	require_once 'header.php';
?>


	<div class="top_vendedores">
		<h1>TOP 5 VENDEDORES</h1>
		<?php
			{
			$counter = 0;
			foreach($pdo->query('SELECT * FROM top_vendedor') as $row) {
				echo '<div class=top_vendores_item>';
				echo '<a href="usuarios.php?rol='.$row['rol'].'">#'.++$counter.'. '.$row['usuario'].'</a>';
				echo '<p>Ventas: '.$row['cantidad_vendida'].'</p>';
				echo '</div>';
			}
			}
		?>
	</div>

	<div class="top_productos_ventas">
		<h1>TOP 5 PRODUCTOS MAS VENDIDOS</h1>
		<?php
			{
			$counter = 0;
			foreach($pdo->query('SELECT * FROM top_mas_vendidos') as $row) {
				echo '<div class=top_productos_ventas_item>';
				echo '<a href="productos.php?sku='.$row['id'].'">#'.++$counter.'. '.$row['nombre'].'</a>';
				echo '<p>Ventas: '.$row['cantidad_vendida'].'</p><p>Precio: '.$row['precio'].'</p>';
				echo '</div>';
			}
			}
		?>
	</div>

	<div class="top_productos_calificaciones">
		<h1>TOP 5 PRODUCTOS MAS VENDIDOS</h1>
		<?php
			{
			$counter = 0;
			foreach($pdo->query('SELECT * FROM top_calificaciones') as $row) {
				echo '<div class=top_productos_ventas_item>';
				echo '<a href="productos.php?sku='.$row['id'].'">#'.++$counter.'. '.$row['nombre'].'</a>';
				echo '<p>Calificacion promedio: '.$row['calificacion_promedio'].'</p><p>Precio: '.$row['precio'].'</p>';
				echo '</div>';
			}
			}
		?>
	</div>

	<script type="module" src="../JS/busqueda.js"></script>

<?php
	require 'footer.php';
?>
