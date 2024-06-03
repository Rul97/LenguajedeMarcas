<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/login.css">
	<title>Login</title>
</head>
<body>
	<form method="post" action="">
		<h1>Práctica FCT</h1>
		<div>
			<?php
				include ("conexion.php");
				include ("controlador/controlador_login.php");
			?>
		</div>
		<label>Usuario</label>
		<input type="text" name="usuario">
		<label>Contraseña</label>
		<input type="password" name="pass">
		<input id="boton" type="submit" name="logear">
	</form>

</body>
</html>