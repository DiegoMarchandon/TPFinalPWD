<?php
include_once '../../configuracion.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idproducto']) && isset($_POST['cantidad'])) {
    $idProducto = $_POST['idproducto'];
    $cantidad = $_POST['cantidad'];

    // Obtener el producto
    $abmProducto = new ABMProducto();
    $producto = $abmProducto->buscar(['idproducto' => $idProducto])[0];

    // Agregar el producto al carrito
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    // Verificar si el producto ya esta en el carrito
    $productoEnCarrito = false;
    foreach ($_SESSION['carrito'] as &$item) {
        if ($item['idproducto'] == $idProducto) {
            $item['cantidad'] += $cantidad;
            $productoEnCarrito = true;
            break;
        }
    }

    // Si el producto no esta en el carrit se agrega
    if (!$productoEnCarrito) {
        $_SESSION['carrito'][] = [
            'idproducto' => $producto->getIdproducto(),
            'pronombre' => $producto->getPronombre(),
            'prodetalle' => $producto->getProdetalle(),
            'precioprod' => $producto->getPrecioprod(),
            'cantidad' => $cantidad
        ];
    }

    echo 'Producto agregado al carrito';
} else {
    echo 'Error al agregar el producto al carrito';
}
?>