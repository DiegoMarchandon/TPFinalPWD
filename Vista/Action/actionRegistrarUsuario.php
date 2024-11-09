<?php

include_once '../../configuracion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = darDatosSubmitted();

    $abmUsuario = new ABMUsuario();

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
    $hashedPassword = $_POST['uspass'];

    $param = [
        'usnombre' => $datos['usnombre'],
        'uspass' => $hashedPassword, // contraseña hasheada
        'usmail' => $datos['usmail'],
        //'usdeshabilitado' => null
    ];

    if ($abmUsuario->alta($param)) {
        // Obtener el ID del usuario recien creado
        $usuarioNuevo = $abmUsuario->buscar(['usnombre' => $datos['usnombre']]);
        $idUsuario = $usuarioNuevo[0]->getIdUsuario();

        // Asignar el rol de "Usuario" por defecto
        $abmUsuarioRol = new ABMUsuarioRol();
        $abmUsuarioRol->alta(['idusuario' => $idUsuario, 'idrol' => 3]); // el id 3 es de "Cliente" que se le asignara a todos los que se registren por defecto

        header('Location: ../Home/login.php?mensaje=cuenta_creada'); // Redirigir al login con mensaje de éxito
    } else {
        echo "Error al registrar el usuario.";
        echo '<br><a href="../Home/registrarUsuario.php">Volver al registro</a>';
    }
}