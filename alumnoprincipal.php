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
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Pagina Alumno</title>
    <link rel="stylesheet" href="css/alumnoprincipal.css">
</head>
<body>
    <header>
        <nav>
            <div>Menú Principal Alumno</div>
            
            <div><?php
        echo $_SESSION['id'];
        ?>
        <a href="controlador/controlador_cerrar_sesion.php">Cerrar sesión</a></div>
        </nav>
    </header>

    <section>
        <div><a href="datosAlumno.php">Modificar Datos Propios</a></div>
        <div><a href="#">Practicas Registradas</a></div>
        <div><a href="#">Listado Empresas</a></div>
        <div><a href="#">Registro Contactos</a></div>
    </section>
    
</body>
</html>