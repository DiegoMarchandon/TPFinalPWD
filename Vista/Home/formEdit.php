<?php
include_once '../../configuracion.php';

//$session = new Session();
$abmUsuario = new ABMUsuario();

$datos = darDatosSubmitted();

// Obtener el usuario a editar
$idUsuario = $datos['id'];
$usuario = $abmUsuario->buscar(['idusuario' => $idUsuario])[0];
?>

<?php include_once("../estructura/headerSeguro.php"); ?>
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
            <input type="password" id="uspass" name="uspass" class="form-control" required>
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