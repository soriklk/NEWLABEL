<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start(); // Iniciar sesión

$host = 'localhost';
$dbname = 'u807590240_bbdd';
$username = 'u807590240_ivan';
$password = 'Pozuelo202020';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Comprobar si el usuario ya existe
        $stmt = $conn->prepare("SELECT COUNT(*) FROM usuarios WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $existe = $stmt->fetchColumn();

        if ($existe) {
            $_SESSION['mensaje'] = "El usuario ya existe.";
            $_SESSION['tipo_mensaje'] = "error"; // Tipo de mensaje rojo
            header("Location: registroV.php");
            exit;
        }

        // Si no existe, registrar el usuario
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (nombre, email, password) VALUES (:nombre, :email, :password)";
        $stmt = $conn->prepare($sql);
        
        if ($stmt->execute([':nombre' => $nombre, ':email' => $email, ':password' => $hashed_password])) {
            $_SESSION['mensaje'] = "Usuario creado exitosamente.";
            $_SESSION['tipo_mensaje'] = "exito"; // Tipo de mensaje verde
            header("Location: registroV.php");
            exit;
        } else {
            $_SESSION['mensaje'] = "Error al registrar el usuario.";
            $_SESSION['tipo_mensaje'] = "error";
            header("Location: registroV.php");
            exit;
        }
    }

} catch (PDOException $e) {
    $_SESSION['mensaje'] = "Error de conexión: " . $e->getMessage();
    $_SESSION['tipo_mensaje'] = "error";
    header("Location: registroV.php");
    exit;
}

$conn = null;
?>
