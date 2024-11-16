<?php
include_once '../../configuracion.php';
if (isset($_POST['idproducto'])) {
    $idProducto = $_POST['idproducto'];
    $ABMcompraitem = new ABMCompraItem();
    $estado = $ABMcompraitem->estadoCompraItem($idProducto);
    echo $estado; // Devuelve el estado como respuesta
}

?>