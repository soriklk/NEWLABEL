<?php
// Desactiva el reporte de errores en pantalla. Es crucial para no corromper la salida JSON.
// En un entorno de desarrollo, puedes comentarlo para ver errores, pero actívalo en producción.
error_reporting(0);
ini_set('display_errors', 0);

// Establece la cabecera para indicar que la respuesta será JSON
header('Content-Type: application/json');

// Inicializa la respuesta por defecto como un fallo
$response = ['success' => false, 'message' => ''];

// Verifica que la petición sea POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger y sanitizar los datos del formulario
    // Usamos el operador null coalescing (??) para evitar warnings si alguna clave no existe
    $nombre = htmlspecialchars(trim($_POST['nombre'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $telefono = htmlspecialchars(trim($_POST['telefono'] ?? 'No proporcionado'));
    $motivo = htmlspecialchars(trim($_POST['motivo'] ?? ''));
    $mensaje = htmlspecialchars(trim($_POST['mensaje'] ?? ''));
    
    // --- Validaciones básicas ---
    if (empty($nombre) || empty(filter_var($email, FILTER_VALIDATE_EMAIL)) || empty($motivo) || empty($mensaje)) {
        $response['message'] = 'Por favor, rellena todos los campos obligatorios y asegúrate de que el email es válido.';
    } else {
        // --- Configuración del correo ---
        $destinatario = "info@newlabel.es"; // ¡IMPORTANTE! Asegúrate de que esta sea tu dirección de correo real
        $asunto = "Nuevo mensaje de contacto - Motivo: " . $motivo;
        
        // Cuerpo del mensaje en formato HTML
        $cuerpo = "
        <html>
        <head>
            <title>Nuevo mensaje de contacto de NEWLABEL</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background-color: #fff; }
                .header { background-color: #000; color: #fff; padding: 15px; text-align: center; border-radius: 5px 5px 0 0; }
                .content { padding: 20px 0; }
                .data-row { margin-bottom: 10px; }
                .data-row strong { display: inline-block; width: 80px; }
                .message-box { background-color: #f9f9f9; padding: 15px; border: 1px solid #eee; border-radius: 5px; margin-top: 15px; }
                .footer { margin-top: 30px; font-size: 0.9em; color: #777; text-align: center; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Mensaje de Contacto de NEWLABEL</h2>
                </div>
                <div class='content'>
                    <p>Has recibido un nuevo mensaje a través del formulario de contacto de tu sitio web:</p>
                    <div class='data-row'><p><strong>Nombre:</strong> " . $nombre . "</p></div>
                    <div class='data-row'><p><strong>Email:</strong> " . $email . "</p></div>
                    <div class='data-row'><p><strong>Teléfono:</strong> " . $telefono . "</p></div>
                    <div class='data-row'><p><strong>Motivo:</strong> " . $motivo . "</p></div>
                    <div class='message-box'>
                        <p><strong>Mensaje:</strong><br>" . nl2br($mensaje) . "</p>
                    </div>
                </div>
                <div class='footer'>
                    Este mensaje fue enviado desde newlabel.es
                </div>
            </div>
        </body>
        </html>
        ";
        
        // Cabeceras para el correo HTML
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: " . $nombre . " <" . $email . ">\r\n";
        $headers .= "Reply-To: " . $email . "\r\n";
        // Puedes añadir una cabecera Cc o Bcc si lo necesitas
        // $headers .= "Cc: otra_direccion@example.com\r\n";

        // Enviar el correo
        if (mail($destinatario, $asunto, $cuerpo, $headers)) {
            $response['success'] = true;
            $response['message'] = '¡Mensaje enviado correctamente! Nos pondremos en contacto contigo pronto.';
        } else {
            // Si mail() devuelve false, hubo un problema al enviar el correo
            // Esto puede deberse a la configuración del servidor de correo
            $response['message'] = 'Hubo un error al enviar el mensaje. Por favor, inténtalo de nuevo más tarde.';
        }
    }
} else {
    // Si alguien intenta acceder directamente a este archivo PHP sin ser una petición POST
    $response['message'] = 'Acceso no permitido.';
}

// Envía la respuesta JSON y termina la ejecución del script.
// Es vital que no haya ninguna otra salida (espacios, enter, etc.) antes o después de esto.
echo json_encode($response);
exit();
?>