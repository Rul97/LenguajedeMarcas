
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
		$paginado_inicio = $_POST['paginado_inicio'] ?? 0;
		$muestra = 15;
		$pagina_actual = ($paginado_inicio/$muestra)+1;
		$siguiente = $_POST['siguiente'] ?? null;
		$anterior = $_POST['anterior'] ?? null;
		$primera = $_POST['primera'] ?? null;
		$ultima = $_POST['ultima'] ?? null;
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>
	<nav>
		<a href="tutorprincipal.php">Pagina principal</a>
		<?php
		echo $_SESSION['id'];
	 	?>
	 	<a href="controlador/controlador_cerrar_sesion.php">Cerrar sesión</a>
	 </nav>
<form method="post" action="datosAlumnosTutor.php">
	<h3>Filtro</h3>
	<label>Nombre:</label>
	<input type="text" name="nombre" value="<?php echo isset($_POST['nombre']) ? $_POST['nombre'] : $nombre; ?>">
	<label>Email:</label>
	<input type="text" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : $email; ?>">
	<input type="submit" name="Filtrar">

	<?php
		try{
		//Conexion a la base de datos
		include ("conexion.php");

		//Consulta para calcular los registros
		$sql_registros="SELECT COUNT(*) as registros from alumno where true";
		
		//Consulta para mostrar los datos
		$query="SELECT * FROM alumno WHERE true";

		//Concatenacion de filtros
		if ($nombre) {
			$query .=" AND nombre like :nombre";
			$sql_registros .=" AND nombre like :nombre";
		}

		if ($email) {
			$query.=" AND email like :email";
			$sql_registros.=" AND email like :email";
		}

		$query .=" limit :paginado_inicio , :muestra";

		$stmt2=$pdo->prepare($sql_registros);
		
		$stmt=$pdo->prepare($query);

		//Asignacion de parametros
		if ($nombre) {
			$nombre = "%$nombre%";
			$stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
			$stmt2->bindParam(':nombre', $nombre, PDO::PARAM_STR);
		}
		
		if ($email) {
			$email = "%$email%";
			$stmt->bindParam(':email', $email, PDO::PARAM_STR);
			$stmt2->bindParam(':email', $email, PDO::PARAM_STR);
		}

		$stmt2->execute();
			$resultado= $stmt2->fetch(PDO::FETCH_ASSOC);
			$num_registros=(int)$resultado['registros'];
			$num_paginas=ceil($num_registros/$muestra);

		if (isset($_POST['siguiente']) && ($paginado_inicio + $muestra) < $num_registros) {
			$paginado_inicio += $muestra;
		}elseif (isset($_POST['anterior']) && ($paginado_inicio - $muestra) >= 0) {
			$paginado_inicio -= $muestra;
		}elseif (isset($_POST['primera'])) {
    		$paginado_inicio = 0; 
		} elseif (isset($_POST['ultima'])) {
   		 	$paginado_inicio = ($num_paginas - 1) * $muestra;
		}
	
			$stmt->bindParam(':paginado_inicio', $paginado_inicio, PDO::PARAM_INT);
			$stmt->bindParam(':muestra', $muestra, PDO::PARAM_INT);
			
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
			echo "<td colspan = 2>Acciones</td>";
			echo "</tr>";
			while ($row = $stmt->fetch()) {
				echo "<tr>";
				echo "<td>{$row['email']}</td>";
				echo "<td>{$row['nia']}</td>";
				echo "<td>{$row['telefono']}</td>";
				echo "<td>{$row['nombre']}</td>";
				echo "<td>{$row['cv_file']}</td>";
				echo "<td>
           				<form action='eliminarAlumno.php' method='POST'>
            				<input type='hidden' name='email_borrar' value='{$row["email"]}'>
            				<input type='submit' name='eliminar' value='Eliminar'>
           				</form>
          			</td>";
    			echo "<td>
            			<form action='editarAlumno.php' method='POST'>
            				<input type='hidden' name='email_editar' value='{$row["email"]}'>
            				<input type='submit' name='Editar' value='Editar'>
            			</form>
            		</td>";
				echo "</tr>";
			}
			echo "</table>";
	?>
	<input type="submit" name="primera" value="<<">
	<input type="submit" name="anterior" value="<">
	<!--<select name="pagina">
		<?php
			for ($i=1; $i <= $num_paginas; $i++) { 
				echo "<option value='$i'>$i</option>";
			}
		?>
	</select>-->
	<input type="text" name="pagina_actual" value="<?php echo isset($_POST['paginado_inicio']) ? ($paginado_inicio/$muestra)+1 : 1  ?>">
	<input type="submit" name="siguiente" value=">">
	<input type="submit" name="ultima" value=">>">
	<!--Para recuperar el valor de la pagina actual al recargar el formulario-->
	<input type="hidden" name="paginado_inicio" value="<?php echo $paginado_inicio; ?>">
</form>

	<?php
		echo "Numero de registros total: ".$num_registros;
		echo " Total de páginas: ".$num_paginas;
		}
		catch(PDOException $e){
			echo "Error en la conexion con la base de datos" .$e->getMessage();
		}
	?>

	<a href="insertAlumno.php">Crear nuevo Alumno</a>
</body>
</html>