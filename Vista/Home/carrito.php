<?php
include_once '../../configuracion.php';
include_once('../estructura/headerSeguro.php');

// primero verifico si el usuario logueado tiene carritos abiertos
// obtenemos el id del usuario logueado
$idUsuarioActual = $session->getUsuario()->getIdusuario();

$ABMcompraitem = new ABMCompraItem;

$resultadoCarrito = $ABMcompraitem->obtenerProductosCarrito($idUsuarioActual);
$productosCarrito = $resultadoCarrito['productosCarrito'];
$totalCarrito = $resultadoCarrito['totalCarrito'];
?>

<div class="container mt-4">
    <h1 class="text-center mb-4">Mis Productos Pendientes</h1>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered" id="carritoTable">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Detalle</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (count($productosCarrito) > 0): ?>
                    <?php foreach ($productosCarrito as $prodCarrito): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($prodCarrito['Nombre']); ?></td>
                            <td><?php echo htmlspecialchars($prodCarrito['Detalle']); ?></td>
                            <td><?php echo '$' . htmlspecialchars($prodCarrito['Precio']); ?></td>
                            <td><?php echo htmlspecialchars($prodCarrito['Cantidad']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="2" class="text-right"><strong>Total:</strong></td>
                        <td colspan="2"><?php echo '$' . htmlspecialchars($totalCarrito); ?></td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No hay productos en el carrito.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-12 text-center">
            <form id="confirmarCompraForm" action="../Action/actionConfirmarCompra.php" method="POST" class=" m-2 d-inline-block">
                <input type="hidden" name="comprasRol" id="hiddenConfirmar" value="cliente">
                <button type="submit" class="btn btn-success">Confirmar Compra</button>
            </form>
            <form id="cancelarCompraForm" action="../Action/actionCancelarCompra.php" method="POST" class=" m-2 d-inline-block">
                <input type="hidden" name="comprasRol" id="hiddenCancelar" value="cliente">
                <button type="submit" class="btn btn-danger">Cancelar Compra</button>
            </form>
        </div>
    </div>
</div>

<script>
    
</script>
</body>
</html>

<?php
include_once('../estructura/footer.php');
?>