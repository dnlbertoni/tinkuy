<?php


namespace Entidad;


use Doctrine\ORM\Mapping as ORM;

/**
 * Historico_Reclamos
 *
 * @ORM\Table(name="historico_reclamos")
 * @ORM\Entity
 */

class Historicoreclamos
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="idreclamo", type="integer", nullable=true )
     */
    private $idreclamo;

    /**
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @ORM\Column(name="estado_anterior", type="integer", nullable=true )
     */
    private $estado_anterior;

    /**
     * @ORM\Column(name="estado_actual", type="integer")
     */
    private $estado_actual;

    /**
     * @ORM\Column(name="idusuario", type="integer", nullable=true)
     */
    private $idusuario;


    public function __get($property)
    {

        if (property_exists($this, $property)) {
            return $this->$property;
        }

    }

    public function __set($property, $value)
    {

        if (property_exists($this, $property)) {

            $this->$property = $value;

        }
    }


}