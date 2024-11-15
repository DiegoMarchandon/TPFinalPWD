<?php
include_once '../../configuracion.php';
header('Content-Type: application/json');
$ABMUsuario = new ABMUsuario;
$session = new Session;

$colUsers = $ABMUsuario->buscarArray(null);
// almaceno al usuario actual en formato de arreglo asociativo
$userActual = $ABMUsuario->buscarArray($session->getUsuario());

$datos = [
    'usersBD' => $colUsers,
    'userActual' => $userActual
];

// codifico el arreglo asociativo en formato JSON
$jsonDatos = json_encode($datos);

echo $jsonDatos;

?>