<?php
// Muestra errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 1. Conexión a la base de datos
$host = 'localhost';
$dbname = 'u807590240_bbdd';
$username = 'u807590240_ivan';
$password = 'Pozuelo202020';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// 2. Verificación de sesión y permisos
session_start();

if (!isset($_SESSION['loggedin']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: login.php");
    exit;
}

// 3. Determinar qué sección mostrar
$section = isset($_GET['section']) ? $_GET['section'] : 'dashboard';

// 4. Procesar acciones del formulario (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_status'])) {
        $pedido_id = $_POST['pedido_id'];
        $nuevo_estado = $_POST['nuevo_estado'];

        $stmt = $pdo->prepare("UPDATE pedidos SET estado = ? WHERE id = ?");
        $stmt->execute([$nuevo_estado, $pedido_id]);

        $_SESSION['message'] = "Estado del pedido #$pedido_id actualizado a $nuevo_estado.";
    }

    if (isset($_POST['delete_order'])) {
        $pedido_id = $_POST['pedido_id'];

        $pdo->prepare("DELETE FROM detalles_pedido WHERE pedido_id = ?")->execute([$pedido_id]);
        $pdo->prepare("DELETE FROM pedidos WHERE id = ?")->execute([$pedido_id]);

        $_SESSION['message'] = "Pedido #$pedido_id eliminado correctamente.";
    }

    // Procesar acciones para clientes
    if (isset($_POST['add_client'])) {
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO clientes (email, password) VALUES (?, ?)");
        $stmt->execute([$email, $password]);

        $_SESSION['message'] = "Cliente $email añadido correctamente.";
    }

    if (isset($_POST['edit_client'])) {
        $id = $_POST['id'];
        $email = $_POST['email'];
        
        // Si se proporcionó una nueva contraseña, actualizarla
        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE clientes SET email = ?, password = ? WHERE id = ?");
            $stmt->execute([$email, $password, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE clientes SET email = ? WHERE id = ?");
            $stmt->execute([$email, $id]);
        }

        $_SESSION['message'] = "Cliente #$id actualizado correctamente.";
    }

    if (isset($_POST['delete_client'])) {
        $id = $_POST['id'];

        $pdo->prepare("DELETE FROM clientes WHERE id = ?")->execute([$id]);

        $_SESSION['message'] = "Cliente #$id eliminado correctamente.";
    }

    // Procesar acciones para productos
    if (isset($_POST['add_product'])) {
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        $descripcion = $_POST['descripcion'] ?? '';

        $stmt = $pdo->prepare("INSERT INTO productos (nombre, precio, descripcion) VALUES (?, ?, ?)");
        $stmt->execute([$nombre, $precio, $descripcion]);

        $_SESSION['message'] = "Producto $nombre añadido correctamente.";
    }

    if (isset($_POST['edit_product'])) {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        $descripcion = $_POST['descripcion'] ?? '';

        $stmt = $pdo->prepare("UPDATE productos SET nombre = ?, precio = ?, descripcion = ? WHERE id = ?");
        $stmt->execute([$nombre, $precio, $descripcion, $id]);

        $_SESSION['message'] = "Producto #$id actualizado correctamente.";
    }

    if (isset($_POST['delete_product'])) {
        $id = $_POST['id'];

        $pdo->prepare("DELETE FROM productos WHERE id = ?")->execute([$id]);

        $_SESSION['message'] = "Producto #$id eliminado correctamente.";
    }
}

// 5. Configuración de filtros y paginación (solo para la sección de pedidos)
$estado_filtro = isset($_GET['estado']) ? $_GET['estado'] : '';
$pedidos_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $pedidos_por_pagina;

