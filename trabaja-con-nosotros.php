<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trabaja con Nosotros - NEWLABEL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #000;
            --secondary-color: #fff;
            --accent-color: #f8f9fa;
        }
        
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--accent-color);
            color: #333;
            line-height: 1.6;
        }
        
        .navbar {
            background-color: var(--secondary-color) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .legal-header {
            background-color: var(--primary-color);
            color: var(--secondary-color);
            padding: 80px 0;
            text-align: center;
            margin-bottom: 50px;
        }
        
        .legal-container {
            max-width: 800px;
            margin: 0 auto 80px;
            padding: 0 20px;
        }
        
        .legal-section {
            margin-bottom: 40px;
        }
        
        .legal-section h2 {
            font-size: 1.8rem;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }
        
        .legal-section h2::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 2px;
            background-color: var(--primary-color);
        }
        
        .legal-section h3 {
            font-size: 1.3rem;
            margin: 25px 0 15px;
            color: #555;
        }
        
        .note-box {
            background-color: #f1f1f1;
            border-left: 4px solid var(--primary-color);
            padding: 15px;
            margin: 20px 0;
        }
        
        .job-positions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }
        
        .position-card {
            background-color: var(--secondary-color);
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }
        
        .position-card:hover {
            transform: translateY(-5px);
        }
        
        .position-card h3 {
            font-size: 1.3rem;
            margin-bottom: 15px;
            color: var(--primary-color);
        }
        
        .position-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            color: #666;
            font-size: 0.9rem;
        }
        
        .btn-apply {
            background-color: var(--primary-color);
            color: var(--secondary-color);
            border: none;
            padding: 8px 20px;
            font-weight: 500;
            transition: all 0.3s;
            display: inline-block;
            margin-top: 15px;
        }
        
        .btn-apply:hover {
            background-color: #333;
            color: var(--secondary-color);
        }
        
        .form-section {
            background-color: var(--secondary-color);
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-top: 40px;
        }
        
        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
        }
        
        .form-control {
            border-radius: 0;
            border: 1px solid #ddd;
            padding: 12px 15px;
            margin-bottom: 20px;
        }
        
        .form-control:focus {
            box-shadow: none;
            border-color: var(--primary-color);
        }
        
        .btn-submit {
            background-color: var(--primary-color);
            color: var(--secondary-color);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
        }
        
        .btn-submit:hover {
            background-color: #333;
        }
        
        .alert {
            border-radius: 0;
            margin-bottom: 20px;
        }

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
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <h3>NEWLABEL</h3>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="../index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="quienesSomos.html">Quienes Somos</a></li>
                    <li class="nav-item"><a class="nav-link" href="dondeEstamos.html">Donde Estamos</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contacto">Contáctanos</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="legal-header">
        <h1>TRABAJA CON NOSOTROS</h1>
        <p>Únete al equipo de NEWLABEL</p>
    </div>

    <div class="legal-container">
        <div class="legal-section">
            <h2>Nuestra Cultura</h2>
            <p>En NEWLABEL no solo creamos moda urbana, creamos un movimiento. Nuestro equipo está formado por apasionados de la moda, la creatividad y la innovación que comparten una visión común: redefinir el streetwear con autenticidad y estilo.</p>
        </div>
        
        <div class="legal-section">
            <h2>Oportunidades Actuales</h2>
            <div class="job-positions">
                <div class="position-card">
                    <h3>Diseñador/a de Moda</h3>
                    <div class="position-meta">
                        <span><i class="bi bi-geo-alt"></i> Madrid</span>
                        <span><i class="bi bi-briefcase"></i> Tiempo completo</span>
                    </div>
                    <p>Buscamos un/a diseñador/a creativo/a con experiencia en streetwear para desarrollar nuestras colecciones.</p>
                    <a href="#apply-form" class="btn btn-apply">Postularme</a>
                </div>
                
                <div class="position-card">
                    <h3>Especialista en Marketing Digital</h3>
                    <div class="position-meta">
                        <span><i class="bi bi-geo-alt"></i> Remoto</span>
                        <span><i class="bi bi-briefcase"></i> Tiempo completo</span>
                    </div>
                    <p>Únete a nuestro equipo de marketing para desarrollar estrategias digitales innovadoras.</p>
                    <a href="#apply-form" class="btn btn-apply">Postularme</a>
                </div>
            </div>
        </div>
        
        <div class="form-section" id="apply-form">
            <h2 class="section-title">Envía tu Solicitud</h2>
            
            <!-- Mensajes de estado -->
            <?php if(isset($_GET['status'])): ?>
                <div class="alert <?php echo ($_GET['status'] == 'success') ? 'alert-success' : 'alert-danger'; ?>">
                    <?php 
                        if($_GET['status'] == 'success') {
                            echo '¡Gracias por tu solicitud! Hemos recibido tu candidatura correctamente.';
                        } else {
                            $message = $_GET['message'] ?? '';
                            switch($message) {
                                case 'missing_fields':
                                    echo 'Error: Faltan campos requeridos.';
                                    break;
                                case 'invalid_email':
                                    echo 'Error: Email no válido.';
                                    break;
                                case 'upload_error':
                                    echo 'Error: Problema al subir el archivo.';
                                    break;
                                case 'invalid_file_type':
                                    echo 'Error: Tipo de archivo no permitido (solo PDF, DOC o DOCX).';
                                    break;
                                case 'file_too_large':
                                    echo 'Error: El archivo excede el tamaño máximo de 5MB.';
                                    break;
                                default:
                                    echo 'Hubo un error al enviar tu solicitud. Por favor, inténtalo de nuevo más tarde.';
                            }
                        }
                    ?>
                </div>
            <?php endif; ?>
            
            <form action="procesar-candidatura.php" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Nombre completo*</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Email*</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Teléfono*</label>
                        <input type="tel" name="telefono" class="form-control" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Posición a la que aplicas*</label>
                        <select name="posicion" class="form-control" required>
                            <option value="" disabled selected>Selecciona una opción</option>
                            <option>Diseñador/a de Moda</option>
                            <option>Especialista en Marketing Digital</option>
                            <option>Asistente de Ventas</option>
                            <option>Otra posición</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">CV (PDF, DOC o DOCX)*</label>
                    <input type="file" name="cv" class="form-control" accept=".pdf,.doc,.docx" required>
                    <small class="text-muted">Tamaño máximo: 5MB</small>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Portfolio o enlace a LinkedIn (opcional)</label>
                    <input type="url" name="portfolio" class="form-control" placeholder="https://">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Carta de presentación (opcional)</label>
                    <textarea name="carta" class="form-control" rows="5" placeholder="Explícanos por qué quieres unirte a nuestro equipo..."></textarea>
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="privacidad" required>
                    <label class="form-check-label" for="privacidad">Acepto la <a href="politica-privacidad.html">Política de Privacidad</a>*</label>
                </div>
                
                <button type="submit" class="btn btn-submit">
                    <i class="bi bi-send"></i> Enviar Solicitud
                </button>
            </form>
        </div>
    </div>

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
</body>
</html>