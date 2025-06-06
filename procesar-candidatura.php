<?php
// 1. Configuración básica con manejo de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

$destinatario = "info@newlabel.es";
$asunto = "Nueva candidatura para NEWLABEL - " . (!empty($_POST['posicion']) ? htmlspecialchars($_POST['posicion']) : "Posición no especificada");

// 2. Validación simplificada pero robusta
if (empty($_POST['nombre']) || empty($_POST['email']) || empty($_FILES['cv']['tmp_name'])) {
    header('Location: trabaja-con-nosotros.php?status=error&message=missing_fields');
    exit;
}

// 3. Sanitización segura
$nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$telefono = !empty($_POST['telefono']) ? filter_var($_POST['telefono'], FILTER_SANITIZE_STRING) : 'No proporcionado';
$posicion = !empty($_POST['posicion']) ? htmlspecialchars($_POST['posicion']) : 'No especificada';

// 4. Validación de email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: trabaja-con-nosotros.php?status=error&message=invalid_email');
    exit;
}

// 5. Procesamiento de archivo (versión simplificada para evitar errores)
$archivoCV = $_FILES['cv'];
$extension = pathinfo($archivoCV['name'], PATHINFO_EXTENSION);
$extensionesPermitidas = ['pdf', 'doc', 'docx'];

if (!in_array(strtolower($extension), $extensionesPermitidas)) {
    header('Location: trabaja-con-nosotros.php?status=error&message=invalid_file_type');
    exit;
}

if ($archivoCV['size'] > 5242880) { // 5MB
    header('Location: trabaja-con-nosotros.php?status=error&message=file_too_large');
    exit;
}

// 6. Construcción del correo (HTML mínimo garantizado)
$mensaje = "
<html>
<head>
    <title>$asunto</title>
</head>
<body>
    <h2>Nueva Candidatura</h2>
    <p><strong>Nombre:</strong> $nombre</p>
    <p><strong>Email:</strong> $email</p>
    <p><strong>Teléfono:</strong> $telefono</p>
    <p><strong>Posición:</strong> $posicion</p>
    <p><strong>CV:</strong> {$archivoCV['name']}</p>
</body>
</html>
";

// 7. Cabeceras esenciales
$headers = "From: Formulario NEWLABEL <no-reply@newlabel.es>\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

// 8. Intento de envío con manejo de errores
try {
    $envioExitoso = mail($destinatario, $asunto, $mensaje, $headers);
    
    if (!$envioExitoso) {
        throw new Exception("Falló la función mail()");
    }
    
    header('Location: trabaja-con-nosotros.php?status=success');
    exit;
    
} catch (Exception $e) {
    error_log("Error al enviar candidatura: " . $e->getMessage());
    header('Location: trabaja-con-nosotros.php?status=error&message=send_error');
    exit;
}
?>