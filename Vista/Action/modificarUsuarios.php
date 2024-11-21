<?php
include_once '../../configuracion.php';

$session = new Session();
$abmUsuario = new ABMUsuario();
echo "<h1>action del login</h1>";

// Recoger los datos enviados por el formulario
$datos = darDatosSubmitted();
$datosExistentes = false;

// inicializamos las variables que almacenarán los datos
$nombreUsuario;
$hashedPassword;
$email;
$usdeshabilitado;

// usamos el $datos['idusuario'] (dato obligatorio en nuestro formulario) para encontrar al usuario
$userActual = $abmUsuario->buscarArray(['idusuario' => $datos['idusuario']]);

// Verificar si se encontró el usuario
if (count($userActual) > 0) {
    $userActual = $userActual[0];

    // si el campo está vacío, se mantiene el nombre actual del usuario
    if ($datos['usnombre'] === '') {
        $nombreUsuario = $userActual['usnombre'];
    } else {
        $nombreUsuario = $datos['usnombre'];
    }

    // si el campo de la contraseña está vacío, se mantiene la contraseña actual del usuario
    if ($datos['uspass'] === '') {
        $hashedPassword = $userActual['uspass'];
    } else {
        $hashedPassword = $datos['uspass'];
    }

    if ($datos['usmail'] === '') {
        $email = $userActual['usmail'];
        echo "<br>nuevoEmail no existe<br>";
    } else {
        $email = $datos['usmail'];
    }

    $param = [
        'idusuario' => $userActual['idusuario'],
        'usnombre' => $nombreUsuario,
        'uspass' => $hashedPassword,
        'usmail' => $email,
        'usdeshabilitado' => $userActual['usdeshabilitado']
    ];

    $usuariosConMismoNombre = $abmUsuario->buscar(['usnombre' => $nombreUsuario]);
    $usuariosConMismoEmail = $abmUsuario->buscar(['usmail' => $email]);

    foreach ($usuariosConMismoNombre as $usuario) {
        if ($usuario->getIdUsuario() != $userActual['idusuario']) {
            $datosExistentes = true;
            break;
        }
    }

    foreach ($usuariosConMismoEmail as $usuario) {
        if ($usuario->getIdUsuario() != $userActual['idusuario']) {
            $datosExistentes = true;
            break;
        }
    }

    // Mostrar los datos para depuración
    //echo "<pre>";
    //var_dump($param);
    //echo "</pre>";

    if ($datosExistentes) {
        //echo "El nombre de usuario o el email ya existen.<br>";
        header('Location: ../Home/actualizarUsuario.php?mensaje=error_al_modificar');
    } else {
        if ($abmUsuario->modificacion($param)) {
            //echo "Actualización exitosa<br>";
            header('Location: ../Home/actualizarUsuario.php?mensaje=actualizacion_exitosa');
        } else {
           //echo "Error al actualizar el usuario.<br>";
            header('Location: ../Home/actualizarUsuario.php?mensaje=error_al_modificar');
        }
    }
} else {
    //echo "Usuario no encontrado.<br>";
    header('Location: ../Home/actualizarUsuario.php?mensaje=error_al_modificar');
}
?>