<?php
include_once '../../configuracion.php';
header('Content-Type: application/json');

$ABMProducto = new ABMProducto;
$session = new Session;

$colProductos = $ABMProducto->buscarArray(null);

$datos = [
    'redirect' => '../Home/stock.php',
    'status' => 'default',
    'colProds' => $colProductos
];

echo json_encode($datos);

?>