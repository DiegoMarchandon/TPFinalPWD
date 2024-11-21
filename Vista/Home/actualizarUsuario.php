<?php include_once("../estructura/headerSeguro.php"); ?>
<?php
include_once '../../configuracion.php';

// creo un objeto de la clase abmUsuarioRol para llamar a la funcion verificarRolUsuario
$abmUsuarioRol = new ABMUsuarioRol(); 

// Obtener el ID del usuario en la sesion para verificar si tiene permisos
$idUsuarioSesion = $session->getUsuario()->getIdUsuario();

// Verificar si el usuario tiene permisos para acceder a esta página (el 1 es el administrador o sea que le estoy
// diciendo que si el usuario no es administrador lo redirija al login)
$usuarioPermitido = $abmUsuarioRol->verificarRolUsuario($idUsuarioSesion, 1);
if (!$usuarioPermitido) {
    header('Location: ../Home/login.php');
    exit();
}

//$session = new Session();
$abmUsuario = new ABMUsuario();

// Obtener el ID del usuario en la sesion
//$idUsuarioSesion = $session->getUsuario()->getIdUsuario();
$nombreUsuarioSesion = $session->getUsuario()->getUsNombre();

// Separar usuarios habilitados y deshabilitados
$usuariosSeparados = $abmUsuario->separarUsuariosHabilitadosYDeshabilitados();
$usuariosHabilitados = $usuariosSeparados['habilitados'];
$usuariosDeshabilitados = $usuariosSeparados['deshabilitados'];
?>

<div class="container mt-5">
    <h1 class="text-center">Lista de Usuarios</h1>
    <div class="mb-4">
        <h3 class="text-left">Administrador: <?php echo $nombreUsuarioSesion; ?></h3>
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre de Usuario</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($usuariosHabilitados as $usuario) {
                    echo "<tr>";
                    echo "<td>" . $usuario->getIdUsuario() . "</td>";
                    echo "<td>" . $usuario->getUsNombre() . "</td>";
                    echo "<td>" . $usuario->getUsMail() . "</td>";
                    echo "<td>";
                    echo "<div class='btn-group' role='group'>";
                    echo "<a href='formEdit.php?id=" . $usuario->getIdUsuario() . "' class='btn btn-warning btn-sm'>Editar</a>";
                    if ($usuario->getIdUsuario() !== $idUsuarioSesion) { // No permitir eliminar al administrador de la sesión
                        echo "<a href='../Action/eliminarLogin.php?id=" . $usuario->getIdUsuario() . "' class='btn btn-danger btn-sm'>Eliminar</a>";
                    }
                    echo "</div>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php if (count($usuariosDeshabilitados) > 0): ?>
        <h2 class="text-center mt-5">Usuarios Eliminados</h2>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre de Usuario</th>
                        <th>Email</th>
                        <th>Fecha Deshabilitado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($usuariosDeshabilitados as $usuario) {
                        echo "<tr>";
                        echo "<td>" . $usuario->getIdUsuario() . "</td>"; 
                        echo "<td>" . $usuario->getUsNombre() . "</td>";
                        echo "<td>" . $usuario->getUsMail() . "</td>";
                        echo "<td>" . $usuario->getUsDeshabilitado() . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?> 
</div>

<?php include_once("../estructura/footer.php"); ?>