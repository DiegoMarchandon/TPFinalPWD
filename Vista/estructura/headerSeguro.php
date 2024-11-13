<?php
include_once '../../configuracion.php';

//primero si no tenes los roles andate al sql y hacele un insert a rol con estos valores
//INSERT INTO `rol`(`idrol`, `rodescripcion`) VALUES ('1','Administrador')
//INSERT INTO `rol`(`idrol`, `rodescripcion`) VALUES ('2','Deposito')
//INSERT INTO `rol`(`idrol`, `rodescripcion`) VALUES ('3','Cliente')

//ahora andate al bdcarritocompras.sql
//busca la tabla del menu deje dos insert con las posibles opciones del menu (estas las podemos cambiar pero es para probar ahora)

//busca la tabla menuRol hace los dos insert que tengo tambien ahi



$session = new Session();
if (!$session->activa() || !$session->validar()) {
    
    header('Location: login.php');
    exit();
}

$userID = $session->getRol()[0]->getObjRol()->getIdrol();

/* estilos personalizados para el navbar dependiendo el rol */
if($userID == 1){ #administrador
    $colorFondo = ' bg-warning ';
}elseif($userID == 2){ #deposito
    $colorFondo = ' bg-secondary ';
}else{ #cliente
    $colorFondo = ' bg-success ';
}

$abmMenuRol = new ABMMenuRol();

//aca tengo que colocar una funcion para obtener el id en base a la 
//sesion iniciada para obtener el rol de cada cuenta por ahora esta asi para probar
$param = ['idrol' => 1]; //cambiale el numerito a "3" si queres ver el del cliente
                        //o tambien "1" el de administrador (el de deposito todavia no subi ninguna opcion)

$menus = $abmMenuRol->buscar(['idrol'=>$userID]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/crypto-js.min.js"></script>
    <script src="../js/script.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light <?= $colorFondo ?>">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">comahu<p class="text-primary d-inline"><b>e-shop</b></p></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php
                foreach ($menus as $menu) {
                    echo '<li class="nav-item">';
                    echo '<a class="nav-link" href="' . $menu->getObjMenu()->getMedescripcion() . '">' . $menu->getObjMenu()->getMenombre() . '</a>';
                    echo '</li>';
                }
                ?>
            </ul>
        </div>
    </div>
</nav>
</body>
</html>