<?php
	require_once "header.php";
?>

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h1>Carrito de compras</h1>
			<hr>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Nombre</th>
						<th>Precio</th>
						<th>Cantidad</th>
						<th>Subtotal</th>
						<th>Quitar</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if(isset($_SESSION["rol"])){
						foreach($pdo->query("SELECT id,nombre,precio,cantidad,stock,subtotal FROM view_carrito WHERE rol_usuario='".$_SESSION['rol']."'") as $row) {
					?>
					<tr>
						<td><?php echo $row['nombre'] ?></td>
						<td><?php echo $row['precio'] ?></td>
						<td><?php echo $row['cantidad'] ?></td>
						<td><?php echo $row['subtotal'] ?></td>
					</tr>

					<?php } } ?>
				</tbody>

</div>

<?php
	require_once "footer.php";
?>