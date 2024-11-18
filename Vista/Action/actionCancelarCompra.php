<?php
include_once '../../configuracion.php';
// header('Content-Type: application/json');

// Configurar la zona horaria a Argentina
date_default_timezone_set('America/Argentina/Buenos_Aires');

$session = new Session();
$fechaFin = date('Y-m-d H:i:s');
$idUsuarioActual = $session->getUsuario()->getIdusuario();

// $datos['idcompra'];
$datos = darDatosSubmitted();


$ABMcompraEstado = new ABMCompraEstado;
// llamo a estadoCompraUsuario para obtener los carritos en estado 2 del usuario actual que tengan una cefechafin sin asignar (por defecto)
// $carritosConfirmados = $ABMcompraEstado->estadoCompraUsuario($idUsuarioActual,2,true);

$colCompras = [];

// acá verifico si este action fue llamado desde el cliente (en el botón cancelar de carrito.php) o desde depósito (en el botón de cancelar de ordenes.php)
// if($datos['comprasRol'] === 'deposito'){
    $colCompras = $ABMcompraEstado->buscarComprasConfirmadasSinFinalizar();
// }else{
//     $colCompras = $ABMcompraEstado->buscarCompraIniciadaPorUsuario($idUsuarioActual);
// }

if(count($colCompras) >0 || $colCompras !== null){
    foreach($colCompras as $compra){
        // echo $datos['idcompra']."<br>".$compra->getIdcompra()."<br>";

        // echo gettype($datos['idcompra'])

        if($compra->getIdcompra() == $datos['idcompra']){
            // si el idcompra recibido se encuentra dentro de la coleccion de compras sin finalizar (compraestado = 2), le pongo una fechafin.
            // $compraEstadoBuscado = $datos['comprasRol'] === 'deposito' ? $ABMcompraEstado->buscarArray(['idcompra' => $datos['idcompra']])[1] : $ABMcompraEstado->buscarArray(['idcompra' => $datos['idcompra']])[1];
            $compraEstadoBuscado = $ABMcompraEstado->buscarArray(['idcompra' => $datos['idcompra']])[1];
            // $compraEstadoBuscado['cefechaini'] = $fechaFin;
            // print_r($compraEstadoBuscado['cefechaini']);
            // echo "<br>";
            // print_r($compraEstadoBuscado['objCompraEstadoTipo']->getIdcompraestadotipo());
            $compraEstadoModificado = [
                'idcompraestado' => $compraEstadoBuscado['idcompraestado'],
                'idcompra' => $datos['idcompra'],
                'idcompraestadotipo' => $compraEstadoBuscado['objCompraEstadoTipo']->getIdcompraestadotipo(),
                'cefechaini' => $compraEstadoBuscado['cefechaini'],
                'cefechafin' => $fechaFin
            ];
            // ahora modifico esa compraestado con el idcompra y idcompraestadotipo = 2
            if($ABMcompraEstado->modificacion($compraEstadoModificado)){
                // si se pudo modificar, continúo con la creación de un nueco registro en compraestado con el estado 4 (cancelado)
                $paramCompraEstado = [
                    'idcompraestado' => null,
                    'idcompra' => $datos['idcompra'],
                    'idcompraestadotipo' => 4, #estado Cancelado
                    'cefechaini' => $fechaFin,
                    'cefechafin' => null
                ];
                if($ABMcompraEstado->alta($paramCompraEstado)){
                    echo "<script>alert('Compra Cancelada con éxito'); window.location.href='../Home/carrito.php';</script>";
                }else{
                    echo "<script>alert('Error al insertar el nuevo estado de la compra'); window.location.href='../Home/carrito.php';</script>";
                }
            }else{
                echo "<script>alert('Error al Cancelar la compra'); window.location.href='../Home/carrito.php';</script>";
            }
        }/* else{
            echo "<script>alert('Noo se encontró un estado de compra iniciado para la compra con ID: ".$datos['idcompra']."); window.location.href='../Home/carrito.php';</script>";
        } */
    }
}else{
    /* al cancelar una compra que no contiene productos se redirige a actionCancelarCompra y se muestra de forma literal lo que está en las comillas del echo */
    echo "<script>alert('No hay compras iniciadas'); window.location.href='../Home/carrito.php';</script>";
}

?>