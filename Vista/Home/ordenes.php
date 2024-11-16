<?php
include_once '../../configuracion.php';
include_once('../estructura/headerSeguro.php');

$ABMCompra = new ABMCompra;
$ABMcompraestado = new ABMCompraEstado;
$colCompras = $ABMCompra->buscarArray(null);

// arreglo que almacenará las compras que tengan un estadotipo de 2
$colComprasAceptadas = [];
// recorro a coleccion de compras
foreach($colCompras as $compra){

    // obtengo los colCompraEstados de la compra
    $colCompraEstados = $ABMcompraestado->buscarArray(["idcompra" => $compra['idcompra']]);

    // recorro la colección de compraEstados
    foreach($colCompraEstados as $compraEstado){
        // echo "<br>----<br>";
        // echo $compraEstado['objCompraEstadoTipo']->getIdcompraestadotipo();
        // echo "<br>----<br>";
        if($compraEstado['objCompraEstadoTipo']->getIdcompraestadotipo() === 2){

            $compra['compraestado'] = $compraEstado['objCompraEstadoTipo']->getIdcompraestadotipo();
            // cada compra que tiene un estado de 2 tuvo que tener un estado de 1.
            $colComprasAceptadas[] = $compra;
        }
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
<div class="container mt-4">
    <h1 class="text-center mb-4">Ordenes realizadas</h1>
    <p class=" text-center text-muted"><b>Estados de compra:</b>    Iniciado(1), aceptado(2), enviado(3), cancelado(4)</p>
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
                <?php if (count($colComprasAceptadas) > 0): ?>
                    <?php foreach ($colComprasAceptadas as $compra): ?>
                        
                        <tr>
                            <td><?php echo htmlspecialchars($compra['idcompra']); ?></td>
                            <td><?php echo htmlspecialchars($compra['cofecha']); ?></td>
                            <td><?php echo htmlspecialchars($compra['objUsuario']->getIdusuario()); ?></td>
                            <td><?php echo htmlspecialchars($compra['objUsuario']->getUsnombre()); ?></td>
                            <td><?php echo htmlspecialchars($compra['objUsuario']->getUsmail()); ?></td>
                            <td><?php echo htmlspecialchars($compra['compraestado']); ?></td>
                        </tr>
                            
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No hay compras para mostrar.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
</div>
</body>
</html>