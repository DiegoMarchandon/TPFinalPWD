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
// voy creando el arreglo asociativo que voy a pasar como respuesta en formato JSON
$response = [
    'status' => 'default',
    'message' => 'Parte inicial del action',
    'redirect' => '../Home/ordenes.php'
];

// Configurar la zona horaria a Argentina
date_default_timezone_set('America/Argentina/Buenos_Aires');

$session = new Session();
$fechaFin = date('Y-m-d H:i:s');
$idUsuarioActual = $session->getUsuario()->getIdusuario();

$datos = darDatosSubmitted();

$ABMcompraEstado = new ABMCompraEstado;
$ABMCompra = new ABMCompra;

// acá verifico si este action fue llamado desde el cliente (en el botón cancelar de carrito.php) o desde depósito (en el botón de cancelar de ordenes.php)
$compraBuscada = isset($datos['idcompra']) ? $ABMcompraEstado->cancelarCompra($datos['idcompra'],null) : $ABMcompraEstado->cancelarCompra(null,$idUsuarioActual);

if(!empty($compraBuscada)){
    $response['status'] = 'success';
    $response['message'] = 'operacion exitosa';
    $clavesMail = $ABMCompra->mailInfo($compraBuscada);
    $response['toName'] = $clavesMail['nombreUsuario'];
    $response['toEmail'] = $clavesMail['mailUsuario'];
}else{
    $response['status'] = 'error';
    $response['message'] = 'no se ha podido cancelar la compra.';
}

echo json_encode($response);
exit;
?>