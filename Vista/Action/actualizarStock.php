<?php
include_once '../../configuracion.php';
header('Content-Type: application/json');

$datos = darDatosSubmitted();

$response = [
    'status' => 'default',
    'message' => 'default',
    'redirect' => '../Home/stock.php'
];

$ABMProducto = new ABMProducto;

$productoBuscado = $ABMProducto->buscarArray(['idproducto' => $datos['idproducto']]);

if($productoBuscado > 0){
    $param = [
        'idproducto' => $productoBuscado[0]['idproducto'],
        'pronombre' => $productoBuscado[0]['pronombre'],
        'prodetalle' => $productoBuscado[0]['prodetalle'],
        'precioprod' => $productoBuscado[0]['precioprod'],
        'procantstock' => $datos['nuevoStock']
    ];
    if($ABMProducto->modificacion($param)){
        $response['status'] = 'success';
        $response['message'] = 'actualizacion de stock exitosa';
    }else{
        $response['status'] = 'Error en 2do condicional';
        $response['message'] = '!$ABMProducto->modificacion($param)';
    }
}else{
    $response['status'] = 'Error en 1er condicional';
    $response['message'] = '$ABMProducto->buscarArray($datos["idproducto"]) === 0';
}
// print_r();


echo json_encode($response);
exit;
?>