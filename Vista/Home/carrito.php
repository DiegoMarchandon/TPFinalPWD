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
                <button type="button" class="btn btn-success btnEnviarCompra">Confirmar Compra</button>
            </form>
            <form id="cancelarCompraForm" action="../Action/actionCancelarCompra.php" method="POST" class=" m-2 d-inline-block">
                <input type="hidden" name="comprasRol" id="hiddenCancelar" value="cliente">
                <button type="button" class="btn btn-danger btnCancelarCompra">Cancelar Compra</button>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
    
        $('.btnEnviarCompra').click(function (event) {
            event.preventDefault();

            $.ajax({
                url: '../Action/actionConfirmarCompra.php',
                method: 'POST',
                data: { comprasRol: "cliente" },
                // jQuery detecta que la respuesta del servidor tiene el tipo application/json en su encabezado, por lo que parsea automáticamente el JSON recibido en un objeto javascript
                success: function (response) {
                    response = typeof response === 'string' ? JSON.parse(response) : response;
                    // alert("alertando respuesta exitosa");
                    if(response.status === 'success'){
                        var toName = response.toName;
                        var toEmail = response.toEmail;
                        var message = 'Su compra ha sido confirmada con éxito. Gracias por su compra.';
                        sendEmail(toName, toEmail, message);
                        alert('Compra confirmada con éxito. Se ha enviado un correo de confirmación.');
                        //window.location.href = response.redirect;
                    } else {
                        alert('Compra no confirmada: ' + response.message);
                    }

                    // Manejo de la respuesta
                    // Aquí puedes agregar lógica adicional para actualizar la página si es necesario
                },
                error: function (xhr, status, error) {
                    alert('Error al enviar la compra: ' + error);
                }
            });
        });

        $('.btnCancelarCompra').click(function (event) {
            event.preventDefault();
            $.ajax({
                url: '../Action/actionCancelarCompra.php',
                method: 'POST',
                data: { comprasRol: "cliente" },
                success: function (response) {
                    // Manejo de la respuesta
                    response = typeof response === 'string' ? JSON.parse(response) : response;
                    // alert("alertando respuesta exitosa");
                    if (response.status === 'success') {
                        var toName = response.toName;
                        var toEmail = response.toEmail;
                        var message = 'Usted cancelo su compra. esperamos que vuelva pronto.';
                        sendEmail(toName, toEmail, message);
                        alert('Compra cancelada con exito. Se ha enviado un correo de confirmación.');
                        //window.location.href = response.redirect;
                    } else {
                        alert('Compra no confirmada: ' + response.message);
                    }
                    // Actualizar la página si es necesario
                },
                error: function (xhr, status, error) {
                    alert('Error al cancelar el carrito: ' + error);
                }
            });
        });
    
    });
</script>
</body>
</html>

<?php
include_once('../estructura/footer.php');
?>