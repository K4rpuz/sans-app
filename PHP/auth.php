
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="../IMG/logo.jpeg" type="image/x-icon">
	<link rel="stylesheet" href="../CSS/normalize.css">
	<link rel="stylesheet" href="../CSS/styles.css">
	<title>SansApp</title>
</head>
<body>
	<section class="container auth-container">
		<div class="login auth">
			<form method="POST" class="form-login">
				<div>
					<p>Rol</p>
					<input type="text" name="rol" id="" placeholder="202030538-2" autocomplete="off">
				</div>
				<div>
					<p>Contraseña</p>
					<input type="password" name="password" id="" placeholder="••••••">
				</div>
				<input type="submit" value="Login">
				<input type="hidden" name="request" value="LOGIN">
			</form>	
		</div>		
	</section>
	<section class="container auth-container">
		<div class="register auth">
			<form method="POST" class="form-register">
				<div class="register-fields">
					<div>
						<p>Rol</p>
						<input type="text" name="rol" id="" placeholder="202030538-2" autocomplete="off">
					</div>
					<div>
						<p>Usuario</p>
						<input type="text" name="user" id="" placeholder="Darcy" autocomplete="off">
					</div>
					<div>
						<p>Email</p>
						<input type="email" name="email" id="" placeholder="Darcy@prestigio.usm.cl" autocomplete="off">
					</div>
					<div>
						<p>Fecha de nacimiento</p>
						<input type="date" name="birthday" id="">
					</div>
					<div>
						<p>Contraseña</p>
						<input type="password" name="password" id="" placeholder="••••••">
					</div>
					<div>
						<p>Confirmar contraseña</p>
						<input type="password" name="password-confirm" id="" placeholder="••••••">
					</div>
				</div>
				<input type="submit" value="Crear cuenta">
				<input type="hidden" name="request" value="REGISTER">
			</form>	
		</div>		
	</section>
	<div id="auth-options">
		<button id="toggle-auth">Registrarse</button>
		<p class="error"></p>
	</div>
	<script type="module" src="../JS/forms.js"></script>
	<script type="module" src="../JS/auth.js"></script>
</body>
</html>
