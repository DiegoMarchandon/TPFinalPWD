<?php include_once("../estructura/headerSeguro.php"); ?>
<?php
include_once '../../configuracion.php';


// Configurar la zona horaria a Argentina
date_default_timezone_set('America/Argentina/Buenos_Aires');

//$session = new Session();
$fechaFin = date('Y-m-d H:i:s');
$idUsuarioActual = $session->getUsuario()->getIdusuario();
//$nombreUsuario = "andres";//$session->getUsuario()->getUsnombre();
//$emailUsuario = "prueba.aemv@gmail.com";//$session->getUsuario()->getUsmail();

$ABMcompraEstado = new ABMCompraEstado;
$carritosIniciados = $ABMcompraEstado->buscarCompraIniciadaPorUsuario($idUsuarioActual);

$compraConfirmada = false;
if ($carritosIniciados !== null) {
    foreach ($carritosIniciados as $compraIniciada) {
        $idCompra = $compraIniciada->getIdcompra();

        // Buscar el estado de la compra con idcompraestadotipo = 1
        $compraEstado = $ABMcompraEstado->buscar(['idcompra' => $idCompra, 'idcompraestadotipo' => 1]);

        if (count($compraEstado) > 0) {
            $compraEstado = $compraEstado[0];
            $compraEstado->setCefechafin($fechaFin);

            if ($compraEstado->modificar()) {
                // Insertar una nueva entrada en la tabla compraestado con idcompraestadotipo = 2
                $paramCompraEstado = [
                    'idcompraestado' => null,
                    'idcompra' => $idCompra,
                    'idcompraestadotipo' => 2, // Estado "confirmada"
                    'cefechaini' => $fechaFin,
                    'cefechafin' => null
                ];

                if ($ABMcompraEstado->alta($paramCompraEstado)) {
                    $compraConfirmada = true;
                } else {
                    echo "<script>alert('Error al insertar el nuevo estado de la compra'); window.location.href='../Home/carrito.php';</script>";
                }
            } else {
                echo "<script>alert('Error al confirmar la compra'); window.location.href='../Home/carrito.php';</script>";
            }
        } else {
            echo "<script>alert('No se encontró un estado de compra iniciado para la compra con ID: $idCompra'); window.location.href='../Home/carrito.php';</script>";
        }
    }
} else {
    echo "<script>alert('No hay compras iniciadas'); window.location.href='../Home/carrito.php';</script>";
}
?>

<?php include_once("../estructura/headerSeguro.php"); ?>
    <?php if ($compraConfirmada): ?>
        <h1>Enviando correo...</h1>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            <?php
            // Definir los parámetros de entrada
            $toName = $session->getUsuario()->getUsnombre();
            $toEmail = $session->getUsuario()->getUsmail();
            $message = 'Usted ha confirmado una compra. Este pendiente a la respuesta de la misma, en breve le notificaremos.';
            ?>
            // Llamar a la función de JavaScript pasando los parámetros desde PHP
            // de esta forma ya me funciono sin problemas
            sendEmail('<?php echo $toName; ?>', '<?php echo $toEmail; ?>', '<?php echo $message; ?>');
        });
    </script>
    <?php endif; ?>
<?php include_once("../estructura/footer.php"); ?>