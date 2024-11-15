<?php
include_once '../../configuracion.php';
include_once('../estructura/headerSeguro.php');

// Configurar la zona horaria a Argentina
date_default_timezone_set('America/Argentina/Buenos_Aires');

$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $idUsuario = $_SESSION['idusuario']; // Obtener el ID del usuario de la sesión
    $fechaCompra = date('Y-m-d H:i:s');

    // Crear una nueva instancia de ABMCompra
    $abmCompra = new ABMCompra();

    // Crear los parámetros para la nueva compra
    $paramCompra = [
        'idcompra' => null,
        'cofecha' => $fechaCompra,
        'idusuario' => $idUsuario
    ];

    // Insertar la compra utilizando ABMCompra
    if ($abmCompra->alta($paramCompra)) {
        
        // Obtener el ID de la compra recién creada
        $idCompra = $abmCompra->buscar(['cofecha' => $fechaCompra, 'idusuario' => $idUsuario])[0]->getIdcompra();

        // Crear una nueva instancia de ABMCompraEstado
        $abmCompraEstado = new ABMCompraEstado();

        // Crear los parámetros para el nuevo estado de la compra
        $paramCompraEstado = [
            'idcompraestado' => null,
            'idcompra' => $idCompra,
            'idcompraestadotipo' => 1, // Estado inicial "iniciada"
            'cefechaini' => $fechaCompra,
            'cefechafin' => null
        ];

        // Insertar el estado de la compra utilizando ABMCompraEstado
        if ($abmCompraEstado->alta($paramCompraEstado)) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al insertar el estado de la compra"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Error al insertar la compra"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Datos no válidos"]);
}

include_once '../estructura/footer.php';
?>