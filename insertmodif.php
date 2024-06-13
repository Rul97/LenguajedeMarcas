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
//Prueba con una ternaria
//if((isset($_POST['accion']) && $_POST['accion'] == "Modificar")) ? "Modificar" : "Insertar";
//$modoEdicion = ($accion == "Modificar");
//Comprobamos que accion lleve datos para saber si vamos a insertar o modificar y en la segunda carga si el post de la variable accion lleva datos
//sabremos si accion es igual a modificar nos llevara al if del update y si no lleva ningun tipo de datos al darle nos metera en la opcion insert
if(isset($_POST['accion']) && $_POST['accion']=="Modificar"){
     $accion="Modificar";
}
else{ 
    $accion="Insertar";
}
$modoEdicion = ($accion == "Modificar");



include ("conexion.php");
try{
if($modoEdicion===true){
    print_r("Voy a modificar");
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

        print_r("Voy a insertar");

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
        <input type="text" name="email"  pattern="[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[a-zA-Z]+" value="<?php echo $email; ?>" required>
        <label>Nia</label>
        <input type="text" name="nia" pattern="[0-9]{8}" value="<?php echo $nia; ?>"  required> <br>
        <script>
            var Input = document.getElementById(nia);
            Input.oninvalid = function(event) {
            event.target.setCustomValidity('Solo se permiten numeros.');
            }   
        </script>
        <label>Telefono </label>
        <input type="text" name="telefono" value="<?php echo $telefono; ?>" > <br>
        <label>Nombre </label>
        <input type="text" name="nombre" value="<?php echo $nombre?>" required > <br>
        <label>CV</label>
        <input type="text" name="cv_file" value="<?php echo $cv_file?>" > <br>
        <label>Contraseña </label>
        <input type="text" name="password" value="<?php echo $password?>" > <br>
        <input type="submit" name="guardar" value="guardar"> 
       <!-- <input type="hidden" name="modoEdicion" value="<?php echo $modoEdicion?>">-->
        <input type="hidden" name="accion" value="<?php echo $accion; ?>">
        <input type="submit" value="cancelar">
        </form>
    </fieldset>
</body>
</html>