<?php
/* script que se encargará de recibir las órdenes en formato JSON que fueron enviadas desde producto.php a la espera de que el cliente confirme
(y una vez confirmadas, se envíen a depósito para que las acepte o las rechace) */
echo "<h1>pagina del carrito que vería el cliente</h1>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 'php://input' es un flujo estándas para leer los datos enviados en una solicitud HTTP (especialmente enviados a través de un método POST).  
    $input = file_get_contents('php://input'); // lee los datos del cuerpo de la solicitud
    echo "datos crudos: $input";

    // decodificar el JSON a un arreglo en PHP
    // convertimos el JSON a un arreglo asociativo en PHP. El segundo parámetro "true" es para que se convierta el JSON a un arreglo en lugar de un objeto 
    $productosRecibidos = json_decode($input, true);
    echo "productos recibidos: <br>";
    print_r($productosRecibidos);
}elseif($_SERVER['REQUEST_METHOD'] === 'GET'){
    // 'php://input' es un flujo estándas para leer los datos enviados en una solicitud HTTP (especialmente enviados a través de un método POST).  
    $input = file_get_contents('php://input'); // lee los datos del cuerpo de la solicitud
    echo "datos crudos: $input";

    // decodificar el JSON a un arreglo en PHP
    // convertimos el JSON a un arreglo asociativo en PHP. El segundo parámetro "true" es para que se convierta el JSON a un arreglo en lugar de un objeto 
    $productosRecibidos = json_decode($input, true);
    echo "productos recibidos: <br>";
    print_r($productosRecibidos);
} else {
    echo "Accede a esta página mediante una solicitud POST.";
}

// mensaje opcional que podemos usar para enviar una respuesta JSON de vuelta al cliente
// echo json_encode(["mensaje" => "Productos recibidos correctamente", "productos" => $productos]);
?>