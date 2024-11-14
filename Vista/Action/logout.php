<?php
include_once '../../configuracion.php';

$session = new Session();
$_SESSION['userConectadoRol'] = '';
$session->cerrar();
header('Location: ../Home/login.php');
exit();
?>