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
// tiene que recuperar datos de la ventana, conoce los objetos de transacción, y conoce el método o función que hace o se espera de la función
$session = new Session;
$ABMCompra = new ABMCompra;
// Configurar la zona horaria a Argentina
date_default_timezone_set('America/Argentina/Buenos_Aires');
// lee los archivos JSON enviados a través de una solicitud HTTP, los decodifica y los convierte en un arreglo asociativo PHP.
// php://input se usa para leer el cuerpo de la solicitud sin procesar
$data = json_decode(file_get_contents('php://input'), true);

$respuesta = [
    'status' => 'default',
    'message' => 'estado inicial'
];


if ($data) {
    $idUsuario = $session->getUsuario()->getIdusuario(); // Obtener el ID del usuario de la sesión
    $idProducto = $data['idproducto'];
    $cantSeleccionada = $data['prodCantSelec'];
    $fechaCompra = date('Y-m-d H:i:s');
    if($ABMCompra->actualizarCompra($idUsuario,$idProducto,$cantSeleccionada)){
        $respuesta['status'] = 'success';
        $respuesta['message'] = "actualización exitosa";
    }else{
        $respuesta['status'] = 'error';
        $respuesta['message'] = "No se ha podido actualizar";
    }

} else {
    $respuesta['status'] = 'error';
    $respuesta['message'] = 'datos no válidos';
}

echo json_encode($respuesta);
include_once '../estructura/footer.php';
?>