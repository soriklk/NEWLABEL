<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEWLABEL | Iniciar Sesión</title>
    <link rel="stylesheet" href="auth.css">
</head>
<body>

    <div class="contenedor" action="scuffers.php">
        <h2>Iniciar Sesión</h2>
        

        <form id="formLogin" action="login.php" method="POST">
            <label for="email">Correo Electrónico:</label>
            <input type="email" name="email" id="email" required>
        
            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password" required>
            
            <button type="submit">Entrar</button>
        </form>

          <!-- Mensaje de error -->
        <?php session_start(); if (isset($_SESSION['error'])): ?>
            <p class="error" style="color:red;"><?= $_SESSION['error']; ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        

        <p>¿No tienes cuenta? <a href="registroV.php">Regístrate aquí</a></p>
    </div>

    <script src="auth.js"></script>
</body>
</html>