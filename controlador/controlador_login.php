<?php
session_start();
if (!empty($_POST['logear'])) {
	if (!empty($_POST['usuario'] && !empty($_POST['pass']))) {
		$usuario=$_POST['usuario'];
		$pass=$_POST['pass'];

		$sql_alumno = $pdo->prepare("select * from alumno where email = :usuario and password = :pass");
		$sql_tutor = $pdo->prepare("select * from tutor where email=:usuario and password=:pass");

		$sql_alumno->bindValue(':usuario', $usuario);
		$sql_alumno->bindValue(':pass', $pass);

		$sql_tutor->bindValue(':usuario', $usuario);
		$sql_tutor->bindValue(':pass', $pass);

		$sql_alumno->execute();
		$sql_tutor->execute();

		$resultado_alumno=$sql_alumno->fetch(PDO::FETCH_ASSOC);
		$resultado_tutor=$sql_tutor->fetch(PDO::FETCH_ASSOC);
		
		if ($resultado_alumno) {
			$_SESSION['id']=$resultado_alumno['email'];
			header("location: alumnoprincipal.php");
		}elseif ($resultado_tutor) {
			$_SESSION['id']=$resultado_tutor['email'];
			header("location: tutorprincipal.php");
		}else{
			echo "Los datos no son correctos";
		}
		
	}else{
		echo "Campos Vacios";
	}
}
?>