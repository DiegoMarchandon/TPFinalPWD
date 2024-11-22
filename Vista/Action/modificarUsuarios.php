<?php
include_once '../../configuracion.php';

$session = new Session();
$abmUsuario = new ABMUsuario();
$datos = darDatosSubmitted();

$response = $abmUsuario->modificarUsuario($datos);

if ($response['status'] === 'success') {
    header('Location: ../Home/actualizarUsuario.php?mensaje=actualizacion_exitosa');
} else {
    header('Location: ../Home/actualizarUsuario.php?mensaje=error_al_modificar');
}
?>