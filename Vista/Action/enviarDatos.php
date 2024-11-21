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

$ABMCompra = new ABMCompra;
$ABMUsuario = new ABMUsuario;
$ABMProducto = new ABMProducto;
$ABMCompraitem = new ABMCompraItem;
$ABMUsuarioRol = new ABMUsuarioRol;
$ABMCompraestado = new ABMCompraEstado;

$arrAsocUsuariosRol = [];
$arrUsuariosActivos = [];
$arrCompraEstados = [];
$arrVentas = [];

foreach($ABMUsuarioRol->buscarArray(null) as $usRol){
    $arrAsocUsuariosRol[] = ['idusuario' => $usRol['objUsuario']->getIdusuario(),'idrol' => $usRol['objRol']->getIdrol()];
}

foreach($ABMUsuario->buscarArray(null) as $usuario){
    $arrUsuariosActivos[] = $usuario['usdeshabilitado'];
}

foreach($ABMCompraestado->buscarArray(null) as $CompraEstado){
    $arrCompraEstados[] = $CompraEstado['objCompraEstadoTipo']->getIdcompraestadotipo();
}

$arrVentas = $ABMCompraestado->ventas();

$datos = [
    'compras' => $ABMCompra->buscarArray(null),
    'usuarios' => $ABMUsuario->buscarArray(null),
    'cantUsuariosActivos' => $arrUsuariosActivos, 
    'ventas' => $arrVentas,
    'productos' => $ABMProducto->buscarArray(null),
    'compraitem' => $ABMCompraitem->buscarArray(null),
    'usuariorol' => $arrAsocUsuariosRol,
    'colCompraEstados' => $arrCompraEstados,
    'compraestado' => $ABMCompraestado->buscarArray(null)
];


echo json_encode($datos);
exit;
?>