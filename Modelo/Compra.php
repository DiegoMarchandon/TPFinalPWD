<?php

class Compra{

    private $idcompra;
    private $cofecha;
    private $objUsuario;
    private $mensajeoperacion;


    /**
     * Get the value of idcompra
     */ 
    public function getIdcompra()
    {
        return $this->idcompra;
    }

    /**
     * Set the value of idcompra
     *
     * @return  self
     */ 
    public function setIdcompra($idcompra)
    {
        $this->idcompra = $idcompra;

         
    }

    /**
     * Get the value of cofecha
     */ 
    public function getCofecha()
    {
        return $this->cofecha;
    }

    /**
     * Set the value of cofecha
     *
     * @return  self
     */ 
    public function setCofecha($cofecha)
    {
        $this->cofecha = $cofecha;

         
    }

    /**
     * Get the value of objUsuario
     */ 
    public function getObjUsuario()
    {
        return $this->objUsuario;
    }

    /**
     * Set the value of objUsuario
     *
     * @return  self
     */ 
    public function setObjUsuario($objUsuario)
    {
        $this->objUsuario = $objUsuario;

         
    }

    /**
     * Get the value of mensajeoperacion
     */ 
    public function getMensajeoperacion()
    {
        return $this->mensajeoperacion;
    }

    /**
     * Set the value of mensajeoperacion
     *
     * @return  self
     */ 
    public function setMensajeoperacion($mensajeoperacion)
    {
        $this->mensajeoperacion = $mensajeoperacion;

         
    }

    public function __construct(){
        $this->idcompra = 0;
        $this->cofecha = null;
        $this->objUsuario = new Usuario();
        $this->mensajeoperacion = '';
    }


    public function setear($idcompra,$cofecha,$objUsuario){
        $this->setIdcompra($idcompra);
        $this->setCofecha($cofecha);
        $this->setObjUsuario($objUsuario);
    }

    public function cargar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="SELECT * FROM compra WHERE idcompra = ".$this->getIdcompra();
      //  echo $sql;
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if($res>-1){
                if($res>0){
                    $row = $base->Registro();
                    // con el id de usuario, lo cargo y lo guardo en mi objeto
                    $objUsuario = new Usuario();
                    $objUsuario->setIdusuario($row['idusuario']);
                    $objUsuario->cargar();

                    $this->setear($row['idcompra'], $row['cofecha'],$objUsuario); 
                    
                }
            }
        } else {
            $this->setmensajeoperacion("compra->cargar: ".$base->getError()[2]);
        }
        return $resp;
    }

    public function insertar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="INSERT INTO compra(cofecha,idusuario)  VALUES(".$this->getCofecha().",".$this->getObjUsuario()->getIdusuario().");"; 
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("compra->insertar: ".$base->getError());
            }
        } else {
            $this->setmensajeoperacion("compra->insertar: ".$base->getError());
        }
        return $resp;
    }

    public function modificar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="UPDATE compra SET cofecha ='".$this->getCofecha()."', idusuario= ".$this->getObjUsuario()->getIdusuario()." WHERE idcompra= ".$this->getIdcompra().";";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("compra->modificar: ".$base->getError());
            }
        } else {
            $this->setmensajeoperacion("compra->modificar: ".$base->getError());
        }
        return $resp;
    }

    public function eliminar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="DELETE FROM compra WHERE idcompra =".$this->getIdcompra();
       // echo $sql;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("compra->eliminar: ".$base->getError());
            }
        } else {
            $this->setmensajeoperacion("compra->eliminar: ".$base->getError());
        }
        return $resp;
    }

    public function listar($parametro=""){
        $arreglo = array();
        $base=new BaseDatos();
        $sql="SELECT * FROM compra ";
     //   echo $sql;
        if ($parametro!="") {
            $sql.='WHERE '.$parametro;
        }
        if($base->iniciar()){
            $res = $base->Ejecutar($sql);
            if($res>-1){
                if($res>0){
                    
                    while ($row = $base->Registro()){
    
                        $objCompra = new Compra();
                        
                        $objCompra->setear($row['idcompra'], $row['cofecha'],$row['idusuario']); 
                        array_push($arreglo, $objCompra);
                    }
                    
                }
                
            } else {
                $this->setmensajeoperacion("Especie->listar: ".$base->getError());
            }
        }
        
        return $arreglo;
    }

}




?>