<?php
	require_once "header.php";

	if(isset($_POST['action'])){
		require_once 'request_processing.php';
		$rol = getOrGoLogin('rol');
		switch($_POST['action']){
			case 'add_to_carrito':
				try{
					$pdo->exec("CALL agregar_producto_carrito('".$rol."', ".$_POST['sku'].", ".$_POST['cantidad'].")");
				}catch(PDOException $e){
					header('location: productos.php?sku='.$_POST['sku'].'&error="No se ha podido agregar al carrito"',true);
				}
				header('location: productos.php?sku='.$_POST['sku'].'&error=Agregado al carrito',true);
				break;
			
			case 'add_calification':
				try{
					$pdo->exec("UPDATE boleta SET calificacion = ".$_POST['calificacion'].", comentario='".$_POST['comentario']."' WHERE id = ".$_POST['id']);
				}catch(PDOException $e){
					header('location: productos.php?sku='.$_POST['sku'].'&error="No se ha podido agregar la calificacion"',true);
				}
				header('location: historial.php?request=compras',true);
				break;
		}
	}

	if(isset($_GET['error'])){
		echo '<script>alert("'.$_GET['error'].'");</script>';
	}


	if(isset($_GET['sku'])){
		$result = $pdo->query("SELECT * FROM producto_info WHERE id = '".$_GET['sku']."'")->fetch(PDO::FETCH_ASSOC);
		if(!$result) die('<div class="alerta">Producto no encontrado</div>');

		
		?>

		<section class="contenedor-producto">	
			<div class="producto-cabecera">
				<h3> <?php
					echo $result['nombre'];
?> </h3>
				<div class="boleta-calificacion">
					<?php
						$calificacion = $result['calificacion_promedio'];
						$calificacion = 5;
						for( $i = 0; $i < $calificacion; ++$i ){
							echo '<img src="../IMG/estrella.png"class="img-calificacion"></img>';
						}

					?>
				</div>
							</div>
			
			<p class="producto-descripcion"> <?php
				echo $result['descripcion'];
			?> </p>
			<p class="producto-precio">
					Precio:
					<?php
						echo $result['precio'];
					?>
					$
			</p>
			<p class="producto-categoria">
			
				Categoria: 
				<?php
					echo $result['categoria'];
				?>
			</p>

			<p class="producto-cantidad-vendida">
				Se ha vendido: 
				<?php
					echo $result['cantidad_vendida'];
				?>
					veces.
			</p>

			<div class="producto-controles">
				<form action="productos.php" method="post" class="form-producto">	
					<input type="hidden" name="action" value="add_to_carrito">
					<input type="hidden" name="sku" value="<?php echo $result['id']; ?>">
					<div class="producto-seleccion">
					<div class="control-cantidad">
						<input type="hidden" name="min" value="0">
						<input type="hidden" name="max" value="<?php echo $result['stock'] ?>">
						<button name="btn" class="btn_producto_cantidad boton-restar">-</button>
						<input type="text" name="cantidad" class="input_cantidad_producto display-cantidad" autocomplete="off" value="1">
						<button name="btn" class="btn_producto_cantidad boton-sumar">+</button>
					</div>
					<p class="producto-stock">
						En stock: <?php
							echo $result['stock'];
						?>
					</p>	
				</div>
				
					<input type="submit" name="add" value="Agregar al carrito" class="boton boton-amarillo boton--producto">
				</form>
					
			</div>
			
			<div class="producto-vendedor">
				<p class="producto-vendedor-etiqueta">Publicado por</p>
				<a class="producto-vendedor" href="usuarios.php?rol=<?php
				echo $result['vendedor'];
				?>"><?php
				echo $result['nombre_vendedor'];
				?></a>
			</div>
		</section>
				<?php
		echo '<hr><div class="comments">';
		$results = $pdo->query("SELECT * FROM calificaciones WHERE id_producto = '".$_GET['sku']."'");
		$result = $results->fetch(PDO::FETCH_ASSOC);
		if(!$result) echo "Este producto aun no tiene calificaciones";
		else{
			while($result){
				echo '<div class="comment">';
				foreach($result as $key => $value){
					echo $key.": ".$value."<br>";
				}
				echo '</div>';
				$result = $results->fetch(PDO::FETCH_ASSOC);
			}
				
		}


		
	}else{
		//die("No se ha recibido el sku"); 	
	}
?>
<script type="module" src="../JS/productos.js"></script>
<?php
	
	require_once "footer.php";
?>
