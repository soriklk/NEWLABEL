<?php
session_start();

$host = 'mysql';
$dbname = 'u807590240_bbdd';
$username = 'u807590240_ivan'; 
$password = 'Pozuelo202020'; 

// Verificar si el formulario se envió con POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['email']) || empty($_POST['email'])) {
        $_SESSION['error'] = "Debe ingresar un correo electrónico.";
        header("Location: login.php");
        exit();
    }

    $email = $_POST['email'];
    $password_input = $_POST['password'];

 // Verificación especial para el admin
if ($email === 'admin@gmail.com' && $password_input === 'Pozuelo202020') {
    $_SESSION['id_usuario'] = 'admin';
    $_SESSION['email'] = 'admin@gmail.com';
    $_SESSION['loggedin'] = true; // ✅ ESTO ES NECESARIO
    $_SESSION['is_admin'] = true; // ✅ ESTO TAMBIÉN
    header("Location: dashboard.php");
    exit();
}


    try {
        // Conectar a la base de datos
        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Consulta segura con prepared statements
        $stmt = $conn->prepare('SELECT * FROM usuarios WHERE email = :email');
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            // Verificar la contraseña utilizando password_verify
            if (password_verify($password_input, $usuario['password'])) {
                $_SESSION['id_usuario'] = $usuario['id_usuario'];
                $_SESSION['email'] = $usuario['email'];
                header("Location: scuffers.php");
                exit();
            } else {
                $_SESSION['error'] = "Usuario o contraseña incorrectos.";
                header("Location: loginV.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Usuario o contraseña incorrectos.";
            header("Location: loginV.php");
            exit();
        }

    } catch (PDOException $e) {
        echo "¡Error de conexión!: " . $e->getMessage();
    }
} else {
    $_SESSION['error'] = "Acceso no autorizado.";
    header("Location: login.php");
    exit();
}
?>