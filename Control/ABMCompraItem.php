<?php

class ABMCompraItem {

    /**
     * Función utilizada en caso que esperemos un arreglo de un solo elemento.
     * Llama a Buscar, convierte el obj del indice 0 a arreglo y lo retorna.
     * Si retorna un arreglo vacío, devuelve null.
     * @return array|null
     */
    public function arrayOnull($arrAsoc) {
        $objetoOnull = $this->buscar($arrAsoc);
        $element = null;

        if (count($objetoOnull) === 1) {
            $element = dismount($objetoOnull[0]);
        }

        return $element;
    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto
     * @param array $datos
     * @return bool
     */
    public function abm($datos) {
        $resp = false;
        if ($datos['accion'] == 'editar') {
            if ($this->modificacion($datos)) {
                $resp = true;
            }
        }
        if ($datos['accion'] == 'borrar') {
            if ($this->baja($datos)) {
                $resp = true;
            }
        }
        if ($datos['accion'] == 'nuevo') {
            if ($this->alta($datos)) {
                $resp = true;
            }
        }
        return $resp;
    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto
     * @param array $param
     * @return CompraItem
     */
    private function cargarObjeto($param) {
        $obj = null;

        if (array_key_exists('idcompraitem', $param) && array_key_exists('idproducto', $param) && array_key_exists('idcompra', $param) && array_key_exists('cicantidad', $param)) {
            $objProducto = new Producto();
            $objProducto->setIdproducto($param['idproducto']);
            $objProducto->cargar();

            $objCompra = new Compra();
            $objCompra->setIdcompra($param['idcompra']);
            $objCompra->cargar();

            $obj = new CompraItem();
            $obj->setear($param['idcompraitem'], $objProducto, $objCompra, $param['cicantidad']);
        }

        return $obj;
    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto que son claves
     * @param array $param
     * @return CompraItem
     */
    private function cargarObjetoConClave($param) {
        $obj = null;

        if (isset($param['idcompraitem'])) {
            $obj = new CompraItem();
            $obj->setIdcompraitem($param['idcompraitem']);
        }
        return $obj;
    }

    /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves
     * @param array $param
     * @return boolean
     */
    private function seteadosCamposClaves($param) {
        $resp = false;
        if (isset($param['idcompraitem']))
            $resp = true;
        return $resp;
    }

    /**
     * permite ingresar un objeto
     * @param array $param
     */
    public function alta($param) {
        $resp = false;
        $objCompraItem = $this->cargarObjeto($param);
        if ($objCompraItem != null and $objCompraItem->insertar()) {
            $resp = true;
        }
        return $resp;
    }

    /**
     * permite eliminar un objeto 
     * @param array $param
     * @return boolean
     */
    public function baja($param) {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $objCompraItem = $this->cargarObjetoConClave($param);
            if ($objCompraItem != null and $objCompraItem->eliminar()) {
                $resp = true;
            }
        }

        return $resp;
    }

    /**
     * permite modificar un objeto
     * @param array $param
     * @return boolean
     */
    public function modificacion($param) {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $objCompraItem = $this->cargarObjeto($param);
            if ($objCompraItem != null and $objCompraItem->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    /**
     * permite buscar un objeto
     * @param array $param
     * @return array
     */
    public function buscar($param) {
        $where = " true ";
        if ($param <> NULL) {
            if (isset($param['idcompraitem'])) $where .= " and idcompraitem ='" . $param['idcompraitem'] . "'";
            if (isset($param['idproducto'])) $where .= " and idproducto ='" . $param['idproducto'] . "'";
            if (isset($param['idcompra'])) $where .= " and idcompra ='" . $param['idcompra'] . "'";
            if (isset($param['cicantidad'])) $where .= " and cicantidad ='" . $param['cicantidad'] . "'";
        }
        $compraItem = new CompraItem();
        $arreglo = $compraItem->listar($where);
        return $arreglo;
    }

    /**
     * Si recibe como parámetro un arreglo asociativo clave-valor, llama al Buscar  
     * y retorna un arreglo con arreglos asociativos. 
     * Si recibe como parámetro un objeto, convierte sus propiedades en un arreglo asociativo.
     * @param array|object $param
     * @return array  
     */
    public function buscarArray($param) {
        $arreglo = [];
        if (is_object($param)) {
            $arreglo = dismount($param);
        } else {
            $arreglo = convert_array($this->buscar($param));
        }
        return $arreglo;
    }

    /**
     * recibe el id de un producto y retorna su estado
     * @param int
     * @return null|int
     */
    public function estadoCompraItem($IDprod){

        $compraEstadoTipo = null;
        $ABMCompraEstado = new ABMCompraEstado;
        // primero busco el compraItem vinculado a ese idProducto
        $compraItemBuscado = $this->buscar(['idproducto' => $IDprod]);
        // si se encontraron productos:
        if(count($compraItemBuscado) > 0){
            // busco un compraestado con el idcompra en cuestión
            $compraEstadoBuscado = $ABMCompraEstado->buscar(['idcompra' => $compraItemBuscado[0]->getObjCompra()->getIdcompra()]);
            if(count($compraEstadoBuscado) > 0){
                // verifico con el compraestado el tipo de estado 
                $compraEstadoTipo = $compraEstadoBuscado[0]->getObjCompraEstadoTipo()->getIdcompraestadotipo();
            }
        }
    
        return $compraEstadoTipo;
    }
}
?>