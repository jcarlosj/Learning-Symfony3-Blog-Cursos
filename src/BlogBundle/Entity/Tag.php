<?php

namespace BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
/**
 * Tag
 */
class Tag
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    # Definimos un atributo para la nueva relación One-To-Many
    protected $entryTag;

    # Definimos un Array o Colección de Objetos usando la clase 'ArrayCollection ' de Doctrine
    # dentro del constructor de la clase
    public function __construct() {
      $this -> entryTag = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Tag
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Tag
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    # ---> Getter y Setter de 'entry'

    # Fijar EntryTag de la Entrada
    public function addEntryTag( \BlogBundle\Entity\EntryTag $entryTag ) {
      $this -> entryTag[] = $entryTag;

      return $this;
    }

    # Obtener las Entradas de cada Tag
    public function getEntryTag() {

      return $this -> entryTag;
    }

}
