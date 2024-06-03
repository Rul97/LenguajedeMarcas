<?php
$estado= $_POST["estados"] ?? null;


//Crear conexion a la base de datos y realiza la insercion
		if ($_POST) {		
			$host='localhost';
			$dbname='universidad';//Cambiar a la base de datos del proyecto fct
			$user='root';
			$pass='';

			try{
				$pdo= new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user,$pass);
				$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				echo "Conexion realizada correctamente <br><br>";

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>

<form method="post" action="estados.php">
	<label>Estados
	</label>
	<select name="estados">
		<<option value="">--selecciona--</option>
		<?php
		while ($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
			echo "<option value='".$row['nombre']."'>" .$row['nombre']." </option>";
			printf("option value='%s'>%s</option>",$row['nombre']);
		}
		?>
	</select>
</form>
<?php
catch(PDOException $e){
				echo "Error en la conexion con la base de datos" .$e->getMessage();

?>			}
</body>
</html>