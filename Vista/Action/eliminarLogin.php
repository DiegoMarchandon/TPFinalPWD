<?php
include_once '../../configuracion.php';
// Configurar la zona horaria a Argentina
date_default_timezone_set('America/Argentina/Buenos_Aires');

$session = new Session();
if (!$session->activa() || !$session->validar()) {
    header('Location: ../Home/login.php');
    exit();
}

$abmUsuario = new ABMUsuario();
$datos = darDatosSubmitted(); 
if (isset($datos['id'])) {
    $param = ['idusuario' => $datos['id']];
    $usuario = $abmUsuario->buscar($param)[0];
    $param = [
        'idusuario' => $usuario->getIdusuario(), 
        'usnombre' => $usuario->getUsnombre(),
        'uspass' => $usuario->getUspass(),
        'usmail' => $usuario->getUsmail(),
        'usdeshabilitado' => date('Y-m-d H:i:s')
    ];
    if ($abmUsuario->modificacion($param)) {
        header('Location: ../Home/actualizarUsuario.php?mensaje=eliminacion_exitosa');
    } else {
        echo "Error al deshabilitar el usuario.";
        echo '<br><a href="../Home/actualizarUsuario.php">Volver a intentar</a>';
    }
}

exit();
?>