<?php
include_once '../../configuracion.php';

// Verificar que el método sea POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = darDatosSubmitted();
    $nombreUsuario = $datos['nombreUsuario'];
    $psw = $datos['uspass'];

    $abmUsuario = new ABMUsuario();
    
    // Buscar el usuario por nombre de usuario
    $usuario = $abmUsuario->buscar(['usnombre' => $nombreUsuario]);

    if (count($usuario) > 0) {
        $usuario = $usuario[0];
        $hashedPassword = $usuario->getUsPass();

        // Verificar la contraseña
        if ($hashedPassword === $psw) {
            echo "Contraseña Verificada Correctamente<br>";
            // Autenticación exitosa, iniciar sesión
            $session = new Session();
            if ($session->iniciar($nombreUsuario, $hashedPassword)) {
                // Sesión iniciada correctamente
                header('Location: ../Home/paginaSegura.php');
            } else {
                // Error al iniciar sesión
                header('Location: ../Home/login.php?error=credenciales');
            }
        } else {
            // Contraseña Incorrecta
            header('Location: ../Home/login.php?error=credenciales');
        }
    } else {
        // Usuario no encontrado
        header('Location: ../Home/login.php?error=credenciales');
    }
}
?>