<?php
session_start();

// Conexión a la base de datos
$servername = "localhost";
$username = "u807590240_ivan";
$password = "Pozuelo202020";
$dbname = "u807590240_bbdd";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Verificar si hay un usuario logueado
    if (!isset($_SESSION['email'])) {
        header("Location: login.php?redirect=finalizar-compra");
        exit();
    }
    
    // Procesar el formulario de compra
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validar datos del formulario
        $required_fields = ['direccion', 'ciudad', 'codigo-postal', 'pais', 'metodo-envio', 'metodoPago'];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                die("Error: El campo $field es requerido.");
            }
        }
        
        // Obtener datos del carrito
        $carrito = json_decode($_POST['carrito'], true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($carrito)) {
            die("Error: Carrito no válido.");
        }
        
        if (count($carrito) === 0) {
            die("Error: El carrito está vacío.");
        }
        
        // Calcular total
        $subtotal = array_reduce($carrito, function($total, $item) {
            return $total + $item['precio'];
        }, 0);
        
        $costo_envio = floatval($_POST['costo-envio']);
        $total = floatval($_POST['total-pedido']);
        
        // Verificar que los cálculos coincidan
        if (abs(($subtotal + $costo_envio) - $total) > 0.01) {
            die("Error: Los totales no coinciden.");
        }
        
        // 1. Registrar/actualizar cliente
        $email = $_SESSION['email'];
        $stmt = $conn->prepare("SELECT id FROM clientes WHERE email = ?");
        $stmt->execute([$email]);
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Obtener los datos del formulario
        $nombre = $_POST['nombre'] ?? '';
        $apellidos = $_POST['apellidos'] ?? '';
        $direccion = $_POST['direccion'] ?? '';
        $ciudad = $_POST['ciudad'] ?? '';
        $codigo_postal = $_POST['codigo-postal'] ?? '';
        $pais = $_POST['pais'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $email = $_SESSION['email'] ?? '';

        // Verificar si ya existe el cliente
        $stmt = $conn->prepare("SELECT id FROM clientes WHERE email = ?");
        $stmt->execute([$email]);
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$cliente) {
            // Crear nuevo cliente con todos los campos
            $stmt = $conn->prepare("INSERT INTO clientes (nombre, apellidos, direccion, ciudad, codigo_postal, pais, telefono, email)
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nombre, $apellidos, $direccion, $ciudad, $codigo_postal, $pais, $telefono, $email]);
            $cliente_id = $conn->lastInsertId();
        } else {
            $cliente_id = $cliente['id'];
            
            // Actualizar datos del cliente por si han cambiado
            $stmt = $conn->prepare("UPDATE clientes SET nombre = ?, apellidos = ?, direccion = ?, ciudad = ?, codigo_postal = ?, pais = ?, telefono = ?
                                    WHERE id = ?");
            $stmt->execute([$nombre, $apellidos, $direccion, $ciudad, $codigo_postal, $pais, $telefono, $cliente_id]);
        }
        
        // 2. Registrar el pedido
        $stmt = $conn->prepare("INSERT INTO pedidos (
            cliente_id, total, metodo_pago, metodo_envio, costo_envio,
            direccion_envio, ciudad_envio, codigo_postal_envio, pais_envio
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $cliente_id,
            $total,
            $_POST['metodoPago'],
            $_POST['metodo-envio'],
            $costo_envio,
            $_POST['direccion'],
            $_POST['ciudad'],
            $_POST['codigo-postal'],
            $_POST['pais']
        ]);
        
        $pedido_id = $conn->lastInsertId();
        
        // 3. Registrar los detalles del pedido
        foreach ($carrito as $item) {
            $stmt = $conn->prepare("INSERT INTO detalles_pedido (
                pedido_id, producto_id, cantidad, precio_unitario, talla
            ) VALUES (?, ?, ?, ?, ?)");
            
            $stmt->execute([
                $pedido_id,
                $item['id'],
                1, // cantidad
                $item['precio'],
                $item['talla']
            ]);
        }
        
        // 4. Enviar correo electrónico con el resumen del pedido
        enviarCorreoConfirmacion($email, $pedido_id, $carrito, $total, $_POST);
        
        // Limpiar el carrito
        echo "<script>localStorage.removeItem('carrito');</script>";
        
        // Mostrar confirmación
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Compra Realizada | NEWLABEL</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
            <style>
                :root {
                    --primary-color: #6c63ff;
                    --secondary-color: #4d44db;
                    --accent-color: #ff6584;
                    --light-color: #f8f9fa;
                    --dark-color: #212529;
                }
                
                body {
                    background-color: #f5f7ff;
                    font-family: 'Poppins', sans-serif;
                    color: var(--dark-color);
                }
                
                .confirmation-container {
                    max-width: 800px;
                    margin: 50px auto;
                    padding: 40px;
                    background: white;
                    border-radius: 16px;
                    box-shadow: 0 10px 30px rgba(108, 99, 255, 0.1);
                    transform: translateY(20px);
                    opacity: 0;
                    animation: fadeInUp 0.8s ease-out forwards;
                }
                
                @keyframes fadeInUp {
                    to {
                        transform: translateY(0);
                        opacity: 1;
                    }
                }
                
                .confirmation-icon {
                    width: 100px;
                    height: 100px;
                    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto 30px;
                    animation: bounceIn 0.8s both;
                }
                
                .confirmation-icon i {
                    font-size: 50px;
                    color: white;
                }
                
                .order-details {
                    background: linear-gradient(to right, #f9f9ff, #f0f2ff);
                    border-radius: 12px;
                    padding: 25px;
                    margin-top: 30px;
                    border-left: 4px solid var(--primary-color);
                    transition: all 0.3s ease;
                }
                
                .order-details:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 5px 15px rgba(108, 99, 255, 0.1);
                }
                
                h1 {
                    color: var(--primary-color);
                    font-weight: 700;
                    margin-bottom: 20px;
                }
                
                .lead {
                    font-size: 1.1rem;
                    color: #555;
                }
                
                .btn-primary {
                    background-color: var(--primary-color);
                    border-color: var(--primary-color);
                    padding: 10px 25px;
                    border-radius: 50px;
                    font-weight: 500;
                    transition: all 0.3s ease;
                }
                
                .btn-primary:hover {
                    background-color: var(--secondary-color);
                    border-color: var(--secondary-color);
                    transform: translateY(-2px);
                    box-shadow: 0 5px 15px rgba(108, 99, 255, 0.3);
                }
                
                .btn-outline-secondary {
                    padding: 10px 25px;
                    border-radius: 50px;
                    font-weight: 500;
                    transition: all 0.3s ease;
                }
                
                .btn-outline-secondary:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                }
                
                .pulse {
                    animation: pulse 2s infinite;
                }
                
                @keyframes pulse {
                    0% {
                        box-shadow: 0 0 0 0 rgba(108, 99, 255, 0.4);
                    }
                    70% {
                        box-shadow: 0 0 0 15px rgba(108, 99, 255, 0);
                    }
                    100% {
                        box-shadow: 0 0 0 0 rgba(108, 99, 255, 0);
                    }
                }
                
                .order-number {
                    font-size: 1.2rem;
                    font-weight: 600;
                    color: var(--primary-color);
                    background: rgba(108, 99, 255, 0.1);
                    padding: 5px 15px;
                    border-radius: 50px;
                    display: inline-block;
                }
                
                .product-list {
                    list-style: none;
                    padding: 0;
                }
                
                .product-list li {
                    padding: 10px 0;
                    border-bottom: 1px solid #eee;
                    display: flex;
                    justify-content: space-between;
                }
                
                .product-list li:last-child {
                    border-bottom: none;
                }
                
                .total-amount {
                    font-size: 1.3rem;
                    font-weight: 700;
                    color: var(--primary-color);
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="confirmation-container text-center">
                    <div class="confirmation-icon pulse">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    <h1 class="mb-3 animate__animated animate__fadeIn">¡Compra realizada con éxito!</h1>
                    <p class="lead animate__animated animate__fadeIn animate__delay-1s">Gracias por tu compra en NEWLABEL. Tu pedido ha sido procesado correctamente.</p>
                    <p class="animate__animated animate__fadeIn animate__delay-1s">Hemos enviado un correo electrónico con los detalles de tu compra a <strong><?= htmlspecialchars($email) ?></strong>.</p>
                    
                    <div class="order-details text-start animate__animated animate__fadeIn animate__delay-2s">
                        <h4 class="mb-4"><i class="bi bi-receipt"></i> Detalles del pedido:</h4>
                        <p><strong>Número de pedido:</strong> <span class="order-number">#<?= $pedido_id ?></span></p>
                        <p><strong>Fecha:</strong> <?= date('d/m/Y H:i') ?></p>
                        
                        <ul class="product-list mt-3">
                            <?php foreach ($carrito as $item): ?>
                                <li>
                                    <span><?= htmlspecialchars($item['nombre']) ?> (<?= htmlspecialchars($item['talla']) ?>)</span>
                                    <span><?= number_format($item['precio'], 2) ?>€</span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        
                        <div class="mt-4">
                            <p><strong>Subtotal:</strong> <?= number_format($subtotal, 2) ?>€</p>
                            <p><strong>Envío:</strong> <?= number_format($costo_envio, 2) ?>€</p>
                            <p class="total-amount">Total: <?= number_format($total, 2) ?>€</p>
                        </div>
                        
                        <hr>
                        
                        <p><strong>Método de pago:</strong> <?= htmlspecialchars(ucfirst($_POST['metodoPago'])) ?></p>
                        <p><strong>Dirección de envío:</strong> 
                            <?= htmlspecialchars($_POST['direccion']) ?>, 
                            <?= htmlspecialchars($_POST['codigo-postal']) ?> 
                            <?= htmlspecialchars($_POST['ciudad']) ?>, 
                            <?= htmlspecialchars($_POST['pais'] === 'es' ? 'España' : 'Internacional') ?>
                        </p>
                    </div>
                    
                    <div class="mt-4 animate__animated animate__fadeIn animate__delay-3s">
                        <a href="scuffers.php" class="btn btn-primary me-2"><i class="bi bi-arrow-left"></i> Volver a la tienda</a>
                    </div>
                    
                    <div class="mt-4 animate__animated animate__fadeIn animate__delay-4s">
                        <small class="text-muted">¿Necesitas ayuda? <a href="contacto.html">Contáctanos</a></small>
                    </div>
                </div>
            </div>
            
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
            <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
        </body>
        </html>
        <?php
        
    } else {
        // Si alguien intenta acceder directamente a esta página sin enviar el formulario
        header("Location: finalizar-compra.php");
        exit();
    }
    
} catch(PDOException $e) {
    // Registrar el error y mostrar mensaje amigable
    error_log("Error en compraRealizada.php: " . $e->getMessage());
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Error | NEWLABEL</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <style>
            body {
                background-color: #f5f7ff;
                font-family: 'Poppins', sans-serif;
            }
            .error-container {
                max-width: 600px;
                margin: 100px auto;
                padding: 30px;
                background: white;
                border-radius: 16px;
                box-shadow: 0 10px 30px rgba(255, 101, 132, 0.1);
                text-align: center;
            }
            .error-icon {
                font-size: 5rem;
                color: #ff6584;
                margin-bottom: 20px;
                animation: shake 0.5s;
            }
            @keyframes shake {
                0%, 100% {transform: translateX(0);}
                10%, 30%, 50%, 70%, 90% {transform: translateX(-5px);}
                20%, 40%, 60%, 80% {transform: translateX(5px);}
            }
            h1 {
                color: #ff6584;
            }
            .btn-primary {
                background-color: #6c63ff;
                border-color: #6c63ff;
                padding: 10px 25px;
                border-radius: 50px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="error-container">
                <div class="error-icon">✕</div>
                <h1 class="mb-3">¡Oops! Algo salió mal</h1>
                <p class="lead">Lo sentimos, ha ocurrido un error al procesar tu compra. Por favor, intenta nuevamente.</p>
                <p>Si el problema persiste, contáctanos para ayudarte.</p>
                <div class="mt-4">
                    <a href="finalizar-compra.php" class="btn btn-primary">Volver a intentar</a>
                    <a href="contacto.html" class="btn btn-outline-secondary ms-2">Contactar soporte</a>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
}

// Función para enviar el correo de confirmación
function enviarCorreoConfirmacion($email, $pedido_id, $carrito, $total, $datos_envio) {
    // Configurar cabeceras para HTML y UTF-8
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: NEWLABEL <info@newlabel.es>\r\n";
    $headers .= "Reply-To: info@newlabel.es\r\n";
    
    // Asunto del correo
    $subject = "✅ Confirmación de tu pedido #$pedido_id en NEWLABEL";
    
    // Construir el cuerpo del correo
    $message = '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Confirmación de pedido</title>
        <style>
            body {
                font-family: "Poppins", Arial, sans-serif;
                line-height: 1.6;
                color: #333;
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
                background-color: #f9f9f9;
            }
            .header {
                text-align: center;
                margin-bottom: 30px;
            }
            .header img {
                max-width: 180px;
                margin-bottom: 20px;
            }
            .content {
                background-color: #fff;
                padding: 30px;
                border-radius: 12px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            }
            h1 {
                color: #6c63ff;
                margin-top: 0;
                font-weight: 700;
            }
            .success-icon {
                display: inline-block;
                width: 50px;
                height: 50px;
                background: linear-gradient(135deg, #6c63ff, #4d44db);
                color: white;
                border-radius: 50%;
                text-align: center;
                line-height: 50px;
                margin-right: 15px;
                vertical-align: middle;
            }
            .order-summary {
                margin: 25px 0;
                border-top: 1px solid #eee;
                border-bottom: 1px solid #eee;
                padding: 25px 0;
            }
            .product {
                display: flex;
                margin-bottom: 15px;
                padding-bottom: 15px;
                border-bottom: 1px solid #f0f0f0;
                align-items: center;
            }
            .product:last-child {
                border-bottom: none;
            }
            .product-image {
                width: 70px;
                height: 70px;
                object-fit: cover;
                margin-right: 15px;
                border-radius: 8px;
                border: 1px solid #eee;
            }
            .product-info {
                flex: 1;
            }
            .product-name {
                font-weight: 600;
                margin-bottom: 5px;
                color: #333;
            }
            .product-price {
                color: #6c63ff;
                font-weight: 500;
            }
            .total {
                font-size: 20px;
                font-weight: 700;
                text-align: right;
                margin-top: 20px;
                color: #6c63ff;
            }
            .shipping-info {
                background-color: #f8f9fa;
                padding: 20px;
                border-radius: 8px;
                margin-top: 25px;
                border-left: 4px solid #6c63ff;
            }
            .footer {
                text-align: center;
                margin-top: 30px;
                color: #777;
                font-size: 14px;
            }
            .badge {
                display: inline-block;
                padding: 5px 12px;
                background: rgba(108, 99, 255, 0.1);
                color: #6c63ff;
                border-radius: 50px;
                font-weight: 600;
                font-size: 0.9em;
            }
            .divider {
                height: 1px;
                background: linear-gradient(to right, transparent, #ddd, transparent);
                margin: 20px 0;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <img src="https://newlabel.es/img/logo-newlabel-removebg-preview.png" alt="NEWLABEL">
        </div>
        
        <div class="content">
            <h1><span class="success-icon">✓</span> ¡Gracias por tu compra!</h1>
            <p>Hemos recibido tu pedido y estamos preparándolo para el envío. A continuación encontrarás los detalles de tu compra.</p>
            
            <div class="order-summary">
                <h2>Resumen del pedido <span class="badge">#'.$pedido_id.'</span></h2>
                <p><strong>Fecha:</strong> '.date('d/m/Y H:i').'</p>
                
                <div class="divider"></div>
                
                <h3>Productos:</h3>';
                
                foreach ($carrito as $item) {
                    $message .= '
                    <div class="product">
                        <div class="product-info">
                            <div class="product-name">'.$item['nombre'].'</div>
                            <div class="product-details">Talla: '.$item['talla'].'</div>
                            <div class="product-price">'.number_format($item['precio'], 2).'€</div>
                        </div>
                    </div>';
                }
                
                $message .= '
                <div class="divider"></div>
                
                <div>
                    <p style="text-align: right;"><strong>Subtotal:</strong> '.number_format(array_reduce($carrito, function($t, $i) { return $t + $i['precio']; }, 0), 2).'€</p>
                    <p style="text-align: right;"><strong>Envío:</strong> '.number_format($datos_envio['costo-envio'], 2).'€</p>
                </div>
                
                <div class="total">
                    Total: '.number_format($total, 2).'€
                </div>
            </div>
            
            <div class="shipping-info">
                <h3>📦 Información de envío</h3>
                <p><strong>Método de envío:</strong> '.htmlspecialchars(ucfirst($datos_envio['metodo-envio'])).'</p>
                <p><strong>Dirección:</strong><br>
                '.htmlspecialchars($datos_envio['direccion']).'<br>
                '.htmlspecialchars($datos_envio['codigo-postal']).' '.htmlspecialchars($datos_envio['ciudad']).'<br>
                '.htmlspecialchars($datos_envio['pais'] === 'es' ? 'España' : 'Internacional').'
                </p>
                <p><strong>💳 Método de pago:</strong> '.htmlspecialchars(ucfirst($datos_envio['metodoPago'])).'</p>
            </div>
            
            <p>Si tienes alguna pregunta sobre tu pedido, no dudes en contactarnos respondiendo a este correo.</p>
            
            <p>¡Gracias por confiar en NEWLABEL!</p>
        </div>
        
        <div class="footer">
            <p>© '.date('Y').' NEWLABEL. Todos los derechos reservados.</p>
            <p><a href="https://newlabel.es" style="color: #6c63ff; text-decoration: none;">Visita nuestra web</a></p>
        </div>
    </body>
    </html>';
    
    // Enviar el correo
    mail($email, $subject, $message, $headers);
}
?>