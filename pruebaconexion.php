<?php

include("conexion.php");

$nombre = $_POST["nombre"] ?? null;
$apellido_1 = $_POST["apellido_1"] ?? null;
$siguiente = $_POST['siguiente'] ?? null;
$anterior = $_POST['anterior'] ?? null;
$primera = $_POST['primera'] ?? null;
$ultima = $_POST['ultima'] ?? null;
$paginado_inicio = $_POST['paginado_inicio'] ?? 0;
$muestra = 15;
$pagina_actual = ($paginado_inicio/$muestra)+1;

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