<?php
include_once '../../configuracion.php';
if (isset($_POST['idproducto'])) {
    $session = new Session;
    // id del usuario actual
    $idUsuario = $session->getUsuario()->getIdusuario();
    $idProducto = $_POST['idproducto'];
    // echo "idProducto: ".$idProducto."<br>";
    $ABMcompraitem = new ABMCompraItem;
    $ABMcompra = new ABMCompra;
    // primero, verifico que existan compraitem con ese idproducto
    if(isset($ABMcompraitem->buscarArray(['idproducto' => $idProducto])[0])){
        
        $buscarCompraItem = $ABMcompraitem->buscarArray(['idproducto' => $idProducto])[0];
        // echo "idcompra de compraitem: <br>";
        // una vez obtengo el compraitem, uso su clave idcompra
        $idcompra = $buscarCompraItem['objCompra']->getIdcompra();
        // print_r($buscarCompraItem['objCompra']->getIdcompra());
        if(count($ABMcompra->buscar(['idcompra' => $idcompra, 'idusuario' => $idUsuario])) > 0){
            // print_r($ABMcompra->buscar(['idproducto' => $idProducto, 'idusuario' => $idUsuario]));
            $estado = $ABMcompraitem->estadoCompraItem($idProducto);
            /* echo "estado:";
            if($estado === null){
                echo " es nulo";
            } */
            echo $estado; // Devuelve el estado como respuesta
        }

    }else{
        echo "no existe el compraitem con ese idproducto";
    }
    // tengo que poner un condicional que relacione ese idproducto con el id del usuario registrado
    // para de esa forma solamente retornar el estado de productos relacionados al usuario
}

?>