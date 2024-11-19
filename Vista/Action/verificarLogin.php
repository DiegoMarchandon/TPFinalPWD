<?php
include_once '../../configuracion.php';
header('Content-Type: application/json');

// Verificar que el método sea POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = darDatosSubmitted();
    $nombreUsuario = $datos['nombreUsuario'];
    $psw = $datos['uspass'];

    $abmUsuario = new ABMUsuario();
    
    // Buscar el usuario por nombre de usuario
    $usuario = $abmUsuario->buscar(['usnombre' => $nombreUsuario]);

    $response = [];

    if (count($usuario) > 0) {
        $usuario = $usuario[0];
        $hashedPassword = $usuario->getUsPass();

        // Verificar la contraseña
        if ($hashedPassword === $psw) {
            // Autenticación exitosa, iniciar sesión
            $session = new Session();
            if ($session->iniciar($nombreUsuario, $hashedPassword)) {
                // Sesión iniciada correctamente
                $response['success'] = 'Inicio de sesión exitoso.';
                $response['redirect'] = '../Home/paginaSegura.php';
            } else {
                // Error al iniciar sesión
                $response['error'] = 'Error al iniciar sesión. Por favor, inténtelo de nuevo.';
            }
        } else {
            // Contraseña Incorrecta
            $response['error'] = 'Credenciales incorrectas. Por favor, inténtelo de nuevo.';
        }
    } else {
        // Usuario no encontrado
        $response['error'] = 'Credenciales incorrectas. Por favor, inténtelo de nuevo.';
    }

    
    echo json_encode($response);
    exit;
}
?>