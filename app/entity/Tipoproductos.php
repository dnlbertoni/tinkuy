<?php


namespace Entidad;


use Doctrine\ORM\Mapping as ORM;

/**
 * Tipoproductos.php
 *
 * @ORM\Table(name="tipoproductos")
 * @ORM\Entity
 */

class Tipoproductos
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(name="estado", type="integer", length=5)
     */
    private $estado;

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