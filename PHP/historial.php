<?php
	require_once "header.php";
	require_once 'request_processing.php';
	$rol = getOrGoLogin('rol');

	if(isset($_GET['request'])){
		switch ($_GET['request']) {
			case 'ventas':
				echo '<h1>Historial de ventas</h1><hr><div class="historial historial--venta">';
				$results = $pdo->query("SELECT * FROM boleta WHERE rol_vendedor= '$rol'");
				$result = $results->fetch(PDO::FETCH_ASSOC);
				if(!$result) echo "No has vendido ningun producto";
				
				else{
					while($result){
						echo '<div class="boleta">';
						$nombre_producto = $result['nombre_producto'];
						$precio_unitario = $result['precio_unidad'];
						$cantidad_comprada = $result['cantidad'];
						$calificacion = $result['calificacion'];
						$rol_comprador = $result['rol_comprador'];
						$id_producto = $result['id_producto'];
						$comentario = $result['comentario'];
						$total = $precio_unitario*$cantidad_comprada;
						echo '<div class="boleta-cabecera">';
						echo '<a class="boleta-link" href="productos.php?sku='.$id_producto.'"><h3>'.$nombre_producto.'</h3></a>';
						echo '<div class="boleta-calificacion">';
						for( $i = 0; $i < $calificacion; ++$i ){
							echo '<img src="../IMG/estrella.png"class="img-calificacion"></img>';
						}
						echo '</div></div>';
						echo '<div class="boleta-detalle">';
						echo '<div class="detalle-container">';
							echo '<p class="detalle-etiqueta">Unidades vendidas</p>';
							echo '<p class="detalle-info">'.$cantidad_comprada.'</p>';
						echo '</div>';
						echo '<div class="detalle-container">';
							echo '<p class="detalle-etiqueta">Precio unidad</p>';
							echo '<p class="detalle-info">'.$precio_unitario.' $</p>';
						echo '</div>';
						echo '<div class="detalle-container">';
							echo '<p class="detalle-etiqueta">Rol del comprador</p>';
							echo '<a href="usuarios.php?rol='.$rol_comprador.'" class="detalle-info detalle-usuario">'.$rol_comprador.'</a>';
						echo '</div>';
						echo '<div class="detalle-container">';
							echo '<p class="detalle-etiqueta">Total recibido</p>';
							echo '<p class="detalle-info">'.$total.' $</p>';
						echo '</div></div>';
						/*foreach($result as $key => $value){
							echo $key.": ".$value."<br>";
						}*/
						echo '<div class="boleta-pie">';
							echo '<p class="boleta-comentario">'.$comentario.'</p>';
							echo '<p class="boleta-fecha">'.( date( 'd/m/Y',strtotime( $result['fecha'] ) )).'</p>';
						echo '</div>';
						echo '</div><br>';
						$result = $results->fetch(PDO::FETCH_ASSOC);
					}
						
				}
				break;
			case 'compras':
				echo '<h1>Historial de compras</h1><div class="historial historial--compra">';
				$results = $pdo->query("SELECT * FROM boleta WHERE rol_comprador= '$rol'");
				$result = $results->fetch(PDO::FETCH_ASSOC);
				if(!$result) echo "No has comprado ningun producto";
				
				else{
					while($result){
						echo '<div class="boleta">';
						$nombre_producto = $result['nombre_producto'];
						$precio_unitario = $result['precio_unidad'];
						$cantidad_comprada = $result['cantidad'];
						$calificacion = $result['calificacion'];
						$rol_vendedor = $result['rol_vendedor'];
						$id_producto = $result['id_producto'];
						$total = $precio_unitario*$cantidad_comprada;
						echo '<div class="boleta-cabecera">';
						echo '<a class="boleta-link" href="productos.php?sku='.$id_producto.'"><h3>'.$nombre_producto.'</h3></a>';
						echo '<div class="boleta-calificacion">';
						for( $i = 0; $i < $calificacion; ++$i ){
							echo '<img src="../IMG/estrella.png"class="img-calificacion"></img>';
						}
						echo '</div></div>';
						echo '<div class="boleta-detalle">';
						echo '<div class="detalle-container">';
							echo '<p class="detalle-etiqueta">Unidades compradas</p>';
							echo '<p class="detalle-info">'.$cantidad_comprada.'</p>';
						echo '</div>';
						echo '<div class="detalle-container">';
							echo '<p class="detalle-etiqueta">Precio unidad</p>';
							echo '<p class="detalle-info">'.$precio_unitario.' $</p>';
						echo '</div>';
						echo '<div class="detalle-container">';
							echo '<p class="detalle-etiqueta">Rol del Vendedor</p>';
							echo '<a href="usuarios.php?rol='.$rol_vendedor.'" class="detalle-info detalle-usuario">'.$rol_vendedor.'</a>';
						echo '</div>';
						echo '<div class="detalle-container">';
							echo '<p class="detalle-etiqueta">Total pagado</p>';
							echo '<p class="detalle-info">'.$total.' $</p>';
						echo '</div></div>';
						/*foreach($result as $key => $value){
							echo $key.": ".$value."<br>";
						}*/
						echo '<div class="boleta-pie">';
							
							if($result['calificacion']){
								echo '<a href="historial.php?request=calificar&id='.$result['id'].'">Editar calificacion</a>';
							}else{
								echo '<a class="boton-amarillo boton boton--calificar" href="historial.php?request=calificar&id='.$result['id'].'">Calificar</a>';
							}
							echo '<p class="boleta-fecha">'.( date( 'd/m/Y',strtotime( $result['fecha'] ) )).'</p>';
						echo '</div>';
						echo '</div><br>';
						$result = $results->fetch(PDO::FETCH_ASSOC);
					}
						
				}
				break;
			case 'calificar':
				if(!isset($_GET['id'])) die("No se recibio el id de la boleta");
				$result = $pdo->query("SELECT id,rol_comprador,calificacion,comentario FROM boleta WHERE rol_comprador='$rol' AND id = ".$_GET['id'])->fetch(PDO::FETCH_ASSOC);
				if(!$result) die('<div class="alerta">Boleta no encontrada</div>');

				?>
				<h1>Calificar</h1><hr>
				<form action="productos.php" method="post" id="comentario_form">
					<input type="hidden" name="action" value="add_calification">
					<input type="hidden" name="id" value="<?php echo $result['id']; ?>">
					<input type="range" name="calificacion" list="puntajes" min="1" max="5" step="1" value="<?php echo $result['calificacion']|0; ?>">
					<label for="comentario_producto">Comentario acerca de su compra</label>
					<textarea name="comentario" id="comentario_producto" cols="30" rows="10" form="comentario_form"><?php echo $result['comentario']; ?></textarea>
					<input type="submit" value="Enviar">
					<a href="historial.php?request=compras">Cancelar</a>
				</form>
				<?php
				break;
		}

	}
	

	require_once "footer.php";
?>
