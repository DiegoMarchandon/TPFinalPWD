<?php
include_once '../../configuracion.php';
header('Content-Type: application/json');

// Configurar la zona horaria a Argentina
date_default_timezone_set('America/Argentina/Buenos_Aires');

$fechaFin = date('Y-m-d H:i:s');

// uso el idcompra obtenido para obtener los compraitem
$datos = darDatosSubmitted();

$ABMcompraitem = new ABMCompraItem;
$ABMproducto = new ABMProducto;
$ABMcompraEstado = new ABMCompraEstado;
// coleccion de compraitems relacionados con ese idcompra
$colCompraItems = $ABMcompraitem->buscarArray(['idcompra' => $datos['idcompra']]);

// buscamos el compraEstado relacionado a ese idcompra y con un idcompraestadotipo = 2
$compraEstado = $ABMcompraEstado->buscarArray(['idcompra' => $datos['idcompra'], 'idcompraestadotipo' => 2])[0];

// banderas para verificar las modificaciones
    // bandera para verificar la modificacion en el stock del producto
$stockProdModificado = false;
    // bandera para verificar el cambio cefechacfin en el compraestado = 2
$compraEstadoCambiado = false;
    // bandera para verificar la creacion de un nuevo registro compraestado = 3
$compraEstadoNuevo = false;

foreach($colCompraItems as $compraitem){

    // almaceno la cantidad a descontar del producto:
    $cantDescontada = $compraitem['cicantidad'];
    /* echo "<br>---<br>";
    print_r($compraitem['objProducto']->getIdproducto());
    echo "<br>---<br>"; */

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
        $stockProdModificado = true;
        // echo "<br>verificacion 1/3<br>";
        // if (count($compraEstado) > 0) {
            // $compraEstado = $compraEstado[0];
            // print_r($compraEstado);
            // modifico la fechafin de la compraestado;
            $param = [
                'idcompraestado' => $compraEstado['idcompraestado'],
                'idcompra' => $compraEstado['objCompra']->getIdcompra(),
                'idcompraestadotipo' => $compraEstado['objCompraEstadoTipo']->getIdcompraestadotipo(),
                'cefechaini' => $compraEstado['cefechaini'],
                'cefechafin' => $fechaFin
            ];
            if($ABMcompraEstado->modificacion($param)){
                $compraEstadoCambiado = true;
                // echo "<br>verificacion 2/3<br>";
                // ahora, creo un nuevo registro en compraestado con idcompraestadotipo = 3 y que tenga como fechaini la fechafin del compraestado = 2
                $param = [
                    'idcompraestado' => null,
                    'idcompra' => $compraEstado['objCompra']->getIdcompra(),
                    'idcompraestadotipo' => 3,
                    'cefechaini' => $fechaFin,
                    'cefechafin' => null
                ];
                if($ABMcompraEstado->alta($param)){
                    $compraEstadoNuevo = true;
                    // echo "<br>verificacion 3/3<br>";
                    $response = /* "exito"; */
                    [
                        // 'idproducto' => $param['idproducto'],
                        'status' => 'success',
                        'message' => 'Producto actualizado'
                    ];
                }else{
                    $compraEstadoNuevo = false;
                }
            }else{
                $compraEstadoCambiado = false;
            }
        // }

        
    }else{
        $stockProdModificado = false;
        // echo "no se modificó el stock";
        $response = /* "error"; */
        [
            // 'idproducto' => $param['idproducto'],
            'status' => 'error',
            'message' => 'Error al actualizar el producto'
        ];
    }

}

// el stock de producto se actualiza apropiadamente. Falta;
// 1) eliminar la compra (para que el compraitem ya no la busque y así se deje de listar en ordenes.php)
    # no
// 1) eliminar los compraitem asociados
    # no
// 2) eliminar la compra asociada / cambiarle la fecha 

// 3) modificar la cefechafin del idcompraestadotipo = 2
// 4) crear un nuevo registro de compraestado con el idcompraestadotipo 3


echo json_encode($response);
exit;
?>