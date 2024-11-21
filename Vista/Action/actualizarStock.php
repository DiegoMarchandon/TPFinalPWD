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

$response = [
    'status' => 'default',
    'message' => 'default',
    'redirect' => '../Home/stock.php'
];

$ABMProducto = new ABMProducto;

$productoBuscado = $ABMProducto->buscarArray(['idproducto' => $datos['idproducto']]);

if($productoBuscado > 0){
    $param = [
        'idproducto' => $productoBuscado[0]['idproducto'],
        'pronombre' => $productoBuscado[0]['pronombre'],
        'prodetalle' => $productoBuscado[0]['prodetalle'],
        'precioprod' => $productoBuscado[0]['precioprod'],
        'procantstock' => $datos['nuevoStock']
    ];
    if($ABMProducto->modificacion($param)){
        $response['status'] = 'success';
        $response['message'] = 'actualizacion de stock exitosa';
    }else{
        $response['status'] = 'Error en 2do condicional';
        $response['message'] = '!$ABMProducto->modificacion($param)';
    }
}else{
    $response['status'] = 'Error en 1er condicional';
    $response['message'] = '$ABMProducto->buscarArray($datos["idproducto"]) === 0';
}

echo json_encode($response);
exit;
?>