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
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Solicitud no válida.']);
    exit;
}

header('Content-Type: application/json');
// voy creando el arreglo asociativo que voy a pasar como respuesta en formato JSON
$response = [
    'status' => 'default',
    'message' => 'Parte inicial del action',
    'redirect' => '../Home/carrito.php'
];

// Configurar la zona horaria a Argentina
date_default_timezone_set('America/Argentina/Buenos_Aires');

$session = new Session();
$fechaFin = date('Y-m-d H:i:s');
$idUsuarioActual = $session->getUsuario()->getIdusuario();

$ABMcompraEstado = new ABMCompraEstado;
$compraIniciada = $ABMcompraEstado->buscarCompraIniciada($idUsuarioActual);

if ($compraIniciada !== null) {
    $idCompra = $compraIniciada->getIdcompra();
    $response = $ABMcompraEstado->confirmarCompra($idCompra, $fechaFin);
} else {
    $response['status'] = 'error';
    $response['message'] = 'No hay compras iniciadas.';
}

echo json_encode($response);
exit;
?>