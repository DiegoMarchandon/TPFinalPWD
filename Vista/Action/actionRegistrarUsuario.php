<?php

include_once '../../configuracion.php';

// Verifica si es una solicitud AJAX
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

// Verifica si es una solicitud POST o GET
$isPostOrGet = $_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET';

// Verifica si el token de seguridad es válido (solo para POST/GET)
$isValidToken = isset($_POST['form_security_token']) && $_POST['form_security_token'] === 'valor_esperado';

// Si no es AJAX ni una solicitud válida POST/GET con el token, redirige
if (!$isAjax && (!$isPostOrGet || !$isValidToken)) {
    header('Location: ../Home/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = darDatosSubmitted();

    $abmUsuario = new ABMUsuario();

    // Verificar si se está realizando una verificación AJAX
    if (isset($datos['verificar']) && $datos['verificar'] == true) {
        $response = [
            'nombreExiste' => false,
            'emailExiste' => false
        ];

        // Verificar si el nombre de usuario ya existe en la base de datos
        if (isset($datos['usnombre'])) {
            $usuariosConMismoNombre = $abmUsuario->buscar(['usnombre' => $datos['usnombre']]);
            if (count($usuariosConMismoNombre) > 0) {
                $response['nombreExiste'] = true;
            }
        }

        // Verificar si el email ya existe en la base de datos
        if (isset($datos['usmail'])) {
            $usuariosConMismoEmail = $abmUsuario->buscar(['usmail' => $datos['usmail']]);
            if (count($usuariosConMismoEmail) > 0) {
                $response['emailExiste'] = true;
            }
        }

        echo json_encode($response);
        exit();
    }

    $response = $abmUsuario->registrarUsuario($datos);

    if ($response['status'] === 'success') {
        header('Location: ../Home/login.php?registro=exitoso'); // Redirigir al login con mensaje de éxito
    } else {
        echo $response['message'];
        echo '<br><a href="../Home/registrarUsuario.php">Volver al registro</a>';
    }
}
?>