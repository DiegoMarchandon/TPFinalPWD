<?php
include_once '../../configuracion.php';

// uso el idcompra obtenido para obtener los compraitem
$datos = darDatosSubmitted();

$ABMcompraitem = new ABMCompraItem;
$ABMproducto = new ABMProducto;
$colCompraItems = $ABMcompraitem->buscarArray(['idcompra' => $datos['idcompra']]);

foreach($colCompraItems as $compraitem){

    // almaceno la cantidad a descontar del producto:
    $cantDescontada = $compraitem['cicantidad'];
    echo "<br>---<br>";
    print_r($compraitem['objProducto']->getIdproducto());
    echo "<br>---<br>";

    $stockActualizado = $compraitem['objProducto']->getProcantstock() - $cantDescontada;

    // modifico la cantidad descontada del producto;
    $param = [
        'idproducto' => $compraitem['objProducto']->getIdproducto(),
        'pronombre' => $compraitem['objProducto']->getPronombre(),
        'prodetalle' => $compraitem['objProducto']->getProdetalle(),
        // actualizo el stock restando la cantidad descontada:
        'procantstock' => $stockActualizado,
        'precioprod' => $compraitem['objProducto']->getPrecioprod()

    ];

    if($ABMproducto->modificacion($param)){
        // acá quiero almacenar una respuesta que sea decodificada con ajax
        $response[] = [
            'idproducto' => $param['idproducto'],
            'status' => 'success',
            'message' => 'Producto actualizado'
        ];
    }else{
        $response[] = [
            'idproducto' => $param['idproducto'],
            'status' => 'error',
            'message' => 'Error al actualizar el producto'
        ];
    }

}

header('Content-Type: application/json');
echo json_encode($response);

// el stock de producto se actualiza apropiadamente. Falta;
// 1) eliminar el compraitem asociado
// 2) eliminar la compra asociada / cambiarle la fecha 
// 3) modificar la cefechafin del idcompraestadotipo = 2
// 4) crear un nuevo registro de compraestado con el idcompraestadotipo 3
?>