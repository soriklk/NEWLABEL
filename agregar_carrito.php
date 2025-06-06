<?php
session_start();
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($_SESSION["carrito"])) {
    $_SESSION["carrito"] = [];
}

$_SESSION["carrito"][] = $data;

echo json_encode(["success" => true, "mensaje" => "Producto añadido al carrito"]);
?>
