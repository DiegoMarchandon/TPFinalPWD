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
               // Verificar el rol del usuario
               $abmUsuarioRol = new ABMUsuarioRol();
               $rolesUsuario = $abmUsuarioRol->buscar(['idusuario' => $usuario->getIdUsuario()]);
               $idRolUsuario = null;
               foreach ($rolesUsuario as $usuarioRol) {
                   $idRolUsuario = $usuarioRol->getObjRol()->getIdrol();
                   break; // Asumimos que un usuario tiene un solo rol
               }

               // Redirigir según el rol del usuario
               if ($idRolUsuario == 3) {
                   $response['redirect'] = '../Home/productos.php';
               } elseif ($idRolUsuario == 1 || $idRolUsuario == 2) {
                   $response['redirect'] = '../Home/paginaSegura.php';
               } else {
                   $response['error'] = 'Rol de usuario no válido.';
               }

               $response['success'] = 'Inicio de sesión exitoso.';
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