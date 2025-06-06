<?php
$servidor = 'localhost';
$base_datos = 'u807590240_bbdd';
$usuario = 'u807590240_ivan'; 
$clave = 'Pozuelo202020'; 

$conn = new mysqli($servidor, $usuario, $clave, $base_datos);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>

