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
            $email=$_POST['email_editar'] ?? null;

            $sql_select = "select * from alumno where email='".$email."';";
            $stmt=$pdo->prepare($sql_select);
            $stmt->execute();
        if($stmt){
            while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                echo "<form action='editarusuario.php' method='POST'>";
                    echo "<input type='text' name='email' value='" .$row['email']. "'>";
                    echo "<input type='text' name='nombre'  value='" .$row['nombre']. "'>";
                    echo "<input type='text' name='nia'  value='" .$row['nia']. "'>";
                    echo "<input type='text' name='telefono' value='" .$row['telefono']. "'>";
                    echo "<input type='text' name='cv_file' value='" .$row['cv_file']. "'>";
                    echo "<input type='text' name='password'  value='" .$row['password']. "'>";    
                    echo "<input type='submit' value='actualizar' name='actualizar'>";
                echo "</form>";
            }
        }
    }
    ?>
</body>
</html>