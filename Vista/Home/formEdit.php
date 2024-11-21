<?php include_once("../estructura/headerSeguro.php"); ?>
<?php
include_once '../../configuracion.php';

// creo un objeto de la clase abmUsuarioRol para llamar a la funcion verificarRolUsuario
$abmUsuarioRol = new ABMUsuarioRol(); 

// Obtener el ID del usuario en la sesion para verificar si tiene permisos
$idUsuarioActual = $session->getUsuario()->getIdUsuario();

// Verificar si el usuario tiene permisos para acceder a esta página (el 1 es el administrador o sea que le estoy
// diciendo que si el usuario no es administrador lo redirija al login)
$usuarioPermitido = $abmUsuarioRol->verificarRolUsuario($idUsuarioActual, 1);
if (!$usuarioPermitido) {
    header('Location: ../Home/login.php');
    exit();
}

//$session = new Session();
$abmUsuario = new ABMUsuario();

$datos = darDatosSubmitted();

// Obtener el usuario a editar
$idUsuario = $datos['id'];
$usuario = $abmUsuario->buscar(['idusuario' => $idUsuario])[0];
?>


<div class="container mt-5">
    <h1 class="text-center">Actualizar Usuario</h1>
    <form id="actualizarForm" action="../Action/modificarUsuarios.php" method="post" onsubmit="return hashPassword()">
        <input type="hidden" name="idusuario" value="<?php echo $usuario->getIdUsuario(); ?>">
        <div class="form-group">
            <label for="usnombre">Nombre:</label>
            <input type="text" name="usnombre" class="form-control" value="<?php echo $usuario->getUsNombre(); ?>" required>
        </div>
        <div class="form-group">
            <label for="uspass">Contraseña:</label>
            <input type="password" id="uspass" name="uspass" class="form-control" >
        </div>
        <div class="form-group">
            <label for="usmail">Email:</label>
            <input type="email" name="usmail" class="form-control" value="<?php echo $usuario->getUsMail(); ?>" required>
        </div>
        <input type="hidden" name="usdeshabilitado" value="<?php echo $usuario->getUsDeshabilitado(); ?>">
        <button type="submit" class="btn btn-primary mt-3">Actualizar</button>
    </form>
</div>
<?php include_once("../estructura/footer.php"); ?>