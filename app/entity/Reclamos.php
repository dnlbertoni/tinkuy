<?php


namespace Entidad;


use Doctrine\ORM\Mapping as ORM;

/**
 * Reclamos
 *
 * @ORM\Table(name="reclamos")
 * @ORM\Entity
 */

class Reclamos
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="fechaini", type="datetime")
     */
    private $fechaini;

    /**
     * @ORM\Column(name="apelnom", type="string", length=255)
     */
    private $apelnom;
    /**
     * @ORM\Column(name="correoelectronico", type="string", length=255)
     */
    private $correoelectronico;

    /**
     * @ORM\Column(name="telefono", type="string", length=255)
     */
    private $telefono;

    /**
     * @ORM\Column(name="tipoprod", type="integer")
     */
    private $tipoprod;

    /**
     * @ORM\Column(name="idproducto", type="integer")
     */
    private $idproducto;

    /**
     * @ORM\Column(name="lote", type="string", length=255)
     */
    private $lote;

    /**
     * @ORM\Column(name="fechavto", type="datetime")
     */
    private $fechavto;

    /**
     * @ORM\Column(name="tiporeclamo", type="integer")
     */
    private $tiporeclamo;

    /**
     * @ORM\Column(name="provincia", type="string", length=255)
     */
    private $provincia;

    /**
     * @ORM\Column(name="idlugarcompra", type="integer")
     */
    private $idlugarcompra;

    /**
     * @ORM\Column(name="comentario", type="text", length=4000, nullable=true)
     */
    private $comentario;

    /**
     * @ORM\Column(name="idorigen", type="integer")
     */
    private $idorigen;

    /**
     * @ORM\Column(name="dictamen", type="string", length=255, nullable=true)
     */
    private $dictamen;

    /**
     * @ORM\Column(name="dictamen_final", type="string", length=255, nullable=true)
     */
    private $dictamen_final;

    /**
     * @ORM\Column(name="contacto_cliente",type="string", length=255, nullable=true)
     */
    private $contacto_cliente;

    /**
     * @ORM\Column(name="nrotrack", type="string", length=255, nullable=true)
     */
    private $nrotrack;

    /**
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
    private $updated;

    /**
     * @ORM\Column(name="fechafin", type="datetime", nullable=true)
     */
    private $fechafin;

    /**
     * @ORM\Column(name="idusuario", type="integer", nullable=true)
     */
    private $idusuario;

    /**
     * @ORM\Column(name="estado", type="integer", length=5 )
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