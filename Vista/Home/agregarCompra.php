<?php
include_once '../../configuracion.php';
include_once '../estructura/headerSeguro.php';
//include_once '../../Control/ABMCompra.php';
//include_once '../../Modelo/Compra.php';
//include_once '../../Control/ABMCompraEstado.php';
//include_once '../../Modelo/CompraEstado.php';
//include_once '../../Control/ABMCompraItem.php';
//include_once '../../Modelo/CompraItem.php';

$session = new Session;

// Configurar la zona horaria a Argentina
date_default_timezone_set('America/Argentina/Buenos_Aires');
// lee los archivos JSON enviados a través de una solicitud HTTP, los decodifica y los convierte en un arreglo asociativo PHP.
// php://input se usa para leer el cuerpo de la solicitud sin procesar
$data = json_decode(file_get_contents('php://input'), true);

print_r($data);

if ($data) {
    $idUsuario = $session->getUsuario()->getIdusuario(); // Obtener el ID del usuario de la sesión
    $fechaCompra = date('Y-m-d H:i:s');

    // Crear una nueva instancia de ABMCompraEstado
    $abmCompraEstado = new ABMCompraEstado();

    // Verificar si el usuario ya tiene una compra "iniciada"
    $compraIniciada = $abmCompraEstado->buscarCompraIniciadaPorUsuario($idUsuario);

    if ($compraIniciada === null) {
        // una nueva instancia de ABMCompra
        $abmCompra = new ABMCompra();

        // Crear los parametros para la nueva compra
        $paramCompra = [
            'idcompra' => null,
            'cofecha' => $fechaCompra,
            'idusuario' => $idUsuario
        ];

        // Insertar la compra utilizando ABMCompra
        if ($abmCompra->alta($paramCompra)) {
            // Obtener el ID de la compra recien creada
            $idCompra = $abmCompra->buscar(['cofecha' => $fechaCompra, 'idusuario' => $idUsuario])[0]->getIdcompra();

            // Crear los parametros para el nuevo estado de la compra
            $paramCompraEstado = [
                'idcompraestado' => null,
                'idcompra' => $idCompra,
                'idcompraestadotipo' => 1, // Estado inicial "iniciada"
                'cefechaini' => $fechaCompra,
                'cefechafin' => null
            ];

            // Insertar el estado de la compra utilizando ABMCompraEstado
            if ($abmCompraEstado->alta($paramCompraEstado)) {
                // Insertar los elementos del carrito en la tabla compraitem
                $abmCompraItem = new ABMCompraItem();

                $paramCompraItem = [
                    'idcompraitem' => null,
                    'idproducto' => $data['idproducto'], //id del producto
                    'idcompra' => $idCompra,
                    'cicantidad' => $data['prodCantSelec'] // La cantidad seleccionada por el cliente
                ];

                if ($abmCompraItem->alta($paramCompraItem)) {
                    echo json_encode(["status" => "success"]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Error al insertar el ítem de la compra"]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "Error al insertar el estado de la compra"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Error al insertar la compra"]);
        }
     } else {
        
        // Extraer el idcompra de la compra iniciada
        $idCompraIniciada = $compraIniciada[0]->getIdcompra();

        // Insertar un nuevo CompraItem en la compra existente
        $abmCompraItem = new ABMCompraItem();

        $paramCompraItem = [
            'idcompraitem' => null,
            'idproducto' => $data['idproducto'], //id del producto
            'idcompra' => $idCompraIniciada,
            'cicantidad' => $data['prodCantSelec'] // La cantidad seleccionada por el cliente
        ];

        if ($abmCompraItem->alta($paramCompraItem)) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al insertar el ítem de la compra"]);
        }
        // echo json_encode(["status" => "error", "message" => "YA TENES UNA COMPRA INICIADA"]);
     }
} else {
    echo json_encode(["status" => "error", "message" => "Datos no válidos"]);
}

include_once '../estructura/footer.php';
?>