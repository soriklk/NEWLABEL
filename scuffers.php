<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../scuffers.css?version=1.1">
    <title>NEWLABEL | Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <?php session_start(); ?>
    <script>
     document.addEventListener('DOMContentLoaded', function() {
        let email = "<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : 'Usuario'; ?>";
        Swal.fire({
            title: '¡Bienvenido, ' + email + '!',
            text: '¡NEWLABEL - DRIP Dont Lie!',
            icon: 'success',
            confirmButtonText: 'Aceptar'
        });
     });
    </script>
</head>
<style>



/* Estilos para el footer moderno */
    .footer-moderno {
        background-color: #000;
        color: #fff;
        padding: 50px 0 0;
        font-family: 'Arial', sans-serif;
        border-top: 1px solid #333;
    }

    .footer-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .footer-section {
        flex: 1;
        min-width: 200px;
        margin-bottom: 30px;
        padding: 0 15px;
    }

    .footer-section h4 {
        color: #fff;
        font-size: 18px;
        margin-bottom: 20px;
        position: relative;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .footer-section h4::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -8px;
        width: 40px;
        height: 2px;
        background-color: #fff;
    }

    .footer-section ul {
        list-style: none;
        padding: 0;
    }

    .footer-section ul li {
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .footer-section ul li i {
        font-size: 14px;
        color: #aaa;
    }

    .footer-section ul li a {
        color: #ccc;
        text-decoration: none;
        transition: color 0.3s;
    }

    .footer-section ul li a:hover {
        color: #fff;
    }

    .slogan {
        color: #aaa;
        font-style: italic;
        margin-bottom: 20px;
    }

    .social-links {
        display: flex;
        gap: 15px;
        margin-top: 20px;
    }

    .social-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        background-color: #222;
        border-radius: 50%;
        transition: all 0.3s;
    }

    .social-icon:hover {
        background-color: #333;
        transform: translateY(-3px);
    }

    .social-icon img {
        width: 18px;
        height: 18px;
        filter: brightness(0) invert(1);
    }

    .footer-bottom {
        background-color: #111;
        padding: 30px 0 20px;
        text-align: center;
        border-top: 1px solid #333;
    }

    .payment-section {
        margin-bottom: 20px;
    }

    .payment-section h5 {
        color: #aaa;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 15px;
    }

    .payment-methods {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 15px;
        max-width: 600px;
        margin: 0 auto;
    }

    .payment-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        background: #222;
        border-radius: 8px;
        padding: 10px 15px;
        min-width: 80px;
        transition: all 0.3s;
    }

    .payment-card:hover {
        background: #333;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(255, 255, 255, 0.1);
    }

    .payment-card img {
        width: 30px;
        height: 30px;
        object-fit: contain;
        margin-bottom: 5px;
        filter: grayscale(100%) brightness(1.5);
        transition: filter 0.3s;
    }

    .payment-card:hover img {
        filter: grayscale(0%) brightness(1);
    }

    .payment-card span {
        font-size: 10px;
        color: #aaa;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .copyright {
        margin: 20px 0 0;
        color: #666;
        font-size: 12px;
    }

    @media (max-width: 768px) {
        .footer-container {
            flex-direction: column;
        }
        
        .footer-section {
            margin-bottom: 30px;
            text-align: center;
        }

        .footer-section h4::after {
            left: 50%;
            transform: translateX(-50%);
        }

        .social-links {
            justify-content: center;
        }

        .payment-methods {
            gap: 10px;
        }

        .payment-card {
            padding: 8px 10px;
            min-width: 70px;
        }
    }
    .logo-newlabel{
        width: 120px;
    }

    .articulo {
        position: relative;
        overflow: hidden;
    }

    .articulo  {
        transition: transform 0.3s ease;
    }

    .articulo:hover {
        transform: scale(1.05);
    }
