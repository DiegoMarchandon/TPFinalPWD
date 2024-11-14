<?php
include_once '../../configuracion.php';
include_once('../estructura/headerSeguro.php');
$ABMUsuario = new ABMUsuario;
$session = new Session;

$colUsers = $ABMUsuario->buscarArray(null);
print_r($colUsers);

$userActual = $session->getUsuario();
print_r($userActual);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuenta</title>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Cambiar Datos de Usuario</h2>
    <form action="cambiar_datos.php" method="POST">
        <!-- Nombre de Usuario -->
        <div class="mb-3">
            <label for="current_username" class="form-label">Nombre de Usuario Actual</label>
            <input type="text" class="form-control" id="current_username" name="current_username" placeholder="Ingrese su nombre de usuario actual" required>
        </div>
        <div class="mb-3">
            <label for="new_username" class="form-label">Nuevo Nombre de Usuario</label>
            <input type="text" class="form-control" id="new_username" name="new_username" placeholder="Ingrese su nuevo nombre de usuario" required>
        </div>

        <!-- Contraseña -->
        <div class="mb-3">
            <label for="current_password" class="form-label">Contraseña Actual</label>
            <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Ingrese su contraseña actual" required>
        </div>
        <div class="mb-3">
            <label for="new_password" class="form-label">Nueva Contraseña</label>
            <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Ingrese su nueva contraseña" required>
        </div>

        <!-- Correo Electrónico -->
        <div class="mb-3">
            <label for="current_email" class="form-label">Correo Electrónico Actual</label>
            <input type="email" class="form-control" id="current_email" name="current_email" placeholder="Ingrese su correo electrónico actual" required>
        </div>
        <div class="mb-3">
            <label for="new_email" class="form-label">Nuevo Correo Electrónico</label>
            <input type="email" class="form-control" id="new_email" name="new_email" placeholder="Ingrese su nuevo correo electrónico" required>
        </div>

        <!-- Botón de Enviar -->
        <button type="submit" class="btn btn-primary">Cambiar Datos</button>
    </form>
</div>
</body>
</html>
<?php 

include_once('../estructura/footer.php');
?>