<?php
include_once '../../configuracion.php';
include_once('../estructura/headerSeguro.php');

// primero verifico si el usuario logueado tiene carritos abiertos
// obtenemos el id del usuario logueado
$idUsuarioActual = $session->getUsuario()->getIdusuario();

$ABMcompraEstado = new ABMCompraEstado;
$carritosIniciados = $ABMcompraEstado->buscarCompraIniciadaPorUsuario($idUsuarioActual);

// print_r($carritosIniciados);

$ABMcompraitem = new ABMCompraItem;

$productosCarrito = [];

if($carritosIniciados !== null){
    // echo "<br>hay compras iniciadas<br>";
    foreach($carritosIniciados as $compraIni){
        // del compraitem, obtengo la cantidad de elementos comprados
        $compraItem = $ABMcompraitem->buscarArray(['idcompra' => $compraIni->getIdcompra()]);

        if (!empty($compraItem) && isset($compraItem[0]['objProducto'])) {
            $productoCarrito = [
                'Nombre' => $compraItem[0]['objProducto']->getPronombre(),
                'Detalle' => $compraItem[0]['objProducto']->getProdetalle(),
                'Precio' => $compraItem[0]['objProducto']->getPrecioprod(),
                'Cantidad' => $compraItem[0]['cicantidad']
            ];

            $productosCarrito[] = $productoCarrito;
        }else{
            echo "<br>No se encontraron productos para la compra con ID: " . $compraIni->getIdcompra() . "<br>";
        }
    }

}else{
    echo "<br>no hay compras iniciadas<br>";
}

// print_r($productosCarrito);

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
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No hay productos en el carrito.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
    /* document.addEventListener('DOMContentLoaded', function() {
        // let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        let carrito = 
        let carritoTable = document.getElementById('carritoTable').getElementsByTagName('tbody')[0];
        let emptyMessage = document.getElementById('emptyMessage');

        if (carrito.length > 0) {
            emptyMessage.style.display = 'none';
            carrito.forEach(function(producto) {
                let row = carritoTable.insertRow();
                row.insertCell(0).innerHTML = `<img src="${producto.prodIMG}" alt="${producto.prodNombre}" width="50">`;
                row.insertCell(1).innerText = producto.prodNombre;
                row.insertCell(2).innerText = producto.prodDetalle;
                row.insertCell(3).innerText = `$${producto.prodPrecio}.00`;
                row.insertCell(4).innerText = producto.prodCantSelec;
            });
        } else {
            carritoTable.style.display = 'none';
        }
    }); */
</script>
</body>
</html>

<?php
include_once('../estructura/footer.php');
?>