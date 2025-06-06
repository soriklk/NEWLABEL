<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["id_usuario"])) {
    die("Error: Debes iniciar sesión para acceder a esta página. <a href='login.php'>Iniciar sesión</a>");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración - NEWLABEL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #000000;
            --secondary-color: #ffffff;
            --accent-color: #f8f9fa;
            --text-color: #333333;
            --border-color: #e0e0e0;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
        }
        
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--accent-color);
            color: var(--text-color);
            line-height: 1.6;
        }
        
        .navbar {
            background-color: var(--secondary-color) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 15px 0;
        }
        
        .logo-newlabel {
            width: 120px;
        }
        
        .settings-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 0 20px;
        }
        
        .settings-header {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .settings-header h2 {
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 15px;
            color: var(--primary-color);
        }
        
        .settings-header p {
            color: #666;
            font-size: 1.1rem;
        }
        
        .settings-section {
            background-color: var(--secondary-color);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            border: 1px solid var(--border-color);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .settings-section:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 25px rgba(0,0,0,0.1);
        }
        
        .settings-section h3 {
            font-size: 1.4rem;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--border-color);
            display: flex;
            align-items: center;
            font-weight: 600;
        }
        
        .settings-section h3 i {
            margin-right: 12px;
            color: var(--primary-color);
            font-size: 1.5rem;
        }
        
        .form-control {
            border-radius: 6px;
            border: 1px solid var(--border-color);
            padding: 12px 15px;
            margin-bottom: 20px;
            font-size: 1rem;
        }
        
        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(0,0,0,0.1);
            border-color: var(--primary-color);
        }
        
        .btn-newlabel {
            background-color: var(--primary-color);
            color: var(--secondary-color);
            border: none;
            padding: 12px 25px;
            font-weight: 600;
            letter-spacing: 0.5px;
            border-radius: 6px;
            transition: all 0.3s;
            text-transform: uppercase;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-newlabel:hover {
            background-color: #333;
            color: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .btn-newlabel i {
            font-size: 1.1rem;
        }
        
        .btn-danger {
            padding: 12px 25px;
            font-weight: 600;
            border-radius: 6px;
            transition: all 0.3s;
            text-transform: uppercase;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-danger:hover {
            transform: translateY(-2px);
        }
        
        .btn-danger i {
            font-size: 1.1rem;
        }
        
        .warning-text {
            color: var(--danger-color);
            font-weight: 500;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }
        
        /* Dark mode styles */
        body.dark-mode {
            --primary-color: #ffffff;
            --secondary-color: #121212;
            --accent-color: #1e1e1e;
            --text-color: #f1f1f1;
            --border-color: #333333;
            background-color: var(--accent-color);
            color: var(--text-color);
        }
        
        body.dark-mode .settings-header p {
            color: #aaa;
        }
        
        body.dark-mode .settings-section {
            background-color: #1e1e1e;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            border-color: #333;
        }
        
        body.dark-mode .settings-section h3 {
            border-bottom-color: #333;
        }
        
        body.dark-mode .form-control {
            background-color: #2d2d2d;
            border-color: #444;
            color: var(--text-color);
        }
        
        body.dark-mode .form-control:focus {
            border-color: #666;
            box-shadow: 0 0 0 3px rgba(255,255,255,0.1);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .settings-container {
                margin: 30px auto;
            }
            
            .settings-section {
                padding: 20px;
            }
            
            .settings-header h2 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <img src="../img/logo-newlabel-removebg-preview.png" alt="NEWLABEL" class="logo-newlabel">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="../index.php">Inicio</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="settings-container">
        <div class="settings-header">
            <h2><i class="bi bi-gear"></i> Configuración de Cuenta</h2>
            <p>Personaliza tu experiencia en NEWLABEL</p>
        </div>

        <!-- Modo Oscuro/Claro -->
        <div class="settings-section">
            <h3><i class="bi bi-palette"></i> Apariencia</h3>
            <p>Personaliza el tema de la interfaz según tus preferencias</p>
            <button class="btn btn-newlabel" onclick="toggleDarkMode()">
                <i class="bi" id="theme-icon"></i> Cambiar Tema
            </button>
        </div>

        <!-- Cambiar Contraseña -->
        <div class="settings-section">
            <h3><i class="bi bi-shield-lock"></i> Seguridad</h3>
            <form action="cambiar_contraseña.php" method="post">
                <div class="mb-3">
                    <label class="form-label">Contraseña Actual</label>
                    <input type="password" name="password_actual" class="form-control" placeholder="Ingresa tu contraseña actual" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nueva Contraseña</label>
                    <input type="password" name="password_nueva" class="form-control" placeholder="Crea una nueva contraseña segura" required>
                </div>
                <button type="submit" class="btn btn-newlabel">
                    <i class="bi bi-arrow-repeat"></i> Actualizar Contraseña
                </button>
            </form>
        </div>

        <!-- Eliminar Cuenta -->
        <div class="settings-section">
            <h3><i class="bi bi-exclamation-octagon"></i> Zona Peligrosa</h3>
            <p class="warning-text">
                <i class="bi bi-exclamation-triangle-fill"></i> Esta acción eliminará permanentemente tu cuenta y todos los datos asociados. No podrás recuperar esta información.
            </p>
            <form action="eliminar_cuenta.php" method="post" onsubmit="return confirm('¿Estás completamente seguro de que deseas eliminar tu cuenta? Esta acción no se puede deshacer.');">
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash3"></i> Eliminar Cuenta Permanentemente
                </button>
            </form>
        </div>
    </div>

    <script>
        // Función para cambiar entre modo oscuro/claro
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            updateThemeIcon();
            localStorage.setItem('darkMode', document.body.classList.contains('dark-mode') ? 'enabled' : 'disabled');
        }
        
        // Actualizar el icono del tema según el modo actual
        function updateThemeIcon() {
            const icon = document.getElementById('theme-icon');
            if (document.body.classList.contains('dark-mode')) {
                icon.classList.remove('bi-moon');
                icon.classList.add('bi-sun');
                document.querySelector('.btn-newlabel[onclick="toggleDarkMode()"]').innerHTML = '<i class="bi bi-sun"></i> Cambiar a Modo Claro';
            } else {
                icon.classList.remove('bi-sun');
                icon.classList.add('bi-moon');
                document.querySelector('.btn-newlabel[onclick="toggleDarkMode()"]').innerHTML = '<i class="bi bi-moon"></i> Cambiar a Modo Oscuro';
            }
        }
        
        // Aplicar preferencia al cargar
        document.addEventListener("DOMContentLoaded", function() {
            if (localStorage.getItem('darkMode') === 'enabled') {
                document.body.classList.add('dark-mode');
            }
            updateThemeIcon();
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>