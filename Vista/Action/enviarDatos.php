<?php
include_once '../../configuracion.php';
header('Content-Type: application/json');

$ABMCompra = new ABMCompra;
$ABMUsuario = new ABMUsuario;
$ABMProducto = new ABMProducto;
$ABMCompraitem = new ABMCompraItem;
$ABMUsuarioRol = new ABMUsuarioRol;
$ABMCompraestado = new ABMCompraEstado;

// $colUsuariosRol = $ABMUsuarioRol->buscarArray(null);
// $colUsuarios = $ABMUsuario->buscarArray(null);
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