<?php
include_once '../../configuracion.php';

session_start();

$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];

?>

<?php include_once("../estructura/headerSeguro.php"); ?>
<div class="container mt-5">
    <h1 class="text-center">Carrito de Compras</h1>
    <div class="row">
        <?php if (empty($carrito)): ?>
            <p class="text-center">El carrito está vacío.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Detalle</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($carrito as $item): ?>
                        <tr>
                            <td><?php echo $item['pronombre']; ?></td>
                            <td><?php echo $item['prodetalle']; ?></td>
                            <td>$<?php echo $item['precioprod']; ?></td>
                            <td><?php echo $item['cantidad']; ?></td>
                            <td>$<?php echo $item['precioprod'] * $item['cantidad']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
<?php include_once("../estructura/footer.php"); ?>