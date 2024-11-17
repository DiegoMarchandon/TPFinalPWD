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
                                        Cantidad: <span class="cantidad"><?php echo htmlspecialchars($item->getCicantidad()); ?></span>
                                        <br>
                                        <span style="color: red;">Stock disponible: <span class="stock-disponible"><?php echo htmlspecialchars($item->getObjProducto()->getProcantstock()); ?></span></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <div class="text-center mt-3">
                                <form action="../Action/actionEnviarCompra.php" method="POST" class="d-inline formEnviarCompra">
                                    <input type="hidden" name="idcompra" value="<?php echo htmlspecialchars($compra->getIdcompra()); ?>">
                                    <button type="button" class="btn btn-success btnEnviarCompra">Enviar Compra</button>
                                </form>
                                <form action="../Action/actionCancelarCompra.php" method="POST" class="d-inline">
                                    <input type="hidden" name="idcompra" value="<?php echo htmlspecialchars($compra->getIdcompra()); ?>">
                                    <button type="button" class="btn btn-danger btnCancelarCompra">Cancelar Compra</button>
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

<script>
    $(document).ready(function () {
        $('.formEnviarCompra').each(function () {
            var form = $(this);
            var stockInsuficiente = false;

            // Verificar el stock disponible antes de habilitar el botón
            form.closest('.card').find('ul li').each(function () {
                var cantidad = parseInt($(this).find('.cantidad').text());
                var stockDisponible = parseInt($(this).find('.stock-disponible').text());
                if (cantidad > stockDisponible) {
                    stockInsuficiente = true;
                    return false; // Salir del bucle
                }
            });

            if (stockInsuficiente) {
                form.find('.btnEnviarCompra').prop('disabled', true);
                form.find('.btnEnviarCompra').after('<p class="text-danger">Debe renovar stock para poder enviar esta compra</p>');
            }
        });

        $('.btnEnviarCompra').click(function (event) {
            event.preventDefault();
            var idCompra = $(this).closest('form').find('input[name="idcompra"]').val();
            $.ajax({
                url: '../Action/actionEnviarCompra.php',
                method: 'POST',
                data: { idcompra: idCompra },
                success: function (response) {
                    response = typeof response === 'string' ? JSON.parse(response) : response;
                    if(response.status === 'success'){
                        alert('Compra enviada');
                        window.location.href = response.redirect;
                    }else{
                        alert(response.message);
                    }
                },
                error: function (xhr, status, error) {
                    alert('Error al enviar la compra: ' + error);
                }
            });
        });

        $('.btnCancelarCompra').click(function () {
            var idCompra = $(this).closest('form').find('input[name="idcompra"]').val();
            $.ajax({
                url: '../Action/actionCancelarCompra.php',
                method: 'POST',
                data: { idcompra: idCompra },
                success: function (response) {
                    response = typeof response === 'string' ? JSON.parse(response) : response;
                    window.location.href = 'ordenes.php';
                    if(response.status === 'success'){
                        alert('Compra cancelada');
                    }else{
                        alert('La compra no se ha podido cancelar');
                    }
                },
                error: function (xhr, status, error) {
                    alert('Error al cancelar la compra: ' + error);
                }
            });
        });
    });
</script>
<?php
include_once('../estructura/footer.php');
?>