<?php
include_once '../../configuracion.php';
header('Content-Type: application/json');
// voy creando el arreglo asociativo que voy a pasar como respuesta en formato JSON
$response = [
    'status' => 'default',
    'message' => 'Parte inicial del action',
    'redirect' => '../Home/carrito.php'
];

/* 
$response = [
    'status' => 'test',
    'message' => 'Esto es una prueba simple',
]; */

// Configurar la zona horaria a Argentina
date_default_timezone_set('America/Argentina/Buenos_Aires');

$session = new Session();
$fechaFin = date('Y-m-d H:i:s');
$idUsuarioActual = $session->getUsuario()->getIdusuario();
//$nombreUsuario = "andres";//$session->getUsuario()->getUsnombre();
//$emailUsuario = "prueba.aemv@gmail.com";//$session->getUsuario()->getUsmail();

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
            $compraEstado->setCefechafin($fechaFin);

            if ($compraEstado->modificar()) {
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
                } else {
                    $response['status'] = 'error en el 4to condicional';
                    $response['message'] = '!$ABMcompraEstado->alta($paramCompraEstado)';
                    // echo "<script>alert('Error al insertar el nuevo estado de la compra'); window.location.href='../Home/carrito.php';</script>";
                }
            } else {
                $response['status'] = 'error en el 3er condicional';
                $response['message'] = '!$compraEstado->modificar()';
                // echo "<script>alert('Error al confirmar la compra'); window.location.href='../Home/carrito.php';</script>";
            }
        } else {
            $response['status'] = 'error en el 2do condicional';
            $response['message'] = 'count($compraEstado) === 0';
            // echo "<script>alert('No se encontró un estado de compra iniciado para la compra con ID: $idCompra'); window.location.href='../Home/carrito.php';</script>";
        }
    }
} else {
    $response['status'] = 'error en el 1er condicional';
    $response['message'] = '$carritosIniciados === null';
    // echo "<script>alert('No hay compras iniciadas'); window.location.href='../Home/carrito.php';</script>";
}
/* 
$json = json_encode($response);
if ($json === false) {
    $response['status'] = 'error';
    $response['message'] = 'Error al codificar el JSON: ' . json_last_error_msg();
    echo json_encode($response);
    exit;
}
echo $json;
exit; */
// var_dump('verificando salidas indeseadas');
echo json_encode($response);
exit;
?>
