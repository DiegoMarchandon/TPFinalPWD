<?php
include_once '../../configuracion.php';

$session = new Session();
$abmUsuarioRol = new ABMUsuarioRol();

$datos = darDatosSubmitted();
$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($datos['id']) && isset($datos['rol'])) {
    $idUsuario = $datos['id'];
    $idRol = $datos['rol'];

    $param = [
        'idusuario' => $idUsuario,
        'idrol' => $idRol
    ];

    // Verifica si el usuario ya tiene el rol asignado
    $usuarioRolExistente = $abmUsuarioRol->buscar($param);
    if (count($usuarioRolExistente) > 0) {
        // El usuario ya tiene este rol
        $response['mensaje'] = 'rol_existente';
    } else {
        // Modificar el rol del usuario
        if ($abmUsuarioRol->modificarRol($param)) {
            $response['mensaje'] = 'asignacion_exitosa';
        } else {
            $response['error'] = 'Error al asignar el rol.';
        }
    }
} else {
    $response['error'] = 'Método no permitido o parámetros faltantes.';
}

header('Content-Type: application/json');
echo json_encode($response);
exit;
?>