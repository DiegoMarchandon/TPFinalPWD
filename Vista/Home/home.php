<?php
include_once '../../configuracion.php';
include_once('../estructura/headerSeguro.php');

//$session = new Session();
$idUsuario = $session->getUsuario()->getIdusuario();

$ABMCompra = new ABMCompra();
$comprasUsuario = $ABMCompra->buscar(['idusuario' => $idUsuario]);

$ABMCompraEstado = new ABMCompraEstado();
$ABMCompraItem = new ABMCompraItem();

$comprasPendientes = [];
$comprasEnviadas = [];
$comprasCanceladas = [];

foreach ($comprasUsuario as $compra) {
    $compraEstado = $ABMCompraEstado->buscar(['idcompra' => $compra->getIdcompra()]);
    if (count($compraEstado) > 0) {
        foreach ($compraEstado as $estado) {
            $estadoTipo = $estado->getObjCompraEstadoTipo()->getIdcompraestadotipo();
            if ($estadoTipo == 2) {
                $comprasPendientes[] = $compra;
            } elseif ($estadoTipo == 3) {
                $comprasEnviadas[] = $compra;
            } elseif ($estadoTipo == 4) {
                $comprasCanceladas[] = $compra;
            }
        }
    }
}

function mostrarCompras($compras, $ABMCompraItem) {
    foreach ($compras as $compra) {
        echo '<div class="col-12 mb-4">';
        echo '<div class="card">';
        echo '<div class="card-header">';
        echo '<h6 class="card-subtitle mb-2 text-muted">Fecha de Compra: ' . htmlspecialchars($compra->getCofecha()) . '</h6>';
        echo '</div>';
        echo '<div class="card-body">';
        echo '<h6>Productos:</h6>';
        echo '<ul>';
        $compraItems = $ABMCompraItem->buscar(['idcompra' => $compra->getIdcompra()]);
        foreach ($compraItems as $item) {
            echo '<li>';
            echo 'Producto: ' . htmlspecialchars($item->getObjProducto()->getPronombre()) . ', ';
            echo 'Cantidad: ' . htmlspecialchars($item->getCicantidad());
            echo '</li>';
        }
        echo '</ul>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}
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