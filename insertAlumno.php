<?php
session_start();
if (empty($_SESSION['id'])) {
    header("location: login.php");
}

//Recoger datos formulario

$email= $_POST['email'] ?? null;
$nia=$_POST["nia"] ?? null;
$telefono=$_POST["telefono"] ?? null;
$nombre = $_POST["nombre"] ?? null;
$cv_file=$_POST["cv_file"] ?? null;
$password=$_POST["password"] ?? null;

if($_POST){

   include ("conexion.php");

        $sql="insert into alumno (email, nia, telefono, nombre, cv_file, password) values (:email, :nia, :telefono, :nombre, :cv_file, :password)";
        $datos = [
            ":email"=>$email,
            ":nia"=>$nia,
            ":telefono"=>$telefono,
            ":nombre"=>$nombre,
            ":cv_file"=>$cv_file,
            ":password"=>$password
        ];
    $stmt=$pdo->prepare($sql);
    $stmt->execute($datos);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        label{
            margin-top:20px
        }
    </style>
</head>
<body>
    <nav>
        <?php
        echo $_SESSION['id'];
        ?>
        <a href="controlador/controlador_cerrar_sesion.php">Cerrar sesión</a>
     </nav>
    <h1>Formulario de registro de Alumno</h1>

    <fieldset>
        <form action="insertAlumno.php" method="post">
        <label>Email </label>
        <input type="text" name="email" required>
        <label>Nia</label>
        <input type="text" name="nia" pattern="[0-9]{8}"> <br>
        <label>Telefono </label>
        <input type="text" name="telefono"> <br>
        <label>Nombre </label>
        <input type="text" name="nombre"> <br>
        <label>CV</label>
        <input type="text" name="cv_file"> <br>
        <label>Contraseña </label>
        <input type="text" name="password"> <br>
        <input type="submit" value="Insertar"> 
        <input type="reset" value="Reiniciar">
        </form>
    </fieldset>
</body>
</html>