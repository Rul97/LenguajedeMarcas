<?php
session_start();
if (empty($_SESSION['id'])) {
	header("location: login.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
</head>
<body>
    <nav>
        <?php
        echo $_SESSION['id'];
        ?>
        <a href="controlador/controlador_cerrar_sesion.php">Cerrar sesión</a>
     </nav>
    <?php 
        if($_POST){
            include ("conexion.php");
			$alumno_id=$_SESSION['id'];
			$empresa_id = $_POST["dar_prioridad"] ?? null;
			$anyo=$_POST['anyo'] ?? null;
			$periodo=$_POST['periodo'] ?? null;

            
		$sql="insert into prioridades (id, alumno_id, empresa_id, anyo, periodo, orden) values (:id, :alumno_id, :empresa_id, :anyo, :periodo, :orden)";
        $datos = [
            ":id"=>$id,
            ":alumno_id"=>$alumno_id,
            ":empresa_id"=>$empresa_id,
            ":anyo"=>$anyo,
            ":periodo"=>$periodo,
            ":orden"=>$orden
        ];

        $consula_id="SELECT max(id)from prioridades";
        $stmt_id=$pdo->prepare(consula_id);
        $stmt_id->execute();
		$id=$stmt_id+1;

        $consulta_orden="SELECT max(orden) from prioridades where alumno_id = :alumno_id group by alumno_id";

            $stmt_orden=$pdo->prepare($consulta_orden);
            $stmt_orden->execute();
            if($stmt_orden){
			$orden=$stmt_orden+1;
			}else{
				$orden=1;
			}
    $stmt=$pdo->prepare($sql);
    $stmt->execute($datos);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Priorizar</title>
</head>
<body>
    <nav>
        <?php
        echo $_SESSION['id'];
        ?>
        <a href="controlador/controlador_cerrar_sesion.php">Cerrar sesión</a>
     </nav>
    <h1>Formulario de prioridad de Empresa</h1>

    <fieldset>
        <form action="priorizarEmpresa.php" method="post">
        <label>Año</label>
        <input type="text" name="anyo">
        <label>Periodo</label>
        <input type="text" name="periodo"> <br>
        <input type="submit" value="Insertar"> 
        <input type="reset" value="Reiniciar">
        </form>
    </fieldset>
</body>
</html>
	
