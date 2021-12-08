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
			case 'form_producto':
				?>
					<form action="productos.php" method="post">
						<input type="hidden" name="action" value="add_producto">
						<?php
						$result = null;
						if(isset($_POST['id'])){
							$result = $pdo->query("SELECT * FROM producto_info WHERE id = '".$_POST['id']."'")->fetch(PDO::FETCH_ASSOC);
							echo '<input type="hidden" name="id" value="'.$result['id'].'">';
						}
						?>
						<input type="hidden" name="vendedor" value="<?php echo $rol ?>">
						<input type="text" name="nombre" value="<?php echo ($result)?$result['nombre']:''?>" autocomplete="off" required>
						<input type="text" name="descripcion" value="<?php echo ($result)?$result['descripcion']:''?>" autocomplete="off" required>
						<input type="text" name="precio" value="<?php echo ($result)?$result['precio']:''?>" autocomplete="off" required>
						<input type="text" name="stock" value="<?php echo ($result)?$result['stock']:''?>" autocomplete="off" required>
						<input type="text" name="categoria" value="<?php echo ($result)?$result['categoria']:''?>" autocomplete="off" required>
						<input type="submit" value="Agregar">
						<a href="productos.php">Cancelar</a>
					</form>
				<?php
				die();
			break;
			case 'add_producto':
				if(isset($_POST['id'])){
					try{
						$pdo->exec("UPDATE producto SET nombre = '".$_POST['nombre']."', descripcion = '".$_POST['descripcion']."', precio = ".$_POST['precio'].", stock = ".$_POST['stock'].", categoria = '".$_POST['categoria']."' WHERE id = ".$_POST['id']);
					}catch(PDOException $e){
						echo $e->getMessage();
						header('location: productos.php?&error="No se ha podido editar el producto"',true);
					}
				}else{
					try{
						$pdo->exec("INSERT INTO producto(nombre, descripcion, precio, stock, categoria, vendedor) VALUES ('".$_POST['nombre']."', '".$_POST['descripcion']."', ".$_POST['precio'].", ".$_POST['stock'].", '".$_POST['categoria']."', '".$rol."')");
					}catch(PDOException $e){
						echo $e->getMessage();
						header('location: productos.php?sku=&error="No se ha podido agregar el producto"',true);
					}
				}
				$categorias = explode(',',$_POST['categoria']);
				foreach($categorias as $categoria){
					try{
						$pdo->exec("INSERT INTO categoria(nombre) VALUES ('".strtolower(trim($categoria))."')");
					}catch(PDOException $e){;}
				}
			break;
		}
	}

	if(isset($_GET['error'])){ echo '<script>alert("'.$_GET['error'].'");</script>';
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
		$total = $pdo->query("SELECT count(*) FROM calificaciones WHERE id_producto = '".$_GET['sku']."'")->fetch(PDO::FETCH_ASSOC)['count'];
		$results = $pdo->query("SELECT * FROM calificaciones WHERE id_producto = '".$_GET['sku']."'");
		$result = $results->fetch(PDO::FETCH_ASSOC);
		if(!$result) echo '<div class="contenedor-aviso-productos"><h3 class="aviso-productos">'."Este producto aun no tiene calificaciones</h3>".'<img class="icon-aviso" src="../IMG/sad.png" alt="icono-triste"></img></div>';
		else{
		echo '<hr><div class="comments">';
		echo '<h3 class="comments-title">Comentarios:'.$total.'</h3>';
			while($result){
				echo '<div class="comment">';
				?>
					<div class="cabecera-comentario">
						<a href="usuarios.php?rol=<?php
							echo $result['rol_comprador'];
						?>" class="comentario-nombre-comprador"> <?php
							echo $result['nombre_comprador'];
						?>	 </a>
						<div class="comentario-calificacion">
							<?php
								$calificacion = $result['calificacion'];
								for( $i = 0; $i < $calificacion; ++$i ){
									echo '<img src="../IMG/estrella.png"class="img-calificacion"></img>';
								}	
							?>
						</div>
					</div>
					<p class="comentario-comentario"> <?php
						echo $result['comentario'];
					?></p>
					<p class="comentario-fecha"> <?php
						echo $result['fecha'];
					?></p>
					</div>
				<?php
					
				
			/*	foreach($result as $key => $value){
					echo $key.": ".$value."<br>";
						}
						echo '</div>';*/
				$result = $results->fetch(PDO::FETCH_ASSOC);
			}
				
		}


		
	}else{

		$rol = getOrGoLogin('rol');
		$results = $pdo->query("SELECT * FROM producto WHERE vendedor= '$rol'");
		$result = $results->fetch(PDO::FETCH_ASSOC);
		?>
		<form action="productos.php" method="post">
			<button type="submit" name="action" value="form_producto">Añadir producto</button>
		</form>

		<?php
		if(!$result) echo "No tienes productos en venta";
		
		else{
			while($result){

				echo '<div class="comment">';
				?>
				<form action="productos.php" method="post">
					<input type="hidden" name="id" value="<?php echo $result['id']; ?>">
					<button type="submit" name="action" value="form_producto">Editar producto</button>
				</form>

				<?php
				foreach($result as $key => $value){
					echo $key.": ".$value."<br>";
				}
				echo '</div><br>';
				$result = $results->fetch(PDO::FETCH_ASSOC);
				
			}
				
		}
		
	}
?>
<script type="module" src="../JS/productos.js"></script>
<?php
	
	require_once "footer.php";
?>
