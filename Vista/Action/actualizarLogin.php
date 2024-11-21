<?php
include_once '../../configuracion.php';

$session = new Session();
$abmUsuario = new ABMUsuario();
// Verificar que el método sea POST

$datos = darDatosSubmitted();
// inicializamos las variables que almacenarán los datos
$nombreUsuario;
$hashedPassword;
$email;
$usdeshabilitado;
// usamos el $datos['nombreActual'] (dato obligatorio en nuestro formulario) para encontrar al usuario
$userActual = $abmUsuario->buscarArray(['usnombre' => $datos['nombreActual']]);

// si el campo está vacío, se mantiene el nombre actual del usuario
if($datos['nuevoNombre'] === ''){
    $nombreUsuario = $userActual[0]['usnombre'];
}else{
    $nombreUsuario = $datos['nuevoNombre'];
}

if($datos['nuevaContraseña'] === '' || $datos['nuevaContraseñaConfirm'] === ''){
    $hashedPassword = $userActual[0]['uspass'];
}else{
    $hashedPassword = $datos['nuevaContraseña'];
}


if($datos['nuevoEmail'] === ''){
    $email = $userActual[0]['usmail'];
    echo "<br>nuevoEmail no existe<br>";
}else{
    $email = $datos['nuevoEmail'];
}

$param = [
    'idusuario' => $userActual[0]['idusuario'],
    'usnombre' => $nombreUsuario,
    'uspass' => $hashedPassword,
    'usmail' => $email,
    'usdeshabilitado' => $userActual[0]['usdeshabilitado']
];

if ($abmUsuario->modificacion($param)) {
    echo "Actualización exitosa<br>";
    header('Location: ../Home/paginaSegura.php?mensaje=actualizacion_exitosa');
} else {
    echo "Error al actualizar el usuario.<br>";
    echo '<br><a href="../Home/actualizarUsuario.php">Volver a intentar</a>';
}

?>