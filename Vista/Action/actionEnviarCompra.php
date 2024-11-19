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
                // verifico que ya no haya un registro de compraestado subido con ese idcompra y el idcompraestadotipo = 3 para evitarme duplicados
                if(count($ABMcompraEstado->buscarArray(['idcompra' => $compraEstado['objCompra']->getIdcompra(), 'idcompraestadotipo' => 3])) === 0 ){
                    if($ABMcompraEstado->alta($param)){
                        $compraEstadoNuevo = true;
                        // echo "<br>verificacion 3/3<br>";
                        $response = /* "exito"; */
                        [
                            // 'idproducto' => $param['idproducto'],
                            'status' => 'success',
                            'message' => 'Producto actualizado',
                            'redirect' => '../Home/ordenes.php'
                        ];
                        // Obtener el usuario asociado a la compra
                        $idcompra = $datos['idcompra'];
                        $compra = new ABMCompra();
                        $objCompra = $compra->buscar(['idcompra' => $idcompra]);
                        if (count($objCompra) > 0) {
                            $objCompra = $objCompra[0];
                            $usuario = $objCompra->getObjUsuario();
                            $toName = $usuario->getUsnombre();
                            $toEmail = $usuario->getUsmail();
                            $response['toName'] = $toName;
                            $response['toEmail'] = $toEmail;
                        }
                    }else{
                        $compraEstadoNuevo = false;
                    }
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
            'message' => 'Error al actualizar el producto',
            'redirect' => '../Home/ordenes.php'
        ];
    }

}

echo json_encode($response);
exit;
?>