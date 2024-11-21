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
    'redirect' => '../Home/carrito.php'
];

// Configurar la zona horaria a Argentina
date_default_timezone_set('America/Argentina/Buenos_Aires');

$session = new Session();
$fechaFin = date('Y-m-d H:i:s');
$idUsuarioActual = $session->getUsuario()->getIdusuario();

$ABMcompraEstado = new ABMCompraEstado;
$carritosIniciados = $ABMcompraEstado->buscarCompraIniciadaPorUsuario($idUsuarioActual);

$compraConfirmada = false;
if ($carritosIniciados !== null) {
    foreach ($carritosIniciados as $compraIniciada) {
        $idCompra = $compraIniciada->getIdcompra();

        // Buscar el estado de la compra con idcompraestadotipo = 1
        $compraEstado = $ABMcompraEstado->buscar(['idcompra' => $idCompra, 'idcompraestadotipo' => 1]);

        if (count($compraEstado) > 0) {
            $compraEstado = $compraEstado[0];

            $compraEstadoModificado = [
                'idcompraestado' => $compraEstado->getIdcompraestado(),
                'idcompra' => $idCompra,
                'idcompraestadotipo' => $compraEstado->getObjCompraEstadoTipo()->getIdcompraestadotipo(),
                'cefechaini' => $compraEstado->getCefechaini(),
                'cefechafin' => $fechaFin
            ];

            if ($ABMcompraEstado->modificacion($compraEstadoModificado)) {
                // Insertar una nueva entrada en la tabla compraestado con idcompraestadotipo = 2
                $paramCompraEstado = [
                    'idcompraestado' => null,
                    'idcompra' => $idCompra,
                    'idcompraestadotipo' => 2, // Estado "confirmada"
                    'cefechaini' => $fechaFin,
                    'cefechafin' => null
                ];

                if ($ABMcompraEstado->alta($paramCompraEstado)) {
                    $response['status'] = 'success';
                    $response['message'] = 'operacion exitosa';
                    $compraConfirmada = true;
                    
                    // Obtener el usuario asociado a la compra
                    $idcompra = $idCompra;
                    $compra = new ABMCompra();
                    $objCompra = $compra->buscar(['idcompra' => $idcompra]);
                    if (count($objCompra) > 0) {
                        $objCompra = $objCompra[0];
                        $usuario = $objCompra->getObjUsuario();
                        $toName = $usuario->getUsnombre();
                        $toEmail = $usuario->getUsmail();
                        $response['toName'] = $toName;
                        $response['toEmail'] = $toEmail;
                    }
                } else {
                    $response['status'] = 'error en el 4to condicional';
                    $response['message'] = '!$ABMcompraEstado->alta($paramCompraEstado)';
                }
            } else {
                $response['status'] = 'error en el 3er condicional';
                $response['message'] = '!$compraEstado->modificar()';
            }
        } else {
            $response['status'] = 'error en el 2do condicional';
            $response['message'] = 'count($compraEstado) === 0';
        }
    }
} else {
    $response['status'] = 'error en el 1er condicional';
    $response['message'] = '$carritosIniciados === null';
}

echo json_encode($response);
exit;
?>