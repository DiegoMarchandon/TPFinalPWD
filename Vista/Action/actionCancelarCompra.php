<?php
include_once '../../configuracion.php';

// Configurar la zona horaria a Argentina
date_default_timezone_set('America/Argentina/Buenos_Aires');

$session = new Session();
$fechaFin = date('Y-m-d H:i:s');
$idUsuarioActual = $session->getUsuario()->getIdusuario();

$ABMcompraEstado = new ABMCompraEstado;
$carritosIniciados = $ABMcompraEstado->buscarCompraIniciadaPorUsuario($idUsuarioActual);

if ($carritosIniciados !== null) {
    foreach ($carritosIniciados as $compraIniciada) {
        $idCompra = $compraIniciada->getIdcompra();

        // Buscar el estado de la compra con idcompraestadotipo = 1
        $compraEstado = $ABMcompraEstado->buscar(['idcompra' => $idCompra, 'idcompraestadotipo' => 1]);

        if (count($compraEstado) > 0) {
            $compraEstado = $compraEstado[0];
            $compraEstado->setCefechafin($fechaFin);

            if ($compraEstado->modificar()) {
                // Insertar una nueva entrada en la tabla compraestado con idcompraestadotipo = 4
                $paramCompraEstado = [
                    'idcompraestado' => null,
                    'idcompra' => $idCompra,
                    'idcompraestadotipo' => 4, // Estado "confirmada"
                    'cefechaini' => $fechaFin,
                    'cefechafin' => $fechaFin
                ];

                if ($ABMcompraEstado->alta($paramCompraEstado)) {
                    echo "<script>alert('Compra Cancelada con éxito'); window.location.href='../Home/carrito.php';</script>";
                } else {
                    echo "<script>alert('Error al insertar el nuevo estado de la compra'); window.location.href='../Home/carrito.php';</script>";
                }
            } else {
                echo "<script>alert('Error al Cancelar la compra'); window.location.href='../Home/carrito.php';</script>";
            }
        } else {
            echo "<script>alert('No se encontró un estado de compra iniciado para la compra con ID: $idCompra'); window.location.href='../Home/carrito.php';</script>";
        }
    }
} else {
    echo "<script>alert('No hay compras iniciadas'); window.location.href='../Home/carrito.php';</script>";
}
?>