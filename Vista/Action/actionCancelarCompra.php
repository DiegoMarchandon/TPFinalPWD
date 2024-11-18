<?php
include_once '../../configuracion.php';
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

// $datos['idcompra'];
$datos = darDatosSubmitted();


$ABMcompraEstado = new ABMCompraEstado;
// llamo a estadoCompraUsuario para obtener los carritos en estado 2 del usuario actual que tengan una cefechafin sin asignar (por defecto)
// $carritosConfirmados = $ABMcompraEstado->estadoCompraUsuario($idUsuarioActual,2,true);

$colCompras = [];

// acá verifico si este action fue llamado desde el cliente (en el botón cancelar de carrito.php) o desde depósito (en el botón de cancelar de ordenes.php)
if($datos['comprasRol'] === 'deposito'){
    $colCompras = $ABMcompraEstado->buscarComprasConfirmadasSinFinalizar();
}else{
    $colCompras = $ABMcompraEstado->buscarCompraIniciadaPorUsuario($idUsuarioActual);
}

if(count($colCompras) >0 || $colCompras !== null){
    foreach($colCompras as $compra){
        // echo $datos['idcompra']."<br>".$compra->getIdcompra()."<br>";

        // echo gettype($datos['idcompra'])

        if(isset($datos['idcompra']) && $compra->getIdcompra() == $datos['idcompra']){
            /* if(!isset($datos['idcompra'])){
                echo "<script>alert('no existe el idcompra (carrito.php)')</script>";
            }else{
                echo "<script>alert('existe el idcompra (carrito.php)')</script>";
            } */

            // si el idcompra recibido se encuentra dentro de la coleccion de compras sin finalizar (compraestado = 2), le pongo una fechafin.
            // si el dato recibido en $datos['compraRol'] indica que el action se llama desde una vista de depósito, accedo al compraestado = 2. Si se llama desde una vista de cliente, accedo al compraestado = 1
            $compraEstadoBuscado = $datos['comprasRol'] === 'deposito' ? $ABMcompraEstado->buscarArray(['idcompra' => $datos['idcompra']])[1] : $ABMcompraEstado->buscarArray(['idcompra' => $datos['idcompra']])[0];
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
                    $response['status'] = 'success';
                    $response['message'] = 'operacion exitosa';
                    // echo "<script>alert('Compra Cancelada con éxito'); window.location.href='../Home/carrito.php';</script>";
                }else{
                    $response['status'] = 'Error en 4to condicional';
                    $response['message'] = '!$ABMcompraEstado->alta($paramCompraEstado)';
                    // echo "<script>alert('Error al insertar el nuevo estado de la compra'); window.location.href='../Home/carrito.php';</script>";
                }
            }else{
                $response['status'] = 'Error en 3er condicional';
                $response['message'] = '!$ABMcompraEstado->modificacion($compraEstadoModificado)';
                // echo "<script>alert('Error al Cancelar la compra'); window.location.href='../Home/carrito.php';</script>";
            }
        }else{
            $response['status'] = 'Error en 2do condicional';
            $response['message'] = '!isset($datos["idcompra"]) || $compra->getIdcompra() != $datos["idcompra"]';
            // echo "<script>alert('Noo se encontró un estado de compra iniciado para la compra con ID: ".$datos['idcompra']."); window.location.href='../Home/carrito.php';</script>";
        }
    }
}else{
    $response['status'] = 'Error en 1er condicional';
    $response['message'] = 'colCompras === 0 || colCompras === null';
    /* al cancelar una compra que no contiene productos se redirige a actionCancelarCompra y se muestra de forma literal lo que está en las comillas del echo */
    // echo "<script>alert('No hay compras iniciadas'); window.location.href='../Home/carrito.php';</script>";
}

echo json_encode($response);
exit;

?>