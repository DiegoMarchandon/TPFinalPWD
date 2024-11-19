<?php
include_once '../../configuracion.php';
include_once("../estructura/header.php");

// Usar la función darDatosSubmitted para obtener los datos
$datos = darDatosSubmitted();
?>

<div class="container mt-5">
    <h1 class="text-center">Iniciar Sesión</h1>
    <?php
    if (isset($datos['error']) && $datos['error'] == 'credenciales') {
        echo '<div class="alert alert-danger text-center">Credenciales incorrectas. Por favor, inténtelo de nuevo.</div>';
    }
    if (isset($datos['registro']) && $datos['registro'] == 'exitoso') {
        echo '<div class="alert alert-success text-center">Cuenta creada exitosamente. Ahora puede iniciar sesión.</div>';
    }
    ?>
    <form id="loginForm" action="../Action/verificarLogin.php" method="POST" onsubmit="return hashPassword()">
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
<?php include_once("../estructura/footer.php"); ?>