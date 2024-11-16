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
    
</body>
</html>