// 6. Consultas para el dashboard
// Consulta para los pedidos con filtros y paginación (solo si estamos en dashboard o pedidos)
if ($section === 'dashboard' || $section === 'pedidos') {
    $sql_pedidos = "
        SELECT p.*, c.email 
        FROM pedidos p
        JOIN clientes c ON p.cliente_id = c.id
    ";

    if (!empty($estado_filtro)) {
        $sql_pedidos .= " WHERE p.estado = :estado";
    }

    $sql_pedidos .= " ORDER BY p.fecha_pedido DESC LIMIT :offset, :pedidos_por_pagina";

    $stmt_pedidos = $pdo->prepare($sql_pedidos);

    if (!empty($estado_filtro)) {
        $stmt_pedidos->bindParam(':estado', $estado_filtro);
    }

    $stmt_pedidos->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt_pedidos->bindValue(':pedidos_por_pagina', $pedidos_por_pagina, PDO::PARAM_INT);
    $stmt_pedidos->execute();
    $pedidos = $stmt_pedidos->fetchAll(PDO::FETCH_ASSOC);

    // Consulta para contar el total de pedidos (para paginación)
    $sql_count = "SELECT COUNT(*) FROM pedidos";
    if (!empty($estado_filtro)) {
        $sql_count .= " WHERE estado = :estado";
    }

    $stmt_count = $pdo->prepare($sql_count);
    if (!empty($estado_filtro)) {
        $stmt_count->bindParam(':estado', $estado_filtro);
    }
    $stmt_count->execute();
    $total_pedidos_filtrados = $stmt_count->fetchColumn();
    $total_paginas = ceil($total_pedidos_filtrados / $pedidos_por_pagina);
}

