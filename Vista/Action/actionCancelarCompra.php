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

// $datos['idcompra'];
$datos = darDatosSubmitted();


$ABMcompraEstado = new ABMCompraEstado;

$colCompras = [];

// acá verifico si este action fue llamado desde el cliente (en el botón cancelar de carrito.php) o desde depósito (en el botón de cancelar de ordenes.php)
if($datos['comprasRol'] === 'deposito'){
    $colCompras = $ABMcompraEstado->buscarComprasConfirmadasSinFinalizar();
}else{
    $colCompras = $ABMcompraEstado->buscarCompraIniciadaPorUsuario($idUsuarioActual);
}

if(count($colCompras) >0 || $colCompras !== null){
    foreach($colCompras as $compra){

        // si existe $datos['idcompra'] (caso de ordenes.php), lo uso para la comparación y almaceno el booleano. Si no existe (caso de carrito.php), directamente almaceno true.
        $bandera = isset($datos['idcompra']) ? $compra->getIdcompra() == $datos['idcompra'] : true; 

        if($bandera){

            // si no existe, almaceno en la clave el valor de la compra actual.
            if(!isset($datos['idcompra'])){
                $datos['idcompra'] = $compra->getIdcompra();
            }

            // si el idcompra recibido se encuentra dentro de la coleccion de compras sin finalizar (compraestado = 2), le pongo una fechafin.
            // si el dato recibido en $datos['compraRol'] indica que el action se llama desde una vista de depósito, accedo al compraestado = 2. Si se llama desde una vista de cliente, accedo al compraestado = 1
            $compraEstadoBuscado = $datos['comprasRol'] === 'deposito' ? $ABMcompraEstado->buscarArray(['idcompra' => $datos['idcompra']])[1] : $ABMcompraEstado->buscarArray(['idcompra' => $datos['idcompra']])[0];

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
                    $banderita = true;
                    // Obtener el usuario asociado a la compra
                    $idcompra = $datos['idcompra'];
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
                }else{
                    $response['status'] = 'Error en 4to condicional';
                    $response['message'] = '!$ABMcompraEstado->alta($paramCompraEstado)';
                }
            }else{
                $response['status'] = 'Error en 3er condicional';
                $response['message'] = '!$ABMcompraEstado->modificacion($compraEstadoModificado)';
            }
        }else{
            if(!isset($datos['idcompra'])){

                $response['status'] = 'Error en 2do condicional (no existe idcompra)';
            }else{
                $response['status'] = 'Error en 2do condicional (existe idcompra)';
                $response['message'] = '!isset($datos["idcompra"]) || $compra->getIdcompra() != $datos["idcompra"]';
            }
        }
    }
}else{
    $response['status'] = 'Error en 1er condicional';
    $response['message'] = 'colCompras === 0 || colCompras === null';
}

echo json_encode($response);
exit;
?>