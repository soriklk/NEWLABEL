<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEWLABEL | Registro</title>
    <link rel="stylesheet" href="auth.css">
</head>
<body>

    <div class="contenedor">
        <h2>Registrarse</h2>
        <form id="formRegistro" action="registro.php" method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
        
            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required>
        
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
        
            <button type="submit">Registrarse</button>
        </form>

        <!-- Mensajes de éxito o error -->
        <?php if (isset($_SESSION['mensaje'])): ?>
            <div id="mensaje" style="color: <?php echo ($_SESSION['tipo_mensaje'] == 'exito') ? 'green' : 'red'; ?>;">
                <?php echo $_SESSION['mensaje']; ?>
            </div>
            <script>
                setTimeout(function() {
                    document.getElementById('mensaje').style.display = 'none';
                    <?php if ($_SESSION['tipo_mensaje'] == 'exito'): ?>
                        window.location.href = 'loginV.php'; // Redirige después de 2 segundos si el registro fue exitoso
                    <?php endif; ?>
                }, 2000);
            </script>
            <?php unset($_SESSION['mensaje']); unset($_SESSION['tipo_mensaje']); ?>
        <?php endif; ?>

        <p>¿Ya tienes cuenta? <a href="loginV.php">Inicia sesión aquí</a></p>
    </div>

    <script src="auth.js"></script>

</body>
</html>
