<?php


namespace Entidad;


use Doctrine\ORM\Mapping as ORM;

/**
 * Estados
 *
 * @ORM\Table(name="estados")
 * @ORM\Entity
 */

class Estados
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(name="idmaquinaestado", type="integer")
     */
    private $idmauinaestado;

    /**
     * @ORM\Column(name="idevento", type="integer")
     */
    private $idevento;

    /**
     * @ORM\Column(name="aplica", type="integer")
     */
    private $aplica;


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