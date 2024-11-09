<?php
class Producto{
    
    private $idproducto;
    private $pronombre;
    private $prodetalle;
    private $procantstock;
    private $mensajeoperacion;


    public function __construct(){
        $this->idproducto = 0;
        $this->pronombre = '';
        $this->prodetalle = '';
        $this->procantstock = 0;

    }
    

    /**
     * obtener el valor de idproducto
     */ 
    public function getIdproducto()
    {
        return $this->idproducto;
    }

    /**
     * enviar el valor de idproducto
     *
     */ 
    public function setIdproducto($idproducto)
    {
        $this->idproducto = $idproducto;

        
    }

    /**
     * obtener el valor de pronombre
     */ 
    public function getPronombre()
    {
        return $this->pronombre;
    }

    /**
     * enviar el valor de pronombre
     *
     */ 
    public function setPronombre($pronombre)
    {
        $this->pronombre = $pronombre;

        
    }

    /**
     * obtener el valor de prodetalle
     */ 
    public function getProdetalle()
    {
        return $this->prodetalle;
    }

    /**
     * enviar el valor de prodetalle
     *
     */ 
    public function setProdetalle($prodetalle)
    {
        $this->prodetalle = $prodetalle;

        
    }

    /**
     * obtener el valor de procantstock
     */ 
    public function getProcantstock()
    {
        return $this->procantstock;
    }

    /**
     * enviar el valor de procantstock
     *
     */ 
    public function setProcantstock($procantstock)
    {
        $this->procantstock = $procantstock;

        
    }

    /**
     * obtener el valor de mensajeoperacion
     */ 
    public function getMensajeoperacion()
    {
        return $this->mensajeoperacion;
    }

    /**
     * enviar el valor de mensajeoperacion
     *
     */ 
    public function setMensajeoperacion($mensajeoperacion)
    {
        $this->mensajeoperacion = $mensajeoperacion;

        
    }


    public function setear($idproducto,$pronombre,$prodetalle,$procantstock){
        $this->setIdproducto($idproducto);
        $this->setPronombre($pronombre);
        $this->setProdetalle($prodetalle);
        $this->setProcantstock($procantstock);
    }

    public function cargar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="SELECT * FROM producto WHERE idproducto = ".$this->getIdproducto();
      //  echo $sql;
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if($res>-1){
                if($res>0){
                    $row = $base->Registro();

                    $this->setear($row['idproducto'], $row['pronombre'],$row['prodetalle'],$row['procantstock']); 
                    
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
        $sql="INSERT INTO producto(pronombre,prodetalle,procantstock)  VALUES('".$this->getPronombre()."','".$this->getProdetalle()."',".$this->getProcantstock().");"; 
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("producto->insertar: ".$base->getError());
            }
        } else {
            $this->setmensajeoperacion("producto->insertar: ".$base->getError());
        }
        return $resp;
    }

    public function modificar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="UPDATE producto SET pronombre ='".$this->getPronombre()."', prodetalle= '".$this->getProdetalle()."', procantstock=".$this->getProcantstock()." WHERE idproducto= ".$this->getIdproducto().";";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("producto->modificar: ".$base->getError());
            }
        } else {
            $this->setmensajeoperacion("producto->modificar: ".$base->getError());
        }
        return $resp;
    }

    public function eliminar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="DELETE FROM producto WHERE idproducto =".$this->getIdproducto();
       // echo $sql;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("producto->eliminar: ".$base->getError());
            }
        } else {
            $this->setmensajeoperacion("producto->eliminar: ".$base->getError());
        }
        return $resp;
    }

    public function listar($parametro=""){
        $arreglo = array();
        $base=new BaseDatos();
        $sql="SELECT * FROM producto ";
     //   echo $sql;
        if ($parametro!="") {
            $sql.='WHERE '.$parametro;
        }
        if($base->iniciar()){
            $res = $base->Ejecutar($sql);
            if($res>-1){
                if($res>0){
                    
                    while ($row = $base->Registro()){
    
                        $objProducto = new Producto();
                        
                        $objProducto->setear($row['idproducto'], $row['pronombre'],$row['prodetalle'],$row['procantstock']); 
                        array_push($arreglo, $objProducto);
                    }
                    
                }
                
            } else {
                $this->setmensajeoperacion("producto->listar: ".$base->getError());
            }
        }
        
        return $arreglo;
    }

    /**
     * to string del objeto Producto
     *
     * @return string
     */
    public function __toString() {
        return "ID Producto: " . $this->getIdproducto() . 
               ", Nombre: " . $this->getPronombre() . 
               ", Detalle: " . $this->getProdetalle() . 
               ", Cantidad en Stock: " . $this->getProcantstock();
    }

}
?>