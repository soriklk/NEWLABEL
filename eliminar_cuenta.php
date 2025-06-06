<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["id_usuario"])) {
    die("Error: Debes iniciar sesión para acceder a esta página.");
}

// Conexión a la base de datos
require_once('conexion.php');  // Asegúrate de tener este archivo para la conexión

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el ID del usuario desde la sesión
    $userId = $_SESSION["id_usuario"];

    // Preparar la consulta para eliminar al usuario de la base de datos
    $query = "DELETE FROM usuarios WHERE id_usuario = ?";
    
    if ($stmt = $conn->prepare($query)) {  // Usar $conn en lugar de $db
        // Vincular el parámetro y ejecutar la consulta
        $stmt->bind_param("i", $userId);
        
        if ($stmt->execute()) {
            // Eliminar la sesión del usuario
            session_destroy();
            
            // Redirigir al index.php después de eliminar la cuenta
            header("Location: index.php");
            exit();  // Asegúrate de que el script termine aquí
        } else {
            // Si la eliminación falló, redirigir al index.php también
            header("Location: index.php");
            exit();  // Asegúrate de que el script termine aquí
        }
        $stmt->close();
    } else {
        // Si la preparación de la consulta falla, redirigir al index.php
        header("Location: index.php");
        exit();  // Asegúrate de que el script termine aquí
    }
} else {
    // Si el formulario no se ha enviado, mostrar la confirmación de eliminación
    echo "<script>
            if (confirm('¿Estás seguro de que deseas eliminar tu cuenta? Esta acción no se puede deshacer.')) {
                // Enviar el formulario de eliminación
                document.getElementById('formEliminar').submit(); 
            } else {
                window.location.href = 'index.php'; // Redirigir al index.php si el usuario cancela
            }
          </script>";
    // Formulario oculto para hacer POST y eliminar la cuenta
    echo "<form id='formEliminar' action='' method='POST' style='display:none;'>
            <input type='hidden' name='eliminar' value='1' />
          </form>";
}
?>