// Consulta para clientes (solo si estamos en la sección de clientes)
if ($section === 'clientes') {
    $clientes = $pdo->query("SELECT id, email, nombre, apellidos, direccion, ciudad, codigo_postal, pais, telefono, fecha_registro FROM clientes ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
}

// Consulta para productos (solo si estamos en la sección de productos)
if ($section === 'productos') {
    $productos = $pdo->query("SELECT * FROM productos ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
}

// Estadísticas generales (solo para dashboard)
if ($section === 'dashboard') {
    $total_clientes = $pdo->query("SELECT COUNT(*) FROM clientes")->fetchColumn();
    $total_pedidos = $pdo->query("SELECT COUNT(*) FROM pedidos")->fetchColumn();
    $pedidos_pendientes = $pdo->query("SELECT COUNT(*) FROM pedidos WHERE estado = 'pendiente'")->fetchColumn();
    $ingresos_totales = $pdo->query("SELECT SUM(total) FROM pedidos WHERE estado = 'completado'")->fetchColumn();

    // Datos para el calendario
    $eventos_calendario = $pdo->query("
        SELECT 
            id as title,
            fecha_pedido as start,
            CONCAT('Pedido #', id, ' - ', estado) as description,
            CASE 
                WHEN estado = 'completado' THEN '#4CAF50'
                WHEN estado = 'pendiente' THEN '#FFC107'
                WHEN estado = 'enviado' THEN '#2196F3'
                ELSE '#9E9E9E'
            END as backgroundColor,
            '#ffffff' as textColor
        FROM pedidos
        WHERE fecha_pedido BETWEEN DATE_SUB(NOW(), INTERVAL 1 MONTH) AND DATE_ADD(NOW(), INTERVAL 1 MONTH)
    ")->fetchAll(PDO::FETCH_ASSOC);
}

// Obtener datos de un cliente específico para edición
$cliente_editar = null;
if (isset($_GET['edit_client_id'])) {
    $id = $_GET['edit_client_id'];
    $stmt = $pdo->prepare("SELECT * FROM clientes WHERE id = ?");
    $stmt->execute([$id]);
    $cliente_editar = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Obtener datos de un producto específico para edición
$producto_editar = null;
if (isset($_GET['edit_product_id'])) {
    $id = $_GET['edit_product_id'];
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->execute([$id]);
    $producto_editar = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin Premium</title>
    
    <!-- Fuentes y estilos -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --danger: #f72585;
            --warning: #f8961e;
            --info: #4895ef;
            --light: #f8f9fa;
            --dark: #212529;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background-color: #f5f7fb;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            display: grid;
            grid-template-columns: 250px 1fr;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 20px 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-nav {
            padding: 20px;
        }
        
        .sidebar-nav a {
            display: block;
            color: rgba(255,255,255,0.8);
            padding: 10px 15px;
            margin-bottom: 5px;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .sidebar-nav a:hover, .sidebar-nav a.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
        }
        
        .sidebar-nav i {
            margin-right: 10px;
        }
        
        /* Main Content */
        .main-content {
            padding: 20px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .user-info {
            display: flex;
            align-items: center;
        }
        
        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        
        /* Cards */
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        
        .card-icon.clients {
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary);
        }
        
        .card-icon.orders {
            background-color: rgba(76, 201, 240, 0.1);
            color: var(--success);
        }
        
        .card-icon.pending {
            background-color: rgba(248, 150, 30, 0.1);
            color: var(--warning);
        }
        
        .card-icon.revenue {
            background-color: rgba(72, 149, 239, 0.1);
            color: var(--info);
        }
        
        .card-title {
            font-size: 14px;
            color: #6c757d;
            font-weight: 500;
        }
        
        .card-value {
            font-size: 24px;
            font-weight: 600;
            margin: 5px 0;
        }
        
        .card-footer {
            font-size: 12px;
            color: #6c757d;
        }
        
        /* Charts Section */
        .charts-section {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .chart-container, .calendar-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--dark);
        }
        
        /* Tables */
        .table-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        
        th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }
        
        tr:hover {
            background-color: #f8f9fa;
        }
        
        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .badge-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .badge-shipped {
            background-color: #cce5ff;
            color: #004085;
        }
        
        .badge-completed {
            background-color: #d4edda;
            color: #155724;
        }
        
        /* Buttons */
        .btn {
            padding: 8px 15px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary);
        }
        
        .btn-danger {
            background-color: var(--danger);
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #d1145a;
        }
        
        .btn-success {
            background-color: var(--success);
            color: white;
        }
        
        .btn-success:hover {
            background-color: #3ab7d8;
        }
        
        .btn-warning {
            background-color: var(--warning);
            color: white;
        }
        
        .btn-warning:hover {
            background-color: #e07e0e;
        }
        
        .select-status {
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 14px;
        }
        
        /* Calendar */
        #calendar {
            height: 400px;
        }
        
        /* Filtros */
        .filter-container {
            background: white;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        
        .filter-container label {
            font-weight: 500;
            margin-right: 10px;
        }
        
        .filter-container select {
            padding: 8px 12px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 14px;
        }
        
        /* Paginación */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }
        
        .pagination a, .pagination span {
            margin: 0 5px;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
        }
        
        .pagination a {
            background-color: var(--primary);
            color: white;
        }
        
        .pagination a:hover {
            background-color: var(--secondary);
        }
        
        .pagination .disabled {
            color: #6c757d;
            cursor: not-allowed;
        }
        
        /* Formularios */
        .form-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .form-group input, 
        .form-group select, 
        .form-group textarea {
            width: 100%;
            padding: 8px 12px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 14px;
        }
        
        .form-group textarea {
            min-height: 100px;
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        
        /* Responsive */
        @media (max-width: 1200px) {
            .charts-section {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
            }
            
            .sidebar {
                display: none;
            }
            
            .filter-container {
                padding: 10px;
            }
            
            .filter-container form {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }
            
            .filter-container select, 
            .filter-container button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>Admin Panel</h2>
            </div>
            <nav class="sidebar-nav">
                <a href="?section=dashboard" class="<?= $section === 'dashboard' ? 'active' : '' ?>"><i>📊</i> Dashboard</a>
                <a href="?section=pedidos" class="<?= $section === 'pedidos' ? 'active' : '' ?>"><i>🛒</i> Pedidos</a>
                <a href="?section=clientes" class="<?= $section === 'clientes' ? 'active' : '' ?>"><i>👥</i> Clientes</a>
                <a href="?section=productos" class="<?= $section === 'productos' ? 'active' : '' ?>"><i>📦</i> Productos</a>
                <a href="#"><i>📈</i> Informes</a>
                <a href="#"><i>⚙️</i> Configuración</a>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1><?= ucfirst($section) ?></h1>
                <div class="user-info">
                    <img src="https://ui-avatars.com/api/?name=ad&background=random" alt="User">
                    <span>admin@gmail.com</span>
                    <form method="post" action="logout.php" style="margin-left: 20px;">
                        <button type="submit" class="btn btn-danger btn-sm">Cerrar sesión</button>
                    </form>
                </div>
            </div>
            
            <?php if (isset($_SESSION['message'])): ?>
                <div style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                    <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($section === 'dashboard'): ?>
                <!-- Cards -->
                <div class="cards">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <div class="card-title">Clientes Totales</div>
                                <div class="card-value"><?= $total_clientes ?></div>
                            </div>
                            <div class="card-icon clients">
                                👥
                            </div>
                        </div>
                        <div class="card-footer">+5% desde el mes pasado</div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <div class="card-title">Pedidos Totales</div>
                                <div class="card-value"><?= $total_pedidos ?></div>
                            </div>
                            <div class="card-icon orders">
                                🛒
                            </div>
                        </div>
                        <div class="card-footer">+12% desde el mes pasado</div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <div class="card-title">Pedidos Pendientes</div>
                                <div class="card-value"><?= $pedidos_pendientes ?></div>
                            </div>
                            <div class="card-icon pending">
                                ⏳
                            </div>
                        </div>
                        <div class="card-footer">-3% desde el mes pasado</div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <div class="card-title">Ingresos Totales</div>
                                <div class="card-value"><?= number_format($ingresos_totales ?? 0, 2) ?> €</div>
                            </div>
                            <div class="card-icon revenue">
                                💰
                            </div>
                        </div>
                        <div class="card-footer">+18% desde el mes pasado</div>
                    </div>
                </div>
                
                <!-- Filtro de pedidos -->
                <div class="filter-container">
                    <form method="GET" action="">
                        <input type="hidden" name="section" value="dashboard">
                        <label for="estado">Filtrar por estado:</label>
                        <select name="estado" id="estado" style="padding: 8px 12px; margin-right: 10px;">
                            <option value="">Todos los pedidos</option>
                            <option value="pendiente" <?= $estado_filtro == 'pendiente' ? 'selected' : '' ?>>Pendientes</option>
                            <option value="enviado" <?= $estado_filtro == 'enviado' ? 'selected' : '' ?>>Enviados</option>
                            <option value="completado" <?= $estado_filtro == 'completado' ? 'selected' : '' ?>>Completados</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                        <a href="?section=dashboard" class="btn btn-secondary" style="margin-left: 10px;">Limpiar filtros</a>
                    </form>
                </div>
                
                <!-- Tabla de pedidos -->
                <div class="table-container">
                    <h3 class="section-title"><?= empty($estado_filtro) ? 'Todos los pedidos' : 'Pedidos '.ucfirst($estado_filtro) ?></h3>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Total</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pedidos as $pedido): ?>
                            <tr>
                                <td>#<?= $pedido['id'] ?></td>
                                <td><?= htmlspecialchars($pedido['email']) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])) ?></td>
                                <td>
                                    <?php 
                                        $badge_class = '';
                                        if ($pedido['estado'] == 'pendiente') $badge_class = 'badge-pending';
                                        elseif ($pedido['estado'] == 'enviado') $badge_class = 'badge-shipped';
                                        elseif ($pedido['estado'] == 'completado') $badge_class = 'badge-completed';
                                    ?>
                                    <span class="badge <?= $badge_class ?>"><?= ucfirst($pedido['estado']) ?></span>
                                </td>
                                <td><?= number_format($pedido['total'], 2) ?> €</td>
                                <td>
                                    <form method="POST" style="display: inline-block; margin-right: 5px;">
                                        <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
                                        <select name="nuevo_estado" class="select-status">
                                            <option value="pendiente" <?= $pedido['estado'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                            <option value="enviado" <?= $pedido['estado'] == 'enviado' ? 'selected' : '' ?>>Enviado</option>
                                            <option value="completado" <?= $pedido['estado'] == 'completado' ? 'selected' : '' ?>>Completado</option>
                                        </select>
                                        <button type="submit" name="update_status" class="btn btn-primary btn-sm">Actualizar</button>
                                    </form>
                                    <form method="POST" style="display: inline-block;">
                                        <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
                                        <button type="submit" name="delete_order" onclick="return confirm('¿Seguro que deseas eliminar este pedido?');" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <!-- Paginación -->
                    <?php if ($total_paginas > 1): ?>
                    <div class="pagination">
                        <?php if ($pagina_actual > 1): ?>
                            <a href="?<?= http_build_query(array_merge($_GET, ['pagina' => $pagina_actual - 1])) ?>">Anterior</a>
                        <?php endif; ?>
                        
                        <span>Página <?= $pagina_actual ?> de <?= $total_paginas ?></span>
                        
                        <?php if ($pagina_actual < $total_paginas): ?>
                            <a href="?<?= http_build_query(array_merge($_GET, ['pagina' => $pagina_actual + 1])) ?>">Siguiente</a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Charts Section -->
                <div class="charts-section">
                    <div class="chart-container">
                        <h3 class="section-title">Pedidos e Ingresos</h3>
                        <canvas id="ordersChart"></canvas>
                    </div>
                    <div class="calendar-container">
                        <h3 class="section-title">Calendario de Pedidos</h3>
                        <div id="calendar"></div>
                    </div>
                </div>
            
            <?php elseif ($section === 'pedidos'): ?>
                <!-- Filtro de pedidos -->
                <div class="filter-container">
                    <form method="GET" action="">
                        <input type="hidden" name="section" value="pedidos">
                        <label for="estado">Filtrar por estado:</label>
                        <select name="estado" id="estado" style="padding: 8px 12px; margin-right: 10px;">
                            <option value="">Todos los pedidos</option>
                            <option value="pendiente" <?= $estado_filtro == 'pendiente' ? 'selected' : '' ?>>Pendientes</option>
                            <option value="enviado" <?= $estado_filtro == 'enviado' ? 'selected' : '' ?>>Enviados</option>
                            <option value="completado" <?= $estado_filtro == 'completado' ? 'selected' : '' ?>>Completados</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                        <a href="?section=pedidos" class="btn btn-secondary" style="margin-left: 10px;">Limpiar filtros</a>
                    </form>
                </div>
                
                <!-- Tabla de pedidos -->
                <div class="table-container">
                    <h3 class="section-title"><?= empty($estado_filtro) ? 'Todos los pedidos' : 'Pedidos '.ucfirst($estado_filtro) ?></h3>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Total</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pedidos as $pedido): ?>
                            <tr>
                                <td>#<?= $pedido['id'] ?></td>
                                <td><?= htmlspecialchars($pedido['email']) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])) ?></td>
                                <td>
                                    <?php 
                                        $badge_class = '';
                                        if ($pedido['estado'] == 'pendiente') $badge_class = 'badge-pending';
                                        elseif ($pedido['estado'] == 'enviado') $badge_class = 'badge-shipped';
                                        elseif ($pedido['estado'] == 'completado') $badge_class = 'badge-completed';
                                    ?>
                                    <span class="badge <?= $badge_class ?>"><?= ucfirst($pedido['estado']) ?></span>
                                </td>
                                <td><?= number_format($pedido['total'], 2) ?> €</td>
                                <td>
                                    <form method="POST" style="display: inline-block; margin-right: 5px;">
                                        <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
                                        <select name="nuevo_estado" class="select-status">
                                            <option value="pendiente" <?= $pedido['estado'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                            <option value="enviado" <?= $pedido['estado'] == 'enviado' ? 'selected' : '' ?>>Enviado</option>
                                            <option value="completado" <?= $pedido['estado'] == 'completado' ? 'selected' : '' ?>>Completado</option>
                                        </select>
                                        <button type="submit" name="update_status" class="btn btn-primary btn-sm">Actualizar</button>
                                    </form>
                                    <form method="POST" style="display: inline-block;">
                                        <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
                                        <button type="submit" name="delete_order" onclick="return confirm('¿Seguro que deseas eliminar este pedido?');" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <!-- Paginación -->
                    <?php if ($total_paginas > 1): ?>
                    <div class="pagination">
                        <?php if ($pagina_actual > 1): ?>
                            <a href="?<?= http_build_query(array_merge($_GET, ['pagina' => $pagina_actual - 1])) ?>">Anterior</a>
                        <?php endif; ?>
                        
                        <span>Página <?= $pagina_actual ?> de <?= $total_paginas ?></span>
                        
                        <?php if ($pagina_actual < $total_paginas): ?>
                            <a href="?<?= http_build_query(array_merge($_GET, ['pagina' => $pagina_actual + 1])) ?>">Siguiente</a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            
            <?php elseif ($section === 'clientes'): ?>
                <!-- Formulario para agregar/editar cliente -->
                
                
                <!-- Tabla de clientes -->
                <div class="table-container">
                    <h3 class="section-title">Listado de Clientes</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Email</th>
                                <th>Nombre</th>
                                <th>Apellidos</th>
                                <th>Direccion</th>
                                <th>Ciudad</th>
                                <th>Codigo Postal</th>
                                <th>Pais</th>
                                <th>Telefono</th>
                                <th>Registro</th>

                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clientes as $cliente): ?>
                            <tr>
                                <td><?= $cliente['id'] ?></td>
                                <td><?= htmlspecialchars($cliente['email']) ?></td>
                                <td><?= $cliente['nombre'] ?></td>
                                <td><?= $cliente['apellidos'] ?></td>
                                <td><?= $cliente['direccion'] ?></td>
                                <td><?= $cliente['ciudad'] ?></td>
                                <td><?= $cliente['codigo_postal'] ?></td>
                                <td><?= $cliente['pais'] ?></td>
                                <td><?= $cliente['telefono'] ?></td>
                                <td><?= $cliente['fecha_registro'] ?></td>
                                <td>
                                    <form method="POST" style="display: inline-block; margin-left: 5px;">
                                        <input type="hidden" name="id" value="<?= $cliente['id'] ?>">
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            
            <?php elseif ($section === 'productos'): ?>
                <!-- Formulario para agregar/editar producto -->
                
                
                <!-- Tabla de productos -->
                <div class="table-container">
                    <h3 class="section-title">Listado de Productos</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($productos as $producto): ?>
                            <tr>
                                <td><?= $producto['id'] ?></td>
                                <td><?= htmlspecialchars($producto['nombre']) ?></td>
                                <td><?= number_format($producto['precio'], 2) ?> €</td>
                                <td>
                                    <form method="POST" style="display: inline-block; margin-left: 5px;">
                                        <input type="hidden" name="id" value="<?= $producto['id'] ?>">
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Scripts -->
    <?php if ($section === 'dashboard'): ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales/es.min.js"></script>
    
    <script>
        // Orders and Revenue Chart
        const ordersCtx = document.getElementById('ordersChart').getContext('2d');
        const ordersChart = new Chart(ordersCtx, {
            type: 'bar',
            data: {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                datasets: [
                    {
                        label: 'Pedidos',
                        data: [12, 19, 15, 21, 18, 25, 22, 30, 28, 26, 32, 40],
                        backgroundColor: 'rgba(67, 97, 238, 0.7)',
                        borderColor: 'rgba(67, 97, 238, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Ingresos (€)',
                        data: [1200, 1900, 1500, 2100, 1800, 2500, 2200, 3000, 2800, 2600, 3200, 4000],
                        backgroundColor: 'rgba(76, 201, 240, 0.7)',
                        borderColor: 'rgba(76, 201, 240, 1)',
                        borderWidth: 1,
                        type: 'line',
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.datasetIndex === 1) {
                                    label += context.parsed.y.toFixed(2) + ' €';
                                } else {
                                    label += context.parsed.y;
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Número de Pedidos'
                        }
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false,
                        },
                        title: {
                            display: true,
                            text: 'Ingresos (€)'
                        }
                    }
                }
            }
        });
        
        // Calendar
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: <?php echo json_encode($eventos_calendario); ?>,
                eventMouseEnter: function(info) {
                    info.el.style.cursor = 'pointer';
                },
                eventClick: function(info) {
                    alert('Pedido: ' + info.event.title + '\nEstado: ' + info.event.extendedProps.description);
                }
            });
            calendar.render();
        });
    </script>
    <?php endif; ?>
</body>
</html>