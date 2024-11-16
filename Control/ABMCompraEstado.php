<?php

class ABMCompraEstado {

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
     * @return CompraEstado
     */
    private function cargarObjeto($param) {
        $obj = null;

        if (array_key_exists('idcompraestado', $param) && array_key_exists('idcompra', $param) && array_key_exists('idcompraestadotipo', $param) && array_key_exists('cefechaini', $param) && array_key_exists('cefechafin', $param)) {
            $objCompra = new Compra();
            $objCompra->setIdcompra($param['idcompra']);
            $objCompra->cargar();

            $objCompraEstadoTipo = new CompraEstadoTipo();
            $objCompraEstadoTipo->setIdcompraestadotipo($param['idcompraestadotipo']);
            $objCompraEstadoTipo->cargar();

            $obj = new CompraEstado();
            $obj->setear($param['idcompraestado'], $objCompra, $objCompraEstadoTipo, $param['cefechaini'], $param['cefechafin']);
        }

        return $obj;
    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto que son claves
     * @param array $param
     * @return CompraEstado
     */
    private function cargarObjetoConClave($param) {
        $obj = null;

        if (isset($param['idcompraestado'])) {
            $obj = new CompraEstado();
            $obj->setIdcompraestado($param['idcompraestado']);
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
        if (isset($param['idcompraestado']))
            $resp = true;
        return $resp;
    }

    /**
     * permite ingresar un objeto
     * @param array $param
     */
    public function alta($param) {
        $resp = false;
        $objCompraEstado = $this->cargarObjeto($param);
        if ($objCompraEstado != null and $objCompraEstado->insertar()) {
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
            $objCompraEstado = $this->cargarObjetoConClave($param);
            if ($objCompraEstado != null and $objCompraEstado->eliminar()) {
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
            $objCompraEstado = $this->cargarObjeto($param);
            if ($objCompraEstado != null and $objCompraEstado->modificar()) {
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
            if (isset($param['idcompraestado'])) $where .= " and idcompraestado ='" . $param['idcompraestado'] . "'";
            if (isset($param['idcompra'])) $where .= " and idcompra ='" . $param['idcompra'] . "'";
            if (isset($param['idcompraestadotipo'])) $where .= " and idcompraestadotipo ='" . $param['idcompraestadotipo'] . "'";
            if (isset($param['cefechaini'])) $where .= " and cefechaini ='" . $param['cefechaini'] . "'";
            if (isset($param['cefechafin'])) $where .= " and cefechafin ='" . $param['cefechafin'] . "'";
        }
        $compraEstado = new CompraEstado();
        $arreglo = $compraEstado->listar($where);
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
     * permite buscar compras por idusuario y verificar el estado
     * @param int $idusuario
     * @return array|null
     */
    public function buscarCompraIniciadaPorUsuario($idusuario) {
        $abmCompra = new ABMCompra();
        $compras = $abmCompra->buscarPorUsuario($idusuario);

        // arreglo para almacenar las compras con estado Iniciado
        $compraEstadoIniciado = [];

        foreach ($compras as $compra) {
            if (count($compras) > 0) {
                // de cada compra específica, obtengo su compraEstado específico
                $compraEstado = $this->buscarArray(['idcompra' => $compra->getIdcompra()]);
                if (count($compraEstado) > 0) {

                    // si el 'idcompraestadotipo' de este compraEstado es 1, significa que la compra fue iniciada. Por lo que la almacenamos
                    if($compraEstado[0]['objCompraEstadoTipo']->getIdcompraestadotipo() === 1 &&  $compraEstado[0]['cefechafin'] === '0000-00-00 00:00:00'){
                        $compraEstadoIniciado[] = $compra; 
                    }

                //     foreach ($compraEstado as $estado) {
                //         if ($estado['idcompraestado'] == 1) {
                //             // return $compra;
                //             $compraEstadoIniciado[] = $compra;
                //         }
                   // }
                //    $compraEstadoIniciado[] = $compraEstado[0]['objCompraEstadoTipo']; 
                }
            }
        } 

        if(count($compraEstadoIniciado) === 0){
            $compraEstadoIniciado = null;
        }

        return $compraEstadoIniciado;
    }
    /**
     * permite buscar compras confirmadas sin finalizar
     * @return array
     */
    public function buscarComprasConfirmadasSinFinalizar() {
        $abmCompra = new ABMCompra();
        $compras = $abmCompra->buscar(null); // Buscar todas las compras

        // arreglo para almacenar las compras confirmadas sin finalizar
        $comprasConfirmadasSinFinalizar = [];

        foreach ($compras as $compra) {
            if (count($compras) > 0) {
                // de cada compra específica, obtengo su compraEstado específico
                $compraEstado = $this->buscarArray(['idcompra' => $compra->getIdcompra()]);
                if (count($compraEstado) > 0) {
                    foreach ($compraEstado as $estado) {
                        // si el 'idcompraestadotipo' de este compraEstado es 2 y 'cefechafin' es '0000-00-00 00:00:00', significa que la compra fue confirmada pero no finalizada. Por lo que la almacenamos
                        if ($estado['objCompraEstadoTipo']->getIdcompraestadotipo() === 2 && $estado['cefechafin'] === '0000-00-00 00:00:00') {
                            $comprasConfirmadasSinFinalizar[] = $compra;
                           // break; // Salir del bucle una vez que encontramos un estado que cumple con las condiciones
                        }
                    }
                }
            }
        }

        return $comprasConfirmadasSinFinalizar;
    }
}
?>