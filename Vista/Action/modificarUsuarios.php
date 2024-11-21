<?php
include_once '../../configuracion.php';

// Verifica si es una solicitud AJAX
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

// Verifica si es una solicitud POST o GET
$isPostOrGet = $_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET';

// Verifica si el token de seguridad es válido (solo para POST/GET)
$isValidToken = isset($_POST['form_security_token']) && $_POST['form_security_token'] === 'valor_esperado';

// Si no es AJAX ni una solicitud válida POST/GET con el token, redirige
if (!$isAjax && (!$isPostOrGet || !$isValidToken)) {
    header('Location: ../Home/login.php');
    exit;
}

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