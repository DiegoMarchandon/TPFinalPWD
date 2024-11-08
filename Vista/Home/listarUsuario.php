<?php include_once("../estructura/headerSeguro.php"); ?>
<?php
include_once '../../configuracion.php';

//$session = new Session();
$abmUsuario = new ABMUsuario();

// Buscar todos los usuarios
$usuarios = $abmUsuario->buscar(null);
?>

<div class="container mt-5">
    <h1 class="text-center">Lista de Usuarios</h1>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre de Usuario</th>
                    <th>Email</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($usuarios as $usuario) {
                    echo "<tr>";
                    echo "<td>" . $usuario->getIdUsuario() . "</td>";
                    echo "<td>" . $usuario->getUsNombre() . "</td>";
                    echo "<td>" . $usuario->getUsMail() . "</td>";
                    $estado = ($usuario->getUsDeshabilitado() == '0000-00-00 00:00:00' || is_null($usuario->getUsDeshabilitado())) ? 'Activo' : 'Deshabilitado';
                    echo "<td>" . $estado . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php include_once("../estructura/footer.php"); ?>