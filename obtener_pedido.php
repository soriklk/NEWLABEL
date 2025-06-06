<?php
// Archivo: obtener_pedido.php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@gmail.com') {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit();
}

$host = 'localhost';
$dbname = 'u807590240_bbdd';
$username = 'u807590240_ivan';
$password = 'Pozuelo202020';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if (isset($_POST['id'])) {
        $stmt = $conn->prepare("
            SELECT p.*, c.nombre as cliente_nombre, pr.nombre as producto_nombre, pr.precio
            FROM pedidos p
            JOIN clientes c ON p.cliente_id = c.id
            JOIN productos pr ON p.producto_id = pr.id
            WHERE p.id = :id
        ");
        $stmt->execute([':id' => $_POST['id']]);
        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($pedido) {
            // Formatear fecha para mejor visualización
            $fecha = new DateTime($pedido['fecha_pedido']);
            $pedido['fecha_pedido_formateada'] = $fecha->format('d/m/Y H:i');
            
            echo json_encode([
                'success' => true,
                'data' => $pedido
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Pedido no encontrado'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'ID de pedido no proporcionado'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error de base de datos: ' . $e->getMessage()
    ]);
}
?>