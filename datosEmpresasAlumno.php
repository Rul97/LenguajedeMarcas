
<?php
session_start();
if (empty($_SESSION['id'])) {
	header("location: login.php");
}
//Variables
		$nombre = $_POST["nombre"] ?? null;
		$cif=$_POST["cif"] ?? null;
		$nombre_fiscal=$_POST["nombre_fiscal"]?? null;
		$email= $_POST['email'] ?? null;
		$direccion=$_POST["direccion"] ?? null;
		$localidad=$_POST[""] ?? null;
		$provincia=$_POST[""] ?? null;
		$numero_plazas=$_POST[""] ?? null;
		$telefono=$_POST["telefono"] ?? null;
		$persona_contacto=$_POST["persona_contacto"] ?? null;
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
	<title>Listado Empresas</title>
</head>
<body>
	<nav>
		<a href="tutorprincipal.php">Pagina principal</a>
		<?php
		echo $_SESSION['id'];
	 	?>
	 	<a href="controlador/controlador_cerrar_sesion.php">Cerrar sesión</a>
	 </nav>
<form method="post" action="datosEmpresasAlumno.php">
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
		$sql_registros="SELECT COUNT(*) as registros from empresa where true";
		
		//Consulta para mostrar los datos
		$query="SELECT * FROM empresa WHERE true";

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
			echo "<td>Nombre</td>";
			echo "<td>CIF</td>";
			echo "<td>Nombre Fiscal</td>";
			echo "<td>Email</td>";
			echo "<td>Dirección</td>";
			echo "<td>Localidad</td>";
			echo "<td>Provincia</td>";
			echo "<td>NºPlazas</td>";
			echo "<td>Telefono</td>";
			echo "<td>Persona de Contacto</td>";
			echo "<td >Seleccionar Prioridad</td>";
			echo "</tr>";
			while ($row = $stmt->fetch()) {
				echo "<tr>";
				echo "<td>{$row['nombre']}</td>";
				echo "<td>{$row['cif']}</td>";
				echo "<td>{$row['nombre_fiscal']}</td>";
				echo "<td>{$row['email']}</td>";
				echo "<td>{$row['direccion']}</td>";
				echo "<td>{$row['localidad']}</td>";
				echo "<td>{$row['provincia']}</td>";
				echo "<td>{$row['numero_plazas']}</td>";
				echo "<td>{$row['telefono']}</td>";
				echo "<td>{$row['persona_contacto']}</td>";
				echo "<td>
           				<form action='priorizarEmpresa.php' method='POST'>
            				<input type='hidden' name='dar_prioridad' value='{$row["nombre"]}'>
            				<input type='submit' name='priorizar' value='Priorizar'>
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
			echo "Error" .$e->getMessage();
		}
	?>
</body>
</html>