<?php
session_start();
if (empty($_SESSION['id'])) {
    header("location: login.php");
}
print_r($_POST);
$email= $_POST['email'] ?? null;
$nia=$_POST["nia"] ?? null;
$telefono=$_POST["telefono"] ?? null;
$nombre = $_POST["nombre"] ?? null;
$cv_file=$_POST["cv_file"] ?? null;
$password=$_POST["password"] ?? null;
$modo_edicion=$_POST["modo_edicion"] ?? null;
$modoEdicion=$_POST["modoEdicion"] ?? null;

if(isset($_POST['editar']) || (isset($_POST['modoEdicion']) && ($_POST['modoEdicion']))){
    $modo_edicion=true;
}else{
    $modo_edicion=false;
}

//echo "edic: ".$modo_edicion;
//exit();


include ("conexion.php");
try{
if($modo_edicion==true){
    if(isset($_POST['guardar'])){
        $sql_modif = "UPDATE alumno SET email = :email, nia = :nia, telefono = :telefono, nombre = :nombre, cv_file = :cv_file, password = :password WHERE email = :email";
    
        $datos=[
            ":email"=>$email,
            ":nia"=>$nia,
            ":telefono"=>$telefono,
            ":nombre"=>$nombre,
            ":cv_file"=>$cv_file,
            ":password"=>$password
        ];
    
        $stmt2 = $pdo->prepare($sql_modif);
        $stmt2->execute($datos);
        
        header("location:datosAlumnosTutor.php");
    }
}else{

    if (isset($_POST['guardar'])){

        $sql="INSERT INTO alumno values (:email, :nia, :telefono, :nombre, :cv_file, :password)";
        
        $datos=[
            ":email"=>$email,
            ":nia"=>$nia,
            ":telefono"=>$telefono,
            ":nombre"=>$nombre,
            ":cv_file"=>$cv_file,
            ":password"=>$password
        ];
        
        $stmt=$pdo->prepare($sql);
        $stmt->execute($datos);
        header("location:datosAlumnosTutor.php");
       
    }
}
}catch(PDOException $e){
    echo "Error en la conexion con la base de datos" .$e->getMessage();
}




/*
if ((isset($_POST['guardar']))){
    

        $sql="INSERT INTO alumno values (:email, :nia, :telefono, :nombre, :cv_file, ':password')";
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

if ($formulario_lleno){
    if((isset($_POST))){
        include ("conexion.php");
        $email=$_POST['email_editar'] ?? null;

        $sql_select = "select * from alumno where email='".$email."';";
        $datos=[
            ":email"=>$email,
            ":nia"=>$nia,
            ":telefono"=>$telefono,
            ":nombre"=>$nombre,
            ":cv_file"=>$cv_file,
            ":password"=>$password
        ];
        
        $stmt=$pdo->prepare($sql_select);
        $stmt->execute();



    }
}
if(isset($_POST['cancelar'])){

    $sql_modif = "UPDATE alumno SET email = :email, nia = :nia, telefono = :telefono, nombre = :nombre, cv_file = :cv_file, password = :password WHERE email = :email";

    $datos=[];

    $stmt2 = $pdo->prepare($sql_modif);
    $stmt2->execute($datos);
    
    header("locate:datosAlumnosTutor.php");
}
*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
        <form action="insertmodif.php" method="post">
        <label>Email </label>
        <input type="text" name="email" value="<?php echo $email; ?>" required>
        <label>Nia</label>
        <input type="text" name="nia" pattern="[0-9]{8}" value="<?php echo $nia; ?>"  > <br>
        <label>Telefono </label>
        <input type="text" name="telefono" value="<?php echo $telefono; ?>" > <br>
        <label>Nombre </label>
        <input type="text" name="nombre" value="<?php echo $nombre?>" > <br>
        <label>CV</label>
        <input type="text" name="cv_file" value="<?php echo $cv_file?>" > <br>
        <label>Contraseña </label>
        <input type="text" name="password" value="<?php echo $password?>" > <br>
        <input type="submit" name="guardar" value="guardar"> 
        <input type="hidden" name="modoEdicion" value="<?php echo $modo_edicion?>">
        <input type="submit" value="cancelar" href="datosAlumnosTutor.php">
        </form>
    </fieldset>
</body>
</html>