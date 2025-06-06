<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEWLABEL | Finalizar Compra</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        :root {
            --primary-color: black;
            --secondary-color: #4d44db;
            --accent-color: #ff6584;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --light-gray: #e9ecef;
            --medium-gray: #adb5bd;
            --dark-gray: #495057;
            --border-radius: 8px;
            --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            --transition: all 0.3s ease;
        }
        
        body {
            background-color: #ffffff;
            font-family: 'Helvetica Neue', Arial, sans-serif;
            color: var(--dark-color);
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .checkout-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .checkout-header h1 {
            font-weight: 700;
            font-size: 28px;
            margin-bottom: 10px;
            color: var(--primary-color);
        }
        
        .checkout-steps {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }
        
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0 15px;
            position: relative;
        }
        
        .step-number {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: var(--light-gray);
            color: var(--medium-gray);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-bottom: 8px;
            transition: var(--transition);
        }
        
        .step.active .step-number {
            background-color: var(--primary-color);
            color: white;
        }
        
        .step.completed .step-number {
            background-color: var(--accent-color);
            color: white;
        }
        
        .step-title {
            font-size: 14px;
            color: var(--medium-gray);
            font-weight: 500;
        }
        
        .step.active .step-title {
            color: var(--primary-color);
        }
        
        .step.completed .step-title {
            color: var(--accent-color);
        }
        
        .step:not(:last-child):after {
            content: '';
            position: absolute;
            top: 16px;
            left: 50px;
            width: 60px;
            height: 2px;
            background-color: var(--light-gray);
        }
        
        .checkout-card {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            margin-bottom: 30px;
            transition: var(--transition);
            border: 1px solid var(--light-gray);
        }
        
        .checkout-card:hover {
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }
        
        .section-header {
            font-size: 20px;
            font-weight: 600;
            padding: 20px 25px;
            border-bottom: 1px solid var(--light-gray);
            margin: 0;
            display: flex;
            align-items: center;
            color: var(--primary-color);
        }
        
        .section-header i {
            margin-right: 10px;
            color: var(--accent-color);
        }
        
        .form-section {
            padding: 25px;
        }
        
        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
            color: var(--dark-gray);
        }
        
        .form-control, .form-select {
            width: 100%;
            padding: 12px 15px;
            font-size: 15px;
            border: 1px solid var(--light-gray);
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            background-color: white;
            transition: var(--transition);
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.1);
        }
        
        .shipping-method {
            display: flex;
            align-items: center;
            padding: 18px;
            border: 1px solid var(--light-gray);
            border-radius: var(--border-radius);
            margin-bottom: 15px;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
        }
        
        .shipping-method:hover {
            border-color: var(--primary-color);
        }
        
        .shipping-method.selected {
            border-color: var(--primary-color);
            background-color: rgba(108, 99, 255, 0.03);
        }
        
        .shipping-method input[type="radio"] {
            position: absolute;
            opacity: 0;
        }
        
        .custom-radio {
            width: 20px;
            height: 20px;
            border: 2px solid var(--medium-gray);
            border-radius: 50%;
            margin-right: 15px;
            position: relative;
            flex-shrink: 0;
            transition: var(--transition);
        }
        
        .shipping-method.selected .custom-radio {
            border-color: var(--primary-color);
            background-color: var(--primary-color);
        }
        
        .shipping-method.selected .custom-radio:after {
            content: '';
            position: absolute;
            top: 3px;
            left: 3px;
            width: 10px;
            height: 10px;
            background-color: white;
            border-radius: 50%;
        }
        
        .shipping-details {
            flex-grow: 1;
        }
        
        .shipping-title {
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--dark-color);
        }
        
        .shipping-description {
            font-size: 14px;
            color: var(--medium-gray);
        }
        
        .shipping-price {
            font-weight: 600;
            margin-left: 15px;
            color: var(--primary-color);
        }
        
        .cart-item {
            display: flex;
            padding: 15px 0;
            border-bottom: 1px solid var(--light-gray);
            align-items: center;
        }
        
        .cart-item:last-child {
            border-bottom: none;
        }
        
        .cart-item-image {
            width: 80px;
            height: 80px;
            border-radius: var(--border-radius);
            margin-right: 20px;
            object-fit: cover;
            background-color: var(--light-gray);
        }
        
        .cart-item-details {
            flex-grow: 1;
        }
        
        .cart-item-name {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .cart-item-variant {
            font-size: 14px;
            color: var(--medium-gray);
            margin-bottom: 5px;
        }
        
        .cart-item-price {
            font-weight: 600;
        }
        
        .remove-item {
            background: none;
            border: none;
            color: var(--accent-color);
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
        }
        
        .remove-item i {
            margin-right: 5px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid var(--light-gray);
        }
        
        .summary-row:last-child {
            border-bottom: none;
            font-weight: 700;
            font-size: 18px;
            color: var(--primary-color);
        }
        
        .btn-primary {
            width: 100%;
            padding: 15px;
            background-color: var(--primary-color);
            border: none;
            border-radius: var(--border-radius);
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .hidden {
            display: none;
        }
        
        .payment-method {
            margin-bottom: 25px;
        }
        
        .payment-icon {
            width: 40px;
            height: 25px;
            object-fit: contain;
            margin-left: 10px;
        }
        
        .credit-card-icons {
            display: flex;
            margin-top: 10px;
        }
        
        .credit-card-icons img {
            width: 40px;
            margin-right: 10px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .container {
                margin: 20px auto;
                padding: 0 15px;
            }
            
            .checkout-steps {
                flex-wrap: wrap;
            }
            
            .step {
                margin: 0 5px 15px;
            }
            
            .step:not(:last-child):after {
                display: none;
            }
            
            .section-header {
                padding: 15px 20px;
                font-size: 18px;
            }
            
            .form-section {
                padding: 20px;
            }
            
            .cart-item-image {
                width: 60px;
                height: 60px;
                margin-right: 15px;
            }
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .checkout-card {
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        .checkout-card:nth-child(2) {
            animation-delay: 0.1s;
        }
        
        .checkout-card:nth-child(3) {
            animation-delay: 0.2s;
        }
        
        .checkout-card:nth-child(4) {
            animation-delay: 0.3s;
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Checkout Header -->
        <div class="checkout-header">
            <h1>Finalizar Compra</h1>
            <div class="checkout-steps">
                <div class="step completed">
                    <div class="step-number">1</div>
                    <div class="step-title">Carrito</div>
                </div>
                <div class="step active">
                    <div class="step-number">2</div>
                    <div class="step-title">Envío & Pago</div>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-title">Confirmación</div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- Resumen del Carrito -->
            <div class="col-lg-8">
                <div class="checkout-card">
                    <h2 class="section-header"><i class="fas fa-shopping-bag"></i>Tu Pedido</h2>
                    <div class="form-section" id="carrito-container"></div>
                </div>

                <!-- Formulario de Envío -->
                <div class="checkout-card">
                    <h2 class="section-header"><i class="fas fa-truck"></i>Información de Envío</h2>
                    <div class="form-section">
                        <form id="formulario-pago" action="compraRealizada.php" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" id="nombre" name="nombre" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="apellidos" class="form-label">Apellidos</label>
                                    <input type="text" id="apellidos" name="apellidos" class="form-control" required>
                                </div>
                            </div>
                            
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" id="direccion" name="direccion" class="form-control" required>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="ciudad" class="form-label">Ciudad</label>
                                    <input type="text" id="ciudad" name="ciudad" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="codigo-postal" class="form-label">Código Postal</label>
                                    <input type="text" id="codigo-postal" name="codigo-postal" class="form-control" required>
                                </div>
                            </div>
                            
                            <label for="pais" class="form-label">País</label>
                            <select id="pais" name="pais" class="form-select" required>
                                <option value="es">España</option>
                                <option value="int">Internacional</option>
                            </select>
                            
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" id="telefono" name="telefono" class="form-control" required>
                    </div>
                </div>

                <!-- Método de Envío -->
                <div class="checkout-card">
                    <h2 class="section-header"><i class="fas fa-shipping-fast"></i>Método de Envío</h2>
                    <div class="form-section" id="metodos-envio">
                        <div class="shipping-method" onclick="seleccionarEnvio(this, 'estandar')">
                            <input type="radio" name="metodo-envio" id="envio-estandar" value="estandar" required>
                            <div class="custom-radio"></div>
                            <div class="shipping-details">
                                <div class="shipping-title">Envío Estándar</div>
                                <div class="shipping-description">Entrega en 3-5 días laborables</div>
                            </div>
                            <div class="shipping-price" id="precio-estandar">4,95€</div>
                        </div>
                        
                        <div class="shipping-method" onclick="seleccionarEnvio(this, 'express')">
                            <input type="radio" name="metodo-envio" id="envio-express" value="express">
                            <div class="custom-radio"></div>
                            <div class="shipping-details">
                                <div class="shipping-title">Envío Express</div>
                                <div class="shipping-description">Entrega en 24-48 horas</div>
                            </div>
                            <div class="shipping-price" id="precio-express">9,95€</div>
                        </div>
                        
                        <div class="shipping-method hidden" id="envio-internacional-container" onclick="seleccionarEnvio(this, 'internacional')">
                            <input type="radio" name="metodo-envio" id="envio-internacional" value="internacional">
                            <div class="custom-radio"></div>
                            <div class="shipping-details">
                                <div class="shipping-title">Envío Internacional</div>
                                <div class="shipping-description">Entrega en 5-10 días laborables</div>
                            </div>
                            <div class="shipping-price" id="precio-internacional">14,95€</div>
                        </div>
                        
                        <div class="shipping-method" onclick="seleccionarEnvio(this, 'gratis')" id="envio-gratis-container">
                            <input type="radio" name="metodo-envio" id="envio-gratis" value="gratis">
                            <div class="custom-radio"></div>
                            <div class="shipping-details">
                                <div class="shipping-title">Envío Gratis</div>
                                <div class="shipping-description">Para pedidos superiores a 100€</div>
                            </div>
                            <div class="shipping-price">0,00€</div>
                        </div>
                    </div>
                </div>

                <!-- Método de Pago -->
                <div class="checkout-card">
                    <h2 class="section-header"><i class="far fa-credit-card"></i>Método de Pago</h2>
                    <div class="form-section">
                        <div class="payment-method">
                            <label for="metodoPago" class="form-label">Selecciona un método de pago</label>
                            <select id="metodoPago" name="metodoPago" class="form-select" required>
                                <option value="">Selecciona...</option>
                                <option value="tarjeta">Tarjeta de Crédito/Débito</option>
                            </select>

                        </div>

                        <!-- Datos de Tarjeta -->
                        <div id="tarjeta-datos" class="hidden">
                            <h3 class="section-header" style="font-size: 18px; padding: 0 0 16px 0; border-bottom: none;"><i class="fas fa-credit-card"></i>Datos de la Tarjeta</h3>
                            <label for="numero-tarjeta" class="form-label">Número de tarjeta</label>
                            <input type="text" id="numero-tarjeta" name="numero-tarjeta" class="form-control" placeholder="1234 5678 9012 3456" required>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="fecha-expiracion" class="form-label">Fecha de expiración</label>
                                    <input type="month" id="fecha-expiracion" name="fecha-expiracion" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="codigo-seguridad" class="form-label">CVV</label>
                                    <div style="position: relative;">
                                        <input type="text" id="codigo-seguridad" name="codigo-seguridad" class="form-control" placeholder="123" required>
                                        <i class="fas fa-question-circle" style="position: absolute; right: 10px; top: 12px; color: var(--medium-gray);" title="Los 3 dígitos en el reverso de tu tarjeta"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Resumen del Pedido -->
            <div class="col-lg-4">
                <div class="checkout-card" style="position: sticky; top: 20px;">
                    <h2 class="section-header"><i class="fas fa-receipt"></i>Resumen</h2>
                    <div class="form-section">
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span id="subtotal">0,00€</span>
                        </div>
                        <div class="summary-row">
                            <span>Envío</span>
                            <span id="precio-envio">0,00€</span>
                        </div>
                        <div class="summary-row">
                            <span>Total</span>
                            <span id="total-pedido">0,00€</span>
                        </div>
                        
                        <!-- Campos ocultos -->
                        <input type="hidden" name="carrito" id="carrito" value="">
                        <input type="hidden" name="costo-envio" id="costo-envio" value="0">
                        <input type="hidden" name="total-pedido" id="total-pedido-hidden" value="0">
                        
                        <button type="submit" class="btn-primary mt-4">
                            <i class="fas fa-lock"></i> Confirmar Compra
                        </button>
                        </form>
                        
                        <div style="margin-top: 20px; font-size: 12px; color: var(--medium-gray); text-align: center;">
                            <p><i class="fas fa-shield-alt"></i> Pago seguro con encriptación SSL</p>
                            <p><i class="fas fa-undo"></i> Devoluciones fáciles en 30 días</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let carrito = JSON.parse(localStorage.getItem("carrito")) || [];
        const carritoContainer = document.getElementById("carrito-container");
        const paisSelect = document.getElementById("pais");
        const envioInternacionalContainer = document.getElementById("envio-internacional-container");
        const envioGratisContainer = document.getElementById("envio-gratis-container");

        // Mostrar carrito
        function mostrarCarrito() {
            if (carrito && carrito.length > 0) {
                let html = "";
                let total = 0;
                
                carrito.forEach((producto, index) => {
                    html += `
                        <div class="cart-item">
                            <img src="${producto.imagen}" alt="${producto.nombre}" class="cart-item-image">
                            <div class="cart-item-details">
                                <div class="cart-item-name">${producto.nombre}</div>
                                <div class="cart-item-variant">Talla: ${producto.talla} | Color: ${producto.color || 'Negro'}</div>
                                <div class="cart-item-price">${producto.precio}€</div>
                            </div>
                            <button class="remove-item" onclick="eliminarProducto(${index})">
                                <i class="fas fa-trash-alt"></i> Quitar
                            </button>
                        </div>
                    `;
                    total += parseFloat(producto.precio);
                });
                
                carritoContainer.innerHTML = html;
                
                // Actualizar precios y disponibilidad de envíos
                actualizarEnvios(total);
                calcularTotalPedido(total);
            } else {
                carritoContainer.innerHTML = "<p class='text-center py-4'>Tu carrito está vacío</p>";
            }
        }

        // Función para eliminar productos del carrito
        function eliminarProducto(index) {
            if (confirm("¿Estás seguro de que quieres eliminar este producto de tu carrito?")) {
                carrito.splice(index, 1);
                localStorage.setItem("carrito", JSON.stringify(carrito));
                mostrarCarrito();
            }
        }

        // Función para seleccionar un método de envío
        function seleccionarEnvio(element, tipo) {
            // Desmarcar todos los métodos de envío
            document.querySelectorAll('.shipping-method').forEach(envio => {
                envio.classList.remove('selected');
            });
            
            // Marcar el seleccionado
            element.classList.add('selected');
            
            // Marcar el radio button correspondiente
            const radio = element.querySelector('input[type="radio"]');
            radio.checked = true;
            
            // Actualizar precio de envío mostrado
            let precioEnvio = 0;
            switch(tipo) {
                case 'estandar':
                    precioEnvio = 4.95;
                    break;
                case 'express':
                    precioEnvio = 9.95;
                    break;
                case 'internacional':
                    precioEnvio = 14.95;
                    break;
                case 'gratis':
                    precioEnvio = 0;
                    break;
            }
            
            document.getElementById('precio-envio').textContent = precioEnvio.toFixed(2) + '€';
            document.getElementById('costo-envio').value = precioEnvio;
            
            // Calcular total del pedido
            const subtotal = carrito.reduce((total, producto) => total + parseFloat(producto.precio), 0);
            calcularTotalPedido(subtotal);
        }

        // Función para actualizar los métodos de envío disponibles
        function actualizarEnvios(subtotal) {
            const pais = paisSelect.value;
            
            // Mostrar/ocultar envío internacional según el país
            if (pais === 'int') {
                envioInternacionalContainer.classList.remove('hidden');
                document.getElementById('envio-estandar').parentElement.classList.add('hidden');
                document.getElementById('envio-express').parentElement.classList.add('hidden');
                document.getElementById('envio-gratis').parentElement.classList.add('hidden');
                
                // Seleccionar envío internacional por defecto
                const envioInt = document.getElementById('envio-internacional-container');
                seleccionarEnvio(envioInt, 'internacional');
            } else {
                envioInternacionalContainer.classList.add('hidden');
                document.getElementById('envio-estandar').parentElement.classList.remove('hidden');
                document.getElementById('envio-express').parentElement.classList.remove('hidden');
                
                // Mostrar/ocultar envío gratis según el subtotal
                if (subtotal >= 100) {
                    envioGratisContainer.classList.remove('hidden');
                    // Seleccionar envío gratis por defecto si está disponible
                    const envioGratis = document.getElementById('envio-gratis-container');
                    seleccionarEnvio(envioGratis, 'gratis');
                } else {
                    envioGratisContainer.classList.add('hidden');
                    // Seleccionar envío estándar por defecto
                    const envioEstandar = document.querySelector('.shipping-method:not(.hidden)');
                    seleccionarEnvio(envioEstandar, 'estandar');
                }
            }
        }

        // Función para calcular el total del pedido
        function calcularTotalPedido(subtotal) {
            const envio = parseFloat(document.getElementById('costo-envio').value) || 0;
            const total = subtotal + envio;
            
            document.getElementById('subtotal').textContent = subtotal.toFixed(2) + '€';
            document.getElementById('total-pedido').textContent = total.toFixed(2) + '€';
            document.getElementById('total-pedido-hidden').value = total.toFixed(2);
        }

        // Evento cuando cambia el país
        paisSelect.addEventListener('change', function() {
            const subtotal = carrito.reduce((total, producto) => total + parseFloat(producto.precio), 0);
            actualizarEnvios(subtotal);
        });

        // Cuando se envíe el formulario, llenar el campo oculto con los datos del carrito
        document.getElementById("formulario-pago").addEventListener("submit", function (e) {
            // Comprobamos si el carrito está vacío antes de enviar el formulario
            if (!carrito || carrito.length === 0) {
                alert("Tu carrito está vacío. Por favor, agrega productos antes de proceder con la compra.");
                e.preventDefault(); // Detener el envío del formulario si el carrito está vacío
            } else {
                // Asegúrate de que el campo oculto tenga el valor correcto
                document.getElementById("carrito").value = JSON.stringify(carrito);
            }
        });

        // Referencia al elemento de selección de método de pago y al formulario de datos de tarjeta
        const metodoPago = document.getElementById("metodoPago");
        const tarjetaDatos = document.getElementById("tarjeta-datos");

        // Escuchamos el cambio en el método de pago seleccionado
        metodoPago.addEventListener("change", function () {
            // Si el método de pago seleccionado es "tarjeta", mostramos el formulario de tarjeta
            if (metodoPago.value === "tarjeta") {
                tarjetaDatos.classList.remove("hidden");
            } else {
                tarjetaDatos.classList.add("hidden");
            }
        });

        // Validación del formulario antes de enviar
        document.getElementById("formulario-pago").addEventListener("submit", function(e) {
            // Validar método de pago
            const metodoPago = document.getElementById("metodoPago").value;
            if (metodoPago === "") {
                alert("Por favor, selecciona un método de pago");
                e.preventDefault();
                return;
            }
            
            // Validar datos de tarjeta si es necesario
            if (metodoPago === "tarjeta") {
                const numeroTarjeta = document.getElementById("numero-tarjeta").value.replace(/\s/g, '');
                const cvv = document.getElementById("codigo-seguridad").value;
                
                if (!/^\d{13,16}$/.test(numeroTarjeta)) {
                    alert("El número de tarjeta no es válido");
                    e.preventDefault();
                    return;
                }
                
                if (!/^\d{3,4}$/.test(cvv)) {
                    alert("El código CVV no es válido");
                    e.preventDefault();
                    return;
                }
                
                // Validar fecha de expiración
                const fechaExpiracion = document.getElementById("fecha-expiracion").value;
                if (!fechaExpiracion) {
                    alert("Por favor, ingresa la fecha de expiración");
                    e.preventDefault();
                    return;
                }
                
                const [year, month] = fechaExpiracion.split('-');
                const fechaExp = new Date(year, month - 1);
                const hoy = new Date();
                if (fechaExp < hoy) {
                    alert("La tarjeta ha expirado");
                    e.preventDefault();
                    return;
                }
            }
            
            // Asegurarse de que se ha seleccionado un método de envío
            if (!document.querySelector('input[name="metodo-envio"]:checked')) {
                alert("Por favor, selecciona un método de envío");
                e.preventDefault();
                return;
            }
            
            // Convertir el carrito a JSON y guardarlo en el campo oculto
            document.getElementById("carrito").value = JSON.stringify(carrito);
        });

        // Formatear número de tarjeta para mejor legibilidad
        document.getElementById("numero-tarjeta").addEventListener("input", function(e) {
            let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            let formatted = '';
            
            for (let i = 0; i < value.length; i++) {
                if (i > 0 && i % 4 === 0) {
                    formatted += ' ';
                }
                formatted += value[i];
            }
            
            e.target.value = formatted;
        });

        // Inicializar
        mostrarCarrito();
    </script>

</body>
</html>