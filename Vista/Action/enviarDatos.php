<?php
include_once '../../configuracion.php';
header('Content-Type: application/json');

$ABMCompra = new ABMCompra;
$ABMUsuario = new ABMUsuario;
$ABMProducto = new ABMProducto;
$ABMCompraitem = new ABMCompraItem;
$ABMUsuarioRol = new ABMUsuarioRol;
$ABMCompraestado = new ABMCompraEstado;

$colUsuariosRol = $ABMUsuarioRol->buscarArray(null);
$colUsuarios = $ABMUsuario->buscarArray(null);
$arrAsocUsuariosRol = [];
$arrUsuariosActivos = [];
foreach($colUsuariosRol as $usRol){
    $arrAsocUsuariosRol[] = ['idusuario' => $usRol['objUsuario']->getIdusuario(),'idrol' => $usRol['objRol']->getIdrol()];
}

foreach($colUsuarios as $usuario){
    $arrUsuariosActivos[] = $usuario['usdeshabilitado'];
}

$datos = [
    'compras' => $ABMCompra->buscarArray(null),
    'usuarios' => $ABMUsuario->buscarArray(null),
    'cantUsuariosActivos' => $arrUsuariosActivos, 
    'productos' => $ABMProducto->buscarArray(null),
    'compraitem' => $ABMCompraitem->buscarArray(null),
    'usuariorol' => $arrAsocUsuariosRol,
    'compraestado' => $ABMCompraestado->buscarArray(null)
];


echo json_encode($datos);
exit;
?>