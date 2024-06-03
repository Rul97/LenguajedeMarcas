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
    <title></title>
</head>
<body>
    
    <?php 
        if($_POST){
            include("conexion.php");
            $usuario=$_SESSION['id'] ?? null;
            $email= $_POST['email'] ?? null;
            $nia=$_POST["nia"] ?? null;
            $telefono=$_POST["telefono"] ?? null;
            $nombre = $_POST["nombre"] ?? null;
            $cv_file=$_POST["cv_file"] ?? null;
            $password=$_POST["password"] ?? null;

            $sql_update = "update alumno set email='".$email."', nombre='".$nombre."', nia='".$nia."', telefono='".$telefono."', cv_file='".$cv_file."', password='".$password."' where email='".$email."';";
            $stmt=$pdo->prepare($sql_update);
            $stmt->execute();

            if($stmt){
                $sql_alumno = $pdo->prepare("select * from alumno where email = :usuario");
                $sql_tutor = $pdo->prepare("select * from tutor where email=:usuario");

                $sql_alumno->bindValue(':usuario', $usuario);

                $sql_tutor->bindValue(':usuario', $usuario);

                $sql_alumno->execute();
                $sql_tutor->execute();

                $resultado_alumno=$sql_alumno->fetch(PDO::FETCH_ASSOC);
                $resultado_tutor=$sql_tutor->fetch(PDO::FETCH_ASSOC);
        
                if ($resultado_alumno) {
                   $_SESSION['id']=$resultado_alumno['email'];
                   header("location: datosAlumno.php");
                }elseif ($resultado_tutor) {
                   $_SESSION['id']=$resultado_tutor['email'];
                   header("location: datosAlumnosTutor.php");
                }
            }
        }
    ?>
</body>
</html>