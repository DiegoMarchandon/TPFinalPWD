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

header('Content-Type: application/json');

$datos = darDatosSubmitted();
$response = [];

// Depuración: agregar los datos recibidos a la respuesta
$response['debug']['datos'] = $datos;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($datos['id']) && isset($datos['rol'])) {
    $abmUsuarioRol = new ABMUsuarioRol();
    $datos['idusuario'] = $datos['id']; // Se espera que el ID del usuario esté en 'id'
    $datos['idrol'] = $datos['rol'];  // Se espera que el ID del rol esté en 'rol'
    $response = array_merge($response, $abmUsuarioRol->asignarRolUnico($datos));
} else {
    $response['status'] = 'error';
    $response['message'] = 'Método no permitido o parámetros faltantes.';
}

echo json_encode($response);
exit;
?>