<?php
	require_once "header.php";
	if(isset($_GET['rol'])){
		$result = $pdo->query("SELECT * FROM usuario_info WHERE rol = '".$_GET['rol']."'")->fetch(PDO::FETCH_ASSOC);
		if(!$result) die('<div class="alerta">usuario no encontrado</div>');

		echo '<div class="info">';
		foreach($result as $key => $value){
			echo $key.": ".$value."<br>";
		}

		echo '</div><hr><div class="productos_en_venta">';
		$results = $pdo->query("SELECT * FROM producto_info WHERE vendedor= '".$_GET['rol']."'");
		$result = $results->fetch(PDO::FETCH_ASSOC);
		if(!$result) echo "Este usuario no tiene productos en venta";
		
		else{
			while($result){
				echo '<div class="comment">';
				foreach($result as $key => $value){
					echo $key.": ".$value."<br>";
				}
				echo '</div><br>';
				$result = $results->fetch(PDO::FETCH_ASSOC);
			}
				
		}


		
	}else{
		die("No se ha recibido el rol del usuario"); 	
	}


	require_once "footer.php";
?>