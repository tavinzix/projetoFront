<?php

$servername = "localhost";
$username = "postgres";
$password = "superi";
$dbname = "postgres";

try {
	$connection = new PDO("pgsql:host=$servername;port=5432;dbname=$dbname; user=$username;password=$password");

	$connection->setAttribute(
		PDO::ATTR_ERRMODE,
		PDO::ERRMODE_EXCEPTION
	);
} catch (PDOException $e) {
	die("Sistema não está funcionando ----- pgsql:host=$servername;port=5432;dbname=$dbname; user=$username;password=$password");
}
?>