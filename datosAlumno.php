<?php
session_start();
if (empty($_SESSION['id'])) {
	header("location: login.php");
}
//Variables
		$email= $_POST['email'] ?? null;
		$nia=$_POST["nia"] ?? null;
		$telefono=$_POST["telefono"] ?? null;
		$nombre = $_POST["nombre"] ?? null;
		$cv_file=$_POST["cv_file"] ?? null;
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Datos Alumno</title>
</head>
<body>
	<nav>
		<a href="alumnoprincipal.php">Página principal</a>
		<?php
		echo $_SESSION['id'];
	 	?>
	 	<a href="controlador/controlador_cerrar_sesion.php">Cerrar sesión</a>
	 </nav>
	 <?php
		try{
		//Conexion a la base de datos
		include ("conexion.php");

		//Consulta para mostrar los datos
		$query="SELECT * FROM alumno WHERE email = :email";

		$stmt=$pdo->prepare($query);
		$stmt->bindParam(':email', $_SESSION['id'], PDO::PARAM_STR);

		$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			echo "<h1>Datos de los Alumnos</h1>";
			echo "<table border='1'>";
			echo "<tr>";
			echo "<td>Email</td>";
			echo "<td>Nia</td>";
			echo "<td>Telefono</td>";
			echo "<td>Nombre</td>";
			echo "<td>CV</td>";
			echo "<td>Acciones</td>";
			echo "</tr>";
			while ($row = $stmt->fetch()) {
				echo "<tr>";
				echo "<td>{$row['email']}</td>";
				echo "<td>{$row['nia']}</td>";
				echo "<td>{$row['telefono']}</td>";
				echo "<td>{$row['nombre']}</td>";
				echo "<td>{$row['cv_file']}</td>";
    			echo "<td>
            			<form action='editarAlumno.php' method='POST'>
            				<input type='hidden' name='email_editar' value='{$row["email"]}'>
            				<input type='submit' name='Editar' value='Editar'>
            			</form>
            		</td>";
				echo "</tr>";
			}
			echo "</table>";
			}
		catch(PDOException $e){
			echo "Error en la conexion con la base de datos" .$e->getMessage();
		}
		?>


</body>
</html>