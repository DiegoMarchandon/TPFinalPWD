<?php

class Usuario{

    private $idusuario;
    private $usnombre;
    private $uspass;
    private $usmail;
    private $usdeshabilitado;
    private $mensajeoperacion;

    

    /**
     * Get the value of idusuario
     */ 
    public function getIdusuario()
    {
        return $this->idusuario;
    }

    /**
     * Set the value of idusuario
     *
     * @return  self
     */ 
    public function setIdusuario($idusuario)
    {
        $this->idusuario = $idusuario;

        
    }

    /**
     * Get the value of usnombre
     */ 
    public function getUsnombre()
    {
        return $this->usnombre;
    }

    /**
     * Set the value of usnombre
     *
     * @return  self
     */ 
    public function setUsnombre($usnombre)
    {
        $this->usnombre = $usnombre;

        
    }

    /**
     * Get the value of uspass
     */ 
    public function getUspass()
    {
        return $this->uspass;
    }

    /**
     * Set the value of uspass
     *
     * @return  self
     */ 
    public function setUspass($uspass)
    {
        $this->uspass = $uspass;

        
    }

    /**
     * Get the value of usmail
     */ 
    public function getUsmail()
    {
        return $this->usmail;
    }

    /**
     * Set the value of usmail
     *
     * @return  self
     */ 
    public function setUsmail($usmail)
    {
        $this->usmail = $usmail;

        
    }

    /**
     * Get the value of usdeshabilitado
     */ 
    public function getUsdeshabilitado()
    {
        return $this->usdeshabilitado;
    }

    /**
     * Set the value of usdeshabilitado
     *
     * @return  self
     */ 
    public function setUsdeshabilitado($usdeshabilitado)
    {
        $this->usdeshabilitado = $usdeshabilitado;

        
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

    public function __construct()
    {
        $this->idusuario = 0;
        $this->usnombre = '';
        $this->uspass = '';
        $this->usmail = '';
        $this->usdeshabilitado = null;
        $this->mensajeoperacion = '';
    }

    public function setear($idusuario,$usnombre,$uspass,$usmail,$usdeshabilitado){
        $this->setIdusuario($idusuario);
        $this->setUsnombre($usnombre);
        $this->setUspass($uspass);
        $this->setUsmail($usmail);
        $this->setUsdeshabilitado($usdeshabilitado);
    }

    public function cargar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="SELECT * FROM usuario WHERE idusuario = ".$this->getIdusuario();
      //  echo $sql;
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if($res>-1){
                if($res>0){
                    $row = $base->Registro();

                    $this->setear($row['idusuario'], $row['usnombre'],$row['uspass'],$row['usmail'],$row['usdeshabilitado']);    
                }
            }
        } else {
            $this->setmensajeoperacion("usuario->cargar: ".$base->getError()[2]);
        }
        return $resp;
    }

    public function insertar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="INSERT INTO usuario(usnombre,uspass,usmail,usdeshabilitado)  VALUES('".$this->getUsnombre()."','".$this->getUspass()."','".$this->getUsmail()."','".$this->getUsdeshabilitado()."');"; 
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("usuario->insertar: ".$base->getError());
            }
        } else {
            $this->setmensajeoperacion("usuario->insertar: ".$base->getError());
        }
        return $resp;
    }
    /*
    public function modificar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="UPDATE usuario SET usnombre ='".$this->getUsnombre()."', uspass= '".$this->getUspass()."', usmail= '".$this->getUsmail()."', usdeshabilitado= ".$this->getUsdeshabilitado()." WHERE idusuario= ".$this->getIdusuario().";";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("usuario->modificar: ".$base->getError());
            }
        } else {
            $this->setmensajeoperacion("usuario->modificar: ".$base->getError());
        }
        return $resp;
    }*/
    
public function modificar() {
    $resp = false;
    $base = new BaseDatos();
    $sql = "UPDATE usuario SET usnombre = '" . $this->getUsnombre() . "', uspass = '" . $this->getUspass() . "', usmail = '" . $this->getUsmail() . "', usdeshabilitado = '" . $this->getUsdeshabilitado() . "' WHERE idusuario = " . $this->getIdusuario() . ";";
    if ($base->Iniciar()) {
        if ($base->Ejecutar($sql)) {
            $resp = true;
        } else {
            $this->setMensajeoperacion("usuario->modificar: " . $base->getError());
        }
    } else {
        $this->setMensajeoperacion("usuario->modificar: " . $base->getError());
    }
    return $resp;
}

    public function eliminar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="DELETE FROM usuario WHERE idusuario =".$this->getIdusuario();
       // echo $sql;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("usuario->eliminar: ".$base->getError());
            }
        } else {
            $this->setmensajeoperacion("usuario->eliminar: ".$base->getError());
        }
        return $resp;
    }

    public function listar($parametro=""){
        $arreglo = array();
        $base=new BaseDatos();
        $sql="SELECT * FROM usuario ";
     //   echo $sql;
        if ($parametro!="") {
            $sql.='WHERE '.$parametro;
        }
        if($base->iniciar()){
            $res = $base->Ejecutar($sql);
            if($res>-1){
                if($res>0){
                    
                    while ($row = $base->Registro()){
    
                        $objUsuario = new Usuario();
                        
                        $objUsuario->setear($row['idusuario'], $row['usnombre'],$row['uspass'],$row['usmail'],$row['usdeshabilitado']); 
                        array_push($arreglo, $objUsuario);
                    }
                    
                }
                
            } else {
                $this->setmensajeoperacion("usuario->listar: ".$base->getError());
            }
        }
        
        return $arreglo;
    }

}
?>