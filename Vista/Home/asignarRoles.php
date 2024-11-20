<?php include_once("../estructura/headerSeguro.php"); ?>
<?php
include_once '../../configuracion.php';

$session = new Session();
$abmUsuario = new ABMUsuario();

// Obtener el ID del usuario en la sesion
$idUsuarioSesion = $session->getUsuario()->getIdUsuario();
$nombreUsuarioSesion = $session->getUsuario()->getUsNombre();

// Separar usuarios habilitados y deshabilitados
$usuariosSeparados = $abmUsuario->separarUsuariosHabilitadosYDeshabilitados();
$usuariosHabilitados = $usuariosSeparados['habilitados'];
?>

<div class="container mt-5">
    <h1 class="text-center">Asignar Roles</h1>
    <div class="mb-4">
        <h3 class="text-left">Administrador: <?php echo $nombreUsuarioSesion; ?></h3>
    </div>
    <div id="mensaje" class="text-center"></div>
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
                    $deshabilitar = $usuario->getIdUsuario() === $idUsuarioSesion ? 'disabled' : '';
                    echo "<tr>";
                    echo "<td>" . $usuario->getIdUsuario() . "</td>";
                    echo "<td>" . $usuario->getUsNombre() . "</td>";
                    echo "<td>" . $usuario->getUsMail() . "</td>";
                    echo "<td>";
                    echo "<div class='btn-group' role='group'>";
                    echo "<button class='btn btn-warning btn-sm asignar-rol' data-id='" . $usuario->getIdUsuario() . "' data-rol='1' $deshabilitar>Administrador</button>";
                    echo "<button class='btn btn-secondary btn-sm asignar-rol' data-id='" . $usuario->getIdUsuario() . "' data-rol='2' $deshabilitar>Deposito</button>";
                    echo "<button class='btn btn-success btn-sm asignar-rol' data-id='" . $usuario->getIdUsuario() . "' data-rol='3' $deshabilitar>Cliente</button>";
                    echo "</div>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.asignar-rol').on('click', function() {
        var idUsuario = $(this).data('id');
        var idRol = $(this).data('rol');

        $.ajax({
            url: '../Action/asignarRol.php',
            method: 'POST',
            data: {
                id: idUsuario,
                rol: idRol
            },
            success: function(response) {
                const mensajeDiv = document.getElementById('mensaje');
                if (response.mensaje === 'rol_existente') {
                    mensajeDiv.innerHTML = '<div class="alert alert-warning text-center">Este Usuario ya posee este rol.</div>';
                } else if (response.mensaje === 'asignacion_exitosa') {
                    mensajeDiv.innerHTML = '<div class="alert alert-success text-center">Rol modificado con éxito.</div>';
                } else if (response.error) {
                    mensajeDiv.innerHTML = '<div class="alert alert-danger text-center">' + response.error + '</div>';
                }
            },
            error: function() {
                const mensajeDiv = document.getElementById('mensaje');
                mensajeDiv.innerHTML = '<div class="alert alert-danger text-center">Error al asignar el rol. Por favor, inténtelo de nuevo.</div>';
            }
        });
    });
});
</script>

<?php include_once("../estructura/footer.php"); ?>