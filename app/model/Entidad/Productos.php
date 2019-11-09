<?php

namespace Entidad;

/**
 * Productos
 */
class Productos
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $idtipoproducto;

    /**
     * @var int
     */
    private $estado;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Productos
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set idtipoproducto.
     *
     * @param int $idtipoproducto
     *
     * @return Productos
     */
    public function setIdtipoproducto($idtipoproducto)
    {
        $this->idtipoproducto = $idtipoproducto;

        return $this;
    }

    /**
     * Get idtipoproducto.
     *
     * @return int
     */
    public function getIdtipoproducto()
    {
        return $this->idtipoproducto;
    }

    /**
     * Set estado.
     *
     * @param int $estado
     *
     * @return Productos
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado.
     *
     * @return int
     */
    public function getEstado()
    {
        return $this->estado;
    }
}
