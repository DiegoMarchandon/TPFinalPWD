<?php

class Rol{
    private $idrol;
    private $rodescripcion;
    private $mensajeoperacion;

    /**
     * Get the value of idrol
     */ 
    public function getIdrol()
    {
        return $this->idrol;
    }

    /**
     * Set the value of idrol
     *
     * @return  self
     */ 
    public function setIdrol($idrol)
    {
        $this->idrol = $idrol;

        
    }

    /**
     * Get the value of rodescripcion
     */ 
    public function getRodescripcion()
    {
        return $this->rodescripcion;
    }

    /**
     * Set the value of rodescripcion
     *
     * @return  self
     */ 
    public function setRodescripcion($rodescripcion)
    {
        $this->rodescripcion = $rodescripcion;

        
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
        $this->idrol = 0;
        $this->rodescripcion = '';

    }

    public function setear($idrol,$rodescripcion){
        $this->setIdrol($idrol);
        $this->setRodescripcion($rodescripcion);
    }

    public function cargar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="SELECT * FROM rol WHERE idrol = ".$this->getIdrol();
      //  echo $sql;
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if($res>-1){
                if($res>0){
                    $row = $base->Registro();

                    $this->setear($row['idrol'], $row['rodescripcion']); 
                    
                }
            }
        } else {
            $this->setmensajeoperacion("rol->cargar: ".$base->getError()[2]);
        }
        return $resp;
    }

    public function insertar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="INSERT INTO rol(rodescripcion)  VALUES('".$this->getRodescripcion()."');"; 
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("rol->insertar: ".$base->getError());
            }
        } else {
            $this->setmensajeoperacion("rol->insertar: ".$base->getError());
        }
        return $resp;
    }

    public function modificar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="UPDATE rol SET rodescripcion ='".$this->getRodescripcion()."' WHERE idrol= ".$this->getIdrol().";";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("rol->modificar: ".$base->getError());
            }
        } else {
            $this->setmensajeoperacion("rol->modificar: ".$base->getError());
        }
        return $resp;
    }

    public function eliminar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="DELETE FROM rol WHERE idrol =".$this->getIdrol();
       // echo $sql;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("rol->eliminar: ".$base->getError());
            }
        } else {
            $this->setmensajeoperacion("rol->eliminar: ".$base->getError());
        }
        return $resp;
    }

    public function listar($parametro=""){
        $arreglo = array();
        $base=new BaseDatos();
        $sql="SELECT * FROM rol ";
     //   echo $sql;
        if ($parametro!="") {
            $sql.='WHERE '.$parametro;
        }
        if($base->iniciar()){
            $res = $base->Ejecutar($sql);
            if($res>-1){
                if($res>0){
                    
                    while ($row = $base->Registro()){
    
                        $objRol = new Rol();
                        
                        $objRol->setear($row['idrol'], $row['rodescripcion']); 
                        array_push($arreglo, $objRol);
                    }
                    
                }
                
            } else {
                $this->setmensajeoperacion("rol->listar: ".$base->getError());
            }
        }
        
        return $arreglo;
    }
}


?>