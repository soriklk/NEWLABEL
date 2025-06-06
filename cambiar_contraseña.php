<?php
session_start();
require_once "conexion.php"; // Conectar a la base de datos

if (!isset($conn)) {
    die("Error: No se pudo establecer la conexión con la base de datos.");
}

// Verificar si se recibió el formulario por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validar que el usuario esté autenticado
    if (!isset($_SESSION["id_usuario"])) {
        die("Error: Usuario no autenticado. <a href='login.php'>Inicia sesión</a>");
    }

    $id_usuario = $_SESSION["id_usuario"];

    // Obtener los datos del formulario
    $password_actual = $_POST["password_actual"] ?? "";
    $password_nueva = $_POST["password_nueva"] ?? "";

    if (empty($password_actual) || empty($password_nueva)) {
        die("Error: Todos los campos son obligatorios.");
    }

    // Obtener la contraseña actual del usuario
    $stmt = $conn->prepare("SELECT password FROM usuarios WHERE id_usuario = ?");
    
    if (!$stmt) {
        die("Error en la consulta: " . $conn->error);
    }
    
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($password_hash_db);
        $stmt->fetch();

        // Verificar la contraseña actual
        if (password_verify($password_actual, $password_hash_db)) {

            // Hashear la nueva contraseña
            $password_nueva_hash = password_hash($password_nueva, PASSWORD_DEFAULT);

            // Actualizar la contraseña en la base de datos
            $update_stmt = $conn->prepare("UPDATE usuarios SET password = ? WHERE id_usuario = ?");
            if (!$update_stmt) {
                die("Error en la consulta de actualización: " . $conn->error);
            }
            
            $update_stmt->bind_param("si", $password_nueva_hash, $id_usuario);

            if ($update_stmt->execute()) {
                // Mostrar el popup con SweetAlert
                echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                      <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                title: 'Contraseña cambiada correctamente',
                                icon: 'success',
                                confirmButtonText: 'Aceptar'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = 'loginV.php';
                                }
                            });
                        });
                      </script>";
            } else {
                // Error al actualizar la contraseña
                echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                      <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                title: 'Error al actualizar la contraseña',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        });
                      </script>";
            }

            $update_stmt->close();
        } else {
            // Contraseña actual incorrecta
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                  <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            title: 'La contraseña actual es incorrecta',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    });
                  </script>";
        }
    } else {
        // Usuario no encontrado
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
              <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Usuario no encontrado',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                });
              </script>";
    }

    $stmt->close();
    $conn->close();
}
?>



