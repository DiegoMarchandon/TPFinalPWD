<?php
include_once '../../configuracion.php';
include_once('../estructura/headerSeguro.php');

$ABMCompra = new ABMCompra;
$ABMcompraestado = new ABMCompraEstado;
$colCompras = $ABMCompra->buscarArray(null);

foreach($colCompras as $compra){

    // obtengo el compraEstado de la compra
    $compraEstado = $ABMcompraestado->buscarArray(["idcompra" => $compra['idcompra']])[0];

    // obtengo el compraestadoTipo del compraEstado
    $compraEstadoTipo = $compraEstado['objCompraEstadoTipo']->getIdcompraestadotipo();
    

    echo "<br>----<br>";
    // si el compraEstadoTipo obtenido a partir de la compra es 1, imprimo la compra
    if($compraEstadoTipo === 1){

        print_r($compra);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ordenes</title>
</head>
<body>
<table border="1" class="table table-bordered">
        <thead>
            <tr>
                <th>ID Compra</th>
                <th>Fecha de Compra</th>
                <th>ID Usuario</th>
                <th>Nombre Usuario</th>
                <th>Email Usuario</th>
                <th>Estado de la Compra</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($colCompras) > 0): ?>
                <?php foreach ($colCompras as $compra): ?>
                    <?php 
                    // Obtengo el compraEstado de la compra
                    $compraEstado = $ABMcompraestado->buscarArray(["idcompra" => $compra['idcompra']])[0];
                    // Obtengo el compraestadoTipo del compraEstado
                    $compraEstadoTipo = $compraEstado['objCompraEstadoTipo']->getIdcompraestadotipo();
                    ?>
                    <?php if ($compraEstadoTipo === 1): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($compra['idcompra']); ?></td>
                            <td><?php echo htmlspecialchars($compra['cofecha']); ?></td>
                            <td><?php echo htmlspecialchars($compra['objUsuario']->getIdusuario()); ?></td>
                            <td><?php echo htmlspecialchars($compra['objUsuario']->getUsnombre()); ?></td>
                            <td><?php echo htmlspecialchars($compra['objUsuario']->getUsmail()); ?></td>
                            <td><?php echo htmlspecialchars($compraEstadoTipo); ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No hay compras para mostrar.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>