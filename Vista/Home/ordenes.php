<?php
include_once '../../configuracion.php';
include_once('../estructura/headerSeguro.php');

$ABMCompraEstado = new ABMCompraEstado();
$comprasConfirmadasSinFinalizar = $ABMCompraEstado->buscarComprasConfirmadasSinFinalizar();

$ABMCompraItem = new ABMCompraItem();

?>

<div class="container mt-4">
    <h1 class="text-center mb-4">Ordenes Confirmadas Sin Finalizar</h1>
    <div class="row">
        <?php if (count($comprasConfirmadasSinFinalizar) > 0): ?>
            <?php foreach ($comprasConfirmadasSinFinalizar as $compra): ?>
                <?php
                $usuario = $compra->getObjUsuario();
                $compraItems = $ABMCompraItem->buscar(['idcompra' => $compra->getIdcompra()]);
                ?>
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title"><?php echo htmlspecialchars($usuario->getUsnombre()); ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted"><?php echo htmlspecialchars($compra->getCofecha()); ?></h6>
                        </div>
                        <div class="card-body">
                            <h6>Productos:</h6>
                            <ul>
                                <?php foreach ($compraItems as $item): ?>
                                    <li>
                                        Producto: <?php echo htmlspecialchars($item->getObjProducto()->getPronombre()); ?>,
                                        Cantidad: <?php echo htmlspecialchars($item->getCicantidad()); ?>
                                        <br>
                                        <span style="color: red;">Stock disponible: <?php echo htmlspecialchars($item->getObjProducto()->getProcantstock()); ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <div class="text-center mt-3">
                                <form action="../Action/actionEnviarCompra.php" method="POST" class="d-inline">
                                    <input type="hidden" name="idcompra" value="<?php echo htmlspecialchars($compra->getIdcompra()); ?>">
                                    <button type="submit" class="btn btn-success">Enviar Compra</button>
                                </form>
                                <form action="../Action/actionCancelarCompra.php" method="POST" class="d-inline">
                                    <input type="hidden" name="idcompra" value="<?php echo htmlspecialchars($compra->getIdcompra()); ?>">
                                    <button type="submit" class="btn btn-danger">Cancelar Compra</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center">No hay compras confirmadas sin finalizar.</div>
            </div>
        <?php endif; ?>
    </div>
</div>


<?php
include_once('../estructura/footer.php');
?>