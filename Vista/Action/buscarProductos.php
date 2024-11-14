<?php
include_once '../../configuracion.php';

$ABMProductos = new ABMProducto;

$colProds = json_encode($ABMProductos->buscarArray(null));
echo $colProds;
?>