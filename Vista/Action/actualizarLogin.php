<?php
include_once '../../configuracion.php';

$session = new Session();
$abmUsuario = new ABMUsuario();

// Verificar que el método sea POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idUsuario = $_POST['idusuario'];
    $nombreUsuario = $_POST['usnombre'];
    $psw = $_POST['uspass'];
    $email = $_POST['usmail'];
    $usdeshabilitado = $_POST['usdeshabilitado'];

    // La contraseña ya viene hasheada desde el cliente
    $hashedPassword = $psw;

    $param = [
        'idusuario' => $idUsuario,
        'usnombre' => $nombreUsuario,
        'uspass' => $hashedPassword,
        'usmail' => $email,
        'usdeshabilitado' => $usdeshabilitado
    ];

   
    if ($abmUsuario->modificacion($param)) {
        echo "Actualización exitosa<br>";
        header('Location: ../Home/paginaSegura.php?mensaje=actualizacion_exitosa');
    } else {
        echo "Error al actualizar el usuario.<br>";
        echo '<br><a href="../Home/actualizarUsuario.php">Volver a intentar</a>';
    }
}
?>