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
$arrVentas = ['estadoCompras' =>[],'montoVentas' =>[]];

foreach($ABMUsuarioRol->buscarArray(null) as $usRol){
    $arrAsocUsuariosRol[] = ['idusuario' => $usRol['objUsuario']->getIdusuario(),'idrol' => $usRol['objRol']->getIdrol()];
}

foreach($ABMUsuario->buscarArray(null) as $usuario){
    $arrUsuariosActivos[] = $usuario['usdeshabilitado'];
}

foreach($ABMCompraestado->buscarArray(null) as $CompraEstado){
    $arrCompraEstados[] = $CompraEstado['objCompraEstadoTipo']->getIdcompraestadotipo();
}

/* 
foreach($ABMCompraestado->buscarArray(null) as $arrCompraEstado){
    $estadoCompra = $arrCompraEstado['objCompraEstadoTipo'];
    $fechaEstado = $arrCompraEstado['cefechaini'];
    // si el compraestado es 3 (enviado) o 4(cancelado)
    if($estadoCompra === 3 || $estadoCompra === 4){

        // en la primera clave de $arrVentas, guardo los estados de las compras
        $arrVentas['estadoCompras'][] = ['estadoCompra' => $estadoCompra, 'fecha' => $fechaEstado];
        
        // si el estado es enviado:
        if($estadoCompra === 3){

            // extraigo el idcompraestado para usarlo en comparaciones
            $IDCompra = $estadoCompra['idcompra'];

            // recorro los compraitem para poder extraer la cantidad vendida
            foreach($ABMCompraitem->buscarArray(null) as $arrCompraitem){

                // si el IDCompra del compraestado coincide con el compraitem recorrido:
                if($arrCompraitem['objCompra']->getIdcompra() == $IDCompra){
                    // extraigo la cantidad del item
                    $cantidad = $arrCompraitem['cicantidad'];
                    

                    $arrVentas['montoVentas'][] = ['fechaVenta' => $fechaEstado,'montoVenta'=>]
                }

            }
        }
    }
} */

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