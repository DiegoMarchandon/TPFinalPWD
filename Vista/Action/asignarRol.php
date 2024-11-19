<?php
include_once '../../configuracion.php';

$session = new Session();
$abmUsuarioRol = new ABMUsuarioRol();

$datos = darDatosSubmitted();
// Verificar que el método sea GET y que el ID del usuario y el rol estén presentes
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($datos['id']) && isset($datos['rol'])) {
    $idUsuario = $datos['id'];
    $idRol = $datos['rol'];

    //echo "ID Usuario: $idUsuario<br>";
    //echo "ID Rol: $idRol<br>";

    $param = [
        'idusuario' => $idUsuario,
        'idrol' => $idRol
    ];

    if ($abmUsuarioRol->modificarRol($param)) {
       // echo "Actualización exitosa<br>";
        header('Location: ../Home/asignarRoles.php?mensaje=asignacion_exitosa');
    } else {
        echo "Error al asignar el rol.<br>";
        echo '<br><a href="../Home/asignarRoles.php">Volver a intentar</a>';
    }
} else {
    echo "Método no permitido o parámetros faltantes.<br>";
}
?>