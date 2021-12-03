<?php
	require_once "header.php";
?>

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h1>Carrito de compras</h1>
			<hr>
			<div class="div_carrito">
				<div class="productos_carrito">
				<form action="carrito_requests.php" method="post">
					
					<?php
					if(isset($_SESSION["rol"])){
						foreach($pdo->query("SELECT id,nombre,precio,cantidad,stock,subtotal FROM view_carrito WHERE rol_usuario='".$_SESSION['rol']."'") as $row) {
					?>
					<article class="producto_carrito">
						<div class="producto_info">	
							<div class="producto_decription">
								<input type="checkbox" name="id">
								<h2><a href="sans-app/PHP/producto/<?php echo $row['id'] ?>"><?php echo $row['nombre'] ?></a></h2>
							</div>
							<div class="ui_cantidad">
								<form action="carrito_requests.php" method="post">
									<!--<input type="submit" value="-" class="disminuir_producto" name="btn_producto_cantidad">-->
									<input type="number" min="1" max="<?php echo $row['stock'] ?>" name="producto_cantidad" class="cantidad_producto" value="<?php echo $row['cantidad'] ?>">
									<span class="stock_producto"><?php echo $row['stock'] ?> disponibles</span>
									<!--<input type="submit" value="+" class="aumentar_producto" name="btn_producto_cantidad">-->
								</form>
							</div>
							<div class="producto_precio">
								<span class="price_detail"><?php echo $row['cantidad'].'x'.$row['precio'] ?></span>
								<span class="price_total"><?php echo $row['precio']*$row['cantidad'] ?></span>
							</div>
						</div>
						<div class="producto_actions">
							<ul class="actions_list">
								<li>
									<form action="carrito_requests.php" method="post">
										<input type="hidden" name="producto_id" value="id">
										<input type="submit" value="Eliminar">
									</form>
								</li>
							</ul>
						</div>
					</article>
					<?php } } ?>

					
					<div class="compra_carrito">
						
					</div>
				</form>
				</div>
			</div>
			


		</div>
	</div>
</div>

<?php
	require_once "footer.php";
?>