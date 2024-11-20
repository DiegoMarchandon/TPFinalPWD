<?php
include_once '../../configuracion.php';
if (isset($_POST['idproducto'])) {
    $session = new Session;
    // id del usuario actual
    $idUsuario = $session->getUsuario()->getIdusuario();
    $idProducto = $_POST['idproducto'];
    $ABMcompraitem = new ABMCompraItem;
    $ABMcompra = new ABMCompra;
    // primero, verifico que existan compraitem con ese idproducto
    if(isset($ABMcompraitem->buscarArray(['idproducto' => $idProducto])[0])){
        
        $buscarCompraItem = $ABMcompraitem->buscarArray(['idproducto' => $idProducto])[0];

        // una vez obtengo el compraitem, uso su clave idcompra
        $idcompra = $buscarCompraItem['objCompra']->getIdcompra();

        if(count($ABMcompra->buscar(['idcompra' => $idcompra, 'idusuario' => $idUsuario])) > 0){
            
            $estado = $ABMcompraitem->estadoCompraItem($idProducto);
            echo $estado; // Devuelve el estado como respuesta
        }

    }else{
        echo "no existe el compraitem con ese idproducto";
    }

}

?>