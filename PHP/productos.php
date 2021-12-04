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

		echo '<div class="info">';
		foreach($result as $key => $value){
			echo $key.": ".$value."<br>";
		}
		?>
		<form action="productos.php" method="post">
			<input type="hidden" name="action" value="add_to_carrito">
			<input type="hidden" name="sku" value="<?php echo $result['id']; ?>">
			<input type="number" name="cantidad" value="1" min="1" max="<?php echo $result['stock'] ?>"><br>
			<input type="submit" name="add" value="Agregar al carrito">
		</form>
		<?php
		echo '</div><hr><div class="comments">';
		$results = $pdo->query("SELECT * FROM calificaciones WHERE id = '".$_GET['sku']."'");
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

	require_once "footer.php";
?>