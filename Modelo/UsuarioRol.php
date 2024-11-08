<?php

class UsuarioRol{

    private $objUsuario;
    private $objRol;
    private $mensajeoperacion;


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
     * Get the value of objRol
     */ 
    public function getObjRol()
    {
        return $this->objRol;
    }

    /**
     * Set the value of objRol
     *
     * @return  self
     */ 
    public function setObjRol($objRol)
    {
        $this->objRol = $objRol;

        
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

    public function __construct() {
        $this->objUsuario = new Usuario();
        $this->objRol = new Rol();
        $this->mensajeoperacion='';
    } 

    public function setear($objUsuario, $objRol){
        $this->setObjRol($objRol);
        $this->setObjUsuario($objUsuario);
    }

    public function setearConClave($idusuario, $idjrol)
    {
        $this->getObjRol()->setIdRol($idjrol);
        $this->getObjUsuario()->setIdUsuario($idusuario);
    }

    public function cargar(){
        $resp = false;
        $base=new BaseDatos();
        // $objUsuario = new Usuario();
        // $objRol = new Rol();
        $sql="SELECT * FROM usuariorol WHERE idusuario = ".$this->getObjUsuario()->getIdusuario()." AND idrol = ".$this->getObjRol()->getIdrol().";";
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if($res>-1){
                if($res>0){
                    $row = $base->Registro();
                    
                    $objUsuario = new Usuario();
                    $objUsuario->setIdUsuario($row['idusuario']);
                    $objUsuario->cargar();
                    $objRol = new Rol();
                    $objRol->setIdRol($row['idrol']);
                    $objRol->cargar();
                    $this->setear($objUsuario->getIdusuario(), $objRol->getIdrol());
                }
            }
        } else {
            $this->setmensajeoperacion("usuariorol->listar: ".$base->getError());
        }
        return $resp;
     
    }

    public function insertar(){
        $resp = false;
        $base=new BaseDatos();
        // $objUsuario = new Usuario();
        // $objRol = new Rol();
        $sql="INSERT INTO usuariorol(idusuario, idrol) VALUES(".$this->getObjUsuario()->getIdusuario().", ".$this->getObjRol()->getIdrol().");";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                /* if($id = $base->lastInsertId($sql)){
                    $this->getobjUsuario()->setIdusuario($id);
                    $this->getobjRol()->setIdrol($id);
                }else{
                    echo "no se encontró el id";
                } */
                $resp = true;
            } else {
                $this->setmensajeoperacion("usuariorol->insertar: ".$base->getError());
            }
        } else {
            $this->setmensajeoperacion("usuariorol->insertar: ".$base->getError());
        }
        return $resp;
    }

     public function modificar(){
        $resp = false;
        $base = new BaseDatos();
        $sql = "UPDATE usuariorol SET idusuario = ".$this->getObjUsuario()->getIdusuario().",".$this->getObjRol()->getIdrol()." WHERE idusuario = ".$this->getobjUsuario()->getIdusuario()." AND idrol= ".$this->getobjRol()->getIdrol().";";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                
                $resp = true;
            } else {
                $this->setmensajeoperacion("usuariorol->insertar: ".$base->getError());
            }
        } else {
            $this->setmensajeoperacion("usuariorol->insertar: ".$base->getError());
        }
        return $resp;
    } 

    public function eliminar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="DELETE FROM usuariorol WHERE idusuario=".$this->getObjUsuario()->getIdusuario() ." AND idrol=".$this->getObjRol()->getIdrol().";";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("usuariorol->eliminar: ".$base->getError());
            }
        } else {
            $this->setmensajeoperacion("usuariorol->eliminar: ".$base->getError());
        }
        return $resp;
    }

    public function listar($parametro =""){
        $arreglo = array();
        $base=new BaseDatos();
        $sql="SELECT * FROM usuariorol ";
        if ($parametro!="") {
            $sql.='WHERE '.$parametro;
        }
        $res = $base->Ejecutar($sql);
        if($res>-1){
            if($res>0){
                
                while ($row = $base->Registro()){
                    
                    $objUsuarioRol = new UsuarioRol();
                    $objUsuario = new Usuario();
                    $objRol = new Rol();

                    $objUsuario->setIdusuario($row['idusuario']);
                    $objUsuario->cargar();

                    $objRol->setIdrol($row['idrol']);
                    $objRol->cargar();

                    $objUsuarioRol->setear($objUsuario, $objRol);
                    array_push($arreglo, $objUsuarioRol);

                }
               
            }
            
        } else {
            $this->setmensajeoperacion("usuariorol->listar: ".$base->getError());
        }
 
        return $arreglo;
    }

}

?>