<?php
include_once '../../configuracion.php';
include_once('../estructura/headerSeguro.php');

//$session = new Session();
$idUsuario = $session->getUsuario()->getIdusuario();

$ABMCompra = new ABMCompra();
$comprasUsuario = $ABMCompra->buscar(['idusuario' => $idUsuario]);

$ABMCompraEstado = new ABMCompraEstado();
$ABMCompraItem = new ABMCompraItem();

$comprasPendientes = $ABMCompra->obtenerComprasPorEstado($idUsuario, 2); //2 son las confirmadas y pasadas a deposito
$comprasEnviadas = $ABMCompra->obtenerComprasPorEstado($idUsuario, 3); //3 son las enviadas
$comprasCanceladas = $ABMCompra->obtenerComprasPorEstado($idUsuario, 4); //son las canceladas tanto por el mismo cliente como por el deposito

?>

<div class="container mt-4">
    <h1 class="text-center mb-4">Mis Compras</h1>

    <h2 class="text-center mb-4">Mis Compras Pendientes de Respuesta</h2>
    <div class="row">
        <?php if (count($comprasPendientes) > 0): ?>
            <?php mostrarCompras($comprasPendientes, $ABMCompraItem); ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center">No tienes compras pendientes de respuesta.</div>
            </div>
        <?php endif; ?>
    </div>

    <h2 class="text-center mb-4">Mis Compras Enviadas</h2>
    <div class="row">
        <?php if (count($comprasEnviadas) > 0): ?>
            <?php mostrarCompras($comprasEnviadas, $ABMCompraItem); ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center">No tienes compras enviadas.</div>
            </div>
        <?php endif; ?>
    </div>

    <h2 class="text-center mb-4">Mis Compras Canceladas</h2>
    <div class="row">
        <?php if (count($comprasCanceladas) > 0): ?>
            <?php mostrarCompras($comprasCanceladas, $ABMCompraItem); ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center">No tienes compras canceladas.</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
include_once('../estructura/footer.php');
?>