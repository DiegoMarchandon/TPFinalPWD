<?php

include_once '../../configuracion.php';

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

    // Verificar si el nombre de usuario ya existe
    $usuarioExistente = $abmUsuario->buscar(['usnombre' => $datos['usnombre']]);
    if (count($usuarioExistente) > 0) {
        echo "El nombre de usuario no está disponible.";
        echo '<br><a href="../Home/registrarUsuario.php">Volver al registro</a>';
        exit();
    }

    // Verificar si el correo electrónico ya existe
    $emailExistente = $abmUsuario->buscar(['usmail' => $datos['usmail']]);
    if (count($emailExistente) > 0) {
        echo "El correo electrónico ya está asociado a una cuenta.";
        echo '<br><a href="../Home/registrarUsuario.php">Volver al registro</a>';
        exit();
    }

    // Obtener la contraseña hasheada
    $hashedPassword = $datos['uspass'];

    $param = [
        'usnombre' => $datos['usnombre'],
        'uspass' => $hashedPassword, // contraseña hasheada
        'usmail' => $datos['usmail'],
    ];

    if ($abmUsuario->alta($param)) {
        // Obtener el ID del usuario recien creado
        $usuarioNuevo = $abmUsuario->buscar(['usnombre' => $datos['usnombre']]);
        $idUsuario = $usuarioNuevo[0]->getIdUsuario();

        // Asignar el rol de "Usuario" por defecto
        $abmUsuarioRol = new ABMUsuarioRol();
        $abmUsuarioRol->alta(['idusuario' => $idUsuario, 'idrol' => 3]); // el id 3 es de "Cliente" que se le asignara a todos los que se registren por defecto

        header('Location: ../Home/login.php?registro=exitoso'); // Redirigir al login con mensaje de éxito
    } else {
        echo "Error al registrar el usuario.";
        echo '<br><a href="../Home/registrarUsuario.php">Volver al registro</a>';
    }
}