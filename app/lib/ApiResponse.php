<?php


namespace App\Lib;


use Entidad\Errores_model;

class ApiResponse {
    private $codigo;
    private $mensaje;
    private $respuesta;
    private $rta;

    public function __construct($respuesta =array(),$codigo=false ){
        $this->respuesta=$respuesta;
        if($codigo){
            $this->setCodigo($codigo);
        }
        $this->setRta();
    }

    /**
     * @param bool $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        $this->setMensaje();
        $this->setRta();
    }

    /**
     * @param mixed $mensaje
     */

    private function setMensaje(){
        $errores = new Errores_model();
        $this->mensaje = $errores->GetMensaje($this->codigo);
    }

    /**
     * @return mixed
     */
    public function getRta(){
        $this->setRta();
        return $this->rta;
    }

    /**
     * @param array $respuesta
     */
    public function setRespuesta($respuesta)
    {
        $this->respuesta = $respuesta;
        $this->setRta();
    }

    /**
     * @param mixed $rta
     */
    private function setRta(){
        $this->rta = array(
            'codigo'  => $this->codigo,
            'mensaje' => $this->mensaje,
            'respuesta' => $this->respuesta
        );
    }


}