</style>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <!-- LOGO IZQUIERDA -->
                <a class="navbar-brand" href="#">
                    <img src="../img/logo-newlabel-removebg-preview.png" alt="logo-newlabel-removebg-preview" class="logo-newlabel">
                </a>
        
                <!-- BOTON MOVILES -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
        
                <!-- MENU NAVEGACION -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="quienesSomos.html">Quienes Somos</a></li>
                        <li class="nav-item"><a class="nav-link" href="dondeEstamos.html">Donde Estamos</a></li>
                        <li class="nav-item"><a class="nav-link" href="#contacto">Contáctanos</a></li>
                    </ul>
                </div>

                <div class="usuario-menu">
                    <img src="../img/perfil.png" alt="Usuario" id="iconoUsuario" style="cursor:pointer;">
                    <span> <?php echo isset($_SESSION["email"]) ? $_SESSION["email"] : "No hay usuario"; ?> </span>
                    
                    <div class="menu-usuario" id="menuUsuario" style="display: none;">
                        <button id="btnConfiguracion" href="configuracion.php">Configuración</button>
                        <button id="btnCerrarSesion" style="color: white;" href="index.html">Cerrar Sesión</button>
                    </div>

                    <!-- Ícono del carrito -->
                    <div class="carrito" id="iconoCarrito">
                        <img src="../img/carrito.png" alt="Carrito" height="30" width="30">
                    </div>

                    <div class="carrito-menu" id="menuCarrito">
                        <h2>Carrito de Compras</h2>
                        <ul id="listaCarrito">
                        <!-- Aquí se mostrarán los productos -->
                        </ul>
                        <p id="totalCarrito">Total: 0€</p>
                        <button id="cerrarCarrito">Cerrar</button>
                        <button id="finalizarCompra">Finalizar Compra</button>
                    </div>
                </div>
            </div>
        </nav>
                                                  
        <div class="seleccion">
            <a href="#pantalones">PANTALONES</a>
            <a href="#sudaderas">SUDADERAS</a>
            <a href="#camisetas">CAMISETAS</a>
            <a href="#interior">INTERIOR</a>
        </div>
    </header>
    
    <div class="fondo" id="inicio">
        <video src="../img/video-landing.mp4" autoplay="" loop="" muted="" alt="Video de fondo en bucle de un rapero">
        </video>
        <div class="contentFondo">
            <h1>BEST SELLERS-</h1>
            <h1>SHOP NOW</h1>
            <p>NEWLABEL – Drip don't lie.</p>
            <button><a href="#sudaderas">BUY NOW</a></button>
        </div>
    </div>

    <?php
    // Conexión a la base de datos
    $servername = "localhost";
    $username = "u807590240_ivan";
    $password = "Pozuelo202020";
    $dbname = "u807590240_bbdd";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Función para mostrar productos por categoría
        function mostrarProductos($conn, $categoria, $sectionId) {
            $stmt = $conn->prepare("SELECT * FROM productos WHERE categoria = :categoria");
            $stmt->bindParam(':categoria', $categoria);
            $stmt->execute();
            
            $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($productos) > 0) {
                echo '<div class="prendas" id="'.$sectionId.'">';
                foreach ($productos as $producto) {
                    echo '<div class="articulo" data-id="'.$producto['id'].'">';
                    echo '<img src="../img/'.$producto['imagen'].'" alt="'.$producto['nombre'].'">';
                    echo '<img src="../img/icono-añadir.png" alt="icono-añadir" class="add-to-cart">';
                    echo '<div class="texto">';
                    echo '<p>'.$producto['nombre'].'</p>';
                    echo '<p>'.$producto['precio'].'€</p>';
                    echo '</div>';
                    
                    echo '<div class="tallas">';
                    $tallas = explode(',', $producto['tallas']);
                    foreach ($tallas as $talla) {
                        echo '<button data-talla="'.trim($talla).'">'.trim($talla).'</button>';
                    }
                    echo '</div>';
                    
                    echo '</div>';
                }
                echo '</div>';
                
                // Mostrar video banner después de cada sección excepto la última
                if ($sectionId !== 'interior') {
                    $video = ($sectionId === 'sudaderas') ? 'fumando.mp4' : (($sectionId === 'pantalones') ? 'chica.mp4' : 'playa.mp4');
                    echo '<div class="video-banner">';
                    echo '<video src="../img/'.$video.'" autoplay loop muted></video>';
                    echo '</div>';
                }
            }
        }
        
        // Mostrar cada categoría de productos
        mostrarProductos($conn, 'sudadera', 'sudaderas');
        mostrarProductos($conn, 'pantalon', 'pantalones');
        mostrarProductos($conn, 'camiseta', 'camisetas');
        mostrarProductos($conn, 'interior', 'interior');
        
    } catch(PDOException $e) {
        echo "<div class='alert alert-danger'>Error al conectar con la base de datos: " . $e->getMessage() . "</div>";
    }
    $conn = null;
    ?>

<footer id="contacto" class="footer-moderno">
    <div class="footer-container">
        <!-- Sección de enlaces rápidos -->
        <div class="footer-section">
            <h4>NEWLABEL</h4>
            <p class="slogan">Drip don't lie.</p>
            <div class="social-links">
                <a href="https://www.instagram.com/ivan.soriia" target="_blank" class="social-icon">
                    <img src="../img/instagram.ico" alt="Instagram">
                </a>
                <a href="https://www.tiktok.com/@sooriiiaa" target="_blank" class="social-icon">
                    <img src="../img/tik-tok.png" alt="TikTok">
                </a>
            </div>
        </div>

        <!-- Sección de ayuda -->
        <div class="footer-section">
            <h4>AYUDA</h4>
            <ul>
                <li><a href="faqs.html">FAQS</a></li>
                <li><a href="contacto.html">CONTACTO</a></li>
                <li><a href="cambios-devoluciones.html">CAMBIOS Y DEVOLUCIONES</a></li>
                <li><a href="politica-envio.html">POLÍTICA DE ENVÍO</a></li>
            </ul>
        </div>

        <!-- Sección legal -->
        <div class="footer-section">
            <h4>LEGAL</h4>
            <ul>
                <li><a href="terminos-condiciones.html">TÉRMINOS Y CONDICIONES</a></li>
                <li><a href="politica-privacidad.html">POLÍTICA DE PRIVACIDAD</a></li>
                <li><a href="trabaja-con-nosotros.php">TRABAJA CON NOSOTROS</a></li>
                <li><a href="aviso-legal.html">AVISO LEGAL</a></li>
            </ul>
        </div>

        <!-- Sección de contacto -->
        <div class="footer-section">
            <h4>CONTÁCTANOS</h4>
            <ul>
                <li><i class="bi bi-envelope"></i> info@newlabel.com</li>
                <li><i class="bi bi-telephone"></i> +34 123 456 789</li>
                <li><i class="bi bi-clock"></i> L-V: 8:00 - 17:00</li>
            </ul>
        </div>
    </div>
</footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="index.js"></script> 
</body>
</html>