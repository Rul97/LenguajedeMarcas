<?php
	$host='localhost';
	$dbname='practicasfct';
	$user='root';
	$pass='';

	try{
		$pdo= new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user,$pass);
		$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
	}catch(PDOException $e){
			echo "Error en la conexion con la base de datos" .$e->getMessage();
		}
?>