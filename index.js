document.addEventListener("DOMContentLoaded", function() {
    // Verificar si hay un usuario activo
    let usuarioActivo = JSON.parse(localStorage.getItem("usuarioActivo"));

    // Mostrar el nombre del usuario si está logueado
    if (usuarioActivo) {
        let nombreUsuarioElement = document.getElementById("nombreUsuario");
        if (nombreUsuarioElement) {
            nombreUsuarioElement.textContent = `¡Bienvenido, ${usuarioActivo.nombre}!`;
        }

        document.getElementById("menuUsuario").style.display = "block"; // Mostrar menú de usuario
    }

    // Icono de usuario: Maneja el despliegue del menú de opciones
    let iconoUsuario = document.getElementById("iconoUsuario");
    if (iconoUsuario) {
        iconoUsuario.addEventListener("click", function() {
            let menuUsuario = document.getElementById("menuUsuario");
            menuUsuario.style.display = (menuUsuario.style.display === "block") ? "none" : "block";
        });
    }

    // Botón de cerrar sesión
    let btnCerrarSesion = document.getElementById("btnCerrarSesion");
    if (btnCerrarSesion) {
        btnCerrarSesion.addEventListener("click", function() {
            localStorage.removeItem("usuarioActivo"); // Eliminar usuario activo
            window.location.href = "index.php"; // Redirigir a scuffers.php
        });
    }
    
    
  let btnConfiguracion = document.getElementById("btnConfiguracion");
    if (btnConfiguracion) {
        btnConfiguracion.addEventListener("click", function() {
            
            window.location.href = "configuracion.php"; // Redirigir a scuffers.php
        });
    }


    // Función para actualizar el carrito
    function actualizarCarrito() {
        let listaCarrito = document.getElementById("listaCarrito");
        let totalCarrito = document.getElementById("totalCarrito");
        let menuCarrito = document.getElementById("menuCarrito");

        listaCarrito.innerHTML = "";
        let total = 0;

        if (carrito.length > 0) {
            menuCarrito.style.display = "block"; // Mostrar carrito solo si hay productos
        } else {
            menuCarrito.style.display = "none";
        }

        carrito.forEach((producto, index) => {
            let item = document.createElement("li");
            item.innerHTML = `
                <img src="${producto.imagen}" alt="${producto.nombre}" style="width: 50px; height: auto; border-radius: 5px;">
                ${producto.nombre} - Talla: ${producto.talla} - ${producto.precio.toFixed(2)}€
                <button class="eliminar-item" data-index="${index}">❌</button>
            `;
            listaCarrito.appendChild(item);
            total += producto.precio;
        });

        totalCarrito.textContent = `Total: ${total.toFixed(2)}€`;

        // Evento para eliminar producto
        document.querySelectorAll(".eliminar-item").forEach((boton) => {
            boton.addEventListener("click", (event) => {
                let index = event.target.getAttribute("data-index");
                carrito.splice(index, 1);
                actualizarCarrito();
            });
        });
    }

    // Inicializamos el carrito
    let carrito = JSON.parse(localStorage.getItem("carrito")) || [];

    // Botón de finalizar compra
    document.getElementById("finalizarCompra").addEventListener("click", function() {
        // Asegurarse de que el carrito tenga productos
        if (carrito.length > 0) {
            // Guardar el carrito en localStorage
            localStorage.setItem("carrito", JSON.stringify(carrito));

            // Redirigir a la página de finalizar compra
            window.location.href = "finalizarCompra.php";
        } else {
            alert("Tu carrito está vacío. Añade productos antes de finalizar la compra.");
        }
    });


// Seleccionamos todos los botones de talla
document.querySelectorAll(".tallas button").forEach(button => {
  button.addEventListener("click", function() {
    // Desmarcar todos los botones de talla
    document.querySelectorAll(".tallas button").forEach(b => b.classList.remove("selected"));

    // Marcar el botón de talla clicado
    this.classList.add("selected");

    // Opcional: Si quieres obtener la talla seleccionada
    let tallaSeleccionada = this.getAttribute("data-talla");
    console.log("Talla seleccionada:", tallaSeleccionada);
  });
});


    
    
    
    // Agregar productos al carrito
    document.querySelectorAll(".add-to-cart").forEach((icono) => {
        icono.addEventListener("click", (event) => {
            let articulo = event.target.closest(".articulo"); // Encuentra el contenedor del producto
            let idProducto = articulo.getAttribute("data-id"); // Obtiene el ID
            let nombreProducto = articulo.querySelector(".texto p").textContent; // Nombre del producto
            let precioProducto = articulo.querySelector(".texto p:nth-child(2)").textContent; // Precio
            let tallaSeleccionada = articulo.querySelector(".tallas button.selected")?.getAttribute("data-talla") || "S"; // Talla seleccionada
            let imgProducto = articulo.querySelector("img:first-of-type").src; // Captura la imagen del producto

            let producto = {
                id: idProducto,
                nombre: nombreProducto,
                precio: parseFloat(precioProducto.replace("€", "").replace(",", ".")), // Convertir precio a número
                talla: tallaSeleccionada,
                imagen: imgProducto // Agregamos la imagen
            };

            carrito.push(producto);
            actualizarCarrito();
        });
    });

    // Cerrar carrito
    document.getElementById("cerrarCarrito").addEventListener("click", function() {
        document.getElementById("menuCarrito").style.display = "none";
    });

    // Obtener elementos del DOM
const carritoIcono = document.getElementById('iconoCarrito');
const menuCarrito = document.getElementById('menuCarrito');
const cerrarCarrito = document.getElementById('cerrarCarrito');

// Función para mostrar el menú del carrito
function toggleCarritoMenu() {
    // Cambiar el estado de visibilidad del menú
    menuCarrito.style.display = (menuCarrito.style.display === 'none' || menuCarrito.style.display === '') ? 'block' : 'none';
}

// Función para cerrar el menú del carrito
function cerrarMenuCarrito() {
    menuCarrito.style.display = 'none';
}

// Agregar eventos
carritoIcono.addEventListener('click', toggleCarritoMenu); // Mostrar/ocultar al hacer clic en el ícono
cerrarCarrito.addEventListener('click', cerrarMenuCarrito); // Cerrar el menú cuando se hace clic en "Cerrar"
    
document.getElementById("instagram").addEventListener("click", function() {
    window.location.href = "https://www.instagram.com/ivan.soriia";
});

document.getElementById("tiktok").addEventListener("click", function() {
    window.location.href = "https://www.tiktok.com/@sooriiiaa";
});

document.getElementById("facebook").addEventListener("click", function() {
    window.location.href = "https://www.facebook.com/tu_usuario";
});

    
    
});
