<?php
include_once '../../configuracion.php';
include_once("../estructura/header.php");

// Usar la función darDatosSubmitted para obtener los datos
$datos = darDatosSubmitted();
?>

<div class="container mt-5">
    <h1 class="text-center">Iniciar Sesión</h1>
    <div id="mensaje" class="text-center"></div>
    <?php
    if (isset($datos['registro']) && $datos['registro'] == 'exitoso') {
        echo '<div class="alert alert-success text-center">Cuenta creada exitosamente. Ahora puede iniciar sesión.</div>';
    }
    ?>
    <form id="loginForm" method="POST" onsubmit="return verificarLogin(event)">
        <div class="form-group">
            <label for="nombreUsuario">Nombre de Usuario</label>
            <input type="text" class="form-control" id="nombreUsuario" name="nombreUsuario" required>
        </div>
        <div class="form-group">
            <label for="uspass">Contraseña</label>
            <input type="password" class="form-control" id="uspass" name="uspass" required>
        </div>
        <div class="text-center">
            <a href="../Home/registrarUsuario.php">Registrarse</a>
        </div>
        <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
    </form>
</div>

<script>
function hashPassword(password) {
    return CryptoJS.SHA256(password).toString();
}

function verificarLogin(event) {
    event.preventDefault(); // Evitar el envío del formulario

    const nombreUsuario = document.getElementById('nombreUsuario').value;
    const uspassField = document.getElementById('uspass');
    const uspass = uspassField.value;

    // Hashear la contraseña
    const hashedPassword = hashPassword(uspass);

    $.ajax({
        url: '../Action/verificarLogin.php',
        method: 'POST',
        data: {
            nombreUsuario: nombreUsuario,
            uspass: hashedPassword
        },
        success: function(response) {
            const mensajeDiv = document.getElementById('mensaje');
            if (response.error) {
                mensajeDiv.innerHTML = '<div class="alert alert-danger text-center">' + response.error + '</div>';
            } else if (response.success) {
                mensajeDiv.innerHTML = '<div class="alert alert-success text-center">' + response.success + '</div>';
                window.location.href = response.redirect;
            }
        },
        error: function() {
            const mensajeDiv = document.getElementById('mensaje');
            mensajeDiv.innerHTML = '<div class="alert alert-danger text-center">Error al verificar las credenciales. Por favor, inténtelo de nuevo.</div>';
        }
    });

    return false; // Evitar el envío del formulario
}
</script>

<?php include_once("../estructura/footer.php"); ?>