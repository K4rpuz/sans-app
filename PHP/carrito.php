<?php
	require_once "header.php";
	require_once 'request_processing.php';
	$rol = getOrGoLogin('rol');

	if(isset($_POST['action'])){
		switch ($_POST['action']) {
			case 'eliminar':
				$formsubData = loadForm(array('producto_id' => 'text'));
				$pdo->exec("DELETE FROM carrito WHERE rol_usuario='$rol' AND id_producto = ".$formsubData['PRODUCTO_ID']);
				break;
			case 'comprar':
				foreach($pdo->query("SELECT id,cantidad FROM view_carrito WHERE rol_usuario='$rol'") as $row){
					try{
						$pdo->exec("CALL comprar_producto('$rol', ".$row['id'].", ".$row['cantidad'].")");
					}catch (PDOException $e) {
						echo $e->getMessage();
					}
					$pdo->exec("DELETE FROM carrito WHERE rol_usuario='$rol' AND id_producto = ".$row['id']);
				}
				break;
			case 'change_cantidad':
				$cantidad = isset($_POST['btn'])?$_POST['btn']:$_POST['producto_cantidad'];
				
				try{
					$pdo->exec("CALL agregar_producto_carrito('$rol', ".$_POST['producto_id'].", $cantidad)");
				}catch (PDOException $e) {
					echo $e->getMessage();
				}
				break;
		}

		header('Location: carrito.php',true);
	}
	
?>

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h1>Carrito de compras</h1>
			<div class="div_carrito">
				<div class="productos_carrito">
					
					<?php
					{ 
					$total = 0;
					foreach($pdo->query("SELECT id,nombre,precio,cantidad,stock,subtotal FROM view_carrito WHERE rol_usuario='$rol'") as $row) {
						$total += $row["subtotal"];
					?>
					<article class="producto_carrito">
							<div class="producto_decription">
								<h2><a href="productos.php?sku=<?php echo $row['id'] ?>"><?php echo $row['nombre'] ?></a></h2>
								<div class="producto_precio">
									<p>Precio unidad: <?php
										echo $row['precio'].' $';
									?></p>
									<p>Total a pagar: <?php
										echo $row['precio']*$row['cantidad'].' $';
									?></p>
									<span class="price_total"><?php  ?></span>
								</div>
							</div>
							<div class="ui_cantidad">
								<form action="carrito.php" method="post" id="form-cantidad-producto_<?php echo $row['id'] ?>">
									<input type="hidden" name="producto_id" value="<?php echo $row['id'] ?>">	
									<input type="hidden" name="action" value="change_cantidad">	
									<div class="control-cantidad">
										<button type="submit" name="btn" <?php echo ($row['cantidad']<=1)?'disabled':'value="'.($row['cantidad']-1).'"'?> class="btn_producto_cantidad">-</button>
										<input type="text" name="producto_cantidad" class="input_cantidad_producto" id="<?php echo $row['stock'].','.$row['cantidad'].','.$row['id'] ?>" value="<?php echo $row['cantidad'] ?>" autocomplete="off">
										<button type="submit" name="btn" <?php echo ($row['stock']<=$row['cantidad'])?'disabled':'value="'.($row['cantidad']+1).'"' ?> class="btn_producto_cantidad">+</button>
									</div>
								</form>
								<span class="stock_producto"><?php echo $row['stock'] ?> disponibles</span>
							</div>
							
						<div class="producto_actions">
							<ul class="actions_list">
								<li>
									<form action="carrito.php" method="post" id="form-borrar-producto">
										<input type="hidden" name="producto_id" value="<?php echo $row['id'] ?>">
										<input type="submit" name="action" class="boton boton-rojo boton-eliminar-producto"value="eliminar">
									</form>
								</li>
							</ul>
						</div>
					</article>
					<?php } } ?>

					
					<div class="compra_carrito">
						<form action="carrito.php" method="post" id="form-compra-carrito">
						<input type="hidden" name="action" value="comprar">
						<span class="total_carrito">Total: <?php echo $total ?> $</span>
						<input type="submit" value="Comprar" class="boton boton-amarillo boton--compra">
						</form>
					</div>

				</div>
			</div>
			


		</div>
	</div>
</div>
<script type="module" src="../JS/carrito.js"></script>

<?php
	require_once "footer.php";
?>
