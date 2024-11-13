<?php include_once("../estructura/headerSeguro.php"); ?>
<?php
include_once '../../configuracion.php';

//$session = new Session();
$abmUsuario = new ABMUsuario();

// Buscar todos los usuarios
$usuarios = $abmUsuario->buscar(null);
?>

<div class="container mt-5">
    <h1 class="text-center">Asignar Roles</h1>
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
                foreach ($usuarios as $usuario) {
                    echo "<tr>";
                    echo "<td>" . $usuario->getIdUsuario() . "</td>";
                    echo "<td>" . $usuario->getUsNombre() . "</td>";
                    echo "<td>" . $usuario->getUsMail() . "</td>";
                    echo "<td>";
                    echo "<div class='btn-group' role='group'>";
                    echo "<a href='../Action/asignarRol.php?id=" . $usuario->getIdUsuario() . "&rol=1' class='btn btn-primary btn-sm'>Administrador</a>";
                    echo "<a href='../Action/asignarRol.php?id=" . $usuario->getIdUsuario() . "&rol=2' class='btn btn-secondary btn-sm'>Deposito</a>";
                    echo "<a href='../Action/asignarRol.php?id=" . $usuario->getIdUsuario() . "&rol=3' class='btn btn-success btn-sm'>Cliente</a>";
                    echo "</div>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php include_once("../estructura/footer.php"); ?>