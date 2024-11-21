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

if (isset($_POST['idproducto'])) {
    $session = new Session;
    // id del usuario actual
    $idUsuario = $session->getUsuario()->getIdusuario();
    $idProducto = $_POST['idproducto'];
    $ABMcompraitem = new ABMCompraItem;
    $ABMcompra = new ABMCompra;
    // primero, verifico que existan compraitem con ese idproducto
    if(isset($ABMcompraitem->buscarArray(['idproducto' => $idProducto])[0])){
        
        $buscarCompraItem = $ABMcompraitem->buscarArray(['idproducto' => $idProducto])[0];

        // una vez obtengo el compraitem, uso su clave idcompra
        $idcompra = $buscarCompraItem['objCompra']->getIdcompra();

        if(count($ABMcompra->buscar(['idcompra' => $idcompra, 'idusuario' => $idUsuario])) > 0){
            
            $estado = $ABMcompraitem->estadoCompraItem($idProducto);
            echo $estado; // Devuelve el estado como respuesta
        }

    }else{
        echo "no existe el compraitem con ese idproducto";
    }

}

?>