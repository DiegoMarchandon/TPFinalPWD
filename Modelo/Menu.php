<?php

class Menu{
    
    private $idmenu;
    private $menombre;
    private $medescripcion;
    private $objPadre;
    private $medeshabilitado;
    private $mensajeoperacion;


    public function __construct(){
        $this->idmenu = 0;
        $this->menombre = '';
        $this->medescripcion = '';
        $this->objPadre = new Menu();
        $this->medeshabilitado = null;
        $this->mensajeoperacion = '';
    }

    


    /**
     * Get the value of idmenu
     */ 
    public function getIdmenu()
    {
        return $this->idmenu;
    }

    /**
     * Set the value of idmenu
     *
     */ 
    public function setIdmenu($idmenu)
    {
        $this->idmenu = $idmenu;

         
    }

    /**
     * Get the value of menombre
     */ 
    public function getMenombre()
    {
        return $this->menombre;
    }

    /**
     * Set the value of menombre
     *
     */ 
    public function setMenombre($menombre)
    {
        $this->menombre = $menombre;

         
    }

    /**
     * Get the value of medescripcion
     */ 
    public function getMedescripcion()
    {
        return $this->medescripcion;
    }

    /**
     * Set the value of medescripcion
     *
     */ 
    public function setMedescripcion($medescripcion)
    {
        $this->medescripcion = $medescripcion;

         
    }

    /**
     * Get the value of  Objpadre
     */ 
    public function getObjpadre()
    {
        return $this->objPadre;
    }

    /**
     * Set the value of idpadre
     *
     */ 
    public function setObjpadre($objPadre)
    {
        $this->objPadre = $objPadre;

         
    }

    /**
     * Get the value of medeshabilitado
     */ 
    public function getMedeshabilitado()
    {
        return $this->medeshabilitado;
    }

    /**
     * Set the value of medeshabilitado
     *
     */ 
    public function setMedeshabilitado($medeshabilitado)
    {
        $this->medeshabilitado = $medeshabilitado;

         
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
     */ 
    public function setMensajeoperacion($mensajeoperacion)
    {
        $this->mensajeoperacion = $mensajeoperacion;

         
    }


    public function setear($idmenu,$menombre,$medescripcion,$objPadre,$medeshabilitado){
        $this->setIdmenu($idmenu);
        $this->setMenombre($menombre);
        $this->setMedescripcion($medescripcion);
        $this->setObjpadre($objPadre);
        $this->setMedeshabilitado($medeshabilitado);
    }

    public function cargar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="SELECT * FROM menu WHERE idmenu = ".$this->getIdmenu();
      //  echo $sql;
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if($res>-1){
                if($res>0){
                    $row = $base->Registro();
                    
                    $objMenuPadre = new Menu();
                    $objMenuPadre->setIdmenu($row['idpadre']);
                    $objMenuPadre->cargar();


                    $this->setear($row['idmenu'], $row['menombre'],$row['medescripcion'],$objMenuPadre,$row['medeshabilitado']);    
                }
            }
        } else {
            $this->setmensajeoperacion("menu->cargar: ".$base->getError()[2]);
        }
        return $resp;
    }

    public function insertar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="INSERT INTO menu(menombre,medescripcion,idpadre,medeshabilitado) VALUES('".$this->getMenombre()."','".$this->getMedescripcion()."',".$this->getObjpadre()->getIdmenu().",'".$this->getMedeshabilitado()."');"; 
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("menu->insertar: ".$base->getError());
            }
        } else {
            $this->setmensajeoperacion("menu->insertar: ".$base->getError());
        }
        return $resp;
    }

    public function modificar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="UPDATE menu SET menombre ='".$this->getMenombre()."', medescripcion= '".$this->getMedescripcion()."', idpadre= '".$this->getObjpadre()->getIdmenu()."', medeshabilitado= ".$this->getMedeshabilitado()." WHERE idmenu= ".$this->getIdmenu().";";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("menu->modificar: ".$base->getError());
            }
        } else {
            $this->setmensajeoperacion("menu->modificar: ".$base->getError());
        }
        return $resp;
    }

    public function eliminar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="DELETE FROM menu WHERE idmenu =".$this->getIdmenu();
       // echo $sql;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("menu->eliminar: ".$base->getError());
            }
        } else {
            $this->setmensajeoperacion("menu->eliminar: ".$base->getError());
        }
        return $resp;
    }

    public function listar($parametro=""){
        $arreglo = array();
        $base=new BaseDatos();
        $sql="SELECT * FROM menu ";
     //   echo $sql;
        if ($parametro!="") {
            $sql.='WHERE '.$parametro;
        }
        if($base->iniciar()){
            $res = $base->Ejecutar($sql);
            if($res>-1){
                if($res>0){
                    
                    while ($row = $base->Registro()){
    
                        $objMenu = new Menu();
                        
                        $objMenu->setear($row['idmenu'], $row['menombre'],$row['medescripcion'],$row['idpadre'],$row['medeshabilitado']); 
                        array_push($arreglo, $objMenu);
                    }
                    
                }
                
            } else {
                $this->setmensajeoperacion("menu->listar: ".$base->getError());
            }
        }
        
        return $arreglo;
    }

    /**
     * tostring del objeto Menu
     *
     * @return string
     */
    public function __toString() {
        return "ID Menu: " . $this->getIdmenu() . 
               ", Nombre: " . $this->getMenombre() . 
               ", Descripción: " . $this->getMedescripcion() . 
               ", ID Padre: " . $this->getObjpadre()->getIdmenu() . 
               ", Deshabilitado: " . $this->getMedeshabilitado();
    }

}

?>