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
// Verificar que el método sea POST

$datos = darDatosSubmitted();
// inicializamos las variables que almacenarán los datos
$nombreUsuario;
$hashedPassword;
$email;
$usdeshabilitado;
// usamos el $datos['nombreActual'] (dato obligatorio en nuestro formulario) para encontrar al usuario
$userActual = $abmUsuario->buscarArray(['usnombre' => $datos['nombreActual']]);

// si el campo está vacío, se mantiene el nombre actual del usuario
if($datos['nuevoNombre'] === ''){
    $nombreUsuario = $userActual[0]['usnombre'];
}else{
    $nombreUsuario = $datos['nuevoNombre'];
}

if($datos['nuevaContraseña'] === '' || $datos['nuevaContraseñaConfirm'] === ''){
    $hashedPassword = $userActual[0]['uspass'];
}else{
    $hashedPassword = $datos['nuevaContraseña'];
}


if($datos['nuevoEmail'] === ''){
    $email = $userActual[0]['usmail'];
    echo "<br>nuevoEmail no existe<br>";
}else{
    $email = $datos['nuevoEmail'];
}

$param = [
    'idusuario' => $userActual[0]['idusuario'],
    'usnombre' => $nombreUsuario,
    'uspass' => $hashedPassword,
    'usmail' => $email,
    'usdeshabilitado' => $userActual[0]['usdeshabilitado']
];

if ($abmUsuario->modificacion($param)) {
    echo "Actualización exitosa<br>";
    header('Location: ../Home/paginaSegura.php?mensaje=actualizacion_exitosa');
} else {
    echo "Error al actualizar el usuario.<br>";
    echo '<br><a href="../Home/actualizarUsuario.php">Volver a intentar</a>';
}

?>