<?php
include_once '../../configuracion.php';
include_once('../estructura/headerSeguro.php');

// primero verifico si el usuario logueado tiene carritos abiertos
// obtenemos el id del usuario logueado
$idUsuarioActual = $session->getUsuario()->getIdusuario();

$ABMcompraEstado = new ABMCompraEstado;
$carritosIniciados = $ABMcompraEstado->buscarCompraIniciadaPorUsuario($idUsuarioActual);
//$carritosIniciados = $compraIniciada[0]->getIdcompra();
// print_r($carritosIniciados);

$ABMcompraitem = new ABMCompraItem;

$productosCarrito = [];
$totalCarrito = 0;

if ($carritosIniciados !== null) {
    foreach ($carritosIniciados as $compraIni) {
        // del compraitem, obtengo la cantidad de elementos comprados
        $compraItems = $ABMcompraitem->buscar(['idcompra' => $compraIni->getIdcompra()]);

        foreach ($compraItems as $compraItem) {
            if (null !== $compraItem->getObjProducto()) {
                $precioTotalProducto = $compraItem->getObjProducto()->getPrecioprod() * $compraItem->getCicantidad();
                $productoCarrito = [
                    'Nombre' => $compraItem->getObjProducto()->getPronombre(),
                    'Detalle' => $compraItem->getObjProducto()->getProdetalle(),
                    'Precio' => $precioTotalProducto,
                    'Cantidad' => $compraItem->getCicantidad()
                ];

                $productosCarrito[] = $productoCarrito;
                $totalCarrito += $precioTotalProducto;
            } else {
                echo "<br>No se encontraron productos para la compra con ID: " . $compraIni->getIdcompra() . "<br>";
            }
        }
    }
} else {
    //echo "<br>No hay compras iniciadas<br>";
}
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
                <button type="submit" class="btn btn-success">Confirmar Compra</button>
            </form>
            <form id="cancelarCompraForm" action="../Action/actionCancelarCompra.php" method="POST" class=" m-2 d-inline-block">
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