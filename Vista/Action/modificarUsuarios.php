<?php
include_once '../../configuracion.php';

$session = new Session();
$abmUsuario = new ABMUsuario();
echo "<h1>action del login</h1>";

// Recoger los datos enviados por el formulario
$datos = darDatosSubmitted();

// inicializamos las variables que almacenarán los datos
$nombreUsuario;
$hashedPassword;
$email;
$usdeshabilitado;

// usamos el $datos['usnombre'] (dato obligatorio en nuestro formulario) para encontrar al usuario
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

    if ($abmUsuario->modificacion($param)) {
        echo "Actualización exitosa<br>";
        header('Location: ../Home/paginaSegura.php?mensaje=actualizacion_exitosa');
    } else {
        echo "Error al actualizar el usuario.<br>";
        echo '<br><a href="../Home/actualizarUsuario.php">Volver a intentar</a>';
    }
} else {
    echo "Usuario no encontrado.<br>";
    echo '<br><a href="../Home/actualizarUsuario.php">Volver a intentar</a>';
}
?>