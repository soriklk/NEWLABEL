document.getElementById("iconoUsuario").addEventListener("click", function() {
    let menuLogin = document.getElementById("menuLogin");
    menuLogin.style.display = (menuLogin.style.display === "block") ? "none" : "block";
});

// Cierra el menú si se hace clic fuera de él
document.addEventListener("click", function(event) {
    let menuLogin = document.getElementById("menuLogin");
    let iconoUsuario = document.getElementById("iconoUsuario");

    if (!menuLogin.contains(event.target) && event.target !== iconoUsuario) {
        menuLogin.style.display = "none";
    }
});

// Redirecciones a login y registro (ajusta según necesites)
document.getElementById("btnLogin").addEventListener("click", function() {
    
    window.location.href = "loginV.php";
});

document.getElementById("btnRegistro").addEventListener("click", function() {
    window.location.href = "registroV.php";
});


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

    // Icono de usuario
    let iconoUsuario = document.getElementById("iconoUsuario");
    if (iconoUsuario) {
        iconoUsuario.addEventListener("click", function() {
            let menuUsuario = document.getElementById("menuUsuario");
            menuUsuario.style.display = (menuUsuario.style.display === "block") ? "none" : "block";
        });
    }

    // // Botón de cerrar sesión
    // let btnCerrarSesion = document.getElementById("btnCerrarSesion");
    // if (btnCerrarSesion) {
    //     btnCerrarSesion.addEventListener("click", function() {
    //         localStorage.removeItem("usuarioActivo"); // Eliminar usuario activo
    //         window.location.href = "index.html"; // Redirigir a login
    //     });
    // }
});



// Validar usuario
function validarUsuario(email, password) {
    let usuarioGuardado = JSON.parse(localStorage.getItem("usuarioRegistrado"));

    if (usuarioGuardado && usuarioGuardado.email === email && usuarioGuardado.password === password) {
        return usuarioGuardado;
    }
    return null;
}

// Manejador del login
document.addEventListener("DOMContentLoaded", function() {
    let formLogin = document.getElementById("loginForm");

    if (formLogin) {
        formLogin.addEventListener("submit", function(event) {
            event.preventDefault();
            let email = document.getElementById("email").value.trim();
            let password = document.getElementById("password").value.trim();

            if (email === "" || password === "") {
                alert("Por favor, completa todos los campos.");
                return;
            }

            let usuario = validarUsuario(email, password);
            if (usuario) {
                alert(`Bienvenido, ${usuario.nombre}!`);
                localStorage.setItem("usuarioActivo", JSON.stringify(usuario));
                window.location.href = "scuffers.php"; // Redirige a la página principal
            } else {
                let mensajeError = document.createElement("p");
                mensajeError.textContent = "No tienes cuenta, por favor regístrate.";
                mensajeError.style.color = "red";
                formLogin.appendChild(mensajeError);
                setTimeout(() => {
                    mensajeError.remove();
                }, 5000);
            }
        });
    }
});

document.addEventListener("DOMContentLoaded", function() {
    let formRegistro = document.getElementById("registroForm");

    if (formRegistro) {
        formRegistro.addEventListener("submit", function(event) {
            event.preventDefault();

            let nombre = document.getElementById("newUsername").value.trim();
            let email = document.getElementById("newEmail").value.trim();
            let password = document.getElementById("newPassword").value.trim();

            if (nombre === "" || email === "" || password === "") {
                alert("Todos los campos son obligatorios.");
                return;
            }

            localStorage.setItem("usuarioRegistrado", JSON.stringify({ nombre, email, password }));
            alert("Registro exitoso. Ahora puedes iniciar sesión.");
            window.location.href = "loginV.php";
        });
    }
});


// Redirecciones a login y registro (ajusta según necesites)
document.getElementById("iconoCarrito").addEventListener("click", function() {
    
    window.location.href = "carrito.php";
    
});          





