
<?php
//Variables
		$host='localhost';
		$dbname='universidad';
		$user='root';
		$pass='';
		$nombre = $_POST["nombre"] ?? null;
		$apellido_1 = $_POST["apellido_1"] ?? null;
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
	
<form method="post" action="index.php">
	<label>Nombre:</label>
	<input type="text" name="nombre" value="<?php echo isset($_POST['nombre']) ? $_POST['nombre'] : $nombre; ?>">
	<label>Primer Apellido:</label>
	<input type="text" name="apellido_1" value="<?php echo isset($_POST['apellido_1']) ? $_POST['apellido_1'] : $apellido_1; ?>">
	<input type="submit" name="Filtrar">



	<?php

		try{
		//Conexion a la base de datos
			$pdo= new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user,$pass);
			$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			

		//Consulta para calcular los registros
		$sql_registros="SELECT COUNT(*) as registros from alumno where true";
		
		//Consulta paaar mostrar los datos
		$query="SELECT * FROM alumno WHERE true";

		//Concatenacion de filtros
		if ($nombre) {
			$query .=" AND NOMBRE like :nombre";
			$sql_registros .=" AND NOMBRE like :nombre";
		}

		if ($apellido_1) {
			$query.=" AND APELLIDO_1 like :apellido_1";
			$sql_registros.=" AND APELLIDO_1 like :apellido_1";
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
		
		if ($apellido_1) {
			$apellido_1 = "%$apellido_1%";
			$stmt->bindParam(':apellido_1', $apellido_1, PDO::PARAM_STR);
			$stmt2->bindParam(':apellido_1', $apellido_1, PDO::PARAM_STR);
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

			echo "<table border='1'>";
			echo "<tr>";
			echo "<td>DNI</td>";
			echo "<td>Nombre</td>";
			echo "<td>Apellido1</td>";
			echo "<td>Apellido2</td>";
			echo "<td>Direccion</td>";
			echo "<td>Localidad</td>";
			echo "<td>Provincia</td>";
			echo "<td>Fecha de nacimiento</td>";
			echo "</tr>";
			while ($row = $stmt->fetch()) {
				echo "<tr>";
				echo "<td>{$row['DNI']}</td>";
				echo "<td>{$row['NOMBRE']}</td>";
				echo "<td>{$row['APELLIDO_1']}</td>";
				echo "<td>{$row['APELLIDO_2']}</td>";
				echo "<td>{$row['DIRECCION']}</td>";
				echo "<td>{$row['LOCALIDAD']}</td>";
				echo "<td>{$row['PROVINCIA']}</td>";
				echo "<td>{$row['FECHA_NACIMIENTO']}</td>";
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
</body>
</html>